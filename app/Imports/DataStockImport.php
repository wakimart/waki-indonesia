<?php

namespace App\Imports;

use App\Http\Controllers\StockInOutController;
use App\Product;
use App\Stock;
use App\StockInOut;
use App\StockInOutProduct;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataStockImport implements ToCollection, WithHeadingRow
{
    protected $to_warehouse_id = null;
    protected $from_warehouse_id = null;
    protected $date = null;
    protected $type = null;
    
    public function __construct($to_warehouse_id)
    {
        $this->to_warehouse_id = $to_warehouse_id;
        $this->from_warehouse_id = 2;
        $this->date = date('Y-m-d');
        $this->type = "in";
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $from_warehouse = Warehouse::find($this->from_warehouse_id);

        // Generate Code
        $getGenerateCode = (new StockInOutController)->generateCode(new Request([
            'type' => $this->type,
            'date' => $this->date,
            'warehouse_type' => $from_warehouse->type,
        ]));
        $code = json_decode($getGenerateCode->getContent(), true)['data'];

        $stockInOut = new StockInOut();
        $stockInOut->warehouse_from_id = $this->from_warehouse_id;
        $stockInOut->warehouse_to_id = $this->to_warehouse_id;
        $stockInOut->code = $code;
        $stockInOut->date = $this->date;
        $stockInOut->type = $this->type;
        $stockInOut->user_id = Auth::user()->id;
        $stockInOut->save();

        $total_product = 0;
        foreach ($collection as $coll) {
            $product = Product::where('code', $coll['kode'])->first();
            if ($product) {
                $stock_to = Stock::where("warehouse_id", $this->to_warehouse_id)
                    ->where("product_id", $product->id)
                    ->where("type_warehouse", null)
                    ->first();

                if (empty($stock_to)) {
                    $stock_to = new Stock();
                    $stock_to->warehouse_id = $this->to_warehouse_id;
                    $stock_to->product_id = $product->id;
                    $stock_to->quantity = 0;
                    $stock_to->save();
                }

                if ($stock_to->quantity != $coll['balance']) {
                    $total_product++;
                    $stock_from = Stock::where("warehouse_id", $this->from_warehouse_id)
                        ->where("product_id", $product->id)
                        ->where("type_warehouse", null)
                        ->first();

                    if (empty($stock_from)) {
                        $stock_from = new Stock();
                        $stock_from->warehouse_id = $this->from_warehouse_id;
                        $stock_from->product_id = $product->id;
                        $stock_from->quantity = 0;
                        $stock_from->save();
                    }

                    $diff_qty = $coll['balance'] - $stock_to->quantity;

                    $stock_to->quantity += $diff_qty;
                    $stock_to->save();

                    $stockInOutProduct = new StockInOutProduct();
                    $stockInOutProduct->stock_in_out_id = $stockInOut->id;
                    $stockInOutProduct->stock_from_id = $stock_from->id;
                    $stockInOutProduct->stock_to_id = $stock_to->id;
                    $stockInOutProduct->product_id = $product->id;
                    $stockInOutProduct->quantity = $diff_qty;
                    $stockInOutProduct->save();
                }
            }
        }

        if ($total_product == 0) {
            $stockInOut->active = false;
            $stockInOut->save();
        }
    }
}
