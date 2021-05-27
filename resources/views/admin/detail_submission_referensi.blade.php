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
    #intro {
        padding-top: 2em;
    }

    table {
        margin: 1em;
        font-size: 14px;
    }

    .table-responsive table {
        display: inline-table;
        table-layout:fixed;
        overflow:scroll;
    }

    .table-responsive table td {
        word-wrap:break-word;
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

    .center {
        text-align: center;
    }

    .right {
        text-align: right;
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
                        <td class="right">Submission Date</td>
                    </thead>
                    <tr>
                        <td class="right">
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
                    <?php $formLength = $references->count(); ?>
                    <input type="hidden"
                        id="form-length"
                        value="{{ $formLength }}" />
                    <?php for ($i = 0; $i < $formLength; $i++): ?>
                        <form method="POST" id="edit-form_{{ $i }}"></form>
                    <?php endfor; ?>
                    <table class="col-md-12" style="table-layout: auto;">
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
                                <td>Souvenir/Prize</td>
                                <td>Status</td>
                                <td>Deliv. Status</td>
                                {{-- <td>Prize</td>
                                <td>Status Prize</td>
                                <td>Deliv. Status Prize</td> --}}
                                <td>Edit</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($references as $key => $reference): ?>
                                <input type="hidden"
                                    id="edit-id_{{ $key }}"
                                    class="d-none"
                                    name="id"
                                    form="edit-form_{{ $key }}"
                                    value="{{ $reference->id }}" />
                                <tr>
                                    <td rowspan="2"
                                        id="name_{{ $key }}">
                                        {{ $reference->name }}
                                    </td>
                                    <td rowspan="2"
                                        class="center" id="age_{{ $key }}">
                                        {{ $reference->age }}
                                    </td>
                                    <td rowspan="2"
                                        class="center" id="phone_{{ $key }}">
                                        {{ $reference->phone }}
                                    </td>
                                    <td rowspan="2"
                                        id="province_{{ $key }}"
                                        data-province="{{ $reference->province_id }}">
                                        {{ $reference->province }}
                                    </td>
                                    <td rowspan="2"
                                        id="city_{{ $key }}"
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
                                    <td rowspan="2"
                                        class="center"
                                        id="link-hs_{{ $key }}"
                                        data-hs="{{ !empty($reference->link_hs) ? implode(", ", $link_hs) : "" }}"
                                        style="overflow-x: auto;">
                                        @if (!empty($reference->link_hs))
                                            @foreach ($link_hs as $value)
                                                @if (is_numeric($value))
                                                    <?php
                                                    $hs = HomeService::select("code")->where("id", $value)->first();
                                                    ?>
                                                    <a id="link-hs-href_{{ $key }}"
                                                        href="{{ route("homeServices_success", ["code" => $hs->code]) }}"
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
                                    <td rowspan="2"
                                        class="center"
                                        id="order_{{ $key }}"
                                        data-order="{{ $reference->order_id }}"
                                        style="overflow-x:auto;">
                                        <?php
                                        if (!empty($reference->order_id)) {
                                            $order = Order::select("id", "code")
                                                ->where("id", $reference->order_id)
                                                ->first();

                                            echo $order->code;
                                        }
                                        ?>
                                    </td>
                                    <td id="souvenir_{{ $key }}"
                                        data-permission="{{ $specialPermission }}"
                                        data-souvenir="{{ $reference->souvenir_id ?? -1 }}">
                                        {{ $reference->souvenir_name }}
                                    </td>
                                    <td class="center"
                                        id="status_souvenir_{{ $key }}"
                                        data-permission="{{ $specialPermission }}">
                                        {{ $reference->status_souvenir }}
                                    </td>
                                    <td class="center"
                                        id="delivery_status_souvenir_{{ $key }}"
                                        data-deliverysouvenir="{{ $reference->delivery_status_souvenir }}"
                                        data-permission="{{ $specialPermission }}">
                                        {{ $reference->delivery_status_souvenir }}
                                    </td>
                                    <td rowspan="2"
                                        class="center">
                                        <button class="btn"
                                            id="btn-edit-save_{{ $key }}"
                                            style="padding: 0;"
                                            data-edit="edit_{{ $key }}"
                                            onclick="clickEdit(this)"
                                            value="{{ $reference->id }}">
                                            <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="center"
                                        id="prize_{{ $key }}"
                                        data-prize="{{ $reference->prize_id }}"
                                        data-permission="{{ $specialPermission }}">
                                        <?php
                                        if (!empty($reference->prize_id)) {
                                            $prize = Prize::select("id", "name")
                                                ->where("id", $reference->prize_id)
                                                ->first();

                                            echo $prize->name;
                                        }
                                        ?>
                                    </td>
                                    <td class="center"
                                        id="status_prize_{{ $key }}"
                                        data-permission="{{ $specialPermission }}">
                                        {{ $reference->status_prize }}
                                    </td>
                                    <td class="center"
                                        id="delivery_status_prize_{{ $key }}"
                                        data-deliveryprize="{{ $reference->delivery_status_prize }}"
                                        data-permission="{{ $specialPermission }}">
                                        {{ $reference->delivery_status_prize }}
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-12 center">
                <button class="btn btn-gradient-primary mt-2"
                    data-toggle="modal"
                    data-target="#edit-reference">
                    Add Reference - Sehat Bersama WAKi/Keuntungan Biaya Iklan
                </button>
            </div>

            <div class="col-md-12 center" style="margin-top: 2em;">
               <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <h2 class="text-center share">Share Submission Form</h2>
                        </div>
                        <form class="forms-sample" method="GET" action="https://wa.me/">
                            <div class="form-group row justify-content-center">
                                <button type="submit" class="btn btn-gradient-primary mr-2" name="text" value="Terima Kasih telah mengikuti program *Sehat Bersama WAKi*. Berikut adalah tautan bukti formulir ( {{ route('refrence_sehat') }}?id={{ $submission->id }} )">Share Sehat bersama Waki</button>
                                <button type="submit" class="btn btn-gradient-primary mr-2" name="text" value="Terima Kasih telah mengikuti program *Keuntungan Biaya Iklan*. Berikut adalah tautan bukti formulir ( {{ route('refrence_untung') }}?id={{ $submission->id }} )">Share Program Biaya Iklan</button>
                            </div>
                        </form>
                    </div>
                </div>
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
                    action="<?php echo route("store_reference_referensi"); ?>">
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
                    <div class="form-group">
                        <label for="edit-souvenir">Souvenir</label>
                        <select class="form-control"
                            id="edit-souvenir"
                            name="souvenir_id">
                            <option selected disabled>
                                Pilih Souvenir
                            </option>
                            <?php foreach ($souvenirs as $souvenir): ?>
                                <option value="<?php echo $souvenir->id; ?>">
                                    <?php echo $souvenir->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-prize">Prize</label>
                        <select class="form-control"
                            id="edit-prize"
                            name="prize_id">
                            <option selected disabled>
                                Choose Prize
                            </option>
                            <?php foreach ($prizes as $prize): ?>
                                <option value="<?php echo $prize->id; ?>">
                                    <?php echo $prize->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-link-hs">Home Service</label>
                        <div id="link-hs-container"></div>
                        <input type="hidden"
                            id="edit-link-hs"
                            name="link_hs"
                            value="" />
                        <br>
                        <button class="btn btn-gradient-info"
                            type="button"
                            id="btn_choose_hs"
                            data-originbutton="btn_choose_hs"
                            data-toggle="modal"
                            data-target="#choose-hs">
                            Choose Home Service
                        </button>
                    </div>
                    <div class="form-group">
                        <label for="edit-link-hs">Order</label>
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
@endsection

@section('script')
<script type="application/javascript">
let provinceOption = "";
let souvenirOption = `<option disabled selected value="">Pilih souvenir</option>`;
let prizeOption = `<option selected value="">Choose Prize</option>`;

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

        result.forEach(function (currentValue) {
            souvenirOption += `<option value="${currentValue["id"]}">`
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
            prizeOption += `<option value="${currentValue["id"]}">`
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

function clickEdit(e) {
    const refSeq = e.dataset.edit.split("_")[1];
    const id = document.getElementById("edit-id_" + refSeq).value;
    const name = document.getElementById("name_" + refSeq).innerHTML.trim();
    const age = document.getElementById("age_" + refSeq).innerHTML.trim();
    const phone = document.getElementById("phone_" + refSeq).innerHTML.trim();
    const province = document.getElementById("province_" + refSeq).getAttribute("data-province");
    const city = document.getElementById("city_" + refSeq).getAttribute("data-city");
    const souvenir = document.getElementById("souvenir_" + refSeq).getAttribute("data-souvenir");

    function linkHS() {
        try {
            return document.getElementById("link-hs-href_" + refSeq).getAttribute("href");
        } catch (error) {
            return "";
        }
    };

    const status = document.getElementById("status_souvenir_" + refSeq).innerHTML.trim();

    function deliverySouvenir () {
        try {
            return document.getElementById("delivery_status_souvenir_" + refSeq).dataset.deliverysouvenir;
        } catch (error) {
            return "";
        }
    };

    function orderId() {
        try {
            return document.getElementById("order_" + refSeq).dataset.order;
        } catch (error) {
            return "";
        }
    }

    function orderCode() {
        try {
            return document.getElementById("order_" + refSeq).innerHTML.trim();
        } catch (error) {
            return "";
        }
    }

    const prize = document.getElementById("prize_" + refSeq).dataset.prize;

    function statusPrize() {
        try {
            document.getElementById("status_prize_" + refSeq).innerHTML.trim();
        } catch (error) {
            return "";
        }
    }

    function deliveryPrize() {
        try {
            return document.getElementById("delivery_status_prize_" + refSeq).dataset.deliveryprize;
        } catch (error) {
            return "";
        }
    }

    const permission = document.getElementById("status_souvenir_" + refSeq).dataset.permission;

    const FORM_ATTR = `form="edit-form_${refSeq}" `;
    const INPUT_CLASS = `class="form-control" `;
    document.getElementById("name_" + refSeq).innerHTML = `<input type="text" `
        + `id="edit-name_${refSeq}" `
        + INPUT_CLASS
        + FORM_ATTR
        + `name="name" `
        + `value="${name}" `
        + `placeholder="Name" `
        + `required />`;

    document.getElementById("age_" + refSeq).innerHTML = `<input type="number" `
        + `id="edit-age_${refSeq}" `
        + INPUT_CLASS
        + FORM_ATTR
        + `name="age" `
        + `value="${age}" `
        + `required />`;

    document.getElementById("phone_" + refSeq).innerHTML = `<input type="number" `
        + `id="edit-phone_${refSeq}" `
        + INPUT_CLASS
        + FORM_ATTR
        + `name="phone" `
        + `value="${phone}" `
        + `required />`;

    document.getElementById("province_" + refSeq).innerHTML = `<select `
        + `id="edit-province_${refSeq}" `
        + INPUT_CLASS
        + FORM_ATTR
        + `name="province" `
        + `onchange="setCity(this)"`
        + `required>`
        + provinceOption
        + `</select>`;
    document.getElementById("edit-province_" + refSeq).value = province;

    document.getElementById("city_" + refSeq).innerHTML = `<select `
        + `id="edit-city_${refSeq}" `
        + INPUT_CLASS
        + FORM_ATTR
        + `name="city" `
        + `required>`
        + `</select>`;

    setCity(document.getElementById("edit-province_" + refSeq));

    const hsInput = document.createElement("input");
    hsInput.type = "hidden";
    hsInput.id = "edit-link_hs_" + refSeq;
    hsInput.name = "link_hs";
    hsInput.value = document.getElementById("link-hs_" + refSeq).dataset.hs;
    const hsButton = document.createElement("button");
    hsButton.type = "button";
    hsButton.className = "btn btn-gradient-info btn-sm";
    hsButton.id = "btn-choose-hs-edit_" + refSeq;
    hsButton.innerHTML = "Add Home Service";
    document.getElementById("link-hs_" + refSeq).innerHTML = "";
    document.getElementById("link-hs_" + refSeq).appendChild(hsInput);
    document.getElementById("edit-link_hs_" + refSeq).setAttribute("form", `edit-form_${refSeq}`);
    document.getElementById("link-hs_" + refSeq).appendChild(hsButton);
    document.getElementById("btn-choose-hs-edit_" + refSeq).setAttribute("data-toggle", "modal");
    document.getElementById("btn-choose-hs-edit_" + refSeq).setAttribute("data-target", "#choose-hs");
    document.getElementById("btn-choose-hs-edit_" + refSeq).setAttribute("data-originbutton", "btn-choose-hs-edit_" + refSeq);

    const orderInput = document.createElement("input");
    orderInput.type = "hidden";
    orderInput.id = "order_id_" + refSeq;
    orderInput.name = "order_id";
    orderInput.value = orderId();
    const orderButton = document.createElement("button");
    orderButton.type = "button";
    orderButton.className = "btn btn-gradient-info btn-sm";
    orderButton.id = "btn-choose-order-edit_" + refSeq;
    orderButton.innerHTML = orderCode() || "Choose Order";
    document.getElementById("order_" + refSeq).innerHTML = "";
    document.getElementById("order_" + refSeq).appendChild(orderInput);
    document.getElementById("order_id_" + refSeq).setAttribute("form", `edit-form_${refSeq}`);
    document.getElementById("order_" + refSeq).appendChild(orderButton);
    document.getElementById("btn-choose-order-edit_" + refSeq).setAttribute("data-originbutton", "btn-choose-order-edit_" + refSeq);
    document.getElementById("btn-choose-order-edit_" + refSeq).setAttribute("data-toggle", "modal");
    document.getElementById("btn-choose-order-edit_" + refSeq).setAttribute("data-target", "#choose-order");

    const prizeSelect = document.createElement("select");
    prizeSelect.className = "form-control";
    prizeSelect.id = `edit-prize_${refSeq}`;
    prizeSelect.name = "prize_id";
    prizeSelect.innerHTML = prizeOption;
    document.getElementById("prize_" + refSeq).innerHTML = "";
    document.getElementById("prize_" + refSeq).appendChild(prizeSelect);
    document.getElementById("edit-prize_" + refSeq).setAttribute("form", `edit-form_${refSeq}`);
    document.getElementById("edit-prize_" + refSeq).value = prize;


    if (permission) {
        document.getElementById("souvenir_" + refSeq).innerHTML = `<select `
            + `id="edit-souvenir_${refSeq}" `
            + INPUT_CLASS
            + FORM_ATTR
            + `name="souvenir_id" `
            + `required>`
            + souvenirOption
            + `</select>`;
        document.getElementById("edit-souvenir_" + refSeq).value = souvenir;

        document.getElementById("status_souvenir_" + refSeq).innerHTML = `<select `
            + `id="edit-status_${refSeq}" `
            + INPUT_CLASS
            + FORM_ATTR
            + `name="status" `
            + `required>`
            + `<option value="pending">pending</option>`
            + `<option value="success">success</option>`
            + `</select>`;
        document.getElementById("edit-status_" + refSeq).value = status || "pending";

        const deliveryStatusSouvenirSelect = document.createElement("select");
        deliveryStatusSouvenirSelect.className = "form-control";
        deliveryStatusSouvenirSelect.id = `edit-delivery-status-souvenir_${refSeq}`;
        deliveryStatusSouvenirSelect.name = "delivery_status_souvenir";
        deliveryStatusSouvenirSelect.innerHTML = '<option selected value="">Choose Souvenir Delivery Status</option>'
            + '<option value="undelivered">Undelivered</option>'
            + '<option value="delivered">Delivered</option>';
        document.getElementById("delivery_status_souvenir_" + refSeq).innerHTML = "";
        document.getElementById("delivery_status_souvenir_" + refSeq).appendChild(deliveryStatusSouvenirSelect);
        document.getElementById("edit-delivery-status-souvenir_" + refSeq).setAttribute("form", `edit-form_${refSeq}`);
        document.getElementById("edit-delivery-status-souvenir_" + refSeq).value = deliverySouvenir();

        const statusPrizeSelect = document.createElement("select");
        statusPrizeSelect.className = "form-control";
        statusPrizeSelect.id = `edit-status-prize_${refSeq}`;
        statusPrizeSelect.name = "status_prize";
        statusPrizeSelect.innerHTML = '<option selected value="">Choose Prize Status</option>'
            + '<option value="pending">Pending</option>'
            + '<option value="success">Success</option>';
        document.getElementById("status_prize_" + refSeq).innerHTML = "";
        document.getElementById("status_prize_" + refSeq).appendChild(statusPrizeSelect);
        document.getElementById("edit-status-prize_" + refSeq).setAttribute("form", `edit-form_${refSeq}`);
        document.getElementById("edit-status-prize_" + refSeq).value = statusPrize();

        const deliveryStatusPrizeSelect = document.createElement("select");
        deliveryStatusPrizeSelect.className = "form-control";
        deliveryStatusPrizeSelect.id = `edit-delivery-status-prize_${refSeq}`;
        deliveryStatusPrizeSelect.name = "delivery_status_prize";
        deliveryStatusPrizeSelect.innerHTML = '<option selected value="">Choose Prize Delivery Status</option>'
            + '<option value="undelivered">Undelivered</option>'
            + '<option value="delivered">Delivered</option>';
        document.getElementById("delivery_status_prize_" + refSeq).innerHTML = "";
        document.getElementById("delivery_status_prize_" + refSeq).appendChild(deliveryStatusPrizeSelect);
        document.getElementById("edit-delivery-status-prize_" + refSeq).setAttribute("form", `edit-form_${refSeq}`);
        document.getElementById("edit-delivery-status-prize_" + refSeq).value = deliveryPrize();
    }

    document.getElementById("btn-edit-save_" + refSeq).setAttribute("onclick", "submitEdit(this)");
    document.getElementById("btn-edit-save_" + refSeq).innerHTML = `<i class="mdi mdi-content-save" style="font-size: 24px; color: blue;"></i>`;
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
        location.reload();
    }).catch(function (error){
        console.error(error);
    });
}

// NEW System
$(document).ready(function(){
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

    // $("#choose-hs").on('hidden.bs.modal', function () {
    //     $("#edit-reference").modal('show');
    // });

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
        getOrderSubmission(filter, submission_id);
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
});

function selectHsNya(id, code){
    let linkHsArray = [];

    if (document.getElementById("edit-link-hs").value) {
        linkHsArray = (document.getElementById("edit-link-hs").value).split(", ");
    } else {
        linkHsArray = [];
    }

    linkHsArray.push(id);
    document.getElementById("edit-link-hs").value = linkHsArray.join(", ");

    const hsTextInput = document.createElement("input");
    hsTextInput.type = "text";
    hsTextInput.className = "form-control";
    hsTextInput.disabled = true;
    hsTextInput.readOnly = true;
    hsTextInput.value = code;
    document.getElementById("link-hs-container").appendChild(hsTextInput);

    $("#choose-hs").modal('hide');
}

function selectOrderNya(id, code){
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
</script>
@endsection
