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
    table{
        margin: 1em;
        font-size: 14px;
    }
    table thead{
        background-color: #8080801a;
        text-align: center;
    }
    table td{
        border: 0.5px #8080801a solid;
        padding: 0.5em;
    }
    .right{
        text-align: right;
    }
    .pInTable{
        margin-bottom: 6pt !important;
        font-size: 10pt;
    }
</style>


<section id="intro" class="clearfix">
    <div class="container">
        <div class="row justify-content-center">
            <h2>REGISTRASI HOME SERVICE BERHASIL</h2>
        </div>

        <div class="row justify-content-center">
          <table class="col-md-12">
              <tr>
                  <td>
                      <p class="pInTable">
                        Terima kasih kepada bapak/ibu yang terhormat telah memberi
                        dukungan dan dorongan kepada WAKi Indonesia selama ini untuk
                        lebih berkembang dan lebih baik di masa mendatang.</p>
                      <p class="pInTable">Tujuan Home Service adalah untuk merapatkan hubungan antara
                        bapak/ibu sama WAKi dan biar bapak/ibu lebih memahami cara guna
                        WAKi produk supaya capai kesan yang lebih baik.</p>
                      <p class="pInTable">Team WAKi Home Service akan menghubungi terlebih dahulu
                        sebelum berangkat ke tempat Bapak/Ibu. Untuk informasi lebih lanjut
                        atau perubahan jadwal home service, dapat menghubungi WAKi
                        Home Service Department kembali di nomor (+6281234511881) atau
                        (Nama), (H/p No), (Cabang).</p>
                  </td>
              </tr>
          </table>
        </div>

        <div class="row justify-content-center">
            <table class="col-md-12">
                <thead>
                    <td colspan="2">Informasi Kustomer </td>
                </thead>
                <tr>
                    <td>No. Member : </td>
                    <td>WKF-0001</td>
                </tr>
                <tr>
                    <td>Nama : </td>
                    <td>Noname Nama</td>
                </tr>
                <tr>
                    <td>No. Telp : </td>
                    <td>08122334455667</td>
                </tr>
                <tr>
                    <td>Kota : </td>
                    <td>Surabaya</td>
                </tr>
                <tr>
                    <td>Alamat : </td>
                    <td>Jl. Raya Lebar</td>
                </tr>
                <tr>
                    <td>Cabang Pembelian : </td>
                    <td>Darmo Park</td>
                </tr>
                <tr>
                    <td>Kode CSO : </td>
                    <td>1122334455</td>
                </tr>
            </table>
            <table class="col-md-12">
                <thead>
                    <td colspan="2">Tanggal dan Waktu Janjian </td>
                </thead>
                <tr>
                    <td>Tanggal : </td>
                    <td>30 Februari 2021</td>
                </tr>
                <tr>
                    <td>Waktu : </td>
                    <td>10:00 AM</td>
                </tr>

            </table>

            <table class="col-md-12">
                <thead>
                    <td colspan="2">Kebijakan Home Service WAKi</td>
                </thead>
                <tr>
                    <td>
                      <p class="pInTable">1. Home Service dari WAKi tidak dipungut biaya apapun.</p>
                      <p class="pInTable">2. Biaya akan dikenakan kepada konsumen jika ada sparepat ataupun kerusakan
                        di luar persetujuan MPC / Warranty.</p>
                      <p class="pInTable">3. Kenyamanan dan keamanan konsumen kami adalah prioritas pertama. Jika
                        ada sesuatu bisa hubungi kami melalui Home Service department:
                        +6281234511881.</p>
                      <p class="pInTable">4. Form ini akan carbon copy (CC) kepada Customer, Petugas, Ketua Cabang,
                        Home Service Department.</p>
                    </td>
                </tr>
            </table>
            <div class="text-center"><button id="submit" type="submit" title="" disabled="" data-action="share/whatsapp/share">Bagikan melalui Whatsapp</button></div>
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
