@extends('admin.layouts.template')

@section('content')
<div class="main-panel">
  	<div class="content-wrapper">
    	<div class="page-header">
      		<h3 class="page-title">Add Delivery Order</h3>
      		<nav aria-label="breadcrumb">
	        	<ol class="breadcrumb">
	          		<li class="breadcrumb-item"><a data-toggle="collapse" href="#deliveryorder-dd" aria-expanded="false" aria-controls="deliveryorder-dd">DO</a></li>
	          		<li class="breadcrumb-item active" aria-current="page">Add DO</li>
	        	</ol>
      		</nav>
    	</div>
	    <div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">	            		
	            		<form id="actionAdd" class="forms-sample" method="POST" action="{{ route('store_deliveryorder') }}">
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

	              			@for($j=0;$j<2;$j++)
	              			<div class="form-group">
	                			<div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
	                  				<div class="col-xs-10 col-sm-10" style="padding: 0;display: inline-block;">
					                    <label for="">Pilihan Promo</label>
					                    <select class="form-control" name="product_{{ $j }}" data-msg="Mohon Pilih Promo" {{ $j>0 ? "":"required"}}>
					                    	<option selected disabled value="">Pilihan Promo{{ $j>0 ? " (optional)":""}}</option>
					                      	@foreach($promos as $key=>$promo)
				                                <option value="{{ $key }}">{{ $promo['code'] }} - {{ $promo['name'] }} ( {{ $promo['harga'] }} )</option>
				                            @endforeach
	                    				</select>
	                    				<div class="validation"></div>
	                  				</div>
	                  				<div class="col-xs-2 col-sm-2" style="padding-right: 0;display: inline-block;">
	                    				<label for="">Jumlah</label>
	                					<select class="form-control" name="qty_{{ $j }}" data-msg="Mohon Pilih Jumlah" {{ $j>0 ? "":"required"}}>
	                  						<option selected value="1">1</option>

				                            @for($i=2; $i<=10;$i++)
				                                <option value="{{ $i }}">{{ $i }}</option>
				                            @endfor
	                    				</select>
	                    				<div class="validation"></div>
	                  				</div>
	                			</div>
	              			</div>
	              			@endfor
	              			
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

	              			<div id="errormessage"></div>

	              			<div class="form-group">
	              				<button id="addDeliveryOrder" type="submit" class="btn btn-gradient-primary mr-2">Simpan</button>
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
	        document.getElementById("addDeliveryOrder").innerHTML = "UPLOADING...";
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

	        document.getElementById("addDeliveryOrder").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("addDeliveryOrder").innerHTML = "SAVE";
	    }
    });
</script>
<script type="text/javascript" src="{{ asset('js/tags-input.js') }}"></script>
<script type="text/javascript">
    for (let input of document.querySelectorAll('#tags')) {
        tagsInput(input);
    }
</script>
<script>
    $(document).ready(function(){
        $("#cso").on("input", function(){
            var txtCso = $(this).val();
            $.get( '{{route("fetchCso")}}', { txt: txtCso })
            .done(function( result ) {
                console.log(result);
                if (result == 'true'){
                    $('#validation_cso').html('Kode CSO Benar');
                    $('#validation_cso').css('color', 'green');
                    $('#submit').removeAttr('disabled');
                }
                else{
                    $('#validation_cso').html('Kode CSO Salah');
                    $('#validation_cso').css('color', 'red');
                    $('#submit').attr('disabled',"");
                }
            });
        });
    });
</script>

@endsection