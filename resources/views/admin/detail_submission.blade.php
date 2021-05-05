<?php

use App\Product;
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

    .table-responsive table{
        display: inline-table;
        table-layout:fixed;
        overflow:scroll;
    }

    .table-responsive table td{
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
@if ($deliveryOrder['code'] !== null)
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
                            {{ date("d/m/Y H:i:s", strtotime($submission['created_at'])) }}
                        </td>
                    </tr>
                </table>

                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Customer Data</td>
                    </thead>
                    <tr>
                        <td>Member Code: </td>
                        <td>{{ $submission['no_member'] }}</td>
                    </tr>
                    <tr>
                        <td>Name: </td>
                        <td>{{ $submission['name'] }}</td>
                    </tr>
                    <tr>
                        <td>Phone Number: </td>
                        <td>{{ $submission['phone'] }}</td>
                    </tr>
                    <tr>
                        <td>Address: </td>
                        <td>{{ $submission['address'] }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <?php
                            echo $submission['district'][0]['province'];
                            echo ", ";
                            echo $submission['district'][0]['kota_kab'];
                            echo ", ";
                            echo $submission['district'][0]['subdistrict_name'];
                            ?>
                        </td>
                    </tr>
                </table>

                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Detail Order</td>
                    </thead>
                    <thead style="background-color: #80808012 !important">
                        <td>Promo Name</td>
                        <td>Quantity</td>
                    </thead>

                    @foreach (json_decode($deliveryOrder['arr_product'], true) as $promo)
                        <tr>
                            @if (is_numeric($promo['id']) && $promo['id'] < 8)
                                <td>
                                    <?php
                                    $getPromo = Promo::select("code", "product", "price")
                                    ->where("id", $promo["id"])
                                    ->first();

                                    $productPromo = json_decode($getPromo["product"]);
                                    $arrayProductId = [];

                                    foreach ($productPromo as $pp) {
                                        $arrayProductId[] = $pp->id;
                                    }

                                    $getProduct = Product::select("code")
                                    ->whereIn(
                                        "id",
                                        $arrayProductId
                                    )
                                    ->get();

                                    $arrayProductCode = [];

                                    foreach ($getProduct as $product) {
                                        $arrayProductCode[] = $product->code;
                                    }

                                    $productCode = implode(", ", $arrayProductCode);
                                    echo $getPromo["code"]
                                        . " (" . $productCode . ") "
                                        . "Rp. " . number_format((int) $getPromo["price"], 0, null, ",");
                                    ?>
                                </td>
                            @else
                                <td>{{ $promo['id'] }}</td>
                            @endif

                            <td>{{ $promo['qty'] }}</td>
                        </tr>
                    @endforeach
                </table>

                <table class="col-md-12">
                    <thead>
                        <td>Sales Branch</td>
                        <td>Sales Code</td>
                    </thead>
                    <tr>
                        <td style="width:50%; text-align: center">
                            <?php
                            echo $deliveryOrder->branch['code'];
                            echo " - ";
                            echo $deliveryOrder->branch['name'];
                            ?>
                        </td>
                        <td style="width:50%; text-align: center">
                            {{ $deliveryOrder->cso['code'] }}
                        </td>
                    </tr>
                </table>

                <div class="table-responsive">
                    <?php for ($i = 0; $i < 10; $i++): ?>
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
                                        {{ $reference->province_name }}
                                    </td>
                                    <td id="city_{{ $key }}"
                                        data-city="{{ $reference->city_id }}">
                                        {{ $reference->city_name }}
                                    </td>
                                    <td id="souvenir_{{ $key }}"
                                        data-souvenir="{{ $reference->souvenir_id ?? -1 }}">
                                        {{ $reference->souvenir_name }}
                                    </td>
                                    <td class="center" id="link-hs_{{ $key }}">
                                        <?php if (!empty($reference->link_hs)): ?>
                                            <a id="link-hs-href_{{ $key }}"
                                                href="{{ $reference->link_hs }}"
                                                target="_blank">
                                                <i class="mdi mdi-home" style="font-size: 24px; color: #2daaff;"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="center" id="status_{{ $key }}">
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

                <div class="table-responsive">
                    <table class="col-md-12">
                        <thead>
                            <td colspan="9">Reference</td>
                        </thead>
                        <thead style="background-color: #80808012 !important">
                            <tr>
                                <td>Name</td>
                                <td>Age</td>
                                <td>Phone</td>
                                <td>Province</td>
                                <td>City</td>
                                <td>Souvenir</td>
                                <td>Link HS</td>
                                <td>Status</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="edit-input" id="name">Amel</td>
                                <td class="center edit-input" id="age">21</td>
                                <td class="center edit-input" id="phone">0811111111</td>
                                <td id="province">
                                    <select class="form-control"
                                        id="edit-province"
                                        name="province">
                                        <option value="Maluku">Maluku</option>
                                        <option value="Jawa Barat">Jawa Barat</option>
                                    </select>
                                </td>
                                <td id="city">
                                    <select class="form-control"
                                        id="edit-city"
                                        name="city">
                                        <option value="Kabupaten Maluku Barat Daya">Kabupaten Maluku Barat Daya</option>
                                        <option value="Kota Cirebon">Kota Cirebon</option>
                                    </select>
                                </td>
                                <td class="souvenir">
                                    <select class="form-control"
                                        id="edit-souvenir"
                                        name="souvenir">
                                        <option value="success">Jeep Hardtop 4x4</option>
                                        <option value="pending">Driver & BBM & Parkir</option>
                                        <option value="success">Tiket Masuk Bromo</option>
                                        <option value="success">Air Mineral</option>
                                    </select>
                                </td>
                                <td class="edit-input">https://dzsdfdfcsadfcsdfcsdsdvszdv</td>
                                <td id="status">
                                    <select class="form-control"
                                        id="edit-status"
                                        name="status">
                                        <option value="pending">pending</option>
                                        <option value="success">success</option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <a class="save" title="Save" data-toggle="tooltip">
                                        <i class="mdi mdi-content-save" style="font-size: 24px; color: blue;"></i>
                                    </a>
                                    <a class="edit" title="Edit" data-toggle="tooltip">
                                        <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row justify-content-center" style="margin-top: 2em;">
                <h2>SUBMISSION HISTORY LOG</h2>
            </div>
            <div class="row justify-content-center">
                <table class="col-md-12">
                    <thead>
                        <td>No.</td>
                        <td>Action</td>
                        <td>User</td>
                        <td>Change</td>
                        <td>Time</td>
                    </thead>
                    @if ($historySubmission !== null)
                        @foreach ($historySubmission as $key => $historySubmission)
                            <tr>
                                <td class="center">{{ $key + 1 }}</td>
                                <td class="center">
                                    {{ $historySubmission->method }}
                                </td>
                                <td class="center">
                                    {{ $historySubmission->name }}
                                </td>
                                <?php
                                $dataChange = json_decode($historySubmission->meta, true);
                                ?>
                                <td>
                                    @foreach ($dataChange['dataChange'] as $key => $value)
                                        <b>{{ $key }}</b>: {{ $value}}
                                        <br>
                                    @endforeach
                                </td>
                                <td class="center">
                                    {{ date("d/m/Y H:i:s", strtotime($historySubmission->created_at)) }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </section>
@else
    <div class="row justify-content-center">
        <h2>CANNOT FIND SUBMISSION</h2>
    </div>
@endif
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
        const inputBeingChecked = document.getElementById(currentValue + refSeq);

        if (!inputBeingChecked.checkValidity()) {
            addOrRemoveInvalid(inputBeingChecked, "add");
            valid = false;
        } else {
            addOrRemoveInvalid(inputBeingChecked, "remove");
        }
    });

    const souvenirData = [];
    for (let i = 0; i < 10; i++) {
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
        document.getElementById("name_" + refSeq).innerHTML = data.name;
        document.getElementById("age_" + refSeq).innerHTML = data.age;
        document.getElementById("phone_" + refSeq).innerHTML = data.phone;
        document.getElementById("province_" + refSeq).setAttribute("data-province", data.province);
        document.getElementById("province_" + refSeq).innerHTML = response.province;
        document.getElementById("city_" + refSeq).setAttribute("data-city", data.city);
        document.getElementById("city_" + refSeq).innerHTML = response.city;
        document.getElementById("souvenir_" + refSeq).setAttribute("data-souvenir", data.souvenir_id);
        document.getElementById("souvenir_" + refSeq).innerHTML = response.souvenir;

        if (data.link_hs) {
            document.getElementById("link-hs_" + refSeq).innerHTML = `<a href="${data.link_hs}" id="link-hs-href_${refSeq}" target="blank">`
                + `<i class="mdi mdi-home" style="font-size: 24px; color: #2daaff;"></i>`
                + `</a>`;
        } else {
            document.getElementById("link-hs_" + refSeq).innerHTML = "";
        }

        document.getElementById("status_" + refSeq).innerHTML = data.status;

        document.getElementById("btn-edit-save_" + refSeq).setAttribute("onclick", "clickEdit(this)");
    document.getElementById("btn-edit-save_" + refSeq).innerHTML = `<i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>`;
    }).catch(function (error){
        console.error(error);
    });
}
</script>
@endsection
