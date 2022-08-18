<?php
$menu_item_page = "warehouse";
$menu_item_second = "add_warehouse";
?>
@extends("admin.layouts.template")

@section("style")
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
                            Warehouse
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Update
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form method="POST"
                            action="{{ route("update_warehouse") }}">
                            @csrf
                            <input type="hidden"
                                name="id"
                                value="{{ $warehouse->id }}"
                                required />

                            <div class="form-group">
                                <label for="parent_warehouse_id">
                                    Parent Warehouse
                                </label>
                                <select class="form-control"
                                    name="parent_warehouse_id"
                                    id="parent_warehouse_id">
                                    <option selected value="">
                                        Select Parent Warehouse
                                    </option>
                                    @foreach ($parentWarehouses as $parentWarehouse)
                                        @if ($warehouse->parent_warehouse_id === $parentWarehouse->id)
                                            <option value="{{ $parentWarehouse->id }}" selected>
                                                {{ $parentWarehouse->code }} - {{ $parentWarehouse->name }}
                                            </option>
                                        @else
                                            <option value="{{ $parentWarehouse->id }}">
                                                {{ $parentWarehouse->code }} - {{ $parentWarehouse->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text"
                                    class="form-control"
                                    name="code"
                                    id="code"
                                    placeholder="Code"
                                    maxlength="191"
                                    value="{{ $warehouse->code }}"
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    placeholder="Name"
                                    maxlength="200"
                                    value="{{ $warehouse->name }}"
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea name="address"
                                    class="form-control"
                                    id="address"
                                    rows="2"
                                    placeholder="Address"
                                    maxlength="300">{{ $warehouse->address }}</textarea>
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
                                        @if ($warehouse->province_id === $province->id)
                                            <option value="{{ $province->id }}" selected>
                                                {{ $province->name }}
                                            </option>
                                        @else
                                            <option value="{{ $province->id }}">
                                                {{ $province->name }}
                                            </option>
                                        @endif
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
                                    <option disabled>
                                        Select City
                                    </option>
                                    @foreach ($cities as $city)
                                        @if ($warehouse->city_id === $city->id)
                                            <option value="{{ $city->id }}" selected>
                                                {{ $city->name }}
                                            </option>
                                        @else
                                            <option value="{{ $city->id }}">
                                                {{ $city->name }}
                                            </option>
                                        @endif
                                    @endforeach
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
                                    @foreach ($subdistricts as $subdistrict)
                                        @if ($warehouse->subdistrict_id === $subdistrict->id)
                                            <option value="{{ $subdistrict->id }}" selected>
                                                {{ $subdistrict->name }}
                                            </option>
                                        @else
                                            <option value="{{ $subdistrict->id }}">
                                                {{ $subdistrict->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description"
                                    class="form-control"
                                    id="description"
                                    rows="2"
                                    placeholder="Description"
                                    maxlength="191">{{ $warehouse->description }}</textarea>
                            </div>

                            <div class="form-group">
                                <button type="submit"
                                    class="btn btn-gradient-primary">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
document.addEventListener("DOMContentLoaded", function () {
    $("#parent_warehouse_id").select2();
    $("#province_id").select2();
    $("#city_id").select2();
    $("#subdistrict_id").select2();
});

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
    }).catch(function (error) {
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
                optionsDistrict += `<option value="${currentValue["subdistrict_id"]}">`
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
</script>
@endsection
