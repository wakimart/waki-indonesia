<?php
$menu_item_page = "public_homecare";
$menu_item_second = "add_public_homecare";
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
            <h3 class="page-title">Add Public Homecare</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            aria-expanded="false">
                            Public Homecare
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
                            form="add-puhc" />

                        <div class="form-group">
                            <label for="branch_id">Branch</label>
                            <select class="form-control"
                                id="branch_id"
                                name="branch_id"
                                form="add-puhc"
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
                            form="add-puhc" />

                        <div class="form-group">
                            <label for="cso_id">CSO</label>
                            <select  class="form-control"
                                name="cso_id"
                                id="cso_id"
                                form="add-puhc">
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

                        <div class="form-group">
                            <label for="cso_optional_id">CSO 2 (Optional)</label>
                            <select  class="form-control"
                                name="cso_optional_id"
                                id="cso_optional_id"
                                form="add-puhc">
                                <option value="" selected>
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
                        
                        <form id="add-puhc"
                            method="POST"
                            enctype="multipart/form-data"
                            action="{{ route("store_public_homecare") }}">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="start_date">Start Date</label>
                                    <input type="date"
                                        class="form-control"
                                        name="start_date"
                                        id="start_date"
                                        onchange="setProduct()"
                                        required />
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="end_date">End Date</label>
                                    <input type="date"
                                        class="form-control"
                                        name="end_date"
                                        id="end_date"
                                        onchange="setProduct()"
                                        required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name">Institute Name</label>
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
                                    name="district_id"
                                    id="subdistrict_id"
                                    required>
                                    <option disabled selected>
                                        Select Subdistrict
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="approval_letter">
                                    Approval Letter
                                </label>
                                <input type="file"
                                    class="form-control"
                                    accept="image/jpeg, image/png"
                                    name="approval_letter"
                                    id="approval_letter"
                                    required />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h2>Product Public Homecare</h2>

                        <div class="row" id="product_parent_0">
                            <input type="hidden" form="add-puhc" name="product_idx[]" value="0">
                            <div class="form-group col-md-10">
                                <label for="ph_product_id_0">Product 1</label>
                                <select class="form-control ph_product_id"
                                    form="add-puhc"
                                    name="ph_product_id_0"
                                    id="ph_product_id_0"
                                    required>
                                    <option disabled selected>
                                        Select Product (Please select branch & schedule first)
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <button id="tambah_product" style="position: absolute; bottom: 0;">
                                    <i class="mdi mdi-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div id="tambahan_product"></div>
                        {{-- ++++++++++++++ ======== ++++++++++++++ --}}

                        <div class="form-group">
                            <label for="other_product">Other Product</label>
                            <textarea class="form-control"
                                form="add-puhc"
                                id="other_product" 
                                name="other_product" 
                                rows="5" required></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit"
            id="submit-button"
            form="add-puhc"
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

    $("#ph_product_id_0").select2();
    $("#province_id").select2();
    $("#city_id").select2();
    $("#subdistrict_id").select2();
    $("#branch_id").select2();
    $("#cso_id").select2();
    $("#cso_optional_id").select2();
});

var product_options = `<option disabled selected>Select Product (Please select branch & schedule first)</option>`;
function setProduct() {
    let start_date = $("#start_date").val();
    let end_date = $("#end_date").val();

    let branch_id = $("#branch_id").val();
    // console.log({date, branch_id});
    if(start_date != ""  && end_date != "" && branch_id != ""){
        fetch(
            '{{ route("get_puhc_product") }}?branch_id=' + branch_id + '&start_date=' + start_date + '&end_date=' + end_date,
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
            $(".ph_product_id").select2("destroy");

            const data = response.data;

            product_options = '';
            product_options += "<option disabled selected>Select Product (Please select branch & schedule first)</option>";

            data.forEach(function (value) {
                product_options +=  "<option value='"+value.id+"'>"+
                    value.code + " - " + value.name + 
                    "</option>";
            });

            $(".ph_product_id").html(product_options)
            $('.ph_product_id').attr('disabled', false);

            $(".ph_product_id").select2();
        }).catch(function(error) {
            console.error(error);
        });
    }
}

var product_idx = 1;
$(document).on('click', '#tambah_product', function() {
    const tambah_product = `
        <div class="row" id="product_parent_`+product_idx+`">
            <input type="hidden" form="add-puhc" name="product_idx[]" value="`+product_idx+`">
            <div class="form-group col-md-10">
                <label for="ph_product_id_`+product_idx+`">Product `+(product_idx + 1)+`</label>
                <select class="form-control ph_product_id"
                    form="add-puhc"
                    name="ph_product_id_`+product_idx+`"
                    id="ph_product_id_`+product_idx+`"
                    required>`+product_options+`
                </select>
            </div>
            <div class="form-group col-md-2">
                <button style="position: absolute; bottom: 0; background-color: red;" class="hapus_product"
                    value="`+product_idx+`">
                    <i class="mdi mdi-minus"></i>
                </button>
            </div>
        </div>`;
    $("#tambahan_product").append(tambah_product);
    $("#ph_product_id_"+product_idx).select2();
    product_idx++;
});

$(document).on('click', '.hapus_product', function() {
    $("#product_parent_"+$(this).val()).remove();
});

function checkPhone(e) {
    if (!e.value) {
        document.getElementById("phone-message").classList.remove("text-success");
        document.getElementById("phone-message").classList.add("text-danger");
        document.getElementById("submit-button").setAttribute("disabled", "");
        document.getElementById("phone-message").innerHTML = "Please input phone number.";
        return;
    }

    fetch(
        '{{ route("check_puhc_phone") }}?phone=' + e.value,
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
</script>
@endsection
