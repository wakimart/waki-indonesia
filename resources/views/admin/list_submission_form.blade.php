<?php
$menu_item_page = "submission";
$menu_item_second = "list_submission_form";
?>
@extends('admin.layouts.template')

@section("style")
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<style type="text/css">
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
            <h3 class="page-title">List Submmission</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#"
                            aria-expanded="false"
                            aria-controls="deliveryorder-dd">
                            Submmission
                        </a>
                    </li>
                    <li class="breadcrumb-item active"
                        aria-current="page">
                        List Submmission
                    </li>
                </ol>
            </nav>
        </div>

        <div class="col-12" style="padding: 0;">
            <form method="GET"
                class="col-12"
                action="{{ route("list_submission_form") }}">
                <div class="col-xs-6 col-sm-3"
                    style="padding: 0; display: inline-block;">
                    <div class="form-group">
                        <label for="filter-type">Search by type</label>
                        <select id="filter_type"
                            class="form-control"
                            name="filter_type">
                            <?php
                            $filterType = isset($_GET["filter_type"]);
                            ?>
                            <option value=""
                                {!! !$filterType ? "selected" : "" !!}>
                                No Filter
                            </option>
                            <option value="mgm"
                                {!! $filterType && $_GET["filter_type"] === "mgm" ? "selected" : "" !!}>
                                MGM
                            </option>
                            <option value="referensi"
                                {!! $filterType && $_GET["filter_type"] === "referensi" ? "selected" : "" !!}>
                                Referensi
                            </option>
                            <option value="takeaway"
                                {!! $filterType && $_GET["filter_type"] === "takeaway" ? "selected" : "" !!}>
                                Takeaway
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3"
                    style="padding: 0; display: inline-block;">
                    <div class="form-group">
                        <label for="filter-type">Search by Name/ Phone/ Code</label>
                        <input class="form-control"
                            type="text"
                            id="filter_text" 
                            name="filter_text"
                            placeholder="Name, Phone, Code"
                            value="{{ isset($_GET["filter_text"]) ? $_GET["filter_text"] : "" }}" />
                    </div>
                </div>
                @if (Auth::user()->roles[0]->slug !== "branch" && Auth::user()->roles[0]->slug !== "cso")
                    <div class="col-xs-6 col-sm-3"
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
                    <div class="col-xs-6 col-sm-3"
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
                <div class="col-xs-6 col-sm-6"
                    style="padding: 0; display: inline-block;">
                    <div class="form-group">
                        <button id="btn-filter"
                            type="button"
                            class="btn btn-gradient-primary m-1">
                            <span class="mdi mdi-magnify"></span> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-12 grid-margin stretch-card" style="padding: 0;">
            <div class="card">
                <div class="card-body">
                    <h5 style="margin-bottom: 0.5em;">
                        Total: {{ $submissions->total() }} data
                    </h5>
                    <div class="table-responsive"
                        style="border: 1px solid #ebedf2;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Registration Date</th>
                                    <th>Member Name</th>
                                    <th>Type</th>
                                    <th>Branch</th>
                                    <th>CSO</th>
                                    <th class="text-center">View</th>
                                    {{-- @if (Gate::check('edit-submission')) --}}
                                        <th class="text-center">Edit</th>
                                    {{-- @endif --}}
                                    {{-- @if (Gate::check('delete-submission')) --}}
                                        <th class="text-center">Delete</th>
                                    {{-- @endif --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $key => $submission)
                                    <tr>
                                        <td class="text-right">{{ $i }}</td>
                                        <td>
                                            {{ date("d F Y", strtotime($submission->created_at)) }}
                                        </td>
                                        <td>
                                            {{ $submission->name }}
                                        </td>
                                        <td style= "background-color:{{ isset($submission->reference[0]) ? 
                                            (
                                                $submission->reference[0]->reference_souvenir == null ? '#ff5050' : '') 
                                            :
                                            '' }}">
                                            {{ strtoupper($submission->type) }}
                                        </td>
                                        <td>
                                            {{ $submission->branch->code }} - {{ $submission->branch->name }}
                                        </td>
                                        <td>
                                            {{ $submission->cso->code }} - {{ $submission->cso->name }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route("detail_submission_form", ["id" => $submission->id, "type" => $submission->type]) }}">
                                                <i class="mdi mdi-eye" style="font-size: 24px;"></i>
                                            </a>
                                        </td>
                                        {{-- @can('edit-submission') --}}
                                            <td class="text-center">
                                                <a href="{{ route('edit_submission_form', ['id' => $submission->id, "type" => $submission->type]) }}">
                                                    <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                                </a>
                                            </td>
                                        {{-- @endcan --}}
                                        {{-- @can('delete-submission') --}}
                                            <td class="text-center">
                                                <button class="btn-delete"
                                                    data-toggle="modal"
                                                    data-target="#deleteDoModal"
                                                    value="{{ route('delete_submission_form', ['id' => $submission->id]) }}">
                                                    <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                </button>
                                            </td>
                                        {{-- @endcan --}}
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{ $submissions->appends($url)->Links() }}
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
                <h5 class="text-center">
                    Are you sure you want to delete this?
                </h5>
            </div>
            <div class="modal-footer">
                <form id="frmDelete" method="post" action="">
                    @csrf
                    <button type="submit" class="btn btn-gradient-danger mr-2">
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

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
<script type="application/javascript">
document.addEventListener("DOMContentLoaded", function () {
    try {
        $("#filter-cso").select2();
    } catch (error) {
        delete error;
    }

    $(document).on("click", "#btn-filter", function(e){
      var urlParamArray = new Array();
      var urlParamStr = "";

      if($('#filter_type').val() != ""){
        urlParamArray.push("filter_type=" + $('#filter_type').val());
      }
      if($('#filter_text').val() != ""){
        urlParamArray.push("filter_text=" + $('#filter_text').val());
      }
      if($('#filter_branch').val() != ""){
        urlParamArray.push("filter_branch=" + $('#filter_branch').val());
      }
      if($('#filter_cso').val() != ""){
        urlParamArray.push("filter_cso=" + $('#filter_cso').val());
      }

      for (var i = 0; i < urlParamArray.length; i++) {
        if (i === 0) {
          urlParamStr += "?" + urlParamArray[i]
        } else {
          urlParamStr += "&" + urlParamArray[i]
        } 
      }

      window.location.href = "{{route('list_submission_form')}}" + urlParamStr;
    });

    $(".btn-delete").click(function (e) {
        $("#frmDelete").attr("action",  $(this).val());
    });
}, false);
</script>
@endsection
