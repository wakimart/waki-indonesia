<?php
$menu_item_page = "public_homecare";
$menu_item_second = "list_all_public_homecare";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
    integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
<style type="text/css">
    .center {text-align: center;}
    .right {text-align: right;}

    .table th img, .table td img {
        border-radius: 0% !important;
    }
    .select2-selection__rendered {
        line-height: 45px !important;
    }
    .select2-container .select2-selection--single {
        height: 45px !important;
    }
    .select2-container--default
    .select2-selection--single
    .select2-selection__arrow {
        top: 10px;
    }

</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">List Public Homecare</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#order-dd"
                            aria-expanded="false"
                            aria-controls="order-dd">
                            Public Homecare
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        List All
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12" style="margin-bottom: 0;">
                <div class="col-xs-6 col-sm-4" style="margin-bottom: 0; padding: 0; display: inline-block">
                    <div class="form-group">
                        <label for="">Search By Status</label>
                        <select id="filter_status"
                            class="form-control"
                            name="filter_status">
                            <?php
                            $filter_status = isset($_GET["filter_status"]);
                            ?>
                            <option value=""
                                {!! !$filter_status ? "selected" : "" !!}>
                                All Status
                            </option>
                            <option value="new"
                                {!! $filter_status && $_GET["filter_status"] === "new" ? "selected" : "" !!}>
                                New
                            </option>
                            <option value="verified"
                                {!! $filter_status && $_GET["filter_status"] === "verified" ? "selected" : "" !!}>
                                Verified
                            </option>
                            <option value="approve_out"
                                {!! $filter_status && $_GET["filter_status"] === "approve_out" ? "selected" : "" !!}>
                                Approve Out
                            </option>
                            <option value="process"
                                {!! $filter_status && $_GET["filter_status"] === "process" ? "selected" : "" !!}>
                                On Process
                            </option>
                            <option value="waiting_in"
                                {!! $filter_status && $_GET["filter_status"] === "waiting_in" ? "selected" : "" !!}>
                                Need to Approve In
                            </option>
                            <option value="done"
                                {!! $filter_status && $_GET["filter_status"] === "done" ? "selected" : "" !!}>
                                Done
                            </option>
                            <option value="rejected"
                                {!! $filter_status && $_GET["filter_status"] === "rejected" ? "selected" : "" !!}>
                                Rejected
                            </option>
                        </select>
                        <div class="validation"></div>
                    </div>
                </div>

                <div class="col-xs-6 col-sm-4" style="margin-bottom: 0; padding: 0; display: inline-block">
                    <div class="form-group">
                        <label for="">Search By Schedule</label>
                        <input type="date"
                            class="form-control"
                            id="filter_schedule"
                            name="filter_schedule"
                            value="{{ isset($_GET['filter_schedule']) ? $_GET['filter_schedule'] : '' }}">
                        <div class="validation"></div>
                    </div>
                </div>

                @if (Auth::user()->roles[0]->slug !== "branch" && Auth::user()->roles[0]->slug !== "cso")
                    <div class="col-xs-6 col-sm-4"
                        style="padding: 0; display: inline-block;">
                        <div class="form-group">
                            <label for="filter-branch">Search by Branch</label>
                            <select class="form-control"
                                id="filter_branch"
                                name="filter_branch">
                                <option value="">Choose Branch</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {!! isset($_GET["filter_branch"]) && $_GET["filter_branch"] == $branch->id ? "selected" : "" !!}>
                                        {{ $branch->code }} - {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-4"
                        style="padding: 0; display: inline-block;">
                        <div class="form-group">
                            <label for="filter-cso">Search by CSO</label>
                            <select class="form-control"
                                id="filter_cso"
                                name="filter_cso">
                                <option value="">Choose CSO</option>
                                @foreach ($csos as $cso)
                                    <option value="{{ $cso->id }}"
                                        {!! isset($_GET["filter_cso"]) && $_GET["filter_cso"] == $cso->id ? "selected" : "" !!}>
                                        {{ $cso->code }} - {{ $cso->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                <div class="col-xs-6 col-sm-4" style="margin-bottom: 0; padding: 0; display: inline-block">
                    <div class="form-group">
                        <label for="">Search By Product Code</label>
                        <input class="form-control"
                            id="filter_product_code"
                            name="filter_product_code"
                            placeholder="Search By Product Code"
                            value="{{ isset($_GET['filter_product_code']) ? $_GET['filter_product_code'] : '' }}">
                        <div class="validation"></div>
                    </div>
                </div>

                <div class="col-xs-6 col-sm-4" style="margin-bottom: 0; padding: 0; display: inline-block">
                    <div class="form-group">
                        <label for="">Search By Instation Name</label>
                        <input class="form-control"
                            id="filter_name"
                            name="filter_name"
                            placeholder="Search By Customer Name"
                            value="{{ isset($_GET['filter_name']) ? $_GET['filter_name'] : '' }}">
                        <div class="validation"></div>
                    </div>
                </div>

                <div class="col-xs-6 col-sm-6" style="padding: 0; display: inline-block">
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

            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h5 style="margin-bottom: 0.5em;">
                            Total : {{ $publichomecares->total() }}
                        </h5>
                        <div class="table-responsive"
                            style="border: 1px solid #ebedf2;">
                            <table class="table table-bordered" id="myTable">
                                <thead>
                                    <tr>
                                        <th>Schedule Date</th>
                                        <th>Instation Name</th>
                                        <th>Product Code</th>
                                        <th>Branch - CSO</th>
                                        <th>Status</th>
                                        <th colspan="3" class="center">View/Edit/Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($publichomecares as $publichomecare)
                                        <tr>
                                            <td>
                                                {{ date("d/m/Y", strtotime($publichomecare->start_date)) }}
                                                <i class="mdi mdi-arrow-right-bold"
                                                    style="font-size: 18px; color: #fed713;">
                                                </i>
                                                {{ date("d/m/Y", strtotime($publichomecare->end_date)) }}
                                            </td>
                                            <td>
                                                {{ $publichomecare->name }}
                                            </td>
                                            <td>
                                                {{ implode(', ', $publichomecare->publicHomecareProduct->pluck('personalHomecareProduct.code')->toArray()) }}
                                            </td>
                                            <td>
                                                {{ $publichomecare->branch->code }}
                                                -
                                                {{ $publichomecare->cso->code }}
                                                ({{ $publichomecare->cso->name }})
                                            </td>
                                            <td>
                                                {{ strtoupper($publichomecare->status) }}
                                            </td>
                                            <td class="center">
                                                <a href="{{ route('detail_public_homecare', ['id' => $publichomecare['id']]) }}">
                                                    <i class="mdi mdi-eye"
                                                        style="font-size: 24px; color: rgb(76 172 245);"></i>
                                                </a>
                                            </td>
                                            <td class="center">
                                                @if($publichomecare->status != "rejected" && $publichomecare->status != "done")
                                                    <a href="{{ route('edit_public_homecare', ['id' => $publichomecare['id']]) }}">
                                                        <i class="mdi mdi-border-color"
                                                            style="font-size: 24px; color: #fed713;"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="center">
                                                @if($publichomecare->status != "done")
                                                    <a class="btn-delete disabled"
                                                        data-toggle="modal"
                                                        href="#deleteDoModal"
                                                        onclick="submitDelete(this)"
                                                        data-id="{{ $publichomecare->id }}">
                                                        <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            {{ $publichomecares->appends($url)->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- partial -->

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
                        Are You Sure to Delete this Public Homecare?
                    </h5>
                </div>
                <div class="modal-footer">
                    <form id="frmDelete"
                        method="post"
                        action="{{ route('delete_public_homecare') }}">
                        @csrf
                        <input type="hidden" name="id" id="id-delete" />
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
</div>
@endsection

@section("script")
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
    integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
    defer></script>
<script type="application/javascript">
document.addEventListener("DOMContentLoaded", function () {
    $("#filter_branch").select2();
    $("#filter_cso").select2();
    $("#filter_status").select2();
});
function submitDelete(e) {
    document.getElementById("id-delete").value = e.dataset.id;
}

$(document).ready(function (e) {
    $("#btn-filter").click(function (e) {
        var urlParamArray = new Array();
        var urlParamStr = "";
        if($('#filter_name').val() != ""){
            urlParamArray.push("filter_name=" + $('#filter_name').val());
        }
        if($('#filter_status').val() != ""){
            urlParamArray.push("filter_status=" + $('#filter_status').val());
        }
        if($('#filter_branch').val() != ""){
            urlParamArray.push("filter_branch=" + $('#filter_branch').val());
        }
        if($('#filter_cso').val() != ""){
            urlParamArray.push("filter_cso=" + $('#filter_cso').val());
        }
        if($('#filter_product_code').val() != ""){
            urlParamArray.push("filter_product_code=" + $('#filter_product_code').val());
        }
        if($('#filter_schedule').val() != ""){
            urlParamArray.push("filter_schedule=" + $('#filter_schedule').val());
        }
        for (var i = 0; i < urlParamArray.length; i++) {
            if (i === 0) {
                urlParamStr += "?" + urlParamArray[i]
            } else {
                urlParamStr += "&" + urlParamArray[i]
            }
        }
        window.location.href = "{{route('list_all_puhc')}}" + urlParamStr;
    });

    $("#btn-filter_reset").click(function (e) {
         window.location.href = "{{route('list_all_puhc')}}";
    });
});

</script>
@endsection
