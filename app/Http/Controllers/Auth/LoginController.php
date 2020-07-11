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

            if($this->attemptLogin($request)){
                // Send the normal successful login response
                return $this->sendLoginResponse($request);
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
        
            if(Hash::check($request->password, $user->password)){              
                $data = ['result' => 1,
                         'data' => $user
                        ];
                return response()->json($data,200);
            }
            
            $err = ['password' => ["The password is invalid."]];
            $data = ['result' => 0,
                     'data' => $err
                    ];
            return response()->json($data,401);
        }
    }
}
