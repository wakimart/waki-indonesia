<?php
$menu_item_page = "order";
$menu_item_second = "list_order";
?>
@extends('admin.layouts.template')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">List Order</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#order-dd"
                            aria-expanded="false"
                            aria-controls="order-dd">
                            Order
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        List Order
                    </li>
                </ol>
            </nav>
        </div>

        <div class="col-12 grid-margin" style="padding: 0;">
            @if (Auth::user()->roles[0]['slug'] != 'branch' && Auth::user()->roles[0]['slug'] != 'cso')
                @if (Utils::$lang=='id')
                    <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
                        <div class="col-xs-6 col-sm-4" style="display: inline-block;">
                            <div class="form-group">
                                <label for="">Filter By City</label>
                                    <select class="form-control"
                                        id="filter_province"
                                        name="filter_province">
                                        <option value="" selected="">
                                            All Province
                                        </option>
                                        @php
                                        $result = RajaOngkir::FetchProvince();
                                        $result = $result['rajaongkir']['results'];
                                        $arrProvince = [];
                                        if (sizeof($result) > 0) {
                                            foreach ($result as $value) {
                                                $terpilihNya = "";
                                                if (isset($_GET['filter_province'])) {
                                                    if ($_GET['filter_province'] == $value['province_id']) {
                                                        $terpilihNya = "selected";
                                                    }
                                                }

                                                echo "<option value=\""
                                                    . $value['province_id']
                                                    . "\""
                                                    . $terpilihNya
                                                    . ">"
                                                    . $value['province']
                                                    . "</option>";
                                            }
                                        }
                                        @endphp
                                </select>
                                <div class="validation"></div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-4"
                            style="display: inline-block;">
                            <div class="form-group">
                            <label style="opacity: 0;" for=""> s</label>
                                <select class="form-control"
                                    id="filter_city"
                                    name="filter_city">
                                    <option value="">All City</option>
                                    @php
                                    if (isset($_GET['filter_province'])) {
                                        $result = RajaOngkir::FetchCity($_GET['filter_province']);
                                        $result = $result['rajaongkir']['results'];
                                        $arrCity = [];
                                        $arrCity[0] = "<option disabled value=\"\">Pilihan Kabupaten</option>";
                                        $arrCity[1] = "<option disabled value=\"\">Pilihan Kota</option>";
                                        if (sizeof($result) > 0) {
                                            foreach ($result as $value) {
                                                $terpilihNya = "";
                                                if (isset($_GET['filter_city'])) {
                                                    if ($_GET['filter_city'] == $value['city_id']) {
                                                        $terpilihNya = "selected";
                                                    }
                                                }

                                                if ($value['type'] == "Kabupaten") {
                                                    $arrCity[0] .= "<option value=\""
                                                        . $value['city_id']
                                                        . "\""
                                                        . $terpilihNya
                                                        . ">"
                                                        . $value['type']
                                                        . " "
                                                        . $value['city_name']
                                                        . "</option>";
                                                } else {
                                                    $arrCity[1] .= "<option value=\""
                                                        . $value['city_id']
                                                        . "\""
                                                        . $terpilihNya
                                                        . ">"
                                                        . $value['type']
                                                        . " "
                                                        . $value['city_name']
                                                        . "</option>";
                                                }
                                            }

                                            echo $arrCity[0];
                                            echo $arrCity[1];
                                        }
                                    }
                                    @endphp
                                </select>
                                <div class="validation"></div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-4"
                            style="display: inline-block;">
                            <div class="form-group">
                            <label style="opacity: 0;" for=""> s</label>
                                <select class="form-control"
                                    id="filter_district"
                                    name="filter_district">
                                    <option value="">All District</option>
                                    @php
                                    if (isset($_GET['filter_city'])) {
                                        $result = RajaOngkir::FetchDistrict($_GET['filter_city']);
                                        $result = $result['rajaongkir']['results'];
                                        if (sizeof($result) > 0) {
                                            foreach ($result as $value) {
                                                $terpilihNya = "";
                                                if (isset($_GET['filter_district'])) {
                                                    if ($_GET['filter_district'] == $value['subdistrict_id']) {
                                                        $terpilihNya = "selected";
                                                    }
                                                }

                                                echo "<option value=\""
                                                    . $value['subdistrict_id']
                                                    . "\""
                                                    . $terpilihNya
                                                    . ">"
                                                    . $value['subdistrict_name']
                                                    . "</option>";
                                            }
                                        }
                                    }
                                    @endphp
                                </select>
                                <div class="validation"></div>
                            </div>
                        </div>
                    </div>
                @endif


                <div class="col-xs-12 col-sm-12 row"
                    style="margin: 0;padding: 0;">
                    <div class="col-xs-6 col-sm-4"
                        style="display: inline-block;">
                        <div class="form-group">
                            <label for="">Filter By Type Customer</label>
                            <select class="form-control" id="filter_type" name="filter_type">
                                <option value="">All Type</option>
                                @php
                                $selected = "";
                                if (isset($_GET['filter_type'])) {
                                    $selected = $_GET['filter_type'];
                                }
                                @endphp
                                <option {{ $selected == "Tele Voucher" ? "selected=\"\"" : "" }}
                                    value="Tele Voucher">
                                    Tele Voucher
                                </option>
                                <option {{ $selected == "Tele Home Service" ? "selected=\"\"" : "" }}
                                    value="Tele Home Service">
                                    Tele Home Service
                                </option>
                                <option {{ $selected == "Home Office Voucher" ? "selected=\"\"" : "" }}
                                    value="Home Office Voucher">
                                    Home Office Voucher
                                </option>
                                <option {{ $selected == "Home Voucher" ? "selected=\"\"" : "" }}
                                    value="Home Voucher">
                                    Home Voucher
                                </option>
                            </select>
                            <div class="validation"></div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-4"
                        style="display: inline-block;">
                        <div class="form-group">
                            <label for="">Filter By Team</label>
                            <select class="form-control"
                                id="filter_branch"
                                name="filter_branch">
                                <option value="" selected="">All Branch</option>
                                @foreach($branches as $branch)
                                    @php
                                    $selected = "";
                                    if (isset($_GET['filter_branch'])) {
                                        if ($_GET['filter_branch'] == $branch['id']) {
                                            $selected = "selected=\"\"";
                                        }
                                    }
                                    @endphp

                                    <option {{ $selected }}
                                        value="{{ $branch['id'] }}">
                                        {{ $branch['code'] }} - {{ $branch['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="validation"></div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-4"
                        style="display: inline-block;">
                        <div class="form-group">
                            <label for="">Filter By CSO</label>
                            <select class="form-control"
                                id="filter_cso"
                                name="filter_cso">
                                <option value="">All CSO</option>
                                @php
                                if (isset($_GET['filter_branch'])) {
                                    $csos = App\Cso::Where('branch_id', $_GET['filter_branch'])->where('active', true)->get();

                                    foreach ($csos as $cso) {
                                        if (isset($_GET['filter_cso'])) {
                                            if ($_GET['filter_cso'] == $cso['id']) {
                                                echo "<option selected=\"\" value=\""
                                                    . $cso['id']
                                                    . "\">"
                                                    . $cso['code']
                                                    . " - "
                                                    . $cso['name']
                                                    . "</option>";
                                                continue;
                                            }
                                        }

                                        echo "<option value=\""
                                            . $cso['id']
                                            . "\">"
                                            . $cso['code']
                                            . " - "
                                            . $cso['name']
                                            . "</option>";
                                    }
                                }
                                @endphp
                            </select>
                            <div class="validation"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 row"
                    style="margin: 0;padding: 0;">
                    <div class="col-xs-6 col-sm-4"
                        style="display: inline-block;">
                        <div class="form-group">
                            <label for="filter_string">
                                Filter by Name, Phone, or Code
                            </label>
                            <input type="text"
                                class="form-control"
                                placeholder="Name, Phone, or Code"
                                value="{{ $_GET["filter_string"] ?? "" }}"
                                id="filter_string"
                                name="filter_string">
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-4"
                        style="display: inline-block;">
                        <div class="form-group">
                            <label for="">Filter By Promo</label>
                            <select class="form-control"
                                id="filter_promo"
                                name="filter_promo">
                                <option value="" selected="">All Promo</option>
                                @foreach($promos as $promo)
                                    @php
                                    $selected = "";
                                    if (isset($_GET['filter_promo'])) {
                                        if ($_GET['filter_promo'] == $promo['id']) {
                                            $selected = "selected=\"\"";
                                        }
                                    }
                                    @endphp

                                    <option {{ $selected }}
                                        value="{{ $promo['id'] }}">
                                        {{ $promo['code'] }} ({{ $promo->productName()[0] }} - {{ $promo->productName()[1] }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="validation"></div>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-4"
                        style="display: inline-block;">
                        <div class="form-group">
                            <label for="">Filter By Status</label>
                            <select class="form-control"
                                id="filter_status"
                                name="filter_status">
                                <option value="" selected="">All Status</option>
                                @foreach(\App\Order::$status as $status)
                                    @php
                                    $selected = "";
                                    if (isset($_GET['filter_status'])) {
                                        if ($_GET['filter_status'] == $status) {
                                            $selected = "selected=\"\"";
                                        }
                                    }
                                    @endphp

                                    <option {{ $selected }}
                                        value="{{ $status }}">
                                        @if($status == \App\Order::$status['6'])
                                        request stock
                                        @elseif($status == \App\Order::$status['7'])
                                        stock approved
                                        @else
                                        {{ $status }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="validation"></div>
                        </div>
                    </div>
                </div>

                @if(Gate::check('list-order_commission'))
                    <div class="col-xs-12 col-sm-12 row"
                        style="margin: 0;padding: 0;">
                        <div class="col-xs-6 col-sm-4"
                            style="display: inline-block;">
                            <div class="form-group">
                                <label for="">
                                    View as
                                </label>
                                <select class="form-control"
                                    id="filter_view_as"
                                    name="filter_view_as">
                                    <option value="" selected="">All Status</option>
                                    <option {{ isset($_GET['filter_view_as']) ? $_GET['filter_view_as'] == 'order' ? 'selected=""' : '' : '' }} value="order">Order</option>
                                    <option {{ isset($_GET['filter_view_as']) ? $_GET['filter_view_as'] == 'commission' ? 'selected=""' : '' : '' }} value="commission">Commission</option>
                                </select>
                                <div class="validation"></div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            @if (Auth::user()->roles[0]['slug'] != 'branch' && Auth::user()->roles[0]['slug'] != 'cso' && Auth::user()->roles[0]['slug'] != 'area-manager')
                <div class="col-xs-12 col-sm-12 row"
                    style="margin: 0; padding: 0;">
                    <div class="col-xs-6 col-sm-6"
                        style="display: inline-block;">
                        <label for=""></label>
                        <div class="form-group">
                            <button id="btn-filter"
                                type="button"
                                class="btn btn-gradient-primary m-1"
                                name="filter"
                                value="-">
                                <span class="mdi mdi-filter"></span> Apply Filter
                            </button>
                            <button id="btn-report"
                                type="button"
                                class="btn btn-gradient-info m-1"
                                name="report"
                                value="-"
                                data-toggle="modal"
                                data-target="#reportOrderModal">
                                <span class="mdi mdi-filter"></span> Report Order
                            </button>
                            <button
                                type="button"
                                class="btn btn-gradient-warning m-1"                                
                                data-toggle="modal"
                                data-target="#customerLetterModal">
                                <span class="mdi mdi-filter"></span> Customer Letter
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-sm-12 col-md-12"
                style="padding: 0; border: 1px solid #ebedf2;">
                <div class="col-xs-12 col-sm-11 col-md-6 table-responsive"
                    id="calendarContainer"
                    style="padding: 0; float: left;"></div>
                <div class="col-xs-12 col-sm-11 col-md-6"
                    id="organizerContainer"
                    style="padding: 0; float: left;"></div>
            </div>
        </div>

        <div class="col-12 grid-margin stretch-card" style="padding: 0;">
            <div class="card">
                <div class="card-body">
                    <h5 style="margin-bottom: 0.5em;">
                        Total : {{ $countOrders }} data
                    </h5>
                    <div class="table-responsive" style="border: 1px solid #ebedf2;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th> No. </th>
                                    <th> Order Code </th>
                                    <th> Temp No </th>
                                    <th> @sortablelink('orderDate', 'Order Date') </th>
                                    <th> Member Name </th>
                                    <th> Type Customer </th>
                                    <th> Branch & CSO</th>
                                    <th> Total Price </th>
                                    <th> Total Payment </th>
                                    <th> Status </th>
                                    {{-- <th colspan="2"> Product </th> --}}
                                    @if (Gate::check('detail-order') || Gate::check('edit-order') || Gate::check('delete-order'))
                                        <th colspan="3"> View / Edit / Delete </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $key => $order)
                                    @php
                                    $ProductPromos = json_decode($order['product'], true);
                                    // $totalProduct = count($ProductPromos);
                                    @endphp
                                    <tr style="background-color: {{isset($_GET['filter_view_as']) ? $_GET['filter_view_as'] == 'commission' ? count($order->orderCommission) == 0 ? '#fed71354' : '' : '' : ''}}">
                                        <td {{-- rowspan="{{ $totalProduct }}" --}} >
                                            {{ $key + 1 }}
                                        </td>
                                        <td  {{-- rowspan="{{ $totalProduct }}" --}} >
                                            <a href="{{ route('detail_order') }}?code={{ $order['code'] }}">
                                                {{ $order['code'] }}
                                            </a>
                                        </td>
                                        <td {{-- rowspan="{{ $totalProduct }}" --}}>
                                            {{ $order['temp_no'] }}
                                        </td>
                                        <td {{-- rowspan="{{ $totalProduct }}" --}}>
                                            {{ date("d/m/Y", strtotime($order['orderDate'])) }}
                                        </td>
                                        <td {{-- rowspan="{{ $totalProduct }}"  --}}>
                                            {{ $order['name'] }}
                                        </td>
                                        <td {{-- rowspan="{{ $totalProduct }}" --}}>
                                            {{ $order['customer_type'] }}
                                        </td>
                                        <td {{-- rowspan="{{ $totalProduct }}" --}}>
                                            {{ $order->branch['code'] }} - {{ $order->branch['name'] }}
                                            <br>
                                            {{ $order->cso['code'] }} - {{ $order->cso['name'] }}
                                        </td>
                                        <td>Rp. {{ number_format($order['total_payment']) }}</td>
                                        <td>Rp. {{ number_format($order->orderPayment->sum('total_payment')) }}</td>
                                        <td class="text-center">
                                            @if ($order['status'] == \App\Order::$status['1'])
                                                <span class="badge badge-secondary">New</span>
                                            @elseif ($order['status'] == \App\Order::$status['2'])
                                                <span class="badge badge-primary">Process</span>
                                            @elseif($order['status'] == \App\Order::$status['6'])
                                                <span class="badge" style="background-color: #FFD495">Request Stock</span>
                                            @elseif($order['status'] == \App\Order::$status['7'])
                                                <span class="badge text-white" style="background-color: #C147E9">Stock Approved</span>
                                            @elseif ($order['status'] == \App\Order::$status['3'])
                                                <span class="badge badge-warning">Delivery</span>
                                            @elseif($order['status'] == \App\Order::$status['8'])
                                                <span class="badge text-white" style="background-color: #FF6E31">Delivered</span>
                                            @elseif ($order['status'] == \App\Order::$status['4'])
                                                <span class="badge badge-success">Success</span>
                                            @elseif ($order['status'] == \App\Order::$status['5'])
                                                <span class="badge badge-danger">Reject</span>
                                            @endif
                                        </td>
                                        {{-- @foreach($ProductPromos as $ProductPromo)
                                            @if (isset(App\DeliveryOrder::$Promo[$ProductPromo['id']]))
                                                <td>
                                                    {{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['code'] }} - {{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['name'] }} ( {{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['harga'] }} )
                                                </td>
                                            @else
                                                <td>
                                                    {{ $ProductPromo['id'] }}
                                                </td>
                                            @endif
                                            <td>{{ $ProductPromo['qty'] }}</td>
                                            @php break; @endphp
                                        @endforeach --}}
                                        @can('detail-order')
                                            <td {{-- rowspan="{{ $totalProduct }}" --}}
                                                style="text-align: center;">
                                                <a href="{{ route('detail_order') }}?code={{ $order['code'] }}">
                                                    <i class="mdi mdi-eye" style="font-size: 24px; color:#33b5e5;"></i>
                                                </a>
                                            </td>
                                        @endcan
                                        @can('edit-order')
                                            @if($order['status'] == 'new' || Auth::user()->inRole("head-admin"))
                                                <td {{-- rowspan="{{ $totalProduct }}" --}}
                                                    style="text-align: center;">
                                                    <a href="{{ route('edit_order', ['id' => $order['id']]) }}">
                                                        <i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
                                                    </a>
                                                </td>
                                            @endif
                                        @endcan
                                        @can('delete-order')
                                            @if($order['status'] == 'new' || Auth::user()->inRole("head-admin"))
                                                <td style="text-align: center;">
                                                    <button value="{{ route('delete_order', ['id' => $order['id']])}}"
                                                        data-toggle="modal"
                                                        data-target="#deleteDoModal"
                                                        class="btn-delete">
                                                        <i class="mdi mdi-delete" style="font-size: 24px; color:#fe7c96;"></i>
                                                    </button>
                                                </td>
                                            @endif
                                        @endcan
                                    </tr>
                                    @php $first = true; @endphp
                                    {{-- @foreach ($ProductPromos as $ProductPromo)
                                        @php
                                        if ($first) {
                                            $first = false;
                                            continue;
                                        }
                                        @endphp
                                        <tr>
                                            @if (is_numeric($ProductPromo['id']))
                                                <td>
                                                    {{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['code'] }} - {{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['name'] }} ( {{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['harga'] }} )
                                                </td>
                                            @else
                                                <td>
                                                    {{ $ProductPromo['id'] }}
                                                </td>
                                            @endif

                                            <td>{{ $ProductPromo['qty'] }}</td>
                                        </tr>
                                    @endforeach --}}
                                @endforeach
                            </tbody>
                        </table>
                        <br/>
                        {!! $orders->appends(\Request::except('page'))->render() !!}
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
                    Are You Sure to Delete this Order?
                </h5>
            </div>
            <div class="modal-footer">
                <form id="frmDelete" method="post" action="">
                    {{ csrf_field() }}
                    <button type="submit"
                        class="btn btn-gradient-danger mr-2">
                        Yes
                    </button>
                </form>
                <button class="btn btn-light" data-dismiss="modal">
                    No
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Delete -->

<!-- Modal Report -->
<div class="modal fade"
    id="reportOrderModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label>Report Order</label>
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
                    <div class="col-xs-6 col-sm-6" style="display: inline-block;">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date"
                                class="form-control"
                                name="start_p"
                                id="start_p"
                                required
                                data-msg="Mohon Isi Tanggal"
                                onload="getDate()" />
                            <div class="validation"></div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6" style="display: inline-block;">
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date"
                                class="form-control"
                                name="end_orderDate"
                                id="end_orderDate"
                                required
                                data-msg="Mohon Isi Tanggal"
                                onload="getDate()" />
                            <div class="validation"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
                    <div class="col-xs-6 col-sm-6" style="display: inline-block;">
                        <div class="form-group">
                            <label for="">Province</label>
                            <select class="form-control"
                                id="province"
                                name="province_id"
                                data-msg="Mohon Pilih Provinsi">
                                <option selected disabled value="">
                                    Pilihan Provinsi
                                </option>
                                @php
                                $result = RajaOngkir::FetchProvince();
                                $result = $result['rajaongkir']['results'];
                                $arrProvince = [];
                                if (sizeof($result) > 0) {
                                    foreach ($result as $value) {
                                        echo "<option value=\""
                                            . $value['province_id']
                                            . "\">"
                                            . $value['province']
                                            . "</option>";
                                    }
                                }
                                @endphp
                            </select>
                            <div class="validation"></div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6"
                        style="display: inline-block;">
                        <div class="form-group">
                            <label for="">City</label>
                            <select class="form-control"
                                id="city"
                                name="city"
                                data-msg="Mohon Pilih Kota">
                                <option selected disabled value="">
                                    Pilihan Kota
                                </option>
                            </select>
                            <div class="validation"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 row"
                    style="margin: 0; padding: 0;">
                    <div class="col-xs-6 col-sm-6"
                        style="display: inline-block;">
                        <div class="form-group">
                            <label for="">Filter By Team</label>
                            <select class="form-control"
                                id="filter_branch_modal"
                                name="filter_branch_modal">
                                <option value="" selected>
                                    All Branch
                                </option>
                                @foreach($branches as $branch)
                                    @php
                                    $selected = "";
                                    if (isset($_GET['filter_branch'])) {
                                        if ($_GET['filter_branch'] == $branch['id']) {
                                            $selected = "selected=\"\"";
                                        }
                                    }
                                    @endphp

                                    <option {{ $selected }}
                                        value="{{ $branch['id'] }}">
                                        {{ $branch['code'] }} - {{ $branch['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="validation"></div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6" style="display: inline-block;">
                        <div class="form-group">
                        <label for="">Filter By CSO</label>
                            <select class="form-control" id="report_cso_modal" name="report_cso_modal">
                            <option value="">All CSO</option>
                            @php
                            if (isset($_GET['filter_branch'])) {
                                $csos = App\Cso::Where('branch_id', $_GET['filter_branch'])->where('active', true)->get();

                                foreach ($csos as $cso) {
                                    if (isset($_GET['filter_cso_modal'])) {
                                        if ($_GET['filter_cso_model'] == $cso['id']) {
                                            echo "<option selected=\"\" value=\""
                                                . $cso['id']
                                                . "\">"
                                                . $cso['code']
                                                . " - "
                                                . $cso['name']
                                                . "</option>";
                                            continue;
                                        }
                                    }
                                    echo "<option value=\""
                                        . $cso['id']
                                        . "\">"
                                        . $cso['code']
                                        . " - "
                                        . $cso['name']
                                        . "</option>";
                                }
                            }
                            @endphp
                            </select>
                            <div class="validation"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Filter By Promo</label>
                    <select class="form-control"
                        id="filter_promo_modal"
                        name="filter_promo_modal">
                        <option value="">
                            All Promo & Product
                        </option>
                        <option value="promo" selected>
                            Only All Promo
                        </option>
                        @foreach($promos as $promo)
                            @php
                            $selected = "";
                            if (isset($_GET['filter_promo_modal'])) {
                                if ($_GET['filter_promo_modal'] == $promo['id']) {
                                    $selected = "selected=\"\"";
                                }
                            }
                            @endphp

                            <option {{ $selected }} value="{{ $promo['id'] }}">
                                {{ $promo['code'] }} ({{ $promo->productName()[0] }} - {{ $promo->productName()[1] }})
                            </option>
                        @endforeach
                    </select>
                    <div class="validation"></div>
                </div>

                <div class="form-group">
                    <label>Filter By Status</label>
                    <select class="form-control" id="filter_status_modal" name="filter_status_modal">
                        <option value="" selected="">All Status</option>
                        @foreach(\App\Order::$status as $status)                        
                            <option value="{{ $status }}">
                                @if($status == \App\Order::$status['6'])
                                    request stock
                                @elseif($status == \App\Order::$status['7'])
                                    stock approved
                                @else
                                    {{ $status }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <div class="validation"></div>
                </div>

                <div class="form-group">
                    <label>Export Type</label>
                    <select class="form-control" id="filter_export_type_modal" name="filter_export_type_modal">
                        <option value="default" selected="">Default</option>
                        <option value="non-komisi">Commission Not Set</option>
                    </select>
                    <div class="validation"></div>
                </div>

                <div class="modal-footer">
                    {{ csrf_field() }}
                    <button type="submit"
                        id="pdfCustomerLetterButton"
                        class="btn btn-gradient-primary mr-2">
                        Download
                    </button>
                    <button class="btn btn-light" data-dismiss="modal">
                        No
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Report -->
</div>

<!-- Modal Report -->
<div class="modal fade"
    id="customerLetterModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label>Customer Letter Download</label>
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('customer_letter')}}" method="post">
                    {{ method_field('PUT') }}
                    @csrf
                    <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
                        <div class="col-xs-6 col-sm-6" style="display: inline-block;">
                            <div class="form-group">
                                <label>Start Date</label>
                                <input type="date"
                                    class="form-control"
                                    name="start_date"                                
                                    required
                                    data-msg="Mohon Isi Tanggal"
                                    value="{{ date('Y-m-01') }}" />
                                <div class="validation"></div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6" style="display: inline-block;">
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="date"
                                    class="form-control"
                                    name="end_date"
                                    required
                                    data-msg="Mohon Isi Tanggal"
                                    value="{{ date('Y-m-d') }}" />
                                <div class="validation"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 row"
                        style="margin: 0; padding: 0;">
                        <div class="col-xs-12 col-sm-12"
                            style="display: inline-block;">
                            <div class="form-group">
                                <label for="">Filter By Team</label>
                                <select class="form-control"
                                    name="filter_by_team">
                                    <option value="" selected>
                                        All Branch
                                    </option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch['id'] }}">
                                            {{ $branch['code'] }} - {{ $branch['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="validation"></div>
                            </div>
                        </div>
                    </div>                    
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-gradient-primary mr-2">
                            Download
                        </button>
                        <button class="btn btn-light" data-dismiss="modal">
                            No
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Report -->
@endsection

@section('script')
<script>
$(document).ready(function(e){
    $("#filter_branch").on("change", function () {
        var id = $(this).val();
        $.get('{{ route("fetchCsoByIdBranch", ['branch' => ""]) }}/' + id)
            .done(function (result) {
                $("#filter_cso").html("");
                var arrCSO = "<option selected value=\"\">All CSO</option>";
                if(result.length > 0){
                    $.each(result, function (key, value) {
                        arrCSO += "<option value=\""
                            + value['id']
                            + "\">"
                            + value['code']
                            + " - "
                            + value['name']
                            + "</option>";
                    });
                    $( "#filter_cso" ).append(arrCSO);
                }
            });

        if (id == "") {
            $("#filter_cso").html("<option selected value=\"\">All CSO</option>");
        }
    });

    $("#filter_province, #edit-province").on("change", function () {
        var id = $(this).val();
        var domCity = $("#filter_city");

        if (this.id == "edit-province") {
            domCity = $( "#edit-city" );
            $(domCity).html("");
        } else {
            $(domCity).html("");
            $(domCity).append("<option selected value=\"\">All City</option>");
        }

        $.get( '{{ route("fetchCity", ['province' => ""]) }}/' + id)
            .done(function( result ) {
                result = result['rajaongkir']['results'];
                var arrCity = [];
                arrCity[0] = "<option disabled value=\"\">Pilihan Kabupaten</option>";
                arrCity[1] = "<option disabled value=\"\">Pilihan Kota</option>";

                if (result.length > 0) {
                    $.each(result, function (key, value) {
                        if (value['type'] == "Kabupaten") {
                            arrCity[0] += "<option value=\""
                                + value['city_id']
                                + "\">"
                                + value['type']
                                + " "
                                + value['city_name']
                                + "</option>";
                        } else {
                            arrCity[1] += "<option value=\""
                                + value['city_id']
                                + "\">"
                                + value['type']
                                + " "
                                + value['city_name']
                                + "</option>";
                        }
                    });
                    $(domCity).append(arrCity[0]);
                    $(domCity).append(arrCity[1]);
                }
            });
    });

    $("#filter_city, #edit-city").on("change", function(){
        var id = $(this).val();
        var domDistrict = $( "#filter_district" );

        if (this.id == "edit-city") {
            domDistrict = $( "#edit-distric" );
            $(domDistrict).html("");
        } else {
            $(domDistrict).html("");
            $(domDistrict).append("<option selected value=\"\">All District</option>");
        }

        $(domDistrict).html("");
        $.get('{{ route("fetchDistrict", ['city' => ""]) }}/' + id)
            .done(function( result ) {
                result = result['rajaongkir']['results'];
                var arrdistrict = "";
                if (result.length > 0) {
                    $.each( result, function( key, value ) {
                        arrdistrict += "<option value=\""
                            + value['subdistrict_id']
                            + "\">"
                            + value['subdistrict_name']
                            + "</option>";
                    });
                    $(domDistrict).append(arrdistrict);
                }
            });
    });

    $(".btn-delete").click(function(e) {
        $("#frmDelete").attr("action",  $(this).val());
    });

    $("#province").on("change", function () {
        var id = $(this).val();
        $("#city").html("");

        $.get('{{ route("fetchCity", ['province' => ""]) }}/' + id)
            .done(function( result ) {
                result = result['rajaongkir']['results'];
                var arrCity = "<option selected disabled value=\"\">Pilihan Kota</option>";

                if (result.length > 0) {
                    $.each(result, function (key, value) {
                        if (value['type'] == "Kota") {
                            arrCity += "<option value=\""
                                + value['city_id']
                                + "\">Kota "
                                + value['city_name']
                                + "</option>";
                        }
                    });

                    $( "#city" ).append(arrCity);
                }
            });
        });

    $("#filter_branch_modal").on("change", function () {
        var id = $(this).val();

        $.get('{{ route("fetchCsoByIdBranch", ['branch' => ""]) }}/' + id)
            .done(function (result) {
                $("#report_cso_modal").html("");
                var arrCSO = "<option selected value=\"\">All CSO</option>";

                if (result.length > 0) {
                    $.each(result, function(key, value) {
                        arrCSO += "<option value=\""
                            + value['id']
                            + "\">"
                            + value['code']
                            + " - "
                            + value['name']
                            + "</option>";
                    });

                    $( "#report_cso_modal" ).append(arrCSO);
                }
            });

        if (id == "") {
            $( "#report_cso_modal" ).html("<option selected value=\"\">All CSO</option>");
        }
    });
});

$(document).on("click", "#btn-filter", function (e) {
    var urlParamArray = new Array();
    var urlParamStr = "";

    if ($('#filter_province').val() != "") {
        urlParamArray.push("filter_province=" + $('#filter_province').val());
    }

    if ($('#filter_city').val() != "") {
        urlParamArray.push("filter_city=" + $('#filter_city').val());
    }

    if ($('#filter_district').val() != "") {
        urlParamArray.push("filter_district=" + $('#filter_district').val());
    }

    if ($('#filter_branch').val() != "") {
        urlParamArray.push("filter_branch=" + $('#filter_branch').val());
    }

    if ($('#filter_cso').val() != "") {
        urlParamArray.push("filter_cso=" + $('#filter_cso').val());
    }

    if ($('#filter_type').val() != "") {
        urlParamArray.push("filter_type=" + $('#filter_type').val());
    }

    if ($('#filter_string').val() != "") {
        urlParamArray.push("filter_string=" + $('#filter_string').val());
    }

    if ($('#filter_promo').val() != "") {
        urlParamArray.push("filter_promo=" + $('#filter_promo').val());
    }

    if ($('#filter_status').val() != "") {
        urlParamArray.push("filter_status=" + $('#filter_status').val());
    }

    if ($('#filter_view_as').val() != "") {
        urlParamArray.push("filter_view_as=" + $('#filter_view_as').val());
    }

    for (var i = 0; i < urlParamArray.length; i++) {
        if (i === 0) {
            urlParamStr += "?" + urlParamArray[i]
        } else {
            urlParamStr += "&" + urlParamArray[i]
        }
    }

    window.location.href = "{{route('admin_list_order')}}" + urlParamStr;
});
</script>
@endsection
