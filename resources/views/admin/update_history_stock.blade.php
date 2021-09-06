<?php
$menu_item_page = "history_stock";
$menu_item_second = "update_history_stock";
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
            <h3 class="page-title">Update History Stock</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            aria-expanded="false">
                            History Stock
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Update
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form method="POST"
                            action="{{ route("update_history_stock") }}">
                            @csrf

                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text"
                                    class="form-control"
                                    name="code"
                                    id="code"
                                    placeholder="Code"
                                    maxlength="191"
                                    value="{{ $historystock->code }}"
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="stock_id">
                                    Stock
                                </label>
                                <select class="form-control"
                                    name="stock_id"
                                    id="stock_id">
                                    <option selected value="">
                                        Select Stock
                                    </option>
                                    @foreach ($stocks as $stock)
                                        @if ($historystock->stock_id === $stock->id)
                                            <option value="{ $stock->product_id }}" selected>
                                            {{ $stock->product['name'] }}
                                            </option>
                                        @else
                                            <option value="{{ $stock->product_id }}">
                                                {{ $stock->product['name'] }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="type">Type</label>
                                <select class="form-control"
                                    id="type"
                                    name="type">
                                    <option selected value="">
                                        Select Type
                                    </option>
                                    <option value="in">In</option>
                                    <option value="out">Out</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="qty">Quantity</label>
                                <input id="qty" 
                                    type="number" 
                                    class="form-control" 
                                    placeholder="Quantity"
                                    value="{{ $historystock->quantity }}"
                                    required />
                                <div class="validation"></div>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description"
                                    class="form-control"
                                    id="description"
                                    rows="2"
                                    placeholder="Description"
                                    maxlength="191"
                                    value="{{ $historystock->quantity }}">
                                </textarea>
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

</script>
@endsection
