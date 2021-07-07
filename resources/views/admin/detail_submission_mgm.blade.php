<?php

use App\Promo;

$menu_item_page = "submission";
$menu_item_second = "detail_submission_form";
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
                            <td colspan="11">Reference</td>
                        </thead>
                        <thead style="background-color: #80808012 !important;">
                            <tr>
                                <td>Name</td>
                                <td>Age</td>
                                <td>Phone</td>
                                <td>Province</td>
                                <td>City</td>
                                <td>Promo 1</td>
                                <td>Qty 1</td>
                                <td>Promo 2</td>
                                <td>Qty 2</td>
                                <td>Image</td>
                                <td>Edit</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($references as $key => $reference): ?>
                                <input type="hidden"
                                    class="d-none"
                                    id="id_{{ $key }}"
                                    value="{{ $reference->id }}" />
                                <tr>
                                    <td id="name_{{ $key }}">
                                        {{ $reference->name }}
                                    </td>
                                    <td class="center" id="age_{{ $key }}">
                                        {{ $reference->age }}
                                    </td>
                                    <td class="right" id="phone_{{ $key }}">
                                        {{ $reference->phone }}
                                    </td>
                                    <td id="province_{{ $key }}"
                                        data-province="{{ $reference->province_id }}"
                                        data-city="{{ $reference->city_id }}">
                                        {{ $reference->province }}
                                    </td>
                                    <td id="city_{{ $key }}"
                                        data-city="{{ $reference->city_id }}">
                                        {{ $reference->city }}
                                    </td>
                                    <td id="promo_1_{{ $key }}"
                                        <?php
                                        echo 'data-promo1="';
                                        if ($reference->promo_1 !== null) {
                                            echo $reference->promo_1 . '"';
                                        } else {
                                            echo 'other"';
                                        }
                                        ?>
                                        >
                                        <?php
                                        if ($reference->promo_1 !== null) {
                                            $queryPromo = Promo::find($reference->promo_1);
                                            $promo = implode(", ", $queryPromo->productCode());
                                            echo $queryPromo->code . " - (" . $promo . ")";
                                        } else {
                                            echo $reference->other_1;
                                        }
                                        ?>
                                    </td>
                                    <td id="qty_1_{{ $key }}" class="right">
                                        {{ $reference->qty_1 }}
                                    </td>
                                    <td id="promo_2_{{ $key }}"
                                        <?php
                                        echo 'data-promo2="';
                                        if (
                                            $reference->promo_2 !== null
                                            && $reference->other_2 === null
                                        ) {
                                            echo $reference->promo_2;
                                        } elseif (
                                            $reference->promo_2 === null
                                            && $reference->other_2 !== null
                                        ) {
                                            echo 'other';
                                        }
                                        echo '"';
                                        ?>
                                        >
                                        <?php
                                        if ($reference->promo_2 !== null) {
                                            $queryPromo = Promo::find($reference->promo_2);
                                            $promo = implode(", ", $queryPromo->productCode());
                                            echo $queryPromo->code . " - (" . $promo . ")";
                                        }

                                        if (
                                            $reference->promo_2 === null
                                            && $reference->other_2 !== null
                                        ) {
                                            echo $reference->other_2;
                                        }
                                        ?>
                                    </td>
                                    <td id="qty_2_{{ $key }}" class="right">
                                        <?php
                                        if ($reference->qty_2 !== null) {
                                            echo $reference->qty_2;
                                        }
                                        ?>
                                    </td>
                                    <td class="center"
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
                                    </td>
                                    <td class="center">
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
                    data-target="#edit-reference"
                    onclick="clickAdd()">
                    Add Reference - MGM
                </button>
            </div>

            {{-- <div class="col-md-12 text-center"
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
                            value="Terima Kasih telah mengikuti program *Sehat Bersama WAKi*. Berikut adalah tautan bukti formulir ( {{ route('refrence_sehat') }}?id={{ $submission->id }} )">
                            Share Member Get Member
                        </button>
                        {{-- <button type="submit"
                            class="btn btn-gradient-primary mr-2 my-2"
                            name="text"
                            value="Terima Kasih telah mengikuti program *Keuntungan Biaya Iklan*. Berikut adalah tautan bukti formulir ( {{ route('refrence_untung') }}?id={{ $submission->id }} )">
                            Share Program Biaya Iklan
                        </button> --}}
                    </div>
                </form>
            </div> --}}

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
                    @for ($i = 1; $i < 3; $i++)
                        <div class="form-group">
                            <label for="promo-{{ $i }}">
                                Promo {{ $i }}
                            </label>
                            <select class="form-control"
                                id="edit-promo-{{ $i }}"
                                name="promo_{{ $i }}"
                                onchange="selectOther(this)"
                                {{ $i > 1 ? "" : "required" }}>
                                <option selected
                                    disabled
                                    value=""
                                    {{ $i > 1 ? "" : "hidden" }}>
                                    Choose Promo {{ $i > 1 ? "(optional)" : ""}}
                                </option>
                                <?php foreach ($promos as $key => $promo): ?>
                                    <option value="<?php echo $promo["id"]; ?>">
                                        <?php
                                        echo $promo->code
                                            . " - ("
                                            . implode(", ", $promo->productCode())
                                            . ") - Rp. "
                                            . number_format($promo->price);
                                        ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="other">OTHER</option>
                            </select>
                            <div class="form-group d-none"
                                id="other-{{ $i }}">
                                <input type="text"
                                    class="form-control"
                                    id="edit-other-{{ $i }}"
                                    name="other_{{ $i }}"
                                    placeholder="Product Name" />
                                <div class="validation"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="qty-{{ $i }}">
                                Quantity {{ $i }}
                            </label>
                            <input type="number"
                                id="edit-qty-{{ $i }}"
                                name="qty_{{ $i }}"
                                class="form-control"
                                max="10"
                                min="1"
                                {{ $i > 1 ? "" : "required" }} />
                        </div>
                    @endfor
                    @for ($i = 1; $i <= 2; $i++)
                        <div class="form-group">
                            <label>
                                Proof DO (image) - {{ $i }}
                            </label>
                            <br>
                            <img id="edit-image-{{ $i }}"
                                class="img-fluid img-thumbnail"
                                src=""
                                style="max-height: 200px"
                                alt="Proof DO {{ $i }}" />
                            <input type="file"
                                id="proof-image-{{ $i }}"
                                name="image_{{ $i }}"
                                class="proof-image form-control"
                                accept=".jpg, .jpeg, .png" />
                        </div>
                    @endfor
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
@endsection

@section("script")
<script type="application/javascript">
let editForm = "";
let modalTitle = "";
let submissionId = parseInt("<?php echo $submission->id; ?>", 10);

document.addEventListener("DOMContentLoaded", function () {
    editForm = document.getElementById("edit-form");
    modalTitle = document.getElementById("modal-title");
    image1 = document.getElementById("image-1");
    image2 = document.getElementById("image-2");
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

function idInput(command) {
    const idInput = document.getElementById("edit-id");
    const submissionIdInput = document.getElementById("submission-id");

    if (!idInput && command === "add") {
        const input = document.createElement("input");
        input.id = "edit-id";
        input.type = "hidden";
        input.name = "id";

        editForm.prepend(input);

        if (submissionIdInput) {
            submissionIdInput.remove();
        }
    } else if (idInput && command === "remove") {
        idInput.remove();

        if (!submissionIdInput) {
            const submissionIdHiddenInput = document.createElement("input");
            submissionIdHiddenInput.id = "submission-id";
            submissionIdHiddenInput.type = "hidden";
            submissionIdHiddenInput.name = "submission_id";
            submissionIdHiddenInput.value = submissionId;

            editForm.prepend(submissionIdHiddenInput);
        }
    }
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

function selectOther(e) {
    const getNumber = e.id.split("-")[2];
    if (e.value === "other") {
        if (document.getElementById("other-" + getNumber).classList.contains("d-none")) {
            document.getElementById("other-" + getNumber).classList.remove("d-none");
        }
    } else {
        if (!document.getElementById("other-" + getNumber).classList.contains("d-none")) {
            document.getElementById("other-" + getNumber).classList.add("d-none");
            document.getElementById("other-" + getNumber).value = "";
        }
    }
}

function clickAdd() {
    idInput("remove");
    modalTitle.innerHTML = "Add Reference";

    for (let i = 1; i <= 2; i++) {
        if (!document.getElementById("edit-image-" + i).classList.contains("d-none")) {
            document.getElementById("edit-image-" + i).classList.add("d-none");
        }
    }

    editForm.setAttribute("action", '<?php echo route("store_reference_mgm"); ?>');
}

function submit() {
    editForm.submit();
}

function clickEdit(e) {
    const getRefSeq = e.dataset.edit.split("_")[1];
    const id = document.getElementById("id_" + getRefSeq).value;
    const name = document.getElementById("name_" + getRefSeq).innerHTML.trim();
    const age = document.getElementById("age_" + getRefSeq).innerHTML.trim();
    const phone = document.getElementById("phone_" + getRefSeq).innerHTML.trim();
    const province = document.getElementById("province_" + getRefSeq).getAttribute("data-province");
    const city = document.getElementById("province_" + getRefSeq).getAttribute("data-city");
    let promo1 = document.getElementById("promo_1_" + getRefSeq).dataset.promo1;
    let promo2 = document.getElementById("promo_2_" + getRefSeq).dataset.promo2;
    const qty1 = document.getElementById("qty_1_" + getRefSeq).innerHTML.trim();
    const qty2 = document.getElementById("qty_2_" + getRefSeq).innerHTML.trim();
    const image1 = document.getElementById("image_" + getRefSeq).dataset.image1;
    const image2 = document.getElementById("image_" + getRefSeq).dataset.image2;

    for (let i = 1; i <= 2; i++) {
        if (document.getElementById("edit-image-" + i).classList.contains("d-none")) {
            document.getElementById("edit-image-" + i).classList.remove("d-none");
        }
    }

    idInput("add");
    modalTitle.innerHTML = "Edit Reference";
    editForm.setAttribute("action", '<?php echo route("update_reference_mgm"); ?>');

    document.getElementById("edit-id").value = id;
    document.getElementById("edit-name").value = name;
    document.getElementById("edit-age").value = age;
    document.getElementById("edit-phone").value = phone;
    document.getElementById("edit-province").value = province;
    document.getElementById("edit-province").setAttribute("data-city", city);
    setCity(document.getElementById("edit-province"));

    if (promo1 === "other") {
        promo1 = document.getElementById("promo_1_" + getRefSeq).innerHTML.trim();
        document.getElementById("edit-other-1").value = promo1;
    }

    if (promo2 === "other") {
        promo2 = document.getElementById("promo_2_" + getRefSeq).innerHTML.trim();
        document.getElementById("edit-other-2").value = promo2;
    }

    promo1input = document.getElementById("edit-promo-1");
    promo2input = document.getElementById("edit-promo-2");
    if (Number.isInteger(parseInt(promo1, 10))) {
        promo1input.value = promo1;
    } else {
        if (promo1 === "other") {
            promo1input.value = "other";
        }
    }
    selectOther(promo1input);

    if (Number.isInteger(parseInt(promo2, 10))) {
        promo2input.value = promo2;
    } else {
        if (promo2 === "other") {
            promo2input.value = "other";
        }
    }
    selectOther(promo2input);

    document.getElementById("edit-qty-1").value = qty1;
    document.getElementById("edit-qty-2").value = qty2;

    document.getElementById("edit-image-1").setAttribute("src", image1);
    if (image2) {
        document.getElementById("edit-image-2").setAttribute("src", image2);
    } else {
        document.getElementById("edit-image-2").classList.add("d-none");
    }
}
</script>
@endsection
