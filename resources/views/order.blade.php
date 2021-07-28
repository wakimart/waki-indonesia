<?php
$menu_item_page = "form";
$menu_item_second = "formorder";
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
            <h2>ORDER FORM</h2>
        </div>
        <div class="row justify-content-center">
            <form action="{{ route('store_order') }}"
                method="post"
                role="form"
                class="contactForm col-md-9">
                @csrf
                <h6>Order Time</h6>
                <div class="form-group">
                    <input type="date"
                        class="form-control"
                        name="orderDate"
                        id="orderDate"
                        placeholder="Order Date"
                        value="{{ date('Y-m-j') }}"
                        required
                        data-msg="Please fill the order date" />
                    <div class="validation"></div>
                    <span class="invalid-feedback"><strong></strong></span>
                </div>
                <h5 class="add-customer d-none">Customer 1</h5>

                <div class="form-group">
                    <input type="text"
                        name="no_member"
                        class="form-control"
                        id="no_member"
                        placeholder="No. Member (optional)" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <input type="text"
                        class="form-control"
                        name="name"
                        id="name"
                        placeholder="Name"
                        required data-msg="Please fill the name" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <input type="text"
                        class="form-control"
                        name="phone"
                        id="phone"
                        placeholder="Phone Number"
                        required
                        data-msg="Please fill the phone number" />
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
                        if(sizeof($result) > 0){
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
                        placeholder="Address"></textarea>
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
                        placeholder="Name"
                        data-msg="Please fill the name" />
                    <div class="validation"></div>
                </div>
                <div class="form-group add-customer d-none">
                    <input type="text"
                        class="form-control cust-2"
                        name="phone-2"
                        id="phone-2"
                        placeholder="Phone Number"
                        data-msg="Please fill the phone number" />
                    <div class="validation"></div>
                </div>
                <div class="form-group add-customer d-none">
                    <input type="text"
                        class="form-control cust-2"
                        name="city-2"
                        id="city-2"
                        placeholder="City"
                        data-msg="Please fill the city" />
                    <div class="validation"></div>
                </div>
                <div class="form-group add-customer d-none">
                    <textarea class="form-control cust-2"
                        name="address-2"
                        id="address-2"
                        rows="5"
                        data-msg="Please fill the address"
                        placeholder="Address"></textarea>
                    <div class="validation"></div>
                </div>
                <div class="text-center">
                    <button id="tambah_member"
                        type="button"
                        style="background: #4caf3a;">
                        Tambah Pembeli
                    </button>
                </div>
                <br>

                <div class="form-group">
                    <select class="form-control"
                        id="cash_upgarde"
                        name="cash_upgrade"
                        data-msg="Please select a type"
                        required>
                        <option selected disabled value="">
                            Select CASH/UPGRADE
                        </option>

                        @foreach($cashUpgrades as $key => $cashUpgrade)
                            <option value="{{ $key }}">
                                {{ strtoupper($cashUpgrade) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="validation"></div>
                </div>

                <div id="container-cashupgrade" style="display: none;">
                    {{-- ++++++++++++++ Product ++++++++++++++ --}}
                    <div class="form-group"
                        style="width: 72%; display: inline-block;">
                        <select class="form-control pilihan-product"
                            name="product_0"
                            data-msg="Mohon Pilih Product"
                            required>
                            <option selected disabled value="">
                                Pilihan Product
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
                        style="width: 16%; display: inline-block;">
                        <select class="form-control"
                            name="qty_0"
                            data-msg="Please select a quantity"
                            required>
                            <option selected value="1">1</option>
                            @for ($i = 2; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        <div class="validation"></div>
                    </div>
                    <div class="text-center"
                        style="display: inline-block; float: right;">
                        <button id="tambah_product"
                            title="Add Product"
                            style="padding: 0.4em 0.7em;">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="form-group d-none">
                        <input type="text"
                            class="form-control"
                            name="product_other_0"
                            placeholder="Product Name"
                            data-msg="Please fill the product" />
                        <div class="validation"></div>
                    </div>

                    <div id="tambahan_product"></div>
                    {{-- ++++++++++++++ ======== ++++++++++++++ --}}

                    <div class="form-group" style="display: none">
                        <input type="text"
                            class="form-control"
                            name="old_product"
                            id="old_product"
                            placeholder="Old Product"
                            data-msg="Please fill the old product"
                            style="text-transform: uppercase;" />
                        <div class="validation"></div>
                    </div>
                    <div class="form-group">
                        <input type="text"
                            class="form-control"
                            name="prize"
                            id="prize"
                            placeholder="Prize Product"
                            data-msg="Please fill the prize"
                            style="text-transform: uppercase;" />
                        <div class="validation"></div>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <select class="form-control"
                        id="payment_type"
                        name="payment_type"
                        data-msg="Please select a type of payment"
                        required>
                        <option selected disabled value="">
                            Select type of payment
                        </option>

                        @foreach ($paymentTypes as $key => $paymentType)
                            <option value="{{ $key }}">
                                {{ strtoupper($paymentType) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="validation"></div>
                </div>
                <div id="container-jenispembayaran" style="display: none;">
                    {{-- ++++++++ BANK ++++++++ --}}
                    <div class="form-group bank_select"
                        style="width: 62%; display: inline-block;">
                        <select class="form-control bank_name"
                            name="bank_0"
                            data-msg="Please select a bank">
                            <option selected disabled value="">
                                Select Bank
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
                            data-msg="Please select the installment amount">
                            <option selected value="1">1X</option>
                            <option class="other_valCicilan" value="3">
                                3X
                            </option>
                            @for ($i = 6; $i <= 24; $i += 6)
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
                            title="Add Bank"
                            style="padding: 0.4em 0.7em;">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>

                    <div id="tambahan_bank"></div>
                    {{-- ++++++++ ==== ++++++++ --}}
                    <div id="container-totalHarga" style="display: none;">
                        <div class="form-group">
                            <input type="number"
                                class="form-control"
                                name="total_payment"
                                id="total_payment"
                                placeholder="Total Harga"
                                required
                                data-msg="Please fill the total price"
                                style="text-transform: uppercase;"/>
                            <div class="validation"></div>
                        </div>
                        <div class="form-group">
                            <input type="number"
                                class="form-control"
                                name="down_payment"
                                id="down_payment"
                                placeholder="Down Payment(DP)"
                                required
                                data-msg="Please fill the Down Payment (DP)"
                                style="text-transform: uppercase;"/>
                            <div class="validation"></div>
                        </div>
                        <div class="form-group">
                            <input type="number"
                                class="form-control"
                                name="remaining_payment"
                                id="remaining_payment"
                                placeholder="Remaining payment"
                                required
                                data-msg="Please fill the remaining payment"
                                style="text-transform: uppercase;"/>
                            <div class="validation"></div>
                        </div>
                    </div>
                </div>
                <br>

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
                <div id="container-Cabang" style="display: none;">
                    <div class="form-group">
                        <input type="text"
                            class="form-control cso"
                            name="cso_id"
                            id="cso"
                            placeholder="CSO Code"
                            required
                            data-msg="Please fill the CSO Code"
                            style="text-transform: uppercase;" />
                        <div class="validation"></div>
                    </div>
                    <div class="form-group">
                        <input type="text"
                            class="form-control cso"
                            name="30_cso_id"
                            id="30_cso"
                            placeholder="Kode Sales 30%"
                            required
                            data-msg="Please fill the CSO Code"
                            style="text-transform: uppercase;" />
                        <div class="validation"></div>
                    </div>
                    <div class="form-group">
                        <input type="text"
                            class="form-control cso"
                            name="70_cso_id"
                            id="70_cso"
                            placeholder="Kode Sales 70%"
                            required
                            data-msg="Please fill the CSO Code"
                            style="text-transform: uppercase;" />
                        <div class="validation"></div>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <span>Customer Type</span>
                    <select id="customer_type"
                        style="margin-top: 0.5em;"
                        class="form-control"
                        style="height: auto;"
                        name="customer_type"
                        value=""
                        required>
                        <option value="Tele Voucher">Tele Voucher</option>
                        <option value="Tele Home Service">
                            Tele Home Service
                        </option>
                        <option value="Home Office Voucher">
                            Home Office Voucher
                        </option>
                        <option value="Home Voucher">Home Voucher</option>
                    </select>
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <textarea class="form-control"
                        name="description"
                        rows="5"
                        data-msg="Mohon Isi Keterangan"
                        placeholder="Keterangan"></textarea>
                    <div class="validation"></div>
                </div>

                <div id="errormessage"></div>
                <div class="text-center">
                    <button id="submit" type="submit" title="Send Message">
                        Save Order Form
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
<script>
    var total_bank = 0;
    var total_product = 0;
    var arrBooleanCso = [ 'false', 'false', 'false' ];

    $(document).ready(function(){
        $(".cso").on("input", function(){
            var txtCso = $(this).val();
            var temp = $(this);
            $.get( '{{route("fetchCso")}}', { txt: txtCso })
            .done(function( result ) {
                var bool = false;

                if (result == 'true'){
                    $(temp).parent().children('.validation').html('Kode CSO Benar');
                    $(temp).parent().children('.validation').css('color', 'green');
                    bool = true;
                }
                else{
                    $(temp).parent().children('.validation').html('Kode CSO Salah');
                    $(temp).parent().children('.validation').css('color', 'red');
                }
                if(temp.attr("id") == 'cso'){
                    arrBooleanCso[0] = bool;
                }
                else if(temp.attr("id") == '30_cso'){
                    arrBooleanCso[1] = bool;
                }
                else if(temp.attr("id") == '70_cso'){
                    arrBooleanCso[2] = bool;
                }
                console.log(arrBooleanCso[0]+" "+arrBooleanCso[1]+" "+arrBooleanCso[2]);
                if(arrBooleanCso[0] == true && arrBooleanCso[1] == true && arrBooleanCso[2] == true){
                    $('#submit').removeAttr('disabled');
                    console.log("masuk");
                }
                else{
                    $('#submit').attr('disabled',"");
                }
            });
        });

        $("#tambah_bank").click(function(e){
            e.preventDefault();
            total_bank++;
            strIsi = "<div class=\"form-group bank_select\" style=\"width: 62%; display: inline-block;\" id=\"bank_"+total_bank+"\"><select class=\"form-control bank_name\" name=\"bank_"+total_bank+"\" data-msg=\"Mohon Pilih Bank\"><option selected disabled value=\"\">Pilihan Bank</option> @foreach($banks as $key=>$bank) <option value=\"{{ $key }}\">{{ $bank }}</option> @endforeach </select><div class=\"validation\"></div></div><div class=\"form-group bank_select\" style=\"width: 26%; display: inline-block;\" id=\"cicilan_"+total_bank+"\"><select class=\"form-control bank_cicilan\" name=\"cicilan_"+total_bank+"\" data-msg=\"Mohon Pilih Jumlah Cicilan\"><option selected value=\"1\">1X</option> <option class=\"other_valCicilan\" value=\"3\">3X</option> @for($i=6; $i<=24;$i+=6) <option class=\"other_valCicilan\" value=\"{{ $i }}\">{{ $i }}X</option> @endfor </select><div class=\"validation\"></div></div><div class=\"text-center\" style=\"display: inline-block; float: right;\"><button class=\"hapus_bank\" value=\""+total_bank+"\" title=\"Hapus Bank\" style=\"padding: 0.4em 0.7em; background-color: red\"><i class=\"fas fa-minus\"></i></button></div>";
            $('#tambahan_bank').html($('#tambahan_bank').html()+strIsi);


            if($("#payment_type").val() == 1){
                $(".other_valCicilan").attr('disabled', "");
                $(".other_valCicilan").hide();
            }
            else{
                $(".other_valCicilan").removeAttr('disabled');
                $(".other_valCicilan").show();
            }
        });
        $(document).on("click",".hapus_bank", function(e){
            e.preventDefault();
            total_bank--;
            $('#bank_'+$(this).val()).remove();
            $('#cicilan_'+$(this).val()).remove();
            $(this).remove();
        });

        $("#tambah_product").click(function(e){
            e.preventDefault();
            total_product++;
            strIsi = "<div id=\"product_"+total_product+"\" class=\"form-group\" style=\"width: 72%; display: inline-block;\"><select class=\"form-control pilihan-product\" name=\"product_"+total_product+"\" data-msg=\"Mohon Pilih Product\" required=\"\"><option selected disabled value=\"\">Pilihan Product</option> @foreach($promos as $key=>$promo) <option value=\"{{ $key }}\">{{ $promo['code'] }} - {{ $promo['name'] }} ( {{ $promo['harga'] }} )</option> @endforeach {!! true ? "<option value="."other".">OTHER</option>" : "" !!} </select><div class=\"validation\"></div></div><div id=\"qty_"+total_product+"\" class=\"form-group\" style=\"width: 16%; display: inline-block;\"><select class=\"form-control\" name=\"qty_"+total_product+"\" data-msg=\"Mohon Pilih Jumlah\" required=\"\"><option selected value=\"1\">1</option> @for($i=2; $i<=10;$i++) <option value=\"{{ $i }}\">{{ $i }}</option> @endfor </select><div class=\"validation\"></div></div><div class=\"text-center\" style=\"display: inline-block; float: right;\"><button class=\"hapus_product\" value=\""+total_product+"\" title=\"Tambah Product\" style=\"padding: 0.4em 0.7em; background-color: red;\"><i class=\"fas fa-minus\"></i></button></div>";

            strIsi += "<div class=\"form-group d-none\"><input type=\"text\" class=\"form-control\" name=\"product_other_"+total_product+"\" placeholder=\"Product Name\" data-msg=\"Please fill the product\" /><div class=\"validation\"></div></div>";

            $('#tambahan_product').html($('#tambahan_product').html()+strIsi);
        });
        $(document).on("click",".hapus_product", function(e){
            e.preventDefault();
            total_product--;
            $('#product_'+$(this).val()).remove();
            $('#qty_'+$(this).val()).remove();
            $(this).remove();
        });
        $("#province").on("change", function(){
            var id = $(this).val();
            $( "#city" ).html("");
            $.get( '{{ route("fetchCity", ['province' => ""]) }}/'+id )
            .done(function( result ) {
                result = result['rajaongkir']['results'];
                var arrCity = "<option selected disabled value=\"\">Pilihan Kota</option>";
                if(result.length > 0){
                    $.each( result, function( key, value ) {
                        if(value['type'] == "Kota"){
                            arrCity += "<option value=\"Kota "+value['city_name']+"\">Kota "+value['city_name']+"</option>";
                        }
                    });
                    $( "#city" ).append(arrCity);
                }
            });
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
                $("#old_product").val("");
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

        $(document).on("change", ".bank_name", function(e){
            $("#container-totalHarga").show();
        });

         $("#branch").change( function(e){
            $("#container-Cabang").show();
        });

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
@endsection
