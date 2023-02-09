<?php

namespace App\Http\Controllers;

use App\Branch;
use App\StockOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOrderRequestController extends Controller
{
    /**
     * Insert Stock Order Request from Change Status Order (Stock Request Pending)
     * @param int $order_id
     * @param array $products_id
     * @param array $quantities (Quantity based $products_id index)
     */
    public static function inserStockOrderRequest($order_id, $products_id, $quantities)
    {
        DB::beginTransaction();
        try {
            $stockOrderRequest = new StockOrderRequest();
            $stockOrderRequest->order_id = $order_id;
            $stockOrderRequest->status = StockOrderRequest::$status['1']; // pending
            $arr_product_qty = [];
            foreach ($products_id as $idx => $product) {
                $arr_product_qty[] = [
                    'product_id' => $product,
                    'qty' => $quantities[$idx],
                ];
            }
            $stockOrderRequest->product_qty = json_encode($arr_product_qty);
            $stockOrderRequest->save();

            DB::commit();
            return response()->json(['success' => 'Berhasil!']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['error' =>  $ex->getMessage(), 500]);
        }
    }

    public function index(Request $request)
    {
        $branches = Branch::Where('active', true)->orderBy("code", 'asc')->get();
        $stockOrderRequests = StockOrderRequest::with('order');
        
        if ($request->filter_branch) {
            $stockOrderRequests->whereHas('order', function($query) use($request) {
                $query->where('branch_id', $request->filter_branch);
            });
        }
        if ($request->search) {
            $stockOrderRequests->whereHas('order', function($query) use($request) {
                $query->where('code', 'like', '%'.$request->search.'%')
                    ->orWhere('temp_no', 'like', '%'.$request->search.'%');
            });
        }

        $stockOrderRequests = $stockOrderRequests->orderBy('id', 'desc')
            ->paginate(15);
        return view('admin.list_stock_order_request', compact('branches', 'stockOrderRequests'));
    }
}
