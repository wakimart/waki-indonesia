<?php
$menu_item_page_sub = "order_report";
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
                            href="#total_sale_cso-dd"
                            aria-expanded="false"
                            aria-controls="total_sale_cso-dd">
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
                        Filter By Branch
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
                        Filter By Cso
                    </label>
                    <input name="filter_cso"
                        id="filter_cso"
                        list="data_cso"
                        class="text-uppercase form-control"
                        placeholder="Search Cso"
                        @isset($currentCso)
                            value="{{ $currentCso['code'] . "-" . $currentCso['name'] }}"
                        @endisset
                        required />
                    <span class="invalid-feedback">
                        <strong></strong>
                    </span>

                    <datalist id="data_cso">
                        <select class="form-control">
                            <option value="All Cso"></option>
                            @foreach ($csos as $cso)
                                <option value="{{ $cso['code'] }}-{{ $cso['name'] }}"></option>
                            @endforeach
                        </select>
                    </datalist>
                    <div class="validation"></div>
                </div>
            </div>

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
                        <a href="{{ route('list_total_sale_cso') }}"
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
                        <a href="{{ route('list_total_sale_cso', $exportParameter) }}"
                            target="_blank"
                            class="btn btn-gradient-info m-1">
                            <span class="mdi mdi-file-document"></span>
                            Print Order Report
                        </a>
                        @php 
                            $exportParameter['export_type'] = "xls";
                        @endphp
                        <a href="{{ route('list_total_sale_cso', $exportParameter) }}"
                            class="btn btn-gradient-info m-1">
                            <span class="mdi mdi-file-document"></span>
                            Export Order Report
                        </a>
                    </div>
                </div>
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
                        Total : {{ $countTotalSales }} data
                    </h5>
                    <div class="table-responsive" style="border: 1px solid #ebedf2;">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th> No. </th>
                                    <th class="text-left"> Order Payment Date </th>
                                    <th>Order Code</th>
                                    <th> Bank In</th>
                                    <th> Debit</th>
                                    <th> Netto Debit</th>
                                    <th> Card</th>
                                    <th> Netto Card</th>
                                    <th> Detail </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($total_sales as $key => $total_sale)
                                <tr>
                                    <td class="text-center">{{ $key+1 }}</td>
                                    <td>{{ date("d/m/Y", strtotime($total_sale['op_payment_date'])) }}</td>
                                    <td>{{ $total_sale['code'] }}</td>
                                    <td class="text-right">Rp. {{ number_format($total_sale['sum_ts_bank_in']) }}</td>
                                    <td class="text-right">Rp. {{ number_format($total_sale['sum_ts_debit']) }}</td>
                                    <td class="text-right">Rp. {{ number_format($total_sale['sum_ts_netto_debit']) }}</td>
                                    <td class="text-right">Rp. {{ number_format($total_sale['sum_ts_card']) }}</td>
                                    <td class="text-right">Rp. {{ number_format($total_sale['sum_ts_netto_card']) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('detail_order') }}?code={{ $total_sale['code'] }}">
                                            <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(99, 110, 114);"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                <tr class="text-right">
                                    <th colspan="3">TOTAL SALES</th>
                                    <th>Rp. {{ number_format($total_sales->sum('sum_ts_bank_in')) }}</th>
                                    <th>Rp. {{ number_format($total_sales->sum('sum_ts_debit')) }}</th>
                                    <th>Rp. {{ number_format($total_sales->sum('sum_ts_netto_debit')) }}</th>
                                    <th>Rp. {{ number_format($total_sales->sum('sum_ts_card')) }}</th>
                                    <th>Rp. {{ number_format($total_sales->sum('sum_ts_netto_card')) }}</th>
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

    const filterCso = document.getElementById("filter_cso").value.trim();
    if (filterCso !== "All Cso" && filterCso.length) {
        const getCsoCode = filterCso.split("-");
        urlParamArray.push("filter_cso=" + getCsoCode[0]);
    }

    for (var i = 0; i < urlParamArray.length; i++) {
        if (i === 0) {
            urlParamStr += "?" + urlParamArray[i]
        } else {
            urlParamStr += "&" + urlParamArray[i]
        }
    }

    window.location.href = "{{route('list_total_sale_cso')}}" + urlParamStr;
});
</script>
@endsection
