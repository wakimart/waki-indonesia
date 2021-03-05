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
            <h3 class="page-title">Add Registration</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#"
                            aria-expanded="false"
                            aria-controls="deliveryorder-dd">
                            Registration
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Add Registration
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
                            action="{{ route('store_submission_form') }}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <span>Type Register</span>
                                <select id="type_register"
                                    style="margin-top: 0.5em; height: auto;"
                                    class="form-control"
                                    name="type_register"
                                    required>
                                    <option value="MGM"
                                        @php
                                            if ($deliveryOrders->type_register === "MGM") {
                                                echo "selected";
                                            }
                                        @endphp
                                    >
                                        MGM
                                    </option>
                                    <option value="Refrensi"
                                        @php
                                            if ($deliveryOrders->type_register === "Refrensi") {
                                                echo "selected";
                                            }
                                        @endphp
                                    >
                                        Referensi
                                    </option>
                                    <option value="Take Away"
                                        @php
                                            if ($deliveryOrders->type_register === "Take Away") {
                                                echo "selected";
                                            }
                                        @endphp
                                    >
                                        Take Away
                                    </option>
                                </select>
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
                            </div>

                            <div class="form-group">
                                <label><h2>Data Pelanggan</h2></label>
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
                                    value="{{  }}"
                                    required />
                                <div class="validation"></div>
                            </div>

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    placeholder="Name"
                                    required />
                                <div class="validation"></div>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="number"
                                    class="form-control"
                                    id="phone"
                                    name="phone"
                                    placeholder="Phone Number"
                                    required />
                                <div class="validation"></div>
                            </div>

                            <div class="form-group">
                                <label for="province">Province</label>
                                <select class="form-control"
                                    id="province"
                                    name="province_id"
                                    data-msg="Mohon Pilih Provinsi"
                                    required>
                                    <option selected disabled value="" hidden>
                                        Pilihan Provinsi
                                    </option>
                                    @php
                                        $result = RajaOngkir::FetchProvince();
                                        $result = $result['rajaongkir']['results'];
                                        $arrProvince = [];
                                        if (sizeof($result) > 0) {
                                            foreach ($result as $value) {
                                                echo "<option value=\"" . $value['province_id'] . "\">" . $value['province']."</option>";
                                            }
                                        }
                                    @endphp
                                </select>
                                <div class="validation"></div>
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
                                <div class="validation"></div>
                            </div>

                            <div class="form-group">
                                <label for="subDistrict">Sub District</label>
                                <select class="form-control"
                                    id="subDistrict"
                                    name="distric"
                                    data-msg="Mohon Pilih Kecamatan"
                                    required>
                                    <option selected disabled value="" hidden>
                                        Pilihan Kecamatan
                                    </option>
                                </select>
                                <div class="validation"></div>
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control"
                                    id="address"
                                    name="address"
                                    rows="4"
                                    placeholder="Address Lengkap"
                                    required>
                                </textarea>
                                <div class="validation"></div>
                            </div>

                            @for($j = 0; $j < 2; $j++)
                                <div class="form-group product-group">
                                    <div class="col-xs-12 col-sm-12 row"
                                        style="margin: 0;padding: 0;">
                                        <div class="col-xs-10 col-sm-10"
                                            style="padding: 0; display: inline-block;">
                                            <label for="promo-{{ $j }}">
                                                Promo {{ $j + 1 }}
                                            </label>
                                            <select class="form-control pilihan-product"
                                                id="promo-{{ $j }}"
                                                name="product_{{ $j }}"
                                                data-msg="Mohon Pilih Promo"
                                                {{ $j > 0 ? "" : "required" }}>
                                                <option selected
                                                    disabled
                                                    value=""
                                                    {{ $j > 0 ? "" : "hidden" }}>
                                                    Choose Promo{{ $j > 0 ? " (optional)" : ""}}
                                                </option>
                                                @foreach($promos as $key => $promo)
                                                    <option value="{{ $key }}">
                                                        {{ $promo['code'] }} - {{ $promo['name'] }} ( {{ $promo['harga'] }} )
                                                    </option>
                                                @endforeach

                                                {{-- KHUSUS Philiphin --}}
                                                @if(true)
                                                    <option value="other">OTHER</option>
                                                @endif
                                            </select>
                                            <div class="validation"></div>
                                        </div>
                                        <div class="col-xs-2 col-sm-2"
                                            style="padding-right: 0;display: inline-block;">
                                            <label for="qty-{{ $j }}">
                                                Qty
                                            </label>
                                            <select class="form-control"
                                                id="qty-{{ $j }}"
                                                name="qty_{{ $j }}"
                                                data-msg="Mohon Pilih Jumlah"
                                                {{ $j > 0 ? "" : "required" }}>
                                                <option selected value="1">
                                                    1
                                                </option>

                                                @for($i = 2; $i <= 10; $i++)
                                                    <option value="{{ $i }}">
                                                        {{ $i }}
                                                    </option>
                                                @endfor
                                            </select>
                                            <div class="validation"></div>
                                        </div>

                                        {{-- KHUSUS Philiphin --}}
                                        @if(true)
                                            <div class="form-group d-none">
                                                <input type="text"
                                                    class="form-control"
                                                    name="product_other_{{ $j }}"
                                                    placeholder="Product Name"
                                                    data-msg="Please fill in the product" />
                                                <div class="validation"></div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endfor

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
                                        @if ($deliveryOrders['branch_id'] === $branch['id'])
                                            <option value="{{ $branch['id'] }}" selected>
                                                {{ $branch['code'] }} - {{ $branch['name'] }}
                                            </option>
                                        @else
                                            <option value="{{ $branch['id'] }}">
                                                {{ $branch['code'] }} - {{ $branch['name'] }}
                                            </option>
                                        @endif
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
                                    value="{{ $deliveryOrders->cso->code }}"
                                    {{ Auth::user()->roles[0]['slug'] == 'cso' ? "readonly=\"\"" : "" }} />
                                <div class="validation" id="validation_cso"></div>
                            </div>

                            <div class="form-group" class="image-proof">
                                <label for="image-proof" class="image-proof">
                                    Image proof
                                </label>
                                <input type="file"
                                    id="image-proof"
                                    name="image_proof[]"
                                    class="image-proof"
                                    accept=".jpg, .jpeg, .png"
                                    multiple />
                            </div>

                            <br>
                            <br>

                            <div id="refrensiForm" class="form-group">
                                <h3>Referensi:</h3>
                                <br>

                                <!-- One "tab" for each step in the form: -->
                                @for($x = 0; $x < 10; $x++)
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
                                                oninput="this.className = ''"
                                                required />
                                            <div class="validation"></div>
                                        </div>

                                        <div class="form-group">
                                            <label for="member-age-{{ $x }}">
                                                Age
                                            </label>
                                            <input type="text"
                                                id="member-age-{{ $x }}"
                                                class="form-control"
                                                name="age_ref[]"
                                                placeholder="Age"
                                                oninput="this.className = ''"
                                                required />
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
                                                oninput="this.className = ''"
                                                required />
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
                                                required>
                                                <option selected
                                                    disabled
                                                    value=""
                                                    hidden>
                                                    Pilihan Provinsi
                                                </option>
                                                @php
                                                    $result = RajaOngkir::FetchProvince();
                                                    $result = $result['rajaongkir']['results'];
                                                    $arrProvince = [];
                                                    if (sizeof($result) > 0) {
                                                        foreach ($result as $value) {
                                                            echo "<option value=\"" . $value['province_id'] . "\">" . $value['province'] . "</option>";
                                                        }
                                                    }
                                                @endphp
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
                                                onselect="this.className = ''"
                                                required>
                                                <option selected
                                                    disabled
                                                    value=""
                                                    hidden>
                                                    Pilihan Kota
                                                </option>
                                            </select>
                                            <div class="validation"></div>
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
                                    @for($x = 0; $x < 10; $x++)
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

@section('script')
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script type="text/javascript">
var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
    // This function will display the specified tab of the form ...
    var x = document.getElementsByClassName("tab");
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
    var x = document.getElementsByClassName("tab");

    // Exit the function if any field in the current tab is invalid:
    if (n == 1 && !validateForm()) {
        return false;
    }

    // Hide the current tab:
    x[currentTab].style.display = "none";
    // Increase or decrease the current tab by 1:
    currentTab = currentTab + n;

    // TODO: Cek codingan, apakah form masih berfungsi atau tidak
    // if you have reached the end of the form... :
    // if (currentTab >= x.length) {
    //     //...the form gets submitted:
    //     // document.getElementById("regForm").submit();
    //     return false;
    // }

    // Otherwise, display the correct tab:
    showTab(currentTab);
}

function validateForm() {
    // This function deals with validation of the form fields
    var x, y, i, valid = true;
    x = document.getElementsByClassName("tab");
    y = x[currentTab].getElementsByTagName("input");

    // A loop that checks every input field in the current tab:
    for (i = 0; i < y.length; i++) {
        // If a field is empty...
        if (y[i].value == "") {
            // add an "invalid" class to the field:
            y[i].className += " invalid";
            // and set the current valid status to false:
            valid = false;
        }
    }

    // If the valid status is true, mark the step as finished and valid:
    if (valid) {
        document.getElementsByClassName("step")[currentTab].className += " finish";
    }

    return valid; // return the valid status
}

function fixStepIndicator(n) {
    // This function removes the "active" class of all steps...
    var i, x = document.getElementsByClassName("step");

    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }

    //... and adds the "active" class to the current step:
    x[n].className += " active";
}

//function load city
$(document).on("change", ".changeProvince", function (){
    var get_index = $(this).attr('id');
    var index = get_index.slice(-1);

    var id = $(this).val();

    $("#city-" + index).html("");
    $.get('{{ route("fetchCity", ['province' => ""]) }}/' + id)
        .done(function(result) {
            result = result['rajaongkir']['results'];
            var arrCity = "<option selected disabled value=\"\" hidden>Pilihan Kota</option>";

            if(result.length > 0){
                $.each(result, function (key, value) {
                    if (value['type'] == "Kabupaten") {
                        arrCity += "<option value=\""+value['city_id'] + "\">Kabupaten " + value['city_name'] + "</option>";
                    }

                    if (value['type'] == "Kota") {
                        arrCity += "<option value=\"" + value['city_id'] + "\">Kota " + value['city_name'] + "</option>";
                    }
                });

                $("#city-" + index).append(arrCity);
            }
        });
})
</script>
<script type="text/javascript" src="{{ asset('js/tags-input.js') }}"></script>
<script type="text/javascript">
for (let input of document.querySelectorAll('#tags')) {
    tagsInput(input);
}
</script>
<script>
$(document).ready(function () {
    $("#cso").on("input", function () {
        check_cso($("#cso").val());
    });

    $("#province").on("change", function () {
        var id = $(this).val();
        $("#city").html("");

        $.get('{{ route("fetchCity", ['province' => ""]) }}/' + id)
            .done(function (result) {
                result = result['rajaongkir']['results'];
                var arrCity = "<option selected disabled value=\"\" hidden>Pilihan Kota</option>";

                if(result.length > 0){
                    $.each(result, function(key, value) {
                        if (value['type'] == "Kabupaten") {
                            arrCity += "<option value=\"" + value['city_id'] + "\">Kabupaten " + value['city_name'] + "</option>";
                        }

                        if (value['type'] == "Kota") {
                            arrCity += "<option value=\"" + value['city_id'] + "\">Kota " + value['city_name'] + "</option>";
                        }
                    });

                    $("#city").append(arrCity);
                }
            });
    });

    $("#city").on("change", function () {
        var id = $(this).val();
        $("#subDistrict").html("");

        $.get('{{ route("fetchDistrict", ['city' => ""]) }}/' + id)
            .done(function (result) {
                result = result['rajaongkir']['results'];
                var arrSubDistsrict = "<option selected disabled value=\"\" hidden>Pilihan Kecamatan</option>";

                if (result.length > 0) {
                    $.each( result, function(key, value) {
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

    {{-- KHUSUS Philiphin --}}
    @if(true)
        $(".pilihan-product").change(function (e) {
            if ($(this).val() == 'other') {
                $(this).parent().next().next().removeClass("d-none");
                $(this).parent().next().next().children().attr('required', '');
            } else {
                $(this).parent().next().next().addClass("d-none");
                $(this).parent().next().next().children().removeAttr('required', '');
            }
        });
    @endif

    // Memunculkan form file upload untuk image-proof
    function showHideImageproof() {
        if ($("#type_register").val() === "Refrensi") {
            $(".image-proof").prop("disabled", false);
            $(".image-proof").show();
        } else {
            $(".image-proof").prop("disabled", true);
            $(".image-proof").hide();
        }
    }

    $("#type_register").on('change', function (e) {
        if ($(this).val() === "Refrensi" || $(this).val() === "MGM") {
            $("#member_label").html("No. MPC");
            $("#member_input").attr("required", true);
        } else {
            $("#member_label").html("No. MPC (opsional)");
            $("#member_input").removeAttr("required");
        }

        showHideImageproof();
    });

    showHideImageproof();

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
});
</script>
@endsection
