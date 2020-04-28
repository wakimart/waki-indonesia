@extends('admin.layouts.template')
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

<div class="main-panel">
  	<div class="content-wrapper">
    	<div class="page-header">
      		<h3 class="page-title">Add Order</h3>
      		<nav aria-label="breadcrumb">
	        	<ol class="breadcrumb">
	          		<li class="breadcrumb-item"><a data-toggle="collapse" href="#deliveryorder-dd" aria-expanded="false" aria-controls="deliveryorder-dd">Order</a></li>
	          		<li class="breadcrumb-item active" aria-current="page">Add Order</li>
	        	</ol>
      		</nav>
    	</div>
	    <div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">	            		
	            		<form id="actionAdd" class="forms-sample" method="POST" action="{{ route('store_order') }}">
	            			{{ csrf_field() }}
	              			<div class="form-group">
	                			<label for="">No. Member (optional)</label>
	                			<input type="number" class="form-control" id="no_member" name="no_member" placeholder="No. Member">
	                			<div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">Nama</label>
				                <input type="text" class="form-control" id="name" name="name" placeholder="Nama">
				                <div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">No. Telepon</label>
				                <input type="number" class="form-control" id="phone" name="phone" placeholder="No. Telepon">
				                <div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">City</label>
				                <input type="text" class="form-control" id="city" name="city" placeholder="Kota">
				                <div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="exampleTextarea1">Alamat</label>
				                <textarea class="form-control" id="address" name="address" rows="4" placeholder="Alamat Lengkap"></textarea>
				                <div class="validation"></div>
	              			</div>

	              			<div class="form-group">
	              				<label for="exampleTextarea1">Pilihan CASH/UPGRADE</label>
			                    <select class="form-control" id="cash_upgarde" name="cash_upgrade" data-msg="Mohon Pilih Tipe" required>
			                        <option selected disabled value="">Pilihan CASH/UPGRADE</option>

			                        @foreach($cashUpgrades as $key=>$cashUpgrade)
			                            <option value="{{ $key }}">{{ strtoupper($cashUpgrade) }}</option>
			                        @endforeach
			                    </select>
			                    <div class="validation"></div>
			                </div>

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
			                <br>

			                <div class="form-group">
			                	<label for="exampleTextarea1">Pilihan Jenis Pembayaran</label>
			                    <select class="form-control" id="payment_type" name="payment_type" data-msg="Mohon Pilih Tipe" required>
			                        <option selected disabled value="">Pilihan Jenis Pembayaran</option>

			                        @foreach($paymentTypes as $key=>$paymentType)
			                            <option value="{{ $key }}">{{ strtoupper($paymentType) }}</option>
			                        @endforeach
			                    </select>
			                    <div class="validation"></div>
			                </div>

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
			                            <option value="{{ $i }}">{{ $i }}X</option>
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
			                <br>
	              			
	              			<div class="form-group">
				                <label for="">Pilihan Cabang</label>
				                <select class="form-control" id="branch" name="branch_id" data-msg="Mohon Pilih Cabang" required>
				                  	<option selected disabled value="">Pilihan Cabang</option>

			                        @foreach($branches as $branch)
			                            <option value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
			                        @endforeach
	                			</select>
	                			<div class="validation"></div>
	              			</div>
	              			<div class="form-group">
	                			<label for="">Kode CSO</label>
	               			 	<input type="text" class="form-control" name="cso_id" id="cso" placeholder="Kode CSO" required data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase"/>
                    			<div class="validation" id="validation_cso"></div>
	              			</div>
	              			<div class="form-group">
	              				<label for="">Kode Sales 30%</label>
			                    <input type="text" class="form-control cso" name="30_cso_id" id="30_cso" placeholder="Kode Sales 30%" required data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase"/>
			                    <div class="validation"></div>
			                </div>
			                <div class="form-group">
			                	<label for="">Kode Sales 70%</label>
			                    <input type="text" class="form-control cso" name="70_cso_id" id="70_cso" placeholder="Kode Sales 70%" required data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase"/>
			                    <div class="validation"></div>
			                </div>
			                <br>

			                <div class="form-group">
			                	<label for="">Tipe Customer</label>
			                    <input type="text" class="form-control" name="customer_type" id="customer_type" placeholder="Tipe Customer" required data-msg="Mohon Isi Tipe Customer" />
			                    <div class="validation"></div>
			                </div>
			                <div class="form-group">
			                	<label for="">Keterangan</label>
			                    <textarea class="form-control" name="description" rows="5" data-msg="Mohon Isi Keterangan" placeholder="Keterangan"></textarea>
			                    <div class="validation"></div>
			                </div>

	              			<div id="errormessage"></div>

	              			<div class="form-group">
	              				<button id="addOrder" type="submit" class="btn btn-gradient-primary mr-2">Simpan</button>
	              				<button class="btn btn-light">Batal</button>	
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
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
        var frmAdd;

	    $("#actionAdd").on("submit", function (e) {
	        e.preventDefault();
	        frmAdd = _("actionAdd");
	        frmAdd = new FormData(document.getElementById("actionAdd"));
	        frmAdd.enctype = "multipart/form-data";
	        var URLNya = $("#actionAdd").attr('action');
	        console.log(URLNya);

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
	        console.log(hasil);

	        for (var key of frmAdd.keys()) {
	            $("#actionAdd").find("input[name="+key+"]").removeClass("is-invalid");
	            $("#actionAdd").find("select[name="+key+"]").removeClass("is-invalid");
	            $("#actionAdd").find("textarea[name="+key+"]").removeClass("is-invalid");

	            $("#actionAdd").find("input[name="+key+"]").next().find("strong").text("");
	            $("#actionAdd").find("select[name="+key+"]").next().find("strong").text("");
	            $("#actionAdd").find("textarea[name="+key+"]").next().find("strong").text("");
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
	            alert("Input Error !!!");
	        }
	        else{
	            alert("Input Success !!!");
	            window.location.reload()
	        }

	        document.getElementById("addOrder").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("addOrder").innerHTML = "SAVE";
	    }
    });
</script>
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
            strIsi = "<div class=\"form-group bank_select\" style=\"width: 62%; display: inline-block;\" id=\"bank_"+total_bank+"\"><select class=\"form-control bank_name\" name=\"bank_"+total_bank+"\" data-msg=\"Mohon Pilih Bank\"><option selected disabled value=\"\">Pilihan Bank</option> @foreach($banks as $key=>$bank) <option value=\"{{ $key }}\">{{ $bank }}</option> @endforeach </select><div class=\"validation\"></div></div><div class=\"form-group bank_select\" style=\"width: 26%; display: inline-block;\" id=\"cicilan_"+total_bank+"\"><select class=\"form-control bank_cicilan\" name=\"cicilan_"+total_bank+"\" data-msg=\"Mohon Pilih Jumlah Cicilan\"><option selected value=\"1\">1X</option> @for($i=2; $i<=12;$i+=2) <option value=\"{{ $i }}\">{{ $i }}X</option> @endfor </select><div class=\"validation\"></div></div><div class=\"text-center\" style=\"display: inline-block; float: right;\"><button class=\"hapus_bank\" value=\""+total_bank+"\" title=\"Hapus Bank\" style=\"padding: 0.4em 0.7em; background-color: red\"><i class=\"fas fa-minus\"></i></button></div>";
            $('#tambahan_bank').html($('#tambahan_bank').html()+strIsi);
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
            if($(this).val() == 2){
                $("#old_product").parent().show();
                $("#old_product").attr('required', "");
            }
            else{
                $("#old_product").parent().hide();
                $("#old_product").removeAttr('required');
            }
        });
    });
</script>
<script type="text/javascript" src="{{ asset('js/tags-input.js') }}"></script>
<script type="text/javascript">
    for (let input of document.querySelectorAll('#tags')) {
        tagsInput(input);
    }
</script>
@endsection