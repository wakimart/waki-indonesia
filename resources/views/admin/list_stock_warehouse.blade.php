<?php
$menu_item_page = "stock";
$menu_item_second = "list_stock_warehouse";
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
            <h3 class="page-title">List Stock</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#order-dd"
                            aria-expanded="false"
                            aria-controls="order-dd">
                            Stock Warehouse
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        List Stock
                    </li>
                </ol>
            </nav>
        </div>

        <form id="form-search" method="GET" action="{{ route("list_warehouse") }}"></form>

        <div class="row">
            <div class="col-12" style="margin-bottom: 0;">
                <div class="col-xs-6 col-sm-4"
                    style="margin-bottom: 0; padding: 0; display: inline-block;">
                    <div class="form-group">
                        <label for="">Search By Name</label>
                        <input class="form-control"
                            id="filter_name"
                            name="filter_name"
                            placeholder="Search By Name"
                            form="form-search"
                            value="{{ $_GET['filter_name'] ?? "" }}" />
                    </div>
                </div>

                <div class="col-xs-6 col-sm-4"
                    style="margin-bottom: 0; padding: 0; display: inline-block;">
                    <div class="form-group">
                        <label for="">Search By Code</label>
                        <input class="form-control"
                            id="filter_code"
                            name="filter_code"
                            placeholder="Search By Code"
                            form="form-search"
                            value="{{ $_GET['filter_code'] ?? "" }}" />
                    </div>
                </div>

                <div class="col-xs-6 col-sm-6"
                    style="padding: 0; display: inline-block;">
                    <div class="form-group">
                        <button class="btn btn-gradient-primary m-1"
                            type="submit"
                            form="form-search">
                            <span class="mdi mdi-filter"></span>
                            Apply Filter
                        </button>
                        <button id="btn-filter_reset"
                            type="button"
                            class="btn btn-gradient-danger m-1">
                            <span class="mdi mdi-refresh"></span>
                            Reset Filter
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h5 style="margin-bottom: 0.5em;">
                            Total: {{ $stocks->total() }}
                        </h5>
                        <div class="table-responsive"
                            style="border: 1px solid #ebedf2;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Remaining Stock</th>
                                        <th colspan="2" class="text-center">
                                            Edit/Delete
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stocks as $stock)
                                        <tr>
                                            <td class="text-right">
                                                {{ ++$i }}
                                            </td>
                                            <td>{{ $stock->product['code'] }}</td>
                                            <td>{{ $stock->product['name'] }}</td>
                                            <td></td>
                                            <td class="text-center">
                                                <a href="">
                                                    <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a class="btn-delete disabled"
                                                    data-toggle="modal"
                                                    href="#deleteDoModal"
                                                    onclick="submitDelete(this)"
                                                    data-id="{{ $stock->id }}">
                                                    <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            {{ $stocks->links() }}
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
                    Are you sure to delete this warehouse?
                </h5>
            </div>
            <div class="modal-footer">
                <form method="post"
                    action="">
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
@endsection

@section("script")
<script type="application/javascript">
function submitDelete(e) {
    document.getElementById("id-delete").value = e.dataset.id;
}

$(document).ready(function (e) {
    $("#btn-filter_reset").click(function (e) {
        window.location.href = "{{ route('list_stock_warehouse') }}";
    });
});
</script>
@endsection
