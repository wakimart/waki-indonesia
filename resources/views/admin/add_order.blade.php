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
        <div class="row">
              <div class="col-12 grid-margin stretch-card">
                <div class="card">
                      <div class="card-body">
                        <form id="actionAdd"
                            class="forms-sample"
                            method="POST"
                            action="{{ route('admin_store_order') }}">
                            @csrf
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
                                <label for="no_member">
                                    No. Member (optional)
                                </label>
                                <input type="number"
                                    class="form-control"
                                    id="no_member"
                                    name="no_member"
                                    placeholder="No. Member" />
                                <div class="validation"></div>
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
                            <br>
                            <div class="form-group">
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
                                <div class="form-group"
                                    style="width: 72%; display: inline-block;">
                                    <select class="form-control pilihan-product selectpicker"
                                        id="product_0"
                                        name="product_0"
                                        data-msg="Mohon Pilih Product"
                                        required>
                                        <option selected disabled value="">
                                            Choose Product
                                        </option>

                                        <?php foreach ($promos as $key => $promo): ?>
                                            <option value="<?php echo $promo["id"]; ?>">
                                                <?php
                                                echo $promo->code
                                                    . " - ("
                                                    . implode(", ", $promo->productName())
                                                    . ") - Rp. "
                                                    . number_format($promo->price);
                                                ?>
                                            </option>
                                        <?php endforeach; ?>

                                        <option value="other">OTHER</option>
                                    </select>
                                    <div class="validation"></div>
                                </div>

                                <div class="form-group"
                                    style="width: 16%; display: inline-block;">
                                    <select class="form-control"
                                        name="qty_0"
                                        data-msg="Mohon Pilih Jumlah"
                                        required>
                                        <option selected value="1">1</option>

                                        @for ($i = 2; $i <= 10; $i++)
                                            <option value="{{ $i }}">
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    <div class="validation"></div>
                                </div>
                                <div class="text-center"
                                    style="display: inline-block; float: right;">
                                    <button id="tambah_product"
                                        title="Tambah Product"
                                        style="padding: 0.4em 0.7em;">
                                        <i class="mdi mdi-plus"></i>
                                    </button>
                                </div>

                                <div class="form-group d-none">
                                    <input type="text"
                                        class="form-control"
                                        name="product_other_0"
                                        placeholder="Product Name"
                                        data-msg="Please fill in the product" />
                                    <div class="validation"></div>
                                </div>

                                <div id="tambahan_product"></div>
                                {{-- ++++++++++++++ ======== ++++++++++++++ --}}

                                <div class="form-group" style="display: none;">
                                    <input type="text"
                                        class="form-control"
                                        name="old_product"
                                        id="old_product"
                                        placeholder="Old Product"
                                        data-msg="Mohon Isi Produk Lama"
                                        style="text-transform:uppercase;" />
                                    <div class="validation"></div>
                                </div>
                                <div class="form-group">
                                    <input type="text"
                                        class="form-control"
                                        name="prize"
                                        id="prize"
                                        placeholder="Prize Product"
                                        data-msg="Mohon Isi Hadiah"
                                        style="text-transform: uppercase;" />
                                    <div class="validation"></div>
                                </div>
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="payment_type">Payment Method</label>
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
                            <div id="container-jenispembayaran"
                                style="display: none;">
                                {{-- ++++++++ BANK ++++++++ --}}
                                <div class="form-group bank_select"
                                    style="width: 62%; display: inline-block;">
                                    <select class="form-control bank_name"
                                        name="bank_0"
                                        data-msg="Mohon Pilih Bank">
                                        <option selected disabled value="">
                                            Choose Bank
                                        </option>

                                        @foreach ($banks as $key => $bank)
                                            <option value="{{ $key }}">
                                                {{ $bank }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="validation"></div>
                                </div>
                                <div class="form-group bank_select"
                                    style="width: 26%; display: inline-block;">
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
                                    </select>
                                    <div class="validation"></div>
                                </div>
                                <div class="text-center"
                                    style="display: inline-block; float: right;">
                                    <button id="tambah_bank"
                                        title="Tambah Bank"
                                        style="padding: 0.4em 0.7em;">
                                        <i class="mdi mdi-plus"></i>
                                    </button>
                                </div>

                                <div id="tambahan_bank"></div>
                                {{-- ++++++++ ==== ++++++++ --}}
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control"
                                        name="total_payment"
                                        id="total_payment"
                                        placeholder="Total Payment"
                                        required
                                        data-msg="Mohon Isi Total Harga"
                                        style="text-transform: uppercase;" />
                                    <div class="validation"></div>
                                </div>
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control"
                                        name="down_payment"
                                        id="down_payment"
                                        placeholder="Down Payment(DP)"
                                        required
                                        data-msg="Mohon Isi Down Payment(DP)"
                                        style="text-transform: uppercase;" />
                                    <div class="validation"></div>
                                </div>
                                <div class="form-group">
                                    <input type="number"
                                        class="form-control"
                                        name="remaining_payment"
                                        id="remaining_payment"
                                        placeholder="Remaining Payment"
                                        required
                                        data-msg="Mohon Isi Sisa Pembayaran"
                                        style="text-transform: uppercase;" />
                                    <div class="validation"></div>
                                </div>
                            </div>
                            <br>

                            <div class="form-group">
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
                            <br>
                            <div class="form-group">
                                <label for="customer_type">Type Customer</label>
                                <select id="customer_type"
                                    style="margin-top: 0.5em; height: auto;"
                                    class="form-control"
                                    name="customer_type"
                                    value=""
                                    required>
                                    <option value="VVIP (Type A)">
                                        VVIP (Type A)
                                    </option>
                                    <option value="WAKi Customer (Type B)">
                                        WAKi Customer (Type B)
                                    </option>
                                    <option value="New Customer (Type C)">
                                        New Customer (Type C)
                                    </option>
                                </select>
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
                                <button id="submit"
                                    type="submit"
                                    class="btn btn-gradient-primary mr-2">
                                    Save
                                </button>
                                <button class="btn btn-light">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
let promoOption = "";
let quantityOption = "";

document.addEventListener("DOMContentLoaded", function () {
    $("#product_0").select2({
        theme: "bootstrap4",
    });

    const URL = '<?php echo route("fetch_promo_dropdown"); ?>';

    fetch(
        URL,
        {
            method: "GET",
            headers: {
                "Accept": "application/json",
            },
        }
    ).then(function (response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    }).then(function (response) {
        const dataPromo = response.data;

        for (const promo in dataPromo) {
            promoOption += `<option value="${dataPromo[promo].id}">${dataPromo[promo].product}</option>`;
        }
    }).catch(function (error) {
        console.error(error);
    });

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
                        console.log("masuk");
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
            strIsi = "<div class=\"form-group bank_select\" style=\"width: 62%; display: inline-block;\" id=\"bank_"+total_bank+"\"><select class=\"form-control bank_name\" name=\"bank_"+total_bank+"\" data-msg=\"Mohon Pilih Bank\"><option selected disabled value=\"\">Pilihan Bank</option> @foreach($banks as $key=>$bank) <option value=\"{{ $key }}\">{{ $bank }}</option> @endforeach </select><div class=\"validation\"></div></div><div class=\"form-group bank_select\" style=\"width: 26%; display: inline-block;\" id=\"cicilan_"+total_bank+"\"><select class=\"form-control bank_cicilan\" name=\"cicilan_"+total_bank+"\" data-msg=\"Mohon Pilih Jumlah Cicilan\"><option selected value=\"1\">1X</option> @for($i=2; $i<=12;$i+=2) <option class=\"other_valCicilan\" value=\"{{ $i }}\">{{ $i }}X</option> @endfor </select><div class=\"validation\"></div></div><div class=\"text-center\" style=\"display: inline-block; float: right;\"><button class=\"hapus_bank\" value=\""+total_bank+"\" title=\"Hapus Bank\" style=\"padding: 0.4em 0.7em; background-color: red\"><i class=\"mdi mdi-minus\"></i></button></div>";
            $('#tambahan_bank').html($('#tambahan_bank').html()+strIsi);


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
            total_bank--;
            $('#bank_'+$(this).val()).remove();
            $('#cicilan_'+$(this).val()).remove();
            $(this).remove();
        });

        $("#tambah_product").click(function (e) {
            e.preventDefault();
            total_product++;

            const newDivProduct = document.createElement("div");
            newDivProduct.className = "form-group";
            newDivProduct.style = "width: 72%; display: inline-block;";

            const newSelectProduct = document.createElement("select");
            newSelectProduct.id = `product_${total_product}`;
            newSelectProduct.className = "form-control pilihan-product";
            newSelectProduct.name = `product_${total_product}`;
            newSelectProduct.required = true;
            newSelectProduct.innerHTML = promoOption;

            const newDivQty = document.createElement("div");
            newDivQty.id = `qty_${total_product}`;
            newDivQty.className = "form-group";
            newDivQty.style = "width: 16%; display: inline-block;";

            const newSelectQty = document.createElement("select");
            newSelectQty.className = "form-control";
            newSelectQty.name = `qty_${total_product}`;
            newSelectQty.innerHTML = quantityOption;

            const newDivRemove = document.createElement("div");
            newDivRemove.className = "text-center";
            newDivRemove.style = "display: inline-block; float: right;";

            const newButtonRemove = document.createElement("button");
            newButtonRemove.className = "hapus_product";
            newButtonRemove.value = total_product;
            newButtonRemove.title = "Kurangi Produk";
            newButtonRemove.style = "padding: 0.4em 0.7em; background-color: red;";
            newButtonRemove.innerHTML = '<i class="fas fa-minus"></i>';

            const newDivOther = document.createElement("div");
            newDivOther.className = "form-group d-none";

            const newInputOther = document.createElement("input");
            newInputOther.type = "text";
            newInputOther.className = "form-control";
            newInputOther.name = `product_other_${total_product}`;
            newInputOther.placeholder = "Product Name";

            newDivProduct.appendChild(newSelectProduct);
            newDivQty.appendChild(newSelectQty);
            newDivRemove.appendChild(newButtonRemove);
            newDivOther.appendChild(newInputOther);

            document.getElementById("tambahan_product").appendChild(newDivProduct);
            document.getElementById("tambahan_product").appendChild(newDivQty);
            document.getElementById("tambahan_product").appendChild(newDivRemove);
            document.getElementById("tambahan_product").appendChild(newDivOther);

            $("#product_" + total_product).select2({
                theme: "bootstrap4",
            });
        });

        $(document).on("click", ".hapus_product", function(e){
            e.preventDefault();
            total_product--;
            $('#product_'+$(this).val()).remove();
            $('#qty_'+$(this).val()).remove();
            $(this).remove();
        });

        $("#cash_upgarde").change( function(e){
            $("#container-cashupgrade").show();
            if($(this).val() == 2){
                $("#old_product").parent().show();
                $("#old_product").attr('required', "");
            }
            else{
                $("#old_product").parent().hide();
                $("#old_product").removeAttr('required');
            }
        });

        $(document).on("change", "#payment_type", function(e){
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

        {{-- KHUSUS Philiphin --}}
        $(document).on("change", ".pilihan-product", function(e){
            if($(this).val() == 'other'){
                $(this).parent().next().next().next().removeClass("d-none");
                $(this).parent().next().next().next().children().attr('required', '');
            }
            else{
                $(this).parent().next().next().next().addClass("d-none");
                $(this).parent().next().next().next().children().removeAttr('required', '');
            }
        });

        //KHUSUS Untuk tambah customer indo
        $("#tambah_member").click(function(e){
            $(".add-customer").removeClass("d-none");
            $(".cust-2").attr('required', '');
            $(this).hide();
        });

        $('#submit').click(function(){
            var appointment =
            $.ajax({
                type: 'POST',
                data: {
                    date: date
                },
                success: function(data){
                    console.log(data.data);
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
<script type="application/javascript" src="{{ asset('js/tags-input.js') }}"></script>
<script type="application/javascript">
    for (let input of document.querySelectorAll('#tags')) {
        tagsInput(input);
    }
</script>
@endsection
