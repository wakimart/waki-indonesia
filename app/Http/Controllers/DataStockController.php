<?php

namespace App\Http\Controllers;

use App\Imports\DataStockImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DataStockController extends Controller
{
    public function importDataStock(Request $request)
    {
        return view('admin.import_datastock');
    }

    public function storeImportDataStock(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'to_warehouse_id' => 'required',
            'file' => 'required|mimes:csv,txt',
        ]);
        if($validator->fails()){
            $arr_Errors = $validator->errors()->all();
            $arr_Keys = $validator->errors()->keys();
            $arr_Hasil = [];
            for ($i = 0; $i < count($arr_Keys); $i++) {
                $arr_Hasil[$arr_Keys[$i]] = $arr_Errors[$i];
            }
            return response()->json(['errors' => $arr_Hasil]);
        } else {
            DB::beginTransaction();
            try {
                $file = $request->file('file');

                Excel::import(new DataStockImport($request->to_warehouse_id), $file);
                DB::commit();

                return response()->json(['success' => 'Berhasil']);
            } catch (Exception $e) {
                DB::rollback();

                return response()->json([
                    "error" => $e->getMessage(),
                ], 500);                
            }
        }
    }
}
