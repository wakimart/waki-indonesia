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

    /**
     * add promo registration in admin
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function admin_AddRegistrationPromo()
    {
        return view('admin.add_regist_event');
    }
    
    /**
     * store promo registration in admin
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function admin_StoreRegistrationPromo(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'email' => 'required',
            'phone' => 'required|min:7',
        ]);
        
        DB::beginTransaction();
        try {
            $data = $request->all();
            $registrationPromotions = RegistrationPromotion::create($data);    
            $request->session()->put('success_registration', "1");
            DB::commit();
            
            return redirect()->route('list_regispromo')->with("success", "Successfully added.");
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->route("add_regispromo")->with("error", $ex);
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
     * detail promo registration in admin
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function admin_DetailRegistrationPromo($id)
    {
        $registration_promotion = RegistrationPromotion::find($id);
        return view('admin.detail_regist_event', compact('registration_promotion'));
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
     * edit promo registration in admin
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function admin_EditRegistrationPromo($id)
    {
        $registration_promotion = RegistrationPromotion::find($id);
        return view('admin.update_regist_event', compact('registration_promotion'));
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
     * update promo registration in admin
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function admin_UpdateRegistrationPromo(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'email' => 'required',
            'phone' => 'required|min:7',
        ]);
        
        DB::beginTransaction();
        try {
            $registrationPromotions = RegistrationPromotion::find($id);   
            $registrationPromotions->first_name = $request->first_name; 
            $registrationPromotions->last_name = $request->last_name; 
            $registrationPromotions->address = $request->address; 
            $registrationPromotions->email = $request->email; 
            $registrationPromotions->phone = $request->phone; 
            $registrationPromotions->update();
            $request->session()->put('success_registration', "1");
            DB::commit();
            
            return redirect()->route('list_regispromo')->with("success", "Successfully updated.");
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->route("update_regispromo")->with("error", $ex);
        }
        
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

    /**
     * delete promo registration in admin
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function admin_DeleteRegistrationPromo($id)
    {
        DB::beginTransaction();

        if (!empty($id)) {
            try {
                $registration_promotion = RegistrationPromotion::find($id);
                $registration_promotion->active = false;
                $registration_promotion->update();

                DB::commit();

                return redirect()
                    ->route("list_regispromo")
                    ->with("success", "Successfully deleted!");
            } catch (Exception $e) {
                DB::rollback();
                return redirect()->route("list_regispromo")->with("error", "Something wrong, please contact IT");
            }
        }

        return response()->json(["result" => "Data not found."], 400);
    }
}
