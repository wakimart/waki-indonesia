<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\PettyCash;
use App\PettyCashClosedBook;
use App\PettyCashDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PettyCashClosedBookController extends Controller
{
    public function create(Request $request)
    {
        $banks = PettyCashController::getUserBank();

        $status = "";
        $startDate = null;
        $endDate = null;
        $currentBank="";
        $pettyCashes = [];
        $lastPTCClosedBook = null;

        // Check Unique Petty Cash Closed Book
        if ($request->filter_date) {
            $startDate = date("Y-m-01", strtotime($request->filter_date));
            $endDate = date("Y-m-d", strtotime($request->filter_date));
        }
        if ($request->filter_bank) {
            $currentBank = BankAccount::where("id", $request->filter_bank)->first();
            if (!in_array($currentBank->id, $banks->pluck('id')->toArray())) $currentBank = "";
        }

        if ($startDate && $endDate && $currentBank) {
            $ptcClosedBook = PettyCashClosedBook::where('bank_account_id', $request->filter_bank)
                ->whereBetween('date', [
                    date('Y-m-01', strtotime($request->filter_date)),
                    date('Y-m-t', strtotime($request->filter_date)),
                ])
                ->first();

            if ($ptcClosedBook) {
                $status = "same-data";
            } else {
                $status = "new-data";
                // List Petty Cash
                $pettyCashes = PettyCashDetail::from('petty_cash_details as ptcd')
                    ->select('ptcd.*', 'ptc.code', 'ptc.transaction_date', 'ptc.type')
                    ->join('petty_cashes as ptc', 'ptcd.petty_cash_id', 'ptc.id')
                    ->where('ptc.active', true)
                    ->whereBetween('transaction_date', [$startDate, $endDate])
                    ->where('bank_account_id', $currentBank->id)
                    ->orderBy('transaction_date')
                    ->orderByRaw('length(code) asc, code asc')
                    ->get();

                // Get Last Month Petty Cash Closed Book
                $lastMonth = date('Y-m-d', strtotime($startDate . " first day of last month"));
                $lastPTCClosedBook = PettyCashClosedBook::where('bank_account_id', $currentBank->id)
                    ->whereBetween('date', [
                        date('Y-m-01', strtotime($lastMonth)),
                        date('Y-m-t', strtotime($lastMonth)),
                    ])->first();
            }
        }
        // End Check Unique Petty Cash Closed Book

        return view('admin.add_postingpettycash', compact('banks', 'status', 'startDate', 'endDate', 'currentBank', 'pettyCashes', 'lastPTCClosedBook'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nominal' => 'required',
            'date' => 'required',
            'bank' => [
                'required',
                Rule::unique('petty_cash_closed_books', 'bank_account_id')
                    ->where(function($query) use ($request) {
                        $query->whereBetween('date', [
                            date('Y-m-01', strtotime($request->date)),
                            date('Y-m-t', strtotime($request->date)),
                        ]);
                    })
            ],
        ], [
            'bank.unique' => 'Posting Petty Cash data already exists.'
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $ptcClosedBook = new PettyCashClosedBook();
            $ptcClosedBook->bank_account_id = $request->bank;
            $ptcClosedBook->date = $request->date;
            $ptcClosedBook->nominal = $request->nominal;
            $ptcClosedBook->user_id = $user->id;
            $ptcClosedBook->save();

            // Update Petty Cash petty_cash_closed_book_id
            $startDate = date("Y-m-01", strtotime($ptcClosedBook->date));
            $endDate = date("Y-m-d", strtotime($ptcClosedBook->date));
            $pettyCashes = PettyCash::where('active', true)
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->where('bank_account_id', $ptcClosedBook->bank_account_id)
                ->update(['petty_cash_closed_book_id' => $ptcClosedBook->id]);

            DB::commit();
            return redirect()->route('add_posting_petty_cash')->with('success', 'Posting Petty Cash successfully created.');
        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->back()->withErrors(['Error Create Posting Petty Cash']);
        }
    }
}
