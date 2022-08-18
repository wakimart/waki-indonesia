<?php
$menu_item_page_sub = "order_report";
$menu_item_second_sub = "list_order_report_cso";
?>
@extends('admin.layouts.template')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">List Order Report By Cso</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#order_report_cso-dd"
                            aria-expanded="false"
                            aria-controls="order_report_cso-dd">
                            Order Report
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        List Order Report By Cso
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

            <div class="col-xs-6 col-sm-3"
                style="padding: 0;display: inline-block;">
                <div class="form-group">
                    <label for="filter_branch">
                        Filter By Team
                    </label>
                    <select class="form-control"
                        id="filter_branch"
                        name="filter_branch">
                        <option value="" selected="">
                            All Branch
                        </option>
                        @foreach ($branches as $branch)
                            @php
                            $selected = "";

                            if (isset($_GET['filter_branch'])) {
                                if ((int) $_GET['filter_branch'] === (int) $branch['id']) {
                                    $selected = "selected";
                                }
                            }
                            @endphp

                            <option {{ $selected }}
                                value="{{ $branch['id'] }}">
                                {{ $branch['code'] }} - {{ $branch['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <div class="validation"></div>
                </div>
            </div>

            <div class="col-xs-6 col-sm-3"
                style="padding: 0;display: inline-block;">
                <div class="form-group">
                    <label for="filter_cso">
                        Filter By CSO
                    </label>
                    <input name="filter_cso"
                        id="filter_cso"
                        list="data_cso"
                        class="text-uppercase form-control"
                        placeholder="Search CSO"
                        @isset($currentCso)
                            value="{{ $currentCso['code'] . "-" . $currentCso['name'] }}"
                        @endisset
                        required />
                    <span class="invalid-feedback">
                        <strong></strong>
                    </span>

                    <datalist id="data_cso">
                        <select class="form-control">
                            <option value="All CSO"></option>
                            @foreach ($csos as $cso)
                                <option value="{{ $cso['code'] }}-{{ $cso['name'] }}"></option>
                            @endforeach
                        </select>
                    </datalist>
                    <div class="validation"></div>
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
                        <a href="{{ route('admin_list_order_report_cso') }}"
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
                        <a href="{{ route('admin_export_order_report_cso', $exportParameter) }}"
                            target="_blank"
                            class="btn btn-gradient-info m-1">
                            <span class="mdi mdi-file-document"></span>
                            Print Order Report
                        </a>
                        @php 
                            $exportParameter['export_type'] = "xls";
                        @endphp
                        <a href="{{ route('admin_export_order_report_cso', $exportParameter) }}"
                            class="btn btn-gradient-info m-1">
                            <span class="mdi mdi-file-document"></span>
                            Export Order Report
                        </a>
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
                    <div class="mb-3">
                        <h4>Branch : {{ $currentBranch ? $currentBranch['code'] . " - " . $currentBranch['name'] : "All Branch" }}</h4>
                        <h4>Cso : {{ $currentCso ? $currentCso['code'] . " - " . $currentCso['name'] : "All Cso" }}</h4>
                    </div>
                    <h5>Date: {{ date("d/m/Y", strtotime($startDate)) }} - {{ date("d/m/Y", strtotime($endDate)) }}</h5>
                    <h5 style="margin-bottom: 0.5em;">
                        Total : {{ $countOrderReports }} data
                    </h5>
                    <div class="table-responsive" style="border: 1px solid #ebedf2;">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th> No. </th>
                                    <th class="text-left"> Order Date </th>
                                    <th class="text-left"> Member Name </th>
                                    <th> Total Payment </th>
                                    <th> Detail </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalPayment = 0;
                                @endphp
                                @foreach ($order_reports as $key => $order_report)
                                <tr>
                                    <td class="text-center">{{ $key+1 }}</td>
                                    <td>{{ date("d/m/Y", strtotime($order_report['orderDate'])) }}</td>
                                    <td>{{ $order_report['name'] }}</td>
                                    <td class="text-right">Rp. {{ number_format($order_report->totalPaymentNya) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('detail_order') }}?code={{ $order_report['code'] }}">
                                            <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(99, 110, 114);"></i>
                                        </a>
                                    </td>
                                </tr>
                                @php
                                    $totalPayment += $order_report->totalPaymentNya;
                                @endphp
                                @endforeach
                                <tr class="text-right">
                                    <th colspan="3">TOTAL SALES</th>
                                    <th>Rp. {{ number_format($totalPayment) }}</th>
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

    if ($('#filter_branch').val() != "") {
        urlParamArray.push("filter_branch=" + $('#filter_branch').val());
    }

    const filterCSO = document.getElementById("filter_cso").value.trim();
    if (filterCSO !== "All CSO" && filterCSO.length) {
        const getCSOCode = filterCSO.split("-");
        urlParamArray.push("filter_cso=" + getCSOCode[0]);
    }

    for (var i = 0; i < urlParamArray.length; i++) {
        if (i === 0) {
            urlParamStr += "?" + urlParamArray[i]
        } else {
            urlParamStr += "&" + urlParamArray[i]
        }
    }

    window.location.href = "{{route('admin_list_order_report_cso')}}" + urlParamStr;
});
</script>
@endsection
