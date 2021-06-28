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
	/*input {
	  padding: 10px;
	  width: 100%;
	  font-size: 17px;
	  font-family: Raleway;
	  border: 1px solid #aaaaaa;
	}*/

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

   .div-CheckboxGroup {
	  border:solid 1px rgba(128, 128, 128, 0.32941);
	  padding:0px 10px ;
	  border-radius:3px;
	}

	input[type='checkbox'], input[type='radio']{
		margin-left: 0px !important;
	}

	@media (max-width: 768px){
		#desktop{
			display: none;
		}

		#mobile{
			display: block;
		}
	}

	@media (min-width: 768px){
		#desktop{
			display: block;
		}

		#mobile{
			display: none;
		}
	}

</style>
@endsection

@section('content')
<div class="main-panel">
  	<div class="content-wrapper">
		<!-- header mobile -->
    	<div id="mobile">
			<h3 class="text-center">Add Acceptance</h3>
			<div class="row">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a data-toggle="collapse" href="#" aria-expanded="false" aria-controls="deliveryorder-dd">Acceptance</a></li>
						<li class="breadcrumb-item active" aria-current="page">Add Acceptance</li>
					</ol>
				</nav>
		  	</div>
	  	</div>

		<!-- header desktop -->
		<div id="desktop">
			<div class="page-header">
				<h3 class="page-title">Add Acceptance</h3>
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						  <li class="breadcrumb-item"><a data-toggle="collapse" href="#" aria-expanded="false" aria-controls="deliveryorder-dd">Acceptance</a></li>
						  <li class="breadcrumb-item active" aria-current="page">Add Acceptance</li>
					</ol>
				</nav>
		  	</div>
		</div>
	    <div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">
	            		<form id="actionAdd" class="forms-sample" method="POST" enctype="multipart/form-data" action="{{ route('store_acceptance_form') }}">
	            			{{ csrf_field() }}
	            			<div class="form-group">
				                <label for="">Type Acceptance</label>
				                <select class="form-control" required>
		                            <option value="Upgrade" selected="">Upgrade</option>
	                			</select>
	                			<div class="validation"></div>
	              			</div>
	              			<div class="form-group">
	              				<label for="">Upgrade Date (Bulan/Tanggal/Tahun)</label>
	              				<input type="date" class="form-control" name="upgrade_date" id="upgrade_date" placeholder="Upgrade Date" value="<?php echo date('Y-m-j'); ?>" required data-msg="Mohon Isi Tanggal"/>
	              				<div class="validation"></div>
	              			</div>

	              			<div class="form-group">
								<label for=""><h2>Data Pelanggan</h2></label><br/>
	                			<label for="">No. MPC (optional)</label>
	                			<input type="text" class="form-control" id="no_mpc" name="no_mpc" placeholder="No. Member">
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
								<select class="form-control" id="province" name="province" data-msg="Mohon Pilih Provinsi" required>
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
								<select class="form-control" id="subDistrict" name="district" data-msg="Mohon Pilih Kecamatan" required>
									<option selected disabled value="">Pilihan Kecamatan</option>
								</select>
								<div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="exampleTextarea1">Address</label>
				                <textarea class="form-control" id="address" name="address" rows="4" placeholder="Address Lengkap" required></textarea>
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
                                <label for="product-add-ons">
                                    Product Add-ons
                                </label>
                                <select name="product_addons_id"
                                    id="product-add-ons"
                                    class="form-control">
                                    <option value="" selected>Choose Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product['id'] }}">
                                            {{ $product['code'] }} - {{ $product['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
	              			<div class="form-group">
	                			<label for="">Old Product</label>
	                			<select class="form-control" id="oldproduct_id" name="oldproduct_id" data-msg="Mohon Pilih Produk Lama" required>
									<option selected disabled value="">Pilihan Product</option>
									@foreach($products as $product)
		                                <option value="{{ $product['id'] }}">{{ $product['code'] }} - {{ $product['name'] }}</option>
		                            @endforeach
	                                <option value="">Other</option>
								</select>
	                			<div class="validation"></div>
	              			</div>
		                    <div class="form-group" id="other_product_group" style="display: none;">
	              				<label for="">Old Product Name</label>
		                        <input type="text" class="form-control" name="other_product" id="other_product" placeholder="Old Product Name" data-msg="Mohon Isi Nama Produk" style="text-transform:uppercase"/>
		                        <div class="validation"></div>
		                    </div>
	              			<div class="form-group">
	              				<label for="">Purchase Date Old Product (Bulan/Tanggal/Tahun)</label>
	              				<input type="date" class="form-control" name="purchase_date" id="purchase_date" placeholder="Purchase Date" value="<?php echo date('Y-m-j'); ?>" required data-msg="Mohon Isi Tanggal" />
	              				<div class="validation"></div>
	              			</div>
		                    <div class="form-group">
	              				<label for="">Request Price</label>
		                        <input type="number" class="form-control" name="request_price" id="request_price" placeholder="Request Price" required data-msg="Mohon Isi Harga Acc" style="text-transform:uppercase"/>
		                        <div class="validation"></div>
		                    </div>
		                    <div class="form-group">
	              				<label for="">Old Product Photo (Max. 5 Photo)</label>
		                        <input multiple type="file" class="form-control" name="image[]" id="image" accept="image/*" placeholder="Old Product Photo" required data-msg="Mohon Sertakan Foto" style="text-transform:uppercase"/>
		                        <div class="validation"></div>
		                    </div>

		                    <div class="form-group" >
	              				<label for="">Kelengkapan</label>
		                        <div class="div-CheckboxGroup" required>
		                        	<div class="form-check form-check">
		                                <input class="form-check-input" type="checkbox" id="kelengkapan-mesin" name="kelengkapan[]" value="mesin">
		                                <label class="form-check-label" for="kelengkapan-mesin">Machine</label>
		                            </div>
		                            <div class="form-check form-check">
		                                <input class="form-check-input" type="checkbox" id="kelengkapan-filter" name="kelengkapan[]" value="filter">
		                                <label class="form-check-label" for="kelengkapan-filter">Filter</label>
		                            </div>
		                            <div class="form-check form-check">
		                                <input class="form-check-input" type="checkbox" id="kelengkapan-aksesoris" name="kelengkapan[]" value="aksesoris">
		                                <label class="form-check-label" for="kelengkapan-aksesoris">Accessories</label>
		                            </div>
		                            <div class="form-check form-check">
		                                <input class="form-check-input" type="checkbox" id="kelengkapan-kabel" name="kelengkapan[]" value="kabel">
		                                <label class="form-check-label" for="kelengkapan-kabel">cabel</label>
		                            </div>
		                            <div class="form-check form-check">
		                                <input class="form-check-input" type="checkbox" id="kelengkapan-other" name="kelengkapan[]" value="other">
		                                <label class="form-check-label" for="kelengkapan-other">Other</label>
		                            </div>
				                    <div class="form-group" id="other_kelengkapan_group" style="display: none;">
				                        <input type="text" class="form-control" name="other_kelengkapan" id="other_kelengkapan" placeholder="Other Accessories"/>
				                    </div>
		                    	</div>
		                    </div>
		                    <div class="form-group" >
	              				<label for="">Machine Condition</label>
		                        <div class="div-CheckboxGroup" required>
		                        	<div class="form-check form-check">
		                                <input class="form-check-input" type="radio" id="kondisi-normal" name="kondisi" checked="" value="normal">
		                                <label class="form-check-label" for="kondisi-normal">Normal</label>
		                            </div>
		                            <div class="form-check form-check">
		                                <input class="form-check-input" type="radio" id="kondisi-need_repair" name="kondisi" value="need_repair">
		                                <label class="form-check-label" for="kondisi-need_repair">Need Repair</label>
		                            </div>
		                    	</div>
		                    </div>
		                    <div class="form-group" >
	              				<label for="">Body Condition</label>
		                        <div class="div-CheckboxGroup" required>
		                        	<div class="form-check">
		                                <input class="form-check-input" type="radio" id="tampilan-new" name="tampilan" checked="" value="new">
		                                <label class="form-check-label" for="tampilan-new">New</label>
		                            </div>
		                            <div class="form-check">
		                                <input class="form-check-input" type="radio" id="tampilan-medium" name="tampilan" value="medium">
		                                <label class="form-check-label" for="tampilan-medium">Moderate</label>
		                            </div>
		                            <div class="form-check">
		                                <input class="form-check-input" type="radio" id="tampilan-need_repair" name="tampilan" value="need_repair">
		                                <label class="form-check-label" for="tampilan-need_repair">Need Repair</label>
		                            </div>
		                    	</div>
		                    </div>
			                <div class="form-group">
			                	<label for="">Description</label>
			                    <textarea class="form-control" name="description" rows="5" data-msg="Mohon Isi Description" placeholder="Description"></textarea>
			                    <div class="validation"></div>
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
	              				<button id="addAcceptance" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
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
        };

        document.getElementById("image").onchange = function() {
        	if(this.files[0].size > 15000000){
        		alert("File foto terlalu besar !\nMaksimal 15MB");
        		this.value = "";
        	};
        };

        $("#oldproduct_id").on("change", function(){
        	$('#other_product').val("");
            if($(this).val() == ''){
            	$('#other_product_group').attr('style', 'display: inherit;');
            	$('#other_product_group').attr('required', true);
            }
            else{
            	$('#other_product_group').attr('style', 'display: none;');
            	$('#other_product_group').removeAttr('required');
            }
		});
        $("#kelengkapan-other").on("change", function(){
            if($(this)[0].checked){
            	$('#other_kelengkapan_group').attr('style', 'display: inherit;');
            }
            else{
            	$('#other_kelengkapan_group').attr('style', 'display: none;');
            }
		});

        $("#actionAdd").on("submit", function (e) {
        	e.preventDefault();
        	if($('.div-CheckboxGroup :checkbox:checked').length < 1){
        		alert("Kelengkapan minimal tercentang 1 !");
        		return false;
        	}
        	var $fileUpload = $("input[type='file']");
        	if (parseInt($fileUpload.get(0).files.length)>5){
        		alert("You can only upload a maximum of 5 files");
        		return false;
        	}

        	frmAdd = _("actionAdd");
        	frmAdd = new FormData(document.getElementById("actionAdd"));
        	frmAdd.enctype = "multipart/form-data";
        	var URLNya = $("#actionAdd").attr('action');

        	var ajax = new XMLHttpRequest();
        	ajax.upload.addEventListener("progress", progressHandler, false);
        	ajax.addEventListener("load", completeHandler, false);
        	ajax.addEventListener("error", errorHandler, false);
        	ajax.open("POST", URLNya);
        	ajax.setRequestHeader("X-CSRF-TOKEN",$('meta[name="csrf-token"]').attr('content'));
        	ajax.send(frmAdd);
        });
        function progressHandler(event){
        	document.getElementById("addAcceptance").innerHTML = "UPLOADING...";
        }
        function completeHandler(event){
        	var hasil = JSON.parse(event.target.responseText);
        	console.log(hasil);

        	for (var key of frmAdd.keys()) {
        		$("#actionAdd").find("input[name="+key.name+"]").removeClass("is-invalid");
        		$("#actionAdd").find("select[name="+key.name+"]").removeClass("is-invalid");
        		$("#actionAdd").find("textarea[name="+key.name+"]").removeClass("is-invalid");

        		$("#actionAdd").find("input[name="+key.name+"]").next().find("strong").text("");
        		$("#actionAdd").find("select[name="+key.name+"]").next().find("strong").text("");
        		$("#actionAdd").find("textarea[name="+key.name+"]").next().find("strong").text("");
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
                alert("Terdapat kesalahan pada inputan ! \n"+hasil['errors']['errorInfo'][2]);
        	}
        	else{
                alert("Data berhasil disimpan !");
	            window.location.href = "{{ route('add_acceptance_form') }}";
	        }
	        document.getElementById("addAcceptance").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	    	document.getElementById("addAcceptance").innerHTML = "SAVE";
	    }
    });
</script>

@endsection
