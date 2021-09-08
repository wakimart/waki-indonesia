<?php
$menu_item_page = "history_stock";
$menu_item_second = "add_history_stock";
?>
@extends("admin.layouts.template")

@section("style")
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
            <h3 class="page-title">Add History Stock</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            aria-expanded="false">
                            History Stock
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
                        <form method="POST"
                            action="{{ route("store_history_stock") }}">
                            @csrf

                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text"
                                    class="form-control"
                                    name="code"
                                    id="code"
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date"
                                    class="form-control"
                                    name="date"
                                    id="date"
                                    value="{{ date("Y-m-d") }}"
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="warehouse_id">Warehouse</label>
                                <select class="form-control"
                                    name="warehouse_id"
                                    id="warehouse_id"
                                    required>
                                    <option disabled selected>
                                        Select Warehouse
                                    </option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">
                                            {{ $warehouse->code }} - {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="type">Type</label>
                                <select class="form-control"
                                    id="type"
                                    name="type"
                                    required>
                                    <option disabled selected>
                                        Select Type
                                    </option>
                                    <option value="in">In</option>
                                    <option value="out">Out</option>
                                </select>
                            </div>

                            <div id="product-container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="text-center"
                                            style="display: block;
                                                background: #4caf3ab3;
                                                float: right;
                                                margin-bottom: 20px;">
                                            <button class="btn btn-gradient-primary"
                                                id="tambah_product"
                                                onclick="addProduct()"
                                                type="button">
                                                Add Product
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden"
                                    id="product-counter"
                                    value="0" />

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="stock">Product</label>
                                            <select class="form-control"
                                                name="product[]"
                                                id="product_0"
                                                required>
                                                <option disabled selected>
                                                    Select Product
                                                </option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">
                                                        {{ $product->code }} - {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="quantity">Quantity</label>
                                            <input id="quantity_0"
                                                class="form-control"
                                                type="number"
                                                name="quantity[]"
                                                placeholder="Quantity"
                                                min="0"
                                                required />
                                        </div>
                                    </div>
                                </div>
                                <div id="tambahan_product"></div>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description"
                                    class="form-control"
                                    id="description"
                                    rows="2"
                                    placeholder="Description"
                                    maxlength="300"></textarea>
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
let productOptions = "<option disabled selected>Select Product</option>";

document.addEventListener("DOMContentLoaded", function() {
    getProduct();
    $("#warehouse_id").select2();
    $("#product_0").select2();
    $("#type").select2();
});

function getProduct() {
    fetch(
        '{{ route("history_stock_get_product") }}',
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
        response.data.forEach(function (value) {
            productOptions += `<option value="${value.id}">${value.code} - ${value.name}</option>`;
        });
    }).catch(function (error) {
        console.error(error);
    })
}

function addProduct() {
    const counter = parseInt(document.getElementById("product-counter").value) + 1;

    // Remove Button
    const rowDivRemove = document.createElement("div");
    rowDivRemove.className = "row";
    rowDivRemove.id = `row-remove-${counter}`;

    const colMd12DivRemove = document.createElement("div");
    colMd12DivRemove.className = "col-md-12";

    const textCenterDivRemove = document.createElement("div");
    textCenterDivRemove.className = "text-center";
    textCenterDivRemove.style = "display: block; background: #4caf3ab3; float: right; margin-bottom: 20px;";

    const buttonRemove = document.createElement("button");
    buttonRemove.className = "btn btn-gradient-danger";
    buttonRemove.type = "button";
    buttonRemove.innerHTML = "Remove Product";
    buttonRemove.setAttribute("onclick", `removeProduct(${counter})`);

    textCenterDivRemove.appendChild(buttonRemove);
    colMd12DivRemove.appendChild(textCenterDivRemove);
    rowDivRemove.appendChild(colMd12DivRemove);
    document.getElementById("tambahan_product").appendChild(rowDivRemove);

    // Product Dropdown
    const rowDivProduct = document.createElement("div");
    rowDivProduct.className = "row";
    rowDivProduct.id = `row-product-${counter}`;

    const colMd8DivProduct = document.createElement("div");
    colMd8DivProduct.className = "col-md-8";

    const formGroupDivProduct = document.createElement("div");
    formGroupDivProduct.className = "form-group";

    const labelProduct = document.createElement("label");
    labelProduct.innerHTML = "Product";
    labelProduct.setAttribute("for", `product_${counter}`);

    const selectProduct = document.createElement("select");
    selectProduct.className = "form-control";
    selectProduct.name = "product[]";
    selectProduct.id = `product_${counter}`;
    selectProduct.required = true;
    selectProduct.innerHTML += productOptions;

    formGroupDivProduct.appendChild(labelProduct);
    formGroupDivProduct.appendChild(selectProduct);
    colMd8DivProduct.appendChild(formGroupDivProduct);
    rowDivProduct.appendChild(colMd8DivProduct);

    // Quantity Input
    const colMd4DivQuantity = document.createElement("div");
    colMd4DivQuantity.className = "col-md-4";

    const formGroupDivQuantity = document.createElement("div");
    formGroupDivQuantity.className = "form-group";

    const labelQuantity = document.createElement("label");
    labelQuantity.innerHTML = "Quantity";
    labelQuantity.setAttribute("for", `quantity_${counter}`);

    const inputQuantity = document.createElement("input");
    inputQuantity.className = "form-control";
    inputQuantity.type = "number";
    inputQuantity.name = "quantity[]";
    inputQuantity.id = `quantity_${counter}`;
    inputQuantity.placeholder = "Quantity";
    inputQuantity.required = true;
    inputQuantity.min = 0;

    formGroupDivQuantity.appendChild(labelQuantity);
    formGroupDivQuantity.appendChild(inputQuantity);
    colMd4DivQuantity.appendChild(formGroupDivQuantity);
    rowDivProduct.appendChild(colMd4DivQuantity);

    document.getElementById("tambahan_product").appendChild(rowDivProduct);
    document.getElementById("product-counter").value = counter;
    $("#product_" + counter).select2();
}

function removeProduct(counter) {
    document.getElementById(`row-remove-${counter}`).remove();
    document.getElementById(`row-product-${counter}`).remove();
}
</script>
@endsection
