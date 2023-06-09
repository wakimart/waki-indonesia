<?php
$menu_item_page_sub = "cso_commission";
?>
@extends('admin.layouts.template')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">List Commission</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#order_report_branch-dd"
                            aria-expanded="false"
                            aria-controls="order_report_branch-dd">
                            Cso Commission
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        List Commission
                    </li>
                </ol>
            </nav>
        </div>

        <div class="col-12 grid-margin" style="padding: 0;">
            <div class="col-xs-12 col-sm-12 row">
                <div class="col-xs-6 col-sm-4" style="display: inline-block;">
                    <div class="form-group">
                        <label for="">Month & Year</label>
                        <input type="month"
                            class="form-control"
                            id="filter_month"
                            name="filter_month"
                            value="{{ isset($_GET['filter_month']) ? $_GET['filter_month'] : date('Y-m') }}">
                        <div class="validation"></div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-4" style="display: inline-block;">
                    <div class="form-group">
                        <label for="filter_branch">
                            Filter By Branch
                        </label>
                        <select class="form-control"
                            id="filter_branch"
                            name="filter_branch">
                            <option value="" selected="">
                                Please select branch
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
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 row"
                style="margin: 0; padding: 0;">
                <div class="col-xs-6 col-sm-6"
                    style="padding: 0; display: inline-block;">
                    <div class="form-group">
                        <button id="btn-filter"
                            type="button"
                            class="btn btn-gradient-primary m-1"
                            name="filter"
                            value="-">
                            <span class="mdi mdi-filter"></span> Apply Filter
                        </button>
                        <a href="{{ route('list_cso_commission') }}"
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
                        <a href="{{ route('admin_export_order_report_branch', $exportParameter) }}"
                            target="_blank"
                            class="btn btn-gradient-info m-1">
                            <span class="mdi mdi-file-document"></span>
                            Print Total Sale
                        </a>
                        @php
                            $exportParameter['export_type'] = "xls";
                        @endphp
                        <a href="{{ route('admin_export_order_report_branch', $exportParameter) }}"
                            class="btn btn-gradient-info m-1">
                            <span class="mdi mdi-file-document"></span>
                            Export Total Sale
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
                    @if($CsoCommissions)
                        <div class="mb-3">
                            <h4>Branch : {{ $branches->where('id', $_GET['filter_branch']) ? $branches->where('id', $_GET['filter_branch'])->first()['code'].' - '.$branches->where('id', $_GET['filter_branch'])->first()['name'] : "-"}}</h4>
                        </div>
                        <h5>Date: {{ date("d/m/Y", strtotime($startDate)) }} - {{ date("d/m/Y", strtotime($endDate)) }}</h5>
                        <h5 style="margin-bottom: 0.5em;">
                            Total : {{ count($CsoCommissions) }} data
                        </h5>
                        <div class="table-responsive" style="border: 1px solid #ebedf2;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <th>No. </th>
                                        <th class="text-left">CSO - Name </th>
                                        <th>Bank Account</th>
                                        <th>Commission</th>
                                        <th>Bonus</th>
                                        <th>Tax</th>
                                        <th>Total Commission</th>
                                        <th>Detail/Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $tot_commission = 0;
                                        $tot_pajak = 0;
                                        $tot_result = 0;
                                    @endphp

                                    @foreach ($CsoCommissions as $key => $Cso_Commission)
                                        @php
                                            $tot_commission += $Cso_Commission['commission'];
                                            $tot_pajak += $Cso_Commission['pajak'];
                                            $tot_result += $Cso_Commission['commission'] - $Cso_Commission['pajak'];
                                            if(count($Cso_Commission->orderCommission) > 0){
                                                dd($Cso_Commission->orderCommission);
                                            }
                                        @endphp

                                        <tr>
                                            <td class="text-center">{{ $key+1 }}</td>
                                            <td>{{ $Cso_Commission->cso['code'] }} - {{ $Cso_Commission->cso['name'] }}</td>
                                            <td>{{ $Cso_Commission->cso['no_rekening'] }}</td>
                                            <td class="text-right">Rp. {{ number_format($Cso_Commission['commission']) }}</td>
                                            <td class="text-right">Rp. </td>
                                            <td class="text-right">Rp. {{ number_format($Cso_Commission['pajak']) }}</td>
                                            <td class="text-right">Rp. {{ number_format($Cso_Commission['commission'] - $Cso_Commission['pajak']) }}</td>
                                            <td class="text-center">
                                                <a href="" target="_blank">
                                                    <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(99, 110, 114);"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @php

                                        @endphp
                                    @endforeach
                                    <tr class="text-right">
                                        <th colspan="3">TOTAL SALES</th>
                                        <th>Rp. {{ number_format($tot_commission) }}</th>
                                        <th>Rp. {{ number_format(0) }}</th>
                                        <th>Rp. {{ number_format($tot_pajak) }}</th>
                                        <th>Rp. {{ number_format($tot_result) }}</th>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                            <br/>
                        </div>
                    @else
                        <h2 class="text-info text-center m-0">Please Select Month & Branch Filter First</h2>
                    @endif
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

    if ($('#filter_month').val() != "") {
        urlParamArray.push("filter_month=" + $('#filter_month').val());
    }

    if ($('#filter_branch').val() != "") {
        urlParamArray.push("filter_branch=" + $('#filter_branch').val());
    }

    for (var i = 0; i < urlParamArray.length; i++) {
        if (i === 0) {
            urlParamStr += "?" + urlParamArray[i]
        } else {
            urlParamStr += "&" + urlParamArray[i]
        }
    }

    window.location.href = "{{route('list_cso_commission')}}" + urlParamStr;
});
</script>
@endsection
