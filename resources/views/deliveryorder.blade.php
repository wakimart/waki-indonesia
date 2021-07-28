<?php
$menu_item_page = "form";
$menu_item_second = "formregistrasi";
?>
@extends('layouts.template')

@section('content')
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
</style>


<section id="intro" class="clearfix">
    <div class="container">
        <div class="row justify-content-center">
            <h2>REGISTRATION FORM</h2>
            <form action="{{ Route('store_delivery_order') }}"
                method="post"
                role="form"
                class="contactForm col-md-9">
                @csrf
                <div class="form-group">
                    <input type="text"
                        name="no_member"
                        class="form-control"
                        id="no_member"
                        placeholder="Member Number (optional)" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <input type="text"
                        class="form-control"
                        name="name"
                        id="name"
                        placeholder="Name"
                        required
                        data-msg="Please fill the name" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <input type="text"
                        class="form-control"
                        name="phone"
                        id="phone"
                        placeholder="Phone Number"
                        required data-msg="Please fill the phone number" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <select class="form-control"
                        id="province"
                        name="province_id"
                        data-msg="Please select a province"
                        required>
                        <option selected disabled value="">
                            Select Province
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
                <div class="form-group">
                    <select class="form-control"
                        id="city"
                        name="city"
                        data-msg="Please select a city"
                        required>
                        <option selected disabled value="">Select City</option>
                    </select>
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <textarea class="form-control"
                        name="address"
                        rows="5"
                        required
                        data-msg="Please fill the address"
                        placeholder="Alamat"></textarea>
                    <div class="validation"></div>
                </div>

                @for ($j = 0; $j < 2; $j++)
                    <div class="form-group"
                        style="width: 82%; display: inline-block;">
                        <select class="form-control pilihan-product"
                            name="product_{{ $j }}"
                            data-msg="Please select a promo"
                            {{ $j > 0 ? "" : "required" }}>
                            <option selected disabled value="">
                                Select Promo {{ $j > 0 ? "(optional)" : "" }}
                            </option>
                            @foreach($promos as $key => $promo)
                                <option value="{{ $key }}">
                                    {{ $promo['code'] }} - {{ $promo['name'] }} ( {{ $promo['harga'] }} )
                                </option>
                            @endforeach
                            <option value="other">OTHER</option>
                        </select>
                        <div class="validation"></div>
                    </div>
                    <div class="form-group"
                        style="width: 16%; display: inline-block; float: right;">
                        <select class="form-control"
                            name="qty_{{ $j }}"
                            data-msg="Please select a quantity"
                            {{ $j > 0 ? "" : "required" }}>
                            <option selected value="1">1</option>
                            @for ($i = 2; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        <div class="validation"></div>
                    </div>

                    <div class="form-group d-none">
                        <input type="text"
                            class="form-control"
                            name="product_other_{{ $j }}"
                            placeholder="Product Name"
                            data-msg="Please fill the product" />
                        <div class="validation"></div>
                    </div>
                @endfor

                <div class="form-group">
                    <select class="form-control"
                        id="branch"
                        name="branch_id"
                        data-msg="Please select a branch"
                        required>
                        <option selected disabled value="">
                            Select Branch
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
                    <input type="text"
                        class="form-control"
                        name="cso_id"
                        id="cso"
                        placeholder="CSO Code"
                        required
                        data-msg="Please fill in the CSO code"
                        style="text-transform: uppercase;" />
                    <div class="validation" id="validation_cso"></div>
                </div>

                <div id="errormessage"></div>
                <div class="text-center">
                    <button id="submit" type="submit" title="Send Message">
                        Save Registration Form
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script type="application/javascript">
    $(document).ready(function () {
        $("#cso").on("input", function () {
            var txtCso = $(this).val();
            $.get('{{ route("fetchCso") }}', { txt: txtCso })
                .done(function (result) {
                    if (result == 'true') {
                        $('#validation_cso').html('Kode CSO Benar');
                        $('#validation_cso').css('color', 'green');
                        $('#submit').removeAttr('disabled');
                    } else {
                        $('#validation_cso').html('Kode CSO Salah');
                        $('#validation_cso').css('color', 'red');
                        $('#submit').attr('disabled',"");
                    }
                });
        });

        $("#province").on("change", function () {
            var id = $(this).val();
            $("#city").html("");
            $.get('{{ route("fetchCity", ['province' => ""]) }}/' + id)
                .done(function (result) {
                    result = result['rajaongkir']['results'];
                    var arrCity = "<option selected disabled value=\"\">Pilihan Kota</option>";
                    if (result.length > 0) {
                        $.each(result, function (key, value) {
                            if (value['type'] == "Kota") {
                                arrCity += "<option value=\"Kota "
                                    + value['city_name']
                                    + "\">Kota "
                                    + value['city_name']
                                    + "</option>";
                            }
                        });
                        $( "#city" ).append(arrCity);
                    }
                });
        });

        $(".pilihan-product").change(function (e) {
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
