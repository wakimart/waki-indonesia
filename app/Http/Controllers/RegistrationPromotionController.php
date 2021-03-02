<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\RegistrationPromotion;
use App\DeliveryOrder;
use App\Branch;
use App\Cso;
use App\User;
use Illuminate\Validation\Rule;
use Validator;
use App\RajaOngkir_City;
use App\RajaOngkir_Province;
use App\HistoryUpdate;
use App\CategoryProduct;
use DB;
use App\Utils;

class RegistrationPromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('landingWAKi');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'email' => 'required',
            'phone' => 'required|min:7',
        ]);
        if($validator->fails()){
            $arr_Errors = $validator->errors()->all();
            $arr_Keys = $validator->errors()->keys();
            $arr_Hasil = [];
            for ($i = 0; $i < count($arr_Keys); $i++) {
                $arr_Hasil[$arr_Keys[$i]] = $arr_Errors[$i];
            }
            return response()->json(['errors' => $arr_Hasil]);
        }else{
            DB::beginTransaction();
            try {
                $data = $request->all();
                $registrationPromotions = RegistrationPromotion::create($data);    
                $request->session()->put('success_registration', "1");
                DB::commit();
                
                return redirect()->route('landing_waki');
                //return redirect()->intended(route('landingWAKi'));
            } catch (\Exception $ex) {
                DB::rollback();
                return response()->json(['errors' => $ex]);
            }
        }
        
    }

    public function admin_ListRegistrationPromo(Request $request){
        $promotions = RegistrationPromotion::where('active', true);
        $countPromotions = RegistrationPromotion::where('active', true)->count();

        if($request->has('search')){
            $promotions = $promotions->where( 'first_name', 'LIKE', '%'.$request->search.'%' )
                                    ->orWhere( 'last_name', 'LIKE', '%'.$request->search.'%' )
                                    ->orWhere( 'phone', 'LIKE', '%'.$request->search.'%' )
                                    ->orWhere( 'email', 'LIKE', '%'.$request->search.'%' );
        }
        $promotions = $promotions->paginate(10);
        return view('admin.list_registrationpromotion', compact('promotions', 'countPromotions'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
