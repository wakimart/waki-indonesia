<?php

use App\Product;

$menu_item_page = "submission";
$menu_item_second = "add_submission_mgm";
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
            <h3 class="page-title">Add Submission - MGM</h3>
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
                        Add Submission - MGM
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
                            action="{{ route("store_submission_mgm") }}">
                            @csrf
                            <div class="form-group">
                                <label>Type Register</label>
                                <input type="hidden" name="type" value="mgm" />
                                <input type="text" readonly disabled value="MGM" />
                            </div>

                            <div class="form-group">
                                <label><h2>Customer Data</h2></label>
                                <br>
                                <label id="member_label" for="member_input">
                                    No. MPC
                                </label>
                                <input id="member_input"
                                    type="text"
                                    class="form-control"
                                    id="no_member"
                                    name="no_member"
                                    placeholder="No. Member"
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    placeholder="Name"
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="number"
                                    class="form-control"
                                    id="phone"
                                    name="phone"
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
                                <label for="city">City</label>
                                <select class="form-control"
                                    id="city"
                                    name="city"
                                    data-msg="Mohon Pilih Kota"
                                    required>
                                    <option selected disabled value="" hidden>
                                        Pilihan Kota
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="subDistrict">Sub District</label>
                                <select class="form-control"
                                    id="subDistrict"
                                    name="district"
                                    data-msg="Mohon Pilih Kecamatan"
                                    required>
                                    <option selected disabled value="" hidden>
                                        Pilihan Kecamatan
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control"
                                    id="address"
                                    name="address"
                                    rows="4"
                                    placeholder="Full address"
                                    required>
                                </textarea>
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
                                        <option value="{{ $branch['id'] }}">
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
                                    required data-msg="Mohon Isi Kode CSO"
                                    style="text-transform:uppercase"
                                    {{ Auth::user()->roles[0]['slug'] == 'cso' ? 'value=' . Auth::user()->cso['code'] : "" }}
                                    {{ Auth::user()->roles[0]['slug'] == 'cso' ? "readonly" : "" }} />
                                <div class="validation" id="validation_cso"></div>
                            </div>
                            <br>
                            <br>
                            <div id="refrensiForm" class="form-group">
                                <h3>Reference Data</h3>
                                <br>
                                @for ($x = 0; $x < 10; $x++)
                                    <div class="tab">
                                        <label for="member-name-{{ $x }}">
                                            Member {{ $x + 1 }}
                                        </label>
                                        <div class="form-group">
                                            <label for="member-name-{{ $x }}">
                                                Name
                                            </label>
                                            <input type="text"
                                                id="member-name-{{ $x }}"
                                                class="form-control"
                                                name="name_ref[]"
                                                placeholder="Name"
                                                {{ $x > 0 ? "" : "required" }} />
                                            <div class="validation"></div>
                                        </div>

                                        <div class="form-group">
                                            <label for="member-age-{{ $x }}">
                                                Age
                                            </label>
                                            <input type="number"
                                                id="member-age-{{ $x }}"
                                                class="form-control"
                                                name="age_ref[]"
                                                placeholder="Age"
                                                {{ $x > 0 ? "" : "required" }} />
                                            <div class="validation"></div>
                                        </div>

                                        <div class="form-group">
                                            <label for="member-phone-{{ $x }}">
                                                Phone Number
                                            </label>
                                            <input type="number"
                                                id="member-phone-{{ $x }}"
                                                class="form-control"
                                                name="phone_ref[]"
                                                placeholder="Phone Number"
                                                {{ $x > 0 ? "" : "required" }} />
                                            <div class="validation"></div>
                                        </div>

                                        <div class="form-group">
                                            <label for="province-{{ $x }}">
                                                Province
                                            </label>
                                            <select class="form-control changeProvince"
                                                id="province-{{ $x }}"
                                                name="province_ref[]"
                                                data-msg="Mohon Pilih Provinsi"
                                                {{ $x > 0 ? "" : "required" }}>
                                                <option selected
                                                    disabled
                                                    value=""
                                                    hidden>
                                                    Pilih Provinsi
                                                </option>
                                                <?php
                                                $result = RajaOngkir::FetchProvince();
                                                $result = $result['rajaongkir']['results'];
                                                if (sizeof($result) > 0) {
                                                    foreach ($result as $value) {
                                                        echo '<option value="' . $value['province_id'] . '">' . $value['province'] . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <div class="validation"></div>
                                        </div>

                                        <div class="form-group">
                                            <label for="city-{{ $x }}">
                                                City
                                            </label>
                                            <select class="form-control"
                                                id="city-{{ $x }}"
                                                name="city_ref[]"
                                                data-msg="Mohon Pilih Kota"
                                                {{ $x > 0 ? "" : "required" }}>
                                                <option selected
                                                    disabled
                                                    value=""
                                                    hidden>
                                                    Pilih Kota
                                                </option>
                                            </select>
                                            <div class="validation"></div>
                                        </div>

                                        @for ($j = 0; $j < 2; $j++)
                                            <div class="form-group product-group"
                                                id="promo-group-{{ $j + 1 }}">
                                                <div class="col-xs-12 col-sm-12 row"
                                                    style="margin: 0;padding: 0;">
                                                    <div class="col-xs-10 col-sm-10"
                                                        style="padding: 0; display: inline-block;">
                                                        <label for="promo-{{ $j + 1 }}">
                                                            Promo {{ $j + 1 }} {{ $j > 0 ? "(optional)" : "" }}
                                                        </label>
                                                        <select class="form-control pilihan-product"
                                                            id="promo-{{ $j + 1 }}"
                                                            name="promo_{{ $j + 1 }}[]"
                                                            data-msg="Mohon Pilih Promo"
                                                            {{ $j > 0 || $x > 0 ? "" : "required" }}>
                                                            <option selected
                                                                disabled
                                                                value=""
                                                                {{ $j > 0 && $x > 0 ? "" : "hidden" }}>
                                                                Choose Promo {{ $j > 0 ? "(optional)" : ""}}
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
                                                        <div class="validation"></div>
                                                    </div>
                                                    <div class="col-xs-2 col-sm-2"
                                                        style="padding-right: 0;display: inline-block;">
                                                        <label for="qty-{{ $j }}">
                                                            Qty
                                                        </label>
                                                        <select class="form-control"
                                                            id="qty-{{ $j + 1 }}"
                                                            name="qty_{{ $j + 1 }}[]"
                                                            data-msg="Mohon Pilih Jumlah"
                                                            {{ $j > 0 ? "" : "required" }}>
                                                            <option selected value="1">
                                                                1
                                                            </option>

                                                            @for ($i = 2; $i <= 10; $i++)
                                                                <option value="{{ $i }}">
                                                                    {{ $i }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                        <div class="validation"></div>
                                                    </div>

                                                    <div class="form-group d-none">
                                                        <input type="text"
                                                            class="form-control"
                                                            name="other_{{ $j + 1 }}[]"
                                                            placeholder="Product Name"
                                                            data-msg="Please fill in the product" />
                                                        <div class="validation"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor

                                        <div class="form-group">
                                            <label for="do-proof">
                                                Proof Delivery Order (Image)
                                            </label>
                                            <input type="file"
                                                class="form-control-file"
                                                id="do-proof"
                                                name="do-image_{{ $x + 1 }}[]"
                                                {{ $x > 0 ? "" : "required" }} />
                                        </div>
                                    </div>
                                @endfor

                                <div style="overflow:auto;">
                                    <div style="float:right;">
                                        <button type="button"
                                            id="prevBtn"
                                            onclick="nextPrev(-1)">
                                            Previous
                                        </button>
                                        <button type="button"
                                            id="nextBtn"
                                            onclick="nextPrev(1)">
                                            Next
                                        </button>
                                    </div>
                                </div>

                                <!-- Circles which indicates the steps of the form: -->
                                <div style="text-align:center; margin-top:40px;">
                                    @for ($x = 0; $x < 10; $x++)
                                        <span class="step"></span>
                                    @endfor
                                </div>
                            </div>
                            <div id="errormessage"></div>

                            <div class="form-group">
                                <button id="addDeliveryOrder"
                                    type="submit"
                                    class="btn btn-gradient-primary mr-2">
                                    Save
                                </button>
                                <button class="btn btn-light">Cancel</button>
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
<script type="application/javascript">
let currentTab = 0;
showTab(currentTab);

function showTab(n) {
    // This function will display the specified tab of the form ...
    const x = document.getElementsByClassName("tab");
    x[n].style.display = "block";

    // ... and fix the Previous/Next buttons:
    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }

    if (n == (x.length - 1)) {
        document.getElementById("nextBtn").style.display = "none";
    } else {
        document.getElementById("nextBtn").style.display = "inline";
    }

    // ... and run a function that displays the correct step indicator:
    fixStepIndicator(n)
}

function nextPrev(n) {
    // This function will figure out which tab to display
    const x = document.getElementsByClassName("tab");

    // Exit the function if any field in the current tab is invalid:
    if (n == 1 && !validateForm()) {
        return false;
    }

    // Hide the current tab:
    x[currentTab].style.display = "none";
    // Increase or decrease the current tab by 1:
    currentTab = currentTab + n;

    // Otherwise, display the correct tab:
    showTab(currentTab);
}

function validateForm() {
    // This function deals with validation of the form fields
    let valid = true;

    const inputArray = ["member-name-", "member-age-", "member-phone-", "province-", "city-"];

    inputArray.forEach(function (currentValue) {
        const inputBeingChecked = document.getElementById(currentValue + currentTab);

        if (!inputBeingChecked.checkValidity()) {
            addOrRemoveInvalid(inputBeingChecked, "add");
            valid = false;
        } else {
            addOrRemoveInvalid(inputBeingChecked, "remove");
        }
    });

    return valid; // return the valid status
}

function addOrRemoveInvalid(element, command) {
    if (command === "add" && !element.className.includes("invalid")) {
        element.classList.add("invalid");
    } else if (command === "remove" && element.className.includes("invalid")) {
        element.classList.remove("invalid");
    }
}

function fixStepIndicator(n) {
    // This function removes the "active" class of all steps...
    const x = document.getElementsByClassName("step");

    for (let i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }

    //... and adds the "active" class to the current step:
    x[n].className += " active";
}

$(document).on("change", ".changeProvince", function () {
    const get_index = $(this).attr('id');
    const index = get_index.slice(-1);

    const id = $(this).val();

    $("#city-" + index).html("");
    $.get('{{ route("fetchCity", ['province' => ""]) }}/' + id)
        .done(function (result) {
            result = result['rajaongkir']['results'];
            let arrCity = "<option selected disabled value=\"\" hidden>Pilihan Kota</option>";

            if (result.length > 0) {
                $.each( result, function (key, value) {
                    if (value['type'] == "Kabupaten") {
                        arrCity += "<option value=\"" + value['city_id'] + "\">Kabupaten " + value['city_name'] + "</option>";
                    }

                    if (value['type'] == "Kota") {
                        arrCity += "<option value=\"" + value['city_id'] + "\">Kota " + value['city_name'] + "</option>";
                    }
                });

                $("#city-" + index).append(arrCity);
            }
        });
})

document.addEventListener("DOMContentLoaded", function () {
    $("#cso").on("input", function () {
        check_cso($("#cso").val());
    });

    $("#province").on("change", function () {
        const id = $(this).val();
        $("#city").html("");

        $.get('{{ route("fetchCity", ['province' => ""]) }}/' + id)
            .done(function (result) {
                result = result['rajaongkir']['results'];
                let arrCity = "<option selected disabled value=\"\" hidden>Pilihan Kota</option>";

                if (result.length > 0) {
                    $.each(result, function (key, value) {
                        if (value['type'] == "Kabupaten") {
                            arrCity += "<option value=\"" + value['city_id'] + "\">Kabupaten " + value['city_name'] + "</option>";
                        }

                        if (value['type'] == "Kota") {
                            arrCity += "<option value=\"" + value['city_id'] + "\">Kota " + value['city_name'] + "</option>";
                        }
                    });

                    $( "#city" ).append(arrCity);
                }
            });
    });

    $("#city").on("change", function () {
        const id = $(this).val();
        $("#subDistrict").html("");

        $.get('{{ route("fetchDistrict", ['city' => ""]) }}/' + id)
            .done(function (result) {
                result = result['rajaongkir']['results'];
                let arrSubDistsrict = "<option selected disabled value=\"\" hidden>Pilihan Kecamatan</option>";

                if (result.length > 0) {
                    $.each( result, function (key, value) {
                        arrSubDistsrict += "<option value=\"" + value['subdistrict_id'] + "\">" + value['subdistrict_name'] + "</option>";
                    });

                    $( "#subDistrict" ).append(arrSubDistsrict);
                }
            });
    });

    function check_cso(code) {
        $.get('{{route("fetchCso")}}', { cso_code: code })
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

    $(".pilihan-product").change(function (e) {
        if ($(this).val() == 'other') {
            $(this).parent().next().next().removeClass("d-none");
            $(this).parent().next().next().children().attr('required', '');
        } else {
            $(this).parent().next().next().addClass("d-none");
            $(this).parent().next().next().children().removeAttr('required', '');
        }
    });

    // Memunculkan alert apabila gambar lebih dari 5
    $("#addDeliveryOrder").click(function () {
        if ($("#type_register").val() === "Refrensi") {
            const numberOfImage = parseInt($("#image-proof").get(0).files.length);

            if (numberOfImage > 5) {
                $("form").submit(function (e) {
                    e.preventDefault();
                });

                alert("Gambar maksimal hanya 5.");
            } else if (numberOfImage === 0) {
                $("form").submit(function (e) {
                    e.preventDefault();
                });

                alert("Gambar harus ada, minimal 1.");
            } else if (numberOfImage >= 1 && numberOfImage <= 5) {
                $("form").submit(function (e) {
                    e.currentTarget.submit();
                });
            }
        }
    });
}, false);
</script>
@endsection
