<?php
$menu_item_page = "acceptance";
$menu_item_second = "list_acceptance_form";
?>
@extends('admin.layouts.template')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Acceptances List</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#"
                            aria-expanded="false"
                            aria-controls="acceptance-dd">
                            Acceptances
                        </a>
                    </li>
                    <li class="breadcrumb-item active"
                        aria-current="page">
                        Acceptances List
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-12" style="padding: 0;">
            <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
                <div class="col-xs-6 col-sm-4" style="padding: 0;display: inline-block;">
                    <div class="form-group">
                        <label for="">Filter By Status</label>
                        <select class="form-control" id="filter_status" name="filter_status">
                            <option value="">All</option>
                            <option value="new" {{ isset($_GET['status']) ? ($_GET['status'] == "new" ? "selected" : "") : ""}}>New</option>
                            <option value="approved" {{ isset($_GET['status']) ? ($_GET['status'] == "approved" ? "selected" : "") : ""}}>Approved</option>
                            <option value="rejected" {{ isset($_GET['status']) ? ($_GET['status'] == "rejected" ? "selected" : "") : ""}}>Rejected</option>
                        </select>
                        <div class="validation"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
            <div class="col-xs-6 col-sm-6" style="padding: 0;display: inline-block;">
                <label for=""></label>
                <div class="form-group">
                    <button id="btn-filter" type="button" class="btn btn-gradient-primary m-1" name="filter" value="-"><span class="mdi mdi-filter"></span> Apply Filter</button>
                </div>
            </div>
        </div>

        <div class="col-12 grid-margin stretch-card" style="padding: 0;">
            <div class="card">
                <div class="card-body">
                    <h5 style="margin-bottom: 0.5em;">
                        Total: {{ $acceptances->count() }} data
                    </h5>
                    <div class="table-responsive"
                        style="border: 1px solid #ebedf2;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Acceptance Date</th>
                                    <th>Member Name</th>
                                    <th>Acc Type</th>
                                    <th>Acceptance Product</th>
                                    <th>Branch - CSO</th>
                                    <th>Status</th>
                                    {{-- @if(Gate::check('edit-deliveryorder') || Gate::check('delete-deliveryorder')) --}}
                                        <th colspan="2">Detail</th>
                                    {{-- @endif --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($acceptances as $key => $acceptance)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            {{ date("d/m/Y", strtotime($acceptance->upgrade_date)) }}
                                        </td>
                                        <td>
                                            {{ $acceptance->name }}
                                        </td>
                                        <td>
                                            Upgrade
                                        </td>
                                        <td>
                                            {{ $acceptance['other_product'] == null ? $acceptance->oldproduct['code'] : $acceptance['other_product'] }} <i class="mdi mdi-arrow-right-bold" style="font-size: 18px; color: #fed713;"></i> {{ $acceptance->newproduct['code'] }}
                                        </td>
                                        <td>
                                            {{ $acceptance->branch->code }} - {{ $acceptance->cso->code }}
                                        </td>
                                        <td>
                                            @if($acceptance['status'] == "new")
                                                <span class="badge badge-primary">New</span>
                                            @elseif($acceptance['status'] == "approved")
                                                <span class="badge badge-success">Approved</span> by : 
                                            @elseif($acceptance['status'] == "rejected")
                                                <span class="badge badge-danger">Rejected</span> by : 
                                            @endif
                                        </td>
                                        {{-- @can('edit-deliveryorder') --}}
                                            <td style="text-align: center;">
                                                <a href="{{ route('detail_acceptance_form' ,['id' => $acceptance['id']]) }}">
                                                    <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(76 172 245);"></i>
                                                </a>
                                            </td>
                                        {{-- @endcan --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{ $acceptances->appends($url)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- partial -->
@endsection

@section('script')
<script>
$(document).ready(function (e) {
    $("#btn-filter").click(function (e) {
        var urlParamArray = new Array();
        var urlParamStr = "";
        if($('#filter_status').val() != ""){
            urlParamArray.push("status=" + $('#filter_status').val());
        }
        for (var i = 0; i < urlParamArray.length; i++) {
            if (i === 0) {
                urlParamStr += "?" + urlParamArray[i]
            } else {
                urlParamStr += "&" + urlParamArray[i]
            }
        }
        window.location.href = "{{route('list_acceptance_form')}}" + urlParamStr;
    });
});
</script>
@endsection
