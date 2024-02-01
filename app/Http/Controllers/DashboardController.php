<?php

namespace App\Http\Controllers;

use App\AbsentOff;
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
use App\Branch;
use App\Cso;

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

        $order = Order::select(DB::raw("SUM(down_payment) AS total_payment"))
        ->whereBetween("orderDate", [$startMonth, $endMonth])
        ->where("active", true)
        ->whereIn('status', ['process', 'delivery', 'success'])
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

        //khusus untuk acc reschedule & delete HS
        $accRescheduleHS = HomeService::where([['active', true], ['is_acc_resc', true]])->orderBy("updated_at", "desc")->get();
        $accDeleteHS = HomeService::where([['active', true], ['is_acc', true]])->orderBy("updated_at", "desc")->get();

        $absentOffs["supervisor"] = AbsentOff::where('status', AbsentOff::$status['1'])
            ->whereNull('supervisor_id')->orderBy('created_at', 'desc')->get();
        $absentOffs["coordinator"] = AbsentOff::where('status', AbsentOff::$status['1'])
            ->whereNull('coordinator_id')->orderBy('created_at', 'desc')->get();

        $ordersOutsideRegion = Order::where('active', true)->whereNotNull('request_hs_acc')->get();

        // rank
        $branches = Branch::where('active', true)->orderBy('code', 'asc')->get();
        $nowDay = date('j');
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-d');
        if($nowDay == 1){
            $startDate = date("Y-m-01",strtotime("-1 month"));
            $endDate = date("Y-m-d",strtotime('last day of previous month'));
        }

        // cso
        $query_rank_by_cso = "SELECT SUM(op.total_payment) 
            FROM order_payments as op
            LEFT JOIN orders as o
            ON o.id = op.order_id
            WHERE o.cso_id = c.id
            AND op.payment_date >= '$startDate'
            AND op.payment_date <= '$endDate'
            AND op.status = 'verified'
            AND (o.status = '" . Order::$status['2'] . "'
            OR o.status = '" . Order::$status['3'] . "' 
            OR o.status = '" . Order::$status['8'] . "'
            OR o.status = '" . Order::$status['4'] . "')";
        $rank_by_cso = Cso::from('csos as c')
            ->select('c.code', 'c.name')
            ->selectRaw("($query_rank_by_cso) as total_sale")
            ->where('active', true)
            ->orderBy('total_sale', 'desc');
        $rank_by_cso_first_part = $rank_by_cso->skip(0)->take(5)->get();
        $rank_by_cso_last_part = $rank_by_cso->skip(5)->take(5)->get();
        // branch
        $query_rank_by_branch = "SELECT SUM(op.total_payment) 
            FROM order_payments as op
            LEFT JOIN orders as o
            ON o.id = op.order_id
            WHERE o.branch_id = b.id
            AND op.payment_date >= '$startDate'
            AND op.payment_date <= '$endDate'
            AND op.status = 'verified'
            AND (o.status = '" . Order::$status['2'] . "'
            OR o.status = '" . Order::$status['3'] . "' 
            OR o.status = '" . Order::$status['8'] . "'
            OR o.status = '" . Order::$status['4'] . "')";
        $rank_by_branch = Branch::from('branches as b')
            ->select('b.code', 'b.name')
            ->selectRaw("($query_rank_by_branch) as total_sale")
            ->where('active', true)
            ->orderBy('total_sale', 'DESC')
            ->skip(0)
            ->take(5)
            ->get();

        return view(
            "admin.dashboard",
            compact(
                "homeServiceToday",
                "order",
                "registration",
                "references",
                "refSouvenirs",
                "accRescheduleHS",
                "accDeleteHS",
                "absentOffs",
                "ordersOutsideRegion",
                "rank_by_cso_first_part",
                "rank_by_cso_last_part",
                "rank_by_branch"
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
