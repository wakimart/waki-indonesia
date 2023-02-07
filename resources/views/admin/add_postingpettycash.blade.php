<?php
$menu_item_page = "petty_cash";
$menu_item_second = "add_posting_petty_cash";

?>
@extends("admin.layouts.template")

@section("style")
<style type="text/css">
    select {
        color: black !important;
    }

    .ck-editor__editable_inline {
        height: 200px;
    }
</style>
@endsection

@section("content")
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Add Posting Petty Cash</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                      <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#posting_petty_cash-dd"
                            aria-expanded="false"
                            aria-controls="posting_petty_cash-dd">
                            Posting Petty Cash
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Add Posting Petty Cash
                    </li>
                </ol>
            </nav>
        </div>
        
        <form id="actionAdd"
            method="GET"
            action="{{ route("add_posting_petty_cash") }}" autocomplete="off">
            <div class="row">
                <div class="col-12 grid-margin stretch-card mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="filter_date">Date</label>
                                    <input type="date"
                                        id="filter_date"
                                        name="filter_date"
                                        value="{{ request()->input('filter_date') }}"
                                        class="form-control" 
                                        required />
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="filter_bank">Bank Account</label>
                                    <select id="filter_bank" name="filter_bank"
                                        class="form-control" 
                                        required >
                                        <option value="" disabled selected>Choose Bank</option>
                                        @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}"
                                            @if($bank->id == request()->input('filter_bank')) selected @endif>
                                            {{ $bank->code }} - {{ $bank->name }} ({{ ucwords($bank->type) }})
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success btn-block">CHECK</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Error Same Posting Petty Cash --}}
        @if($status == "same-data")
        <h3 class="text-center" style="color: red">Posting Petty Cash data already exists.</h3>
        @endif

        {{-- Validator Store New Posting Petty Cash --}}
        @if ($errors->any())
        <div class="text-center" style="color: red">
            @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
        </div>
        @endif

        @if($status =="new-data" && !$lastPTCClosedBook)
        <h3 class="text-center" style="color: red">No Data Found for Last Posting Petty Cash.</h3>
        @endif

        {{-- New Posting Petty Cash --}}
        @if($status == "new-data" && $lastPTCClosedBook)
        <h3 class="text-center" style="color: green">New Posting Petty Cash</h3>
        <div class="row">
            <div class="col-12 grid-margin stretch-card mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5>Petty Cash Statement</h5>
                        <h5>Bank : {{ $currentBank->code }} - {{ $currentBank->name }}</h5>
                        <h5>Periode : {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}</h5>            
                        <div class="table-responsive" style="border: 1px solid #ebedf2;">
                            <div class="table-responsive"
                                style="border: 1px solid #ebedf2;">
                                <table class="table table-bordered" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Code</th>
                                            <th>Bank/Account</th>
                                            <th>Out</th>
                                            <th>In</th>
                                            <th>Saldo</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php 
                                            $total_nominal_in = 0;
                                            $total_nominal_out = 0;
                                            $total_nominal_saldo = 0;
                                        @endphp
                                        @if($lastPTCClosedBook)
                                            <tr>
                                                <td>
                                                    {{ date('01/m/Y', strtotime($startDate)) }}
                                                </td>
                                                <td>-</td>
                                                <td>SALDO</td>
                                                <td class="text-right">0</td>
                                                <td class="text-right">0</td>
                                                <td class="text-right">{{ number_format($lastPTCClosedBook->nominal) }}</td>
                                                <td></td>
                                            </tr>
                                            @php $total_nominal_saldo += $lastPTCClosedBook->nominal; @endphp
                                        @endif
                                        @foreach($pettyCashes as $pettyCash)
                                        @php 
                                            $bank_petty_cash_type = $pettyCash->petty_cash_out_bank_account_id ? "bank" : "account";
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ date('d/m/Y', strtotime($pettyCash->transaction_date)) }}
                                            </td>
                                            <td>
                                                {{ $pettyCash->code }}
                                            </td>
                                            @if($pettyCash->type == "out")
                                            <td>{{ $bank_petty_cash_type == "bank" 
                                                ? $pettyCash->pettyCashOutBankAccount['code'] . ' - ' . $pettyCash->pettyCashOutBankAccount['name']
                                                : $pettyCash->pettyCashOutType['code'] . ' - ' . $pettyCash->pettyCashOutType['name'] }}
                                            </td>
                                            @else
                                            <td>{{ $pettyCash->pettyCash->bankAccount['code'] }} - {{ $pettyCash->pettyCash->bankAccount['name'] }}</td>
                                            @endif
                                            <td class="text-right">
                                                @if($pettyCash->type == "out")
                                                    @php
                                                        $total_nominal_out += $pettyCash->nominal;
                                                        $total_nominal_saldo -= $pettyCash->nominal;
                                                    @endphp
                                                    {{ number_format($pettyCash->nominal) }}
                                                @else
                                                    0
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if($pettyCash->type == "in")
                                                    @php 
                                                        $total_nominal_in += $pettyCash->nominal;
                                                        $total_nominal_saldo += $pettyCash->nominal
                                                    @endphp
                                                    {{ number_format($pettyCash->nominal) }}
                                                @else
                                                    0
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                {{ number_format($total_nominal_saldo) }}
                                            </td>
                                            <td>
                                                <?php echo $pettyCash->description ?>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 grid-margin stretch-card mb-3">
                <div class="card">
                    <form method="post" action="{{ route('store_posting_petty_cash') }}" autocomplete="off">
                        @csrf
                        <input type="hidden" name="bank" value="{{ request()->input('filter_bank') }}">
                        <input type="hidden" name="date" value="{{ request()->input('filter_date') }}">
                        <input type="hidden" name="nominal" value="{{ $total_nominal_saldo }}">
                        <div class="card-body text-center">
                            <h3>Saldo Remains {{ number_format($total_nominal_saldo) }}</h3>
                            <button id="addFinancialRoutine" type="submit"
                                class="btn btn-gradient-primary">
                                Posting Petty Cash
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section("script")
@endsection