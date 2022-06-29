<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use \Illuminate\Http\Request;
use App\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/cms-admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'logoutUser']);
    }

    public function logoutUser()
    {
        Auth::guard('web')->logout(); //default - web. Users table
        return redirect('/login');
    }

    public function username()
    {
        return 'username';
    }

    public function redirectTo()
    {
        return $this->redirectTo;
    }

    public function login(Request $request) {
        $this->validateLogin($request);
        
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // This section is the only change
        if ($this->guard()->validate($this->credentials($request))) {
            $user = $this->guard()->getLastAttempted();

            // Make sure the user is active
            if ($user->active && $this->attemptLogin($request)) {

                // Make sure the user agreement is true






                // Send the normal successful login response
                return $this->sendLoginResponse($request);
            } else {
                // Increment the failed login attempts and redirect back to the
                // login form with an error message.
                $this->incrementLoginAttempts($request);
                return redirect()
                    ->back()
                    ->withInput($request->only($this->username(), 'remember'))
                    ->withErrors(['username' => 'You must be active to login.']);
            }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }
    
    //KHUSUS API REST
    public function loginApi(Request $request)
    {
        $messages = array(
                'username.exists' => 'The username is invalid.',
        );

        $validator = \Validator::make($request->all(), [
            'username' => ['required', 'exists:users,username'],
            'password' => 'required|min:4'
        ], $messages);

        if ($validator->fails()){
            $data = ['result' => 0,
                     'data' => $validator->errors()
                    ];
            return response()->json($data, 401);
        }
        else{
            $user = User::Where('username', strtoupper($request->username))->first();
            $user->roles;
            $user->cso;
        
            if(Hash::check($request->password, $user->password)){
                if ($user->active) {
                    //update FMC token
                    $token = array();
                    if ($user->fmc_token == null){
                        $user->fmc_token = array();
                    }
                    $token = $user->fmc_token;
                    array_push($token, $request->fcm_token);
                    $user->fmc_token = $token;
                    $user->save();           
                    $user['list_branches'] = $user->listBranches();
                    $data = ['result' => 1,
                            'data' => $user
                            ];
                    return response()->json($data,200);
                } else {
                    $err = ['username' => ["You must be active to login."]];
                    $data = ['result' => 0,
                            'data' => $err
                            ];
                    return response()->json($data,401);
                }
            }
            
            $err = ['password' => ["The password is invalid."]];
            $data = ['result' => 0,
                     'data' => $err
                    ];
            return response()->json($data,401);
        }
    }



    public function logoutApi(Request $request)
    {
        $messages = array(
                'username.exists' => 'The username is invalid.',
        );

        $validator = \Validator::make($request->all(), [
            'username' => ['required', 'exists:users,username'],
            'fcm_token' => 'required'
        ], $messages);

        if ($validator->fails()){
            $data = ['result' => 0,
                     'data' => $validator->errors()
                    ];
            return response()->json($data, 401);
        }
        else{
            $user = User::Where('username', strtoupper($request->username))->first();
            $tmpToken = [];
            if($user != null && $user->fmc_token != null){
                foreach ($user->fmc_token as $token){
                    if($token != $request->fcm_token && $token != null){
                        array_push($tmpToken, $token);
                    }
                }
                $user->fmc_token = $tmpToken;
                $user->save();
                $data = ['result' => 1,
                             'data' => $user
                            ];
                return response()->json($data,200);
                
            }
            $data = ['result' => 0,
                     'data' => 'user not found'
                    ];
            return response()->json($data,401);
        }
    }
    
    public function loginQRApi(Request $request){
        $user = User::where('qrcode', $request['hash'])->first();
        if ($user->active) {
            $user->roles;
            $user->cso;
            $token = array();
            
            if ($user->fmc_token == null){
                $user->fmc_token = array();
            }
            $token = $user->fmc_token;
            array_push($token, $request->fcm_token);
            $user->fmc_token = $token;
            $user->save();           
            $user['list_branches'] = $user->listBranches();

            if($user != null){
                $data = ['result' => 1,
                        'data' => $user
                        ];
                return response()->json($data,200);
            }
        } else {
            $err = ['username' => ["You must be active to login."]];
            $data = ['result' => 0,
                    'data' => $err
                    ];
            return response()->json($data,401);
        }
    }
}
