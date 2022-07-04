<?php

use App\HomeService;
use App\Order;
use App\Prize;

$menu_item_page = "submission";
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
        width: 100%;
        min-width: 50px;
        max-width: 400px;
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
</style>
@endsection

@section('content')
@if ($submission->code !== null)
<div class="main-panel">
    <div class="content-wrapper">
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                @php
                                    $style_h = "";
                                    if($submission->status_reference == "new"){
                                        $style_h = "color: #4c94ff;";
                                    }
                                    elseif($submission->status_reference == "rejected"){
                                        $style_h = "color: #ff4c4c;";
                                    }
                                @endphp
                                <h2 style="{{ $style_h }}">SUBMISSION {{ strtoupper($submission->status_reference) }}</h2>
                            </div>

                            <div class="row justify-content-center">

                                <div class="table-responsive"
                                    style="margin-right: 0.5em;">
                                    <table class="table">
                                        <thead>
                                            <td>Submission Date</td>
                                        </thead>
                                        <tr>
                                            <td class="text-center">
                                                {{ date("d/m/Y H:i:s", strtotime($submission->created_at)) }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="table-responsive"
                                    style="margin-right: 0.5em;">
                                    <table class="table">
                                        <thead>
                                            <td colspan="2">Customer Data</td>
                                        </thead>
                                        <tr>
                                            <td>Member Code:</td>
                                            <td>{{ $submission->no_member }}</td>
                                        </tr>
                                        <tr>
                                            <td>Name:</td>
                                            <td>{{ $submission->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Phone Number:</td>
                                            <td>{{ $submission->phone }}</td>
                                        </tr>
                                        <tr>
                                            <td rowspan="2">Address:</td>
                                            <td>{{ $submission->address }}</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                echo $submission->district
                                                    . ", "
                                                    . $submission->city
                                                    . ", "
                                                    . $submission->province;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Proof (image)</td>
                                            <td>
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if (!empty($submission["image_" . $i]))
                                                        <a href="{{ asset("sources/registration/" . $submission["image_" . $i]) }}"
                                                            target="_blank">
                                                            <i class="mdi mdi-numeric-{{ $i }}-box" style="font-size: 24px; color: blue;"></i>
                                                        </a>
                                                    @endif
                                                @endfor
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="table-responsive"
                                    style="margin-right: 0.5em;">
                                    <table class="table">
                                        <thead>
                                            <td>Sales Branch</td>
                                            <td>Sales Code</td>
                                        </thead>
                                        <tr>
                                            <td class="text-center">
                                                <?php
                                                echo $submission->branch_code
                                                    . " - "
                                                    . $submission->branch_name;
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                {{ $submission->cso_code }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="table-responsive"
                                    style=" margin-left 1em; margin-right: 1em;">
                                    <?php
                                    $formLength = $references->count();
                                    $arr_souvenir = [];
                                    ?>
                                    <input type="hidden"
                                        id="form-length"
                                        value="{{ $formLength }}" />
                                    @for ($i = 0; $i < $formLength; $i++)
                                        <form method="POST" id="edit-form_{{ $i }}"></form>
                                    @endfor
                                    <table class="table"
                                        style="table-layout: auto;">
                                        <thead>
                                            <td colspan="14">Reference</td>
                                        </thead>
                                        <thead style="background-color: #80808012 !important;">
                                            <tr>
                                                <td>Name</td>
                                                <td>Age</td>
                                                <td>Phone</td>
                                                <td>Province</td>
                                                <td>City</td>
                                                <td>Link HS</td>
                                                <td>Order</td>
                                                <td>Wakimart Link</td>
                                                <td>Souvenir</td>
                                                <td>Status</td>
                                                <td>Deliv. Status</td>
                                                @if ($specialPermission)
                                                    <th class="text-center">View</th>
                                                @endif
                                                <td>Edit</td>
                                                <td>Delete</td>
                                                <td>Signature</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($references as $key => $reference)
                                                <?php
                                                array_push($arr_souvenir, $reference['souvenir_id']);
                                                ?>
                                                <input type="hidden"
                                                    id="edit-id_{{ $key }}"
                                                    class="d-none"
                                                    name="id"
                                                    form="edit-form_{{ $key }}"
                                                    value="{{ $reference->id }}" />
                                                <input type="hidden" id="wakimart_link_{{ $key }}" value="{{$reference->wakimart_link}}">
                                                <tr>
                                                    <td id="name_{{ $key }}">
                                                        {{ $reference->name }}
                                                    </td>
                                                    <td class="text-center"
                                                        id="age_{{ $key }}">
                                                        {{ $reference->age }}
                                                    </td>
                                                    <td class="text-center"
                                                        id="phone_{{ $key }}">
                                                        {{ $reference->phone }}
                                                    </td>
                                                    <td id="province_{{ $key }}"
                                                        data-province="{{ $reference->province_id }}">
                                                        {{ $reference->province }}
                                                    </td>
                                                    <td id="city_{{ $key }}"
                                                        data-city="{{ $reference->city_id }}">
                                                        {{ $reference->city }}
                                                    </td>
                                                    <?php
                                                    if (!empty($reference->link_hs)) {
                                                        $i = 1;
                                                        $link_hs = json_decode(
                                                            $reference->link_hs,
                                                            JSON_THROW_ON_ERROR
                                                        );
                                                    }
                                                    ?>
                                                    <td class="text-center"
                                                        id="link-hs_{{ $key }}"
                                                        data-hs="{{ !empty($reference->link_hs) ? implode(", ", $link_hs) : "" }}"
                                                        style="overflow-x: auto;">
                                                        @if (!empty($reference->link_hs))
                                                            @foreach ($link_hs as $value)
                                                                @if (is_numeric($value))
                                                                    <?php
                                                                    $hs = HomeService::select("code", "appointment")->where("id", $value)->first();

                                                                    $hs_code = str_replace("%2F", "/", $hs->code);
                                                                    ?>
                                                                    <a id="link-hs-href_{{ $value }}"
                                                                        data-hs={{ $hs->code }}
                                                                        href="{{ route("admin_list_homeService", ["filter_search" => $hs_code, "isSubmission" => "true", "appointment" => $hs->appointment]) }}"
                                                                        target="_blank">
                                                                        <i class="mdi mdi-numeric-{{ $i }}-box" style="font-size: 24px; color: #2daaff;"></i>
                                                                    </a>
                                                                @else
                                                                    <a id="link-hs-href_{{ $key }}"
                                                                        href="{{ $value }}"
                                                                        target="_blank">
                                                                        <i class="mdi mdi-numeric-{{ $i }}-box" style="font-size: 24px; color: red;"></i>
                                                                    </a>
                                                                @endif
                                                                <?php $i++; ?>
                                                            @endforeach
                                                        @endif
                                                    </td>

                                                    <td class="text-center"
                                                        id="order_{{ $key }}"
                                                        data-order="{{ $reference->order_id }}"
                                                        style="overflow-x:auto;">
                                                        @php
                                                        if (!empty($reference->order_id)) {
                                                            $order = Order::select("id", "code")
                                                                ->where("id", $reference->order_id)
                                                                ->first();

                                                            echo '<a href="'.route("detail_order", ["code" => $order->code]).'">'.$order->code.'</a>';
                                                        }
                                                        @endphp
                                                    </td>
                                                    <td class="text-center"
                                                        id="order_white_{{ $key }}"
                                                        data-order="{{ $reference->order_id }}"
                                                        style="overflow-x:auto; display: none;">
                                                        @php
                                                        if (!empty($reference->order_id)) {
                                                            $order = Order::select("id", "code")
                                                                ->where("id", $reference->order_id)
                                                                ->first();

                                                            echo '<a href="'.route("detail_order", ["code" => $order->code]).'" style="color:white">'.$order->code.'</a>';
                                                        }
                                                        @endphp
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ $reference->wakimart_link }}" target="_blank">{{ $reference->wakimart_link }}</a>
                                                    </td>
                                                    <td id="souvenir_{{ $key }}"
                                                        class="text-center"
                                                        data-permission="{{ $specialPermission }}"
                                                        data-souvenir="{{ $reference->souvenir_id ?? -1 }}">
                                                        {{ $reference->souvenir_name }}
                                                    </td>
                                                    <td class="text-center"
                                                        id="status_souvenir_{{ $key }}"
                                                        data-permission="{{ $specialPermission }}">
                                                        {{ $reference->status_souvenir }}
                                                    </td>
                                                    <td class="text-center"
                                                        id="delivery_status_souvenir_{{ $key }}"
                                                        data-deliverysouvenir="{{ $reference->delivery_status_souvenir }}"
                                                        data-permission="{{ $specialPermission }}">
                                                        {{ $reference->delivery_status_souvenir }}
                                                    </td>
                                                    @if ($specialPermission)
                                                        <td class="text-center">
                                                            <button id="btnDetailRef_{{ $key }}"
                                                                type="button"
                                                                class="btn"
                                                                style="padding: 0;"
                                                                value="{{ $reference->id }}"
                                                                onclick="loadDataPerRef(this.value)">
                                                                <i class="mdi mdi-eye" style="font-size: 24px; color: #007bff;"></i>
                                                            </button>
                                                        </td>
                                                    @endif
                                                    <td class="text-center">
                                                        @if ($reference->status_souvenir !== "success")
                                                            <button class="btn"
                                                                id="btn-edit-save_{{ $key }}"
                                                                style="padding: 0;"
                                                                data-edit="edit_{{ $key }}"
                                                                onclick="clickEdit(this)"
                                                                data-toggle="modal"
                                                                data-target="#edit-reference"
                                                                value="{{ $reference->id }}">
                                                                <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <button class="btn"
                                                            id="btn-delete-reference_{{ $key }}"
                                                            style="padding: 0;"
                                                            onclick="deleteReference(this)"
                                                            data-id="{{ $reference->id }}"
                                                            data-toggle="modal"
                                                            data-target="#delete-reference-modal">
                                                            <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                        </button>
                                                    </td>
                                                    <td class="text-center">
                                                        @if($reference->reference_souvenir->status == 'success' && $reference->reference_souvenir->delivery_status_souvenir == 'delivered' && $reference->online_signature == '')
                                                            <button class="btn" style="padding: 0" onclick="createSignature({{ $reference->id }})">
                                                                <i class="mdi mdi-pencil-box-outline" style="font-size: 24px; color: #32a852;"></i>                                                
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                                {{-- <tr>
                                                    <td class="text-center"
                                                        id="prize_{{ $key }}"
                                                        data-prize="{{ $reference->prize_id }}"
                                                        data-permission="{{ $specialPermission }}">
                                                        @php
                                                        $bonus_prize = ($key + 1) % 3;
                                                        if (!empty($reference->prize_id)) {
                                                            $prize = Prize::select("id", "name")
                                                                ->where("id", $reference->prize_id)
                                                                ->first();

                                                            if ($bonus_prize == 0) {
                                                                echo $prize->name . " + Voucher WAKimart Rp. 1.000.000";
                                                            } else {
                                                                echo $prize->name;
                                                            }
                                                        }
                                                        @endphp
                                                    </td>
                                                    <td class="text-center"
                                                        id="status_prize_{{ $key }}"
                                                        data-permission="{{ $specialPermission }}">
                                                        {{ $reference->status_prize }}
                                                    </td>
                                                    <td class="text-center"
                                                        id="delivery_status_prize_{{ $key }}"
                                                        data-deliveryprize="{{ $reference->delivery_status_prize }}"
                                                        data-permission="{{ $specialPermission }}">
                                                        {{ $reference->delivery_status_prize }}
                                                    </td>
                                                </tr> --}}
                                            @endforeach
                                            <input type="hidden"
                                                id="temp_arr_souvenir"
                                                value="{{ json_encode($arr_souvenir) }}" />
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-12 text-center mt-4">
                                <button class="btn btn-gradient-primary mt-2"
                                    id="btnAddReference"
                                    onclick="checkAddReference()">
                                    <span>Add Reference - Happy With WAKi</span>
                                </button>
                            </div>

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
                                            value="Terima Kasih telah mengikuti program *Happy With WAKi*. Berikut adalah tautan bukti formulir ( {{ route('refrence_sehat') }}?id={{ $submission->id }} )">
                                            Share Happy With WAKi
                                        </button>
                                        {{-- <button type="submit"
                                            class="btn btn-gradient-primary mr-2 my-2"
                                            name="text"
                                            value="Terima Kasih telah mengikuti program *Keuntungan Biaya Iklan*. Berikut adalah tautan bukti formulir ( {{ route('refrence_untung') }}?id={{ $submission->id }} )">
                                            Share Program Biaya Iklan
                                        </button> --}}
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
                                                            <b>{{ $key }}</b>: {{ var_export($value, true) }}
                                                            <br>
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

            @if($specialPermission && ($submission->status_reference != "approved" && $submission->status_reference != "rejected"))
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center mb-2">
                                <h2>Status Reference</h2>
                            </div>
                            <form id="actionAdd"
                                class="forms-sample"
                                method="POST"
                                action="{{ route('update_submission_status_referensi') }}">
                                {{ csrf_field() }}
                                <input type="text"
                                    name="id"
                                    value="{{ $submission['id'] }}"
                                    hidden />
                                <div class="form-group row justify-content-center">
                                    <button id="upgradeProcess"
                                        type="submit"
                                        class="btn btn-gradient-primary m-3 btn-lg"
                                        name="status"
                                        value="approved">
                                        Approved
                                    </button>
                                    <button id="upgradeProcess"
                                        type="submit"
                                        class="btn btn-gradient-danger m-3 btn-lg"
                                        name="status"
                                        value="rejected">
                                        Reject
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@else
    <div class="row justify-content-center">
        <h2>CANNOT FIND SUBMISSION</h2>
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
                <h5 class="modal-title" id="modal-title">Edit Reference</h5>
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
                    action="{{ route("store_reference_referensi") }}">
                    @csrf
                    <input type="hidden"
                        id="edit-id"
                        name="submission_id"
                        value="{{ $submission->id }}" />
                    <input type="hidden"
                        id="url"
                        name="url"
                        value="{{ url()->full() }}" />
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
                        <label for="edit-age">Age</label>
                        <input type="number"
                            class="form-control"
                            id="edit-age"
                            name="age"
                            value=""
                            placeholder="Age"
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
                        <label for="edit-province">Province</label>
                        <select class="form-control"
                            id="edit-province"
                            onchange="setCityAdd(this)"
                            name="province"
                            required>
                            <option selected disabled>
                                Pilih Provinsi
                            </option>
                            <?php
                            $result = RajaOngkir::FetchProvince();
                            $result = $result['rajaongkir']['results'];
                            if (sizeof($result) > 0) {
                                foreach ($result as $value) {
                                    echo '<option value="'
                                        . $value['province_id']
                                        . '">'
                                        . $value['province']
                                        . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-city">City</label>
                        <select class="form-control"
                            id="edit-city"
                            name="city"
                            required>
                            <option selected disabled>
                                Pilih Kota
                            </option>
                        </select>
                    </div>
                    <div id="appendSouvenir" class="form-group">
                        <label id="label_souvenir" for="edit-souvenir">
                            Souvenir
                        </label>
                        <select class="form-control"
                            id="edit-souvenir"
                            name="souvenir_id">
                            <option selected disabled>
                                Pilih Souvenir
                            </option>
                            @foreach($souvenirs as $souvenir)
                                @if($souvenir['id'] == 7)
                                    <option value="{{ $souvenir['id'] }}" hidden>
                                        {{ $souvenir['name'] }}
                                    </option>
                                @else
                                    <option value="{{ $souvenir['id'] }}">
                                        {{ $souvenir['name'] }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    {{-- <div id="appendPrize" class="form-group">
                        <label id="label_prize" for="edit-prize">Prize</label>
                        <select class="form-control"
                            id="edit-prize"
                            name="prize_id">
                            <option selected disabled>
                                Choose Prize
                            </option>
                            @foreach($prizes as $prize)
                                @if($prize['id'] == 4)
                                    <option value="{{ $prize['id'] }}" hidden>
                                        {{ $prize['name'] }}
                                    </option>
                                @else
                                    <option value="{{ $prize['id'] }}">
                                        {{ $prize['name'] }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="form-group">
                        <label for="edit-link-hs">Home Service</label>
                        <input type="hidden"
                            id="edit-link-hs"
                            name="link_hs"
                            value="" />
                        <div id="link-hs-container"></div>
                        <br>
                        <button class="btn btn-gradient-info"
                            type="button"
                            id="btn_choose_hs"
                            data-originbutton="btn_choose_hs"
                            data-toggle="modal"
                            data-target="#choose-hs">
                            Choose Home Service
                        </button>
                        <input type="hidden" id="hs-row-count" value="0" />
                    </div>
                    <div class="form-group">
                        <label for="edit-order">Order</label>
                        <input type="hidden"
                            id="edit-order"
                            name="order_id"
                            value="" />
                        <br>
                        <button class="btn btn-gradient-info"
                            type="button"
                            id="btn_choose_order"
                            data-originbutton="btn_choose_order"
                            data-toggle="modal"
                            data-target="#choose-order">
                            Choose Waki Order
                        </button>
                    </div>
                    <div class="form-group">
                        <input type="text"
                            class="form-control"
                            id="edit-wakimart-link"
                            name="wakimart_link"
                            value=""
                            placeholder="Wakimart Link"/>
                    </div>
                </form>
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
    id="choose-hs"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Choose Home Service
                </h5>
                <button type="button"
                    class="close"
                    id="choose-hs-close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit-phone">By Date</label>
                    <input type="date"
                        class="form-control"
                        id="hs-filter-date"
                        name="date"
                        value="<?php echo date("Y-m-d"); ?>"/>

                    <form target="_blank" id="make-new-hs" action="{{ route('admin_add_homeService') }}" style="text-align: center;">
                        <button name="reference_id" id="btn_add_hs_reference" value="186" class='btn btn-gradient-primary btn-sm' type='submit' style="width: 100%; margin: 1em 0em 0em 0em;" >New Home Service</button>
                    </form>
                </div>
                <div style="overflow-y: auto; height: 20em;">
                    <table class="col-md-12" style="margin: 1em 0em;">
                        <thead>
                            <td>Time</td>
                            <td>Detail</td>
                            <td>Choose</td>
                        </thead>
                        <tbody id="table-hs"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade"
    id="choose-order"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Choose Order
                </h5>
                <button type="button"
                    class="close"
                    id="choose-order-close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="order-filter-name_phone">By Name / Phone</label>
                    <input type="text"
                        class="form-control"
                        id="order-filter-name_phone"
                        maxlength="191"
                        value=""
                        placeholder="Name / Phone"/>
                </div>
                <div style="overflow-y: auto; height: 20em;">
                    <table class="col-md-12" style="margin: 1em 0em;">
                        <thead>
                            <td>Date</td>
                            <td>Detail</td>
                            <td>Choose</td>
                        </thead>
                        <tbody id="table-order"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PER REF -->
<div class="modal fade"
    id="modal-per-reference"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 0;">
                <h5 class="text-center">
                    Detail Reference
                </h5>
                <button type="button"
                    class="close"
                    id="modal-ref-close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <div class="modal-body">
                <div id="div_detailhs" class="form-group d-none">
                    <table id="table-detail-hs" style="margin: 1em 0em; width:100%">
                        <thead>
                            <td>Tanggal & Jam</td>
                            <td>Link HS</td>
                            <td>Detail</td>
                            <td>Photo</td>
                        </thead>
                        <tbody id="append_tbody_hs">

                        </tbody>
                    </table>
                </div>
                <div id="div_detailorder" class="form-group d-none">
                    <label>Detail Order</label>
                    <table id="table-detail-order" style="margin: 1em 0em; width: 100%">
                        <thead>
                            <td>Code</td>
                            <td>Member</td>
                            <td>Product</td>
                            <td>Qty</td>
                        </thead>
                        <tbody id="append_tbody_order">

                        </tbody>
                    </table>
                </div>

                <div id="div_wakimartlink" class="form-group d-none">
                    <table style="margin: 1em 0em; width:100%">
                        <thead>
                            <td>Wakimart Link</td>
                        </thead>
                        <tbody id="append_tbody_wakimart_link">

                        </tbody>
                    </table>
                </div>

                
                <form id="formUpdateStatus" method="POST" action="{{ route('update_reference') }}">
                    {{ csrf_field() }}                    
                    <div class="form-group text-center mt-4">
                        <label>Other Detail</label>
                        <table id="table-detail-other" style="margin: 1em 0em;width:100%">
                            <thead>
                                <td>Item</td>
                                <td>Name</td>
                                <td>Status</td>
                                <td>Status Delivery</td>
                            </thead>
                            <tbody id="append_tbody_other">

                            </tbody>
                        </table>

                        <input id="ref_id" type="hidden" name="id" value="">
                        <input id="ref_name" type="hidden" name="name" value="">
                        <input id="ref_phone" type="hidden" name="phone" value="">
                        <input id="ref_age" type="hidden" name="age" value="">
                        <input id="ref_province" type="hidden" name="province" value="">
                        <input id="ref_city" type="hidden" name="city" value="">
                        <input id="refs_order" type="hidden" name="order_id" value="">

                        <button class="btn btn-primary" type="submit" id="btn-confirmUpdate">SAVE</button>
                    </div>
                </form>

                <!-- display signature -->
                <div id="displaySignature" class="mt-4 text-center"></div>
            </div>

        </div>
    </div>
</div>
<!-- MODAL PER REF -->

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
                    Are you sure you want to delete this reference?
                </h5>
            </div>
            <div class="modal-footer">
                <form method="post" action="{{ route("delete_reference") }}">
                    @csrf
                    <input type="hidden" name="id" id="delete-reference-id" />
                    <input type="hidden"
                        name="url"
                        value="{{ url()->full() }}" />
                    <button type="submit" class="btn btn-gradient-danger">
                        Yes
                    </button>
                </form>
                <button class="btn btn-light" type="button">No</button>
            </div>
        </div>
    </div>
</div>

<!-- SIGNATURE MODAL -->
<div class="modal fade"
    id="createSignatureModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding-bottom: 0;">
                <h5 class="text-center">
                    Add Signature
                </h5>
                <button type="button"
                    class="close"
                    id="modal-ref-close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <div class="modal-body">
                <form action="{{route('online_signature.add')}}" method="POST" enctype="multipart/form-data" id="signatureForm">
                    @csrf
                    <div class="col-md-12 text-center p-0">
                        <div class="wrapper">
                            <input type="hidden" name="ref_id" id="ref-id">
                            <input type="hidden" name="online_signature" id="signature-data">
                            <input type="hidden" name="url" value="{{ url()->full() }}" />
                            @foreach($references as $refForCanvas)
                                <canvas id="signature-pad-{{$refForCanvas->id}}" class="signature-pad d-none" width=500 height=200 style="border: 2px solid black"></canvas>
                                <div class="d-none div-d-none" id="button-canvas-{{$refForCanvas->id}}">
                                    <button type="button" class="btn btn-secondary btn-sm decrease-width" id="clear-canvas-{{$refForCanvas->id}}">Clear</button>
                                    <button type="button" class="btn btn-primary btn-sm decrease-width" id="save-canvas-{{$refForCanvas->id}}">Save</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END SIGNATURE MODAL -->
@endsection

@section('script')
<script type="text/javascript">
    // signature pad
    var signature_pad = []
    <?php foreach($references as $refForCanvas) { ?>
        this["canvas_{{$refForCanvas->id}}"] = $("#signature-pad-{{$refForCanvas->id}}")[0];
        if(window.devicePixelRatio >= 2){
            this["canvas_{{$refForCanvas->id}}"].width = 255
        }
        signature_pad['{{$refForCanvas->id}}'] = new SignaturePad(this["canvas_{{$refForCanvas->id}}"], {});
    <?php } ?>
</script>
<script type="application/javascript">
let provinceOption = "";
let souvenirOptionAll = `<option disabled selected value="">Choose Souvenir</option>`;
let souvenirOption = `<option disabled selected value="">Choose Souvenir</option>`;
let prizeOptionAll = `<option selected value="">Choose Prize</option>`;
let prizeOption = `<option selected value="">Choose Prize</option>`;

var souvenir_res = null;

document.addEventListener('DOMContentLoaded', function () {
    const URL_PROVINCE = '<?php echo route("fetchProvince"); ?>';
    const URL_SOUVENIR = '<?php echo route("fetchSouvenir"); ?>';
    const URL_PRIZE = '<?php echo route("fetchPrize"); ?>';

    fetch(
        URL_PROVINCE,
        {
            method: "GET",
            headers: {
                "Accept": "application/json",
            },
        }
    ).then(function (response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    }).then(function (response) {
        const result = response["rajaongkir"]["results"];

        result.forEach(function (currentValue) {
            provinceOption += `<option value="${currentValue["province_id"]}">`
                + currentValue["province"]
                + `</option>`;
        });
    }).catch(function (error) {
        console.error(error);
    });

    fetch(
        URL_SOUVENIR,
        {
            method: "GET",
            headers: {
                "Accept": "application/json",
            },
        }
    ).then(function (response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    }).then(function (response) {
        const result = response.data;
        souvenir_res = result;

        result.forEach(function (currentValue) {
            if (currentValue["id"] === 7) {
                souvenirOption += `<option value="${currentValue["id"]}" hidden="">`
                    + currentValue["name"]
                    + `</option>`;
            } else {
                souvenirOption += `<option value="${currentValue["id"]}">`
                    + currentValue["name"]
                    + `</option>`;
            }

            souvenirOptionAll += `<option value="${currentValue["id"]}">`
                + currentValue["name"]
                + `</option>`;
        });
    }).catch(function (error) {
        console.error(error);
    });

    fetch(
        URL_PRIZE,
        {
            method: "GET",
            headers: {
                "Accept": "application/json",
            },
        }
    ).then(function (response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    }).then(function (response) {
        const result = response.data;

        result.forEach(function (currentValue) {
            if (currentValue["id"] === 4) {
                prizeOption += `<option value="${currentValue["id"]}" hidden>`
                    + currentValue["name"]
                    + `</option>`;
            } else {
                prizeOption += `<option value="${currentValue["id"]}">`
                    + currentValue["name"]
                    + `</option>`;
            }

            prizeOptionAll += `<option value="${currentValue["id"]}">`
                + currentValue["name"]
                + `</option>`;
        });
    }).catch(function (error) {
        console.error(error);
    });
}, false);

function getCSRF() {
    const getMeta = document.getElementsByTagName("meta");
    let metaCSRF = "";

    for (let i = 0; i < getMeta.length; i++) {
        if (getMeta[i].getAttribute("name") === "csrf-token") {
            metaCSRF = getMeta[i].getAttribute("content");
            break;
        }
    }

    return metaCSRF;
}

function setCity(e) {
    fetch(
        '<?php echo route("fetchCity", ["province" => ""]); ?>/' + e.value,
        {
            method: "GET",
            headers: {
                "Accept": "application/json",
            },
        }
    ).then(function (response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    }).then(function (response) {
        const result = response["rajaongkir"]["results"];

        const arrCity = [];
        arrCity[0] = '<option disabled value="">Pilihan Kabupaten</option>';
        arrCity[1] = '<option disabled value="">Pilihan Kota</option>';

        if (result.length > 0) {
            result.forEach(function (currentValue) {
                if (currentValue["type"] === "Kabupaten") {
                    arrCity[0] += `<option value="${currentValue["city_id"]}">`
                        + `${currentValue['type']} ${currentValue['city_name']}`
                        + `</option>`;
                } else {
                    arrCity[1] += `<option value="${currentValue['city_id']}">`
                        + `${currentValue['type']} ${currentValue['city_name']}`
                        + `</option>`;
                }
            });

            const refSeq = e.id.split("_")[1];

            document.getElementById("edit-city_" + refSeq).innerHTML = arrCity[0] + arrCity[1];
            document.getElementById("edit-city_" + refSeq).value = document.getElementById("city_" + refSeq).getAttribute("data-city");
        }
    }).catch(function(error) {
        console.error(error);
    });
}

function setCityAdd(e) {
    fetch(
        '<?php echo route("fetchCity", ["province" => ""]); ?>/' + e.value,
        {
            method: "GET",
            headers: {
                "Accept": "application/json",
            },
        }
    ).then(function (response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    }).then(function (response) {
        const result = response["rajaongkir"]["results"];

        const arrCity = [];
        arrCity[0] = '<option disabled value="">Pilihan Kabupaten</option>';
        arrCity[1] = '<option disabled value="">Pilihan Kota</option>';

        if (result.length > 0) {
            result.forEach(function (currentValue) {
                if (currentValue["type"] === "Kabupaten") {
                    arrCity[0] += `<option value="${currentValue["city_id"]}">`
                        + `${currentValue['type']} ${currentValue['city_name']}`
                        + `</option>`;
                } else {
                    arrCity[1] += `<option value="${currentValue['city_id']}">`
                        + `${currentValue['type']} ${currentValue['city_name']}`
                        + `</option>`;
                }
            });

            document.getElementById("edit-city").innerHTML = arrCity[0] + arrCity[1];
        }
    }).catch(function(error) {
        console.error(error);
    });
}

var total_ref = $('#form-length').val();
function checkAddReference() {
    clearModal();

    var temp_index_ref = parseInt(total_ref) + 1;

    // Checking souvenir 1juta
    var arrSouvenir = JSON.parse($("#temp_arr_souvenir").val(), true);
    var check_souvenir = arrSouvenir.includes(7);

    if(check_souvenir == false){
        if(temp_index_ref >= 6){
            //showing souvenir voucher 1jt
            $('#label_souvenir').remove();
            $('#edit-souvenir').remove();
            $('#appendSouvenir').append('\
                <label id="label_souvenir" for="edit-souvenir">Souvenir</label>\
                <select class="form-control" id="edit-souvenir" name="souvenir_id">'
                + souvenirOptionAll +
                '</select>\
            ');
        }
    }

    $("#edit-reference").modal('show');
}

function loadDataPerRef(ref_id) {
    $.get('{{ route("fetchDetailPerReference", ['reference' => ""]) }}/' + ref_id )
    .done(function( result ) {
        // Empty data
        $('#div_detailhs').addClass('d-none');
        $('#div_detailorder').addClass('d-none');
        $('#div_wakimartlink').addClass('d-none');
        $('#append_tbody_hs').empty();
        $('#append_tbody_order').empty();
        $('#append_tbody_wakimart_link').empty();
        $('#append_tbody_other').empty();

        if(result.length > 0){
            var data = JSON.parse(result);
            var data_refs = data['data_refs'];// Reference souvenir
            var data_ref = data['data_ref']; // Reference
            var data_hs = data['data_hs'];
            var data_order = data['data_order'];
            var data_souvenir = data['data_souvenir'];
            // var data_prize = data['data_prize'];
            var detail_product = data['detail_product'];
            // Detail HS
            if (data_hs != null) {
                $('#div_detailhs').removeClass('d-none');
                for (var i = 0; i < data_hs.length; i++) {
                    var url = "{{ route('homeServices_success', ['code'=>"codeTmp"]) }}";
                    url = url.replace('codeTmp', data_hs[i]['code']);

                    var img_url = "{{ asset('sources/homeservice/') }}" +"/"+ data_hs[i]['image'];

                    if (data_hs[i]['image'] != null) {
                        $('#append_tbody_hs').append('\
                            <tr id="tr_detail_hs_'+i+'">\
                                <td id="td_date_'+i+'">'+ data_hs[i]['appointment']+'</td>\
                                <td id="td_link_'+i+'"><a id="a_link_'+i+'" href="'+url+'" target="_blank">Open Link</a></td>\
                                <td id="td_detailcust_'+i+'">'+data_hs[i]['name']+'<br>'+data_hs[i]['phone']+'<br>'+data_hs[i]['address']+'</td>\
                                <td id="td_image_'+i+'">\
                                    <a href="'+img_url+'" target="_blank">\
                                        <i class="mdi mdi-numeric-'+i+'-box" style="font-size: 24px; color: blue;"></i>\
                                    </a>\
                                </td>\
                            </tr>\
                        ');
                    } else {
                       $('#append_tbody_hs').append('\
                            <tr id="tr_detail_hs_'+i+'">\
                                <td id="td_date_'+i+'">'+ data_hs[i]['appointment']+'</td>\
                                <td id="td_link_'+i+'"><a id="a_link_'+i+'" href="'+url+'" target="_blank">Open Link</a></td>\
                                <td id="td_detailcust_'+i+'">'+data_hs[i]['name']+'<br>'+data_hs[i]['phone']+'<br>'+data_hs[i]['address']+'</td>\
                                <td id="td_image_'+i+'">\
                                    <a href="" target="_blank"></a>\
                                </td>\
                            </tr>\
                        ');
                    }

                }
            }

            //detail Order
            if(data_order != null){
                $('#div_detailorder').removeClass('d-none');

                var rowspan = detail_product.length;
                for (var o = 0; o < detail_product.length; o++) {
                    $('#append_tbody_order').append('\
                        <tr id="tr_detail_order_'+o+'">\
                            <td rowspan="'+rowspan+'" id="td_code_'+o+'">'+data_order[o]['code']+'</td>\
                            <td rowspan="'+rowspan+'" id="td_detailcustorder_'+o+'">'+data_order[o]['name']+'<br>'+data_order[o]['phone']+'<br>'+data_order[o]['address']+'</td>\
                            <td id="td_detailproduct_'+o+'">'+detail_product[o]['name']+'</td>\
                            <td id="td_qtyproduct_'+o+'">'+detail_product[o]['qty']+'</td>\
                        </tr>\
                    ');
                    break;
                }

                var first = true;
                for (var y = 0; y < detail_product.length; y++) {
                    if(first){
                        first = false;
                        continue;
                    }
                    $('#append_tbody_order').append('\
                        <tr id="tr_detail_order_'+y+'">\
                            <td id="td_detailproduct_'+y+'">'+detail_product[y]['name']+'</td>\
                            <td id="td_qtyproduct_'+y+'">'+detail_product[y]['qty']+'</td>\
                        </tr>\
                    ');
                }

                var total_payment = parseInt(data_order[0]['total_payment']);
                var fix_totalpayment = total_payment.toFixed(0);
                $('#append_tbody_order').append('\
                    <tr>\
                        <td colspan="2" style="text-align: right;"><b>TOTAL (Rp)</b></td>\
                        <td colspan="2" style="text-align: right;">'+fix_totalpayment+'</td>\
                    </tr>\
                ');
                $('#refs_order').val(data_refs[0]['order_id']);

            }

            // data wakimart_link
            if ( data_refs[0]['wakimart_link'] != null ) {
                $('#div_wakimartlink').removeClass('d-none');
                $('#append_tbody_wakimart_link').append('\
                    <tr>\
                        <td><a href="'+data_refs[0]['wakimart_link']+'" target="_blank">'+data_refs[0]['wakimart_link']+'</a></td>\
                    </tr>\
                ');
            }
            if (data_souvenir != null) {
                for (var a = 0; a < data_souvenir.length; a++) {
                    $('#append_tbody_other').append('\
                        <tr id="tr_detail_souvenir">\
                            <td>SOUVENIR</td>\
                            <td>\
                                <select id="select_edit-souvenir_'+a+'" class="form-control" name="souvenir_id">'
                                + souvenirOptionAll +
                                '</select>\
                            </td>\
                            <td>\
                                <select id="select_edit-status_'+a+'" class="form-control" name="status" >\
                                    <option value="">Choose Status</option>\
                                    <option value="pending">pending</option>\
                                    <option value="success">success</option>\
                                </select>\
                            </td>\
                            <td>\
                                <select id="select_edit-delivery-status-souvenir_'+a+'" class="form-control" name="delivery_status_souvenir">\
                                    <option value="">Choose Status Delivery</option>\
                                    <option value="undelivered">undelivered</option>\
                                    <option value="delivered">delivered</option>\
                                </select>\
                            </td>\
                        </tr>\
                    ');

                    $('#select_edit-souvenir_'+a).val(data_refs[a]['souvenir_id']);
                    $('#select_edit-status_'+a).val(data_refs[a]['status']);
                    $('#select_edit-delivery-status-souvenir_'+a).val(data_refs[a]['delivery_status_souvenir']);
                }
            }

            //data_prize != null
            if (false) {
                for (var p = 0; p < data_prize.length; p++) {
                    $('#append_tbody_other').append('\
                        <tr id="tr_detail_souvenir">\
                            <td>PRIZE</td>\
                            <td>\
                                <select id="select_edit-prize_'+p+'" class="form-control" name="prize_id">'
                                + prizeOptionAll +
                                '</select>\
                            </td>\
                            <td>\
                                <select id="select_edit-status-prize_'+p+'" class="form-control" name="status_prize">\
                                    <option value="">Choose Status</option>\
                                    <option value="pending">pending</option>\
                                    <option value="success">success</option>\
                                </select>\
                            </td>\
                            <td>\
                                <select id="select_edit-delivery-status-prize_'+p+'" class="form-control" name="delivery_status_prize">\
                                    <option value="">Choose Status Delivery</option>\
                                    <option value="undelivered">undelivered</option>\
                                    <option value="delivered">delivered</option>\
                                </select>\
                            </td>\
                        </tr>\
                    ');

                    $('#select_edit-prize_'+p).val(data_refs[p]['prize_id']);
                    $('#select_edit-status-prize_'+p).val(data_refs[p]['status_prize']);
                    $('#select_edit-delivery-status-prize_'+p).val(data_refs[p]['delivery_status_prize']);
                }
            }

            // || data_prize != null
            if (data_souvenir != null) {
                $('#ref_id').val(data_ref['id']);
                $('#ref_name').val(data_ref['name']);
                $('#ref_age').val(data_ref['age']);
                $('#ref_phone').val(data_ref['phone']);
                $('#ref_province').val(data_ref['province']);
                $('#ref_city').val(data_ref['city']);
                // $('#refs_souvenir').val(data_refs[0]['souvenir_id']);
                // $('#refs_prize').val(data_refs[0]['prize_id']);
                // $('#refs_order').val(data_refs[0]['order_id']);
            }

            var displaySignature = ''
            if(data_ref.online_signature){
                displaySignature = `
                    <img src="{{asset('sources/online_signature/${data_ref.online_signature}')}}">
                `
            }
            $('#displaySignature').html(displaySignature)
            console.log(data)

            $("#modal-per-reference").modal("show");
        }
    });
}

function clearModal() {
    const submissionId = '{{ $submission->id }}';
    const actionStore = '{{ route("store_reference_referensi") }}';
    document.getElementById("edit-form").setAttribute("action", actionStore);
    document.getElementById("edit-id").value = submissionId;
    document.getElementById("edit-name").value = "";
    document.getElementById("edit-age").value = "";
    document.getElementById("edit-phone").value = "";
    document.getElementById("edit-wakimart-link").value = "";
    document.getElementById("edit-province").selectedIndex = 0;
    document.getElementById("edit-city").value = "";
    document.getElementById("edit-souvenir").selectedIndex = 0;
    // document.getElementById("edit-prize").selectedIndex = 0;
    document.getElementById("edit-link-hs").value = "";
    document.getElementById("link-hs-container").innerHTML = "";
    document.getElementById("edit-order").value = "";
    // document.getElementById("btn_choose_order").innerHTML = "Choose Order";
}

function clickEdit(e) {
    clearModal();
    const refSeq = e.dataset.edit.split("_")[1];

    //checking souvenir 1juta
    var arrSouvenir = JSON.parse($("#temp_arr_souvenir").val(), true);
    var check_souvenir = arrSouvenir.includes(7);

    var counter_ref = parseInt(refSeq) + 1;
    if (check_souvenir == false) {
        if (counter_ref >= 6) {
            souvenirOptionEdit = souvenirOptionAll;
        } else {
            souvenirOptionEdit = souvenirOption;
        }
    }

    const actionUpdate = '{{ route("update_reference") }}';
    const id = document.getElementById("edit-id_" + refSeq).value;
    const name = document.getElementById("name_" + refSeq).innerHTML.trim();
    const age = document.getElementById("age_" + refSeq).innerHTML.trim();
    const phone = document.getElementById("phone_" + refSeq).innerHTML.trim();
    const wakimart_link = document.getElementById("wakimart_link_" + refSeq).value;
    const province = document.getElementById("province_" + refSeq).getAttribute("data-province");
    const city = document.getElementById("city_" + refSeq).getAttribute("data-city");
    const souvenir = document.getElementById("souvenir_" + refSeq).getAttribute("data-souvenir");
    // const prize = document.getElementById("prize_" + refSeq).dataset.prize;

    function linkHS() {
        try {
            return document.getElementById("link-hs_" + refSeq).dataset.hs;
        } catch (error) {
            delete error;
            return "";
        }
    };

    const hsArray = linkHS() ? linkHS().split(", ") : [];

    function orderId() {
        try {
            return document.getElementById("order_" + refSeq).dataset.order;
        } catch (error) {
            delete error;
            return "";
        }
    }

    function orderCode() {
        try {
            // return document.getElementById("order_" + refSeq).innerHTML.trim();
            return document.getElementById("order_white_" + refSeq).innerHTML.trim();
        } catch (error) {
            delete error;
            return "";
        }
    }

    document.getElementById("edit-form").setAttribute("action", actionUpdate);
    document.getElementById("edit-id").value = id;
    document.getElementById("btn_add_hs_reference").value = id;
    document.getElementById("edit-name").value = name;
    document.getElementById("edit-age").value = age;
    document.getElementById("edit-phone").value = phone;
    document.getElementById("edit-wakimart-link").value = wakimart_link;
    document.getElementById("edit-province").value = province;
    setCityAdd(document.getElementById("edit-province"));
    setTimeout(function () {
        document.getElementById("edit-city").value = city;
    }, 500);
    document.getElementById("edit-souvenir").innerHTML = souvenirOptionEdit;
    document.getElementById("edit-souvenir").value = souvenir;
    // document.getElementById("edit-prize").value = prize;

    document.getElementById("hs-row-count").value = hsArray.length;
    hsArray.forEach(function (value, index) {
        const divRow = document.createElement("div");
        divRow.id = `hs-row_${index}`;
        divRow.className = "form-row";

        const hsTextInput = document.createElement("input");
        hsTextInput.type = "text";
        hsTextInput.className = "form-control col-8";
        hsTextInput.disabled = true;
        hsTextInput.readOnly = true;
        hsTextInput.value = document.getElementById(`link-hs-href_${value}`).dataset.hs;

        const buttonRemove = document.createElement("button");
        buttonRemove.type = "button";
        buttonRemove.className = "btn btn-gradient-danger col-4";
        buttonRemove.innerHTML = "Remove";
        buttonRemove.setAttribute("data-hs", value);
        buttonRemove.setAttribute("data-sequence", index);
        buttonRemove.setAttribute("onclick", "removeHS(this)");

        divRow.appendChild(hsTextInput);
        divRow.appendChild(buttonRemove);
        document.getElementById("link-hs-container").appendChild(divRow);
    });
    document.getElementById("edit-link-hs").value = hsArray.join(", ");

    document.getElementById("edit-order").value = orderId();
    document.getElementById("btn_choose_order").innerHTML = orderCode() || "Choose Waki Order";
}

function validateForm(refSeq) {
    const inputArray = [
        "edit-name_",
        "edit-age_",
        "edit-phone_",
        "edit-province_",
        "edit-city_",
        "edit-souvenir_",
        "edit-link-hs_",
        "edit-status_",
    ];

    let valid = true;

    inputArray.forEach(function (currentValue) {
        try {
            const inputBeingChecked = document.getElementById(currentValue + refSeq);

            if (!inputBeingChecked.checkValidity()) {
                addOrRemoveInvalid(inputBeingChecked, "add");
                valid = false;
            } else {
                addOrRemoveInvalid(inputBeingChecked, "remove");
            }
        } catch (error) {
            delete error;
        }
    });

    if (document.getElementById("status_souvenir_" + refSeq).dataset.permission) {
        const souvenirData = [];
        const formLength = parseInt(document.getElementById("form-length").value, 10);
        for (let i = 0; i < formLength; i++) {
            souvenirData.push(document.getElementById("souvenir_" + i).dataset.souvenir);
        }

        const souvenir = document.getElementById("edit-souvenir_" + refSeq);
        souvenirData[refSeq] = souvenir.value;

        const findDuplicate = souvenirData.filter(function (currentValue) {
            return currentValue === souvenir.value;
        });

        if (findDuplicate.length > 2) {
            addOrRemoveInvalid(souvenir, "add");
            valid = false;
        } else {
            addOrRemoveInvalid(souvenir, "remove");
        }
    }

    return valid;
}

function addOrRemoveInvalid(element, command) {
    if (command === "add") {
        if (!element.className.includes("error")) {
            element.classList.add("error");
        }
    } else if (command === "remove") {
        if (element.className.includes("error")) {
            element.classList.remove("error");
        }
    }
}

function submitEdit(e) {
    const refSeq = e.dataset.edit.split("_")[1];
    const form = document.getElementById("edit-form_" + refSeq);
    const data = new URLSearchParams();
    const URL = '<?php echo route("update_reference"); ?>';

    for (const pair of new FormData(form)) {
        data.append(pair[0], pair[1]);
    }

    console.log(data);

    if (!validateForm(refSeq)) {
        return false;
    }

    fetch(
        URL,
        {
            method: "POST",
            headers: {
                "Accept": "application/json",
                "X-CSRF-TOKEN": getCSRF(),
            },
            body: data,
        }
    ).then(function (response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    }).then(function (response) {
        // const data = response.data;
        // const dataSouvenir = response.dataSouvenir;

        // document.getElementById("name_" + refSeq).innerHTML = data.name;
        // document.getElementById("age_" + refSeq).innerHTML = data.age;
        // document.getElementById("phone_" + refSeq).innerHTML = data.phone;
        // document.getElementById("province_" + refSeq).setAttribute("data-province", data.province);
        // document.getElementById("province_" + refSeq).innerHTML = response.province;
        // document.getElementById("city_" + refSeq).setAttribute("data-city", data.city);
        // document.getElementById("city_" + refSeq).innerHTML = response.city;
        // document.getElementById("souvenir_" + refSeq).setAttribute("data-souvenir", dataSouvenir.souvenir_id);
        // document.getElementById("souvenir_" + refSeq).innerHTML = response.souvenir;

        // if (dataSouvenir.link_hs) {
        //     document.getElementById("link-hs_" + refSeq).innerHTML = `<a href="${dataSouvenir.link_hs}" id="link-hs-href_${refSeq}" target="blank">`
        //         + `<i class="mdi mdi-home" style="font-size: 24px; color: #2daaff;"></i>`
        //         + `</a>`;
        // } else {
        //     document.getElementById("link-hs_" + refSeq).innerHTML = "";
        // }

        // document.getElementById("status_" + refSeq).innerHTML = dataSouvenir.status;

        // document.getElementById("btn-edit-save_" + refSeq).setAttribute("onclick", "clickEdit(this)");
        // document.getElementById("btn-edit-save_" + refSeq).innerHTML = `<i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>`;
        //console.log(response);
        location.reload();
    }).catch(function (error){
        console.error(error);
    });
}

// NEW System
$(document).ready(function () {
    let originButton = "";

    $("#edit-reference").on('shown.bs.modal', function () {
        if ($(".modal-backdrop").length > 1) {
            $(".modal-backdrop")[0].remove();
        }
    });

    $("#edit-reference").on('hidden.bs.modal', function () {
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    });

    // KHUSUS UNTUK HS
    $("#choose-hs").on('shown.bs.modal', function (event) {
        // $("#edit-reference").modal('hide');
        originButton = event.relatedTarget.dataset.originbutton;

        let submission_id = "{{ $submission->id }}";
        let date = $('#hs-filter-date').val();

        getHsSubmission(date, submission_id, originButton);
    });

    $("#choose-hs").on('hidden.bs.modal', function () {
        $('#btn_add_hs_reference').val("");
    });

    $('#hs-filter-date').on('change', function (e) {
        let submission_id = "{{ $submission->id }}";
        let date = $('#hs-filter-date').val();
        getHsSubmission(date, submission_id, originButton);
    });

    function getHsSubmission(date, submission_id, originButton) {
        $('#table-hs').html("");
        $.get('{{ route("list_hs_submission") }}', { date: date, submission_id: submission_id })
            .done(function (result) {
                if (result.hs.length > 0) {
                    let hsNya = result.hs;

                    $.each(hsNya, function (key, value) {
                        let JamNya = new Date(value.appointment);

                        let isiNya = "<tr><td>" + JamNya.getHours() + ":" + (JamNya.getMinutes() < 10 ? '0' : '') + JamNya.getMinutes() + "</td><td>" +
                        "<b>Name</b>: " + value.name + "<br>" +
                        "<b>Phone</b>: " + value.phone + "<br>" +
                        "<b>Address</b>: " + value.address + "<br>";

                        if (originButton === "btn_choose_hs") {
                            isiNya += "</td><td><button class='btn btn-gradient-info btn-sm' type='button' onclick='selectHsNya(" + value.id + ", \"" + value.code + "\")'>Choose This</button></td></tr>";
                        } else {
                            isiNya += `</td><td><button class='btn btn-gradient-info btn-sm' type='button' onclick='selectHsForEdit(${value.id}, "${value.code}", "${originButton}")'>Choose This</button></td></tr>`;
                        }

                        $('#table-hs').append(isiNya);
                    });
                } else {
                    let isiNya = "<tr><td colspan='3' style='text-align: center;'>No Data</td></tr>";
                    $('#table-hs').append(isiNya);
                }
            });
    }

    // KHUSUS UNTUK ORDER
    $("#choose-order").on('shown.bs.modal', function (event) {
        // $("#edit-reference").modal('hide');
        originButton = event.relatedTarget.dataset.originbutton;

        let submission_id = "{{ $submission->id }}";
        getOrderSubmission("", submission_id, originButton);
    });

    // $("#choose-order").on('hidden.bs.modal', function () {
    //     $("#edit-reference").modal('show');
    // });

    $('#order-filter-name_phone').on('input', function (e) {
        let submission_id = "{{ $submission->id }}";
        let filter = $(this).val();
        getOrderSubmission(filter, submission_id, "btn_choose_order");
    });

    function getOrderSubmission(filter, submission_id, originButton) {
        $('#table-order').html("");
        let isiNya = "<tr><td colspan='3' style='text-align: center'>Loading...</td></tr>";
        $('#table-order').append(isiNya);

        $.get('{{ route("list_order_submission") }}', { filter: filter, submission_id: submission_id })
            .done(function (result) {
                $('#table-order').html("");
                if (result.orders.length > 0) {
                    let orderNya = result.orders;

                    $.each(orderNya, function (key, value) {
                        let isiNya = "<tr><td>" + value.orderDate + "</td><td>" +
                        "<b>Name</b>: " + value.name + "<br>" +
                        "<b>Phone</b>: " + value.phone + "<br>" +
                        "<b>Address</b>: " + value.address + "<br>" +
                        "<b>Product</b>: " + value.product + "<br>";

                        if (originButton === "btn_choose_order") {
                            isiNya += "</td><td><button class='btn btn-gradient-info btn-sm' type='button' onclick='selectOrderNya(" + value.id + ", \"" + value.code + "\")'>Choose This</button></td></tr>";
                        } else {
                            isiNya += `</td><td><button class='btn btn-gradient-info btn-sm' type='button' onclick='selectOrderForEdit(${value.id}, "${value.code}", "${originButton}")'>Choose This</button></td></tr>`;
                        }

                        $('#table-order').append(isiNya);
                    });
                } else {
                    let isiNya = "<tr><td colspan='3' style='text-align: center;'>No Data</td></tr>";
                    $('#table-order').append(isiNya);
                }
            });
    }

    //UPDATE STATUS ONLY
    var frmUpdate;

    $("#formUpdateStatus").on("submit", function (e) {
        e.preventDefault();
        frmAdd = _("formUpdateStatus");
        frmAdd = new FormData(document.getElementById("formUpdateStatus"));
        frmAdd.enctype = "multipart/form-data";
        var URLNya = $("#formUpdateStatus").attr('action');
        console.log(URLNya);

        var ajax = new XMLHttpRequest();
        ajax.upload.addEventListener("progress", progressHandler, false);
        ajax.addEventListener("load", completeHandler, false);
        ajax.addEventListener("error", errorHandler, false);
        ajax.open("POST", URLNya);
        ajax.setRequestHeader("X-CSRF-TOKEN",$('meta[name="csrf-token"]').attr('content'));
        ajax.send(frmAdd);
    });
    function progressHandler(event){
        document.getElementById("btn-confirmUpdate").innerHTML = "UPLOADING...";
    }
    function completeHandler(event){
        var hasil = JSON.parse(event.target.responseText);

        for (var key of frmAdd.keys()) {
            $("#formUpdateStatus").find("input[name="+key.name+"]").removeClass("is-invalid");
            $("#formUpdateStatus").find("select[name="+key.name+"]").removeClass("is-invalid");
            $("#formUpdateStatus").find("textarea[name="+key.name+"]").removeClass("is-invalid");

            $("#formUpdateStatus").find("input[name="+key.name+"]").next().find("strong").text("");
            $("#formUpdateStatus").find("select[name="+key.name+"]").next().find("strong").text("");
            $("#formUpdateStatus").find("textarea[name="+key.name+"]").next().find("strong").text("");
        }

        if(hasil['errors'] != null){
            for (var key of frmAdd.keys()) {
                if(typeof hasil['errors'][key] === 'undefined') {

                }
                else {
                    $("#formUpdateStatus").find("input[name="+key+"]").addClass("is-invalid");
                    $("#formUpdateStatus").find("select[name="+key+"]").addClass("is-invalid");
                    $("#formUpdateStatus").find("textarea[name="+key+"]").addClass("is-invalid");

                    $("#formUpdateStatus").find("input[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
                    $("#formUpdateStatus").find("select[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
                    $("#formUpdateStatus").find("textarea[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
                }
            }
            alert(hasil['errors']);
        }
        else{
            alert("Input Success !!!");
            window.location.reload()
        }
        document.getElementById("btn-confirmUpdate").innerHTML = "SAVE";
    }
    function errorHandler(event){
        document.getElementById("btn-confirmUpdate").innerHTML = "SAVE";
    }
});

function selectHsNya(id, code) {
    let linkHsArray = [];

    if (document.getElementById("edit-link-hs").value) {
        linkHsArray = (document.getElementById("edit-link-hs").value).split(", ");
    }

    linkHsArray.push(id);
    document.getElementById("edit-link-hs").value = linkHsArray.join(", ");

    let hsRow = document.getElementById("hs-row-count").value;

    const divRow = document.createElement("div");
    divRow.id = `hs-row_${hsRow}`;
    divRow.className = "form-row";

    const hsTextInput = document.createElement("input");
    hsTextInput.type = "text";
    hsTextInput.className = "form-control col-8";
    hsTextInput.disabled = true;
    hsTextInput.readOnly = true;
    hsTextInput.value = code;

    const buttonRemove = document.createElement("button");
    buttonRemove.type = "button";
    buttonRemove.className = "btn btn-gradient-danger col-4";
    buttonRemove.innerHTML = "Remove";
    buttonRemove.setAttribute("data-hs", id);
    buttonRemove.setAttribute("data-sequence", hsRow);
    buttonRemove.setAttribute("onclick", "removeHS(this)");

    divRow.appendChild(hsTextInput);
    divRow.appendChild(buttonRemove);
    document.getElementById("link-hs-container").appendChild(divRow);

    hsRow++;
    document.getElementById("hs-row-count").value = hsRow;

    $("#choose-hs").modal('hide');
}

function removeHS(e) {
    const linkHsArray = (document.getElementById("edit-link-hs").value).split(", ");
    const reconstructedHsArray = linkHsArray.filter(function (value) {
        return value !== e.dataset.hs;
    });
    document.getElementById("edit-link-hs").value = reconstructedHsArray.join(", ");

    document.getElementById(`hs-row_${e.dataset.sequence}`).remove();
}

function selectOrderNya(id, code) {
    $('#edit-order').val(id);
    $('#btn_choose_order').html("Order Code: " + code);
    $("#choose-order").modal('hide');
}

function selectHsForEdit(id, code, origin) {
    const REF_SEQ = origin.split("_")[1];
    let linkHsArray = [];

    if (document.getElementById("edit-link_hs_" + REF_SEQ).value) {
        linkHsArray = (document.getElementById("edit-link_hs_" + REF_SEQ).value).split(", ");
    } else {
        linkHsArray = [];
    }

    linkHsArray.push(id);
    document.getElementById("edit-link_hs_" + REF_SEQ).value = linkHsArray.join(", ");
    document.getElementById("btn-choose-hs-edit_" + REF_SEQ).innerHTML = code;

    $("#choose-hs-close").click();
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
}

function selectOrderForEdit(id, code, origin) {
    const REF_SEQ = origin.split("_")[1];
    document.getElementById("order_id_" + REF_SEQ).value = id;
    document.getElementById("btn-choose-order-edit_" + REF_SEQ).innerHTML = code;

    $("#choose-order-close").click();
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
}

function deleteReference(e) {
    document.getElementById("delete-reference-id").value = e.dataset.id;
}

function createSignature(id) {
    $(".div-d-none").addClass('d-none')
    $('.signature-pad').addClass('d-none')
    $("#signature-pad-"+id).removeClass('d-none')
    $('#createSignatureModal').modal('show');
    $('#button-canvas-'+id).removeClass('d-none')
    document.getElementById('clear-canvas-'+id).addEventListener('click', function () {
        signature_pad[id].clear();
    });
    document.getElementById('save-canvas-'+id).addEventListener('click', function () {
        if (signature_pad[id].isEmpty()) {
            return alert("Please provide a signature first.");
        } else {
            $('#ref-id').val(id)
            $('#signature-data').val(signature_pad[id].toDataURL("image/png"))
            $('#signatureForm').submit()
        }
    });
}
</script>
@endsection
