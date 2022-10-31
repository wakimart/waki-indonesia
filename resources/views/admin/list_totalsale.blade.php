<?php
$menu_item_page_sub = "order_report";
$menu_item_second_sub = "list_total_sale";
?>
@extends('admin.layouts.template')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">List Order Report</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#total_sale-dd"
                            aria-expanded="false"
                            aria-controls="total_sale-dd">
                            Order Report
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        List Order Report
                    </li>
                </ol>
            </nav>
        </div>

        <div class="col-12 grid-margin" style="padding: 0;">
            <div class="col-xs-12 col-sm-12 row"
                style="margin: 0;padding: 0;">
                <div class="col-xs-6 col-sm-4" style="margin-bottom: 0; padding: 0; display: inline-block">
                    <div class="form-group">
                        <label for="">Start Date</label>
                        <input type="date"
                            class="form-control"
                            id="filter_start_date"
                            name="filter_start_date"
                            value="{{ isset($_GET['filter_start_date']) ? $_GET['filter_start_date'] : '' }}">
                        <div class="validation"></div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-4" style="margin-bottom: 0; padding: 0; display: inline-block">
                    <div class="form-group">
                        <label for="">End Date</label>
                        <input type="date"
                            class="form-control"
                            id="filter_end_date"
                            name="filter_end_date"
                            value="{{ isset($_GET['filter_end_date']) ? $_GET['filter_end_date'] : '' }}">
                        <div class="validation"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-xs-12 col-sm-12 row"
                style="margin: 0; padding: 0;">
                <div class="col-xs-8 col-sm-8"
                    style="padding: 0; display: inline-block;">
                    <label for=""></label>
                    <div class="form-group">
                        <button id="btn-filter"
                            type="button"
                            class="btn btn-gradient-primary m-1"
                            name="filter"
                            value="-">
                            <span class="mdi mdi-filter"></span> Apply Filter
                        </button>
                        <a href="{{ route('list_total_sale') }}"
                            class="btn btn-gradient-danger m-1"
                            value="-">
                            <span class="mdi mdi-filter"></span> Reset Filter
                        </a>
                    </div>
                    <div class="form-group">
                        @php 
                            $exportParameter = request()->input(); 
                        @endphp
                        <a href="{{ route('export_total_sale__bybank', $exportParameter). (Auth::user()->inRole("head-admin") ? '' : '?isPrint=1' ) }}"
                            class="btn btn-gradient-info m-1">
                            <span class="mdi mdi-file-document"></span>
                            {{ Auth::user()->inRole("head-admin") ? 'Export' : 'Print' }} Order Report By Bank
                        </a>
                        <a href="{{ route('export_total_sale_bybranch', $exportParameter). (Auth::user()->inRole("head-admin") ? '' : '?isPrint=1' ) }}"
                            class="btn btn-gradient-info m-1">
                            <span class="mdi mdi-file-document"></span>
                            {{ Auth::user()->inRole("head-admin") ? 'Export' : 'Print' }} Order Report By Branch
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 grid-margin stretch-card" style="padding: 0;">
            <div class="card">
                <div class="card-body">
                    <h5>Date: {{ date("d/m/Y", strtotime($startDate)) }} - {{ date("d/m/Y", strtotime($endDate)) }}</h5>
                    <h5 style="margin-bottom: 0.5em;">
                        Total : {{ $countTotalSales }} data
                    </h5>
                    <div class="table-responsive" style="border: 1px solid #ebedf2;">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th> No. </th>
                                    <th class="text-left"> Branch </th>
                                    <th> Bank In</th>
                                    <th> Debit</th>
                                    <th> Netto Debit</th>
                                    <th> Card</th>
                                    <th> Netto Card</th>
                                    <th> Detail </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalSaleGross = 0;
                                    $totalSaleNetto = 0;
                                @endphp
                                @foreach ($total_sales as $key => $total_sale)
                                @php
                                    $totalSaleGross += $total_sale['sum_ts_bank_in'] + $total_sale['sum_ts_debit'] + $total_sale['sum_ts_card'];
                                    $totalSaleNetto += $total_sale['sum_ts_netto_debit'] + $total_sale['sum_ts_netto_card'];
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $key+1 }}</td>
                                    <td>{{ $total_sale['code'] }} - {{ $total_sale['name'] }}</td>
                                    <td class="text-right">Rp. {{ number_format($total_sale['sum_ts_bank_in']) }}</td>
                                    <td class="text-right">Rp. {{ number_format($total_sale['sum_ts_debit']) }}</td>
                                    <td class="text-right">Rp. {{ number_format($total_sale['sum_ts_netto_debit']) }}</td>
                                    <td class="text-right">Rp. {{ number_format($total_sale['sum_ts_card']) }}</td>
                                    <td class="text-right">Rp. {{ number_format($total_sale['sum_ts_netto_card']) }}</td>
                                    <td class="text-center">
                                        @php
                                            $paramReportBranch = request()->input();
                                            $paramReportBranch['filter_branch'] = $total_sale['id'];
                                        @endphp
                                        <a href="{{ route('list_total_sale_branch', $paramReportBranch) }}" target="_blank">
                                            <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(99, 110, 114);"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                <tr class="text-right">
                                    <th colspan="2">SUB TOTAL</th>
                                    <th>Rp. {{ number_format($total_sales->sum('sum_ts_bank_in')) }}</th>
                                    <th>Rp. {{ number_format($total_sales->sum('sum_ts_debit')) }}</th>
                                    <th>Rp. {{ number_format($total_sales->sum('sum_ts_netto_debit')) }}</th>
                                    <th>Rp. {{ number_format($total_sales->sum('sum_ts_card')) }}</th>
                                    <th>Rp. {{ number_format($total_sales->sum('sum_ts_netto_card')) }}</th>
                                    <td></td>
                                </tr>
                                <tr class="text-right">
                                    <th colspan="2">TOTAL SALES GROSS</th>
                                    <th>Rp. {{ number_format($totalSaleGross) }}</th>
                                </tr>
                                <tr class="text-right">
                                    <th colspan="2">TOTAL CHARGE</th>
                                    <th>Rp. {{ number_format($totalSaleGross - $totalSaleNetto) }}</th>
                                </tr>
                                <tr class="text-right">
                                    <th colspan="2">TOTAL SALES NETTO</th>
                                    <th>Rp. {{ number_format($totalSaleNetto) }}</th>
                                </tr>
                            </tbody>
                        </table>
                        <br/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).on("click", "#btn-filter", function (e) {
    var urlParamArray = new Array();
    var urlParamStr = "";

    if ($('#filter_start_date').val() != "") {
        urlParamArray.push("filter_start_date=" + $('#filter_start_date').val());
    }

    if ($('#filter_end_date').val() != "") {
        urlParamArray.push("filter_end_date=" + $('#filter_end_date').val());
    }

    for (var i = 0; i < urlParamArray.length; i++) {
        if (i === 0) {
            urlParamStr += "?" + urlParamArray[i]
        } else {
            urlParamStr += "&" + urlParamArray[i]
        }
    }

    window.location.href = "{{route('list_total_sale')}}" + urlParamStr;
});
</script>
@endsection
