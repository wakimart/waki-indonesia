<?php
    $menu_item_page = "service";
    $menu_item_second = "update_service";
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

	.check label{
		width: 25em;
	}

	@media (min-width: 768px){
		#desktop{
			display: block;
		}

		#mobile{
			display: none;
		}
	}

	@media (max-width: 768px){
		#desktop{
			display: none;
		}

		#mobile{
			display: block;
		}
	}


</style>
@endsection

@section('content')
<div class="main-panel">
  	<div class="content-wrapper">
		  <!-- header mobile -->
		<div id="mobile">
			<h3 class="text-center">Update Service</h3>
			<div class="row">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a data-toggle="collapse" href="#service-dd" aria-expanded="false" aria-controls="service-dd">Service</a></li>
						<li class="breadcrumb-item active" aria-current="page">Update Service</li>
					</ol>
				</nav>
		  	</div>
	  	</div>

		<!-- header desktop -->
		<div id="desktop">
			<div class="page-header">
				<h3 class="page-title">Update Service</h3>
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a data-toggle="collapse" href="#service-dd" aria-expanded="false" aria-controls="service-dd">Service</a></li>
						<li class="breadcrumb-item active" aria-current="page">Update Service</li>
					</ol>
				</nav>
			</div>
		</div>
	    <div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">
	            		<form id="actionAdd" class="forms-sample" method="POST" action="{{ route('update_service') }}">
							{{ csrf_field() }}
							
							@php
								$service_date = explode(' ',$services['service_date']);
								$service_date = $service_date[0];
							@endphp

							<div class="form-group">
								<label for="">Service Date</label>
								<input type="date" class="form-control" name="service_date" id="service_date" placeholder="Tanggal Order" value="{{$service_date}}" required data-msg="Mohon Isi Tanggal" />
								<div class="validation"></div>
								<span class="invalid-feedback">
									<strong></strong>
								</span>
							</div>
	              			<div class="form-group">
	                			<label for="">No. MPC (optional)</label>
	                			<input type="number" class="form-control" id="no_mpc" name="no_mpc" placeholder="No. MPC" value="{{$services['no_mpc']}}">
	                			<div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">Name</label>
				                <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{$services['name']}}" required>
				                <div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="exampleTextarea1">Address</label>
				                <textarea class="form-control" id="address" name="address" rows="4" placeholder="Address" required>{{$services['address']}}</textarea>
				                <div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">Phone Number</label>
				                <input type="number" class="form-control" id="phone" name="phone" placeholder="Phone Number" value="{{$services['phone']}}" required>
				                <div class="validation"></div>
	              			</div>

	              			<label for="">PRODUCT SERVICE</label>
	              			<div class="text-center" style="display: inline-block;background: #4caf3ab3;float: right;margin-bottom: 20px;">
	              				<button class="btn btn-gradient-primary mr-2" id="tambah_productservice" type="button">Add Product Service</button>
	              			</div>
	              			</br>

	              			@foreach($product_services as $key => $product_service)
		              			<div class="form-group" id="container-productservice-{{$key}}">
		              				<input type="hidden" id="productservice_active-{{$key}}" value="{{$product_service['active']}}">
		              				<label for="">Product Service {{$key + 1}}</label>
		              				@if($key > 0)
			              			<div class="text-center" style="display: inline-block;float: right;margin-bottom: 20px;">
										<button class="btn btn-gradient-danger remove_productservice" type="button">Remove</button>
									</div>
			              			@endif
				                    <div class="form-group">
				                        <select class="form-control pilihan-productservice" id="product_service-{{$key}}" data-msg="Mohon Pilih Product" required>
				                            <option selected disabled value="">Choose PRODUCT SERVICE</option>
				                            @foreach($products as $product)
				                            	@if($product_service['product_id'] == $product['id'])
				                                	<option value="{{ $product['id'] }}" selected>{{ $product['code'] }} - {{ $product['name'] }}</option>
				                                @elseif($product_service['product_id'] == null)
				                                	<option value="other" selected>OTHER</option>
				                                @else
				                                	<option value="{{ $product['id'] }}">{{ $product['code'] }} - {{ $product['name'] }}</option>
				                                @endif
				                            @endforeach
				                        </select>
				                        <div class="validation"></div>
				                    </div>

				                    @if($product_service['product_id'] == null)
				                   	<div class="form-group">
			                            <input type="text" class="form-control" id="productservice_other_{{$key}}" placeholder="Product Name" data-msg="Please fill in the product" value="{{$product_service['other_product']}}"/>
			                            <div class="validation"></div>
			                        </div>
			                        @endif

				                    <div id="container-sparepart-0" class="d-none">
				                    	<div id="detailSparepart-0">
				                    		<div class="form-group" style="width: 72%; display: inline-block;">
						                        <select id="idSparepart-0-0" class="form-control pilihan-product" data-msg="Mohon Pilih Product">
						                            <option selected disabled value="">Choose SPAREPART</option>

						                            @foreach($spareparts as $sparepart)
						                                <option value="{{ $sparepart['id'] }}">{{ $sparepart['name'] }}</option>
						                            @endforeach
						                        </select>
						                        <div class="validation"></div>
						                    </div>
						                    <div class="form-group" style="width: 16%; display: inline-block;">
						                        <input id="idQtySparepart-0-0" type="number" class="form-control" placeholder="Qty">
						                        <div class="validation"></div>
						                    </div>
						                    <div class="text-center" style="display: inline-block; float: right;"><button class="add_sparepart btn btn-gradient-primary mr-2" id="tambah_sparepart" type="button" title="Tambah Sparepart" style="padding: 0.4em 0.7em;"><i class="mdi mdi-plus"></i></button></div>	
				                    	</div>
				                    </div>

				                    @php
				                    	$issues = json_decode($product_service['issues']);
				                    	$count_main_issue = count($issues[0]->issues);

				                    	$due_date = explode(' ',$product_service['due_date']);
										$due_date = $due_date[0];

										$cbx_issues = ["Kerusakan Listrik", "Bersuara/Bergetar", "Heating", "Jatuh, Pecah, Unit Lepas", "Kerusakan Mekanik", "Lainnya..."];
				                    @endphp

				                    <div class="form-group check">
					                	<label for="">Issues</label>
					                	<br>
					                	@for($i = 0; $i < count($cbx_issues); $i++)
				                			@if(in_array($cbx_issues[$i], $issues[0]->issues))
					                			<label class="checkbox-inline">
											      	<input id="cbx_issue-{{$i}}-{{$key}}" name="cbx_issue-{{$key}}" style="margin-right: 10px;" type="checkbox" value="{{$cbx_issues[$i]}}" checked="true">{{$cbx_issues[$i]}}
											    </label>
											@else
												<label class="checkbox-inline">
											      	<input id="cbx_issue-{{$i}}-{{$key}}" name="cbx_issue-{{$key}}" style="margin-right: 10px;" type="checkbox" value="{{$cbx_issues[$i]}}">{{$cbx_issues[$i]}}
											    </label>
				                			@endif
				                		@endfor

					                    <textarea class="form-control" id="issues-{{$key}}" rows="5" data-msg="Mohon Isi Issues" placeholder="Description" required="">{{$issues[1]->desc}}</textarea>
					                    <div class="validation"></div>
					                </div>

				                    <div class="form-group">
										<label for="">Due Date</label>
										<input type="date" class="form-control" id="due_date-{{$key}}" placeholder="Tanggal Order" required data-msg="Mohon Isi Tanggal" value="{{$due_date}}"/>
										<div class="validation"></div>
										<span class="invalid-feedback">
											<strong></strong>
										</span>
									</div>
									<br>

				                    
				                    {{-- ++++++++++++++ ======== ++++++++++++++ --}}			                    
				                </div>
				                <input type="hidden" id="id_productservice-{{$key}}" name="id_productservice" value="{{$product_service['id']}}">
				                <input type="hidden" id="id_service-{{$key}}" name="id_service" value="{{$product_service['service_id']}}">
			                @endforeach

			                <div id="tambahan_productservice"></div>

	              			<div id="errormessage"></div>

							<input type="hidden" id="technician_schedule_id" name="technician_schedule_id" value="{{ $services['technician_schedule_id'] }}">
	              			@php $total_productservice = count($product_services); @endphp
	              			<input type="hidden" id="total_productservice" value="{{$total_productservice}}">
	              			
	              			<div class="form-group">
	              				<button id="addService" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
	              				<button class="btn btn-light">Cancel</button>
	              			</div>
	            		</form>

	          		</div>
	        	</div>
	      	</div>
	    </div>
	</div>
</div>
<!-- modal success -->
<div class="modal fade" role="dialog" tabindex="-1" id="modal-Success">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Input Success</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="txt-success">Appointment telah berhasil dibuat.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-gradient-primary" type="button" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- modal error -->
<div class="modal fade" role="dialog" tabindex="-1" id="modal-Error">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Input Failed</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="txt-success">"Appointment dengan nomer ini sudah ada!!"</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-gradient-danger" type="button" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		var idService = $('#total_productservice').val();
		var detailProductService = $('#total_productservice').val() - 1;
		var detailSparepart = 0;
		var counter_service = $('#total_productservice').val();

		$('#tambah_productservice').click(function(e){
			idService++;
			detailProductService++;
			counter_service++;
			detailSparepart++; 

			if(counter_service <= 3){
				e.preventDefault();
				$('#tambahan_productservice').append('\
					<div id="container-productservice-'+detailProductService+'">\
						<input type="hidden" id="productservice_active-'+detailProductService+'" value="1">\
						<div class="text-center" style="display: inline-block;float: right;margin-bottom: 20px;">\
							<button class="btn btn-gradient-danger remove_productservice" type="button">Remove</button>\
						</div>\
						<label for="">Product Service '+counter_service+'</label>\
						<div class="form-group">\
							<select class="form-control pilihan-productservice" id="product_service-'+detailProductService+'" data-msg="Mohon Pilih Product" required>\
								<option selected disabled value="">Choose PRODUCT SERVICE</option>\
									@if(true)<option value="other">OTHER</option>@endif\
									@foreach($products as $product)<option value="{{ $product['id'] }}">{{ $product['code'] }} - {{ $product['name'] }}</option>@endforeach\
								</option>\
								@if(true)<option value="OTHER">OTHER</option>@endif\
							</select>\
							<div class="validation"></div>\
						</div>\
						<div class="form-group d-none">\
			                <input type="text" class="form-control" id="productservice_other_'+detailProductService+'" placeholder="Product Name" data-msg="Please fill in the product" />\
			            	<div class="validation"></div>\
			            </div>\
						<div id="container-sparepart-'+detailSparepart+'" class="d-none">\
							<div id="detailSparepart-'+detailSparepart+'">\
								<div class="form-group" style="width: 72%; display: inline-block;">\
									<select id="idSparepart-0-'+detailSparepart+'" class="form-control pilihan-product" name="sparepart[]" data-msg="Mohon Pilih Sparepart">\
										<option selected disabled value="">Choose SPAREPART</option>\
											@foreach($spareparts as $sparepart)<option value="{{ $sparepart['id'] }}">{{ $sparepart['name'] }}</option>@endforeach\
										</option>\
									</select>\
									<div class="validation"></div>\
								</div>\
								<div class="form-group" style="width: 16%; display: inline-block;">\
							        <input id="idQtySparepart-0-'+detailSparepart+'" type="number" class="form-control" name="sparepart_qty" placeholder="Qty">\
							        <div class="validation"></div>\
							    </div>\
							    <div class="text-center" style="display: inline-block; float: right;">\
							    	<button class="add_sparepart btn btn-gradient-primary mr-2" id="tambah_sparepart" type="button" title="Tambah Sparepart" style="padding: 0.4em 0.7em;"><i class="mdi mdi-plus"></i></button>\
							    </div>\
							</div>\
						</div>\
						<div class="form-group check">\
							<label for="">Issues</label>\
							<br>\
							<label class="checkbox-inline">\
						      	<input id="cbx_issue-0-'+detailProductService+'" name="cbx_issue-'+detailProductService+'" style="margin-right: 10px;" type="checkbox" value="Kerusakan Listrik">Kerusakan Listrik\
						    </label>\
						    <label class="checkbox-inline">\
						      	<input id="cbx_issue-1-'+detailProductService+'" name="cbx_issue-'+detailProductService+'" style="margin-right: 10px;" type="checkbox" value="Bersuara/Bergetar">Bersuara/Bergetar\
						    </label>\
						    <label class="checkbox-inline">\
						      	<input id="cbx_issue-2-'+detailProductService+'" name="cbx_issue-'+detailProductService+'" style="margin-right: 10px;" type="checkbox" value="Heating">Heating\
						    </label>\
						    <br>\
						    <label class="checkbox-inline">\
						      	<input id="cbx_issue-3-'+detailProductService+'" name="cbx_issue-'+detailProductService+'" style="margin-right: 10px;" type="checkbox" value="Jatuh, Pecah, Unit Lepas">Jatuh, Pecah, Unit Lepas\
						    </label>\
						    <label class="checkbox-inline">\
						      	<input id="cbx_issue-4-'+detailProductService+'" name="cbx_issue-'+detailProductService+'" style="margin-right: 10px;" type="checkbox" value="Kerusakan Mekanik">Kerusakan Mekanik\
						    </label>\
						    <label class="checkbox-inline">\
						      	<input id="cbx_issue-5-'+detailProductService+'" name="cbx_issue-'+detailProductService+'" style="margin-right: 10px;" type="checkbox" value="Lainnya...">Lainnya...\
						    </label>\
						    \
							<textarea class="form-control" id="issues-'+detailProductService+'" rows="5" data-msg="Mohon Isi Issues" placeholder="Issues" required></textarea>\
							<div class="validation"></div>\
						</div>\
						<div class="form-group">\
							<label for="">Due Date</label>\
							<input type="date" class="form-control" id="due_date-'+detailProductService+'" placeholder="Tanggal Order" value="<?php echo date('Y-m-j'); ?>" required data-msg="Mohon Isi Tanggal" />\
							<div class="validation"></div>\
							<span class="invalid-feedback">\
							<strong></strong>\
							</span>\
						</div><br>\
					</div>\
				');
			}else{
				alert("Service maksimal 3 produk!!!");
			}
		});


		$(document).on("click", ".remove_productservice", function(){
			var get_temp_index = $(this).parent().parent().children().attr("id");
			var get_index = get_temp_index.slice(-1);

			$("#productservice_active-"+get_index).val("0");
			$(this).parent().parent().children().addClass("d-none");

			// detailProductService--;
			// detailSparepart--;
			// counter_service--;
			// idService--;
		});

		var idSparepart = 0;
		var idQtySparepart = 0;
		$(document).on("click", ".add_sparepart", function(){
			var getIdParent = $(this).parent().parent().attr('id');
			var id_parent = getIdParent.slice(-1);

			idSparepart++;
			idQtySparepart++;

			var added_sparepart = "<div id='detailSparepart-"+idSparepart+"'><div class='form-group' style='width: 72%; display: inline-block;'><select id='idSparepart-"+idSparepart+"-"+id_parent+"' class='form-control pilihan-product' name='sparepart[]' data-msg='Mohon Pilih Product' required=''><option selected disabled value=''>Choose SPAREPART</option>@foreach($spareparts as $sparepart)<option value='{{ $sparepart['id'] }}'>{{ $sparepart['name'] }}</option>@endforeach</select><div class='validation'></div></div><div class='form-group' style='width: 16%; display: inline-block;'><input id='idQtySparepart-"+idQtySparepart+"-"+id_parent+"' type='number' class='form-control' name='sparepart_qty' placeholder='Qty'><div class='validation'></div></div><div class='text-center' style='display: inline-block; float: right;'><button class='remove_sparepart btn btn-gradient-danger' type='button' title='Remove Sparepart' style='padding: 0.4em 0.7em;'><i class='mdi mdi-minus'></i></button></div></div>";

			$(this).parent().parent().parent().append(added_sparepart);
		});

		$(document).on("click", ".remove_sparepart", function(){
			$(this).parent().parent().remove();

			idSparepart--;
			idQtySparepart--;
		});

		@if(true)
            $(document).on("change", ".pilihan-productservice", function(e){
                if($(this).val() == 'other'){
                    $(this).parent().next().removeClass("d-none");
                    $(this).parent().next().children().attr('required', '');
                }
                else{
                    $(this).parent().next().addClass("d-none");
                    $(this).parent().next().children().removeAttr('required', '');
                }
            });
        @endif


		var frmAdd;
        var arr_productService = [];
        var arr_id_productservice = [];

	    $("#actionAdd").on("submit", function (e) {
	        e.preventDefault();
	        frmAdd = _("actionAdd");
	        frmAdd = new FormData(document.getElementById("actionAdd"));
	        frmAdd.enctype = "multipart/form-data";       

	        for (var i = 0; i < idService; i++) {
	        	var id_product_service = $("#id_productservice-" + i).val();
	        	var id_service = $("#id_service-" + i).val();

	        	var product = $("#product_service-" + i).val();

	        	var other = "";
	        	if(product == "other"){
	        		other = $("#productservice_other_" + i).val();
	        	}
	        	var issues = $("#issues-" + i).val();
	        	var due_date = $("#due_date-" + i).val();
	        	var active = $("#productservice_active-"+i).val();

	        	// var arr_sparepart = [];
	        	// for (var s = 0; s < idSparepart + 1; s++) {
	        	// 	var sparepart = $("#idSparepart-"+s+"-"+i).val();
	        	// 	var qty = $("#idQtySparepart-"+s+"-"+i).val();

	        	// 	if(sparepart != null && qty != null){
	        	// 		arr_sparepart.push([sparepart, qty]);
	        	// 	}
	        	// }

	        	var arr_issues = [];
	        	$('input[name="cbx_issue-'+i+'"]:checked').each(function() {
				   arr_issues.push(this.value);
				});

	        	//arr_productService.push([product, arr_sparepart, arr_issues, issues, due_date]);
	        	arr_productService.push([id_service, id_product_service, product, arr_issues, issues, due_date, other, active]);
	        	arr_id_productservice.push(id_product_service);
	        }

	        var arr_jsonproductservice = JSON.stringify(arr_productService);
	        var arr_jsonidproductservice = JSON.stringify(arr_id_productservice);

	        console.log(arr_jsonproductservice);

	        frmAdd.append('productservices', arr_jsonproductservice);
	        frmAdd.append('idproductservices', arr_jsonidproductservice);

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
	        document.getElementById("addService").innerHTML = "UPLOADING...";
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
	            alert(hasil['errors']);
	        }
	        else{ 
	            alert("Input Success !!!");
	            window.location.reload()
	        }

	        document.getElementById("addService").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("addService").innerHTML = "SAVE";
	    }
	});
	

</script>
@endsection
