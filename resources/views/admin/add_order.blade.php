<?php
$menu_item_page = "order";
$menu_item_second = "add_order";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="{{ asset("css/lib/select2/select2-bootstrap4.min.css") }}" />
<style type="text/css">
    #intro {padding-top: 2em;}
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
    .select2-selection__rendered {
        line-height: 45px !important;
    }
    .select2-container .select2-selection--single {
        height: 45px !important;
    }
    .imagePreview {
        width: 100%;
        height: 150px;
        background-position: center center;
        background-color: #fff;
        background-size: cover;
        background-repeat: no-repeat;
        display: inline-block;
    }
    .del {
        position: absolute;
        top: 0px;
        right: 10px;
        width: 30px;
        height: 30px;
        text-align: center;
        line-height: 30px;
        background-color: rgba(255, 255, 255, 0.6);
        cursor: pointer;
    }
    .select2-container--bootstrap4 .select2-results__group {
        color: black;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
      <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Add Order</h3>
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
                        Add Order
                    </li>
                </ol>
            </nav>
        </div>
        <form id="actionAdd"
            class="forms-sample"
            method="POST"
            action="{{ route('admin_store_order') }}">
            @csrf
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="orderDate">Waktu Order</label>
                                <input type="date"
                                    class="form-control"
                                    name="orderDate"
                                    id="orderDate"
                                    placeholder="Tanggal Order"
                                    value="<?php echo date("Y-m-d"); ?>"
                                    required
                                    data-msg="Mohon Isi Tanggal" />
                                <div class="validation"></div>
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="tempNo">Temp No (Opsional)</label>
                                <input type="text"
                                    class="form-control"
                                    name="temp_no"
                                    placeholder="Temp No"/>
                                <div class="validation"></div>
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <h4><strong>Data Customer</strong></h4>
                                <label for="customer_type">Type Customer</label>
                                @php $customer_types = ["VVIP (Type A)", "WAKi Customer (Type B)", "New Customer (Type C)"]; @endphp
                                <select id="customer_type"
                                    style="margin-top: 0.5em; height: auto;"
                                    class="form-control"
                                    name="customer_type"
                                    value=""
                                    required>
                                    @foreach ($customer_types as $customer_type)
                                    <option value="{{ $customer_type }}">
                                        {{ $customer_type }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="no_member">
                                    No. Member (optional)
                                </label>
                                <input type="number"
                                    class="form-control"
                                    id="no_member"
                                    name="no_member"
                                    placeholder="No. Member" />
                                <div class="validation"></div>
                                <div id="checkMPC" style="color: green; font-size: 9pt;"></div>
                            </div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    placeholder="Name" />
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="number"
                                    class="form-control"
                                    id="phone"
                                    name="phone"
                                    placeholder="Phone Number">
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

                                    <?php
                                    $result = RajaOngkir::FetchProvince();
                                    $result = $result['rajaongkir']['results'];

                                    if (sizeof($result) > 0) {
                                        foreach ($result as $value) {
                                            echo '<option value="' . $value['province_id'] . '">'
                                                . $value['province']
                                                . "</option>";
                                        }
                                    }
                                    ?>
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
                                </select>
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="subDistrict">Sub District</label>
                                <select class="form-control"
                                    id="subDistrict"
                                    name="distric"
                                    data-msg="Mohon Pilih Kecamatan"
                                    required>
                                    <option selected disabled value="">
                                        Pilihan Kecamatan
                                    </option>
                                </select>
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control"
                                    id="address"
                                    name="address"
                                    rows="4"
                                    placeholder="Address"></textarea>
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="">Know From</label>
                                <select class="form-control"
                                    id="know_from"
                                    name="know_from"
                                    data-msg="Mohon Pilih Kecamatan"
                                    required>
                                    @foreach ($from_know as $key => $value)
                                        <option value="{{ $value }}">
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="validation"></div>
                            </div>
                            <br>
                            <h5 class="add-customer d-none">Customer 2</h5>
                            <div class="form-group add-customer d-none">
                                <input type="text"
                                    name="no_member-2"
                                    class="form-control"
                                    id="no_member-2"
                                    placeholder="No. Member (optional)" />
                                <div class="validation"></div>
                            </div>
                            <div class="form-group add-customer d-none">
                                <input type="text"
                                    class="form-control cust-2"
                                    name="name-2"
                                    id="name-2"
                                    placeholder="Nama"
                                    data-msg="Mohon Isi Nama" />
                                <div class="validation"></div>
                            </div>
                            <div class="form-group add-customer d-none">
                                <input type="text"
                                    class="form-control cust-2"
                                    name="phone-2"
                                    id="phone-2"
                                    placeholder="No. Telepon"
                                    data-msg="Mohon Isi Nomor Telepon" />
                                <div class="validation"></div>
                            </div>
                            <div class="form-group add-customer d-none">
                                <input type="text"
                                    class="form-control cust-2"
                                    name="city-2"
                                    id="city-2"
                                    placeholder="Kota"
                                    data-msg="Mohon Isi Kota" />
                                <div class="validation"></div>
                            </div>
                            <div class="form-group add-customer d-none">
                                <textarea class="form-control cust-2"
                                    name="address-2"
                                    id="address-2"
                                    rows="5"
                                    data-msg="Mohon Isi Alamat"
                                    placeholder="Alamat"></textarea>
                                <div class="validation"></div>
                            </div>
                            <div class="text-center">
                                <button id="tambah_member"
                                    type="button"
                                    style="background: #4caf3ab3">
                                    Tambah Pembeli
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <h4><strong>Product / Promo</strong></h4>
                                <label for="">CASH/UPGRADE</label>
                                <select class="form-control"
                                    id="cash_upgarde"
                                    name="cash_upgrade"
                                    data-msg="Mohon Pilih Tipe"
                                    required>
                                    <option selected disabled value="">
                                        Choose CASH/UPGRADE
                                    </option>

                                    @foreach ($cashUpgrades as $key => $cashUpgrade)
                                        <option value="{{ $key }}">
                                            {{ strtoupper($cashUpgrade) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="validation"></div>
                            </div>

                            <div id="container-cashupgrade"
                                class="col-md-12"
                                style="display: none; padding: 0;">
                                {{-- ++++++++++++++ Product ++++++++++++++ --}}
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label for="">Purchase Product</label>
                                            <select class="form-control pilihan-product"
                                                id="product_0"
                                                name="product_0"
                                                data-msg="Mohon Pilih Product"
                                                data-sequence="0"
                                                onchange="selectOther(this)"
                                                required>
                                                <option selected disabled value="">
                                                    Choose Product
                                                </option>

                                                <optgroup label="Product">
                                                    @foreach($products as $product)
                                                    <option value="product_{{ $product->id }}">
                                                        {{ $product->code }}
                                                        - ({{ $product->name }})
                                                        - Rp {{ number_format($product->price) }}
                                                    </option>
                                                    @endforeach
                                                </optgroup>

                                                @if(Auth::user()->roles[0]['slug'] != 'cso' && Auth::user()->roles[0]['slug'] != 'branch')
                                                    <option value="other">
                                                        OTHER
                                                    </option>
                                                @endif
                                            </select>
                                            <div class="validation"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Qty Product</label>
                                            <input type="number"
                                                class="form-control"
                                                name="qty_0"
                                                id="qty_0"
                                                data-sequence="0"
                                                placeholder="Qty"
                                                value="1"
                                                min="1"
                                                required
                                                oninput="selectQty(this)"
                                                data-msg="Mohon Isi Jumlah Product" />
                                            <div class="validation"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-right"
                                        style="margin-bottom: 1em;">
                                        <button id="tambah_product"
                                            title="Tambah Product"
                                            style="padding: 0.4em 0.7em;">
                                            <i class="mdi mdi-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group d-none"
                                    id="product_other_container_0">
                                    <input type="text"
                                        class="form-control"
                                        id="product_other_0"
                                        name="product_other_0"
                                        placeholder="Product Name"
                                        data-msg="Please fill in the product" />
                                    <div class="validation"></div>
                                </div>

                                <div id="tambahan_product"></div>
                                {{-- ++++++++++++++ ======== ++++++++++++++ --}}

                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group" style="display: none;">
                                            <label for="">Old Product</label>
                                            <select class="form-control"
                                                id="old_product"
                                                name="old_product"
                                                data-msg="Mohon Pilih Produk Lama">
                                                <option selected disabled value="">
                                                    Choose Old Product
                                                </option>

                                                @foreach($products as $product)
                                                <option value="{{ $product->id }}">
                                                    {{ $product->code }}
                                                    - ({{ $product->name }})
                                                    - Rp {{ number_format($product->price) }}
                                                </option>
                                                @endforeach

                                                <option value="other">
                                                    OTHER
                                                </option>
                                            </select>
                                            <div class="validation"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group" style="display: none;">
                                            <label for="">Qty Old Product</label>
                                            <input type="number"
                                                class="form-control"
                                                name="old_product_qty"
                                                id="old_product_qty"
                                                placeholder="Qty"
                                                data-msg="Mohon Isi Jumlah Old Product" />
                                            <div class="validation"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" style="display: none;"
                                                class="form-control"
                                                name="old_product_other"
                                                id="old_product_other"
                                                placeholder="Old Product Name"
                                                data-msg="Mohon Isi Produk Lama"
                                                style="text-transform:uppercase;" />
                                            <div class="validation"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label for="">Prize Product</label>
                                            <select class="form-control"
                                                id="prize"
                                                name="prize"
                                                data-msg="Mohon Pilih Prize Produk">
                                                <option selected disabled value="">
                                                    Choose Prize Product
                                                </option>

                                                @foreach($products as $product)
                                                <option value="{{ $product->id }}">
                                                    {{ $product->code }}
                                                    - ({{ $product->name }})
                                                    - Rp {{ number_format($product->price) }}
                                                </option>
                                                @endforeach

                                                <option value="other">
                                                    OTHER
                                                </option>
                                            </select>
                                            <div class="validation"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Qty Prize Product</label>
                                            <input type="number"
                                                class="form-control"
                                                name="prize_qty"
                                                id="prize_qty"
                                                placeholder="Qty"
                                                data-msg="Mohon Isi Jumlah Prize" />
                                            <div class="validation"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" style="display: none"
                                                class="form-control"
                                                name="prize_other"
                                                id="prize_other"
                                                placeholder="Prize Product Name"
                                                data-msg="Mohon Isi Hadiah"
                                                style="text-transform: uppercase;" />
                                            <div class="validation"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <h4><strong>Branch & CSO</strong></h4>
                                <label for="branch">Branch</label>
                                <select class="form-control"
                                    id="branch"
                                    name="branch_id"
                                    data-msg="Mohon Pilih Cabang"
                                    required>
                                    <option selected disabled value="">
                                        Choose Branch
                                    </option>

                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch['id'] }}">
                                            {{ $branch['code'] }} - {{ $branch['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="validation"></div>
                            </div>
                            <div id="container-Cabang" style="display: none;">
                                <div class="form-group">
                                    <label for="cso">CSO Code</label>
                                    <input type="text"
                                        class="form-control cso"
                                        name="cso_id"
                                        id="cso"
                                        placeholder="CSO Code"
                                        required
                                        data-msg="Mohon Isi Kode CSO"
                                        style="text-transform: uppercase;"
                                        {{ Auth::user()->roles[0]['slug'] == 'cso' ? "value=" . Auth::user()->cso['code'] : "" }}
                                        {{ Auth::user()->roles[0]['slug'] == 'cso' ? "readonly=\"\"" : "" }} />
                                    <div class="validation"></div>
                                </div>
                                <div class="form-group">
                                    <label for="30_cso">CSO Code 30%</label>
                                    <input type="text"
                                        class="form-control cso"
                                        name="30_cso_id"
                                        id="30_cso"
                                        placeholder="CSO Code 30%"
                                        data-msg="Mohon Isi Kode CSO"
                                        style="text-transform: uppercase;" />
                                    <div class="validation"></div>
                                </div>
                                <div class="form-group">
                                    <label for="70_cso">CSO Code 70%</label>
                                    <input type="text"
                                        class="form-control cso"
                                        name="70_cso_id"
                                        id="70_cso"
                                        placeholder="CSO Code 70%"
                                        data-msg="Mohon Isi Kode CSO"
                                        style="text-transform: uppercase;" />
                                    <div class="validation"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <h4><strong>Payment</strong></h4>
                                <label for="">Total Price</label>
                                <input type="text"
                                    class="form-control"
                                    name="total_payment"
                                    id="total_payment"
                                    placeholder="Total Price"
                                    required
                                    data-type="currency"
                                    data-msg="Mohon Isi Total Harga"
                                    style="text-transform: uppercase;" />
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="">Payment Method</label>
                                <select class="form-control"
                                    id="payment_type"
                                    name="payment_type"
                                    data-msg="Mohon Pilih Tipe"
                                    required>
                                    <option selected disabled value="">
                                        Choose Payment Method
                                    </option>

                                    @foreach ($paymentTypes as $key => $paymentType)
                                        <option value="{{ $key }}">
                                            {{ strtoupper($paymentType) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="validation"></div>
                            </div>

                            {{-- ++++++++ BANK ++++++++ --}}
                            <div id="container-jenispembayaran"
                                style="display: none;">
                                <div class="p-3 mb-2" style="border: 1px solid black">
                                    <div class="form-group">
                                        <label for="">Payment Type</label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-8">
                                            <select class="form-control bank_name"
                                                name="bank_0"
                                                data-msg="Mohon Pilih Bank">
                                                <option selected disabled value="">
                                                    Choose Bank
                                                </option>

                                                @foreach ($banks as $bank)
                                                    <option value="{{ $bank->id }}">
                                                        {{ $bank->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="validation"></div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <select class="form-control bank_cicilan"
                                                name="cicilan_0"
                                                data-msg="Mohon Pilih Jumlah Cicilan">
                                                <option selected value="1">1X</option>
                                                @for ($i = 2; $i <= 12; $i += 2)
                                                    <option class="other_valCicilan"
                                                        value="{{ $i }}">
                                                        {{ $i }}X
                                                    </option>
                                                @endfor
                                                <option class="other_valCicilan"
                                                    value="18">
                                                    18X
                                                </option>
                                                <option class="other_valCicilan"
                                                    value="24">
                                                    24X
                                                </option>
                                            </select>
                                            <div class="validation"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Total Payment</label>
                                        <input type="text"
                                            class="form-control downpayment"
                                            name="downpayment_0"
                                            placeholder="Total Payment"
                                            required
                                            data-type="currency"
                                            data-msg="Mohon Isi Total Pembayaran"
                                            style="text-transform: uppercase;" />
                                        <div class="validation"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <label>Bukti Pembayaran</label>
                                            <span style="float: right;">min. 1 picture</span>
                                        </div>
                                        @for ($i = 0; $i < 3; $i++)
                                            <div class="col-xs-12 col-sm-6 col-md-4 form-group imgUp"
                                                style="padding: 15px; float: left;">
                                                <label>Image {{ $i + 1 }}</label>
                                                <div class="imagePreview"
                                                    style="background-image: url({{ asset('sources/dashboard/no-img-banner.jpg') }});">
                                                </div>
                                                <label class="file-upload-browse btn btn-gradient-primary"
                                                    style="margin-top: 15px;">
                                                    Upload
                                                    <input name="images_0_{{ $i }}"
                                                        id="productimg-{{ $i }}"
                                                        type="file"
                                                        accept=".jpg,.jpeg,.png"
                                                        class="uploadFile img"
                                                        value="Upload Photo"
                                                        style="width: 0px; height: 0px; overflow: hidden;"
                                                        {{ $i === 0 ? "required" : "" }} />
                                                </label>
                                                <i class="mdi mdi-window-close del"></i>
                                            </div>
                                        @endfor
                                        <div class="validation"></div>
                                    </div>
                                    <div class="text-center">
                                        <button id="tambah_bank"
                                            title="Tambah Bank"
                                            type="button">
                                            <i class="mdi mdi-plus"></i> Add Payment Type
                                        </button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div id="tambahan_bank"></div>
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="">Remaining Payment</label>
                                <input type="text"
                                    class="form-control"
                                    name="remaining_payment"
                                    id="remaining_payment"
                                    placeholder="Remaining Payment"
                                    required readonly
                                    data-type="currency"
                                    data-msg="Mohon Isi Sisa Pembayaran"
                                    style="text-transform: uppercase;" />
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control"
                                    id="description"
                                    name="description"
                                    rows="5"
                                    data-msg="Mohon Isi Description"
                                    placeholder="Description"></textarea>
                                <div class="validation"></div>
                            </div>

                            <div id="errormessage"></div>

                            <div class="form-group">
                                <button id="addOrder"
                                    type="submit"
                                    class="btn btn-gradient-primary mr-2">
                                    Save
                                </button>
                                <button class="btn btn-light">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- modal success -->
<div class="modal fade" role="dialog" tabindex="-1" id="modal-Success">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Input Success</h4>
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="txt-success">Appointment telah berhasil dibuat.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-gradient-primary"
                    type="button"
                    data-dismiss="modal">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- modal error -->
<div class="modal fade" role="dialog" tabindex="-1" id="modal-Error">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Input Failed</h4>
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="txt-success">
                    "Appointment dengan nomer ini sudah ada!"
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-gradient-danger"
                    type="button"
                    data-dismiss="modal">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
<script type="application/javascript">
let promoOption = `<option selected disabled value="">Choose Product</option>`;
let quantityOption = "";

document.addEventListener("DOMContentLoaded", function () {
    $("#product_0, #old_product, #prize").select2({
        theme: "bootstrap4",
    });

    // Untuk Tambah Product
    promoOption+=`<optgroup label="Promo">`;
    @foreach ($promos as $promo)
        promoOption += `<option value="promo_{{ $promo->id }}">{{ $promo->code . " - (" . implode(", ", $promo->productName()) . ") - Rp. " . number_format($promo->price) }}</option>`;
    @endforeach
    promoOption+=`</optgroup>`;

    promoOption+=`<optgroup label="Product">`;
    @foreach ($products as $product)
        promoOption += `<option value="product_{{ $product->id }}">{{ $product->code . " - (" . $product->name . ") - Rp. " . number_format($product->price) }}</option>`;
    @endforeach
    promoOption+="</optgroup>";

    promoOption += `<option value="other">OTHER</option>`;

    // const URL = '<?php echo route("fetch_promo_dropdown"); ?>';

    // fetch(
    //     URL,
    //     {
    //         method: "GET",
    //         headers: {
    //             "Accept": "application/json",
    //         },
    //     }
    // ).then(function (response) {
    //     if (!response.ok) {
    //         throw new Error(`HTTP error! status: ${response.status}`);
    //     }

    //     return response.json();
    // }).then(function (response) {
    //     const dataPromo = response.data;

    //     for (const promo in dataPromo) {
    //         promoOption += `<option value="${dataPromo[promo].id}">${dataPromo[promo].product}</option>`;
    //     }

    //     promoOption += `<option value="other">OTHER</option>`;
    // }).catch(function (error) {
    //     console.error(error);
    // });

    for (let i = 1; i <= 10; i++) {
        quantityOption += `<option value="${i}">${i}</option>`;
    }
}, false);
</script>
<script type="application/javascript">
    var total_bank = 0;
    var total_product = 0;
    var arrBooleanCso = [ {{ Auth::user()->roles[0]['slug'] == 'cso' ? "true" : "false" }}, false, false ];

    $(document).ready(function () {
        $(document).on("input", 'input[data-type="currency"]', function() {
            $(this).val(numberWithCommas($(this).val()));
        });

        $(".cso").on("input", function () {
            var txtCso = $(this).val();
            var temp = $(this);
            $.get( '{{route("fetchCso")}}', { cso_code: txtCso })
                .done(function( result ) {
                    var bool = false;

                    if (result['result'] == 'true' && result['data'].length > 0) {
                        $(temp).parent().children('.validation').html('Kode CSO Benar');
                        $(temp).parent().children('.validation').css('color', 'green');
                        bool = true;
                    } else {
                        $(temp).parent().children('.validation').html('Kode CSO Salah');
                        $(temp).parent().children('.validation').css('color', 'red');
                    }

                    if(temp.attr("id") == 'cso') {
                        arrBooleanCso[0] = bool;
                    } else if(temp.attr("id") == '30_cso') {
                        arrBooleanCso[1] = bool;
                    } else if(temp.attr("id") == '70_cso') {
                        arrBooleanCso[2] = bool;
                    }

                    if (arrBooleanCso[0] == true && arrBooleanCso[1] == true && arrBooleanCso[2] == true) {
                        $('#submit').removeAttr('disabled');
                    } else {
                        $('#submit').attr('disabled',"");
                    }
                });
        });

        $("#province").on("change", function(){
            var id = $(this).val();
            $("#city").html("");
            $.get('{{ route("fetchCity", ['province' => ""]) }}/' + id)
                .done(function( result ) {
                    result = result['rajaongkir']['results'];
                    var arrCity = "<option selected disabled value=\"\">Pilihan Kota</option>";
                    if(result.length > 0){
                        $.each( result, function( key, value ) {
                            if(value['type'] == "Kabupaten"){
                                arrCity += "<option value=\""+value['city_id']+"\">Kabupaten "+value['city_name']+"</option>";
                            }

                            if(value['type'] == "Kota"){
                                arrCity += "<option value=\""+value['city_id']+"\">Kota "+value['city_name']+"</option>";
                            }


                        });
                        $("#city").append(arrCity);
                    }
                });
        });

        $("#city").on("change", function(){
            var id = $(this).val();
            $( "#subDistrict" ).html("");
            $.get('{{ route("fetchDistrict", ['city' => ""]) }}/' + id)
                .done(function( result ) {
                    result = result['rajaongkir']['results'];
                    var arrSubDistsrict = "<option selected disabled value=\"\">Pilihan Kecamatan</option>";
                    if(result.length > 0){
                        $.each( result, function( key, value ) {
                            arrSubDistsrict += "<option value=\""+value['subdistrict_id']+"\">"+value['subdistrict_name']+"</option>";
                        });
                        $("#subDistrict").append(arrSubDistsrict);
                    }
                });
        });

        $("#tambah_bank").click(function(e){
            e.preventDefault();
            total_bank++;
            strIsi = `
            <div class="p-3 mb-2" style="border: 1px solid black" id="bank_` + total_bank + `">
                <div class="form-group">
                    <label for="">Payment Type ` + (total_bank + 1) + `</label>
                </div>
                <div class="row">
                    <div class="form-group col-md-8">
                        <select class="form-control bank_name"
                            name="bank_` + total_bank + `"
                            data-msg="Mohon Pilih Bank">
                            <option selected disabled value="">
                                Choose Bank
                            </option>

                            @foreach ($banks as $bank)
                                <option value="{{ $bank->id }}">
                                    {{ $bank->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="validation"></div>
                    </div>
                    <div class="form-group col-md-4">
                        <select class="form-control bank_cicilan"
                            name="cicilan_` + total_bank + `"
                            data-msg="Mohon Pilih Jumlah Cicilan">
                            <option selected value="1">1X</option>
                            @for ($i = 2; $i <= 12; $i += 2)
                                <option class="other_valCicilan"
                                    value="{{ $i }}">
                                    {{ $i }}X
                                </option>
                            @endfor
                            <option class="other_valCicilan"
                                value="18">
                                18X
                            </option>
                            <option class="other_valCicilan"
                                value="24">
                                24X
                            </option>
                        </select>
                        <div class="validation"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Total Payment</label>
                    <input type="text"
                        class="form-control downpayment"
                        name="downpayment_` + total_bank + `"
                        placeholder="Total Payment"
                        required
                        data-type="currency"
                        data-msg="Mohon Isi Total Pembayaran"
                        style="text-transform: uppercase;" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <label>Bukti Pembayaran</label>
                        <span style="float: right;">min. 1 picture</span>
                    </div>
                    @for ($i = 0; $i < 3; $i++)
                        <div class="col-xs-12 col-sm-6 col-md-4 form-group imgUp"
                            style="padding: 15px; float: left;">
                            <label>Image {{ $i + 1 }}</label>
                            <div class="imagePreview"
                                style="background-image: url({{ asset('sources/dashboard/no-img-banner.jpg') }});">
                            </div>
                            <label class="file-upload-browse btn btn-gradient-primary"
                                style="margin-top: 15px;">
                                Upload
                                <input name="images_` + total_bank + `_{{ $i }}"
                                    id="productimg-{{ $i }}"
                                    type="file"
                                    accept=".jpg,.jpeg,.png"
                                    class="uploadFile img"
                                    value="Upload Photo"
                                    style="width: 0px; height: 0px; overflow: hidden;"
                                    {{ $i === 0 ? "required" : "" }} />
                            </label>
                            <i class="mdi mdi-window-close del"></i>
                        </div>
                    @endfor
                    <div class="validation"></div>
                </div>
                <div class="text-center">
                    <button style="padding: 0.4em 0.7em; background-color: red"
                        class="hapus_bank"
                        title="Hapus Bank"
                        value="` + total_bank + `"
                        type="button">
                        <i class="mdi mdi-minus"></i> Delete Payment Type
                    </button>
                </div>
                <div class="clearfix"></div>
            </div>`;
            $('#tambahan_bank').append(strIsi);


            if ($("#payment_type").val() == 1) {
                $(".other_valCicilan").attr('disabled', "");
                $(".other_valCicilan").hide();
            } else {
                $(".other_valCicilan").removeAttr('disabled');
                $(".other_valCicilan").show();
            }
        });

        $(document).on("click", ".hapus_bank", function (e) {
            e.preventDefault();
            // total_bank--;
            $('#bank_'+$(this).val()).remove();
            $('#cicilan_'+$(this).val()).remove();
            $(this).remove();
            checkRemainingPayment();
        });

        $("#tambah_product").click(function (e) {
            e.preventDefault();
            total_product++;

            const newDivProduct = document.createElement("div");
            newDivProduct.className = "form-group";
            // newDivProduct.style = "width: 74%; float: left; display: inline-block;";

            const newSelectProduct = document.createElement("select");
            newSelectProduct.id = `product_${total_product}`;
            newSelectProduct.className = "form-control pilihan-product";
            newSelectProduct.name = `product_${total_product}`;
            newSelectProduct.required = true;
            newSelectProduct.innerHTML = promoOption;
            newSelectProduct.setAttribute("onchange", "selectOther(this)");
            newSelectProduct.setAttribute("data-sequence", total_product);

            const newDivQty = document.createElement("div");
            newDivQty.className = "form-group";
            // newDivQty.style = "width: 14%; float: right; display: inline-block;";

            const newSelectQty = document.createElement("input");
            newSelectQty.type = "number";
            newSelectQty.className = "form-control";
            newSelectQty.id = `qty_${total_product}`;
            newSelectQty.name = `qty_${total_product}`;
            newSelectQty.required = true;
            newSelectQty.value = "1";
            newSelectQty.min = "1";
            // newSelectQty.innerHTML = quantityOption;
            newSelectQty.setAttribute("oninput", "selectQty(this)");
            newSelectQty.setAttribute("data-sequence", total_product);

            const newDivRemove = document.createElement("div");
            // newDivRemove.className = "col-md-12";
            newDivRemove.style = "margin-bottom: 1em; display:flex; justify-content: flex-end; padding: 0;";

            const newButtonRemove = document.createElement("button");
            newButtonRemove.className = "hapus_product";
            newButtonRemove.value = total_product;
            newButtonRemove.title = "Kurangi Produk";
            newButtonRemove.style = "padding: 0.4em 0.7em; background-color: red;";
            newButtonRemove.innerHTML = '<i class="fas fa-minus"></i>';

            const newDivOther = document.createElement("div");
            newDivOther.id = `product_other_container_${total_product}`;
            newDivOther.className = "col-md-12 form-group d-none";

            const newInputOther = document.createElement("input");
            newInputOther.id = `product_other_${total_product}`;
            newInputOther.type = "text";
            newInputOther.className = "form-control";
            newInputOther.name = `product_other_${total_product}`;
            newInputOther.placeholder = "Product Name";

            const newDivCol9 = document.createElement("div");
            newDivCol9.className = "col-md-9 form-group";

            const newDivCol3 = document.createElement("div");
            newDivCol3.className = "col-md-3 form-group";

            const newDivCol12Qty = document.createElement("div");
            newDivCol12Qty.className = "col-md-12 form-group text-right";

            newDivCol9.innerHTML = "<label>Purchase Product</label>";
            newDivCol9.appendChild(newDivProduct.appendChild(newSelectProduct));
            newDivCol3.innerHTML = "<label>Qty Product</label>"
            newDivCol3.appendChild(newDivQty.appendChild(newSelectQty));
            newDivCol12Qty.appendChild(newDivRemove.appendChild(newButtonRemove));
            newDivOther.appendChild(newInputOther);

            const newDivParentProduct = document.createElement("div");
            newDivParentProduct.className = "row";
            newDivParentProduct.id = `product_parent_${total_product}`;

            newDivParentProduct.appendChild(newDivCol9);
            newDivParentProduct.appendChild(newDivCol3);
            newDivParentProduct.appendChild(newDivCol12Qty);
            newDivParentProduct.appendChild(newDivOther);

            // document.getElementById("tambahan_product").appendChild(newDivProduct);
            // document.getElementById("tambahan_product").appendChild(newDivQty);
            // document.getElementById("tambahan_product").appendChild(newDivRemove);
            // document.getElementById("tambahan_product").appendChild(newDivOther);
            document.getElementById("tambahan_product").appendChild(newDivParentProduct);

            $("#product_" + total_product).select2({
                theme: "bootstrap4",
            });
        });

        $(document).on("click", ".hapus_product", function(e){
            e.preventDefault();
            // total_product--;

            $('#product_parent_'+$(this).val()).remove();
            // $('#product_'+$(this).val()).remove();
            // $('#qty_'+$(this).val()).remove();
            // $('#product_other_container_'+$(this).val()).remove();
            // $('#product_other_'+$(this).val()).remove();
            // $("#product_"+$(this).val()).select2('destroy');

            // $(this).remove();

            //kurangi total price
            for (var i = 0; i < arr_index_temp.length; i++) {
                if(arr_index_temp[i][0] == $(this).val()){
                    var min_price = parseInt(arr_index_temp[i][2]);
                    var min_qty = parseInt(arr_index_temp[i][3]);

                    total_price = total_price - (min_price * min_qty);
                    $("#total_payment").val(numberWithCommas(total_price));
                    checkRemainingPayment();
                }
            }

            //remove dari array
            arr_index_temp.splice($(this).val(), 1);
        });

        $("#cash_upgarde").change( function(e){
            $("#container-cashupgrade").show();
            if($(this).val() == 2){
                $("#old_product").parent().show();
                $("#old_product").attr('required', "");
                $("#old_product_qty").parent().show();
                $("#old_product_qty").attr('required', "");
            }
            else{
                $("#old_product").parent().hide();
                $("#old_product").removeAttr('required');
                $("#old_product_qty").parent().hide();
                $("#old_product_qty").removeAttr('required');
            }
        });

        $(document).on("change", "#payment_type", function(e){
            total_bank = 0;
            $("#container-jenispembayaran").show();
            $(".other_valCicilan").parent().val('1');
            $('#tambahan_bank').html("");
            if($(this).val() == 1){
                $(".other_valCicilan").attr('disabled', "");
                $(".other_valCicilan").hide();
            }
            else{
                $(".other_valCicilan").removeAttr('disabled');
                $(".other_valCicilan").show();
            }
        });

        $("#branch").change( function(e){
            $("#container-Cabang").show();
        });

        //KHUSUS Untuk tambah customer indo
        $("#tambah_member").click(function(e){
            $(".add-customer").removeClass("d-none");
            $(".cust-2").attr('required', '');
            $(this).hide();
        });

        $("#old_product, #prize").change(function() {
            element = $(this);
            if (element.val() == "other") {
                $("#" + element.attr('id') + "_other").show();
                $("#" + element.attr('id') + "_other").attr("required", "");
            } else {
                $("#" + element.attr('id') + "_other").hide();
                $("#" + element.attr('id') + "_other").removeAttr("required");
            }
        });
    });


    function checkProductArray(array, index){
        for (var i = 0; i < array.length; i++) {
            if(array[i][0] === index){
                return true;
            }
        }
        return false;
    }

    var total_price = 0;
    var arr_index_temp = [];
    function selectOther(e) {
        const sequence = e.dataset.sequence; //index select nya

        if (e.value === "other") {
            document.getElementById("product_other_container_" + sequence).classList.remove("d-none");
            document.getElementById("product_other_" + sequence).setAttribute("required", "");
            var get_qty = $('#qty_'+sequence).val();
            arr_index_temp.push([sequence, null, 0, get_qty]);
        } else if (e.value !== "other") {
            document.getElementById("product_other_container_" + sequence).classList.add("d-none");
            document.getElementById("product_other_" + sequence).removeAttribute("required");

            //auto fill price and qty
            var promo_id = e.value;
            var get_qty = $('#qty_'+sequence).val();
            $.get( '{{ route("fetchDetailPromo", ['promo' => ""]) }}/'+promo_id )
            .done(function (result){
                if(result.length > 0){
                    var data = JSON.parse(result);
                    var price = parseInt(data['price']);

                    if(arr_index_temp.length == 0){
                        arr_index_temp.push([sequence, promo_id, price, get_qty]);
                        total_price = total_price + (price * get_qty);
                    }else{
                        if(checkProductArray(arr_index_temp, sequence) == true){
                            //kurangi total price dengan harga lama
                            var old_price = parseInt(arr_index_temp[sequence][2]);
                            var old_qty = parseInt(arr_index_temp[sequence][3]);
                            total_price = total_price - (old_price * old_qty);

                            //simpan promo id yg baru & harga
                            arr_index_temp[sequence][1] = promo_id;
                            arr_index_temp[sequence][2] = price;
                            arr_index_temp[sequence][3] = get_qty;

                            //update total price yg baru
                            total_price = total_price + (price * get_qty);
                        }else{
                            //kalau ga exist, push ke array
                            arr_index_temp.push([sequence, promo_id, price, get_qty]);

                            //update total price
                            total_price = total_price + (price * get_qty);
                        }
                    }
                    console.log(arr_index_temp);
                    console.log(total_price);

                    $("#total_payment").val(numberWithCommas(total_price));
                    checkRemainingPayment();
                }
            });
        }
    }

    function selectQty(e){
        const sequence = e.dataset.sequence; //index select nya

        var get_qty = parseInt($('#qty_'+sequence).val());

        if (!get_qty) get_qty = 0;
        if(checkProductArray(arr_index_temp, sequence) == true){
            //kurangi price dengan harga lama
            var old_price = parseInt(arr_index_temp[sequence][2]);
            var old_qty = parseInt(arr_index_temp[sequence][3]);
            total_price = total_price - (old_price * old_qty);

            //simpan qty yg baru
            arr_index_temp[sequence][3] = get_qty;

            //update total price
            total_price = total_price + (old_price * get_qty);
            $("#total_payment").val(numberWithCommas(total_price));
            checkRemainingPayment();
        }
    }

    function numberWithCommas(x) {
        var parts = x.toString().split(".");
        parts[0] = parts[0].replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    }

    function numberNoCommas(x) {
        var parts = x.toString().split(".");
        parts[0] = parts[0].replace(/\D/g, "");
        return parts.join(".");
    }

    function checkRemainingPayment() {
        var total_downpayment = 0;
        $(".downpayment").each(function(i, obj) {
            total_downpayment += parseFloat(numberNoCommas($(this).val()))
        });
        var total_payment = parseFloat(numberNoCommas($("#total_payment").val()));
         if (total_downpayment > total_payment) {
            total_downpayment = 0;
            $(".downpayment").val('');
            alert("Total Payment cant be higher than the Total Price");
        }
        var remaining_payment = total_payment - total_downpayment;
        $("#remaining_payment").val(numberWithCommas(remaining_payment));
    }

    $(document).on("input", "#total_payment, .downpayment", function() {
        checkRemainingPayment();
    });
    $(document).on("change", "#total_payment, .downpayment", function() {
        var down_payment = parseFloat(numberNoCommas($(this).val()));
        if (down_payment == 0) {
            $(this).val("");
            alert("Total Payment cant be 0");
        }
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        var frmAdd;

        $("#actionAdd").on("submit", function (e) {
            e.preventDefault();

            // var $fileUpload = $("input[type='file']");
            // if (parseInt($fileUpload.get(0).files.length)>5){
            //     alert("You can only upload a maximum of 5 files");
            //     return false;
            // }

            frmAdd = _("actionAdd");
            frmAdd = new FormData(document.getElementById("actionAdd"));
            frmAdd.enctype = "multipart/form-data";
            var URLNya = $("#actionAdd").attr('action');

            // Change numberWithComma before submit
            $('input[data-type="currency"]').each(function() {
              var frmName = $(this).attr('name');
              frmAdd.set(frmName, numberNoCommas(frmAdd.get(frmName)));
            });

            var ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.addEventListener("load", completeHandler, false);
            ajax.addEventListener("error", errorHandler, false);
            ajax.open("POST", URLNya);
            ajax.setRequestHeader("X-CSRF-TOKEN",$('meta[name="csrf-token"]').attr('content'));
            ajax.send(frmAdd);
        });
        function progressHandler(event){
            document.getElementById("addOrder").innerHTML = "UPLOADING...";
        }
        function completeHandler(event){
            var hasil = JSON.parse(event.target.responseText);

            for (var key of frmAdd.keys()) {
                $("#actionAdd").find("input[name="+key.name+"]").removeClass("is-invalid");
                $("#actionAdd").find("select[name="+key.name+"]").removeClass("is-invalid");
                $("#actionAdd").find("textarea[name="+key.name+"]").removeClass("is-invalid");

                $("#actionAdd").find("input[name="+key.name+"]").next().find("strong").text("");
                $("#actionAdd").find("select[name="+key.name+"]").next().find("strong").text("");
                $("#actionAdd").find("textarea[name="+key.name+"]").next().find("strong").text("");
            }

            if(hasil['errors'] != null){
                for (var key of frmAdd.keys()) {
                    if(typeof hasil['errors'][key] === 'undefined') {

                    }
                    else {
                        $("#actionAdd").find("input[name="+key+"]").addClass("is-invalid");
                        $("#actionAdd").find("select[name="+key+"]").addClass("is-invalid");
                        $("#actionAdd").find("textarea[name="+key+"]").addClass("is-invalid");

                        $("#actionAdd").find("input[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
                        $("#actionAdd").find("select[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
                        $("#actionAdd").find("textarea[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
                    }
                }
                alert(hasil['errors']);
            }
            else{
                var kode = hasil['success']['code'].replace('/', "%2F");
                var url = "{{ route('detail_order', ['code'=>"codeTmp"])}}";
                url = url.replace('codeTmp', kode);
                window.location.href = url;
                // alert("Input Success !!!");
                // window.location.reload()
            }
            document.getElementById("addOrder").innerHTML = "SAVE";
        }
        function errorHandler(event){
            document.getElementById("addOrder").innerHTML = "SAVE";
        }
    })
</script>
<script type="application/javascript" src="{{ asset('js/tags-input.js') }}"></script>
<script type="application/javascript">
    for (let input of document.querySelectorAll('#tags')) {
        tagsInput(input);
    }
</script>
<script type="application/javascript">
    $(document).on("change", ".uploadFile", function () {
        const uploadFile = $(this);
        const files = this.files ? this.files : [];

        // no file selected, or no FileReader support
        if (!files.length || !window.FileReader) {
            return;
        }

        // only image file
        if (/^image/.test(files[0].type)) {
            // instance of the FileReader
            const reader = new FileReader();
            // read the local file
            reader.readAsDataURL(files[0]);

            // set image data as background of div
            reader.onloadend = function () {
                uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url(" + this.result + ")");
            };
        }
    });
</script>
<script>
    $(document).ready(function() {
        $("#no_member").focus(function(){
            $("#name").attr("readonly", true);
            $("#phone").attr("readonly", true);
            $("#checkMPC").text("");
        });
        $("#no_member").blur(function(){
            $.ajax({
                method: "post",
                url: "{{ route('mpc_waki_check') }}",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'mpc_waki': this.value,
                },
                success: function(data) {
                    $("#name").removeAttr("readonly");
                    $("#phone").removeAttr("readonly");
                    if(data['status'] == 'success'){
                        $("#checkMPC").text("Found MPC Data");
                        $("#name").val(data['data_mpc']['name']);
                        $("#phone").val(data['data_mpc']['phone']);
                    }
                },
                error: function(data) {
                    $("#name").removeAttr("readonly");
                    $("#phone").removeAttr("readonly");
                    console.log({"Error!":  data['responseText']});
                }
            });
        });
    });
</script>
@endsection
