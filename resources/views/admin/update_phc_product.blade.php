<?php
$menu_item_page = "personal_homecare";
$menu_item_second = "update_product";
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
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Update Personal Homecare Product</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            aria-expanded="false">
                            Personal Homecare
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Update Product
                    </li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form id="add-phc-product"
                            method="POST"
                            action="{{ route("update_phc_product") }}">
                            @csrf
                            <input type="hidden"
                                id="id"
                                name="id"
                                value="{{ $phcproducts->id }}" 
                                required />
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
                                    <option disabled {{ substr($phcproducts->code, 1, 1) == "1" ? 'selected' : '' }} value="1"
                                        data-code="1">
                                        Surabaya
                                    </option>
                                    <option disabled {{ substr($phcproducts->code, 1, 1) == "2" ? 'selected' : '' }} value="2"
                                        data-code="2">
                                        Semarang
                                    </option>
                                    <option disabled {{ substr($phcproducts->code, 1, 1) == "3" ? 'selected' : '' }} value="3"
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
                                        @if ($phcproducts->branch_id == $branch->id)
                                            <option value="{{ $branch->id }}"
                                                data-code="{{ $branch->code }}"
                                                selected>
                                                {{ $branch->code }} - {{ $branch->name }}
                                            </option>
                                        @else
                                            <option value="{{ $branch->id }}"
                                                data-code="{{ $branch->code }}">
                                                {{ $branch->code }} - {{ $branch->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="branch_id">Product</label>
                                <select class="form-control"
                                    id="product_id"
                                    name="product_id"
                                    onchange="selectProduct(this)">
                                    <option disabled selected>
                                        Select Product
                                    </option>
                                    @foreach ($products as $product)
                                        @if ($phcproducts->product_id == $product->id)
                                            <option value="{{ $product->id }}"
                                                data-code="{{ $product->code }}"
                                                selected disabled>
                                                {{ $product->code }} - {{ $product->name }}
                                            </option>
                                        @else
                                            <option value="{{ $product->id }}"
                                                data-code="{{ $product->code }}" disabled>
                                                {{ $product->code }} - {{ $product->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="code-increment">
                                    Code (please select the branch & product first)
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="code-suffix">{{ substr($phcproducts->code, 0, 3) }}</span>
                                    </div>
                                    <input type="text"
                                        class="form-control"
                                        name="code-increment"
                                        id="code-increment"
                                        pattern="[0-9]*"
                                        inputmode="numeric"
                                        placeholder="Number"
                                        oninput="setCode()"
                                        value="{{ substr($phcproducts->code, 3, 3) }}" 
                                        disabled
                                        required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control"
                                    id="status"
                                    name="status">
                                    <option value="0" {{ $phcproducts->status ? 'selected' : '' }}>Not Available</option>
                                    <option value="1" {{ $phcproducts->status ? 'selected' : '' }}>
                                        Available
                                    </option>
                                </select>
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
    $("#branch_id").select2();
    $("#product_id").select2();
});

function getSelectedCode(e) {
    return e.options[e.selectedIndex].dataset.code;
}

function selectWarehouse(e) {
    setCodeSuffix();
}

function selectBranch(e) {
    document.getElementById("branch-code").value = getSelectedCode(e);
}

function selectProduct(e) {
    document.getElementById("product-code").value = getSelectedCode(e);
    setCodeSuffix();
}

function setCodeSuffix() {
    let productCode = document.getElementById("product-code").value;
    const warehouse_id = document.getElementById("warehouse_id").value;
    // const branchCode = document.getElementById("branch-code").value;

    if(productCode == "WK2079"){
        productCode = "A";
    }
    else if(productCode == "WK2076H"){
        productCode = "B";
    }
    else if(productCode == "WK2076i"){
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
    else if(productCode == "WK2076H"){
        productCode = "B";
    }
    else if(productCode == "WK2076i"){
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
    }

    document.getElementById("code").value = `${productCode}${warehouse_id}_${increment}`;
}
</script>
@endsection
