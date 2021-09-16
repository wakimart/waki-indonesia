<?php
$menu_item_page = "personal_homecare";
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

    .img-h500 {
        max-height: 500px;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Edit Personal Homecare</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            aria-expanded="false">
                            Personal Homecare
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h2>Agent Data</h2>

                        <div class="form-group">
                            <label for="branch_id">Branch</label>
                            <select class="form-control"
                                id="branch_id"
                                name="branch_id"
                                form="add-phc"
                                {{ $personalhomecare['status'] == "new" ? 'required' : 'disabled' }}>
                                <option disabled>
                                    Select Branch
                                </option>
                                @foreach ($branches as $branch)
                                    @if ($personalhomecare->branch_id == $branch->id)
                                        <option value="{{ $branch->id }}" selected>
                                            {{ $branch->code }} - {{ $branch->name }}
                                        </option>
                                    @else
                                        <option value="{{ $branch->id }}">
                                            {{ $branch->code }} - {{ $branch->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden"
                            id="cso-id-hidden"
                            form="add-phc" />

                        <div class="form-group">
                            <label for="cso_id">CSO</label>
                            <select  class="form-control"
                                name="cso_id"
                                id="cso_id"
                                {{ $personalhomecare['status'] == "new" ? 'required' : 'disabled' }}
                                form="add-phc">
                                <option disabled>
                                    Select CSO
                                </option>
                                @foreach ($csos as $cso)
                                    @if ($personalhomecare->cso_id == $cso->id)
                                        <option value="{{ $cso->id }}" selected>
                                            {{ $cso->code }} - {{ $cso->name }}
                                        </option>
                                    @else
                                        <option value="{{ $cso->id }}">
                                            {{ $cso->code }} - {{ $cso->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h2>Customer Data</h2>

                        <form id="add-phc"
                            method="POST"
                            enctype="multipart/form-data"
                            action="{{ route("update_personal_homecare") }}">
                            @csrf
                            <input type="hidden"
                                name="id"
                                value="{{ $personalhomecare["id"] }}" />
                            <div class="form-group">
                                <label for="schedule">Schedule Date</label>
                                <input type="date"
                                    class="form-control"
                                    name="schedule"
                                    id="schedule"
                                    value="{{ date('Y-m-d', strtotime($personalhomecare['schedule'])) }}"
                                    {{ $personalhomecare['status'] == "new" ? 'required' : 'disabled' }} />
                            </div>

                            <div class="form-group">
                                <label for="ph_product_id">Product</label>
                                <select class="form-control"
                                    name="ph_product_id"
                                    id="ph_product_id"
                                    {{ $personalhomecare['status'] == "new" || Gate::check('change-status-product-personalhomecare') ? 'required' : 'disabled' }} >
                                    <option disabled>
                                        Select Product (Please select branch first)
                                    </option>
                                    @foreach ($phcProducts as $phcProduct)
                                        @if ($personalhomecare->ph_product_id == $phcProduct->id)
                                            <option value="{{ $phcProduct->id }}"
                                                selected>
                                                {{ $phcProduct->code }} - {{ $phcProduct->name }}
                                            </option>
                                        @else
                                            <option value="{{ $phcProduct->id }}">
                                                {{ $phcProduct->code }} - {{ $phcProduct->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text"
                                    class="form-control"
                                    maxlength="191"
                                    name="name"
                                    id="name"
                                    placeholder="Name"
                                    value="{{ $personalhomecare['name'] }}"
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="tel"
                                    class="form-control"
                                    maxlength="20"
                                    name="phone"
                                    id="phone"
                                    placeholder="Phone"
                                    value="{{ $personalhomecare['phone'] }}"
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control"
                                    name="address"
                                    id="address"
                                    placeholder="Address"
                                    rows="3"
                                    required>{{ $personalhomecare['address'] }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="province_id">Province</label>
                                <select class="form-control"
                                    name="province_id"
                                    id="province_id"
                                    onchange="setCity(this)"
                                    required>
                                    <option disabled>
                                        Select Province
                                    </option>
                                    @foreach ($provinces as $province)
                                        @if ($personalhomecare->province_id == $province->id)
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
                                        @if ($personalhomecare->city_id == $city->id)
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
                                    <option disabled>
                                        Select Subdistrict
                                    </option>
                                    @foreach ($subdistricts as $subdistrict)
                                        @if ($personalhomecare->subdistrict_id == $subdistrict->id)
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
                                <img src="{{ asset("sources/phc/" . $personalhomecare->id_card) }}"
                                    class="img-thumbnail img-h500"
                                    alt="customer id card" />
                            </div>

                            <div class="form-group">
                                <label for="id_card_image">
                                    Customer ID Card
                                </label>
                                <input type="file"
                                    class="form-control"
                                    accept="image/jpeg, image/png"
                                    name="id_card_image"
                                    id="id_card_image" />
                            </div>

                            <div class="form-group">
                                <img src="{{ asset("sources/phc/" . $personalhomecare->member_wakimart) }}"
                                    class="img-thumbnail img-h500"
                                    alt="customer member wakimart" />
                            </div>

                            <div class="form-group">
                                <label for="member_wakimart_image">
                                    Customer Wakimart Member
                                </label>
                                <input type="file"
                                    class="form-control"
                                    accept="image/jpeg, image/png"
                                    name="member_wakimart_image"
                                    id="member_wakimart_image" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" form="add-phc" class="btn btn-gradient-primary">
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
document.addEventListener("DOMContentLoaded", function() {
    const csoId = '{{ Auth::user()->roles[0]["slug"] === "cso" ? Auth::user()->cso['id'] : "" }}';

    if (csoId !== "") {
        document.getElementById("cso_id").value = csoId;
        document.getElementById("cso_id").disabled = true;
        document.getElementById("cso_id").removeAttribute("name");
        document.getElementById("cso-id-hidden").value = csoId;
        document.getElementById("cso-id-hidden").setAttribute("name", "cso_id");
    }

    $("#ph_product_id").select2();
    $("#province_id").select2();
    $("#city_id").select2();
    $("#subdistrict_id").select2();
    $("#branch_id").select2();
    $("#cso_id").select2();
});

function setProduct(e) {
    fetch(
        '{{ route("get_phc_product") }}?branch_id=' + e.value,
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
        $("#ph_product_id").select2("destroy");

        const data = response.data;

        data.forEach(function (value) {
            const options = document.createElement("option");
            options.value = value.id;
            options.innerHTML = `${value.code} - ${value.name}`;

            document.getElementById("ph_product_id").append(options);
        });

        document.getElementById("ph_product_id").removeAttribute("disabled");

        $("#ph_product_id").select2();
    }).catch(function(error) {
        console.error(error);
    });
}

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
</script>
@endsection
