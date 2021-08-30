<?php
$menu_item_page = "personal_homecare_product";
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
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form id="add-phc-product"
                            method="POST"
                            action="{{ route("store_phc_product") }}">
                            @csrf
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
                                    Code (please select the branch & product first)
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
                                        required />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control"
                                    id="status"
                                    name="status">
                                    <option value="0">Not Available</option>
                                    <option value="1" selected>
                                        Available
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit"
                                    class="btn btn-gradient-primary">
                                    Save
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

function selectBranch(e) {
    document.getElementById("branch-code").value = getSelectedCode(e);
    setCodeSuffix();
}

function selectProduct(e) {
    document.getElementById("product-code").value = getSelectedCode(e);
    setCodeSuffix();
}

function setCodeSuffix() {
    const productCode = document.getElementById("product-code").value;
    const branchCode = document.getElementById("branch-code").value;

    document.getElementById("code-suffix").innerHTML = `${productCode}${branchCode}`;

    if (productCode && branchCode) {
        document.getElementById("code-increment").removeAttribute("disabled");
        getIncrementSuggestion(`${productCode}${branchCode}`);
    }
}

function getIncrementSuggestion(code) {
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
    const productCode = document.getElementById("product-code").value;
    const branchCode = document.getElementById("branch-code").value;
    const increment = document.getElementById("code-increment").value;

    document.getElementById("code").value = `${productCode}${branchCode}${increment}`;
}
</script>
@endsection
