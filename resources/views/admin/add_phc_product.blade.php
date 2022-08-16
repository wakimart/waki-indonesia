<?php
$menu_item_page = "personal_homecare";
$menu_item_second = "add_phc_product";
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
            <h3 class="page-title">Add Personal Homecare Product</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            aria-expanded="false">
                            Personal Homecare Product
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Add Product
                    </li>
                </ol>
            </nav>
        </div>

        <form id="add-phc-product"
            method="POST"
            action="{{ route("store_phc_product") }}">
            @csrf

            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h2>Product Detail</h2>
                            <input type="hidden"
                                id="branch-code"
                                name="branch_code"
                                required />
                            <input type="hidden"
                                id="product-code"
                                name="product_code"
                                required />
                            <input type="hidden"
                                name="code"
                                id="code"
                                required />
                            <div class="form-group">
                                <label for="warehouse_id">Warehouse</label>
                                <select class="form-control"
                                    id="warehouse_id"
                                    name="warehouse_id"
                                    onchange="selectWarehouse(this)" required="">
                                    <option disabled selected>
                                        Select Warehouse
                                    </option>
                                    <option value="1"
                                        data-code="1">
                                        Surabaya
                                    </option>
                                    <option value="2"
                                        data-code="2">
                                        Semarang
                                    </option>
                                    <option value="3"
                                        data-code="3">
                                        Jakarta
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="branch_id">Branch</label>
                                <select class="form-control"
                                    id="branch_id"
                                    name="branch_id"
                                    onchange="selectBranch(this)">
                                    <option disabled selected>
                                        Select Branch
                                    </option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            data-code="{{ $branch->code }}">
                                            {{ $branch->code }} - {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="product_id">Product</label>
                                <select class="form-control"
                                    id="product_id"
                                    name="product_id"
                                    onchange="selectProduct(this)">
                                    <option disabled selected>
                                        Select Product
                                    </option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            data-code="{{ $product->code }}">
                                            {{ $product->code }} - {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="code-increment">
                                    Code (please select the warehouse & product first)
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="code-suffix"></span>
                                    </div>
                                    <input type="text"
                                        class="form-control"
                                        name="code-increment"
                                        id="code-increment"
                                        pattern="[0-9]*"
                                        inputmode="numeric"
                                        placeholder="Number"
                                        oninput="setCode()"
                                        disabled
                                        readonly
                                        required />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h2>Product Checklist</h2>

                            <div class="form-group">
                                <span style="display: block;">Completeness</span>
                                <div class="div-CheckboxGroup">

                                    <div id="checklist_prod"></div>

                                    <div class="form-check">
                                        <label for="completeness-other"
                                            class="form-check-label">
                                            <input type="checkbox"
                                                name="completeness[]"
                                                id="completeness-other"
                                                value="other"
                                                onchange="showOtherInput(this)" />
                                            Other
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <input type="text"
                                            class="form-control d-none"
                                            placeholder="Other description"
                                            name="other_completeness"
                                            id="other-text"/>
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
                                                value="normal"/>
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
                                                value="need_repair"/>
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
                                                value="new"/>
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
                                                value="moderate"/>
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
                                                value="need_repair"/>
                                            Need Repair
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit"
                                    class="btn btn-gradient-primary">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>

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

const arr_checklistProd = {!! json_encode( App\PersonalHomecareChecklist::$completeness_list, true ) !!};

document.addEventListener("DOMContentLoaded", function () {
    $("#branch_id").select2();
    $("#product_id").select2();
});

function getSelectedCode(e) {
    return e.options[e.selectedIndex].dataset.code;
}

function selectBranch(e) {
    document.getElementById("branch-code").value = getSelectedCode(e);
    // setCodeSuffix();
}

function selectWarehouse(e) {
    setCodeSuffix();
}

function selectProduct(e) {
    document.getElementById("product-code").value = getSelectedCode(e);
    setCodeSuffix();
    setChecklist(getSelectedCode(e));
}

function setChecklist(code) {
    var firstLetter = "";

    if(code == "WK2079"){
        firstLetter = "A";
    }
    else if(code == "WKT2076H"){
        firstLetter = "B";
    }
    else if(code == "WKT2076i"){
        firstLetter = "C";
    }
    else if(code == "WKT2080"){
        firstLetter = "D";
    }
    else if(code == "WKA2023"){
        firstLetter = "E";
    }
    else if(code == "WKA2024"){
        firstLetter = "F";
    } else if (code === "WKT2090") {
        firstLetter = "G";
    }

    document.getElementById("checklist_prod").innerHTML = "";
    if(typeof arr_checklistProd[firstLetter] != 'undefined'){
        arr_checklistProd[firstLetter].forEach(function (item, index){
            $("#checklist_prod").append("<div class=\"form-check\">"
                                            +"<label for=\"completeness-"+item+"\""
                                            +"class=\"form-check-label\">"
                                            +"<input type=\"checkbox\""
                                                +"name=\"completeness[]\""
                                                +"id=\"completeness-"+item+"\""
                                                +"value=\""+item+"\"/>"
                                            +item+"<i class=\"input-helper\"></i>"
                                        +"</label>"
                                    +"</div>");
        });
    }

}

function setCodeSuffix() {
    let productCode = document.getElementById("product-code").value;
    const warehouse_id = document.getElementById("warehouse_id").value;
    // const branchCode = document.getElementById("branch-code").value;

    if(productCode == "WK2079"){
        productCode = "A";
    }
    else if(productCode == "WKT2076H"){
        productCode = "B";
    }
    else if(productCode == "WKT2076i"){
        productCode = "C";
    }
    else if(productCode == "WKT2080"){
        productCode = "D";
    }
    else if(productCode == "WKA2023"){
        productCode = "E";
    }
    else if(productCode == "WKA2024"){
        productCode = "F";
    } else if (productCode === "WKT2090") {
        productCode = "G";
    }
    else{
        productCode = "-";
    }

    document.getElementById("code-suffix").innerHTML = `${productCode}${warehouse_id}_`;

    if (productCode && warehouse_id) {
        document.getElementById("code-increment").removeAttribute("disabled");
        getIncrementSuggestion(`${productCode}${warehouse_id}_`);
    }
}

function getIncrementSuggestion(code) {
    console.log(code);
    fetch(
        `{{ route("get_phc_product_increment") }}/?code=${code}`,
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
        const data = response.data.toString();
        document.getElementById("code-increment").value = data.padStart(3, "0");
        setCode();
    }).catch(function (error) {
        console.error(error);
    });
}

function setCode() {
    // const productCode = document.getElementById("product-code").value;
    // const branchCode = document.getElementById("branch-code").value;

    let productCode = document.getElementById("product-code").value;
    const warehouse_id = document.getElementById("warehouse_id").value;
    const increment = document.getElementById("code-increment").value;

    if(productCode == "WK2079"){
        productCode = "A";
    }
    else if(productCode == "WKT2076H"){
        productCode = "B";
    }
    else if(productCode == "WKT2076i"){
        productCode = "C";
    }
    else if(productCode == "WKT2080"){
        productCode = "D";
    }
    else if(productCode == "WKA2023"){
        productCode = "E";
    }
    else if(productCode == "WKA2024"){
        productCode = "F";
    } else if (productCode === "WKT2090") {
        productCode = "G";
    }

    document.getElementById("code").value = `${productCode}${warehouse_id}_${increment}`;
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
