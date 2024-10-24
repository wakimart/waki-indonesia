<?php
$menu_item_page = "personal_homecare";
$menu_item_second = "add_personal_homecare";
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
            <h3 class="page-title">Add Personal Homecare</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            aria-expanded="false">
                            Personal Homecare
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Add
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h2>Agent Data</h2>

                        <input type="hidden"
                            id="branch-id-hidden"
                            form="add-phc" />

                        <div class="form-group">
                            <label for="branch_id">Branch</label>
                            <select class="form-control"
                                id="branch_id"
                                name="branch_id"
                                form="add-phc"
                                onchange="setProduct()"
                                required>
                                <option disabled selected>
                                    Select Branch
                                </option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">
                                        {{ $branch->code }} - {{ $branch->name }}
                                    </option>
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
                                form="add-phc">
                                <option disabled>
                                    Select CSO
                                </option>
                                @foreach ($csos as $cso)
                                    <option value="{{ $cso->id }}">
                                        {{ $cso->code }} - {{ $cso->name }}
                                    </option>
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
                            action="{{ route("store_personal_homecare") }}">
                            @csrf
                            <div class="form-group">
                                <label for="schedule">Schedule Date</label>
                                <input type="date"
                                    class="form-control"
                                    name="schedule"
                                    id="schedule"
                                    onchange="setProduct()"
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="ph_product_id">Product</label>
                                <select class="form-control"
                                    name="ph_product_id"
                                    id="ph_product_id"
                                    required>
                                    <option disabled selected>
                                        Select Product (Please select branch & schedule first)
                                    </option>
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
                                    onblur="checkPhone(this)"
                                    required />
                                <small id="phone-message" class="form-text"></small>
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
                                <label for="id_card_image">
                                    Customer ID Card
                                </label>
                                <input type="file"
                                    class="form-control"
                                    accept="image/jpeg, image/png"
                                    name="id_card_image"
                                    id="id_card_image"
                                    required />
                            </div>
                            <div class="form-group">
                                <label for="member_wakimart_image">
                                    Customer Wakimart Member
                                </label>
                                <input type="file"
                                    class="form-control"
                                    accept="image/jpeg, image/png"
                                    name="member_wakimart_image"
                                    id="member_wakimart_image"
                                    required />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" hidden="">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h2>Product Checklist</h2>

                        <div class="form-group">
                            <span style="display: block;">Completeness</span>
                            <div class="div-CheckboxGroup">
                                <div class="form-check">
                                    <label for="completeness-machine"
                                        class="form-check-label">
                                        <input type="checkbox"
                                            name="completeness[]"
                                            id="completeness-machine"
                                            value="machine"
                                            form="add-phc" />
                                        Machine
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label for="completeness-filter"
                                        class="form-check-label">
                                        <input type="checkbox"
                                            name="completeness[]"
                                            id="completeness-filter"
                                            value="filter"
                                            form="add-phc" />
                                        Filter
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label for="completeness-accessories"
                                        class="form-check-label">
                                        <input type="checkbox"
                                            name="completeness[]"
                                            id="completeness-accessories"
                                            value="accessories"
                                            form="add-phc" />
                                        Accessories
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label for="completeness-cable"
                                        class="form-check-label">
                                        <input type="checkbox"
                                            name="completeness[]"
                                            id="completeness-cable"
                                            value="cable"
                                            form="add-phc" />
                                        Cable
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label for="completeness-other"
                                        class="form-check-label">
                                        <input type="checkbox"
                                            name="completeness[]"
                                            id="completeness-other"
                                            value="other"
                                            form="add-phc"
                                            onchange="showOtherInput(this)" />
                                        Other
                                    </label>
                                </div>
                                <div class="form-group">
                                    <input type="text"
                                        class="form-control d-none"
                                        placeholder="Other description"
                                        name="other_completeness"
                                        id="other-text"
                                        form="add-phc" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <span style="display: block;">Machine Condition</span>
                            <div class="div-CheckboxGroup">
                                <div class="form-check">
                                    <label for="machine-condition-normal"
                                        class="form-check-label">
                                        <input type="radio"
                                            class="form-check-input"
                                            name="machine_condition"
                                            id="machine-condition-normal"
                                            value="normal"
                                            form="add-phc" />
                                        Normal
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label for="machine-condition-need-repair"
                                        class="form-check-label">
                                        <input type="radio"
                                            class="form-check-input"
                                            name="machine_condition"
                                            id="machine-condition-need-repair"
                                            value="need_repair"
                                            form="add-phc" />
                                        Need Repair
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <span style="display: block;">Physical Condition</span>
                            <div class="div-CheckboxGroup">
                                <div class="form-check">
                                    <label for="physical-condition-new"
                                        class="form-check-label">
                                        <input type="radio"
                                            class="form-check-input"
                                            name="physical_condition"
                                            id="physical-condition-new"
                                            value="new"
                                            form="add-phc" />
                                        New
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label for="physical-condition-moderate"
                                        class="form-check-label">
                                        <input type="radio"
                                            class="form-check-input"
                                            name="physical_condition"
                                            id="physical-condition-moderate"
                                            value="moderate"
                                            form="add-phc" />
                                        Moderate
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label for="physical-condition-need-repair"
                                        class="form-check-label">
                                        <input type="radio"
                                            class="form-check-input"
                                            name="physical_condition"
                                            id="physical-condition-need-repair"
                                            value="need_repair"
                                            form="add-phc" />
                                        Need Repair
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="product-photo-1">Product Photo</label>
                            <input type="file"
                                class="form-control"
                                accept="image/jpeg, image/png"
                                name="product_photo_1"
                                id="product-photo-1"
                                form="add-phc" />
                        </div>

                        <div class="form-group">
                            <label for="product-photo-2">
                                Product Photo with CSO and Customer (Optional)
                            </label>
                            <input type="file"
                                class="form-control"
                                accept="image/jpeg, image/png"
                                name="product_photo_2"
                                id="product-photo-2"
                                form="add-phc" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h2>Home Service Appointment</h2>

                        @for($hs = 0; $hs < 5; $hs++)
                        <label>Appointment {{$hs + 1}}</label>
                        <div class="form-group">
                            <label for="">Date</label>
                            <input type="date"
                                form="add-phc"
                                class="form-control"
                                name="date[]"
                                id="date-{{$hs}}"
                                placeholder="Tanggal Janjian"
                                required
                                value="<?php echo date('Y-m-j'); ?>"
                                onload="setMinAppointmentTime(this)"
                                data-msg="Mohon Isi Tanggal" readonly/>
                            <div class="validation"></div>
                            <span class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="">Time</label>
                            <input type="time"
                                form="add-phc"
                                class="form-control"
                                name="time[]"
                                id="time-{{$hs}}"
                                placeholder="Jam Janjian"
                                value="<?php echo date('H:i'); ?>"
                                required
                                data-msg="Mohon Isi Jam"
                                min="10:00"
                                max="20:00" />
                            <div class="validation"></div>
                            <span class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <button type="submit"
            id="submit-button"
            form="add-phc"
            class="btn btn-gradient-primary">
            Submit
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

    const branchId = '{{ Auth::user()->roles[0]["slug"] === "branch" ? Auth::user()->listBranches()[0]['id'] : "" }}';
    if (branchId !== "") {
        document.getElementById("branch_id").value = branchId;
        document.getElementById("branch_id").disabled = true;
        document.getElementById("branch_id").removeAttribute("name");
        document.getElementById("branch-id-hidden").value = branchId;
        document.getElementById("branch-id-hidden").setAttribute("name", "branch_id");

        setProduct();
    }

    $("#ph_product_id").select2();
    $("#province_id").select2();
    $("#city_id").select2();
    $("#subdistrict_id").select2();
    $("#branch_id").select2();
    $("#cso_id").select2();
});

function setMinAppointmentTime(e) {
    // Tanggal & waktu dari inputan
    const getCurrentDate = new Date(Date.parse(e.value));

    // Tanggal & waktu hari ini
    const today = new Date();

    if (
        getCurrentDate.getFullYear() === today.getFullYear()
        && getCurrentDate.getMonth() === today.getMonth()
        && getCurrentDate.getDate() === today.getDate()
    ) {
        if (today.getHours() < 10) {
            document.getElementById("time").setAttribute("min", "10:00");
        } else if (today.getHours() >= 10 && today.getHours() < 20) {
            document.getElementById("time").setAttribute(
                "min",
                ("0" + (getCurrentDate.getHours() + 1)).slice(-2)
                + ":"
                + ("0" + (getCurrentDate.getMinutes() + 1)).slice(-2)
            );
        } else if (today.getHours() >= 20) {
            document.getElementById("time").setAttribute("disabled", "");
        }
    } else {
        document.getElementById("time").setAttribute("min", "10:00");
    }
}

function setProduct() {
    let date = $("#schedule").val();

    //set for appointment hs
    if(date != ""){
        var selected_date = new Date(date);
        var next_selected_date = new Date (selected_date);
        for (var hs = 0; hs < 5; hs++) {
            if(hs != 0){
                next_selected_date.setDate(selected_date.getDate() + 1);
                selected_date.setDate(next_selected_date.getDate());
            }

            var hs_date = next_selected_date.getDate();
            //console.log(hs_date.length);
            if(hs_date < 10){
                hs_date = '0' + hs_date;
            }
            var hs_month = next_selected_date.getMonth() + 1;
            if(hs_month < 10){
                hs_month = '0' + hs_month;
            }
            var hs_year = next_selected_date.getFullYear();
            var fixed_date_hs =  hs_year + "-" + hs_month + "-" + hs_date;
            console.log(fixed_date_hs);
            document.getElementById("date-" + hs).value = fixed_date_hs;
        }
    }
    //end set for appointment hs


    let branch_id = $("#branch_id").val();
    console.log({date, branch_id});
    if(date != "" && branch_id != ""){
        fetch(
            '{{ route("get_phc_product") }}?branch_id=' + branch_id + '&date=' + date,
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
            document.getElementById("ph_product_id").innerHTML = "<option disabled selected>Select Product (Please select branch & schedule first)</option>";

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
}

function checkPhone(e) {
    if (!e.value) {
        document.getElementById("phone-message").classList.remove("text-success");
        document.getElementById("phone-message").classList.add("text-danger");
        document.getElementById("submit-button").setAttribute("disabled", "");
        document.getElementById("phone-message").innerHTML = "Please input phone number.";
        return;
    }

    fetch(
        '{{ route("check_phc_phone") }}?phone=' + e.value,
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
        console.log(response);
        if (response.result === 0) {
            document.getElementById("phone-message").classList.remove("text-success");
            document.getElementById("phone-message").classList.add("text-danger");
            document.getElementById("submit-button").setAttribute("disabled", "");
            document.getElementById("phone-message").innerHTML = response.data;
        } else if (response.result === 1) {
            document.getElementById("phone-message").classList.remove("text-danger");
            document.getElementById("phone-message").classList.add("text-success");
            document.getElementById("submit-button").removeAttribute("disabled");
            document.getElementById("phone-message").innerHTML = response.data;
        }
    }).catch(function (error) {
        console.error(error);
    })
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
