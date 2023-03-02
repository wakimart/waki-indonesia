<?php

use App\Product;

$menu_item_page = "submission";
$menu_item_second = "add_submission_takeaway";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    #intro {
        padding-top: 2em;
    }
    button{
        background: #1bb1dc;
        border: 0;
        border-radius: 3px;
        padding: 8px 30px;
        color: #fff;
        transition: 0.3s;
    }
    .validation{
        color: red;
        font-size: 9pt;
    }
    input, select, textarea{
        border-radius: 0 !important;
        box-shadow: none !important;
        border: 1px solid #dce1ec !important;
        font-size: 14px !important;
    }
    #regForm {
      background-color: #ffffff;
      margin: 100px auto;
      padding: 40px;
      width: 70%;
      min-width: 300px;
    }
    /* Style the input fields */
    input {
      padding: 10px;
      width: 100%;
      font-size: 17px;
      font-family: Raleway;
      border: 1px solid #aaaaaa;
    }
    /* Mark input boxes that gets an error on validation: */
    input.invalid {
      background-color: #ffdddd;
    }
    .invalid {
        border: 1px solid red !important;
    }
    /* Hide all steps by default: */
    .tab {
      display: none;
    }
    /* Make circles that indicate the steps of the form: */
    .step {
      height: 15px;
      width: 15px;
      margin: 0 2px;
      background-color: #bbbbbb;
      border: none;
      border-radius: 50%;
      display: inline-block;
      opacity: 0.5;
    }
    /* Mark the active step: */
    .step.active {
      opacity: 1;
    }
    /* Mark the steps that are finished and valid: */
    .step.finish {
      background-color: #4CAF50;
    }
    select {
      color: black !important;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
      <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Edit Submission - Referensi Happy With WAKi</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#"
                            aria-expanded="false"
                            aria-controls="deliveryorder-dd">
                            Submission
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Submission - Referensi Happy With WAKi
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <form id="actionAdd"
                            class="forms-sample"
                            method="POST"
                            enctype="multipart/form-data"
                            action="{{ route("update_submission_referensi") }}">
                            @csrf
                            <input type="hidden"
                                name="id"
                                value="{{ $submission->id }}" />
                            <div class="form-group">
                                <label>Type Register</label>
                                <input type="hidden"
                                    name="type"
                                    value="referensi" />
                                <input type="text"
                                    readonly
                                    disabled
                                    value="Referensi Happy With WAKi" />
                            </div>

                            <div class="form-group">
                                <label><h2>Customer Data</h2></label>
                                <br>
                                <label id="member_label"
                                    for="member_input">
                                    No. MPC
                                </label>
                                <input id="member_input"
                                    type="text"
                                    class="form-control"
                                    id="no_member"
                                    name="no_member"
                                    value="{{ $submission->no_member }}"
                                    placeholder="No. member"
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    placeholder="Name"
                                    value="{{ $submission->name }}"
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="number"
                                    class="form-control"
                                    id="phone"
                                    name="phone"
                                    value="{{ $submission->phone }}"
                                    placeholder="Phone Number"
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="province">Province</label>
                                <select class="form-control"
                                    id="province"
                                    name="province"
                                    data-msg="Mohon Pilih Provinsi"
                                    required>
                                    <option selected disabled value="" hidden>
                                        Pilihan Provinsi
                                    </option>
                                    <?php
                                    $result = RajaOngkir::FetchProvince();
                                    $result = $result['rajaongkir']['results'];
                                    if (sizeof($result) > 0) {
                                        $selected = "";
                                        foreach ($result as $value) {
                                            if ($submission->province_id === $value["province_id"]) {
                                                $selected = "selected";
                                            }

                                            echo '<option '
                                                . 'value="' . $value['province_id'] . '" '
                                                . $selected
                                                . '>'
                                                . $value['province']
                                                . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="city">City</label>
                                <select class="form-control"
                                    id="city"
                                    name="city"
                                    data-msg="Mohon Pilih Kota"
                                    required>
                                    <option selected disabled value="" hidden>
                                        Pilihan Kota
                                    </option>
                                    <?php
                                    $getCity = RajaOngkir::FetchCity($submission->province_id);
                                    $getCity = $getCity["rajaongkir"]["results"];

                                    if (!empty($getCity)) {
                                        foreach ($getCity as $city) {
                                            $selected = "";
                                            if ($city->city_id === $submission->city_id) {
                                                $selected = "selected";
                                            }

                                            echo '<option '
                                                . 'value="' . $city->city_id . '" '
                                                . $selected
                                                . ">"
                                                . $city->type . " " . $city->city_name
                                                . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="subDistrict">Sub District</label>
                                <select class="form-control"
                                    id="district"
                                    name="district"
                                    data-msg="Mohon Pilih Kecamatan"
                                    required>
                                    <option selected disabled value="" hidden>
                                        Pilihan Kecamatan
                                    </option>
                                    <?php
                                    $getDistrict = RajaOngkir::FetchDistrict($submission->city_id);
                                    $getDistrict =  $getDistrict["rajaongkir"]["results"];

                                    if (!empty($getDistrict)) {
                                        foreach ($getDistrict as $district) {
                                            $selected = "";
                                            if ($district->subdistrict_id === $submission->district_id) {
                                                $selected = "selected";
                                            }

                                            echo '<option '
                                                . 'value="' . $district->subdistrict_id . '" '
                                                . $selected
                                                . ">"
                                                . $district->subdistrict_name
                                                . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control"
                                    id="address"
                                    name="address"
                                    rows="4"
                                    placeholder="Full address"
                                    required>{{ $submission->address }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="branch">Branch</label>
                                <select class="form-control"
                                    id="branch"
                                    name="branch_id"
                                    data-msg="Mohon Pilih Cabang"
                                    required>
                                    <option selected disabled value="">
                                        Choose Branch
                                    </option>

                                    @foreach ($branches as $branch)
                                        <?php
                                        $selected = "";
                                        if ($branch->code === $submission->branch_code) {
                                            $selected = "selected";
                                        }
                                        ?>
                                        <option value="{{ $branch['id'] }}"
                                            {{ $selected }}>
                                            {{ $branch['code'] }} - {{ $branch['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="validation"></div>
                            </div>

                            <div class="form-group">
                                <label for="cso">CSO Code</label>
                                    <input type="text"
                                    class="form-control"
                                    name="cso_id"
                                    id="cso"
                                    placeholder="CSO Code"
                                    required
                                    data-msg="Mohon Isi Kode CSO"
                                    style="text-transform: uppercase;"
                                    value="{{ $submission->cso_code }}"
                                    oninput="check_cso(this)"
                                    {{ Auth::user()->roles[0]['slug'] == 'cso' ? "readonly" : "" }} />
                                <div class="validation" id="validation_cso"></div>
                            </div>
                            <div class="form-group">
                                <label for="wakimart-link">
                                    Wakimart Link (Optional)
                                </label>
                                <input type="text"
                                    class="form-control"
                                    id="wakimart-link"
                                    name="wakimart_link"
                                    placeholder="Wakimart Link"
                                    value="{{ $submission->wakimart_link }}" />
                                <div class="validation"></div>
                            </div>
                            <br>
                            <br>
                            <div class="form-group">
                                <button id="addDeliveryOrder"
                                    type="submit"
                                    class="btn btn-gradient-primary mr-2">
                                    Save
                                </button>
                                <button class="btn btn-light">Cancel</button>
                            </div>

                            @for ($i = 1; $i <= 5; $i++)
                                <div class="form-group">
                                    <label for="proof-image-{{ $i }}">
                                        Proof Image - {{ $i }}
                                    </label>
                                    <br>
                                    @if (!empty($submission["image_" . $i]))
                                        <img class="img-fluid img-thumbnail"
                                            src="{{ asset("sources/registration/" . $submission["image_" . $i]) }}"
                                            style="max-height: 300px"
                                            alt="Image DO {{ $i }}" />
                                    @endif
                                    <input type="file"
                                        id="proof-image-{{ $i }}"
                                        name="proof_image_{{ $i }}"
                                        class="proof-image"
                                        accept=".jpg, .jpeg, .png" />
                                </div>
                            @endfor
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("script")
<script type="application/javascript">
function check_cso(e) {
    const code = e.value;

    $.get('{{ route("fetchCso") }}', { cso_code: code })
        .done(function (result) {
            if (result['result'] == "true" && result['data'].length > 0) {
                $('#validation_cso').html('Kode CSO Benar');
                $('#validation_cso').css('color', 'green');
                $('#submit').removeAttr('disabled');
            } else {
                $('#validation_cso').html('Kode CSO Salah');
                $('#validation_cso').css('color', 'red');
                $('#submit').attr('disabled',"");
            }
        });
}

document.addEventListener("DOMContentLoaded", function () {
    check_cso(document.getElementById("cso"));

    $("#province").on("change", function () {
        const id = $(this).val();
        $("#city").html("");

        $.get('{{ route("fetchCity", ['province' => ""]) }}/' + id)
            .done(function (result) {
                result = result['rajaongkir']['results'];
                let arrCity = '<option selected disabled value="" hidden>Pilih Kota</option>';

                if (result.length > 0) {
                    $.each(result, function (key, value) {
                        if (value['type'] == "Kabupaten") {
                            arrCity += `<option value="${value['city_id']}">Kabupaten ${value['city_name']}</option>`;
                        }

                        if (value['type'] == "Kota") {
                            arrCity += `<option value="${value['city_id']}">Kota ${value['city_name']}</option>`;
                        }
                    });

                    $("#city").append(arrCity);
                }
            });
    });

    $("#city").on("change", function () {
        const id = $(this).val();
        $("#district").html("");

        $.get('{{ route("fetchDistrict", ['city' => ""]) }}/' + id)
            .done(function (result) {
                result = result['rajaongkir']['results'];
                let arrSubDistsrict = '<option selected disabled value="" hidden>Pilih Kecamatan</option>';

                if (result.length > 0) {
                    $.each( result, function (key, value) {
                        arrSubDistsrict += `"<option value="${value['subdistrict_id']}">`
                            + value['subdistrict_name']
                            + "</option>";
                    });

                    $("#district").append(arrSubDistsrict);
                }
            });
    });
}, false);
</script>
@endsection
