<?php
    $menu_item_page = "service";
    $menu_item_second = "add_service";
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
</style>
@endsection

@section('content')
<div class="main-panel">
  	<div class="content-wrapper">
    	<div class="page-header">
      		<h3 class="page-title">Add Service</h3>
      		<nav aria-label="breadcrumb">
	        	<ol class="breadcrumb">
	          		<li class="breadcrumb-item"><a data-toggle="collapse" href="#service-dd" aria-expanded="false" aria-controls="service-dd">Service</a></li>
	          		<li class="breadcrumb-item active" aria-current="page">Add Service</li>
	        	</ol>
      		</nav>
    	</div>
	    <div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">
	            		<form id="actionAdd" class="forms-sample" method="POST" action="{{ route('store_service') }}">
							{{ csrf_field() }}
							<div class="form-group">
								<label for="">Service Date</label>
								<input type="date" class="form-control" name="service_date" id="service_date" placeholder="Tanggal Order" value="<?php echo date('Y-m-j'); ?>" required data-msg="Mohon Isi Tanggal" />
								<div class="validation"></div>
								<span class="invalid-feedback">
									<strong></strong>
								</span>
							</div>
	              			<div class="form-group">
	                			<label for="">No. MPC (optional)</label>
	                			<input type="number" class="form-control" id="no_mpc" name="no_mpc" placeholder="No. MPC">
	                			<div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">Name</label>
				                <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
				                <div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="exampleTextarea1">Address</label>
				                <textarea class="form-control" id="address" name="address" rows="4" placeholder="Address" required></textarea>
				                <div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">Phone Number</label>
				                <input type="number" class="form-control" id="phone" name="phone" placeholder="Phone Number" required>
				                <div class="validation"></div>
	              			</div>

	              			<label for="">PRODUCT SERVICE</label>
	              			<div class="text-center" style="display: inline-block;background: #4caf3ab3;float: right;margin-bottom: 20px;">
	              				<button class="btn btn-gradient-primary mr-2" id="tambah_productservice" type="button">Add Product Service</button>
	              			</div>
	              			</br>

	              			<label for="">Product Service 1</label>
	              			<div class="form-group" id="container-productservice-0">
			                    <div class="form-group">
			                        <select class="form-control pilihan-productservice" id="product_service-0" data-msg="Mohon Pilih Product" required>
			                            <option selected disabled value="">Choose PRODUCT SERVICE</option>
			                            @if(true)
			                                <option value="other">OTHER</option>
			                            @endif
			                            @foreach($products as $product)
			                                <option value="{{ $product['id'] }}">{{ $product['code'] }} - {{ $product['name'] }}</option>
			                            @endforeach
			                        </select>
			                        <div class="validation"></div>
			                    </div>

			                   	<div class="form-group d-none">
		                            <input type="text" class="form-control" id="productservice_other_0" placeholder="Product Name" data-msg="Please fill in the product" />
		                            <div class="validation"></div>
		                        </div>

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

			                    <div class="form-group">
				                	<label for="">Issues</label>
				                	<br>
				                	<label style="margin-right: 20%;" class="checkbox-inline">
								      	<input id="cbx_issue-0-0" name="cbx_issue-0" style="margin-right: 10px;" type="checkbox" value="Kerusakan Listrik">Kerusakan Listrik
								    </label>
								    <label style="margin-right: 20%;" class="checkbox-inline">
								      	<input id="cbx_issue-1-0" name="cbx_issue-0" style="margin-right: 10px;" type="checkbox" value="Bersuara/Bergetar">Bersuara/Bergetar
								    </label>
								    <label style="margin-right: 20%;" class="checkbox-inline">
								      	<input id="cbx_issue-2-0" name="cbx_issue-0" style="margin-right: 10px;" type="checkbox" value="Heating">Heating
								    </label>
								    <br>
								    <label style="margin-right: 20%;" class="checkbox-inline">
								      	<input id="cbx_issue-3-0" name="cbx_issue-0" style="margin-right: 10px;" type="checkbox" value="Jatuh, Pecah, Unit Lepas">Jatuh, Pecah, Unit Lepas
								    </label>
								    <label style="margin-right: 20%;" class="checkbox-inline">
								      	<input id="cbx_issue-4-0" name="cbx_issue-0" style="margin-right: 10px;" type="checkbox" value="Kerusakan Mekanik">Kerusakan Mekanik
								    </label>
								    <label style="margin-right: 20%;" class="checkbox-inline">
								      	<input id="cbx_issue-5-0" name="cbx_issue-0" style="margin-right: 10px;" type="checkbox" value="Lainnya...">Lainnya...
								    </label>

				                    <textarea class="form-control" id="issues-0" rows="5" data-msg="Mohon Isi Issues" placeholder="Description" required=""></textarea>
				                    <div class="validation"></div>
				                </div>

			                    <div class="form-group">
									<label for="">Due Date</label>
									<input type="date" class="form-control" id="due_date-0" placeholder="Tanggal Order" value="<?php echo date('Y-m-j'); ?>" required data-msg="Mohon Isi Tanggal" />
									<div class="validation"></div>
									<span class="invalid-feedback">
										<strong></strong>
									</span>
								</div>
								<br>

			                    <div id="tambahan_productservice"></div>
			                    {{-- ++++++++++++++ ======== ++++++++++++++ --}}			                    
			                </div>

	              			<div id="errormessage"></div>

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
		var idService = 0;
		var detailProductService = 0;
		var detailSparepart = 0;
		var counter_service = 1;

		$('#tambah_productservice').click(function(e){
			idService++;
			detailProductService++;
			counter_service++;
			detailSparepart++; 

			if(counter_service <= 3){
				e.preventDefault();
				$('#tambahan_productservice').append('\
					<div id="container-productservice-'+detailProductService+'">\
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
						<div class="form-group">\
							<label for="">Issues</label>\
							<br>\
							<label style="margin-right: 20%;" class="checkbox-inline">\
						      	<input id="cbx_issue-0-'+detailProductService+'" name="cbx_issue-'+detailProductService+'" style="margin-right: 10px;" type="checkbox" value="Kerusakan Listrik">Kerusakan Listrik\
						    </label>\
						    <label style="margin-right: 20%;" class="checkbox-inline">\
						      	<input id="cbx_issue-1-'+detailProductService+'" name="cbx_issue-'+detailProductService+'" style="margin-right: 10px;" type="checkbox" value="Bersuara/Bergetar">Bersuara/Bergetar\
						    </label>\
						    <label style="margin-right: 20%;" class="checkbox-inline">\
						      	<input id="cbx_issue-2-'+detailProductService+'" name="cbx_issue-'+detailProductService+'" style="margin-right: 10px;" type="checkbox" value="Heating">Heating\
						    </label>\
						    <br>\
						    <label style="margin-right: 20%;" class="checkbox-inline">\
						      	<input id="cbx_issue-3-'+detailProductService+'" name="cbx_issue-'+detailProductService+'" style="margin-right: 10px;" type="checkbox" value="Jatuh, Pecah, Unit Lepas">Jatuh, Pecah, Unit Lepas\
						    </label>\
						    <label style="margin-right: 20%;" class="checkbox-inline">\
						      	<input id="cbx_issue-4-'+detailProductService+'" name="cbx_issue-'+detailProductService+'" style="margin-right: 10px;" type="checkbox" value="Kerusakan Mekanik">Kerusakan Mekanik\
						    </label>\
						    <label style="margin-right: 20%;" class="checkbox-inline">\
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
			$(this).parent().parent().remove();

			detailProductService--;
			detailSparepart--;
			counter_service--;
			idService--;
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

	    $("#actionAdd").on("submit", function (e) {
	        e.preventDefault();
	        frmAdd = _("actionAdd");
	        frmAdd = new FormData(document.getElementById("actionAdd"));
	        frmAdd.enctype = "multipart/form-data";       

	        for (var i = 0; i < idService + 1 ; i++) {
	        	var product = $("#product_service-" + i).val();

	        	var other = "";
	        	if(product == "other"){
	        		other = $("#productservice_other_" + i).val();
	        	}
	        	var issues = $("#issues-" + i).val();
	        	var due_date = $("#due_date-" + i).val();

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
	        	arr_productService.push([product, arr_issues, issues, due_date, other]);
	        }

	        var arr_jsonproductservice = JSON.stringify(arr_productService);

	        console.log(arr_productService);

	        frmAdd.append('productservices', arr_jsonproductservice);

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
	            var route_to_list = "{{route('list_service')}}";
	           	window.location.href = route_to_list;
	        }

	        document.getElementById("addService").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("addService").innerHTML = "SAVE";
	    }
	});
	

</script>
@endsection
