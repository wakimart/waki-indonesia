<?php

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
    #intro {
        padding-top: 2em;
        min-width: 0;
    }

    table {
        margin: 1em;
        font-size: 14px;
    }

    .table-responsive table {
        width: 100%; 
        overflow:scroll;
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

    .pInTable {
        margin-bottom: 6pt !important;
        font-size: 10pt;
    }

    select.form-control {
        color: black !important;
    }

    @media screen and (max-width: 768px) {

        .table-responsive{
            margin-right: 10px;
        }
    }

</style>
@endsection

@section('content')
@if ($submission->code !== null)
    <section id="intro" class="clearfix">
        <div class="container">
            <div class="row justify-content-center">
                <h2>SUBMISSION SUCCESS</h2>
            </div>
            <div class="row justify-content-center">
                <table class="col-md-12">
                    <thead>
                        <td class="text-right">Submission Date</td>
                    </thead>
                    <tr>
                        <td class="text-right">
                            {{ date("d/m/Y H:i:s", strtotime($submission->created_at)) }}
                        </td>
                    </tr>
                </table>

                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Customer Data</td>
                    </thead>
                    <tr>
                        <td>Member Code: </td>
                        <td>{{ $submission->no_member }}</td>
                    </tr>
                    <tr>
                        <td>Name: </td>
                        <td>{{ $submission->name }}</td>
                    </tr>
                    <tr>
                        <td>Phone Number: </td>
                        <td>{{ $submission->phone }}</td>
                    </tr>
                    <tr>
                        <td rowspan="2">Address: </td>
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
                </table>

                <table class="col-md-12">
                    <thead>
                        <td>Sales Branch</td>
                        <td>Sales Code</td>
                    </thead>
                    <tr>
                        <td style="width:50%; text-align: center">
                            <?php
                            echo $submission->branch_code
                                . " - "
                                . $submission->branch_name;
                            ?>
                        </td>
                        <td style="width:50%; text-align: center">
                            {{ $submission->cso_code }}
                        </td>
                    </tr>
                </table>

                <div class="table-responsive">
                    <table class="col-md-12">
                        <thead>
                            <td colspan="12">Reference</td>
                        </thead>
                        <thead style="background-color: #80808012 !important;">
                            <tr>
                                <td>Name</td>
                                <td>Age</td>
                                <td>Phone</td>
                                <td>Province</td>
                                <td>City</td>
                                <td>Order</td>
                                <td>Prize</td>
                                <td>Status</td>
                                <td>Deliv. Status</td>
                                @if ($specialPermission)
                                    <td class="text-center">View</td>
                                @endif
                                <td class="text-center">Edit</td>
                                <td class="text-center">Delete</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($references as $key => $reference)
                                <input type="hidden"
                                    class="d-none"
                                    id="id_{{ $key }}"
                                    value="{{ $reference->id }}" />
                                <input type="hidden"
                                    id="edit-id_{{ $key }}"
                                    class="d-none"
                                    name="id"
                                    form="edit-form_{{ $key }}"
                                    value="{{ $reference->id }}" />
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
                                    <td class="text-center"
                                        id="order_{{ $key }}"
                                        data-order="{{ $reference->order_id }}"
                                        style="overflow-x: auto;">
                                        @php
                                        if (!empty($reference->order_id)) {
                                            $order = Order::select("id", "code")
                                                ->where("id", $reference->order_id)
                                                ->first();

                                            echo $order->code;
                                        }
                                        @endphp
                                    </td>
                                    <td class="text-center"
                                        id="prize_{{ $key }}"
                                        data-prize="{{ $reference->prize_id }}"
                                        data-permission="{{ $specialPermission }}">
                                        @php
                                        if (!empty($reference->prize_id)) {
                                            $prize = Prize::select("id", "name")
                                                ->where("id", $reference->prize_id)
                                                ->first();
                                            echo $prize->name;
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
                                    {{-- <td class="center"
                                        id="image_{{ $key }}"
                                        data-image1="{{ asset("sources/registration/" . $reference->image_1) }}"
                                        @if ($reference->image_2 !== null)
                                            data-image2="{{ asset("sources/registration/" . $reference->image_2) }}"
                                        @endif
                                        >
                                        @for ($i = 1; $i <= 2; $i++)
                                            @if (!empty($reference["image_" . $i]))
                                                <a href="{{ asset("sources/registration/" . $reference["image_" . $i]) }}"
                                                    target="_blank">
                                                    <i class="mdi mdi-numeric-{{ $i }}-box" style="font-size: 24px; color: blue;"></i>
                                                </a>
                                            @endif
                                        @endfor
                                    </td> --}}
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-12 text-center mt-4">
                <button class="btn btn-gradient-primary mt-2"
                    data-toggle="modal"
                    data-target="#edit-reference"
                    onclick="clickAdd()">
                    Add Reference - MGM
                </button>
            </div>



            <div class="col-md-12 center"
                style="margin-top: 3em;">
                <div class="row justify-content-center">
                    <h2 class="text-center share">
                        Share Submission Form
                    </h2>
                </div>
                <form class="forms-sample"
                    method="GET"
                    action="https://wa.me/">
                    <div class="form-group row justify-content-center">
                        <button type="submit"
                            class="btn btn-gradient-primary mr-2 my-2"
                            name="text"
                            value="Terima Kasih telah mengikuti program *Member Get Member*. Berikut adalah tautan bukti formulir ( {{ route('refrence_untung') }}?id={{ $submission->id }} )">
                            Share Program MGM
                        </button>
                        <button type="submit"
                            class="btn btn-gradient-danger mr-2 my-2"
                            name="text"
                            value=" {{ route("detail_submission_form", ["id" => $submission->id, "type" => "mgm"]) }} ">
                            Share Form MGM for ACC
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
                    <table class="col-md-12">
                        <thead>
                            <td class="center">No.</td>
                            <td>Action</td>
                            <td>User</td>
                            <td>Change</td>
                            <td>Time</td>
                        </thead>
                        @foreach ($historySubmission as $key => $history)
                            <?php $dataChange = json_decode($history->meta, true); ?>
                            <tr>
                                <td class="right">{{ $key + 1 }}</td>
                                <td class="center">
                                    {{ $history->method }}
                                </td>
                                <td class="center">
                                    {{ $history->name }}
                                </td>
                                <td>
                                    @foreach ($dataChange["dataChange"] as $key => $value)
                                        <b>{{ $key }}</b>: {{ var_export($value, true) }}
                                        <br>
                                    @endforeach
                                </td>
                                <td class="center">
                                    {{ date("d/m/Y H:i:s", strtotime($history->created_at)) }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif
        </div>
    </section>
@else
    <div class="row justify-content-center">
        <h2>CANNOT FIND SUBMISSION</h2>
    </div>
@endif
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
                <form id="edit-form" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="edit-id" name="id" value="" />
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
                            onchange="setCity(this)"
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
                    <div id="appendPrize" class="form-group">
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
                            Choose Order
                        </button>
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

<div class="modal fade"
    id="modal-per-reference"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
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
                    <table id="table-detail-hs" style="margin: 1em 0em;">
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
                    <table id="table-detail-order" style="margin: 1em 0em;">
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

                <form id="formUpdateStatus" method="POST" action="{{ route('update_reference_mgm') }}">
                    @csrf
                    <div class="form-group">
                        <label>Other Detail</label>
                        <table id="table-detail-other" style="margin: 1em 0em;">
                            <thead>
                                <td>Item</td>
                                <td>Name</td>
                                <td>Status</td>
                                <td>Status Delivery</td>
                            </thead>
                            <tbody id="append_tbody_other">

                            </tbody>
                        </table>

                        <input id="ref_id" type="hidden" name="id" />
                        <input id="ref_name" type="hidden" name="name" />
                        <input id="ref_phone" type="hidden" name="phone" />
                        <input id="ref_age" type="hidden" name="age" />
                        <input id="ref_province" type="hidden" name="province" />
                        <input id="ref_city" type="hidden" name="city" />
                        <input id="refs_order" type="hidden" name="order_id" />

                        <button class="btn btn-primary"
                            type="submit"
                            id="btn-confirmUpdate">
                            Save
                        </button>
                    </div>
                </form>
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
@endsection

@section("script")
<script type="application/javascript">
let editForm = "";
let modalTitle = "";
let submissionId = parseInt("{{ $submission->id }}", 10);
let prizeOptionAll = `<option selected value="">Choose Prize</option>`;
let prizeOption = `<option selected value="">Choose Prize</option>`;

document.addEventListener("DOMContentLoaded", function () {
    editForm = document.getElementById("edit-form");
    modalTitle = document.getElementById("modal-title");

    fetch(
        '{{ route("fetchPrize") }}',
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

            document.getElementById("edit-city").innerHTML = arrCity[0] + arrCity[1];
            document.getElementById("edit-city").value = e.dataset.city;
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

function submit() {
    editForm.submit();
}

function loadDataPerRef(ref_id) {
    $.get('{{ route("fetchDetailPerReference", ['reference' => ""]) }}/' + ref_id )
    .done(function( result ) {
        // Empty data
        $('#append_tbody_hs').empty();
        $('#append_tbody_order').empty();
        $('#append_tbody_other').empty();

        if(result.length > 0){
            var data = JSON.parse(result);
            var data_refs = data['data_refs']; // Reference souvenir
            var data_ref = data['data_ref']; // Reference
            var data_hs = data['data_hs'];
            var data_order = data['data_order'];
            var data_prize = data['data_prize'];
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

            // Detail Order
            if (data_order != null) {
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
            }

            if (data_prize != null) {
                for (var p = 0; p < data_prize.length; p++) {
                    $('#append_tbody_other').append('\
                        <tr id="tr_detail_souvenir">\
                            <td>PRIZE</td>\
                            <td>\
                                <select {{ Auth::user()->id == 1 ? "disabled" : "" }} id="select_edit-prize_'+p+'" class="form-control" name="prize_id">'
                                + prizeOptionAll +
                                '</select>\
                            </td>\
                            <td>\
                                <select {{ Auth::user()->id == 1 ? "disabled" : "" }} id="select_edit-status-prize_'+p+'" class="form-control" name="status_prize">\
                                    <option value="">Choose Status</option>\
                                    <option value="pending">pending</option>\
                                    <option value="success">success</option>\
                                </select>\
                            </td>\
                            <td>\
                                <select id="select_edit-delivery-status-prize_'+p+'" class="form-control" name="delivery_status_prize">\
                                    <option value="">Choose Status Delivery</option>\
                                    <option value="undelivered">undelivered</option>\
                                    @if(Auth::user()->id == 1)
                                        <option value="delivered by CSO">delivered by CSO</option>\
                                    @endif
                                    <option value="delivered by Courier">delivered by Courier</option>\
                                    <option value="success">success</option>\
                                </select>\
                            </td>\
                        </tr>\
                    ');

                    $('#select_edit-prize_'+p).val(data_refs[p]['prize_id']);
                    $('#select_edit-status-prize_'+p).val(data_refs[p]['status_prize']);
                    $('#select_edit-delivery-status-prize_'+p).val(data_refs[p]['delivery_status_prize']);
                }
            }

            if (data_prize != null) {
                $('#ref_id').val(data_ref['id']);
                $('#ref_name').val(data_ref['name']);
                $('#ref_age').val(data_ref['age']);
                $('#ref_phone').val(data_ref['phone']);
                $('#ref_province').val(data_ref['province']);
                $('#ref_city').val(data_ref['city']);
                $('#refs_prize').val(data_refs[0]['prize_id']);
                $('#refs_order').val(data_refs[0]['order_id']);
            }

            $("#modal-per-reference").modal("show");
        }
    });
}

function clearModal() {
    const submissionId = '{{ $submission->id }}';
    const actionStore = '{{ route("store_reference_mgm") }}';
    document.getElementById("edit-form").setAttribute("action", actionStore);
    document.getElementById("edit-id").value = submissionId;
    document.getElementById("edit-name").value = "";
    document.getElementById("edit-age").value = "";
    document.getElementById("edit-phone").value = "";
    document.getElementById("edit-province").selectedIndex = 0;
    document.getElementById("edit-city").value = "";
    document.getElementById("edit-prize").selectedIndex = 0;
    document.getElementById("edit-order").value = "";
    document.getElementById("btn_choose_order").innerHTML = "Choose Order";
}

function clickEdit(e) {
    clearModal();
    const refSeq = e.dataset.edit.split("_")[1];

    const actionUpdate = '{{ route("update_reference_mgm") }}';
    const id = document.getElementById("edit-id_" + refSeq).value;
    const name = document.getElementById("name_" + refSeq).innerHTML.trim();
    const age = document.getElementById("age_" + refSeq).innerHTML.trim();
    const phone = document.getElementById("phone_" + refSeq).innerHTML.trim();
    const province = document.getElementById("province_" + refSeq).getAttribute("data-province");
    const city = document.getElementById("city_" + refSeq).getAttribute("data-city");
    const prize = document.getElementById("prize_" + refSeq).dataset.prize;

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
            return document.getElementById("order_" + refSeq).innerHTML.trim();
        } catch (error) {
            delete error;
            return "";
        }
    }

    document.getElementById("edit-form").setAttribute("action", actionUpdate);
    document.getElementById("edit-id").value = id;
    document.getElementById("edit-name").value = name;
    document.getElementById("edit-age").value = age;
    document.getElementById("edit-phone").value = phone;
    document.getElementById("edit-province").value = province;
    setCityAdd(document.getElementById("edit-province"));
    setTimeout(function () {
        document.getElementById("edit-city").value = city;
    }, 500);
    document.getElementById("edit-prize").value = prize;

    document.getElementById("edit-order").value = orderId();
    document.getElementById("btn_choose_order").innerHTML = orderCode() || "Choose Order";
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

    // KHUSUS UNTUK ORDER
    $("#choose-order").on('shown.bs.modal', function (event) {
        originButton = event.relatedTarget.dataset.originbutton;
        console.log(originButton);

        let submission_id = "{{ $submission->id }}";
        getOrderSubmission("", submission_id, originButton);
    });

    $("#choose-order").on('hidden.bs.modal', function () {
        $("#edit-reference").modal('show');
    });

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
                if (typeof hasil['errors'][key] === 'undefined') {

                } else {
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

function selectOrderNya(id, code) {
    $('#edit-order').val(id);
    $('#btn_choose_order').html("Order Code: " + code);
    $("#choose-order").modal('hide');
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
</script>
@endsection
