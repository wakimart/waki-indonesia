<?php
$menu_item_page = "history_stock";
$menu_item_second = "list_history_stock";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    .table th img, .table td img {
        border-radius: 0% !important;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">List History Stock</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#order-dd"
                            aria-expanded="false"
                            aria-controls="order-dd">
                            History Stock
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
                <div class="col-xs-6 col-sm-4"
                    style="margin-bottom: 0; padding: 0; display: inline-block;">
                    <div class="form-group">
                        <label for="filter_type">Filter By Type</label>
                        <select id="filter_type"
                            class="form-control"
                            name="filter_type">
                            <?php
                            $filter_type = isset($_GET["filter_type"]);
                            ?>
                            <option value=""
                                {!! !$filter_type ? "selected" : "" !!}>
                                All Type
                            </option>
                            <option value="in"
                                {!! $filter_type && $_GET["filter_type"] === "in" ? "selected" : "" !!}>
                                In
                            </option>
                            <option value="out"
                                {!! $filter_type && $_GET["filter_type"] === "out" ? "selected" : "" !!}>
                                Out
                            </option>
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
                            required />
                    </div>
                </div>
                <div class="col-xs-6 col-sm-4"
                    style="margin-bottom: 0; padding: 0; display: inline-block;">
                    <div class="form-group">
                        <label for="filter_code">Filter By Code</label>
                        <input class="form-control"
                            id="filter_code"
                            name="filter_code"
                            placeholder="Filter By Code"
                            value="{{ isset($_GET['filter_code']) ? $_GET['filter_code'] : '' }}">
                    </div>
                </div>
                <div class="col-xs-6 col-sm-4"
                    style="margin-bottom: 0; padding: 0; display: inline-block;">
                    <div class="form-group">
                        <label for="filter_stock_name">
                            Filter By Stock Name
                        </label>
                        <input class="form-control"
                            id="filter_stock_name"
                            name="filter_stock_name"
                            placeholder="Filter By Stock Name"
                            value="{{ isset($_GET['filter_stock_name']) ? $_GET['filter_stock_name'] : '' }}">
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

                <div class="col-xs-6 col-sm-6"
                    style="padding: 0; display: inline-block;">
                    <div class="form-group">
                        <button id="btn-exportByWarehouse"
                            type="button"
                            class="btn btn-gradient-info m-1"
                            name="export"
                            data-toggle="modal"
                            data-target="#exportByWarehouse"
                            value="-">
                            <span class="mdi mdi-file-document"></span>
                            Export XLS by Warehouse
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <?php
                        $pagination = $historystocks;
                        $historystocks = $historystocks->groupBy("code");
                        ?>
                        <h5 style="margin-bottom: 0.5em;">
                            Total: {{ $historystocks->count() }} data
                        </h5>
                        <div class="table-responsive"
                            style="border: 1px solid #ebedf2;">
                            <table class="table table-bordered" id="myTable">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Code</th>
                                        <th>Date</th>
                                        <th>Warehouse</th>
                                        <th>Type</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th colspan="3" class="center">
                                            View/Edit/Delete
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($historystocks as $code => $historycode)
                                        <tr>
                                            <td class="text-right" rowspan="{{ sizeof($historycode) }}">
                                                {{ ++$i }}
                                            </td>
                                            <td rowspan="{{ sizeof($historycode) }}">
                                                {{ $code }}
                                            </td>
                                            <td rowspan="{{ sizeof($historycode) }}">
                                                {{ date("d-m-Y", strtotime($historycode[0]->date)) }}
                                            </td>
                                            <td class="text-center" rowspan="{{ sizeof($historycode) }}">
                                                <b>{{ $historycode[0]->stock->warehouse['code'] }}</b>
                                                <br>
                                                ({{ $historycode[0]->stock->warehouse['name'] }})
                                            </td>
                                            <td class="text-center" rowspan="{{ sizeof($historycode) }}">
                                                {{ ucfirst($historycode[0]->type) }}
                                            </td>
                                            <td>
                                                {{ $historycode[0]->stock->product['code'] }}
                                            </td>
                                            <td>
                                                {{ $historycode[0]->quantity }}
                                            </td>
                                            <td class="text-center" rowspan="{{ sizeof($historycode) }}">
                                                <a href="{{ route('detail_history_stock', ['code' => $historycode[0]['code']]) }}">
                                                    <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(76 172 245);"></i>
                                                </a>
                                            </td>
                                            <td class="text-center" rowspan="{{ sizeof($historycode) }}">
                                                @if ($historycode[0]->type == "in")
                                                    <a href="{{ route('edit_history_in', ['code' => $historycode[0]['code']]) }}">
                                                        <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('edit_history_out', ['code' => $historycode[0]['code']]) }}">
                                                        <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="text-center" rowspan="{{ sizeof($historycode) }}">
                                                <a class="btn-delete disabled"
                                                    data-toggle="modal"
                                                    href="#deleteDoModal"
                                                    onclick="submitDelete(this)"
                                                    data-id="{{ $historycode[0]->id }}">
                                                    <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        @if (sizeof($historycode) > 1)
                                            @for ($i = 1; $i < sizeof($historycode); $i++)
                                                <tr>
                                                    <td>
                                                        {{ $historycode[$i]->stock->product['code'] }}
                                                    </td>
                                                    <td>
                                                        {{ $historycode[$i]->quantity }}
                                                    </td>
                                                </tr>
                                            @endfor
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            {{ $pagination->links() }}
                        </div>
                    </div>
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
                    action="{{ route('delete_personal_homecare') }}">
                    @csrf
                    <input type="hidden" name="id" id="id-delete" />
                    <button type="submit"
                        class="btn btn-gradient-danger mr-2">
                        Yes
                    </button>
                </form>
                <button class="btn btn-light">No</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Delete -->

<!-- Modal export By Input Warehouse -->
<div class="modal fade"
    id="exportByWarehouse"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label>Export History Stock</label>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Tanggal Awal</label>
                    <input type="date"
                        class="form-control"
                        name="date"
                        id="filter_startDate"
                        placeholder="Awal Tanggal"
                        required
                        data-msg="Mohon Isi Tanggal"
                        onload="getDate()" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <label>Tanggal Akhir</label>
                    <input type="date"
                        class="form-control"
                        name="date"
                        id="filter_endDate"
                        placeholder="Akhir Tanggal"
                        required
                        data-msg="Mohon Isi Tanggal"
                        onload="getDate()" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <label for="filter_inputByWarehouse">Pick Warehouse</label>
                    <select class="form-control"
                        name="warehouse_id"
                        id="filter_inputByWarehouse"
                        required>
                        <option disabled selected>
                            Select Warehouse
                        </option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">
                                {{ $warehouse->code }} - {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                @csrf
                <input type="hidden"
                    id="hiddenInput"
                    name="cancel"
                    value="1" />
                <button type="submit"
                    data-dismiss="modal"
                    id="btn-exportByWarehouse"
                    class="btn btn-gradient-danger mr-2"
                    name="id"
                    onclick="submitExportXLSByInputWarehouse()"
                    value="-">
                    Export
                </button>
                <button type="button"
                    data-dismiss="modal"
                    class="btn btn-light">
                    No
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Date Picker export By Input Xls -->
@endsection

@section("script")
<script type="application/javascript">
function submitDelete(e) {
    document.getElementById("id-delete").value = e.dataset.id;
}

// Export XLS By Input Warehouse
function submitExportXLSByInputWarehouse() {
    let urlParamStr = "";

    let startDate = document.getElementById("filter_startDate").value;
    let endDate = document.getElementById("filter_endDate").value;
    let inputWarehouse = document.getElementById("filter_inputByWarehouse").value;

    urlParamStr += "filter_startDate=" + startDate + "&";
    urlParamStr += "filter_endDate=" + endDate + "&";
    urlParamStr += "filter_inputByWarehouse=" + inputWarehouse;

    window.location.href = "{{ route('historystock_export-to-xls-by-warehouse') }}" + "?" + urlParamStr;
}

$(document).ready(function (e) {
    $("#btn-filter").click(function (e) {
        var urlParamArray = new Array();
        var urlParamStr = "";

        if ($('#filter_type').val() != "") {
            urlParamArray.push("filter_type=" + $('#filter_type').val());
        }

        if ($('#filter_date').val() != "") {
            urlParamArray.push("filter_date=" + $('#filter_date').val());
        }

        if ($('#filter_code').val() != "") {
            urlParamArray.push("filter_code=" + $('#filter_code').val());
        }

        if ($('#filter_stock_name').val() != "") {
            urlParamArray.push("filter_stock_name=" + $('#filter_stock_name').val());
        }

        for (var i = 0; i < urlParamArray.length; i++) {
            if (i === 0) {
                urlParamStr += "?" + urlParamArray[i]
            } else {
                urlParamStr += "&" + urlParamArray[i]
            }
        }

        window.location.href = "{{ route('list_history_stock') }}" + urlParamStr;
    });

    $("#btn-filter_reset").click(function (e) {
        window.location.href = "{{ route('list_history_stock') }}";
    });
});
</script>
@endsection
