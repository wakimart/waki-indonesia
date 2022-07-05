<?php
$menu_item_page = "type_customer";
$menu_item_second = "list_type_customer";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    .center {text-align: center;}
    .right {text-align: right;}
    .table th img, .table td img {
        border-radius: 0% !important;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">List Type Customer</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#tpye_customer-dd"
                            aria-expanded="false"
                            aria-controls="tpye_customer-dd">
                            Type Customer
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        List Type Customer
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">

            <div class="col-12" style="margin-bottom: 0;">
                    <div class="col-xs-6 col-sm-4" style="margin-bottom: 0; padding: 0; display: inline-block">
                        <div class="form-group">
                            <label for="">Search By Name</label>
                            <input class="form-control" id="search" name="search" placeholder="Search By Name and Code">
                            <div class="validation"></div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6" style="padding: 0; display: inline-block">
                        <label for=""></label>
                        <div class="form-group">
                            <button id="btn-filter" type="button" class="btn btn-gradient-primary m-1" name="filter" value="-"><span class="mdi mdi-filter"></span> Apply Filter</button>
                        </div>
                    </div>
            </div>

            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h5 style="margin-bottom: 0.5em;">
                            Total : {{ $countTypeCustomers }} data
                        </h5>
                        <div class="table-responsive"
                            style="border: 1px solid #ebedf2;">
                            <table class="table table-bordered" id="myTable">
                                <thead>
                                    <tr>
                                        <th class="center">No.</th>
                                        <th>Name</th>
                                        @can('edit-type_customer')
                                        <th class="center">Edit</th>
                                        @endcan
                                        @can('delete-type_customer')
                                        <th class="center">Delete</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($type_customers as $key => $type_customer)
                                        <tr>
                                            <td class="right">
                                                <?php echo $i; ?>
                                            </td>
                                            <td>{{ $type_customer['name'] }}</td>
                                            @can('edit-type_customer')
                                            <td class="center">
                                                <a href="{{ route('edit_type_customer', ['id' => $type_customer['id']]) }}">
                                                    <i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
                                                </a>
                                            </td>
                                            @endcan
                                            @can('delete-type_customer')
                                            <td class="center">
                                                <a class="btn-delete"
                                                    data-toggle="modal"
                                                    href="#deleteDoModal"
                                                    onclick="submitDelete(this)"
                                                    data-id="{{ $type_customer["id"] }}">
                                                    <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                </a>
                                            </td>
                                            @endcan
                                        </tr>
                                        <?php $i++; ?>
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            <?php echo $type_customers->appends($url)->links(); ?>
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
                        Are you sure to delete this type customer?
                    </h5>
                </div>
                <div class="modal-footer">
                    <form id="frmDelete"
                        method="post"
                        action="{{ route("delete_type_customer") }}">
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
</div>
@endsection

@section("script")
<script type="application/javascript">
function submitDelete(e) {
    document.getElementById("id-delete").value = e.dataset.id;
}

$(document).ready(function (e) {
    $("#btn-filter").click(function (e) {
        var urlParamArray = new Array();
        var urlParamStr = "";
        if($('#search').val() != ""){
            urlParamArray.push("search=" + $('#search').val());
        }
        for (var i = 0; i < urlParamArray.length; i++) {
            if (i === 0) {
                urlParamStr += "?" + urlParamArray[i]
            } else {
                urlParamStr += "&" + urlParamArray[i]
            }
        }
        window.location.href = "{{route('list_type_customer')}}" + urlParamStr;
    });
});

</script>
@endsection
