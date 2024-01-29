<?php
$menu_item_page = "financial_routine";
$menu_item_second = "report_financial_routine_all_bank_branch";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    #intro {
        padding-top: 2em;
        min-width: 0;
    }

    input[readonly], textarea[readonly]{
        background-color: #fff !important;
    }

    input[readonly]:focus, textarea[readonly]:focus{
        outline: none !important;
        border:1px solid #ebedf2;

    }

    .no-margin p{
        margin-bottom: 0 !important;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Report All Bank/Branch</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                      <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#report_financial_routine_all_bank_branch-dd"
                            aria-expanded="false"
                            aria-controls="report_financial_routine_all_bank_branch-dd">
                            Financial Routine
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Report All Bank/Branch
                    </li>
                </ol>
            </nav>
        </div>
        
        <form id="actionAdd"
            method="GET"
            action="{{ route("report_financial_routine_all_bank_branch") }}" autocomplete="off">
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
                                    <label for="filter_fr_by">Financial Routine By</label>
                                    <select id="filter_fr_by" name="filter_fr_by"
                                        class="form-control" 
                                        required >
                                        <option value="" disabled selected>Choose By</option>
                                        <option value="bank" @if(request()->input('filter_fr_by') == 'bank') selected @endif>Bank</option>
                                        <option value="branch" @if(request()->input('filter_fr_by') == 'branch') selected @endif>Branch</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success btn-block">PRINT</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        @if($financialRoutines)
        <div class="row" id="element-to-print">
            <div class="col-12 grid-margin stretch-card mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <h5>Financial Routine by {{ ucwords(request()->input('filter_fr_by')) }}</h5>
                                <h5>Periode : {{ date('d/m/Y', strtotime(request()->input('filter_date'))) }}</h5>            
                            </div>
                            <button id="btn-bulk-print" class="btn btn-secondary hide-print" style="margin: 0.25rem 0 0.25rem auto">
                                Bulk Print
                                <span>
                                    <i class="mdi mdi-printer-settings" style="margin-left: 5px; font-size: 24px;vertical-align: middle;"></i>
                                </span>
                            </button>
                            <button id="btn-print"
                                type="button"
                                class="btn btn-gradient-info m-1 hide-print">
                                Print
                                <span>
                                    <i class="mdi mdi-printer" 
                                    style="margin-left: 5px; 
                                            font-size: 24px; 
                                            vertical-align: middle;">
                                    </i>
                                </span>
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="myTable">
                                <thead>
                                    <tr>
                                        <th style="vertical-align: bottom;">
                                            Branch
                                        </th>
                                        <th style="vertical-align: bottom; text-align: right">
                                            Last Saldo
                                            <br>Last Not Bank In
                                        </th>
                                        <th style="vertical-align: bottom; text-align: right">
                                            Total Sale
                                            <br>Adm Bank
                                            <br>Etc In
                                        </th>
                                        <th style="vertical-align: bottom; text-align: right">
                                            Charge Bank
                                            <br>Etc Out
                                        </th>
                                        <th style="vertical-align: bottom; text-align: right">
                                            Not Bank In
                                            <br>Saldo
                                        </th>
                                        <th style="vertical-align: bottom; text-align: center">
                                            Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                        $total_last_saldo = 0;
                                        $total_last_not_bank_in = 0;
                                        $total_sale = 0;
                                        $total_adm_bank = 0;
                                        $total_etc_in = 0;
                                        $total_charge_bank = 0;
                                        $total_etc_out = 0;
                                        $total_not_bank_in = 0;
                                        $total_saldo = 0;
                                        $total_per_fr = 0;
                                    @endphp
                                    @foreach ($financialRoutines as $financialRoutine)
                                        @php 
                                            $subtotal_per_fr = $financialRoutine->financialRoutine['remains_saldo']
                                                + $financialRoutine->financialRoutine['remains_sales']
                                                + $financialRoutine->total_sale
                                                + $financialRoutine->bank_interest
                                                + $financialRoutine->etc_in;
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $financialRoutine->bankAccount->code }} - {{ $financialRoutine->bankAccount->name }}
                                            </td>
                                            <td style="text-align: right">
                                                <br>{{ number_format($financialRoutine->financialRoutine['remains_saldo']) }}
                                                <br>{{ number_format($financialRoutine->financialRoutine['remains_sales']) }}
                                            </td>
                                            <td style="text-align: right">
                                                {{ number_format($financialRoutine->total_sale) }}
                                                <br>{{ number_format($financialRoutine->bank_interest) }}
                                                <br>{{ number_format($financialRoutine->etc_in) }}
                                            </td>
                                            <td style="text-align: right">
                                                <br>{{ number_format($financialRoutine->bank_tax) }}
                                                <br>{{ number_format($financialRoutine->etc_out) }}
                                            </td>
                                            <td style="text-align: right">
                                                <br>{{ number_format($financialRoutine->remains_sales) }}
                                                <br>{{ number_format($financialRoutine->remains_saldo) }}
                                            </td>
                                            <td style="text-align: right">
                                                <br>
                                                <br>{{ number_format($subtotal_per_fr) }}
                                            </td>
                                        </tr>
                                        @php 
                                            $total_last_saldo += $financialRoutine->financialRoutine['remains_saldo'];
                                            $total_last_not_bank_in += $financialRoutine->financialRoutine['remains_sales'];
                                            $total_sale += $financialRoutine->total_sale;
                                            $total_adm_bank += $financialRoutine->bank_interest;
                                            $total_etc_in += $financialRoutine->etc_in;
                                            $total_charge_bank += $financialRoutine->bank_tax;
                                            $total_etc_out += $financialRoutine->etc_out;
                                            $total_not_bank_in += $financialRoutine->remains_sales;
                                            $total_saldo += $financialRoutine->remains_saldo;
                                            $total_per_fr += $subtotal_per_fr;
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <th rowspan="3">
                                            Total
                                        </th>
                                        <th style="text-align: right">
                                            <br>{{ number_format($total_last_saldo) }}
                                            <br>{{ number_format($total_last_not_bank_in) }}
                                        </th>
                                        <th style="text-align: right">
                                            {{ number_format($total_sale) }}
                                            <br>{{ number_format($total_adm_bank) }}
                                            <br>{{ number_format($total_etc_in) }}
                                        </th>
                                        <th style="text-align: right">
                                            <br>{{ number_format($total_charge_bank) }}
                                            <br>{{ number_format($total_etc_out) }}
                                        </th>
                                        <th style="text-align: right">
                                            <br>{{ number_format($total_not_bank_in) }}
                                            <br>{{ number_format($total_saldo) }}
                                        </th>
                                        <th style="text-align: right">
                                            <br>
                                            <br>{{ number_format($total_per_fr) }}
                                        </th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @php
            $isBranch = true;
            if(request()->input('filter_fr_by') == 'bank'){
                $isBranch = false;
            }
        @endphp
        @foreach($financialRoutines as $financialRoutine)
            <div class="col-12 grid-margin stretch-card d-none">
                <div class="card">
                    <div class="card-body" style="padding-left: 2em; padding-right: 2em;">
                        <!-- LAYOUT PRINT -->
                        <div class="row">
                            <div class="col-12 grid-margin stretch-card element-to-print-summary-financial-routine">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div>
                                            <div style="" class="scale-up-font">
                                                <div class="row d-flex justify-content-end">
                                                    <div class="col-10 text-right">Code : </div>
                                                    <div class="col-2 text-left pl-0">{{ $financialRoutine->bankAccount['code'] }}</div>
                                                </div>
                                                <div class="row d-flex justify-content-end">
                                                    <div class="col-10 text-right">Name Acc : </div>
                                                    <div class="col-2 text-left pl-0">{{ $financialRoutine->bankAccount['name'] }}</div>
                                                </div>
                                                <div class="row d-flex justify-content-end">
                                                    <div class="col-10 text-right">No Acc : </div>
                                                    <div class="col-2 text-left pl-0">{{ $financialRoutine->bankAccount['account_number'] }}</div>
                                                </div>
                                            </div>
                                            <div style="margin: auto; text-align: center; font-weight: 600;" class="my-4">
                                                <h5 style="font-size: 28px; @if($financialRoutine->remains_saldo < 0) color: red; @endif">
                                                    Laporan Rutin Keuangan
                                                    <br>
                                                    {{ date('F Y', strtotime($financialRoutine->routine_date)) }} @if($financialRoutine->remains_saldo < 0) (MINUS) @endif
                                                </h5>
                                            </div>
                                            <br>
                                        </div>
                                        <div class="row scale-up-font">
                                            <div class="col-3">
                                                <div style="margin-bottom: 5px; text-align: right;">
                                                    <p class="mb-0">Last Month Remains Saldo :</p>
                                                </div>
                                            </div>
                                            <div class="col-2 text-right d-flex justify-content-end align-items-end" style="padding-left: 0;">
                                                <div style="margin-bottom: 5px;">
                                                    <p class="mb-0">{{ number_format($financialRoutine->financialRoutine['remains_saldo']) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row scale-up-font">
                                            <div class="col-3">
                                                <div style="margin-bottom: 5px; text-align: right;">
                                                    <p class="mb-0">Last Month's Sale That Hasn't Been Bank In :</p>
                                                </div>
                                            </div>
                                            <div class="col-2 text-right d-flex justify-content-end align-items-end" style="padding-left: 0;">
                                                <div style="margin-bottom: 5px;">
                                                    <p class="mb-0">{{ number_format($financialRoutine->financialRoutine['remains_sales']) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row scale-up-font">
                                            <div class="col-3">
                                                <div style="margin-bottom: 5px; text-align: right;">
                                                    <p class="mb-0">Total Sale :</p>
                                                </div>
                                            </div>
                                            <div class="col-2 text-right d-flex justify-content-end align-items-end" style="padding-left: 0;">
                                                <div style="margin-bottom: 5px;">
                                                    <p class="mb-0">{{ number_format($financialRoutine->total_sale) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row scale-up-font">
                                            <div class="col-3">
                                                <div style="margin-bottom: 5px; text-align: right;">
                                                    <p class="mb-0">Interest / Administration Bank :</p>
                                                </div>
                                            </div>
                                            <div class="col-2 text-right d-flex justify-content-end align-items-end" style="padding-left: 0;">
                                                <div style="margin-bottom: 5px;">
                                                    <p class="mb-0">{{ number_format($financialRoutine->bank_interest) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row scale-up-font">
                                            <div class="col-3">
                                                <div style="margin-bottom: 5px; text-align: right;">
                                                    <p class="mb-0">Etc In :</p>
                                                </div>
                                            </div>
                                            <div class="col-2 text-right d-flex justify-content-end align-items-end" style="padding-left: 0;">
                                                <div style="margin-bottom: 5px;">
                                                    <p class="mb-0">{{ number_format($financialRoutine->etc_in) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="row scale-up-font">
                                            <div class="col-3">
                                                <div style="text-align: right;">
                                                    <p class="mb-0">To Petty Cash :</p>
                                                </div>
                                            </div>
                                            <div class="col-2" style="padding-left: 0;">
                                                <div style="">
                                                    <p class="mb-0"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row scale-up-font justify-content-center" style="padding: 0.5em 2em;">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead class="table-bordered">
                                                        <td class="text-center" style="width: 10%;">No</td>
                                                        <td class="text-center" style="width: 20%;">Date</td>
                                                        <td class="text-center" style="width: 45%;">Description</td>
                                                        <td class="text-center" style="width: 25%;">Nominal</td>
                                                    </thead>
                                                    <tbody class="table-bordered">
                                                        @php $sub_total_fr_details = 0; @endphp
                                                        @foreach ($financialRoutine->financialRoutineTransaction as $frTransaction)
                                                        <tr>
                                                            <td class="text-center">{{ $loop->iteration }}</td>
                                                            <td class="text-left">
                                                                {{ date('d/m/Y', strtotime($frTransaction->transaction_date))  }}
                                                            </td>
                                                            <td class="text-left">
                                                                To {{ $frTransaction->bankAccount['code']  }} ({{ $frTransaction->bankAccount['name'] }})
                                                                <br>
                                                                {{ $frTransaction->description }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ number_format($frTransaction->transaction) }}
                                                            </td>
                                                        </tr>
                                                        @php $sub_total_fr_details += $frTransaction->transaction; @endphp
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <table class="table">
                                                    <tbody class="table-borderless">
                                                        <tr>
                                                            <td class="text-right" style="width: 30%;">Sub Total : </td>
                                                            <td colspan="2"></td>
                                                            <td class="text-right" style="width: 25%;">{{ number_format($sub_total_fr_details) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-right">Tax / Charge Bank : </td>
                                                            <td colspan="2"></td>
                                                            <td class="text-right">{{ number_format($financialRoutine->bank_tax) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-right">Etc Out : </td>
                                                            <td colspan="2"></td>
                                                            <td class="text-right">{{ number_format($financialRoutine->etc_out) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-right">Sale That Hasn't Been Bank In : </td>
                                                            <td colspan="2"></td>
                                                            <td class="text-right">{{ number_format($financialRoutine->remains_sales) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-right">Remains Saldo : </td>
                                                            <td></td>
                                                            <td></td>
                                                            <td class="text-right" @if($financialRoutine->remains_saldo < 0) style="color: red; font-weight: 600;" @endif>
                                                                {{ number_format($financialRoutine->remains_saldo) }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            @php
                                                                $total_in = $financialRoutine->financialRoutine['remains_saldo'] +
                                                                    $financialRoutine->financialRoutine['remains_sales'] +
                                                                    $financialRoutine->total_sale +
                                                                    $financialRoutine->bank_interest +
                                                                    $financialRoutine->etc_in;
                                                                $total_out = $sub_total_fr_details +
                                                                    $financialRoutine->bank_tax +
                                                                    $financialRoutine->etc_out +
                                                                    $financialRoutine->remains_sales +
                                                                    $financialRoutine->remains_saldo;
                                                            @endphp
                                                            <td class="text-right" style="font-weight: 600;">Total : </td>
                                                            <td style="width: 25%; font-weight: 600; border-top: 1px solid #3e4b5b;">{{ number_format($total_in) }}</td>
                                                            <td style="width: 20%;"></td>
                                                            <td class="text-right" style="font-weight: 600; border-top: 1px solid #3e4b5b;">{{ number_format($total_out) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-right">Description : </td>
                                                            <td colspan="3" class="no-margin"><?php echo $financialRoutine->description; ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end layout print -->

                    </div>
                 </div>
            </div>
            <div class="col-12 grid-margin stretch-card d-none">
                <div class="card">
                    <div class="card-body" style="padding-left: 2em; padding-right: 2em;">
                        @if(!$isBranch)
                            <!-- LAYOUT PRINT -->
                            <div class="row">
                                <div class="col-12 grid-margin stretch-card element-to-print-detail-financial-routine">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div>
                                                <div style="" class="scale-up-font">
                                                    <div class="row d-flex justify-content-end">
                                                        <div class="col-10 text-right">Code : </div>
                                                        <div class="col-2 text-left pl-0">{{ $financialRoutine->bankAccount['code'] }}</div>
                                                    </div>
                                                    <div class="row d-flex justify-content-end">
                                                        <div class="col-10 text-right">Name Acc : </div>
                                                        <div class="col-2 text-left pl-0">{{ $financialRoutine->bankAccount['name'] }}</div>
                                                    </div>
                                                    <div class="row d-flex justify-content-end">
                                                        <div class="col-10 text-right">No Acc : </div>
                                                        <div class="col-2 text-left pl-0">{{ $financialRoutine->bankAccount['account_number'] }}</div>
                                                    </div>
                                                </div>
                                                <div style="margin: auto; text-align: center; font-weight: 600;" class="my-4">
                                                    <h5 style="font-size: 28px;">
                                                        Report Bank In Sales (by Bank)
                                                        <br>
                                                        {{ date('F Y', strtotime($financialRoutine->routine_date)) }}
                                                    </h5>
                                                </div>
                                                <br>
                                            </div>
                                            <div class="row justify-content-center scale-up-font" style="padding: 0.5em 2em;">
                                                <div class="table-responsive">
                                                    @php
                                                        $totalSaleGross = 0;
                                                        $totalSaleNetto = 0;
                                                    @endphp
                                                    @foreach ($financialRoutine->totalSales($isBranch) as $branch)
                                                    <table class="table">
                                                        <thead class="table-bordered">
                                                            <tr>
                                                                <td colspan="9" class="text-center" style="width: 5%; font-weight: 900;">Branch : {{ $branch['br_code'] }} - {{ $branch['br_name'] }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center" style="width: 5%;">No</td>
                                                                <td class="text-center" style="width: 15%;">Order Payment Date</td>
                                                                <td class="text-center" style="width: 15%;">Estimate Date</td>
                                                                <td class="text-center" style="width: 25%;">Order Code</td>
                                                                <td class="text-center" style="width: 20%">Bank In</td>
                                                                <td class="text-center" style="width: 20%">Debit</td>
                                                                <td class="text-center" style="width: 20%">Netto Debit</td>
                                                                <td class="text-center" style="width: 20%">Card</td>
                                                                <td class="text-center" style="width: 20%">Netto Card</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="table-bordered">
                                                            @php
                                                                $subtotalBankIn = 0;
                                                                $subtotalDebit = 0;
                                                                $subtotalNettoDebit = 0;
                                                                $subtotalCard = 0;
                                                                $subtotalNettoCard = 0;
                                                            @endphp
                                                            @foreach ($branch['orders'] as $total_sale)
                                                            <tr>
                                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                                <td class="text-left">
                                                                    {{ date("d/m/Y", strtotime($total_sale['payment_date'])) }}
                                                                </td>
                                                                <td class="text-left">
                                                                    {{ $total_sale['estimate_transfer_date'] ? date("d/m/Y", strtotime($total_sale['estimate_transfer_date'])) : date('d/m/Y', strtotime('+'.$total_sale['bacc_estimate_transfer'].' days', strtotime($total_sale['payment_date']))) }}
                                                                </td>
                                                                <td class="text-left">
                                                                    {{ $total_sale['o_code'] }}
                                                                </td>
                                                                <td class="text-right">
                                                                    {{ number_format($total_sale['ts_bank_in']) }}
                                                                </td>
                                                                <td class="text-right">
                                                                    {{ number_format($total_sale['ts_debit']) }}
                                                                </td>
                                                                <td class="text-right">
                                                                    {{ number_format($total_sale['ts_netto_debit']) }}
                                                                </td>
                                                                <td class="text-right">
                                                                    {{ number_format($total_sale['ts_card']) }}
                                                                </td>
                                                                <td class="text-right">
                                                                    {{ number_format($total_sale['ts_netto_card']) }}
                                                                </td>
                                                            </tr>
                                                            @php
                                                                $subtotalBankIn += $total_sale['ts_bank_in'];
                                                                $subtotalDebit += $total_sale['ts_debit'];
                                                                $subtotalNettoDebit += $total_sale['ts_netto_debit'];
                                                                $subtotalCard += $total_sale['ts_card'];
                                                                $subtotalNettoCard += $total_sale['ts_netto_card'];
                                                            @endphp
                                                            @endforeach
                                                            <tr>
                                                                <td class="text-right" colspan="4" style="font-weight: 600;">Total : </td>
                                                                <td class="text-right" style="font-weight: 600;">{{ number_format($subtotalBankIn) }}</td>
                                                                <td class="text-right" style="font-weight: 600;">{{ number_format($subtotalDebit) }}</td>
                                                                <td class="text-right" style="font-weight: 600;">{{ number_format($subtotalNettoDebit) }}</td>
                                                                <td class="text-right" style="font-weight: 600;">{{ number_format($subtotalCard) }}</td>
                                                                <td class="text-right" style="font-weight: 600;">{{ number_format($subtotalNettoCard) }}</td>
                                                            </tr>
                                                            @php
                                                                $totalSaleGross += $subtotalBankIn + $subtotalDebit + $subtotalCard;
                                                                $totalSaleNetto += $subtotalBankIn + $subtotalNettoDebit + $subtotalNettoCard;
                                                            @endphp
                                                            @if($loop->last)
                                                            <tr>
                                                                <td colspan="9" style="text-align: center;">
                                                                    <span class="mx-2" style="font-weight: 900; color: #2a6099;">{{ "Total Sale Gross : Rp. ".number_format($totalSaleGross) }}</span>
                                                                    <span class="mx-2" style="font-weight: 900; color: #2a992f;">{{ "Total Sale Netto : Rp. ".number_format($totalSaleNetto) }}</span>
                                                                </td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- end layout print -->
                        @else
                            <!-- LAYOUT PRINT -->
                            <div class="row">
                                <div class="col-12 grid-margin stretch-card element-to-print-detail-financial-routine">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div>
                                                <div style="" class="scale-up-font">
                                                    <div class="row d-flex justify-content-end">
                                                        <div class="col-10 text-right">Code : </div>
                                                        <div class="col-2 text-left pl-0">{{ $financialRoutine->bankAccount['code'] }}</div>
                                                    </div>
                                                    <div class="row d-flex justify-content-end">
                                                        <div class="col-10 text-right">Name Acc : </div>
                                                        <div class="col-2 text-left pl-0">{{ $financialRoutine->bankAccount['name'] }}</div>
                                                    </div>
                                                    <div class="row d-flex justify-content-end">
                                                        <div class="col-10 text-right">No Acc : </div>
                                                        <div class="col-2 text-left pl-0">{{ $financialRoutine->bankAccount['account_number'] }}</div>
                                                    </div>
                                                </div>
                                                <div style="margin: auto; text-align: center; font-weight: 600;" class="my-4">
                                                    <h5 style="font-size: 28px;">
                                                        Report Bank In Sales (by Branch)
                                                        <br>
                                                        {{ date('F Y', strtotime($financialRoutine->routine_date)) }}
                                                    </h5>
                                                </div>
                                                <br>
                                            </div>
                                            <div class="row justify-content-center scale-up-font" style="padding: 0.5em 2em;">
                                                <div class="table-responsive">
                                                        @php
                                                            $idxBank = 0;
                                                            $totalGross = 0;
                                                            $totalNetto = 0;
                                                        @endphp
                                                        @foreach ($financialRoutine->totalSales($isBranch) as $branch)
                                                            @foreach ($branch['banks'] as $bank)
                                                                @php
                                                                    $totalSubGross = 0;
                                                                    $totalSubNetto = 0;
                                                                    $idxBank++;
                                                                @endphp
                                                                <table class="table">
                                                                <thead class="table-bordered">
                                                                    <tr>
                                                                        <td colspan="10" class="text-center" style="width: 5%; font-weight: 900;">Bank : {{ $bank['b_code'] }} - {{ $bank['b_name'] }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-center" style="width: 5%;">No</td>
                                                                        <td class="text-center" style="width: 10%;">Order Payment Date</td>
                                                                        <td class="text-center" style="width: 10%;">Estimate Date</td>
                                                                        <td class="text-center" style="width: 15%;">Order Code</td>
                                                                        {{-- <td class="text-center" style="width: 20%;">Cso</td> --}}
                                                                        <td class="text-center" style="width: 20%;">Bank In</td>
                                                                        <td class="text-center" style="width: 20%;">Debit</td>
                                                                        <td class="text-center" style="width: 20%;">Netto Debit</td>
                                                                        <td class="text-center" style="width: 20%;">Card</td>
                                                                        <td class="text-center" style="width: 20%;">Netto Card</td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="table-bordered">
                                                                    @php
                                                                        $subtotalBankIn = 0;
                                                                        $subtotalDebit = 0;
                                                                        $subtotalNettoDebit = 0;
                                                                        $subtotalCard = 0;
                                                                        $subtotalNettoCard = 0;
                                                                    @endphp
                                                                    @foreach ($bank['orders'] as $key => $order)
                                                                        <tr>
                                                                            <td class="text-center">{{ $key+1 }}</td>
                                                                            <td class="text-left">
                                                                                {{ date('d/m/Y', strtotime($order['op_payment_date'])) }}
                                                                            </td>
                                                                            <td class="text-left">
                                                                                {{ $order['op_estimate_transfer_date'] ? date("d/m/Y", strtotime($order['op_estimate_transfer_date'])) : date('d/m/Y', strtotime('+'.$order['estimate_transfer'].' days', strtotime($order['o.created_at']))) }}
                                                                            </td>
                                                                            <td class="text-left">
                                                                                {{ $order['o_code'] }}
                                                                            </td>
                                                                            {{-- <td class="text-left">
                                                                                {{ $order['c_name'] }}
                                                                            </td> --}}
                                                                            @php $totalSubGross+= $order['ts_bank_in']; $totalSubNetto+= $order['ts_bank_in']; @endphp
                                                                            <td class="text-right">{{ number_format($order['ts_bank_in']) }}</td>
                                                                            @php $totalSubGross+= $order['ts_debit']; $totalSubNetto+= $order['ts_netto_debit']; @endphp
                                                                            <td class="text-right">{{ number_format($order['ts_debit']) }}</td>
                                                                            <td class="text-right">{{ number_format($order['ts_netto_debit']) }}</td>
                                                                            @php $totalSubGross+= $order['ts_card']; $totalSubNetto+= $order['ts_netto_card']; @endphp
                                                                            <td class="text-right">{{ number_format($order['ts_card']) }}</td>
                                                                            <td class="text-right">{{ number_format($order['ts_netto_card']) }}</td>
                                                                        </tr>
                                                                        @php
                                                                            $subtotalBankIn += $order['ts_bank_in'];
                                                                            $subtotalDebit += $order['ts_debit'];
                                                                            $subtotalNettoDebit += $order['ts_netto_debit'];
                                                                            $subtotalCard += $order['ts_card'];
                                                                            $subtotalNettoCard += $order['ts_netto_card'];
                                                                        @endphp
                                                                    @endforeach
                                                                    <tr>
                                                                        <td class="text-right" colspan="4" style="font-weight: 600;">Total : </td>
                                                                        <td class="text-right" style="font-weight: 600;">{{ number_format($subtotalBankIn) }}</td>
                                                                        <td class="text-right" style="font-weight: 600;">{{ number_format($subtotalDebit) }}</td>
                                                                        <td class="text-right" style="font-weight: 600;">{{ number_format($subtotalNettoDebit) }}</td>
                                                                        <td class="text-right" style="font-weight: 600;">{{ number_format($subtotalCard) }}</td>
                                                                        <td class="text-right" style="font-weight: 600;">{{ number_format($subtotalNettoCard) }}</td>
                                                                    </tr>
                                                                    @php $totalGross+= $totalSubGross; $totalNetto+= $totalSubNetto; @endphp
                                                                    @if(count($branch['banks']) == $idxBank)
                                                                        <tr>
                                                                            <td colspan="10" style="text-align: center;">
                                                                                <span class="mx-2" style="font-weight: 900; color: #2a6099;">{{ "Total Sale Gross : Rp. ".number_format($totalGross) }}</span>
                                                                                <span class="mx-2" style="font-weight: 900; color: #2a992f;">{{ "Total Sale Netto : Rp. ".number_format($totalNetto) }}</span>
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                </tbody>
                                                                </table>
                                                            @endforeach
                                                        @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- end layout print -->
                        @endif
                    </div>
                 </div>
            </div>
        @endforeach
        @endif
    </div>
</div>

@endsection

@section("script")
<script type="application/javascript">
$(document).ready(function() {
    $(".showPrinted").hide();
    $(document).on("click", "#btn-print", function(){
        $(".showPrinted").show();
        $(".hide-print").hide();

        var printContents = document.getElementById("element-to-print").innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents; 

        window.print();

        document.body.innerHTML = originalContents;

        $(".showPrinted").hide();
        $(".hide-print").show();
        return true;
    });
    $("#success-alert").hide();

    $(document).on("click", "#btn-bulk-print", function(){
        $(".showPrinted").show();
        $(".hide-print").hide();
        var printContents = document.getElementById("element-to-print").innerHTML;
        var originalContents = document.body.innerHTML;

        var bulkContents = []
        for(var i = 0; i < {{count($financialRoutines)}}; i++){
            bulkContents += `
                <div style="page-break-before:always;">
                    ${document.getElementsByClassName("element-to-print-summary-financial-routine")[i].innerHTML}
                </div>
                <div style="page-break-before:always;">
                    ${document.getElementsByClassName("element-to-print-detail-financial-routine")[i].innerHTML}
                </div>
            `
        }

        document.body.innerHTML  = printContents + bulkContents
        window.print();
        document.body.innerHTML = originalContents;
        $(".showPrinted").hide();
        $(".hide-print").show();
        return true;
    });
});

</script>
@endsection