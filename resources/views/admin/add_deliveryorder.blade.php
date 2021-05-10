<?php
$menu_item_page = "deliveryorder";
$menu_item_second = "add_deliveryorder";
?>
@extends('admin.layouts.template')

@section('style')
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

    input, select, textarea{
        border-radius: 0 !important;
        box-shadow: none !important;
        border: 1px solid #dce1ec !important;
        font-size: 14px !important;
    }

    #regForm {
        background-color: #ffffff;
        margin: 100px auto;
        padding: 40px;
        width: 70%;
        min-width: 300px;
    }

    /* Style the input fields */
    input {
        padding: 10px;
        width: 100%;
        font-size: 17px;
        font-family: Raleway;
        border: 1px solid #aaaaaa;
    }

    /* Mark input boxes that gets an error on validation: */
    input.invalid {
        background-color: #ffdddd;
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
            <h3 class="page-title">Add Registration</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#"
                            aria-expanded="false"
                            aria-controls="deliveryorder-dd">
                            Registration
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Add Registration
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
                            action="{{ route('store_deliveryorder') }}">
                            @csrf
                            <div class="form-group">
                                <label><h2>Data Pelanggan</h2></label>
                                <br/>
                                <label id="member_label" for="member_input">
                                    No. MPC (optional)
                                </label>
                                <input id="member_input"
                                    type="text"
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
                                    placeholder="Name"
                                    required />
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="number"
                                    class="form-control"
                                    id="phone"
                                    name="phone"
                                    placeholder="Phone Number"
                                    required />
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="province">Province</label>
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
                                    placeholder="Full Address"></textarea>
                                <div class="validation"></div>
                            </div>

                            @for ($j = 0; $j < 2; $j++)
                                <div class="form-group product-group">
                                    <div class="col-xs-12 col-sm-12 row"
                                        style="margin: 0; padding: 0;">
                                        <div class="col-xs-10 col-sm-10"
                                            style="padding: 0; display: inline-block;">
                                            <label for="product-{{ $j }}">
                                                Promo {{ $j + 1 }}
                                            </label>
                                            <select id="product-{{ $j }}"
                                                class="form-control pilihan-product"
                                                name="product_{{ $j }}"
                                                data-msg="Mohon Pilih Promo"
                                                {{ $j > 0 ? "" : "required" }}>
                                                <option selected disabled value="">
                                                    Choose Promo{{ $j > 0 ? " (optional)" : ""}}
                                                </option>
                                                @foreach ($promos as $key => $promo)
                                                    <option value="{{ $key }}">
                                                        {{ $promo['code'] }} - {{ $promo['name'] }} ( {{ $promo['harga'] }} )
                                                    </option>
                                                @endforeach

                                                <option value="other">OTHER</option>
                                            </select>
                                            <div class="validation"></div>
                                        </div>
                                        <div class="col-xs-2 col-sm-2"
                                            style="padding-right: 0; display: inline-block;">
                                            <label for="qty-{{ $j }}">
                                                Qty
                                            </label>
                                            <select class="form-control"
                                                id="qty-{{ $j }}"
                                                name="qty_{{ $j }}"
                                                data-msg="Mohon Pilih Jumlah"
                                                {{ $j > 0 ? "" : "required" }}>
                                                <option selected value="1">
                                                    1
                                                </option>

                                                @for ($i = 2; $i <= 10; $i++)
                                                    <option value="{{ $i }}">
                                                        {{ $i }}
                                                    </option>
                                                @endfor
                                            </select>
                                            <div class="validation"></div>
                                        </div>

                                        <div class="form-group d-none">
                                            <input type="text"
                                                class="form-control"
                                                name="product_other_{{ $j }}"
                                                placeholder="Product Name"
                                                data-msg="Please fill in the product" />
                                            <div class="validation"></div>
                                        </div>
                                    </div>
                                </div>
                            @endfor

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
                              <div class="form-group">
                                <label for="cso">CSO Code</label>
                                    <input type="text"
                                        class="form-control"
                                        name="cso_id"
                                        id="cso"
                                        placeholder="CSO Code"
                                        required
                                        data-msg="Mohon Isi Kode CSO"
                                        style="text-transform:uppercase;"
                                        {{ Auth::user()->roles[0]['slug'] == 'cso' ? "value=" . Auth::user()->cso['code'] : "" }}
                                        {{ Auth::user()->roles[0]['slug'] == 'cso' ? "readonly=\"\"" : "" }} />
                                <div class="validation" id="validation_cso"></div>
                            </div>

                            <div id="errormessage"></div>
                            <div class="form-group">
                                <button id="addDeliveryOrder"
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
    $(document).ready(function () {
        for (let i = 0; i < 2; i++) {
            $("#product-" + i).select2({
                theme: "bootstrap4",
            });
        }

        $("#cso").on("input", function () {
            check_cso($("#cso").val());
        });

        $("#province").on("change", function () {
            var id = $(this).val();
            $("#city").html("");

            $.get( '{{ route("fetchCity", ['province' => ""]) }}/'+id )
                .done(function( result ) {
                    result = result['rajaongkir']['results'];
                    var arrCity = "<option selected disabled value=\"\">Pilihan Kota</option>";

                    if (result.length > 0) {
                        $.each( result, function (key, value) {
                            arrCity += `<option value="${value['city_id']}">${value["type"]} ${value['city_name']}</option>`;
                        });

                        $("#city").append(arrCity);
                    }
                });
        });

        $("#city").on("change", function () {
            var id = $(this).val();
            $("#subDistrict").html("");

            $.get('{{ route("fetchDistrict", ['city' => ""]) }}/' + id)
                .done(function (result) {
                    result = result['rajaongkir']['results'];
                    var arrSubDistsrict = '<option selected disabled value="">Pilihan Kecamatan</option>';

                    if (result.length > 0) {
                        $.each(result, function (key, value) {
                            arrSubDistsrict += `<option value="${value['subdistrict_id']}">${value['subdistrict_name']}</option>`;
                        });

                        $("#subDistrict").append(arrSubDistsrict);
                    }
                });
        });

        function check_cso(code) {
            $.get('{{ route("fetchCso") }}', { cso_code: code })
                .done(function (result) {
                    if (result['result'] == "true" && result['data'].length > 0) {
                        $('#validation_cso').html('Kode CSO Benar');
                        $('#validation_cso').css('color', 'green');
                        $('#submit').removeAttr('disabled');
                    } else {
                        $('#validation_cso').html('Kode CSO Salah');
                        $('#validation_cso').css('color', 'red');
                        $('#submit').attr('disabled',"");
                    }
                });
        }

        $(".pilihan-product").change(function (e){
            if ($(this).val() == 'other') {
                $(this).parent().next().next().removeClass("d-none");
                $(this).parent().next().next().children().attr('required', '');
            } else {
                $(this).parent().next().next().addClass("d-none");
                $(this).parent().next().next().children().removeAttr('required', '');
            }
        });
    });
</script>
@endsection
