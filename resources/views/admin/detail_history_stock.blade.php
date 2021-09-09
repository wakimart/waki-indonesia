<?php
$menu_item_page = "history_stock";
$menu_item_second = "detail_history_stock";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    #intro {
        padding-top: 2em;
        min-width: 0;
    }

    table {
        margin: 1em;
        font-size: 14px;
    }

    .table-responsive table {
        width: 100%;
        overflow:scroll;
    }

    .table-responsive table .form-control {
        height: 32px;
        line-height: 32px;
        box-shadow: none;
        border-radius: 2px;
        margin-bottom: 0;
    }

    .table-responsive table .form-control.error {
        border-color: #f50000;
        border: 1px solid red;
    }

    .table-responsive table td .save {
        display: none;
    }

    table thead {
        background-color: #8080801a;
        text-align: center;
    }

    table td {
        border: 0.5px #8080801a solid;
        padding: 0.5em;

    }

    .pInTable {
        margin-bottom: 6pt !important;
        font-size: 10pt;
    }

    select.form-control {
        color: black !important;
    }

    @media screen and (max-width: 768px) {
        .table-responsive {
            margin-right: 10px;
        }
    }

</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body" style="padding-left: 2em; padding-right: 2em;">
                        <div class="row justify-content-center mt-3">
                            <h2>DETAIL HISTORY STOCK</h2>
                        </div>
                        <div class="row justify-content-center">
                            <h3>{{ $historystocks->code }}</h3>
                        </div>
                        <hr>
                        <div class="row justify-content-center my-5">
                            <h4>History In</h4>

                            <div class="table-responsive">
                                <table class="col-md-12">
                                    <thead style="background-color: #80808012 !important;">
                                        <tr>
                                            <td>Date</td>
                                            <td>Warehouse</td>
                                            <td>Product</td>
                                            <td>Quantity</td>
                                            <td>Description</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($historyIn as $key => $historystock)
                                            <tr>
                                                <td class="text-center"
                                                    id="code_{{ $key }}">
                                                    {{ $historystock->date }}
                                                </td>
                                                <td class="text-left"
                                                    id="warehouse_{{ $key }}">
                                                    {{ $historystock->stock->warehouse['name'] }}
                                                </td>
                                                <td class="text-left"
                                                    id="product_{{ $key }}">
                                                    {{ $historystock->stock->product['name']  }}
                                                </td>
                                                <td class="text-center"
                                                    id="quantity_{{ $key }}">
                                                    {{ $historystock->quantity }}
                                                </td>
                                                <td class="text-left"
                                                    id="description_{{ $key }}">
                                                    {{ $historystock->description }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row justify-content-center my-5">
                            <h4>History Out</h4>

                            <div class="table-responsive">
                                <table class="col-md-12">
                                    <thead style="background-color: #80808012 !important;">
                                        <tr>
                                            <td>Date</td>
                                            <td>Warehouse</td>
                                            <td>Product</td>
                                            <td>Quantity</td>
                                            <td>Description</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($historyOut as $key => $historystock)
                                            <tr>
                                                <td class="text-center"
                                                    id="code_{{ $key }}">
                                                    {{ $historystock->date }}
                                                </td>
                                                <td class="text-left"
                                                    id="warehouse_{{ $key }}">
                                                    {{ $historystock->stock->warehouse['name'] }}
                                                </td>
                                                <td class="text-left"
                                                    id="product_{{ $key }}">
                                                    {{ $historystock->stock->product['name']  }}
                                                </td>
                                                <td id="quantity_{{ $key }}"
                                                    class="text-left">
                                                    {{ $historystock->quantity }}
                                                </td>
                                                <td id="description_{{ $key }}"
                                                    class="text-center">
                                                    {{ $historystock->description }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section("script")
<script type="application/javascript">

</script>
@endsection
