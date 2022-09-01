<?php
$menu_item_page = "stock_warehouse";
$menu_item_second = "list_stock_warehouse";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
    integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
<style type="text/css">
    .table th img, .table td img {
        border-radius: 0% !important;
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
            <h3 class="page-title">List Stock</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse" aria-expanded="false">
                            Stock Warehouse
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        List Stock
                    </li>
                </ol>
            </nav>
        </div>

        <form id="form-search"
            method="GET"
            action="{{ route("list_stock_warehouse") }}"></form>

        <div class="row">
            <div class="col-12" style="margin-bottom: 0;">
                <div class="row" style="padding-right: 20px; padding-left: 20px;">
                    {{-- <div class="col-xs-6 col-sm-4 col-lg-4"
                        style="margin-bottom: 0; padding: 0 5px 0 0; display: inline-block;">
                        <div class="form-group">
                            <label for="filter_name">Search By Name</label>
                            <input class="form-control"
                                id="filter_name"
                                name="filter_name"
                                placeholder="Search By Name"
                                form="form-search"
                                value="{{ $_GET['filter_name'] ?? "" }}" />
                        </div>
                    </div>
    
                    <div class="col-xs-6 col-sm-4 col-lg-4"
                        style="margin-bottom: 0; padding: 0 5px 0 0; display: inline-block;">
                        <div class="form-group">
                            <label for="filter_code">Search By Code</label>
                            <input class="form-control"
                                id="filter_code"
                                name="filter_code"
                                placeholder="Search By Code"
                                form="form-search"
                                value="{{ $_GET['filter_code'] ?? "" }}" />
                        </div>
                    </div> --}}
                    
                    {{-- <div class="col-xs-6 col-sm-4 col-lg-3"
                        style="margin-bottom: 0; padding: 0 5px 0 0; display: inline-block;">
                        <div class="form-group">
                            <label for="filter_month">Filter by Month</label>
                            <input type="date"
                                class="form-control"
                                name="filter_month"
                                id="filter_month"
                                min="{{ date("Y-m-d", strtotime("-2 months")) }}"
                                value="{{ $_GET["filter_month"] ?? '' }}"
                                form="form-search" />
                        </div>
                    </div> --}}

                    
                    <div class="col-xs-6 col-sm-4 col-md-3"
                        style="margin-bottom: 0; padding: 0 5px 0 0; display: inline-block;">
                        <div class="form-group">
                            <label for="filter_product">Filter by Product</label>
                            <select class="form-control"
                                name="filter_product"
                                id="filter_product"
                                form="form-search">
                                @if (isset($_GET["filter_product"]) && !empty($_GET["filter_product"]))
                                    <option disabled>Select Product</option>
                                @else
                                    <option disabled selected>
                                        Select Product
                                    </option>
                                @endif
                                @foreach ($products as $product)
                                    @if (isset($_GET["filter_product"]) && !empty($_GET["filter_product"]) && $_GET["filter_product"] == $product->id)
                                        <option value="{{ $product->id }}" selected>
                                            {{ $product->code }} - {{ $product->name }}
                                        </option>
                                    @else
                                        <option value="{{ $product->id }}">
                                            {{ $product->code }} - {{ $product->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-4 col-md-3"
                        style="margin-bottom: 0; padding: 0 5px 0 0; display: inline-block;">
                        <div class="form-group">
                            <label for="filter_city">
                                Filter by City
                            </label>
                            <select class="form-control"
                                name="filter_city"
                                id="filter_city"
                                form="form-search"
                                onchange="setParentWarehouse(this)">
                                @if (isset($_GET["filter_city"]) && !empty($_GET["filter_city"]))
                                    <option disabled>Select City</option>
                                @else
                                    <option disabled selected>
                                        Select City
                                    </option>
                                @endif
                                @foreach ($parentWarehouses as $parentWarehouse)
                                    @if (isset($_GET["filter_city"]) && !empty($_GET["filter_city"]) && $_GET["filter_city"] == $parentWarehouse->city_id)
                                        <option value="{{ $parentWarehouse->city_id }}" selected>
                                            {{ $parentWarehouse->getCityFullName() }}
                                        </option>
                                    @else
                                        <option value="{{ $parentWarehouse->city_id }}">
                                            {{ $parentWarehouse->getCityFullName() }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-xs-6 col-sm-4 col-md-3"
                        style="margin-bottom: 0; padding: 0 5px 0 0; display: inline-block;">
                        <div class="form-group">
                            <label for="filter_warehouse">
                                Filter by Parent Warehouse
                            </label>
                            <select class="form-control"
                                name="filter_warehouse"
                                id="filter_warehouse"
                                form="form-search">
                                    @if (isset($_GET["filter_warehouse"]) && !empty($_GET["filter_warehouse"]))
                                        <option disabled>Select Parent Warehouse</option>
                                    @else
                                        <option disabled selected>
                                            Select Parent Warehouse
                                        </option>
                                    @endif

                                    @if (isset($_GET["filter_warehouse"]) && !empty($_GET["filter_warehouse"]))
                                        @foreach ($parentWarehouses as $parentWarehouse)
                                            @if($_GET["filter_city"] == $parentWarehouse->city_id)
                                                @if (isset($_GET["filter_warehouse"]) && !empty($_GET["filter_warehouse"]) && $_GET["filter_warehouse"] == $parentWarehouse->id)
                                                    <option value="{{ $parentWarehouse->id }}" selected>
                                                        {{ $parentWarehouse->code }} - {{ $parentWarehouse->name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $parentWarehouse->id }}">
                                                        {{ $parentWarehouse->code }} - {{ $parentWarehouse->name }}
                                                    </option>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                            </select>
                        </div>
                    </div>

                </div>
                <div class="row" style="padding-right: 20px; padding-left: 20px;">
                    <div class="col-xs-12 col-sm-12"
                        style="padding: 0; display: inline-block;">
                        <div class="form-group">
                            <button class="btn btn-gradient-primary m-1"
                                type="submit"
                                form="form-search">
                                <span class="mdi mdi-filter"></span>
                                Apply Filter
                            </button>
                            <button id="btn-filter_reset"
                                type="button"
                                class="btn btn-gradient-danger m-1">
                                <span class="mdi mdi-refresh"></span>
                                Reset Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header" style="background: none;">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link active"
                                    style="font-weight: 500; font-size: 1em;"
                                    id="Semua-tab"
                                    data-toggle="tab"
                                    href="#Semua-table" 
                                    role="tab"
                                    aria-controls="Semua"
                                    aria-selected="true">
                                    Semua
                                </a>
                            </li>
                            @if ((isset($_GET["filter_city"]) && !empty($_GET["filter_city"])) && (isset($_GET["filter_warehouse"]) && !empty($_GET["filter_warehouse"])))
                                @foreach($warehouses as $key => $warehouse)                        
                                <li class="nav-item">
                                    <a class="nav-link"
                                        style="font-weight: 500; font-size: 1em;"
                                        id="{{$warehouse->code}}-tab"
                                        data-toggle="tab"
                                        href="#{{$warehouse->code}}-table" 
                                        role="tab"
                                        aria-controls="{{$warehouse->code}}"
                                        aria-selected="true">
                                        {{$warehouse['name']}}
                                    </a>
                                </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active pl-3 pr-3 pb-3 pt-1" id="Semua-table" role="tabpanel" aria-labelledby="Semua-tab">
                                <h5 style="margin-bottom: 1em;">
                                    Total: {{ $stocks->total() }} data
                                </h5>
                                <div class="table-responsive"
                                    style="border: 1px solid #ebedf2;">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No.</th>
                                                @if ((isset($_GET["filter_product"]) && !empty($_GET["filter_product"])) || (empty($_GET["filter_warehouse"]) &&!empty($_GET["filter_warehouse"])))
                                                    <th>Warehouse</th>
                                                @endif
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Remaining Stock</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $totalQty = 0;
                                            @endphp

                                            @foreach ($stocks as $stock)
                                                <tr>
                                                    <td class="text-right">
                                                        {{ ++$i }}
                                                    </td>
                                                    @if ((isset($_GET["filter_product"]) && !empty($_GET["filter_product"])) || (empty($_GET["filter_warehouse"]) &&!empty($_GET["filter_warehouse"])))
                                                        <td>
                                                            {{ $stock->warehouse_code }} - {{ $stock->warehouse_name }}
                                                        </td>
                                                    @endif
                                                    <td>
                                                        {{ $stock->product_code }}
                                                    </td>
                                                    <td>
                                                        {{ $stock->product_name }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $totalQty += $stock->quantity;
                                                        @endphp

                                                        @if (isset($_GET["filter_month"]) && !empty($_GET["filter_month"]))
                                                            {{ $stock->month_quantity }}
                                                        @else
                                                            {{ $stock->quantity }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if(isset($_GET["filter_product"]))
                                                <tr>
                                                    <td colspan="{{ isset($_GET["filter_product"]) ? (isset($_GET["filter_warehouse"]) ? 3 : 4) : 4 }}" class="text-right">
                                                        <strong>Total Quantity : </strong>
                                                    </td>
                                                    <td class="text-left">
                                                        {{ $totalQty }}
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <br>
                                    {{ $stocks->links() }}
                                </div>
                            </div>
                            @foreach($warehouses as $key => $warehouse)
                                @php
                                    $i = 0;
                                    $totalQty = 0;
                                @endphp

                                <div class="tab-pane fade show pl-3 pr-3 pb-3 pt-1" id="{{ $warehouse->code }}-table" role="tabpanel" aria-labelledby="{{ $warehouse->code }}-tab">
                                    <h5 style="margin-bottom: 1em;">
                                        Total: {{ count($warehouse->stock) }} data
                                    </h5>
                                    <div class="table-responsive"
                                        style="border: 1px solid #ebedf2;">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No.</th>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                    <th>Remaining Stock</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($warehouse->stockWithProduct(isset($_GET["filter_product"]) ? $_GET["filter_product"] : null) as $stockData)
                                                    <tr>
                                                        <td class="text-right">
                                                            {{ ++$i }}
                                                        </td>
                                                        <td>
                                                            {{ $stockData->product['code'] }}
                                                        </td>
                                                        <td>
                                                            {{ $stockData->product['name'] }}
                                                        </td>
                                                        <td>
                                                            @php
                                                                $totalQty += $stockData['quantity'];
                                                            @endphp

                                                            @if (isset($_GET["filter_month"]) && !empty($_GET["filter_month"]))
                                                                {{ $stockData->month_quantity }}
                                                            @else
                                                                {{ $stockData['quantity'] }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if(isset($_GET["filter_product"]))
                                                    <tr>
                                                        <td colspan="3" class="text-right">
                                                            <strong>Total Quantity : </strong>
                                                        </td>
                                                        <td class="text-left">
                                                            {{ $totalQty }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                        <br>
                                        {{ $stocks->links() }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div class="modal fade"
    id="deleteDoModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 style="text-align: center;">
                    Are you sure to delete this stock?
                </h5>
            </div>
            <div class="modal-footer">
                <form method="post"
                    action="">
                    @csrf
                    <input type="hidden" name="id" id="id-delete" />
                    <button type="submit"
                        class="btn btn-gradient-danger mr-2">
                        Yes
                    </button>
                </form>
                <button class="btn btn-light">No</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Delete -->
@endsection

@section("script")
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" 
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" 
    crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
    integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
    defer></script>
<script type="application/javascript">
    function submitDelete(e) {
        document.getElementById("id-delete").value = e.dataset.id;
    }

    document.addEventListener("DOMContentLoaded", function() {
        $("#filter_product").select2();
        $("#filter_warehouse").select2();
        $("#filter_city").select2();

        $("#btn-filter_reset").click(function (e) {
            window.location.href = "{{ route('list_stock_warehouse') }}";
        });
    });

</script>
<script>
    function setParentWarehouse(e) {
        $("#filter_warehouse").prop('required',true);
        const URL = '{{ route("fetchParentByCity", ['city' => ""]) }}/' + e.value;

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
            const result = response;

            let optionsParent = "";

            if (result.length > 0) {
                $("#filter_warehouse").select2("destroy");

                result.forEach(function (currentValue) {
                    optionsParent += '<option value="'
                        + currentValue["id"]
                        + '">'
                        + currentValue['code']
                        + " - "
                        + currentValue['name']
                        + '</option>';
                });

                document.getElementById("filter_warehouse").innerHTML = optionsParent;

                $("#filter_warehouse").select2();
            }
        }).catch(function (error) {
            console.error(error);
        })
    }
</script>
@endsection
