<?php
    $menu_item_page = "acceptance";
    $menu_item_second = "add_acceptance_form";
?>
@extends('admin.layouts.template')

@section('style')
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

	/* Hide all steps by default: */
	.tab {
	  display: none;
	}

	/* Make circles that indicate the steps of the form: */
	.step {
	  height: 15px;
	  width: 15px;
	  margin: 0 2px;
	  background-color: #bbbbbb;
	  border: none;
	  border-radius: 50%;
	  display: inline-block;
	  opacity: 0.5;
	}

	/* Mark the active step: */
	.step.active {
	  opacity: 1;
	}

	/* Mark the steps that are finished and valid: */
	.step.finish {
	  background-color: #4CAF50;
	}
</style>
@endsection

@section('content')
<div class="main-panel">
  	<div class="content-wrapper">
    	<div class="page-header">
      		<h3 class="page-title">Add Acceptance</h3>
      		<nav aria-label="breadcrumb">
	        	<ol class="breadcrumb">
	          		<li class="breadcrumb-item"><a data-toggle="collapse" href="#" aria-expanded="false" aria-controls="deliveryorder-dd">Acceptance</a></li>
	          		<li class="breadcrumb-item active" aria-current="page">Add Acceptance</li>
	        	</ol>
      		</nav>
    	</div>
	    <div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">
	            		<form id="actionAdd" class="forms-sample" method="POST" action="{{ route('store_acceptance_form') }}">
	            			{{ csrf_field() }}
	            			<div class="form-group">
				                <label for="">Type Acceptance</label>
				                <select class="form-control" required>
		                            <option value="Upgrade">Upgrade</option>
	                			</select>
	                			<div class="validation"></div>
	              			</div>
	              			<div class="form-group">
	              				<label for="">Upgrade Date</label>
	              				<input type="date" class="form-control" name="upgrade_date" id="upgrade_date" placeholder="Upgrade Date" value="<?php echo date('Y-m-j'); ?>" required data-msg="Mohon Isi Tanggal" />
	              				<div class="validation"></div>
	              			</div>

	              			<div class="form-group">
								<label for=""><h2>Data Pelanggan</h2></label><br/>
	                			<label for="">No. MPC (optional)</label>
	                			<input type="text" class="form-control" id="no_member" name="no_member" placeholder="No. Member">
	                			<div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">Name</label>
				                <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
				                <div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">Phone Number</label>
				                <input type="number" class="form-control" id="phone" name="phone" placeholder="Phone Number" required>
				                <div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">Province</label>
								<select class="form-control" id="province" name="province_id" data-msg="Mohon Pilih Provinsi" required>
									<option selected disabled value="">Pilihan Provinsi</option>

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
									<option selected disabled value="">Pilihan Kota</option>
								</select>
								<div class="validation"></div>
							</div>
							<div class="form-group">
				                <label for="">Sub District</label>
								<select class="form-control" id="subDistrict" name="distric" data-msg="Mohon Pilih Kecamatan" required>
									<option selected disabled value="">Pilihan Kecamatan</option>
								</select>
								<div class="validation"></div>
	              			</div> 
	              			<div class="form-group">
				                <label for="exampleTextarea1">Address</label>
				                <textarea class="form-control" id="address" name="address" rows="4" placeholder="Address Lengkap"></textarea>
				                <div class="validation"></div>
	              			</div>

	              			<div class="form-group">
								<label for=""><h2>Product Upgrade</h2></label><br/>
	                			<label for="">New Product</label>
	                			<select class="form-control" id="newproduct_id" name="newproduct_id" data-msg="Mohon Pilih Product Baru" required>
									<option selected disabled value="">Pilihan Product</option>
									@foreach($products as $product)
		                                <option value="{{ $product['id'] }}">{{ $product['code'] }} - {{ $product['name'] }}</option>
		                            @endforeach
								</select>
	                			<div class="validation"></div>
	              			</div>
	              			<div class="form-group">
	                			<label for="">Old Product</label>
	                			<select class="form-control" id="oldproduct_id" name="oldproduct_id" data-msg="Mohon Pilih Produk Lama" required>
									<option selected disabled value="">Pilihan Product</option>
									@foreach($products as $product)
		                                <option value="{{ $product['id'] }}">{{ $product['code'] }} - {{ $product['name'] }}</option>
		                            @endforeach
								</select>
	                			<div class="validation"></div>
	              			</div>
	              			<div class="form-group">
	              				<label for="">Purchase Date Old Product</label>
	              				<input type="date" class="form-control" name="purchase_date" id="purchase_date" placeholder="Purchase Date" value="<?php echo date('Y-m-j'); ?>" required data-msg="Mohon Isi Tanggal" />
	              				<div class="validation"></div>
	              			</div>
		                    <div class="form-group">
	              				<label for="">Request Price</label>
		                        <input type="number" class="form-control" name="request_price" id="request_price" placeholder="Request Price" required data-msg="Mohon Isi Harga Acc" style="text-transform:uppercase"/>
		                        <div class="validation"></div>
		                    </div>		                    
		                    <div class="form-group">
	                    		<input class="form-control" type="checkbox" value="" id="invalidCheck2" required>
		                    </div>

	              			<div class="form-group">
								<label for=""><h2>Data Sales </h2></label><br/>
				                <label for="">Branch</label>
				                <select class="form-control" id="branch" name="branch_id" data-msg="Mohon Pilih Cabang" required>
				                  	<option selected disabled value="">Choose Branch</option>

			                        @foreach($branches as $branch)
			                            <option value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
			                        @endforeach
	                			</select>
	                			<div class="validation"></div>
	              			</div>
	              			<div class="form-group">
	                			<label for="">CSO Code</label>
	               			 	<input type="text" class="form-control" name="cso_id" id="cso" placeholder="CSO Code" required data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase" {{ Auth::user()->roles[0]['slug'] == 'cso' ? "value=".Auth::user()->cso['code'] : "" }}  {{ Auth::user()->roles[0]['slug'] == 'cso' ? "readonly=\"\"" : "" }} />
                    			<div class="validation" id="validation_cso"></div>
	              			</div>

	              			<div id="errormessage"></div>
	              			<div class="form-group">
	              				<button id="addDeliveryOrder" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
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
	//function load city
	$(document).on("change", ".changeProvince", function(){
		var get_index = $(this).attr('id');
		var index = get_index.slice(-1);

		var id = $(this).val();

		$("#city-" + index).html("");
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
                $("#city-" + index).append(arrCity);
            }
        });
		//console.log(id);
	})
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
            check_cso($("#cso").val());
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
		
        function check_cso(code) {
        	$.get( '{{route("fetchCso")}}', { cso_code: code })
            .done(function( result ) {
                if (result['result'] == "true" && result['data'].length > 0) {
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
        }
    });
</script>

@endsection
