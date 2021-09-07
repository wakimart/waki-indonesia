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

                            <input type="hidden" name="stock_id" required />

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
                                    onchange=""
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

                            <div class="form-group">
                                <label for="product_id">Product</label>
                                <select class="form-control"
                                    name="product_id"
                                    id="product_id"
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

                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input id="quantity"
                                    type="number"
                                    class="form-control"
                                    placeholder="Quantity"
                                    required />
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
document.addEventListener("DOMContentLoaded", function() {
    $("#warehouse_id").select2();
    $("#product_id").select2();
    $("#type").select2();
});
</script>
@endsection
