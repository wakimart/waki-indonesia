<?php
$menu_item_page = "submission_video_photo";
$menu_item_second = "list_submission_video_photo";
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
    .btn-delete {
      background: transparent;
      border: 0;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">List Submmission Video & Photo</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#"
                            aria-expanded="false"
                            aria-controls="submission_video_photo-dd">
                            Submmission Video & Photo
                        </a>
                    </li>
                    <li class="breadcrumb-item active"
                        aria-current="page">
                        List Submmission Video & Photo
                    </li>
                </ol>
            </nav>
        </div>

        <div class="col-12" style="padding: 0;">
            <form method="GET"
                class="col-12"
                action="{{ route("list_submission_video_photo") }}">
                <div class="col-xs-6 col-sm-3"
                    style="padding: 0; display: inline-block;">
                    <div class="form-group">
                        <label for="filter_date">Search by Date</label>
                        <input type="date"
                            id="filter_date"
                            class="form-control"
                            name="filter_date" />
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
                        Total: {{ $submissionVPs->total() }} data</b>
                    </h5>
                    <div class="table-responsive"
                        style="border: 1px solid #ebedf2;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Submission Date</th>
                                    <th>Type</th>
                                    <th>Branch</th>
                                    <th>Status</th>
                                    <th class="text-center">View</th>
                                    @if (Gate::check('edit-submission_video_photo'))
                                        <th class="text-center">Edit</th>
                                    @endif
                                    @if (Gate::check('delete-submission_video_photo'))
                                        <th class="text-center">Delete</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissionVPs as $key => $submissionVP)
                                    @php
                                        //khusus admin KEzia
                                        $submission_done = "";
                                        if(Auth::user()->id == 2){
                                            foreach ($submissionVP->submissionVideoPhotoDetails as $perDetail) {
                                                if($perDetail->status == 'approved'){
                                                    $submission_done = "background-color: #beffc9;";
                                                    break;
                                                }
                                            }
                                        }
                                        else{
                                            if($submissionVP->status == "pending"){
                                                $submissionVP_done = "background-color: #cdedf7;";
                                            }
                                            elseif($submissionVP->status == "rejected"){
                                                $submissionVP_done = "background-color: #ffdbdb;";
                                            }
                                        }
                                    @endphp

                                    <tr style="{{ $submissionVP_done }}">
                                        <td class="text-right">{{ $i }}</td>
                                        <td>
                                            {{ date("d F Y", strtotime($submissionVP->submission_date)) }}
                                        </td>
                                        <td>
                                            {{ $submissionVP->type }}
                                        </td>
                                        <td>
                                            {{ $submissionVP->branch->code }} - {{ $submissionVP->branch->name }}
                                        </td>
                                        <td>
                                            {{ ucwords($submissionVP->status) }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route("detail_submission_video_photo", ["id" => $submissionVP->id]) }}">
                                                <i class="mdi mdi-eye" style="font-size: 24px;"></i>
                                            </a>
                                        </td>
                                        @can('edit-submission_video_photo')
                                            <td class="text-center">
                                                <a href="{{ route('edit_submission_video_photo', ['id' => $submissionVP->id]) }}">
                                                    <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                                </a>
                                            </td>
                                        @endcan
                                        @can('delete-submission_video_photo')
                                            <td class="text-center">
                                                <button class="btn-delete p-0"
                                                    data-toggle="modal"
                                                    data-target="#deleteDoModal"
                                                    value="{{ route('delete_submission_video_photo', ['id' => $submissionVP->id]) }}">
                                                    <i class="mdi mdi-delete" style="font-size: 24px; color: #f94569;"></i>
                                                </button>
                                            </td>
                                        @endcan
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{ $submissionVPs->appends($url)->Links() }}
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
        $("#filter_cso").select2();
    } catch (error) {
        delete error;
    }

    $(document).on("click", "#btn-filter", function(e){
      var urlParamArray = new Array();
      var urlParamStr = "";

      if($('#filter_date').val() != ""){
        urlParamArray.push("filter_date=" + $('#filter_date').val());
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

      window.location.href = "{{route('list_submission_video_photo')}}" + urlParamStr;
    });

    $(".btn-delete").click(function (e) {
        $("#frmDelete").attr("action",  $(this).val());
    });
}, false);
</script>
@endsection
