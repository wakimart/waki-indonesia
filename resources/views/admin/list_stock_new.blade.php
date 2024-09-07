<?php
$menu_item_page = "stock";
$menu_item_second = "list_stock";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="{{ asset("css/lib/select2/select2-bootstrap4.min.css") }}" />
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
            <h3 class="page-title">List Stock</h3>
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
                        List
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12" style="margin-bottom: 0;">
                <div class="col-xs-6 col-sm-3"
                    style="display: inline-block;">
                    <div class="form-group">
                        <label for="filter_date">Filter By Date</label>
                        <input class="form-control"
                            type="date"
                            id="filter_date"
                            name="filter_date"
                            placeholder="Filter By Date"
                            value="{{ isset($_GET['filter_date']) ? $_GET['filter_date'] : '' }}"
                            required />
                    </div>
                </div>

                <div class="col-xs-6 col-sm-3"
                    style="display: inline-block;">
                    <div class="form-group">
                        <label for="filter_code">Filter By Code</label>
                        <input class="form-control"
                            id="filter_code"
                            name="filter_code"
                            placeholder="Filter By Code"
                            value="{{ isset($_GET['filter_code']) ? $_GET['filter_code'] : '' }}" />
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3"
                    style="display: inline-block;">
                    <div class="form-group" style="position: relative; top: 1rem;">
                        <label for="filter_product_code">
                            Filter By Product Code
                        </label>
                        {{-- <input class="form-control"
                            id="filter_product_code"
                            name="filter_product_code"
                            placeholder="Filter By Product Code"
                            value="{{ isset($_GET['filter_product_code']) ? $_GET['filter_product_code'] : '' }}" /> --}}
                        <select class="form-control"
                            id="filter_product_code"
                            name="filter_product_code"
                            required>
                            <option selected disabled value="">
                                All Product
                            </option>
                            @foreach($products as $product)
                                <option {{ isset($_GET['filter_product_code']) ? ($_GET['filter_product_code'] == $product->code ? 'selected' : '') : '' }} value="{{ $product->code }}">
                                    {{ $product->code }} 
                                    - ({{ $product->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>                
                <div class="col-xs-6 col-sm-4"
                    style="display: inline-block;">
                    <div class="form-group">
                        <label for="filter_from_warehouse">Filter From Warehouse</label>
                        <select id="filter_from_warehouse" name="filter_from_warehouse" class="form-control">
                            <option value="" selected>Choose From Warehouse</option>
                            @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}"
                                @if(request()->input('filter_from_warehouse') ==  $warehouse->id) selected @endif>
                                {{ $warehouse->code }} - {{ $warehouse->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>                
                <div class="col-xs-6 col-sm-4"
                    style="display: inline-block;">
                    <div class="form-group">
                        <label for="filter_to_warehouse">Filter To Warehouse</label>
                        <select id="filter_to_warehouse" name="filter_to_warehouse" class="form-control">
                            <option value="" selected>Choose To Warehouse</option>
                            @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}"
                                @if(request()->input('filter_to_warehouse') ==  $warehouse->id) selected @endif>
                                {{ $warehouse->code }} - {{ $warehouse->name }}
                            </option>
                            @endforeach
                        </select>
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
                    </div>
                </div>
            </div>


            <div class="col-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    @php 
                        $tabActive = request()->query('tabActive') ?? "in";
                        $tabActive = isset($_GET['status']) ? 'In_Pending' : 'in';
                    @endphp
                    @foreach ($stockTypes as $keyType => $stockInOuts)
                    <li class="nav-item">
                        <a class="nav-link @if ($tabActive == $keyType) active @endif"
                            data-toggle="tab" href="#tab_{{ $keyType }}">
                            {{ ucwords($keyType) }} ({{ $stockInOuts->total() }})
                        </a>
                    </li>
                    @endforeach
                </ul>

                <div class="tab-content" id="myTabContent">
                    @foreach ($stockTypes as $keyType => $stockInOuts)
                        @if($keyType == 'In_Pending')
                            <div id="tab_{{ $keyType }}" class="tab-pane fade in @if ($tabActive == $keyType) active show @endif" style="overflow-x:auto;">
                                <div class="col-12 grid-margin stretch-card" style="padding: 0;">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 style="margin-bottom: 0.5em;">Total : {{$stockInOuts->total()}} data</h5>
                                            <form>
                                                <div class="row">
                                                    <div class="col-12" style="margin-bottom: 0;">
                                                        <div class="col-xs-3 col-sm-3"
                                                            style="display: inline-block;">
                                                            <div class="form-group">
                                                                <label for="filter_stock_connection">Filter By Status</label>
                                                                <select id="filter_stock_connection" name="filter_stock_connection" class="form-control">
                                                                    <option value="outstanding" {{ isset($_GET['status']) ? ($_GET['status'] == 'outstanding' ? 'selected' : '') : '' }}>Outstanding</option>
                                                                    <option value="confirm" {{ isset($_GET['status']) ? ($_GET['status'] == 'confirm' ? 'selected' : '') : '' }}>Confirm</option>
                                                                    <option value="cancel" {{ isset($_GET['status']) ? ($_GET['status'] == 'cancel' ? 'selected' : '') : '' }}>Cancel</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-3 col-sm-3"
                                                            style="display: inline-block;">
                                                            <div class="form-group">
                                                                <button id="btn-filter-pending"
                                                                    type="button"
                                                                    class="btn btn-gradient-primary m-1"
                                                                    name="filter"
                                                                    value="-">
                                                                    <span class="mdi mdi-filter"></span>
                                                                    Apply Filter
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <div class="table-responsive" style="border: 1px solid #ebedf2;">
                                                <div class="table-responsive"
                                                    style="border: 1px solid #ebedf2;">
                                                    <table class="table table-bordered" id="myTable">
                                                        <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <th>Code OUT {{ isset($_GET['status']) ? ($_GET['status'] == 'confirm' ? '- Code IN' : '') : '' }}</th>
                                                                <th class="text-center">Warehouse (From-To)</th>
                                                                <th>Out Date</th>
                                                                <th>Status</th>
                                                                <th colspan="2" class="text-center">
                                                                    View
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($stockInOuts as $stockInOut)
                                                                @php
                                                                    $count_sioProduct = $stockInOut->stockOut->stockInOutProduct->count();
                                                                @endphp
                                                                <tr>
                                                                    <td class="text-center">
                                                                        {{ $loop->iteration + $stockInOuts->firstItem() - 1 }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $stockInOut->stockOut->code }} {{ isset($_GET['status']) ? ($_GET['status'] == 'confirm' ? '=> '. $stockInOut->stockIn->code : '') : '' }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <b>{{ $stockInOut->stockOut->warehouseFrom['code'] }}</b>
                                                                        =>
                                                                        <b>{{ $stockInOut->stockOut->warehouseTo['code'] }}</b>
                                                                    </td>
                                                                    <td>
                                                                        {{ date("d-m-Y", strtotime($stockInOut->stockOut->date)) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ strtoupper($stockInOut['status']) }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if (Gate::check('detail-stock_in_out'))
                                                                        <a href="{{ route('detail_stock_in_out', ['code' => $stockInOut->stockOut->code, 'id' => $stockInOut->stockOut->id]) }}">
                                                                            <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(76 172 245);"></i>
                                                                            {!! isset($_GET['status']) ? ($_GET['status'] == 'confirm' ? '<p>OUT</p>' : '') : '' !!}
                                                                        </a>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @php
                                                                            $isStatusFilter = isset($_GET['status']) ? ($_GET['status'] == 'confirm' ? true : false) : false;
                                                                        @endphp
                                                                        @if($isStatusFilter)
                                                                            <a href="{{ route('detail_stock_in_out', ['code' => $stockInOut->stockIn->code, 'id' => $stockInOut->stockIn->id]) }}">
                                                                                <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(76 172 245);"></i>
                                                                                <p>IN</p>
                                                                            </a>
                                                                        @else
                                                                            <a href="{{ route('add_stock_in', ['code' => $stockInOut->stockOut->code, 'id' => $stockInOut->stockOut->id]) }}">
                                                                                <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                                                            </a>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <br>
                                                </div>
                                                {{ $stockInOuts->appends(request()->input())->appends(['tabActive' => $keyType])->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div id="tab_{{ $keyType }}" class="tab-pane fade in @if ($tabActive == $keyType) active show @endif" style="overflow-x:auto;">
                                <div class="col-12 grid-margin stretch-card" style="padding: 0;">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 style="margin-bottom: 0.5em;">Total : {{$stockInOuts->total()}} data</h5>
                                            <div class="table-responsive" style="border: 1px solid #ebedf2;">
                                                <div class="table-responsive"
                                                    style="border: 1px solid #ebedf2;">
                                                    <table class="table table-bordered" id="myTable">
                                                        <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <th>Code</th>
                                                                <th class="text-center">Warehouse (From-To)</th>
                                                                <th>Date</th>
                                                                <th>Product</th>
                                                                <th>Quantity</th>
                                                                <th colspan="3" class="text-center">
                                                                    View/Edit/Delete
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($stockInOuts as $stockInOut)
                                                                @php
                                                                    $count_sioProduct = $stockInOut->stockInOutProduct->count();
                                                                @endphp
                                                                <tr>
                                                                    <td class="text-center" rowspan="{{ $count_sioProduct }}">
                                                                        {{ $loop->iteration + $stockInOuts->firstItem() - 1 }}
                                                                    </td>
                                                                    <td rowspan="{{ $count_sioProduct }}">
                                                                        {{ $stockInOut->code }}
                                                                    </td>
                                                                    <td class="text-center" rowspan="{{ $count_sioProduct }}">
                                                                        <b>{{ $stockInOut->warehouseFrom['code'] }}</b>
                                                                        =>
                                                                        <b>{{ $stockInOut->warehouseTo['code'] }}</b>
                                                                    </td>
                                                                    <td rowspan="{{ $count_sioProduct }}">
                                                                        {{ date("d-m-Y", strtotime($stockInOut->date)) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $stockInOut->stockInOutProduct[0]->product['code'] }}
                                                                    </td>
                                                                    <td class="text-right">
                                                                        {{ $stockInOut->stockInOutProduct[0]->quantity }}
                                                                    </td>
                                                                    <td class="text-center" rowspan="{{ $count_sioProduct }}">
                                                                        @if (Gate::check('detail-stock_in_out'))
                                                                        <a href="{{ route('detail_stock_in_out', ['code' => $stockInOut->code, 'id' => $stockInOut->id]) }}">
                                                                            <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(76 172 245);"></i>
                                                                        </a>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center" rowspan="{{ $count_sioProduct }}">
                                                                        @if ($stockInOut->type == "in")
                                                                            @if (Gate::check('edit-stock_in'))
                                                                            <a href="{{ route('edit_stock_in', ['code' => $stockInOut->code, 'id' => $stockInOut->id]) }}">
                                                                                <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                                                            </a>
                                                                            @endif
                                                                        @else
                                                                            @if (Gate::check('edit-stock_out'))
                                                                            <a href="{{ route('edit_stock_out', ['code' => $stockInOut->code, 'id' => $stockInOut->id]) }}">
                                                                                <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                                                            </a>
                                                                            @endif
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center" rowspan="{{ $count_sioProduct }}">
                                                                        @if (Gate::check('delete-stock_in_out'))
                                                                        <a class="btn-delete disabled"
                                                                            data-toggle="modal"
                                                                            href="#deleteDoModal"
                                                                            onclick="submitDelete(this)"
                                                                            data-id="{{ $stockInOut->id }}">
                                                                            <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                                        </a>
                                                                        @endif
                                                                    </td>
                                                                </tr>

                                                                @if ($count_sioProduct > 1)
                                                                    @for ($i = 1; $i < $count_sioProduct; $i++)
                                                                        <tr>
                                                                            <td>
                                                                                {{ $stockInOut->stockInOutProduct[$i]->product['code'] }}
                                                                            </td>
                                                                            <td class="text-right">
                                                                                {{ $stockInOut->stockInOutProduct[$i]->quantity }}
                                                                            </td>
                                                                        </tr>
                                                                    @endfor
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <br>
                                                </div>
                                                {{ $stockInOuts->appends(request()->input())->appends(['tabActive' => $keyType])->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Delete -->
<div class="modal fade"
    id="deleteDoModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 style="text-align:center;">
                    Are you sure to delete this history stock?
                </h5>
            </div>
            <div class="modal-footer">
                <form id="frmDelete"
                    method="post"
                    action="{{ route('delete_stock_in_out') }}">
                    @csrf
                    <input type="hidden" name="stock_id" id="id-delete" />
                    <button type="submit"
                        class="btn btn-gradient-danger mr-2">
                        Yes
                    </button>
                </form>
                <button class="btn btn-light" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Delete -->
@endsection

@section("script")
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
<script type="application/javascript">
function submitDelete(e) {
    document.getElementById("id-delete").value = e.dataset.id;
}
$(document).ready(function (e) {
    $("#filter_product_code").select2({
        theme: "bootstrap4",
    });

    $("#btn-filter, #btn-filter-pending").click(function (e) {
        var urlParamArray = new Array();
        var urlParamStr = "";
        if ($('#filter_date').val() != "") {
            urlParamArray.push("filter_date=" + $('#filter_date').val());
        }
        if ($('#filter_code').val() != "") {
            urlParamArray.push("filter_code=" + $('#filter_code').val());
        }
        if ($('#filter_product_code').val() != "") {
            urlParamArray.push("filter_product_code=" + $('#filter_product_code').val());
        }
        if ($('#filter_from_warehouse').val() != "") {
            urlParamArray.push("filter_from_warehouse=" + $('#filter_from_warehouse').val());
        }
        if ($('#filter_to_warehouse').val() != "") {
            urlParamArray.push("filter_to_warehouse=" + $('#filter_to_warehouse').val());
        }
        if ($('#filter_stock_connection').val() != "" && $(this)[0].id == "btn-filter-pending") {
            urlParamArray.push("status=" + $('#filter_stock_connection').val());
        }
        for (var i = 0; i < urlParamArray.length; i++) {
            if (i === 0) {
                urlParamStr += "?" + urlParamArray[i]
            } else {
                urlParamStr += "&" + urlParamArray[i]
            }
        }
        window.location.href = "{{ route('list_stock') }}" + urlParamStr;
    });
    $("#btn-filter_reset").click(function (e) {
        window.location.href = "{{ route('list_stock') }}";
    });
});
</script>
@endsection
