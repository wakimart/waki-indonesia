<?php
$menu_item_page = "history_stock";
$menu_item_second = "add_history_out";
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
            <h3 class="page-title">Add History Stock Out</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            aria-expanded="false">
                            History Stock
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Add Out
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form id="actionAdd" method="POST"
                            action="{{ route("store_history_stock") }}">
                            @csrf

                            <div class="form-group">
                                <label>Type</label>
                                <input type="hidden"
                                    id="type"
                                    name="type"
                                    value="out" />
                                <input class="form-control"
                                    type="text"
                                    readonly
                                    disabled
                                    value="OUT" />
                            </div>

                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text"
                                    class="form-control"
                                    name="code"
                                    id="code"
                                    required />
                                <span class="invalid-feedback"><strong></strong></span>
                            </div>

                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date"
                                    class="form-control"
                                    name="date"
                                    id="date"
                                    value="{{ date("Y-m-d") }}"
                                    required />
                                <span class="invalid-feedback"><strong></strong></span>
                            </div>

                            <input type="hidden" id="from_warehouse_id-disabled" name="from_warehouse_id" value="">
                            <input type="hidden" id="to_warehouse_id-disabled" name="to_warehouse_id" value="">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="from_warehouse_id">From Warehouse</label>
                                    <select class="form-control"
                                        name="from_warehouse_id"
                                        id="from_warehouse_id"
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
                                    <span class="invalid-feedback"><strong></strong></span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="to_warehouse_id">To Warehouse</label>
                                    <select class="form-control"
                                        name="to_warehouse_id"
                                        id="to_warehouse_id"
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
                                    <span class="invalid-feedback"><strong></strong></span>
                                </div>
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
                                            <label for="product_0">
                                                Product
                                            </label>
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
                                            <span class="invalid-feedback"><strong></strong></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="quantity_0">
                                                Quantity
                                            </label>
                                            <input id="quantity_0"
                                                class="form-control"
                                                type="number"
                                                name="quantity[]"
                                                placeholder="Quantity"
                                                min="0"
                                                oninput="checkStock(this)"
                                                required />
                                            <small id="alert-quantity_0"
                                                class="form-text text-danger"></small>
                                            <span class="invalid-feedback"><strong></strong></span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="koli_0">
                                                Koli
                                            </label>
                                            <input id="koli_0"
                                                class="form-control"
                                                type="text"
                                                name="koli[]"
                                                placeholder="Koli"
                                                required />
                                            <span class="invalid-feedback"><strong></strong></span>
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
                                <span class="invalid-feedback"><strong></strong></span>
                            </div>

                            <div class="form-group">
                                <button type="submit"
                                    class="btn btn-gradient-primary"
                                    id="button-submit">
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
    $("#from_warehouse_id").select2();
    $("#to_warehouse_id").select2();
    $("#warehouse_id").select2();
    $("#product_0").select2();
    // $("#type").select2();
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
    textCenterDivRemove.style = "display: block; float: right; margin-bottom: 20px;";

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
    colMd4DivQuantity.className = "col-md-2";

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
    inputQuantity.min = 0;
    inputQuantity.required = true;
    inputQuantity.setAttribute("oninput", `checkStock(this)`);

    const smallAlertQuantity = document.createElement("small");
    smallAlertQuantity.id = `alert-quantity_${counter}`;
    smallAlertQuantity.className = "form-text text-danger";

    // Koli Input
    const colMd2DivKoli = document.createElement("div");
    colMd2DivKoli.className = "col-md-2";

    const formGroupDivKoli = document.createElement("div");
    formGroupDivKoli.className = "form-group";

    const labelKoli = document.createElement("label");
    labelKoli.innerHTML = "Koli";
    labelKoli.setAttribute("for", `koli_${counter}`);

    const inputKoli = document.createElement("input");
    inputKoli.className = "form-control";
    inputKoli.type = "text";
    inputKoli.name = "koli[]";
    inputKoli.id = `koli_${counter}`;
    inputKoli.placeholder = "Koli";
    inputKoli.required = true;

    formGroupDivQuantity.appendChild(labelQuantity);
    formGroupDivQuantity.appendChild(inputQuantity);
    formGroupDivQuantity.appendChild(smallAlertQuantity);
    colMd4DivQuantity.appendChild(formGroupDivQuantity);
    rowDivProduct.appendChild(colMd4DivQuantity);

    formGroupDivKoli.appendChild(labelKoli);
    formGroupDivKoli.appendChild(inputKoli);
    colMd2DivKoli.appendChild(formGroupDivKoli);
    rowDivProduct.appendChild(colMd2DivKoli);

    document.getElementById("tambahan_product").appendChild(rowDivProduct);
    document.getElementById("product-counter").value = counter;
    $("#product_" + counter).select2();
}

function removeProduct(counter) {
    document.getElementById(`row-remove-${counter}`).remove();
    document.getElementById(`row-product-${counter}`).remove();
}

function checkStock(e) {
    console.log("test");
    const sequence = e.id.split("_")[1];
    const warehouse = document.getElementById("from_warehouse_id").value;
    const product = document.getElementById(`product_${sequence}`).value;
    const quantity = document.getElementById(`quantity_${sequence}`).value;

    fetch(
        `{{ route("history_stock_get_stock") }}?warehouse_id=${warehouse}&product_id=${product}`,
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
        // If query return null
        if (response.quantity === undefined && response.quantity === null) {
            document.getElementById("button-submit").setAttribute("disabled", "");
            document.getElementById(`alert-quantity_${sequence}`).innerHTML = "Stock is not enough (Current stock: 0)";
            return;
        }

        // If query have result
        if ((response.quantity - quantity) < 0) {
            document.getElementById("button-submit").setAttribute("disabled", "");
            document.getElementById(`alert-quantity_${sequence}`).innerHTML = `Stock is not enough (Current stock: ${response.quantity})`;
        } else if ((response.quantity - quantity) >= 0) {
            document.getElementById("button-submit").removeAttribute("disabled");
            document.getElementById(`alert-quantity_${sequence}`).innerHTML = "";
        }
    }).catch(function (error) {
        console.error(error);
    })
}

$("#code").on("change", function() {
    getStockByCode()
});

function getStockByCode() {
    var code = $("#code").val();
    const type = $("#type").val();
    $.ajax({
        method: "GET",
        url: "{{ route('fetchHistoryStockByCode') }}",
        data: {code, type},
        success: function(data) {
            if (data['data'] != null) {
                var result = data['data'];
                $("#from_warehouse_id").val(result['from_warehouse_id']).trigger("change");
                $("#from_warehouse_id-disabled").val(result['from_warehouse_id']);
                $('#from_warehouse_id').attr('disabled',true);

                $("#to_warehouse_id").val(result['to_warehouse_id']).trigger("change");
                $("#to_warehouse_id-disabled").val(result['to_warehouse_id']);
                $('#to_warehouse_id').attr('disabled',true);
            } else {
                $('#from_warehouse_id').attr('disabled',false);
                $('#to_warehouse_id').attr('disabled',false);
            }
        }
    });
}
</script>
<script type="text/javascript">
    $(document).ready(function() {
        var frmAdd;

        $("#actionAdd").on("submit", function (e) {
            e.preventDefault();

            var from_warehouse_id = $("#from_warehouse_id").val();
            var to_warehouse_id = $("#to_warehouse_id").val();
            if (from_warehouse_id == to_warehouse_id) {
                return alert('Can\'t use same warehouse.');
            }

            frmAdd = _("actionAdd");
            frmAdd = new FormData(document.getElementById("actionAdd"));
            frmAdd.enctype = "multipart/form-data";
            var URLNya = $("#actionAdd").attr('action');

            var ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.addEventListener("load", completeHandler, false);
            ajax.addEventListener("error", errorHandler, false);
            ajax.open("POST", URLNya);
            ajax.setRequestHeader("X-CSRF-TOKEN",$('meta[name="csrf-token"]').attr('content'));
            ajax.send(frmAdd);
        });
        function progressHandler(event){
            document.getElementById("button-submit").innerHTML = "UPLOADING...";
        }
        function completeHandler(event){
            var hasil = JSON.parse(event.target.responseText);

            for (var key of frmAdd.keys()) {
                $("#actionAdd").find("input[name='"+key.name+"']").removeClass("is-invalid");
                $("#actionAdd").find("select[name='"+key.name+"']").removeClass("is-invalid");
                $("#actionAdd").find("textarea[name='"+key.name+"']").removeClass("is-invalid");

                $("#actionAdd").find("input[name='"+key.name+"']").next().find("strong").text("");
                $("#actionAdd").find("select[name='"+key.name+"']").siblings().find("strong").text("");
                $("#actionAdd").find("textarea[name='"+key.name+"']").next().find("strong").text("");
            }

            if(hasil['errors'] != null){
                for (var key of frmAdd.keys()) {
                    if(typeof hasil['errors'][key] === 'undefined') {

                    }
                    else {
                        $("#actionAdd").find("input[name='"+key+"']").addClass("is-invalid");
                        $("#actionAdd").find("select[name='"+key+"']").addClass("is-invalid");
                        $("#actionAdd").find("textarea[name='"+key+"']").addClass("is-invalid");

                        $("#actionAdd").find("input[name='"+key+"']").next().find("strong").text(hasil['errors'][key]);
                        $("#actionAdd").find("select[name='"+key+"']").siblings().find("strong").text(hasil['errors'][key]);
                        $("#actionAdd").find("textarea[name='"+key+"']").next().find("strong").text(hasil['errors'][key]);
                    }
                }
                alert("Input Error !!!");
            }
            else{
                alert("Input Success !!!");
                window.location.reload()
            }
            document.getElementById("button-submit").innerHTML = "SAVE";
        }
        function errorHandler(event){
            document.getElementById("button-submit").innerHTML = "SAVE";
        }
    })
</script>
@endsection
