<?php
$menu_item_page = "stock";
$menu_item_second = "list_stock_in_out";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    .table th img, .table td img {
        border-radius: 0% !important;
    }
    .nav > li > a {
      position: relative;
      display: block;
      padding: 15px 20px;
    }
    .nav-tabs {
      border-bottom: 1px solid #ddd;
      background: #f2f3f2;
      border: 0;
      margin: 0 auto;
      padding: 0px 20px;
    }
    .nav-tabs > li {
      float: left;
      margin-bottom: -1px;
    }
    .nav-tabs > li > a {
      margin-right: 2px;
      line-height: 1.42857143;
      border: 1px solid transparent;
      border-radius: 4px 4px 0 0;
    }
    .nav-tabs > li > a:hover {
      border-color: #eee #eee #ddd;
    }
    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:hover,
    .nav-tabs > li.active > a:focus {
      color: #555;
      cursor: default;
      background-color: #fff;
      border-bottom-color: transparent;
      border: 0;
      padding: 15px 20px;
    }
    .nav-tabs.nav-justified {
      width: 100%;
      border-bottom: 0;
    }
    .nav-tabs.nav-justified > li {
      float: none;
    }
    .nav-tabs.nav-justified > li > a {
      margin-bottom: 5px;
      text-align: center;
    }
    .nav-tabs.nav-justified > .dropdown .dropdown-menu {
      top: auto;
      left: auto;
    }
    .nav-tabs li a:hover {background: #fff;}
    .nav-tabs li.active a {color: #30a5ff;}
    .nav-tabs li a {color: #999;}
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">List Stock In/Out</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#order-dd"
                            aria-expanded="false"
                            aria-controls="order-dd">
                            Stock
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        List Stock In/Out
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12" style="margin-bottom: 0;">
                <div class="col-xs-6 col-sm-4"
                    style="margin-bottom: 0; padding: 0; display: inline-block;">
                    <div class="form-group">
                        <label for="filter_parent_warehouse">Filter Parent Warehouse</label>
                        <select id="filter_parent_warehouse" name="filter_parent_warehouse" class="form-control">
                            <option value="" selected>Choose Parent Warehouse</option>
                            @foreach ($parent_warehouses as $parent_warehouse)
                            <option value="{{ $parent_warehouse->id }}"
                                @if(request()->input('filter_parent_warehouse') ==  $parent_warehouse->id) selected @endif>
                                {{ $parent_warehouse->code }} - {{ $parent_warehouse->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-4"
                    style="margin-bottom: 0; padding: 0; display: inline-block;">
                    <div class="form-group">
                        <label for="filter_date">Filter By Date</label>
                        <input class="form-control"
                            type="date"
                            id="filter_date"
                            name="filter_date"
                            placeholder="Filter By Date"
                            value="{{ isset($_GET['filter_date']) ? $_GET['filter_date'] : '' }}"
                            min="{{ date('Y-m-d', strtotime('-2 months')) }}"
                            required />
                    </div>
                </div>

                <div class="col-xs-6 col-sm-6"
                    style="padding: 0; display: inline-block;">
                    <div class="form-group">
                        <button id="btn-filter"
                            type="button"
                            class="btn btn-gradient-primary m-1"
                            name="filter"
                            value="-">
                            <span class="mdi mdi-filter"></span>
                            Apply Filter
                        </button>
                        <button id="btn-filter_reset"
                            type="button"
                            class="btn btn-gradient-danger m-1"
                            name="filter_reset"
                            value="-">
                            <span class="mdi mdi-refresh"></span>
                            Reset Filter
                        </button>
                        <button id="btn-printStockInOut"
                            type="button"
                            class="btn btn-gradient-info m-1"
                            name="export"
                            data-toggle="modal"
                            data-target="#printStockInOut"
                            value="-">
                            <span class="mdi mdi-file-document"></span>
                            Print Stock In/Out
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <p>
                    @if ($selectedWarehouse)
                    Parent Warehouse : {{ $selectedWarehouse->code }} - {{ $selectedWarehouse->name }}
                    <br />
                    Periode : {{ date('d F Y', strtotime($selectedDate)) }}
                    <br/>
                    @else
                    Please Select Parent Warehouse First
                    @endif
                </p>
            </div>


            <div class="col-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    @php $tabActive = request()->query('tabActive') ?? array_key_first($stockWarehouses) @endphp
                    @foreach ($stockWarehouses as $keyWarehouse => $stockInOuts)
                    <li class="nav-item">
                        <a class="nav-link @if ($tabActive == $keyWarehouse) active @endif" 
                            data-toggle="tab" href="#tab_{{ $keyWarehouse }}">
                            {{ ucwords($keyWarehouse) }}
                        </a>
                    </li>
                    @endforeach
                </ul>

                <div class="tab-content" id="myTabContent">
                    @foreach ($stockWarehouses as $keyWarehouse => $stockInOuts)
                    <div id="tab_{{ $keyWarehouse }}" class="tab-pane fade in @if ($tabActive == $keyWarehouse) active show @endif" style="overflow-x:auto;">
                        <div class="col-12 grid-margin stretch-card" style="padding: 0;">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive" style="border: 1px solid #ebedf2;">
                                        <div class="table-responsive"
                                            style="border: 1px solid #ebedf2;">
                                            <table class="table table-bordered" id="myTable">
                                                <thead>
                                                    <tr>
                                                        <th style="vertical-align: middle;text-align: center;">No.</th>
                                                        <th style="vertical-align: middle;text-align: center;">Code</th>
                                                        <th style="vertical-align: middle;text-align: center;">Name</th>
                                                        <th style="vertical-align: middle;text-align: center;">
                                                            Qty <br> {{ date('d-m-Y', strtotime("-1 days", strtotime($selectedDate))) }}
                                                        </th>
                                                        <th style="vertical-align: middle;text-align: center;">In</th>
                                                        <th style="vertical-align: middle;text-align: center;">Out</th>
                                                        <th style="vertical-align: middle;text-align: center;">Qty Final</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($stockInOuts as $stockInOut)
                                                        @php 
                                                        @endphp
                                                        <tr>
                                                            <td style="vertical-align: top; text-align: right;">{{ $loop->iteration }}</td>
                                                            <td style="vertical-align: top; text-align: left;">{{ $stockInOut->code }}</td>
                                                            <td style="vertical-align: top; text-align: left;">{{ $stockInOut->name }}</td>
                                                            <td style="vertical-align: top; text-align: right;">
                                                                {{ number_format(
                                                                    $stockInOut->sum_current_quantity 
                                                                    + $stockInOut->today_out - $stockInOut->today_in
                                                                    + $stockInOut->selectedDate_out - $stockInOut->selectedDate_in) }}
                                                            </td>
                                                            <td style="vertical-align: top; text-align: right;">
                                                                {{ number_format($stockInOut->selectedDate_in) }}
                                                            </td>
                                                            <td style="vertical-align: top; text-align: right;">
                                                                {{ number_format($stockInOut->selectedDate_out) }}
                                                            </td>
                                                            <td style="vertical-align: top; text-align: right;">
                                                                {{ number_format( $stockInOut->sum_current_quantity + $stockInOut->today_out - $stockInOut->today_in ) }}
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
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal export By Input Warehouse -->
<div class="modal fade"
    id="printStockInOut"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="submitPrint">
                <div class="modal-header">
                    <label>Print Stock In/Out</label>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="filter_print_parent_warehouse">Parent Warehouse</label>
                        <select class="form-control"
                            id="filter_print_parent_warehouse"
                            required>
                            <option value="" selected>Choose Parent Warehouse</option>
                            @foreach ($parent_warehouses as $parent_warehouse)
                                <option value="{{ $parent_warehouse->id }}"
                                    @if(request()->input('filter_parent_warehouse') ==  $parent_warehouse->id) selected @endif>
                                    {{ $parent_warehouse->code }} - {{ $parent_warehouse->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="filter_print_date">Date</label>
                        <input type="date"
                            class="form-control"
                            id="filter_print_date"
                            placeholder="Date"
                            required
                            value="{{ isset($_GET['filter_date']) ? $_GET['filter_date'] : '' }}"
                            min="{{ date('Y-m-d', strtotime('-2 months')) }}" />
                        <div class="validation"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit"
                        class="btn btn-gradient-danger mr-2">
                        Print
                    </button>
                    <button type="button"
                        data-dismiss="modal"
                        class="btn btn-light">
                        No
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal export By Warehouse -->
@endsection

@section("script")
<script type="application/javascript">
function submitDelete(e) {
    document.getElementById("id-delete").value = e.dataset.id;
}
// Print
$("#submitPrint").submit(function(e) {
    e.preventDefault();
    let urlParamStr = "";
    let parentWarehouse = document.getElementById("filter_print_parent_warehouse").value;
    let date = document.getElementById("filter_print_date").value;
    urlParamStr += "filter_parent_warehouse=" + parentWarehouse + "&";
    urlParamStr += "filter_date=" + date + "&";
    window.open("{{ route('list_stock_in_out') }}" + "?print=true&" + urlParamStr, '_blank');
    $("#printStockInOut").modal('hide');
    $(".modal-backdrop").remove();
})
$(document).ready(function (e) {
    $("#btn-filter").click(function (e) {
        var urlParamArray = new Array();
        var urlParamStr = "";
        if ($('#filter_date').val() != "") {
            urlParamArray.push("filter_date=" + $('#filter_date').val());
        }
        if ($('#filter_parent_warehouse').val() != "") {
            urlParamArray.push("filter_parent_warehouse=" + $('#filter_parent_warehouse').val());
        }
        for (var i = 0; i < urlParamArray.length; i++) {
            if (i === 0) {
                urlParamStr += "?" + urlParamArray[i]
            } else {
                urlParamStr += "&" + urlParamArray[i]
            }
        }
        window.location.href = "{{ route('list_stock_in_out') }}" + urlParamStr;
    });
    $("#btn-filter_reset").click(function (e) {
        window.location.href = "{{ route('list_stock_in_out') }}";
    });
});
</script>
@endsection