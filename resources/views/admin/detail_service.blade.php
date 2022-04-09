<?php
$menu_item_page = "service";
$menu_item_second = "detail_service";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    #intro {
        padding-top: 2em;
    }

    button {
        background: #1bb1dc;
        border: 0;
        border-radius: 3px;
        padding: 8px 30px;
        color: #fff;
        transition: 0.3s;
    }

    table {
        margin: 1em;
        font-size: 14px;
    }

    table thead {
        background-color: #8080801a;
        text-align: center;
    }

    table td {
        border: 0.5px #8080801a solid;
        padding: 0.5em;
    }

    .center {
        text-align: center;
    }

    .right {
        text-align: right;
    }

    .justify-content-center {
        padding: 0em 1em;
    }

    .share {
        padding-bottom: 20px;
    }

    /*-- mobile --*/
    @media (max-width: 768px) {
        .share {
            font-size: 1.7em;
            text-align: center;
            padding-bottom: 20px;
        }
    }

    @media (min-width: 768px) {
        .table-responsive::-webkit-scrollbar {
            display: none;
        }
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Detail Service</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#"
                            aria-expanded="false"
                            aria-controls="upgrade-dd">
                            Service
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Detail Service
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <h2>Detail Service</h2>
                        </div>
                        <div class="row justify-content-center">
                            <div class="table-responsive">
                                <table class="col-md-12">
                                    <thead>
                                        <td>Status</td>
                                        <td>Service Code</td>
                                        <td>Service Date</td>
                                    </thead>
                                    <tr>
                                        <td class="center">
                                            @if (strtolower($services['status']) == "new")
                                                <span class="badge badge-secondary">
                                                    New
                                                </span>
                                            @elseif (strtolower($services['status']) == "process")
                                                <span class="badge badge-primary">
                                                    Process by: {{ $services->statusBy("process")->user_id['name'] }}
                                                </span>
                                            @elseif (strtolower($services['status']) == "repaired")
                                                <span class="badge badge-warning">
                                                    Repaired by: {{ $services->statusBy("repaired")->user_id['name'] }}
                                                </span>
                                            @elseif (strtolower($services['status']) == "quality control")
                                                <span class="badge badge-warning">
                                                    Quality Control by: {{ $services->statusBy("quality_control")->user_id['name'] }}
                                                </span>
                                            @elseif (strtolower($services['status']) == "delivery")
                                                <span class="badge badge-info">
                                                    Delivery by: {{ $services->statusBy("delivery")->user_id['name'] }}
                                                </span>
                                            @elseif (strtolower($services['status']) == "pickup")
                                                <span class="badge badge-info">
                                                    Pickup by: {{ $services->statusBy("pickup")->user_id['name'] }}
                                                </span>
                                            @elseif (strtolower($services['status']) == "completed")
                                                <span class="badge badge-success">
                                                    Completed by: {{ $services->statusBy("completed")->user_id['name'] }}
                                                </span>
                                            @elseif (strtolower($services['status']) == "cancel")
                                                <span class="badge badge-danger">
                                                    Cancel by: {{ $services->statusBy("cancel")->user_id['name'] }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="center">
                                            {{ $services['code'] }}
                                        </td>
                                        <td class="center">
                                            {{ date("d/m/Y", strtotime($services['service_date'])) }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="table-responsive">
                                <table class="col-md-12">
                                    <thead>
                                        <td colspan="2">Data Service</td>
                                    </thead>
                                    <tr>
                                        <td>No. MPC:</td>
                                        @if($services['no_mpc'] != null)
                                            <td>{{ $services['no_mpc'] }}</td>
                                        @else
                                            <td>-</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>Customer Name: </td>
                                        <td>{{ $services['name'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Customer Phone: </td>
                                        <td>{{ $services['phone'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Customer Address: </td>
                                        <td>{{ $services['address'] }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            @foreach($services->product_services as $key => $product_service)
                                <div class="table-responsive">
                                    <table class="col-md-12">
                                        <thead>
                                            <td colspan="5">
                                                Data Product Service {{ $key + 1 }}
                                            </td>
                                        </thead>
                                        <tr>
                                            <td>Product:</td>
                                            @if($product_service['product_id'] != null)
                                                <td colspan="4">
                                                    {{ $product_service->product['name'] }}
                                                </td>
                                            @elseif($product_service['product_id'] == null)
                                                <td colspan="4">
                                                    {{ $product_service['other_product'] }}
                                                </td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td>Issues:</td>
                                            @php
                                            $issues = json_decode($product_service['issues']);
                                            @endphp
                                            <td colspan="4">
                                                {{ implode(",", $issues[0]->issues) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Description:</td>
                                            <td colspan="4">
                                                {{ $issues[1]->desc }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Due Date:</td>
                                            <td colspan="4">
                                                {{ date("d/m/Y", strtotime($product_service['due_date'])) }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                @if ($product_service['sparepart'] != null)
                                    @php
                                    $arr_sparepart = json_decode($product_service['sparepart']);
                                    $count_sparepart = count($arr_sparepart);
                                    @endphp
                                    <div class="table-responsive">
                                        <table class="col-md-12">
                                            <thead>
                                                <td colspan="5">
                                                    Detail Sparepart
                                                </td>
                                            </thead>
                                            <tr>
                                                <td>No.</td>
                                                <td>Sparepart</td>
                                                <td>Qty</td>
                                                <td>Price (Rp)</td>
                                                <td>Total (Rp)</td>
                                            </tr>
                                            <tr>
                                                @foreach ($arr_sparepart as $index => $item)
                                                    @php
                                                    $unit_price = $product_service->getSparepart($item->id)->id['price'];
                                                    $total_price = (int)$item->qty * $unit_price;
                                                    @endphp
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        {{ $product_service->getSparepart($item->id)->id['name'] }}
                                                    </td>
                                                    <td>{{ $item->qty }}</td>
                                                    <td>
                                                        {{ number_format($unit_price) }}
                                                    </td>
                                                    <td>
                                                        {{ number_format($total_price) }}
                                                    </td>
                                                    @php break; @endphp
                                                @endforeach
                                            </tr>
                                            @php $first = true; @endphp
                                            @for ($i = 0; $i < $count_sparepart; $i++)
                                                @php
                                                if ($first) {
                                                    $first = false;
                                                    continue;
                                                }
                                                $unit_price_two = $product_service->getSparepart($arr_sparepart[$i]->id)->id['price'];
                                                $total_price_two = (int)$arr_sparepart[$i]->qty * $unit_price_two;
                                                @endphp
                                                <tr>
                                                    <td>{{ $i + 1 }}</td>
                                                    <td>
                                                        {{ $product_service->getSparepart($arr_sparepart[$i]->id)->id['name'] }}
                                                    </td>
                                                    <td>
                                                        {{ $arr_sparepart[$i]->qty }}
                                                    </td>
                                                    <td>
                                                        {{ number_format($unit_price_two) }}
                                                    </td>
                                                    <td>
                                                        {{ number_format($total_price_two) }}
                                                    </td>
                                                </tr>
                                                @endfor
                                        </table>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($services['status'] == 'Delivery' || $services['status'] == 'Pickup')
            @php
            $arr_serviceoption = $services->ServiceOptionDelivery();
            @endphp
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="table-responsive">
                                    <table class="col-md-12 col-sm-12 col-xs-12">
                                        <thead>
                                            <td colspan="5">
                                                {{ $services['status'] }} Detail
                                            </td>
                                        </thead>
                                        <tr>
                                            <td>Recipient's Name: </td>
                                            <td>
                                                {{ $arr_serviceoption->recipient_name }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Recipient's Phone: </td>
                                            <td>
                                                {{ $arr_serviceoption->recipient_phone }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Address: </td>
                                            <td>
                                                {{ $arr_serviceoption->address }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Branch: </td>
                                            <td>
                                                {{ $services->getDetailSales($arr_serviceoption->branch_id, $arr_serviceoption->cso_id)->branch['code'] }} - {{ $services->getDetailSales($arr_serviceoption->branch_id, $arr_serviceoption->cso_id)->branch['name'] }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>CSO: </td>
                                            <td>
                                                {{ $services->getDetailSales($arr_serviceoption->branch_id, $arr_serviceoption->cso_id)->cso['code'] }} - {{ $services->getDetailSales($arr_serviceoption->branch_id, $arr_serviceoption->cso_id)->cso['name'] }}
                                            </td>
                                        </tr>
                                        @if(isset($arr_serviceoption->appointment))
                                            <tr>
                                                <td>Tanggal: </td>
                                                <td>
                                                    {{ date("d/m/Y", strtotime($arr_serviceoption->appointment)) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Jam: </td>
                                                <td>
                                                    {{ date("H:i", strtotime($arr_serviceoption->appointment)) }}
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($services['history_status'] != null)
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="table-responsive">
                                    <table class="col-md-12 col-sm-12 col-xs-12">
                                        <thead>
                                            <td colspan="3">History Status</td>
                                        </thead>
                                        <tr>
                                            <td class="center">Status</td>
                                            <td class="center">User</td>
                                            <td class="center">Date</td>
                                        </tr>
                                        @foreach(json_decode($services['history_status'], true) as $history_status)
                                            <tr>
                                                <td class="center">
                                                    @if (strtolower($history_status['status']) == "process")
                                                        <span class="badge badge-primary">
                                                            Process
                                                        </span>
                                                    @elseif (strtolower($history_status['status']) == "repaired")
                                                        <span class="badge badge-warning">
                                                            Repaired
                                                        </span>
                                                    @elseif (strtolower($history_status['status']) == "quality_control")
                                                        <span class="badge badge-warning">
                                                            Quality Control
                                                        </span>
                                                    @elseif (strtolower($history_status['status']) == "delivery")
                                                        <span class="badge badge-info">
                                                            Delivery
                                                        </span>
                                                    @elseif (strtolower($history_status['status']) == "pickup")
                                                        <span class="badge badge-info">
                                                            Pickup
                                                        </span>
                                                    @elseif (strtolower($history_status['status']) == "completed")
                                                        <span class="badge badge-success">
                                                            Completed
                                                        </span>
                                                    @elseif (strtolower($history_status['status']) == "cancel")
                                                        <span class="badge badge-danger">
                                                            Cancel
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="center">
                                                    {{ $services->statusBy(strtolower($history_status['status']))->user_id['name'] }}
                                                </td>
                                                <td class="center">
                                                    {{ date("d/m/Y", strtotime($history_status['updated_at'])) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <?php if (strtolower($services->status) === "repaired"): ?>
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <form id="actionAdd"
                                    class="forms-sample"
                                    method="POST"
                                    action="<?php echo route("update_service_status"); ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden"
                                        name="id"
                                        value="<?php echo $services->id; ?>" />
                                    @can('change-status-qc-service')
                                        <button id="upgradeProcess"
                                            type="submit"
                                            class="btn btn-gradient-primary btn-lg"
                                            name="status"
                                            value="Quality_Control">
                                            Quality Control
                                        </button>
                                    @endcan
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif (strtolower($services->status) === "quality control"): ?>
            @can('change-status-delivery-service')
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="row justify-content-center"
                                    style="margin-bottom: 1em">
                                    <h3>Delivery / Pickup Detail</h3>
                                </div>
                                <form id="actionAdd"
                                    class="forms-sample"
                                    method="POST"
                                    action="<?php echo route("update_service_status"); ?>">
                                    <?php echo csrf_field(); ?>

                                    <div class="form-group">
                                        <label for="">Delivery / Pickup</label>
                                        <select class="form-control"
                                            id="status"
                                            name="status"
                                            data-msg="Mohon Pilih Tipe"
                                            required>
                                            <option value="Delivery" {{ isset($request->input['status']) ? ( $request->input['status'] == "Delivery" ? "selected" : "" ) : "" }}>
                                                Delivery
                                            </option>
                                            <option value="Pickup" {{ isset($request->input['status']) ? ( $request->input['status'] == "Pickup" ? "selected" : "" ) : "" }}>
                                                Pickup
                                            </option>
                                        </select>
                                        <div class="validation"></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="name">Recipient's Name</label>
                                        <input type="text"
                                            class="form-control"
                                            id="name"
                                            name="name"
                                            placeholder="Name"
                                            required
                                            {{ isset($request->input['name']) ? "value=".$request->input['name'] : "" }}>
                                        <div class="validation"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Province</label>
                                        <select class="form-control"
                                            id="province"
                                            name="province_id"
                                            data-msg="Mohon Pilih Provinsi"
                                            required>
                                            <option selected disabled value="">
                                                Pilihan Provinsi
                                            </option>
                                            @php
                                            $result = RajaOngkir::FetchProvince();
                                            $result = $result['rajaongkir']['results'];
                                            if (sizeof($result) > 0) {
                                                foreach ($result as $value) {
                                                    $selected = "";
                                                    if (isset($request->input['province_id'])) {
                                                        if ($value['province_id'] == $request->input['province_id']) {
                                                            $selected = "selected";
                                                        }
                                                    }
                                                    echo "<option "
                                                        . $selected
                                                        . " value=\""
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
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <select class="form-control"
                                            id="city"
                                            name="city"
                                            data-msg="Mohon Pilih Kota"
                                            required>
                                            <option selected disabled value="">
                                                Pilihan Kota
                                            </option>
                                            @php
                                            if (isset($request->input['city']) && isset($request->input['province_id'])) {
                                                $result = RajaOngkir::FetchCity($request->input['province_id']);
                                                $result = $result['rajaongkir']['results'];
                                                if (sizeof($result) > 0) {
                                                    foreach ($result as $value) {
                                                        $selected = "";
                                                        if ($value['city_id'] == $request->input['city']) {
                                                            $selected = "selected";
                                                        }
                                                        echo "<option "
                                                            . $selected
                                                            . " value=\""
                                                            . $value['city_id']
                                                            . "\">"
                                                            . $value['type']
                                                            . " "
                                                            . $value['city_name']
                                                            . "</option>";
                                                    }
                                                }
                                            }
                                            @endphp
                                        </select>
                                        <div class="validation"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="subDistrict">
                                            Sub-District
                                        </label>
                                        <select class="form-control"
                                            id="subDistrict"
                                            name="subDistrict"
                                            data-msg="Mohon Pilih Kecamatan"
                                            required>
                                            <option selected disabled value="">
                                                Pilihan Kecamatan
                                            </option>
                                            @php
                                            if (isset($request->input['subDistrict']) && isset($request->input['city'])) {
                                                $result = RajaOngkir::FetchDistrict($request->input['city']);
                                                $result = $result['rajaongkir']['results'];
                                                if (sizeof($result) > 0) {
                                                    foreach ($result as $value) {
                                                        $selected = "";
                                                        if ($value['subdistrict_id'] == $request->input['subDistrict']) {
                                                            $selected = "selected";
                                                        }
                                                        echo "<option "
                                                            . $selected
                                                            . " value=\""
                                                            . $value['subdistrict_id']
                                                            . "\">"
                                                            . $value['subdistrict_name']
                                                            . "</option>";
                                                    }
                                                }
                                            }
                                            @endphp
                                        </select>
                                        <div class="validation"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">
                                            Address
                                        </label>
                                        <textarea class="form-control"
                                            id="address"
                                            name="address"
                                            rows="4"
                                            placeholder="Address"
                                            required>{{ isset($request->input['address']) ? $request->input['address'] : "" }}</textarea>
                                        <div class="validation"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="number"
                                            class="form-control"
                                            id="phone"
                                            name="phone"
                                            placeholder="Phone Number"
                                            required {{ isset($request->input['phone']) ? "value=".$request->input['phone'] : "" }} />
                                        <div class="validation"></div>
                                    </div>

                                    <div class="form_appoint_container"
                                        style="display: initial;">
                                        <label for="" style="margin-top: 1em;">
                                            <h2>Data Schedule </h2>
                                        </label>
                                        <br />
                                        <div class="form-group">
                                            <label for="date">
                                                Tanggal Janjian
                                            </label>
                                            <input type="date"
                                                class="form-control"
                                                name="date"
                                                id="date"
                                                placeholder="Tanggal Janjian"
                                                required
                                                data-msg="Mohon Isi Tanggal"
                                                value="{{ isset($request->input['date']) ? $request->input['date'] : date('Y-m-d') }}" />
                                            <div class="validation"></div>
                                            <span class="invalid-feedback">
                                                <strong></strong>
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <label for="time">
                                                Jam Janjian
                                            </label>
                                            <input type="time"
                                                class="form-control"
                                                name="time"
                                                id="time"
                                                placeholder="Jam Janjian"
                                                required
                                                data-msg="Mohon Isi Jam"
                                                min="10:00"
                                                max="20:00"
                                                value="{{ isset($request->input['time']) ? $request->input['time'] : date('H:i') }}" />
                                            <div class="validation"></div>
                                            <span class="invalid-feedback">
                                                <strong></strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="" style="margin-top: 1em;">
                                            <h2>Data Sales </h2>
                                        </label>
                                        <br />
                                        <label for="branch">Branch</label>
                                        <select class="form-control"
                                            id="branch"
                                            name="branch_id"
                                            data-msg="Mohon Pilih Cabang"
                                            required>
                                            <option selected disabled value="">
                                                Choose Branch
                                            </option>
                                            @foreach($branches as $branch)
                                                <option {{ isset($request->input['branch_id']) ? ( $request->input['branch_id'] == $branch['id'] ? "selected" : "" ) : "" }}
                                                    value="{{ $branch['id'] }}">
                                                    {{ $branch['code'] }} - {{ $branch['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="validation"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cso">CSO Code</label>
                                        <input type="text"
                                            class="form-control"
                                            name="cso_id"
                                            id="cso"
                                            placeholder="CSO Code"
                                            required
                                            data-msg="Mohon Isi Kode CSO"
                                            style="text-transform: uppercase;"
                                            value="{{ isset($request->input['cso_id']) ? $request->input['cso_id'] : (Auth::user()->roles[0]['slug'] == 'cso' ? Auth::user()->cso['code'] : "") }}" {{ Auth::user()->roles[0]['slug'] == 'cso' ? "readonly=\"\"" : "" }} />
                                        <div class="validation" id="validation_cso"></div>
                                    </div>

                                    <input type="hidden"
                                        name="id"
                                        value="<?php echo $services->id; ?>" />
                                    <button id="upgradeProcess"
                                        type="submit"
                                        class="btn btn-gradient-primary btn-lg"
                                        name="submit"
                                        value="-">
                                        Save
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        <?php elseif (strtolower($services->status) === "delivery" || strtolower($services->status) === "pickup"): ?>
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <form id="actionAdd"
                                    class="forms-sample"
                                    method="POST"
                                    action="<?php echo route("update_service_status"); ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden"
                                        name="id"
                                        value="<?php echo $services->id; ?>" />
                                    @can('change-status-complete-service')
                                        <button id="upgradeProcess"
                                            type="submit"
                                            class="btn btn-gradient-primary btn-lg"
                                            name="status"
                                            value="Completed">
                                            Completed
                                        </button>
                                    @endcan
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        @if (strtolower($services->status) === "new" || strtolower($services->status) === "process")
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <form id="actionAdd"
                                    class="forms-sample"
                                    method="POST"
                                    action="{{ route("update_service_status") }}">
                                    @csrf
                                    <input type="hidden"
                                        name="id"
                                        value="{{ $services->id }}" />
                                    @can('change-status-complete-service')
                                        <button id="upgradeProcess"
                                            type="submit"
                                            class="btn btn-gradient-danger btn-lg"
                                            name="status"
                                            value="Cancel">
                                            Cancel Service
                                        </button>
                                    @endcan
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <h2 class="text-center share">
                                Share Service Process
                            </h2>
                        </div>
                        <form class="forms-sample"
                            method="GET"
                            action="https://api.whatsapp.com/send">
                            <div class="form-group row justify-content-center">
                                <button id="upgradeProcess"
                                    type="submit"
                                    class="btn btn-gradient-primary mr-2 btn-lg"
                                    name="text"
                                    value="Untuk mengecek status product service anda, anda dapat mengklik link di sini ( {{ route('track_service' ,['id' => $services['id']]) }} )">
                                    Share Link
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

@section('script')
<script type="application/javascript">
    $(document).ready(function () {
        $("#cso").on("input", function () {
            check_cso($("#cso").val());
        });

        function check_cso(code) {
            $.get('{{route("fetchCso")}}', { cso_code: code })
                .done(function(result) {
                    if (result['result'] == "true" && result['data'].length > 0) {
                        $('#validation_cso').html('Kode CSO Benar');
                        $('#validation_cso').css('color', 'green');
                        $('#submit').removeAttr('disabled');
                    } else {
                        $('#validation_cso').html('Kode CSO Salah');
                        $('#validation_cso').css('color', 'red');
                        $('#submit').attr('disabled', "");
                    }
                });
        };

        $("#province").on("change", function () {
            var id = $(this).val();
            $("#city").html("");
            $("#subDistrict").html("<option selected disabled value=\"\">Pilihan Kecamatan</option>");
            $.get('{{ route("fetchCity", ['province' => ""]) }}/' + id)
                .done(function(result) {
                    result = result['rajaongkir']['results'];
                    var arrCity = "<option selected disabled value=\"\">Pilihan Kota</option>";
                    if (result.length > 0) {
                        $.each(result, function(key, value) {
                            arrCity += "<option value=\""
                                + value['city_id']
                                + "\">"
                                + value['type']
                                + " "
                                + value['city_name']
                                + "</option>";
                        });
                        $("#city").append(arrCity);
                    }
                });
        });

        $("#city").on("change", function () {
            var id = $(this).val();
            $("#subDistrict").html("");
            $.get('{{ route("fetchDistrict", ['city ' => ""]) }}/' + id)
                .done(function(result) {
                    result = result['rajaongkir']['results'];
                    var arrSubDistsrict = "<option selected disabled value=\"\">Pilihan Kecamatan</option>";
                    if (result.length > 0) {
                        $.each(result, function(key, value) {
                            arrSubDistsrict += "<option value=\""
                                + value['subdistrict_id']
                                + "\">"
                                + value['subdistrict_name']
                                + "</option>";
                        });
                        $("#subDistrict").append(arrSubDistsrict);
                    }
                });
        });

        $("#status").on("change", function () {
            if ($(this).val() == "Pickup") {
                $(".form_appoint_container").hide();
                $("#date").removeAttr('required');
                $("#time").removeAttr('required');
            } else {
                $(".form_appoint_container").show();
                $("#date").attr('required', 'true');
                $("#time").attr('required', 'true');
            }
        });

        @if ($request->all() != null)
            alert("{{ $request->errMessage }}");
        @endif
    });
</script>
@endsection
