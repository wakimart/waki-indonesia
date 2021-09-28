<?php
$menu_item_page = "personal_homecare";
$menu_item_second = "list_product";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
    integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
<style type="text/css">
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

    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link {
        color: #111 !important;
    }

    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
        color: #36cd7a !important;
    }

    .nav-tabs .nav-link {
        padding-left: 1.75em;
        padding-right: 1.75em;
    }

</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">List Personal Homecare Product</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#order-dd"
                            aria-expanded="false"
                            aria-controls="order-dd">
                            Personal Homecare Product
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        List Product
                    </li>
                </ol>
            </nav>
        </div>

        <form class="d-none"
            id="form-search"
            method="GET"
            action="{{ route('list_phc_product') }}">
        </form>

        <div class="row">
            <div class="col-12" style="margin-bottom: 0;">
                <div class="col-xs-6 col-sm-4"
                    style="margin-bottom: 0; padding: 0; display: inline-block;">
                    <div class="form-group">
                        <label for="branch_id">Search By Branch</label>
                        <select class="form-control"
                            id="branch_id"
                            name="branch_id"
                            form="form-search">
                            <option disabled selected>
                                Select Branch
                            </option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ isset($url['branch_id']) ? ($url['branch_id'] == $branch['id'] ? 'selected' : '') : '' }}>
                                    {{ $branch->code }} - {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Search By Status</label>
                        <select class="form-control"
                            id="status"
                            name="status"
                            form="form-search">
                            <option disabled {{ isset($url['status']) ? '' : 'selected' }}>
                                Select Status
                            </option>
                            <option value="pending" {{ isset($url['status']) ? ($url['status'] == 'pending' ? 'selected' : '') : '' }}>
                                Pending
                            </option>
                            <option value="unavailable" {{ isset($url['status']) ? ($url['status'] == 'unavailable' ? 'selected' : '') : '' }}>
                                Unavailable
                            </option>
                            <option value="available" {{ isset($url['status']) ? ($url['status'] == 'available' ? 'selected' : '') : '' }}>
                                Available
                            </option>
                        </select>
                    </div>
                </div>
                <input type="hidden"
                    name="product_id"
                    form="form-search"
                    value="{{ $url['product_id'] }}" />
                <div class="col-xs-6 col-sm-6"
                    style="padding: 0; display: inline-block">
                    <div class="form-group">
                        <button type="submit"
                            class="btn btn-gradient-primary"
                            form="form-search">
                            <span class="mdi mdi-filter"></span> Apply Filter
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header" style="background: none;">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link {{ $url['product_id'] == 4 ? 'active' : ''}}"
                                    style="font-weight: 500; font-size: 1em;"
                                    id="one-tab"
                                    href="{{ route('list_phc_product') }}?product_id=4{{ isset($url['status']) ? '&status='.$url['status'] : '' }}{{ isset($url['branch_id']) ? '&branch_id='.$url['branch_id'] : '' }}"
                                    aria-controls="One"
                                    aria-selected="true">
                                    WK2079
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $url['product_id'] == 3 ? 'active' : ''}}"
                                    style="font-weight: 500; font-size: 1em;"
                                    id="two-tab"
                                    href="{{ route('list_phc_product') }}?product_id=3{{ isset($url['status']) ? '&status='.$url['status'] : '' }}{{ isset($url['branch_id']) ? '&branch_id='.$url['branch_id'] : '' }}"
                                    aria-controls="Two"
                                    aria-selected="true">
                                    WKT2076H
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $url['product_id'] == 2 ? 'active' : ''}}"
                                    style="font-weight: 500; font-size: 1em;"
                                    id="three-tab"
                                    href="{{ route('list_phc_product') }}?product_id=2{{ isset($url['status']) ? '&status='.$url['status'] : '' }}{{ isset($url['branch_id']) ? '&branch_id='.$url['branch_id'] : '' }}"
                                    aria-controls="Three"
                                    aria-selected="true">
                                    WKT2076i
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $url['product_id'] == 7 ? 'active' : ''}}"
                                    style="font-weight: 500; font-size: 1em;"
                                    id="four-tab"
                                    href="{{ route('list_phc_product') }}?product_id=7{{ isset($url['status']) ? '&status='.$url['status'] : '' }}{{ isset($url['branch_id']) ? '&branch_id='.$url['branch_id'] : '' }}"
                                    aria-controls="Four"
                                    aria-selected="true">
                                    WKA2023
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $url['product_id'] == 6 ? 'active' : ''}}"
                                    style="font-weight: 500; font-size: 1em;"
                                    id="five-tab"
                                    href="{{ route('list_phc_product') }}?product_id=6{{ isset($url['status']) ? '&status='.$url['status'] : '' }}{{ isset($url['branch_id']) ? '&branch_id='.$url['branch_id'] : '' }}"
                                    aria-controls="Five"
                                    aria-selected="true">
                                    WKA2024
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $url['product_id'] == 1 ? 'active' : ''}}"
                                    style="font-weight: 500; font-size: 1em;"
                                    id="six-tab"
                                    href="{{ route('list_phc_product') }}?product_id=1{{ isset($url['status']) ? '&status='.$url['status'] : '' }}{{ isset($url['branch_id']) ? '&branch_id='.$url['branch_id'] : '' }}"
                                    aria-controls="Six"
                                    aria-selected="true">
                                    WKT2080
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show {{ $url['product_id'] == 4 ? 'active' : ''}} p-3" id="one" role="tabpanel" aria-labelledby="one-tab">
                                <h5 style="margin-bottom: 0.5em;">
                                    Total: {{ $phcproducts->total() }}
                                </h5>
                                <div class="table-responsive"
                                    style="border: 1px solid #ebedf2;">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No.</th>
                                                <th>Code</th>
                                                <th>Product Name</th>
                                                <th class="text-center">Branch</th>
                                                <th>Status</th>
                                                <th class="text-center">View</th>
                                                <th class="text-center">Edit</th>
                                                <th class="text-center">Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($phcproducts as $key => $phcproduct)
                                                @if($phcproduct->product->code == 'WK2079')
                                                <tr>
                                                    <td class="text-right">
                                                        {{ ++$i }}
                                                    </td>
                                                    <td>{{ $phcproduct->code }}</td>
                                                    <td>
                                                        {{ $phcproduct->product->name }}
                                                    </td>
                                                    <td>
                                                        {{ $phcproduct->branch->code }} - {{ $phcproduct->branch->name }}
                                                    </td>
                                                    <td>
                                                        {{ ucwords($phcproduct->status) }}
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('detail_phc_product', ['id' => $phcproduct['id']]) }}">
                                                            <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(76 172 245);"></i>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('edit_phc_product', ['id' => $phcproduct['id']]) }}">
                                                            <i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a class="btn-delete"
                                                            data-toggle="modal"
                                                            href="#deleteDoModal"
                                                            onclick="submitDelete(this)"
                                                            data-id="{{ $phcproduct["id"] }}">
                                                            <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                    {{ $phcproducts->appends($url)->links() }}
                                </div>
                            </div>
                            <div class="tab-pane fade show {{ $url['product_id'] == 3 ? 'active' : ''}} p-3" id="two" role="tabpanel" aria-labelledby="two-tab">
                                <h5 style="margin-bottom: 0.5em;">
                                    Total: {{ $phcproducts->total() }}
                                </h5>
                                <div class="table-responsive"
                                    style="border: 1px solid #ebedf2;">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No.</th>
                                                <th>Code</th>
                                                <th>Product Name</th>
                                                <th class="text-center">Branch</th>
                                                <th>Status</th>
                                                <th class="text-center">View</th>
                                                <th class="text-center">Edit</th>
                                                <th class="text-center">Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($phcproducts as $key => $phcproduct)
                                                @if($phcproduct->product->code == 'WKT2076H')
                                                <tr>
                                                    <td class="text-right">
                                                        {{ ++$i }}
                                                    </td>
                                                    <td>{{ $phcproduct->code }}</td>
                                                    <td>
                                                        {{ $phcproduct->product->name }}
                                                    </td>
                                                    <td>
                                                        {{ $phcproduct->branch->code }} - {{ $phcproduct->branch->name }}
                                                    </td>
                                                    <td>
                                                        {{ ucwords($phcproduct->status) }}
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('detail_phc_product', ['id' => $phcproduct['id']]) }}">
                                                            <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(76 172 245);"></i>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('edit_phc_product', ['id' => $phcproduct['id']]) }}">
                                                            <i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a class="btn-delete"
                                                            data-toggle="modal"
                                                            href="#deleteDoModal"
                                                            onclick="submitDelete(this)"
                                                            data-id="{{ $phcproduct["id"] }}">
                                                            <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                    {{ $phcproducts->appends($url)->links() }}
                                </div>
                            </div>
                            <div class="tab-pane fade show {{ $url['product_id'] == 2 ? 'active' : ''}} p-3" id="three" role="tabpanel" aria-labelledby="three-tab">
                                <h5 style="margin-bottom: 0.5em;">
                                    Total: {{ $phcproducts->total() }}
                                </h5>
                                <div class="table-responsive"
                                    style="border: 1px solid #ebedf2;">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No.</th>
                                                <th>Code</th>
                                                <th>Product Name</th>
                                                <th class="text-center">Branch</th>
                                                <th>Status</th>
                                                <th class="text-center">View</th>
                                                <th class="text-center">Edit</th>
                                                <th class="text-center">Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($phcproducts as $key => $phcproduct)
                                                @if($phcproduct->product->code == 'WKT2076i')
                                                <tr>
                                                    <td class="text-right">
                                                        {{ ++$i }}
                                                    </td>
                                                    <td>{{ $phcproduct->code }}</td>
                                                    <td>
                                                        {{ $phcproduct->product->name }}
                                                    </td>
                                                    <td>
                                                        {{ $phcproduct->branch->code }} - {{ $phcproduct->branch->name }}
                                                    </td>
                                                    <td>
                                                        {{ ucwords($phcproduct->status) }}
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('detail_phc_product', ['id' => $phcproduct['id']]) }}">
                                                            <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(76 172 245);"></i>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('edit_phc_product', ['id' => $phcproduct['id']]) }}">
                                                            <i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a class="btn-delete"
                                                            data-toggle="modal"
                                                            href="#deleteDoModal"
                                                            onclick="submitDelete(this)"
                                                            data-id="{{ $phcproduct["id"] }}">
                                                            <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                    {{ $phcproducts->appends($url)->links() }}
                                </div>
                            </div>
                            <div class="tab-pane fade show {{ $url['product_id'] == 7 ? 'active' : ''}} p-3" id="four" role="tabpanel" aria-labelledby="four-tab">
                                <h5 style="margin-bottom: 0.5em;">
                                    Total: {{ $phcproducts->total() }}
                                </h5>
                                <div class="table-responsive"
                                    style="border: 1px solid #ebedf2;">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No.</th>
                                                <th>Code</th>
                                                <th>Product Name</th>
                                                <th class="text-center">Branch</th>
                                                <th>Status</th>
                                                <th class="text-center">View</th>
                                                <th class="text-center">Edit</th>
                                                <th class="text-center">Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($phcproducts as $key => $phcproduct)
                                                @if($phcproduct->product->code == 'WKA2023')
                                                <tr>
                                                    <td class="text-right">
                                                        {{ ++$i }}
                                                    </td>
                                                    <td>{{ $phcproduct->code }}</td>
                                                    <td>
                                                        {{ $phcproduct->product->name }}
                                                    </td>
                                                    <td>
                                                        {{ $phcproduct->branch->code }} - {{ $phcproduct->branch->name }}
                                                    </td>
                                                    <td>
                                                        {{ ucwords($phcproduct->status) }}
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('detail_phc_product', ['id' => $phcproduct['id']]) }}">
                                                            <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(76 172 245);"></i>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('edit_phc_product', ['id' => $phcproduct['id']]) }}">
                                                            <i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a class="btn-delete"
                                                            data-toggle="modal"
                                                            href="#deleteDoModal"
                                                            onclick="submitDelete(this)"
                                                            data-id="{{ $phcproduct["id"] }}">
                                                            <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                    {{ $phcproducts->appends($url)->links() }}
                                </div>
                            </div>
                            <div class="tab-pane fade show {{ $url['product_id'] == 6 ? 'active' : ''}} p-3" id="five" role="tabpanel" aria-labelledby="five-tab">
                                <h5 style="margin-bottom: 0.5em;">
                                    Total: {{ $phcproducts->total() }}
                                </h5>
                                <div class="table-responsive"
                                    style="border: 1px solid #ebedf2;">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No.</th>
                                                <th>Code</th>
                                                <th>Product Name</th>
                                                <th class="text-center">Branch</th>
                                                <th>Status</th>
                                                <th class="text-center">View</th>
                                                <th class="text-center">Edit</th>
                                                <th class="text-center">Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($phcproducts as $key => $phcproduct)
                                                @if($phcproduct->product->code == 'WKA2024')
                                                <tr>
                                                    <td class="text-right">
                                                        {{ ++$i }}
                                                    </td>
                                                    <td>{{ $phcproduct->code }}</td>
                                                    <td>
                                                        {{ $phcproduct->product->name }}
                                                    </td>
                                                    <td>
                                                        {{ $phcproduct->branch->code }} - {{ $phcproduct->branch->name }}
                                                    </td>
                                                    <td>
                                                        {{ ucwords($phcproduct->status) }}
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('detail_phc_product', ['id' => $phcproduct['id']]) }}">
                                                            <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(76 172 245);"></i>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('edit_phc_product', ['id' => $phcproduct['id']]) }}">
                                                            <i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a class="btn-delete"
                                                            data-toggle="modal"
                                                            href="#deleteDoModal"
                                                            onclick="submitDelete(this)"
                                                            data-id="{{ $phcproduct["id"] }}">
                                                            <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                    {{ $phcproducts->appends($url)->links() }}
                                </div>
                            </div>
                            <div class="tab-pane fade show {{ $url['product_id'] == 1 ? 'active' : ''}} p-3" id="six" role="tabpanel" aria-labelledby="six-tab">
                                <h5 style="margin-bottom: 0.5em;">
                                    Total: {{ $phcproducts->total() }}
                                </h5>
                                <div class="table-responsive"
                                    style="border: 1px solid #ebedf2;">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No.</th>
                                                <th>Code</th>
                                                <th>Product Name</th>
                                                <th class="text-center">Branch</th>
                                                <th>Status</th>
                                                <th class="text-center">View</th>
                                                <th class="text-center">Edit</th>
                                                <th class="text-center">Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($phcproducts as $key => $phcproduct)
                                                @if($phcproduct->product->code == 'WKT2080')
                                                <tr>
                                                    <td class="text-right">
                                                        {{ ++$i }}
                                                    </td>
                                                    <td>{{ $phcproduct->code }}</td>
                                                    <td>
                                                        {{ $phcproduct->product->name }}
                                                    </td>
                                                    <td>
                                                        {{ $phcproduct->branch->code }} - {{ $phcproduct->branch->name }}
                                                    </td>
                                                    <td>
                                                        {{ ucwords($phcproduct->status) }}
                                                    </td>
                                                    <td class="center">
                                                        <a href="{{ route('detail_phc_product', ['id' => $phcproduct['id']]) }}">
                                                            <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(76 172 245);"></i>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('edit_phc_product', ['id' => $phcproduct['id']]) }}">
                                                            <i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <a class="btn-delete"
                                                            data-toggle="modal"
                                                            href="#deleteDoModal"
                                                            onclick="submitDelete(this)"
                                                            data-id="{{ $phcproduct["id"] }}">
                                                            <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                    {{ $phcproducts->appends($url)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- partial -->
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
                    <h5 class="text-center">
                        Are you sure to delete this product?
                    </h5>
                </div>
                <div class="modal-footer">
                    <form id="frmDelete"
                        method="post"
                        action="{{ route('delete_phc_product') }}">
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
});

function submitDelete(e) {
    document.getElementById("id-delete").value = e.dataset.id;
}

</script>
@endsection
