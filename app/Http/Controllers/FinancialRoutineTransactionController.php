<?php

namespace App\Http\Controllers;

use App\FinancialRoutine;
use App\FinancialRoutineTransaction;
use App\HistoryUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinancialRoutineTransactionController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $frTransaction = new FinancialRoutineTransaction();
            $frTransaction->transaction_date = $request->input('tdetail_date');
            $frTransaction->bank_account_id = $request->input('tdetail_bank');
            $frTransaction->transaction = $request->input('tdetail_nominal');
            $frTransaction->description = $request->input('tdetail_description');
            $frTransaction->financial_routine_id = $request->input('financial_routine_id');
            $frTransaction->user_id = $user->id;
            $frTransaction->save();

            $financialRoutine = FinancialRoutine::find($request->financial_routine_id);
            $financialRoutine->updateRemainsSaldo();
            $financialRoutine->save();

            $historyUpdate['type_menu'] = "Financial Routine";
            $historyUpdate['method'] = "Update";
            $historyUpdate['meta'] = json_encode([
                'user'=>$user['id'],
                'createdAt' => date("Y-m-d h:i:s"),
                'dataChange'=> ["Add Financial Routine Transaction" => $frTransaction]
            ]);

            $historyUpdate['user_id'] = $user['id'];
            $historyUpdate['menu_id'] = $frTransaction['financial_routine_id'];
            $createData = HistoryUpdate::create($historyUpdate);

            DB::commit();
            return redirect()->back()->with('success', 'Petty Cash Berhasil Di Tambah');
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["errors" => $ex->getMessage()], 500);
        }
    }

    public function edit(Request $request)
    {
        if($request->has('financial_routine_id') && $request->has('fr_transaction_id')){
            $frTransaction = FinancialRoutineTransaction::where('financial_routine_id', $request->get('financial_routine_id'))
                ->where('id', $request->get('fr_transaction_id'))->first();

            if ($frTransaction) {
                return response()->json([
                    'status' => 'success',
                    'result' => $frTransaction
                ]);
            }
        }
        return response()->json(['result' => 'Gagal!!']);   
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->has('financial_routine_id') && $request->has('fr_transaction_id')) {
                $frTransaction = FinancialRoutineTransaction::where('financial_routine_id', $request->get('financial_routine_id'))
                    ->where('id', $request->get('fr_transaction_id'))->first();
                    
                if ($frTransaction) {
                    $frTransactionOld = FinancialRoutineTransaction::where('financial_routine_id', $request->get('financial_routine_id'))
                        ->where('id', $request->get('fr_transaction_id'))->first();

                    $data = $request->all();
                    $user = Auth::user();
                    $frTransaction->transaction_date = $request->input('tdetail_date');
                    $frTransaction->bank_account_id = $request->input('tdetail_bank');
                    $frTransaction->transaction = $request->input('tdetail_nominal');
                    $frTransaction->description = $request->input('tdetail_description');
                    $frTransaction->financial_routine_id = $request->input('financial_routine_id');
                    $frTransaction->user_id = $user->id;
                    $frTransaction->save();

                    $financialRoutine = FinancialRoutine::find($request->financial_routine_id);
                    $financialRoutine->updateRemainsSaldo();
                    $financialRoutine->save();

                    $historyUpdate['type_menu'] = "Financial Routine";
                    $historyUpdate['method'] = "Update";
                    $historyUpdate['meta'] = json_encode([
                        'user'=>$user['id'],
                        'createdAt' => date("Y-m-d h:i:s"),
                        'dataChange'=> ["Update Financial Routine Transaction: " => [$frTransaction->id => array_diff(json_decode($frTransaction, true), json_decode($frTransactionOld, true)) ]]
                    ]);

                    $historyUpdate['user_id'] = $user['id'];
                    $historyUpdate['menu_id'] = $data['financial_routine_id'];
                    $createData = HistoryUpdate::create($historyUpdate);
                    DB::commit();

                    return redirect()->back()->with('success', 'Petty Cash Berhasil Di Ubah');
                }
            }
            return response()->json(['result' => 'Gagal!!']);
        } catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' =>  $ex->getMessage(), 500]);
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            if($request->has('id')){
                $frTransaction = FinancialRoutineTransaction::find($request->get('id'));

                if ($frTransaction) {
                    $data['financial_routine_id'] = $frTransaction->financial_routine_id;
                    $frTransactionOld = FinancialRoutineTransaction::find($request->get('id'));
                    $frTransaction->delete();

                    // Set Order Down Payment
                    $financialRoutine = FinancialRoutine::find($data['financial_routine_id']);
                    $financialRoutine->updateRemainsSaldo();
                    $financialRoutine->save();

                    $user = Auth::user();
                    $historyUpdate['type_menu'] = "Financial Routine";
                    $historyUpdate['method'] = "Update";
                    $historyUpdate['meta'] = json_encode([
                        'user'=>$user['id'],
                        'createdAt' => date("Y-m-d h:i:s"),
                        'dataChange'=> ["Deleted Financial Routine Transaction" => $frTransactionOld ]
                    ]);

                    $historyUpdate['user_id'] = $user['id'];
                    $historyUpdate['menu_id'] = $data['financial_routine_id'];
                    $createData = HistoryUpdate::create($historyUpdate);
                    DB::commit();

                    return redirect()->back()->with('success', 'Petty Cash Berhasil Di Hapus');
                }
            }
            
            return response()->json(['result' => 'Gagal!!']);
        }catch (\Exception $ex) {
            DB::rollback();
            return response()->json(['error' =>  $ex->getMessage(), 500]);
        }
    }
}
