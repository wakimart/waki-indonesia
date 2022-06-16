<?php

namespace App\Http\Controllers;

use App\HomeService;
use App\Order;
use App\RegistrationPromotion;
use App\Role;
use App\User;
use App\PersonalHomecare;
use App\ReferenceSouvenir;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // Update role user yg baru
            $role = Role::where('slug', Auth::user()->roles[0]['slug'])->first();
            $users = User::find(Auth::user()->id);

            $arr_role = (array) json_decode($role->permissions);
            $arr_user_role = (array) json_decode($users->permissions);
            $role_key = array_keys($arr_role);
            $user_role_key = array_keys($arr_user_role);

            $check_permission = array_diff($role_key, $user_role_key);
            if (count($check_permission) > 0) {
                foreach ($check_permission as $key => $val) {
                    $arr_user_role[$val] = $arr_role[$val];
                }
                $users->permissions = json_encode($arr_user_role);
                $users->save();
            }
        } catch (Exception $e) {
            unset($e);
            return redirect()->route("login");
        }

        // Bulan ini
        $startMonth = date("Y-m-01");
        $endMonth = date("Y-m-t");

        // Hari ini
        $startToday = date("Y-m-d 00:00:00");
        $endToday = date("Y-m-d 23:59:59");

        $order = Order::select(DB::raw("SUM(total_payment) AS total_payment"))
        ->whereBetween("orderDate", [$startMonth, $endMonth])
        ->where("active", true)
        ->first();

        $homeServiceToday = HomeService::select(DB::raw("COUNT(id) AS count"))
        ->whereBetween("appointment", [$startToday, $endToday])
        ->where("active", true)
        ->first();

        $registration = RegistrationPromotion::select(
            DB::raw("COUNT(id) AS count")
        )
        ->whereBetween("created_at", [$startMonth, $endMonth])
        ->where("active", true)
        ->first();

        //Khusus untuk Submission reference need to review
        $references = ReferenceSouvenir::where(function ($q){
                        $q->whereJsonLength('reference_souvenirs.link_hs', '>=', 3)->where('reference_souvenirs.status', 'pending');
                    })->orWhere(function ($q){
                        $q->where([['reference_souvenirs.order_id', '!=', null], ['reference_souvenirs.status_prize', 'pending'], ['orders.total_payment', '>=', 20000000]]);
                    })
                    ->leftJoin("orders", "reference_souvenirs.order_id", "=", "orders.id")
                    ->select('reference_souvenirs.*')
                    ->get();

        //khusus untuk reference souvenir need to acc
        $refSouvenirs = ReferenceSouvenir::where('is_acc', true)->get();

        //khusus untuk personal homecare to acc
        $personalHomecares = [];
        $personalHomecares['new'] = PersonalHomecare::where([['active', true], ['status', 'new']])
                        ->orderBy("updated_at", "desc")
                        ->get();
        $personalHomecares['verified'] = PersonalHomecare::where([['active', true], ['status', 'verified']])
                        ->orderBy("updated_at", "desc")
                        ->get();
        $personalHomecares['waiting_in'] = PersonalHomecare::where([['active', true], ['status', 'waiting_in']])
                        ->orderBy("updated_at", "desc")
                        ->get();
        $personalHomecares['reschedule_acc'] = PersonalHomecare::where('active', true)
                        ->whereNotNull('reschedule_date')
                        ->orderBy("updated_at", "desc")
                        ->get();
        $personalHomecares['extend_acc'] = PersonalHomecare::where([['active', true], ['is_extend', true]])
                        ->orderBy("updated_at", "desc")
                        ->get();
        $personalHomecares['cancel_acc'] = PersonalHomecare::where([['active', true], ['is_cancel', true]])
                        ->orderBy("updated_at", "desc")
                        ->get();


        // $personalHomecares = PersonalHomecare::where('active', true)
        //                 ->whereIn('status', ['new', 'waiting_in', 'verified'])
        //                 ->orWhere(function ($q){
        //                     $q->whereNotNull('reschedule_date');
        //                 })
        //                 ->orWhere(function ($q){
        //                     $q->where('is_extend', true);
        //                 })
        //                 ->orderBy("updated_at", "desc")
        //                 ->get();

        //khusus untuk acc delete HS
        $accDeleteHS = HomeService::where([['active', true], ['is_acc', true]])->orderBy("updated_at", "desc")->get();

        return view(
            "admin.dashboard",
            compact(
                "homeServiceToday",
                "order",
                "registration",
                "references",
                "refSouvenirs",
                "personalHomecares",
                "accDeleteHS"
            )
        );
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
        //
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

    public function countHS() {
        $startMonth = date("Y-m-01 00:00:00");
        $endMonth = date("Y-m-t 23:59:59");

        $homeServiceMonth = HomeService::select(
            DB::raw("DAYOFMONTH(appointment) AS appointment_date"),
            DB::raw("COUNT(id) AS data_count"),
        )
        ->whereBetween("appointment", [$startMonth, $endMonth])
        ->where("active", true)
        ->groupBy("appointment_date")
        ->orderBy("appointment_date", "asc")
        ->get();

        return response()->json([
            "result" => 1,
            "data" => $homeServiceMonth,
        ]);
    }
}
