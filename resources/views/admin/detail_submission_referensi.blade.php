<?php

use App\Promo;

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
                    <table class="col-md-12">
                        <thead>
                            <td colspan="9">Reference</td>
                        </thead>
                        <thead style="background-color: #80808012 !important;">
                            <tr>
                                <td>Name</td>
                                <td>Age</td>
                                <td>Phone</td>
                                <td>Province</td>
                                <td>City</td>
                                <td>Souvenir</td>
                                <td>Link HS</td>
                                <td>Status</td>
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
                                <td id="name_{{ $key }}">
                                    {{ $reference->name }}
                                </td>
                                <td class="center" id="age_{{ $key }}">
                                    {{ $reference->age }}
                                </td>
                                <td class="center" id="phone_{{ $key }}">
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
                                <td id="souvenir_{{ $key }}"
                                    data-permission="{{ $specialPermission }}"
                                    data-souvenir="{{ $reference->souvenir_id ?? -1 }}">
                                    {{ $reference->souvenir_name }}
                                </td>
                                <td class="center"
                                    id="link-hs_{{ $key }}"
                                    data-permission="{{ $specialPermission }}">
                                    <?php if (!empty($reference->link_hs)): ?>
                                        <a id="link-hs-href_{{ $key }}"
                                            href="{{ $reference->link_hs }}"
                                            target="_blank">
                                            <i class="mdi mdi-home" style="font-size: 24px; color: #2daaff;"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td class="center"
                                    id="status_{{ $key }}"
                                    data-permission="{{ $specialPermission }}">
                                    {{ $reference->status }}
                                </td>
                                <td class="center">
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
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-12 center">
                <button class="btn btn-gradient-primary mt-2"
                    data-toggle="modal"
                    data-target="#edit-reference">
                    Add Reference - Sehat Bersama WAKi
                </button>
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
                <form id="edit-form"
                    method="POST"
                    enctype="multipart/form-data"
                    action="<?php echo route("update_reference"); ?>">
                    @csrf
                    <input type="hidden" id="edit-id" name="id" value="" />
                    <input type="hidden" id="url" name="url" value="{{ url()->full() }}" />
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
                        <label for="edit-link-hs">Link Home Service</label>
                        <input type="url"
                            class="form-control"
                            id="edit-link-hs"
                            name="link_hs"
                            pattern="https://.*"
                            maxlength="191"
                            value=""
                            placeholder="Link Home Service" />
                    </div>
                    <div class="form-group">
                        <label for="edit-status">Status</label>
                        <select class="form-control"
                            id="edit-status"
                            name="status">
                            <option value="pending">pending</option>
                            <option value="success">success</option>
                        </select>
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
@endsection

@section('script')
<script type="application/javascript">
let provinceOption = "";
let souvenirOption = `<option disabled selected value="">Pilih souvenir</option>`;

document.addEventListener('DOMContentLoaded', function () {
    const URL_PROVINCE = '<?php echo route("fetchProvince"); ?>';
    const URL_SOUVENIR = '<?php echo route("fetchSouvenir"); ?>';

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
    const linkHS = function () {
        try {
            return document.getElementById("link-hs-href_" + refSeq).getAttribute("href");
        } catch (error) {
            return "";
        }
    };
    const status = document.getElementById("status_" + refSeq).innerHTML.trim();
    const permission = document.getElementById("status_" + refSeq).dataset.permission;

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

        document.getElementById("link-hs_" + refSeq).innerHTML = `<input type="url" `
            + `id="edit-link-hs_${refSeq}" `
            + INPUT_CLASS
            + FORM_ATTR
            + `name="link_hs" `
            + `pattern="https://.*" `
            + `maxlength="191" `
            + `value="${linkHS()}" `
            + `placeholder="Link HS" />`;

        document.getElementById("status_" + refSeq).innerHTML = `<select `
            + `id="edit-status_${refSeq}" `
            + INPUT_CLASS
            + FORM_ATTR
            + `name="status" `
            + `required>`
            + `<option value="pending">pending</option>`
            + `<option value="success">success</option>`
            + `</select>`;
        document.getElementById("edit-status_" + refSeq).value = status || "pending";
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

    if (document.getElementById("status_" + refSeq).dataset.permission) {
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
        const data = response.data;
        const dataSouvenir = response.dataSouvenir;

        document.getElementById("name_" + refSeq).innerHTML = data.name;
        document.getElementById("age_" + refSeq).innerHTML = data.age;
        document.getElementById("phone_" + refSeq).innerHTML = data.phone;
        document.getElementById("province_" + refSeq).setAttribute("data-province", data.province);
        document.getElementById("province_" + refSeq).innerHTML = response.province;
        document.getElementById("city_" + refSeq).setAttribute("data-city", data.city);
        document.getElementById("city_" + refSeq).innerHTML = response.city;
        document.getElementById("souvenir_" + refSeq).setAttribute("data-souvenir", dataSouvenir.souvenir_id);
        document.getElementById("souvenir_" + refSeq).innerHTML = response.souvenir;

        if (dataSouvenir.link_hs) {
            document.getElementById("link-hs_" + refSeq).innerHTML = `<a href="${dataSouvenir.link_hs}" id="link-hs-href_${refSeq}" target="blank">`
                + `<i class="mdi mdi-home" style="font-size: 24px; color: #2daaff;"></i>`
                + `</a>`;
        } else {
            document.getElementById("link-hs_" + refSeq).innerHTML = "";
        }

        document.getElementById("status_" + refSeq).innerHTML = dataSouvenir.status;

        document.getElementById("btn-edit-save_" + refSeq).setAttribute("onclick", "clickEdit(this)");
    document.getElementById("btn-edit-save_" + refSeq).innerHTML = `<i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>`;
    }).catch(function (error){
        console.error(error);
    });
}
</script>
@endsection
