@extends('admin.layouts.template')

@section('content')
<div class="main-panel">
  	<div class="content-wrapper">
    	<div class="page-header">
      		<h3 class="page-title">Edit Delivery Order</h3>
    	</div>
	    <div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">	            		
	            		<form id="actionUpdate" class="forms-sample" method="POST" action="{{ route('update_deliveryorder') }}">
	            			{{ csrf_field() }}
	            			<div class="form-group">
	                			<label for="">Order Code</label>
	                			<input type="text" class="form-control" id="code" name="code" value="{{$deliveryOrders['code']}}" disabled="true">
	                			<div class="validation"></div>
	              			</div>
	            			<div class="form-group">
	                			<label for="">No. Member (optional)</label>
	                			<input type="number" class="form-control" id="no_member" name="no_member" value="{{$deliveryOrders['no_member']}}">
	                			<div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">Nama</label>
				                <input type="text" class="form-control" id="name" name="name" value="{{$deliveryOrders['name']}}">
				                <div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">No. Telepon</label>
				                <input type="number" class="form-control" id="phone" name="phone" value="{{$deliveryOrders['phone']}}">
				                <div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">City</label>
				                <input type="text" class="form-control" id="city" name="city" value="{{$deliveryOrders['city']}}">
				                <div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="exampleTextarea1">Alamat</label>
				                <textarea class="form-control" id="address" name="address" rows="4" placeholder="Alamat Lengkap">{{$deliveryOrders['address']}}</textarea>
				                <div class="validation"></div>
	              			</div>

	              			@php 
	                            $ProductPromos = json_decode($deliveryOrders['arr_product'], true);
	                            $totalProduct = count($ProductPromos);
	                            $j = 0;
	                        @endphp
	              			@foreach($ProductPromos as $key => $ProductPromo)
	              			@php $j++; @endphp
	              			<div class="form-group">
	                			<div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
	                  				<div class="col-xs-10 col-sm-10" style="padding: 0;display: inline-block;">
					                    <label for="">Pilihan Promo</label>
					                    <select class="form-control" name="product_{{ $j }}" data-msg="Mohon Pilih Promo" {{ $j>0 ? "":"required"}}>
					                    	<option selected disabled value="">Pilihan Promo{{ $j>0 ? " (optional)":""}}</option>
					                      	@foreach($promos as $key=>$promo)
					                      		@if(App\DeliveryOrder::$Promo[$ProductPromo['id']]['code'] == $promo['code'])
				                                	<option value="{{ $key }}" selected="true">{{ $promo['code'] }} - {{ $promo['name'] }} ( {{ $promo['harga'] }} )</option>
				                                @else
				                                	<option value="{{ $key }}">{{ $promo['code'] }} - {{ $promo['name'] }} ( {{ $promo['harga'] }} )</option>
				                                @endif
				                            @endforeach
	                    				</select>
	                    				<div class="validation"></div>
	                  				</div>
	                  				<div class="col-xs-2 col-sm-2" style="padding-right: 0;display: inline-block;">
	                    				<label for="">Jumlah</label>
	                					<select class="form-control" name="qty_{{ $j }}" data-msg="Mohon Pilih Jumlah" {{ $j>0 ? "":"required"}}>
	                  						<option selected value="1">1</option>

				                            @for($i=2; $i<=10;$i++)
				                            	@if($ProductPromo['qty'] == $i)
				                                <option value="{{ $i }}" selected="true">{{ $i }}</option>
				                                @else
				                                <option value="{{ $i }}">{{ $i }}</option>
				                                @endif
				                            @endfor
	                    				</select>
	                    				<div class="validation"></div>
	                  				</div>
	                			</div>
	              			</div>
	              			@endforeach
	              			
	              			<div class="form-group">
				                <label for="">Pilihan Cabang</label>
				                <select class="form-control" id="branch" name="branch_id" data-msg="Mohon Pilih Cabang" required>
				                  	<option selected disabled value="">Pilihan Cabang</option>

			                        @foreach($branches as $branch)
			                        	@if($deliveryOrders['branch_id'] == $branch['id'])
			                        		<option value="{{ $branch['id'] }}" selected="true">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
			                        	@else
			                        		<option value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
			                        	@endif
			                        @endforeach
	                			</select>
	                			<div class="validation"></div>
	              			</div>
	              			<div class="form-group">
	                			<label for="">Kode CSO</label>
	               			 	<input type="text" class="form-control" name="cso_id" id="cso" value="{{$deliveryOrders->cso['code']}}" required data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase"/>
                    			<div class="validation" id="validation_cso"></div>
	              			</div>

	              			<div id="errormessage"></div>

	              			<div class="form-group">
	              				<input type="hidden" name="idCSO" value="{{$deliveryOrders['cso_id']}}">
	              				<input type="hidden" name="idDeliveryOrder" value="{{$deliveryOrders['id']}}">
	              				<button id="updateDeliveryOrder" type="submit" class="btn btn-gradient-primary mr-2">Simpan</button>
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
        var frmUpdate;

	    $("#actionUpdate").on("submit", function (e) {
	        e.preventDefault();
	        frmUpdate = _("actionUpdate");
	        frmUpdate = new FormData(document.getElementById("actionUpdate"));
	        frmUpdate.enctype = "multipart/form-data";
	        var URLNya = $("#actionUpdate").attr('action');
	        console.log(URLNya);

	        var ajax = new XMLHttpRequest();
	        ajax.upload.addEventListener("progress", progressHandler, false);
	        ajax.addEventListener("load", completeHandler, false);
	        ajax.addEventListener("error", errorHandler, false);
	        ajax.open("POST", URLNya);
	        ajax.setRequestHeader("X-CSRF-TOKEN",$('meta[name="csrf-token"]').attr('content'));
	        ajax.send(frmUpdate);
	    });
	    function progressHandler(event){
	        document.getElementById("updateDeliveryOrder").innerHTML = "UPLOADING...";
	    }
	    function completeHandler(event){
	        var hasil = JSON.parse(event.target.responseText);
	        console.log(hasil);

	        for (var key of frmUpdate.keys()) {
	            $("#actionUpdate").find("input[name="+key+"]").removeClass("is-invalid");
	            $("#actionUpdate").find("select[name="+key+"]").removeClass("is-invalid");
	            $("#actionUpdate").find("textarea[name="+key+"]").removeClass("is-invalid");

	            $("#actionUpdate").find("input[name="+key+"]").next().find("strong").text("");
	            $("#actionUpdate").find("select[name="+key+"]").next().find("strong").text("");
	            $("#actionUpdate").find("textarea[name="+key+"]").next().find("strong").text("");
	        }

	        if(hasil['errors'] != null){
	            for (var key of frmUpdate.keys()) {
	                if(typeof hasil['errors'][key] === 'undefined') {
	                    
	                }
	                else {
	                    $("#actionUpdate").find("input[name="+key+"]").addClass("is-invalid");
	                    $("#actionUpdate").find("select[name="+key+"]").addClass("is-invalid");
	                    $("#actionUpdate").find("textarea[name="+key+"]").addClass("is-invalid");

	                    $("#actionUpdate").find("input[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
	                    $("#actionUpdate").find("select[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
	                    $("#actionUpdate").find("textarea[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
	                }
	            }
	            alert("Update Error !!!");
	        }
	        else{
	            alert("Update Success !!!");
	            window.location.reload()
	        }

	        document.getElementById("updateDeliveryOrder").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("updateDeliveryOrder").innerHTML = "SAVE";
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