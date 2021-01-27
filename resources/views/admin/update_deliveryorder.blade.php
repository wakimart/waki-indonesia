<?php
    $menu_item_page = "deliveryorder";
?>
@extends('admin.layouts.template')

@section('content')
<div class="main-panel">
  	<div class="content-wrapper">
    	<div class="page-header">
      		<h3 class="page-title">Edit Registration</h3>
    	</div>
	    <div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">	            		
	            		<form id="actionUpdate" class="forms-sample" method="POST" action="{{ route('update_deliveryorder') }}">
	            			{{ csrf_field() }}
	            			<div class="form-group">
	                			<label for="">Registration Code</label>
	                			<input type="text" class="form-control" id="code" name="code" value="{{$deliveryOrders['code']}}" readonly="">
	                			<div class="validation"></div>
	              			</div>
	            			<div class="form-group">
	                			<label for="">No. Member (optional)</label>
	                			<input type="text" class="form-control" id="no_member" name="no_member" value="{{$deliveryOrders['no_member']}}">
	                			<div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">Name</label>
				                <input type="text" class="form-control" id="name" name="name" value="{{$deliveryOrders['name']}}">
				                <div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">Phone Number</label>
				                <input type="number" class="form-control" id="phone" name="phone" value="{{$deliveryOrders['phone']}}">
				                <div class="validation"></div>
	              			</div>
	              			{{-- <div class="form-group">
				                <label for="">City</label>
				                <input type="text" class="form-control" id="city" name="city" value="{{$deliveryOrders['city']}}">
				                <div class="validation"></div>
							  </div> --}}
							  <div class="form-group">
				                <label for="">Province</label>
								<select class="form-control" id="province" name="province_id" data-msg="Mohon Pilih Provinsi" required>
									<option selected disabled value="">{{$deliveryOrders['district']['province']}}</option>
									@php
										$result = RajaOngkir::FetchProvince();
										$result = $result['rajaongkir']['results'];
										$arrProvince = [];
										if(sizeof($result) > 0){
											foreach ($result as $value) {
												echo "<option value=\"". $value['province_id']."\">".$value['province']."</option>";
											}
										}
									@endphp
								</select>
								<div class="validation"></div>
							  </div>
							<div class="form-group">
				                <label for="">City</label>
								<select class="form-control" id="city" name="city" data-msg="Mohon Pilih Kota" required>
									<option selected disabled value="">{{$deliveryOrders['district']['city']}}</option>
								</select>
								<div class="validation"></div>
							</div>
							<div class="form-group">
				                <label for="">Sub District</label>
								<select class="form-control" id="subDistrict" name="distric" data-msg="Mohon Pilih Kecamatan" required>
									<option selected disabled value="">{{$deliveryOrders['district']['subdistrict_name']}}</option>
								</select>
								<div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="exampleTextarea1">Address</label>
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
						                    <label for="">Promo</label>
						                    <select class="form-control pilihan-product" name="product_{{ $j }}" data-msg="Mohon Pilih Promo" {{ $j>0 ? "":"required"}}>
						                    	<option selected disabled value="">Choose Promo{{ $j>0 ? " (optional)":""}}</option>

					                            @if(is_numeric($ProductPromo['id']))
						                            @foreach($promos as $key=>$promo)
						                            	@if(App\DeliveryOrder::$Promo[$ProductPromo['id']]['code'] == $promo['code'])
						                                	<option value="{{ $key }}" selected="true">{{ $promo['code'] }} - {{ $promo['name'] }} ( {{ $promo['harga'] }} )</option>
						                                @else
						                                	<option value="{{ $key }}">{{ $promo['code'] }} - {{ $promo['name'] }} ( {{ $promo['harga'] }} )</option>
						                                @endif
						                            @endforeach
												@endif

				                                @if(true)
				                                	@if(!is_numeric($ProductPromo['id']))
					                                	@foreach($promos as $key=>$promo)
						                                	<option value="{{ $key }}">{{ $promo['code'] }} - {{ $promo['name'] }} ( {{ $promo['harga'] }} )</option>
							                            @endforeach

						                                <option value="other" selected>OTHER</option>
				                                	@endif
				                                @endif

		                    				</select>
		                    				<div class="validation"></div>
		                  				</div>
		                  				<div class="col-xs-2 col-sm-2" style="padding-right: 0;display: inline-block;">
		                    				<label for="">Qty</label>
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

			                    {{-- KHUSUS Philiphin --}}
			                    @if(true)
				                    @if(!is_numeric($ProductPromo['id']))
				                        <div class="form-group">
				                            <input type="text" class="form-control" name="product_other_{{ $j }}" placeholder="Product Name" data-msg="Please fill in the product" value="{{ $ProductPromo['id'] }}" />
				                            <div class="validation"></div>
				                        </div>
			                        @endif
			                    @endif

	              			@endforeach
	              			
	              			<div class="form-group">
				                <label for="">Branch</label>
				                <select class="form-control" id="branch" name="branch_id" data-msg="Mohon Pilih Cabang" required>
				                  	<option selected disabled value="">Choose Branch</option>

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
	                			<label for="">CSO Code</label>
	               			 	<input type="text" class="form-control" name="cso_id" id="cso" value="{{$deliveryOrders->cso['code']}}" required data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase" {{ Auth::user()->roles[0]['slug'] == 'cso' ? "readonly=\"\"" : "" }} />
                    			<div class="validation" id="validation_cso"></div>
	              			</div>

	              			<div id="errormessage"></div>

	              			<div class="form-group">
	              				<input type="hidden" id="idCSO" name="idCSO" value="">
	              				<input type="hidden" name="idDeliveryOrder" value="{{$deliveryOrders['id']}}">
	              				<button id="updateDeliveryOrder" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
	              				<button class="btn btn-light">Cancel</button>	
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
		$("#province").on("change", function(){
            var id = $(this).val();
            $( "#city" ).html("");
            $.get( '{{ route("fetchCity", ['province' => ""]) }}/'+id )
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
                    $( "#city" ).append(arrCity);
                }
            });
		});
		$("#city").on("change", function(){
            var id = $(this).val();
			$( "#subDistrict" ).html("");
            $.get( '{{ route("fetchDistrict", ['city' => ""]) }}/'+id )
            .done(function( result ) {
				result = result['rajaongkir']['results'];
				console.log(result);
                var arrSubDistsrict = "<option selected disabled value=\"\">Pilihan Kecamatan</option>";
                if(result.length > 0){
                    $.each( result, function( key, value ) {                            
                        arrSubDistsrict += "<option value=\""+value['subdistrict_id']+"\">"+value['subdistrict_name']+"</option>";
                    });
                    $( "#subDistrict" ).append(arrSubDistsrict);
                }
            });
        });
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
            $.get( '{{route("fetchCso")}}', { cso_code: txtCso })
            .done(function( result ) {
                console.log(result);
                if (result.result == 'true'){
                    $('#validation_cso').html('Kode CSO Benar');
                    $('#validation_cso').css('color', 'green');
                    $('#submit').removeAttr('disabled');
					document.getElementById('idCSO').value = result.data[0].id; 
					// $('#idCSO').val() = 
                }
                else{
                    $('#validation_cso').html('Kode CSO Salah');
                    $('#validation_cso').css('color', 'red');
                    $('#submit').attr('disabled',"");
                }
            });
        });

        {{-- KHUSUS Philiphin --}}
	    @if(true)
	        $(".pilihan-product").change( function(e){
	            if($(this).val() == 'other'){
	                $(this).parent().next().next().removeClass("d-none");
	                $(this).parent().next().next().children().attr('required', '');
	            }
	            else{
	                $(this).parent().next().next().addClass("d-none");
	                $(this).parent().next().next().children().removeAttr('required', '');
	            }
	        });
	    @endif
    });    
</script>
@endsection