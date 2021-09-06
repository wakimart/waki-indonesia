<?php
$menu_item_page = "stock_warehouse";
$menu_item_second = "add_warehouse";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
    integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
<style type="text/css">
    button {
        background: #1bb1dc;
        border: 0;
        border-radius: 3px;
        padding: 8px 30px;
        color: #fff;
        transition: 0.3s;
    }

    .validation {
        color: red;
        font-size: 9pt;
    }

    input, select, textarea {
        border-radius: 0 !important;
        box-shadow: none !important;
        border: 1px solid #dce1ec !important;
        font-size: 14px !important;
    }

    .input-group-text {
        color: black !important;
    }

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

    .div-CheckboxGroup {
        border: solid 1px rgba(128, 128, 128, 0.32941);
        padding: 10px;
        border-radius: 3px;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Update Warehouse</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            aria-expanded="false">
                            Stock Warehouse
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Update Warehouse
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form id="add-warehouse"
                            method="POST"
                            enctype="multipart/form-data"
                            action="">
                            @csrf

                            <div class="form-group">
                                <label for="parent">Parent Warehouse</label>
                                <select class="form-control"
                                    name=""
                                    id="parent"
                                    required>
                                    <option disabled selected>
                                        Select Parent Warehouse (Jika Ada)
                                    </option>
                                    <option></option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text"
                                    class="form-control"
                                    name=""
                                    id="code"
                                    placeholder="Code"
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text"
                                    class="form-control"
                                    maxlength="191"
                                    name="name"
                                    id="name"
                                    placeholder="Name"
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control"
                                    name="address"
                                    id="address"
                                    placeholder="Address"
                                    rows="3"
                                    required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="province_id">Province</label>
                                <select class="form-control"
                                    name="province_id"
                                    id="province_id"
                                    onchange="setCity(this)"
                                    required>
                                    <option disabled selected>
                                        Select Province
                                    </option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}">
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="city_id">City</label>
                                <select class="form-control"
                                    name="city_id"
                                    id="city_id"
                                    onchange="setSubdistrict(this)"
                                    required>
                                    <option disabled selected>
                                        Select City
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="subdistrict_id">Subdistrict</label>
                                <select class="form-control"
                                    name="subdistrict_id"
                                    id="subdistrict_id"
                                    required>
                                    <option disabled selected>
                                        Select Subdistrict
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control"
                                    name="description"
                                    id="description"
                                    placeholder="Descruption"
                                    rows="3"
                                    required></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit"
            id="submit-button"
            form="add-phc"
            class="btn btn-gradient-primary">
            Update
        </button>
    </div>
</div>
@endsection

@section("script")
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
    integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
    defer></script>
<script type="application/javascript">

function setCity(e) {
    const URL = '{{ route("fetchCity", ["province" => ""]) }}/' + e.value;

    fetch(
        URL,
        {
            method: "GET",
            headers: {
                "Accept": "application/json",
            },
            mode: "same-origin",
            referrerPolicy: "no-referrer",
        }
    ).then(function (response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    }).then(function (response) {
        const result = response["rajaongkir"]["results"];

        const arrCity = [];
        arrCity[0] = '<option disabled>Pilihan Kabupaten</option>';
        arrCity[1] = '<option disabled>Pilihan Kota</option>';

        if (result.length > 0) {
            $("#city_id").select2("destroy");

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

            document.getElementById("city_id").innerHTML = arrCity[0] + arrCity[1];

            $("#city_id").select2();
        }
    }).catch(function(error) {
        console.error(error);
    });
}

function setSubdistrict(e) {
    const URL = '{{ route("fetchDistrict", ['city' => ""]) }}/' + e.value;

    fetch(
        URL,
        {
            method: "GET",
            headers: {
                "Accept": "application/json",
            },
            mode: "same-origin",
            referrerPolicy: "no-referrer",
        }
    ).then(function (response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    }).then(function (response) {
        const result = response["rajaongkir"]["results"];

        let optionsDistrict = "";

        if (result.length > 0) {
            $("#subdistrict_id").select2("destroy");

            result.forEach(function (currentValue) {
                optionsDistrict += '<option value="'
                    + currentValue["subdistrict_id"]
                    + '">'
                    + currentValue['subdistrict_name']
                    + '</option>';
            });

            document.getElementById("subdistrict_id").innerHTML = optionsDistrict;

            $("#subdistrict_id").select2();
        }
    }).catch(function (error) {
        console.error(error);
    })
}

function showOtherInput(e) {
    if (e.checked) {
        document.getElementById("other-text").classList.remove("d-none");
        document.getElementById("other-text").setAttribute("required", "");
    } else {
        document.getElementById("other-text").removeAttribute("required");
        document.getElementById("other-text").classList.add("d-none");
    }
}
</script>
@endsection
