<?php
$menu_item_page = "order_report";
$menu_item_second = "list_order_report";
?>
@extends('admin.layouts.template')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">List Total Sale</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#order_report-dd"
                            aria-expanded="false"
                            aria-controls="order_report-dd">
                            Order Report
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        List Total Sale
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

            {{-- <div class="col-xs-12 col-sm-12 row"
                style="margin: 0;padding: 0;">
                <div class="col-xs-6 col-sm-4"
                    style="padding: 0;display: inline-block;">
                    <div class="form-group">
                        <label for="filter_string">
                            Filter by Name, Phone, or Code
                        </label>
                        <input type="text"
                            class="form-control"
                            placeholder="Name, Phone, or Code"
                            value="{{ $_GET["filter_string"] ?? "" }}"
                            id="filter_string"
                            name="filter_string">
                    </div>
                </div>
            </div> --}}

            <div class="col-xs-12 col-sm-12 row"
                style="margin: 0; padding: 0;">
                <div class="col-xs-6 col-sm-6"
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
                        <a href="{{ route('admin_list_order_report') }}"
                            class="btn btn-gradient-danger m-1"
                            value="-">
                            <span class="mdi mdi-filter"></span> Reset Filter
                        </a>
                    </div>
                    <div class="form-group">
                        @php
                            $exportParameter = request()->input();
                            $exportParameter['export_type'] = "print";
                        @endphp
                        <a href="{{ route('admin_export_order_report', $exportParameter) }}"
                            target="_blank"
                            class="btn btn-gradient-info m-1">
                            <span class="mdi mdi-file-document"></span>
                            Print Total Sale
                        </a>
                        @if(Auth::user()->inRole("head-admin"))
                            @php
                                $exportParameter['export_type'] = "xls";
                            @endphp
                            <a href="{{ route('admin_export_order_report', $exportParameter) }}"
                                class="btn btn-gradient-info m-1">
                                <span class="mdi mdi-file-document"></span>
                                Export Total Sale
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12"
                style="padding: 0; border: 1px solid #ebedf2;">
                <div class="col-xs-12 col-sm-11 col-md-6 table-responsive"
                    id="calendarContainer"
                    style="padding: 0; float: left;"></div>
                <div class="col-xs-12 col-sm-11 col-md-6"
                    id="organizerContainer"
                    style="padding: 0; float: left;"></div>
            </div>
        </div>

        <div class="col-12 grid-margin stretch-card" style="padding: 0;">
            <div class="card">
                <div class="card-body">
                    <h5>Date: {{ date("d/m/Y", strtotime($startDate)) }} - {{ date("d/m/Y", strtotime($endDate)) }}</h5>
                    <h5 style="margin-bottom: 0.5em;">
                        Total : {{ $countOrderReports }} data
                    </h5>
                    <div class="table-responsive" style="border: 1px solid #ebedf2;">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th> No. </th>
                                    <th class="text-left"> Branch </th>
                                    <th> Sales Until Yesterday</th>
                                    <th> Sales Today </th>
                                    <th> Total Sales </th>
                                    <th> Detail </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_sale_untilYesterday = 0;
                                    $total_sale_today = 0;
                                    $totalSales = 0;
                                @endphp
                                @foreach ($order_reports as $key => $order_report)
                                <tr>
                                    <td class="text-center">{{ $key+1 }}</td>
                                    <td>{{ $order_report['code'] }} - {{ $order_report['name'] }}</td>
                                    <td class="text-right">Rp. {{ number_format($order_report['total_sale_untilYesterday']) }}</td>
                                    <td class="text-right">Rp. {{ number_format($order_report['total_sale_today']) }}</td>
                                    <td class="text-right">Rp. {{ number_format($order_report['total_sale_untilYesterday'] + $order_report['total_sale_today']) }}</td>
                                    <td class="text-center">
                                        @php
                                            $paramReportBranch = request()->input();
                                            $paramReportBranch['filter_branch'] = $order_report['id'];
                                        @endphp
                                        <a href="{{ route('admin_list_order_report_branch', $paramReportBranch) }}" target="_blank">
                                            <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(99, 110, 114);"></i>
                                        </a>
                                    </td>
                                </tr>
                                @php
                                    $total_sale_untilYesterday += $order_report['total_sale_untilYesterday'];
                                    $total_sale_today += $order_report['total_sale_today'];
                                    // $totalSales += ($order_report['total_sale_untilYesterday'] + $order_report['total_sale_today']);
                                @endphp
                                @endforeach
                                <tr class="text-right">
                                    <th colspan="2">TOTAL SALES</th>
                                    <th>Rp. {{ number_format($total_sale_untilYesterday) }}</th>
                                    <th>Rp. {{ number_format($total_sale_today) }}</th>
                                    <th>Rp. {{ number_format($total_sale_untilYesterday + $total_sale_today) }}</th>
                                    <td></td>
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

    window.location.href = "{{route('admin_list_order_report')}}" + urlParamStr;
});
</script>
@endsection
