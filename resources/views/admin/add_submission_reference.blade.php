<?php
$menu_item_page = "submission";
$menu_item_second = "add_submission_reference";
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
        border-color: #f50000 !important;
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
            <h3 class="page-title">
                Add Submission - Referensi Sehat Bersama WAKi/Keuntungan Biaya Iklan
            </h3>
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
                        Add Submission - Referensi
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
                            action="{{ route("store_submission_reference") }}">
                            @csrf
                            <div class="form-group">
                                <label>Type Register</label>
                                <input type="hidden"
                                    name="type"
                                    value="referensi" />
                                <input type="text"
                                    readonly
                                    disabled
                                    value="Referensi Sehat Bersama WAKi/Keuntungan Biaya Iklan" />
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
                                    data-targetselect="city"
                                    onchange="setCity(this)"
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
                                    onchange="setDistrict(this)"
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

                            <div class="form-group" id="customer-image-group">
                                <label for="image-proof">
                                    Proof image
                                </label>
                                <input type="file"
                                    id="proof-image"
                                    name="proof_image[]"
                                    class="proof-image"
                                    accept=".jpg, .jpeg, .png"
                                    multiple
                                    required />
                            </div>
                            <br>
                            <br>
                            <div id="refrensiForm" class="form-group">
                                <h3>Reference Data</h3>
                                <br>
                                @for ($x = 0; $x < 1; $x++)
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
                                    </div>

                                    <div class="form-group">
                                        <label for="member-province-{{ $x }}">
                                            Province
                                        </label>
                                        <select class="form-control changeProvince"
                                            id="member-province-{{ $x }}"
                                            name="province_ref[]"
                                            data-msg="Mohon Pilih Provinsi"
                                            data-targetselect="member-city-{{ $x }}"
                                            onchange="setCity(this)"
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
                                        <label for="member-city-{{ $x }}">
                                            City
                                        </label>
                                        <select class="form-control"
                                            id="member-city-{{ $x }}"
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
                                    </div>

                                    <div class="form-group">
                                        <label for="member-souvenir-{{ $x }}">
                                            Souvenir
                                        </label>
                                        <select class="form-control"
                                            id="member-souvenir-{{ $x }}"
                                            name="souvenir_id[]"
                                            {{ $x > 0 ? "" : "required" }}>
                                            <option selected
                                                disabled
                                                hidden
                                                value="">
                                                Pilih Souvenir
                                            </option>
                                            @foreach ($souvenirs as $souvenir)
                                                <option value="{{ $souvenir->id }}">
                                                    {{ $souvenir->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="link-hs-{{ $x }}">
                                            Home Service
                                        </label>
                                        <input type="hidden"
                                            id="link-hs-{{ $x }}"
                                            name="link_hs[]"
                                            value="" />
                                        <br>
                                        <button class="btn btn-gradient-info"
                                            type="button"
                                            data-toggle="modal"
                                            data-target="#choose-hs">
                                            Choose Home Service
                                        </button>
                                    </div>

                                    <div class="form-group">
                                        <label for="member-order-{{ $x }}">
                                            Order
                                        </label>
                                        <input type="hidden"
                                            id="member-order-{{ $x }}"
                                            name="order_id[]"
                                            value="" />
                                        <br>
                                        <button class="btn btn-gradient-info"
                                            type="button"
                                            data-toggle="modal"
                                            data-target="#choose-order">
                                            Choose Order
                                        </button>
                                    </div>

                                    <div class="form-group">
                                        <label for="member-prize-{{ $x }}">
                                            Prize
                                        </label>
                                        <select class="form-control"
                                            id="member-prize-{{ $x }}"
                                            name="prize_id[]"
                                            {{ $x > 0 ? "" : "required" }}>
                                            <option selected
                                                disabled
                                                hidden
                                                value="">
                                                Choose Prize
                                            </option>
                                            @foreach ($prizes as $prize)
                                                <option value="{{ $prize->id }}">
                                                    {{ $prize->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endfor
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
                        </form>

                    </div>
                </div>
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
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button class="btn btn-gradient-primary"
                    type="button"
                    data-dismiss="modal"
                    aria-label="Close">
                    Ok
                </button>
                <button class="btn btn-gradient-dark"
                    type="button"
                    data-dismiss="modal"
                    aria-label="Close">
                    Close
                </button>
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
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button class="btn btn-gradient-primary"
                    type="button"
                    data-dismiss="modal">
                    Ok
                </button>
                <button class="btn btn-gradient-dark"
                    type="button"
                    data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section("script")
<script type="application/javascript">
const souvenirArray = []
for (let i = 0; i < 10; i++) {
    souvenirArray.push(-1);
};

function validateForm() {
    // This function deals with validation of the form fields
    let valid = true;

    const inputArray = [
        "member-name-",
        "member-age-",
        "member-phone-",
        "member-province-",
        "member-city-",
        "member-souvenir-",
        "link-hs-",
    ];

    inputArray.forEach(function (currentValue) {
        const inputBeingChecked = document.getElementById(currentValue + currentTab);

        if (!inputBeingChecked.checkValidity()) {
            addOrRemoveInvalid(inputBeingChecked, "add");
            valid = false;
        } else {
            addOrRemoveInvalid(inputBeingChecked, "remove");
        }
    });

    const souvenirInput = document.getElementById("member-souvenir-" + currentTab);
    const souvenirValue = parseInt(souvenirInput.value, 10);
    if (souvenirValue) {
        souvenirArray[currentTab] = souvenirValue;
        const findDuplicate = souvenirArray.filter(function (currentValue) {
            return currentValue === souvenirValue;
        });

        if (findDuplicate.length > 2) {
            addOrRemoveInvalid(souvenirInput, "add");
            valid = false;
        } else {
            addOrRemoveInvalid(souvenirInput, "remove");
        }
    }

    // return the valid status
    return valid;
}

function addOrRemoveInvalid(element, command) {
    if (command === "add" && !element.className.includes("invalid")) {
        element.classList.add("invalid");
    } else if (command === "remove" && element.className.includes("invalid")) {
        element.classList.remove("invalid");
    }
}

function requiredAttributeHandler(e) {
    const REF_SEQ = e.id.split("-")[2];
    if (REF_SEQ === 0) {
        return;
    }

    const inputArray = [
        "member-name-",
        "member-age-",
        "member-phone-",
        "member-province-",
        "member-city-",
        "member-souvenir-",
    ];
    let isEmpty = true;

    if (e.value) {
        isEmpty = false;
    } else if (e.value === null || e.value === undefined) {
        inputArray.forEach(function (currentValue) {
            if (document.getElementById(currentValue + REF_SEQ).value === null) {
                isEmpty = false;
            }
        });
    }

    if (!isEmpty) {
        inputArray.forEach(function (currentValue) {
            document.getElementById(currentValue + REF_SEQ).setAttribute("required", "");
        });

        return;
    }

    if (isEmpty) {
        inputArray.forEach(function (currentValue) {
            document.getElementById(currentValue + REF_SEQ).removeAttribute("required");
        });

        return;
    }
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

            document.getElementById(e.dataset.targetselect).innerHTML = arrCity[0] + arrCity[1];
        }
    }).catch(function(error) {
        console.error(error);
    });
}

function setDistrict(e) {
    const URL = '{{ route("fetchDistrict", ['city' => ""]) }}/' + e.value;

    fetch(
        URL,
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

        let optionsDistrict = "";

        if (result.length > 0) {
            result.forEach(function (currentValue) {
                optionsDistrict += '<option value="'
                    + currentValue["subdistrict_id"]
                    + '">'
                    + currentValue['subdistrict_name']
                    + '</option>';
            });

            document.getElementById("subDistrict").innerHTML = optionsDistrict;
        }
    }).catch(function (error) {
        console.error(error);
    })
}

document.addEventListener("DOMContentLoaded", function () {
    $("#cso").on("input", function () {
        check_cso($("#cso").val());
    });

    function check_cso(code) {
        $.get('{{ route("fetchCso") }}', { cso_code: code })
            .done(function (result) {
                if (result['result'] == "true" && result['data'].length > 0) {
                    $('#validation_cso').html('Kode CSO Benar');
                    $('#validation_cso').css('color', 'green');
                    $('#submit').removeAttr('disabled');
                } else {
                    $('#validation_cso').html('Kode CSO Salah');
                    $('#validation_cso').css('color', 'red');
                    $('#submit').attr('disabled', "");
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
        if ($("#type_register").val() === "referensi") {
            const numberOfImage = parseInt($("#proof-image").get(0).files.length);

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