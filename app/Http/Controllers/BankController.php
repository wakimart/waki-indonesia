<?php

namespace App\Http\Controllers;

use App\Bank;
use App\BankAccount;
use App\CreditCard;
use App\HistoryUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class BankController extends Controller
{
    public function index(Request $request)
    {
        $url = $request->all();

        $banks = Bank::where('active', true);

        if ($request->has('search')) {
            $banks->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $countBanks = $banks->count();
        $banks = $banks->paginate(10);

        return view(
            "admin.list_bank",
            compact(
                "countBanks",
                "banks",
                "url"
            )
        )
            ->with("i", (request()->input("page", 1) - 1) * 10 + 1);;
    }

    public function create()
    {
        return view('admin.add_bank');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if($validator->fails()){
            $arr_Errors = $validator->errors()->all();
            $arr_Keys = $validator->errors()->keys();
            $arr_Hasil = [];
            for ($i = 0; $i < count($arr_Keys); $i++) {
                $arr_Hasil[$arr_Keys[$i]] = $arr_Errors[$i];
            }
            return response()->json(['errors' => $arr_Hasil]);
        }else {
            $data = $request->all();
            Bank::create($data);

            return response()->json(['success' => 'Berhasil']);
        }
    }

    public function edit(Request $request)
    {
        if($request->has('id')){
            $bank = Bank::find($request->get('id'));
            return view('admin.update_bank', compact('bank'));
        }else{
            return response()->json(['result' => 'Gagal!!']);
        }
    }

    public function update(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if($validator->fails()){
            $arr_Errors = $validator->errors()->all();
            $arr_Keys = $validator->errors()->keys();
            $arr_Hasil = [];
            for ($i = 0; $i < count($arr_Keys); $i++) {
                $arr_Hasil[$arr_Keys[$i]] = $arr_Errors[$i];
            }
            return response()->json(['errors' => $arr_Hasil]);
        }else {
            $data = $request->all();
            $bank = Bank::find($request->get('idBank'));
            $bank->name = $data['name'];
            $bank->save();

            return response()->json(['success' => 'Berhasil']);
        }
    }

    public function destroy(Request $request)
    {
        DB::beginTransaction();

        if (!empty($request->id)) {
            try {
                $bank = Bank::find($request->id);
                $bank->active = false;
                $bank->save();

                $user = Auth::user();
                $historyDeleteBank["type_menu"] = "Bank";
                $historyDeleteBank["method"] = "Delete";
                $historyDeleteBank["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $bank->getChanges(),
                    ],
                    JSON_THROW_ON_ERROR
                );

                $historyDeleteBank["user_id"] = $user["id"];
                $historyDeleteBank["menu_id"] = $request->id;
                HistoryUpdate::create($historyDeleteBank);

                DB::commit();

                return redirect()
                    ->route("list_bank")
                    ->with("success", "Data berhasil dihapus!");
            } catch (Exception $e) {
                DB::rollback();

                return response()->json([
                    "error" => $e,
                ], 500);
            }
        }

        return response()->json(["result" => "Data tidak ditemukan."], 400);
    }

    /**
     * index bank account
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function indexBankAccount(Request $request)
    {
        $url = $request->all();
        $datas = BankAccount::leftJoin('banks', function($join) {
                    $join->on('bank_accounts.bank_id', '=', 'banks.id');
                })->where('bank_accounts.active', true);

        if ($request->has('search')) {
            $datas->where(function($q) use($request) {
                $q->where('bank_accounts.code', 'like', '%'.$request->search.'%')
                ->orWhere('bank_accounts.name', 'like', '%'.$request->search.'%')
                ->orWhere('bank_accounts.account_number', 'like', '%'.$request->search.'%')
                ->orWhere('bank_accounts.type', 'like', '%'.$request->search.'%')
                ->orWhere('bank_accounts.charge_percentage', 'like', '%'.$request->search.'%')
                ->orWhere('bank_accounts.estimate_transfer', 'like', '%'.$request->search.'%')
                ->orWhere('banks.name', 'like', '%'.$request->search.'%');
            });
        }
        $datas = $datas->select(['bank_accounts.*', 'banks.name as bank_name']);
        $datas = $datas->paginate(10);

        return view("admin.list_bank_account", compact("datas", "url"))->with("i", (request()->input("page", 1) - 1) * 10 + 1);
    }

    /**
     * create bank account
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function createBankAccount()
    {
        $banks = Bank::where('active', true)->get();
        return view('admin.add_bank_account', compact('banks'));
    }

    /**
     * store bank account
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function storeBankAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required',
            'account_number' => 'required',
            'type' => 'required|string',
            'charge_percentage' => 'required|numeric|min:0',
            'estimate_transfer' => 'required|integer|min:0',
            'bank_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            DB::beginTransaction();
            try {
                BankAccount::create($request->all());
                DB::commit();
                return Redirect::back()->with("success", "Bank account successfully added.");
            } catch (\Exception $ex) {
                DB::rollBack();
                return Redirect::back()->withErrors("Something wrong when add bank account, please call Team IT")->withInput();
            }
        }
    }

    /**
     * edit bank account
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function editBankAccount($id)
    {
        $bankAccount = BankAccount::find($id);
        $banks = Bank::where('active', true)->get();
        return view('admin.update_bank_account', compact('bankAccount', 'banks'));
    }

    /**
     * update bank account
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function updateBankAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required',
            'account_number' => 'required',
            'type' => 'required|string',
            'charge_percentage' => 'required|numeric',
            'estimate_transfer' => 'required|integer',
            'bank_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            DB::beginTransaction();
            try {
                $bankAccount = BankAccount::find($request->idBankAccount);
                $bankAccount->fill($request->all())->save();
                DB::commit();
                return Redirect::back()->with("success", "Bank account successfully updated.");
            } catch (\Exception $ex) {
                DB::rollBack();
                return Redirect::back()->withErrors("Something wrong when update bank account, please call Team IT")->withInput();
                // return Redirect::back()->withErrors($ex->getMessage());
            }
        }
    }

    /**
     * deactivate bank account
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function destroyBankAccount(Request $request)
    {
        DB::beginTransaction();

        if (!empty($request->id)) {
            try {
                $bank = BankAccount::find($request->id);
                $bank->active = false;
                $bank->save();

                $user = Auth::user();
                $historyDeleteBank["type_menu"] = "Bank Account";
                $historyDeleteBank["method"] = "Delete";
                $historyDeleteBank["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $bank->getChanges(),
                    ],
                    JSON_THROW_ON_ERROR
                );

                $historyDeleteBank["user_id"] = $user["id"];
                $historyDeleteBank["menu_id"] = $request->id;
                HistoryUpdate::create($historyDeleteBank);

                DB::commit();
                
                return Redirect::back()->with("success", "Bank account deleted successfully.");
            } catch (Exception $e) {
                DB::rollback();
                return Redirect::back()->withErrors("Something wrong when delete bank account, please call Team IT");
            }
        }
        
        return Redirect::back()->withErrors("Data not found");
    }

    /**
     * get bank account by bank
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function getBankAccountData($id)
    {
        $bankAccounts = BankAccount::where('active', true)->where('bank_id', $id)->get();
        return response()->json($bankAccounts);
    }

    /**
     * index credit card (list)
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function indexCreditCard(Request $request)
    {
        $url = $request->all();
        $datas = CreditCard::leftJoin('bank_accounts', function($join) {
                    $join->on('credit_cards.bank_account_id', '=', 'bank_accounts.id');
                })->where('credit_cards.active', true);

        if ($request->has('search')) {
            $datas->where(function($q) use($request) {
                $q->where('credit_cards.code', 'like', '%'.$request->search.'%')
                ->orWhere('credit_cards.name', 'like', '%'.$request->search.'%')
                ->orWhere('credit_cards.cicilan', 'like', '%'.$request->search.'%')
                ->orWhere('credit_cards.charge_percentage_sales', 'like', '%'.$request->search.'%')
                ->orWhere('credit_cards.charge_percentage_company', 'like', '%'.$request->search.'%')
                ->orWhere('credit_cards.estimate_transfer', 'like', '%'.$request->search.'%')
                ->orWhere('credit_cards.description', 'like', '%'.$request->search.'%')
                ->orWhere('bank_accounts.name', 'like', '%'.$request->search.'%');
            });
        }
        $datas = $datas->select(['credit_cards.*', 'bank_accounts.name as bank_account_name']);
        $datas = $datas->paginate(10);

        return view("admin.list_credit_card", compact("datas", "url"))->with("i", (request()->input("page", 1) - 1) * 10 + 1);
    }

    /**
     * create credit card
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function createCreditCard()
    {
        $bankAccounts = BankAccount::where('active', true)->get();
        return view('admin.add_credit_card', compact('bankAccounts'));
    }

    /**
     * store credit card
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function storeCreditCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required',
            'bank_account_id' => 'required|integer',
            'cicilan' => 'required|integer|min:0',
            'charge_percentage_sales' => 'required|numeric|min:0',
            'charge_percentage_company' => 'required|numeric|min:0',
            'estimate_transfer' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            DB::beginTransaction();
            try {
                CreditCard::create($request->all());
                DB::commit();
                return Redirect::back()->with("success", "Credit card successfully added.");
            } catch (\Exception $ex) {
                DB::rollBack();
                return Redirect::back()->withErrors("Something wrong when add credit card, please call Team IT")->withInput();
            }
        }
    }

    /**
     * edit credit card
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function editCreditCard($id)
    {
        $creditCard = CreditCard::find($id);
        $bankAccounts = BankAccount::where('active', true)->get();
        return view('admin.update_credit_card', compact('creditCard', 'bankAccounts'));
    }

    /**
     * update credit card
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function updateCreditCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required',
            'bank_account_id' => 'required|integer',
            'cicilan' => 'required|integer|min:0',
            'charge_percentage_sales' => 'required|numeric|min:0',
            'charge_percentage_company' => 'required|numeric|min:0',
            'estimate_transfer' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }else{
            DB::beginTransaction();
            try {
                $creditCard = CreditCard::find($request->idCreditCard);
                $creditCard->fill($request->all())->save();
                DB::commit();
                return Redirect::back()->with("success", "Credit Card successfully updated.");
            } catch (\Exception $ex) {
                DB::rollBack();
                return Redirect::back()->withErrors("Something wrong when update credit card, please call Team IT")->withInput();
                // return Redirect::back()->withErrors($ex->getMessage());
            }
        }
    }

    /**
     * destroy credit card
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function destroyCreditCard(Request $request)
    {
        DB::beginTransaction();

        if (!empty($request->id)) {
            try {
                $creditCard = CreditCard::find($request->id);
                $creditCard->active = false;
                $creditCard->save();

                $user = Auth::user();
                $historyDeleteCreditCard["type_menu"] = "Credit Card";
                $historyDeleteCreditCard["method"] = "Delete";
                $historyDeleteCreditCard["meta"] = json_encode(
                    [
                        "user" => $user["id"],
                        "createdAt" => date("Y-m-d H:i:s"),
                        "dataChange" => $creditCard->getChanges(),
                    ],
                    JSON_THROW_ON_ERROR
                );

                $historyDeleteCreditCard["user_id"] = $user["id"];
                $historyDeleteCreditCard["menu_id"] = $request->id;
                HistoryUpdate::create($historyDeleteCreditCard);

                DB::commit();
                
                return Redirect::back()->with("success", "Credit card deleted successfully.");
            } catch (Exception $e) {
                DB::rollback();
                return Redirect::back()->withErrors("Something wrong when delete credit card, please call Team IT");
            }
        }
        
        return Redirect::back()->withErrors("Data not found");
    }

    /**
     * get credit card data
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function getCreditCardData($id)
    {
        $data = CreditCard::find($id);
        $data['bank_account'] = $data->bankAccount;
        $data['bank_account']['bank'] = $data->bankAccount->bank;
        return response()->json($data);
    }

    /**
     * get bank account data from payment modal
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function getBankAccountDataFromPaymentModal($id)
    {
        $data = BankAccount::find($id);
        $data['bank'] = $data->bank;
        return response()->json($data);
    }
}
