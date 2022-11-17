<?php
$menu_item_page = "submission_video_photo";
$menu_item_second = "detail_submission_form";

$specialPermission = true;
if (
    Auth::user()->roles[0]->slug === "branch"
    || Auth::user()->roles[0]->slug === "cso"
) {
    $specialPermission = false;
}
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="{{ asset("css/lib/select2/select2-bootstrap4.min.css") }}" />
<style type="text/css">
    #intro {padding-top: 2em;}
    .signature-pad {
      width: 320px;
      height: 200px;
      background-color: white;
      margin-bottom: 0.5em;
    }
    @media (max-width:400px){
      .signature-pad {
        width: 255px !important;
      }
    }
    @media (min-width:768px){
      .signature-pad {
        width: 500px !important;
      }
    }
    table {
        margin: 1em;
        font-size: 14px;
    }
    .table-responsive table .form-control {
        height: 32px;
        line-height: 32px;
        box-shadow: none;
        border-radius: 2px;
        margin-bottom: 0;
    }
    .table-responsive table .form-control.error {
        border-color: #f50000;
        border: 1px solid red;
    }
    .table-responsive table td .save {
        display: none;
    }
    table thead {
        background-color: #8080801a;
        text-align: center;
    }
    table td {
        border: 0.5px #8080801a solid;
        padding: 0.5em;
    }
    .right {
        text-align: right;
        padding-right: 1em;
    }
    .pInTable {
        margin-bottom: 6pt !important;
        font-size: 10pt;
    }
    select.form-control {
        color: black !important;
    }
    .modal {
        overflow-y: auto !important;
    }
    .btn {
        /* width: 100%;
        min-width: 50px;
        max-width: 400px; */
    }
    @media (max-width: 480px) {
		.btn span {
            font-size: 2.5vw;
            padding: 0 !important;
        }
	}
    @media (max-width: 1187px) {
		.btn {
            margin-bottom: 3em;
        }
	}

    .decrease-width {
        width: 100px !important
    }

    .imagePreview {
        width: 100%;
        height: 150px;
        background-position: center center;
        background-color: #fff;
        background-size: cover;
        background-repeat: no-repeat;
        display: inline-block;
    }
    .del {
        position: absolute;
        top: 0px;
        right: 10px;
        width: 30px;
        height: 30px;
        text-align: center;
        line-height: 30px;
        background-color: rgba(255, 255, 255, 0.6);
        cursor: pointer;
    }

</style>
@endsection

@section('content')
@if ($submissionVP->id !== null)
<div class="main-panel">
    <div class="content-wrapper">
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                @php
                                    $style_h = "";
                                    if($submissionVP->status == "pending"){
                                        $style_h = "color: #fcb128;";
                                    } elseif($submissionVP->status == "approved") {
                                        $style_h = "color: #59c653";
                                    } elseif($submissionVP->status == "rejected"){
                                        $style_h = "color: #ff4c4c;";
                                    }
                                @endphp
                                <h2>SUBMISSION VIDEO & PHOTO <span style="{{ $style_h }}">({{ strtoupper($submissionVP->status) }})</span></h2>
                            </div>

                            <div class="row justify-content-center">
                                <div class="table-responsive"
                                    style="margin-right: 0.5em;">
                                    <table class="table">
                                        <thead>
                                            <td>Type</td>
                                            <td>Submission Date</td>
                                            <td>Branch</td>
                                        </thead>
                                        <tr>
                                            <td class="text-center">
                                                {{ $submissionVP->type }}
                                            </td>
                                            <td class="text-center">
                                                {{ date("d/m/Y", strtotime($submissionVP->submission_date)) }}
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                echo $submissionVP->branch->code
                                                    . " - "
                                                    . $submissionVP->branch->name;
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="table-responsive"
                                    style=" margin-left 1em; margin-right: 1em;">
                                    <?php
                                    $formLength = $submissionVP->submissionVideoPhotoDetails->count();
                                    ?>
                                    <input type="hidden"
                                        id="form-length"
                                        value="{{ $formLength }}" />
                                    <table class="table"
                                        style="table-layout: auto;">
                                        <thead>
                                            <td colspan="15">Submission Video & Photo Detail</td>
                                        </thead>
                                        <thead style="background-color: #80808012 !important;">
                                            <tr>
                                                <td>Cso</td>
                                                <td>Detail Date</td>
                                                <td>Name</td>
                                                <td>Phone</td>
                                                <td>Address</td>
                                                <td>Url Drive</td>
                                                <td>MPC/Wakimart</td>
                                                <td>Souvenir</td>
                                                <td>Status</td>
                                                @if(Gate::check('edit-submission_video_photo_detail'))
                                                <td>Edit</td>
                                                @endif
                                                @if(Gate::check('delete-submission_video_photo_detail'))
                                                <td>Delete</td>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($submissionVP->submissionVideoPhotoDetails as $key => $sVPDetail)
                                                <input type="hidden"
                                                    id="edit-id_{{ $key }}"
                                                    class="d-none"
                                                    name="id"
                                                    value="{{ $sVPDetail->id }}" />
                                                <tr>
                                                    <td id="cso_{{ $key }}"
                                                        data-cso="{{ $sVPDetail->cso['id'] }}">
                                                        {{ $sVPDetail->cso['code'] }} - {{ $sVPDetail->cso['name'] }}
                                                    </td>
                                                    <td id="detail-date_{{ $key }}"
                                                        data-date="{{ $sVPDetail->detail_date }}">
                                                        {{ date('d/m/Y', strtotime($sVPDetail->detail_date)) }}
                                                    </td>
                                                    <td id="name_{{ $key }}">
                                                        {{ $sVPDetail->name }}
                                                    </td>
                                                    <td class="text-center"
                                                        id="phone_{{ $key }}">
                                                        {{ $sVPDetail->phone }}
                                                    </td>
                                                    <td id="address_{{ $key }}">
                                                        {{ $sVPDetail->address }}
                                                    </td>
                                                    <td id="url-drive_{{ $key }}"
                                                        data-url="{{ $sVPDetail->url_drive }}">
                                                        <a href="{{ $sVPDetail->url_drive }}" target="_blank" style="color: #6b63ff">
                                                            Link Drive
                                                        </a>
                                                    </td>
                                                    <td id="mpc-wakimart_{{ $key }}">
                                                        {{ $sVPDetail->mpc_wakimart }}
                                                    </td>
                                                    <td id="souvenir_{{ $key }}">
                                                        {{ $sVPDetail->souvenir }}
                                                    </td>
                                                    <td id="status_{{ $key }}"
                                                        data-status="{{ $sVPDetail->status }}">
                                                        {{ ucwords($sVPDetail->status) }}
                                                    </td>
                                                    @if(Gate::check('edit-submission_video_photo_detail'))
                                                    <td class="text-center">
                                                        @if ($sVPDetail->status !== "approved")
                                                            <button class="btn"
                                                                id="btn-edit-save_{{ $key }}"
                                                                style="padding: 0;"
                                                                data-edit="edit_{{ $key }}"
                                                                onclick="clickEdit(this)"
                                                                data-toggle="modal"
                                                                data-target="#edit-reference"
                                                                value="{{ $sVPDetail->id }}">
                                                                <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                    @endif
                                                    @if(Gate::check('delete-submission_video_photo_detail'))
                                                    <td class="text-center">
                                                        <button class="btn"
                                                            id="btn-delete-reference_{{ $key }}"
                                                            style="padding: 0;"
                                                            onclick="deleteReference(this)"
                                                            data-id="{{ $sVPDetail->id }}"
                                                            data-toggle="modal"
                                                            data-target="#delete-reference-modal">
                                                            <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                        </button>
                                                    </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if(Gate::check('add-submission_video_photo_detail') && $submissionVP->status != 'approved')
                            <div class="col-md-12 text-center mt-4">
                                <button class="btn btn-gradient-primary mt-2"
                                    id="btnAddReference"
                                    onclick="checkAddReference()">
                                    <span>Add New Detail</span>
                                </button>
                            </div>
                            @endif

                            <div class="col-md-12 text-center"
                                style="margin-top: 3em;">
                                <div class="row justify-content-center">
                                    <h2 class="text-center share">
                                        Share Submission Form
                                    </h2>
                                </div>
                                <form class="forms-sample"
                                    method="GET"
                                    action="https://api.whatsapp.com/send">
                                    <div class="form-group row justify-content-center">
                                        <button type="submit"
                                            class="btn btn-gradient-primary mr-2 my-2"
                                            name="text"
                                            value="Berikut adalah tautan detail submission video & photo ( {{ route('detail_submission_video_photo') }}?id={{ $submissionVP->id }} )">
                                            Share Submission Video & Photo
                                        </button>
                                    </div>
                                </form>
                             </div>

                            @if ($historySubmission->isNotEmpty())
                                <div class="row justify-content-center"
                                    style="margin-top: 2em;">
                                    <h2>SUBMISSION HISTORY LOG</h2>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="table-responsive" style="margin-right: 0.5em;">
                                        <table class="table">
                                            <thead>
                                                <td class="text-center">No.</td>
                                                <td>Action</td>
                                                <td>User</td>
                                                <td>Change</td>
                                                <td>Time</td>
                                            </thead>
                                            @foreach ($historySubmission as $key => $history)
                                                <?php
                                                $dataChange = json_decode($history->meta, true);
                                                ?>
                                                <tr>
                                                    <td class="right">{{ $key + 1 }}</td>
                                                    <td class="text-center">
                                                        {{ $history->method }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $history->name }}
                                                    </td>
                                                    <td>
                                                        @foreach ($dataChange["dataChange"] as $key => $value)
                                                        <b>{{$key}}</b>: {{ is_array($value) ? json_encode($value) : $value }}<br/>
                                                        @endforeach
                                                    </td>
                                                    <td class="text-center">
                                                        {{ date("d/m/Y H:i:s", strtotime($history->created_at)) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            @endif

                         </div>
                    </div>
                </div>
            </div>

            @if(Gate::check('change-status-approved-submission_video_photo') || Gate::check('change-status-rejected-submission_video_photo'))
            @if($specialPermission && ($submissionVP->status != "approved" && $submissionVP->status != "rejected"))
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center mb-2">
                                <h2>Status Submission</h2>
                            </div>
                            <form id="actionAdd"
                                class="forms-sample"
                                method="POST"
                                action="{{ route('update_status_submission_video_photo') }}">
                                {{ csrf_field() }}
                                <input type="text"
                                    name="id"
                                    value="{{ $submissionVP['id'] }}"
                                    hidden />
                                <div class="form-group row justify-content-center">
                                    @if(Gate::check('change-status-approved-submission_video_photo'))
                                    <button id="upgradeProcess"
                                        type="submit"
                                        class="btn btn-gradient-primary m-3 btn-lg"
                                        name="status"
                                        value="approved">
                                        Approved
                                    </button>
                                    @endif
                                    @if(Gate::check('change-status-rejected-submission_video_photo'))
                                    <button id="upgradeProcess"
                                        type="submit"
                                        class="btn btn-gradient-danger m-3 btn-lg"
                                        name="status"
                                        value="rejected">
                                        Reject
                                    </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>
@else
    <div class="row justify-content-center">
        <h2>CANNOT FIND SUBMISSION VIDEO PHOTO</h2>
    </div>
@endif
{{-- INI BUAT ADD REFERENSI, ID-NYA "EDIT" KARENA COPAS DARI VIEW LAIN --}}
<div class="modal fade"
    id="edit-reference"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span id="modal-title">Edit</span> Submission Video & Photo Detail</h5>
                <button type="button"
                    id="edit-close"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-form"
                    method="POST"
                    enctype="multipart/form-data"
                    action="{{ route("store_submission_video_photo_detail") }}">
                    @csrf
                    <input type="hidden"
                        id="edit-id"
                        name="submission_id"
                        value="{{ $submissionVP->id }}" />
                    <div class="form-group">
                        <label for="edit-detail_date">Detail Date</label>
                        <input type="date"
                            class="form-control"
                            id="edit-detail_date"
                            name="detail_date"
                            value=""
                            required />
                    </div>
                    <div class="form-group">
                        <label for="edit-cso">Cso</label>
                        <select class="form-control select2"
                            id="edit-cso"
                            name="cso"
                            data-msg="Mohon Pilih Cso"
                            required>
                            <option selected disabled value="">
                                Choose Cso
                            </option>

                            @foreach ($csos as $cso)
                                <option value="{{ $cso['id'] }}">
                                    {{ $cso['code'] }} - {{ $cso['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-name">Name</label>
                        <input type="text"
                            class="form-control"
                            id="edit-name"
                            name="name"
                            maxlength="191"
                            value=""
                            placeholder="Name"
                            required />
                    </div>
                    <div class="form-group">
                        <label for="edit-phone">Phone</label>
                        <input type="number"
                            class="form-control"
                            id="edit-phone"
                            name="phone"
                            value=""
                            placeholder="Phone"
                            required />
                    </div>
                    <div class="form-group">
                        <label for="edit-address">Address</label>
                        <textarea
                            class="form-control"
                            id="edit-address"
                            name="address"
                            value=""
                            rows="3"
                            required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit-url_drive">Url Drive</label>
                        <input type="text"
                            class="form-control"
                            id="edit-url_drive"
                            name="url_drive"
                            value=""
                            placeholder="https://deive.google.com/drive/folders/"
                            required />
                    </div>
                    <div class="form-group">
                        <label for="edit-mpc_wakimart">MPC/Wakimart (Optional)</label>
                        <input type="text"
                            class="form-control"
                            id="edit-mpc_wakimart"
                            name="mpc_wakimart"
                            value=""
                            placeholder="MPC/Wakimart" />
                    </div>
                    <div id="appendSouvenir" class="form-group">
                        <label id="label_souvenir" for="edit-souvenir">
                            Souvenir
                        </label>
                        <select class="form-control"
                            id="edit-souvenir"
                            name="souvenir"
                            required>
                            <option selected disabled>
                                Pilih Souvenir
                            </option>
                            <option value="SLIPPER MASSAGE">
                                SLIPPER MASSAGE
                            </option>
                        </select>
                    </div>
                </form>
                <div class="clearfix"></div>
                @if (Gate::check('change-status-approved-submission_video_photo_detail') 
                || Gate::check('change-status-rejected-submission_video_photo_detail'))
                <div id="divIdStatusDetail" class="text-center p-3" style="border: 1px solid black;">
                    <h5 class="mb-3">Status Detail</h5>
                    <form id="frmUpdateStatusDetail"
                        method="post"
                        action="{{ route('update_status_submission_video_photo_detail') }}">
                        @csrf
                        <input type="hidden" id="id-update-staus-detail" name="id" value="">
                        <div class="btn-action" style="text-align: center;">
                            @if (Gate::check('change-status-approved-submission_video_photo_detail'))
                            <button type="submit" 
                                class="btn btn-gradient-primary" 
                                id="btn-update-status-payment-true"
                                name="status"
                                value="approved">
                                Verified
                            </button>
                            @endif
                            @if (Gate::check('change-status-rejected-submission_video_photo_detail'))
                            <button type="submit" 
                                class="btn btn-gradient-danger" 
                                id="btn-update-status-payment-false"
                                name="status"
                                value="rejected">
                                Rejected
                            </button>
                            @endif
                        </div>
                    </form>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <input type="submit"
                    form="edit-form"
                    value="Submit"
                    class="btn btn-gradient-primary mr-2" />
                <button class="btn btn-light"
                    data-dismiss="modal"
                    aria-label="Close">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade"
    id="delete-reference-modal"
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
                    Are you sure you want to delete this Detail?
                </h5>
            </div>
            <div class="modal-footer">
                <form method="post" action="{{ route("delete_submission_video_photo_detail") }}">
                    @csrf
                    <input type="hidden" name="id" id="delete-reference-id" />
                    <button type="submit" class="btn btn-gradient-danger">
                        Yes
                    </button>
                </form>
                <button class="btn btn-light" type="button">No</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
<script type="application/javascript">

$(document).ready(function() {
    $("#edit-cso").select2({
        theme: "bootstrap4",
        dropdownParent: $('#edit-reference .modal-body')
    });
});

function checkAddReference() {
    clearModal();
    $("#edit-reference").modal('show');
}

function clearModal() {
    const submissionId = '{{ $submissionVP->id }}';
    const actionStore = '{{ route("store_submission_video_photo_detail") }}';
    document.getElementById("modal-title").innerHTML = "Add";
    document.getElementById("edit-form").setAttribute("action", actionStore);
    document.getElementById("edit-id").value = submissionId;
    $("#edit-cso").val("").trigger("change");
    document.getElementById("edit-detail_date").value = "";
    document.getElementById("edit-url_drive").value = "";
    document.getElementById("edit-mpc_wakimart").value = "";
    document.getElementById("edit-name").value = "";
    document.getElementById("edit-phone").value = "";
    document.getElementById("edit-address").value = "";
    document.getElementById("edit-souvenir").value = "";
    $("#divIdStatusDetail").hide();
    $("#id-update-staus-detail").val("");
}

function clickEdit(e) {
    clearModal();
    const refSeq = e.dataset.edit.split("_")[1];

    const actionUpdate = '{{ route("update_submission_video_photo_detail") }}';
    const id = document.getElementById("edit-id_" + refSeq).value;
    const cso = document.getElementById("cso_" + refSeq).getAttribute("data-cso");
    const detail_date = document.getElementById("detail-date_" + refSeq).getAttribute("data-date");
    const url_drive = document.getElementById("url-drive_" + refSeq).getAttribute("data-url");
    const mpc_wakimart = document.getElementById("mpc-wakimart_" + refSeq).innerHTML.trim();
    const name = document.getElementById("name_" + refSeq).innerHTML.trim();
    const phone = document.getElementById("phone_" + refSeq).innerHTML.trim();
    const address = document.getElementById("address_" + refSeq).innerHTML.trim();
    const souvenir = document.getElementById("souvenir_" + refSeq).innerHTML.trim();
    const status = document.getElementById("status_" + refSeq).getAttribute("data-status");

    document.getElementById("modal-title").innerHTML = "Edit";
    document.getElementById("edit-form").setAttribute("action", actionUpdate);
    document.getElementById("edit-id").value = id;
    $("#edit-cso").val(cso).trigger('change');
    document.getElementById("edit-detail_date").value = detail_date;
    document.getElementById("edit-url_drive").value = url_drive;
    document.getElementById("edit-mpc_wakimart").value = mpc_wakimart;
    document.getElementById("edit-name").value = name;
    document.getElementById("edit-phone").value = phone;
    document.getElementById("edit-address").value = address;
    document.getElementById("edit-souvenir").value = souvenir;

    if (status != 'approved') {
        $("#divIdStatusDetail").show();
        $("#id-update-staus-detail").val(id);
    }   
}

function deleteReference(e) {
    document.getElementById("delete-reference-id").value = e.dataset.id;
}
</script>
@endsection
