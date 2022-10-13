<?php
$menu_item_page = "stock";
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
            <h3 class="page-title">Edit Stock Out</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            aria-expanded="false">
                            Stock
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Stock Out
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form id="actionAdd" method="POST"
                            action="{{ route("update_stock_in_out") }}" autocomplete="off">
                            @csrf

                            <input type="hidden"
                                name="stock_in_out_id"
                                value="{{ $stockInOut->id }}">

                            <input type="hidden"
                                id="type"
                                name="type"
                                value="out" />

                            <div class="form-group">
                                <label for="code">Code (Please Select Date and Select To)</label>
                                <input type="text"
                                    class="form-control"
                                    name="code"
                                    id="code"
                                    value="{{ $stockInOut->code }}"
                                    required readonly />
                                <span class="invalid-feedback"><strong></strong></span>
                                <small id="code_error" style="color: red"></small>
                                <small id="code_success" style="color: green"></small>
                            </div>

                            <div class="form-group">
                                <label for="temp_no">Temp. No (Optional)</label>
                                <input type="text" name="temp_no" class="form-control"
                                value="{{ $stockInOut->temp_no }}">
                            </div>

                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date"
                                    class="form-control"
                                    name="date"
                                    id="date"
                                    value="{{ $stockInOut->date }}"
                                    required />
                                <span class="invalid-feedback"><strong></strong></span>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="">From Warehouse/Branch/Vendor</label>
                                    <select id="from_warehouse_type" class="form-control mb-3">
                                        <option value="" selected disabled>Select From</option>
                                        @foreach (App\Warehouse::$Type as $type)
                                            <option value="{{ $type }}"
                                                @if($stockInOut->warehouseFrom['type'] == $type) selected @endif>
                                                {{ ucwords($type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select class="form-control"
                                        name="from_warehouse_id"
                                        id="from_warehouse_id"
                                        required>
                                        <option value="" disabled selected>
                                            Select Warehouse
                                        </option>
                                    </select>
                                    <span class="invalid-feedback"><strong></strong></span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">To Warehouse/Branch</label>
                                    <select id="to_warehouse_type" class="form-control mb-3">
                                        <option value="" selected disabled>Select To</option>
                                        @foreach (App\Warehouse::$Type as $type)
                                            @if ($type != "vendor")
                                            <option value="{{ $type }}"
                                                @if($stockInOut->warehouseTo['type'] == $type) selected @endif>
                                                {{ ucwords($type) }}
                                            </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <select class="form-control"
                                        name="to_warehouse_id"
                                        id="to_warehouse_id"
                                        required>
                                        <option value="" disabled selected>
                                            Select Warehouse
                                        </option>
                                    </select>
                                    <span class="invalid-feedback"><strong></strong></span>
                                </div>
                            </div>

                            <div id="product-container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="text-center"
                                            style="display: block;
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

                                <?php 
                                $count_sioProduct = count($stockInOut->stockInOutProduct);

                                ?>

                                <input type="hidden"
                                    id="product-counter"
                                    value="{{ $count_sioProduct - 1 }}" />

                                @for ($i = 0; $i < $count_sioProduct; $i++)
                                    <input type="hidden" name="old_stock_in_out_products_id[]" value="{{ $stockInOut->stockInOutProduct[$i]['id'] }}">
                                    <div class="row" id="row-remove-{{ $i }}">
                                        <input type="hidden" name="stock_in_out_product_id[]" value="{{ $stockInOut->stockInOutProduct[$i]['id'] }}">
                                        <div class="col-md-12">
                                            <div class="text-center" 
                                                style="display: block; 
                                                    float: right; 
                                                    margin-bottom: 20px;">
                                                <button class="btn btn-gradient-danger" 
                                                    type="button" 
                                                    onclick="removeProduct({{ $i }})">
                                                    Remove Product
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="row-product-{{ $i }}">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="product_{{ $i }}">
                                                    Product
                                                </label>
                                                <select class="form-control product_id"
                                                    name="product[]"
                                                    id="product_{{ $i }}"
                                                    required>
                                                    <option value="" disabled selected>
                                                        Select Product
                                                    </option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}"
                                                            @if($stockInOut->stockInOutProduct[$i]['product_id'] == $product->id) selected @endif>
                                                            {{ $product->code }} - {{ $product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="invalid-feedback"><strong></strong></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="quantity_{{ $i }}">
                                                    Quantity
                                                </label>
                                                <input id="quantity_{{ $i }}"
                                                    class="form-control"
                                                    type="number"
                                                    name="quantity[]"
                                                    placeholder="Quantity"
                                                    min="0"
                                                    value="{{ $stockInOut->stockInOutProduct[$i]['quantity'] }}"
                                                    required />
                                                <span class="invalid-feedback"><strong></strong></span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="koli_{{ $i }}">
                                                    Koli (Optional)
                                                </label>
                                                <input id="koli_{{ $i }}"
                                                    class="form-control"
                                                    type="number"
                                                    name="koli[]"
                                                    placeholder="Koli"
                                                    value="{{ $stockInOut->stockInOutProduct[$i]['koli'] }}" />
                                                <span class="invalid-feedback"><strong></strong></span>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                                <div id="tambahan_product"></div>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description"
                                    class="form-control"
                                    id="description"
                                    rows="2"
                                    placeholder="Description"
                                    maxlength="300">{{ $stockInOut->description }}</textarea>
                                <span class="invalid-feedback"><strong></strong></span>
                            </div>

                            <div class="form-group">
                                <button type="submit"
                                    id="addHistory"
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
let productOptions = "<option value='' disabled selected>Select Product</option>";
document.addEventListener("DOMContentLoaded", function() {
    getProduct();
    $("#from_warehouse_id").select2();
    $("#to_warehouse_id").select2();
    for (let i = 0; i <= document.getElementById("product-counter").value; i++) {
        $(`#product_${i}`).select2();
    }
    // $("#type").select2();
    getWarehouse($("#from_warehouse_type").val(), null, "#from_warehouse_id").then(function() {
        $("#from_warehouse_id").val("{{ $stockInOut->warehouse_from_id }}").trigger('change');
    });
    getWarehouse($("#to_warehouse_type").val(), null, "#to_warehouse_id").then(function() {
        $("#to_warehouse_id").val("{{ $stockInOut->warehouse_to_id }}").trigger('change');
    });
});
function getProduct() {
    fetch(
        '{{ route("stock_in_out_get_product") }}',
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
    selectProduct.className = "form-control product_id";
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
    inputQuantity.required = true;
    inputQuantity.min = 0;
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
    inputKoli.type = "number";
    inputKoli.name = "koli[]";
    inputKoli.id = `koli_${counter}`;
    inputKoli.placeholder = "Koli";
    formGroupDivQuantity.appendChild(labelQuantity);
    formGroupDivQuantity.appendChild(inputQuantity);
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

$(document).ready(function() {
    $(document).on("select2:select", '.product_id', function(e) { 
        const ph_product = $(this);
        var count_ph_product = 0;
        $('.product_id').each(function() {
            if (ph_product.val() == $(this).val()) count_ph_product++;
            if (count_ph_product > 1) {
                ph_product.val("").trigger("change");
                alert('Cannot Choose Same Product');
                return false;
            }
        })
    });
});

$("#from_warehouse_type").on('change', function() {
    getWarehouse($(this).val(), true, "#from_warehouse_id");
});

$("#to_warehouse_type").on('change', function() {
    getWarehouse($(this).val(), null, "#to_warehouse_id");
})

function getWarehouse(warehouse_type, check_parent, target_element) {
    $(target_element).html("");
    return $.ajax({
        method: "GET",
        url: "{{ route('fetchWarehouse') }}",
        data: {warehouse_type, check_parent},
        success: function(response) {
            var option_warehouse = `<option value="" selected disabled>Select Warehouse</option>`;
            response.data.forEach(function(value) {
                option_warehouse += `<option value="${value.id}">${value.code} - ${value.name}</option>`;
            })
            $(target_element).html(option_warehouse);
        },
    });
}

$(document).on('change', '#date, #to_warehouse_type', function() {
    const old_code = "{{ $stockInOut->code }}";
    const type = $("#type").val();
    const date = $("#date").val();
    const warehouse_type = $("#to_warehouse_type").val();
    const check_date = date != "{{ $stockInOut->date }}";
    const check_warehouse_type = warehouse_type != "{{ $stockInOut->warehouseTo['type']}}";
    if (type && date && warehouse_type && (check_date || check_warehouse_type)) {
        $.ajax({
            method: "GET",
            url: "{{ route('stock_in_out_generate_code') }}",
            data: {type, date, warehouse_type},
            success: function(response) {
                if (response.data) {
                    $("#code").val(response.data);
                } else {
                    alert(response.data);
                }
            },
        });
    } else {
        $("#code").val(old_code);
    }
})
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
            document.getElementById("addHistory").innerHTML = "UPLOADING...";
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
            document.getElementById("addHistory").innerHTML = "SAVE";
        }
        function errorHandler(event){
            document.getElementById("addHistory").innerHTML = "SAVE";
        }
    })
</script>
@endsection