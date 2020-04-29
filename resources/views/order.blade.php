@extends('layouts.template')

@section('content')
<style type="text/css">
    #intro {
        padding-top: 2em;
    }
    button{
        background: #1bb1dc;
        border: 0;
        border-radius: 3px;
        padding: 8px 30px;
        color: #fff;
        transition: 0.3s;
    }
    .validation{
        color: red;
        font-size: 9pt;
    }
    input, select, textarea{
        border-radius: 0 !important;
        box-shadow: none !important;
        border: 1px solid #dce1ec !important;
        font-size: 14px !important;
    }
</style>


<section id="intro" class="clearfix">
    <div class="container">
        <div class="row justify-content-center">
            <h2>FORM ORDER</h2>
        </div>
        <div class="row justify-content-center">
            <form action="{{ Route('store_order') }}" method="post" role="form" class="contactForm col-md-9">
                @csrf
                <div class="form-group">
                    <input type="text" name="no_member" class="form-control" id="no_member" placeholder="No. Member (optional)"/>
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Nama" required data-msg="Mohon Isi Nama" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="phone" id="phone" placeholder="No. Telepon" required data-msg="Mohon Isi Nomor Telepon" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="city" id="city" placeholder="Kota" required data-msg="Mohon Isi Kota" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <textarea class="form-control" name="address" rows="5" required data-msg="Mohon Isi Alamat" placeholder="Alamat"></textarea>
                    <div class="validation"></div>
                </div>
                <br>

                <div class="form-group">
                    <select class="form-control" id="cash_upgarde" name="cash_upgrade" data-msg="Mohon Pilih Tipe" required>
                        <option selected disabled value="">Pilihan CASH/UPGRADE</option>

                        @foreach($cashUpgrades as $key=>$cashUpgrade)
                            <option value="{{ $key }}">{{ strtoupper($cashUpgrade) }}</option>
                        @endforeach
                    </select>
                    <div class="validation"></div>
                </div>

                <div id="container-cashupgrade" style="display: none;">
                    {{-- ++++++++++++++ Product ++++++++++++++ --}}
                    <div class="form-group" style="width: 72%; display: inline-block;">
                        <select class="form-control" name="product_0" data-msg="Mohon Pilih Product" required="">
                            <option selected disabled value="">Pilihan Product</option>

                            @foreach($promos as $key=>$promo)
                                <option value="{{ $key }}">{{ $promo['code'] }} - {{ $promo['name'] }} ( {{ $promo['harga'] }} )</option>
                            @endforeach
                        </select>
                        <div class="validation"></div>
                    </div>
                    <div class="form-group" style="width: 16%; display: inline-block;">
                        <select class="form-control" name="qty_0" data-msg="Mohon Pilih Jumlah" required="">
                            <option selected value="1">1</option>

                            @for($i=2; $i<=10;$i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        <div class="validation"></div>
                    </div>
                    <div class="text-center" style="display: inline-block; float: right;"><button id="tambah_product" title="Tambah Product" style="padding: 0.4em 0.7em;"><i class="fas fa-plus"></i></button></div>

                    <div id="tambahan_product"></div>
                    {{-- ++++++++++++++ ======== ++++++++++++++ --}}

                    <div class="form-group" style="display: none">
                        <input type="text" class="form-control" name="old_product" id="old_product" placeholder="Product Lama" data-msg="Mohon Isi Produk Lama" style="text-transform:uppercase"/>
                        <div class="validation"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="prize" id="prize" placeholder="Product Hadiah" data-msg="Mohon Isi Hadiah" style="text-transform:uppercase"/>
                        <div class="validation"></div>
                    </div>
                </div>
                <br>

                <div class="form-group">
                    <select class="form-control" id="payment_type" name="payment_type" data-msg="Mohon Pilih Tipe" required>
                        <option selected disabled value="">Pilihan Jenis Pembayaran</option>

                        @foreach($paymentTypes as $key=>$paymentType)
                            <option value="{{ $key }}">{{ strtoupper($paymentType) }}</option>
                        @endforeach
                    </select>
                    <div class="validation"></div>
                </div>
                <div id="container-jenispembayaran" style="display: none;">
                    {{-- ++++++++ BANK ++++++++ --}}
                    <div class="form-group bank_select" style="width: 62%; display: inline-block;">
                        <select class="form-control bank_name" name="bank_0" data-msg="Mohon Pilih Bank">
                            <option selected disabled value="">Pilihan Bank</option>

                            @foreach($banks as $key=>$bank)
                                <option value="{{ $key }}">{{ $bank }}</option>
                            @endforeach
                        </select>
                        <div class="validation"></div>
                    </div>
                    <div class="form-group bank_select" style="width: 26%; display: inline-block;">
                        <select class="form-control bank_cicilan" name="cicilan_0" data-msg="Mohon Pilih Jumlah Cicilan">
                            <option selected value="1">1X</option>
                            @for($i=2; $i<=12;$i+=2)
                                <option class="other_valCicilan" value="{{ $i }}">{{ $i }}X</option>
                            @endfor
                        </select>
                        <div class="validation"></div>
                    </div>
                    <div class="text-center" style="display: inline-block; float: right;"><button id="tambah_bank" title="Tambah Bank" style="padding: 0.4em 0.7em;"><i class="fas fa-plus"></i></button></div>

                    <div id="tambahan_bank"></div>
                    {{-- ++++++++ ==== ++++++++ --}}
                    <div class="form-group">
                        <input type="number" class="form-control" name="total_payment" id="total_payment" placeholder="Total Harga" required data-msg="Mohon Isi Total Harga" style="text-transform:uppercase"/>
                        <div class="validation"></div>
                    </div>
                    <div class="form-group">
                        <input type="number" class="form-control" name="down_payment" id="down_payment" placeholder="Down Payment(DP)" required data-msg="Mohon Isi Down Payment(DP)" style="text-transform:uppercase"/>
                        <div class="validation"></div>
                    </div>
                    <div class="form-group">
                        <input type="number" class="form-control" name="remaining_payment" id="remaining_payment" placeholder="Sisa Pembayaran" required data-msg="Mohon Isi Sisa Pembayaran" style="text-transform:uppercase"/>
                        <div class="validation"></div>
                    </div>
                </div>
                <br>

                <div class="form-group">
                    <select class="form-control" id="branch" name="branch_id" data-msg="Mohon Pilih Cabang" required>
                        <option selected disabled value="">Pilihan Cabang</option>

                        @foreach($branches as $branch)
                            <option value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
                        @endforeach
                    </select>
                    <div class="validation"></div>
                </div>
                <div id="container-Cabang" style="display: none;">
                    <div class="form-group">
                        <input type="text" class="form-control cso" name="cso_id" id="cso" placeholder="Kode Sales" required data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase"/>
                        <div class="validation"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control cso" name="30_cso_id" id="30_cso" placeholder="Kode Sales 30%" required data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase"/>
                        <div class="validation"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control cso" name="70_cso_id" id="70_cso" placeholder="Kode Sales 70%" required data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase"/>
                        <div class="validation"></div>
                    </div>
                </div>
                <br>


                <div class="form-group">
                    <input type="text" class="form-control" name="customer_type" id="customer_type" placeholder="Tipe Customer" data-msg="Mohon Isi Tipe Customer" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <textarea class="form-control" name="description" rows="5" data-msg="Mohon Isi Keterangan" placeholder="Keterangan"></textarea>
                    <div class="validation"></div>
                </div>

                <div id="errormessage"></div>
                <div class="text-center"><button id="submit" type="submit" title="Send Message" disabled="">Simpan Form Order</button></div>
            </form>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
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
            strIsi = "<div class=\"form-group bank_select\" style=\"width: 62%; display: inline-block;\" id=\"bank_"+total_bank+"\"><select class=\"form-control bank_name\" name=\"bank_"+total_bank+"\" data-msg=\"Mohon Pilih Bank\"><option selected disabled value=\"\">Pilihan Bank</option> @foreach($banks as $key=>$bank) <option value=\"{{ $key }}\">{{ $bank }}</option> @endforeach </select><div class=\"validation\"></div></div><div class=\"form-group bank_select\" style=\"width: 26%; display: inline-block;\" id=\"cicilan_"+total_bank+"\"><select class=\"form-control bank_cicilan\" name=\"cicilan_"+total_bank+"\" data-msg=\"Mohon Pilih Jumlah Cicilan\"><option selected value=\"1\">1X</option> @for($i=2; $i<=12;$i+=2) <option class=\"other_valCicilan\" value=\"{{ $i }}\">{{ $i }}X</option> @endfor </select><div class=\"validation\"></div></div><div class=\"text-center\" style=\"display: inline-block; float: right;\"><button class=\"hapus_bank\" value=\""+total_bank+"\" title=\"Hapus Bank\" style=\"padding: 0.4em 0.7em; background-color: red\"><i class=\"fas fa-minus\"></i></button></div>";
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
            strIsi = "<div id=\"product_"+total_product+"\" class=\"form-group\" style=\"width: 72%; display: inline-block;\"><select class=\"form-control\" name=\"product_"+total_product+"\" data-msg=\"Mohon Pilih Product\" required=\"\"><option selected disabled value=\"\">Pilihan Product</option> @foreach($promos as $key=>$promo) <option value=\"{{ $key }}\">{{ $promo['code'] }} - {{ $promo['name'] }} ( {{ $promo['harga'] }} )</option> @endforeach </select><div class=\"validation\"></div></div><div id=\"qty_"+total_product+"\" class=\"form-group\" style=\"width: 16%; display: inline-block;\"><select class=\"form-control\" name=\"qty_"+total_product+"\" data-msg=\"Mohon Pilih Jumlah\" required=\"\"><option selected value=\"1\">1</option> @for($i=2; $i<=10;$i++) <option value=\"{{ $i }}\">{{ $i }}</option> @endfor </select><div class=\"validation\"></div></div><div class=\"text-center\" style=\"display: inline-block; float: right;\"><button class=\"hapus_product\" value=\""+total_product+"\" title=\"Tambah Product\" style=\"padding: 0.4em 0.7em; background-color: red;\"><i class=\"fas fa-minus\"></i></button></div>";
            $('#tambahan_product').html($('#tambahan_product').html()+strIsi);
        });
        $(document).on("click",".hapus_product", function(e){
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

         $("#branch").change( function(e){
            $("#container-Cabang").show();
        });
    });
</script>
@endsection
