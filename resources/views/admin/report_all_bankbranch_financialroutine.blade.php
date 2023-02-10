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
});

</script>
@endsection