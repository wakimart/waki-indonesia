<?php
    $menu_item_page = "technician";
    $menu_item_second = "update_task";
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
      		<h3 class="page-title">Update Product Service</h3>
      		<nav aria-label="breadcrumb">
	        	<ol class="breadcrumb">
	          		<li class="breadcrumb-item"><a data-toggle="collapse" href="#technician-dd" aria-expanded="false" aria-controls="technician-dd">Technician</a></li>
	          		<li class="breadcrumb-item active" aria-current="page">Update Product Service</li>
	        	</ol>
      		</nav>
    	</div>
	    <div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">
	            		<form id="actionAdd" class="forms-sample" method="POST" action="{{ route('update_taskservice') }}">
							{{ csrf_field() }}

							@php
								$counterSparepart = -1;
							@endphp

							@foreach($product_services as $key => $product_service)
							<label for="">Product Service {{$key+1}}</label>
	              			<div class="form-group" id="container-productservice-{{$key}}">
			                    <div class="form-group">
			                        <select class="form-control pilihan-product" id="product_service-{{$key}}" data-msg="Mohon Pilih Product" required disabled>
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
		                            <input type="text" class="form-control" id="productservice_other_{{$key}}" placeholder="Product Name" data-msg="Please fill in the product"  value="{{$product_service['other_product']}}" disabled />
		                            <div class="validation"></div>
		                        </div>
			                    @endif

			                    @if($product_service['sparepart'] != null)
			                    @php
	                            	$arr_sparepart = json_decode($product_service['sparepart']);
	                            	$count_sparepart = count($arr_sparepart);
	                            @endphp

	                            <label for="">Sparepart</label>
			                    <div id="container-sparepart-{{$key}}">
			                    	@foreach($arr_sparepart as $index => $item)
			                    	@php $counterSparepart++; @endphp
			                    	<div id="detailSparepart-{{$key}}">
			                    		<div class="form-group" style="width: 72%; display: inline-block;">
					                        <select id="idSparepart-{{$index}}-{{$key}}" class="form-control pilihan-sparepart" data-msg="Mohon Pilih Product" required>
					                            <option selected disabled value="">Choose SPAREPART</option>
				                            	@foreach($spareparts as $sparepart)
				                            		@if($arr_sparepart[$index]->id == $sparepart['id'])
					                                	<option value="{{ $sparepart['id'] }}" selected>{{ $sparepart['name'] }}</option>
					                                @else
					                                	<option value="{{ $sparepart['id'] }}">{{ $sparepart['name'] }}</option>
					                                @endif
					                            @endforeach
					                            <option value="other">OTHER</option>
					                        </select>
					                        <div class="validation"></div>
					                    </div>
					                    <div class="form-group" style="width: 16%; display: inline-block;">
					                        <input id="idQtySparepart-{{$index}}-{{$key}}" type="number" class="form-control" placeholder="Qty" value="{{$arr_sparepart[$index]->qty}}">
					                        <div class="validation"></div>
					                    </div>

					                    @if($index > 0)
					                    	<div class='text-center' style='display: inline-block; float: right;'><button class='remove_sparepart btn btn-gradient-danger' type='button' title='Remove Sparepart' style='padding: 0.4em 0.7em;'><i class='mdi mdi-minus'></i></button></div>
					                    @else
					                    	<div class="text-center" style="display: inline-block; float: right;"><button class="add_sparepart btn btn-gradient-primary mr-2" type="button" title="Tambah Sparepart" style="padding: 0.4em 0.7em;"><i class="mdi mdi-plus"></i></button></div>	
					                    @endif
					                    
					                    <div class="form-group d-none" style="width: 72%; display: inline-block;">
				                            <input type="text" class="form-control" id="sparepart_other-{{$index}}-{{$key}}" placeholder="Sparepart Name" data-msg="Please fill in the product"/>
				                            <div class="validation"></div>
				                        </div>
				                        <div class="form-group d-none" style="width: 25%; display: inline-block;">
				                            <input type="number" class="form-control" id="price_other-{{$index}}-{{$key}}" placeholder="Price (Rp.)" data-msg="Please fill in the product"/>
				                            <div class="validation"></div>
				                        </div>
					                    
			                    	</div>
			                    	@endforeach
			                    </div>
			                    @else
			                    @php $counterSparepart++; @endphp
		                    	<div id="container-sparepart-{{$key}}">
			                    	<div id="detailSparepart-{{$key}}">
			                    		<div class="form-group" style="width: 72%; display: inline-block;">
					                        <select id="idSparepart-0-{{$key}}" class="form-control pilihan-sparepart" data-msg="Mohon Pilih Product" required>
					                            <option selected disabled value="">Choose SPAREPART</option>
					                            @foreach($spareparts as $sparepart)
					                                <option value="{{ $sparepart['id'] }}">{{ $sparepart['name'] }}</option>
					                            @endforeach
					                            <option value="other">OTHER</option>
					                        </select>
					                        <div class="validation"></div>
					                    </div>
					                    <div class="form-group" style="width: 16%; display: inline-block;">
					                        <input id="idQtySparepart-0-{{$key}}" type="number" class="form-control" placeholder="Qty">
					                        <div class="validation"></div>
					                    </div>
					                    <div class="text-center" style="display: inline-block; float: right;"><button class="add_sparepart btn btn-gradient-primary mr-2" type="button" title="Tambah Sparepart" style="padding: 0.4em 0.7em;"><i class="mdi mdi-plus"></i></button></div>

					                    <div class="form-group d-none" style="width: 72%; display: inline-block;">
				                            <input type="text" class="form-control" id="sparepart_other-0-{{$key}}" placeholder="Sparepart Name" data-msg="Please fill in the product"/>
				                            <div class="validation"></div>
				                        </div>
				                        <div class="form-group d-none" style="width: 25%; display: inline-block;">
				                            <input type="number" class="form-control" id="price_other-0-{{$key}}" placeholder="Price (Rp.)" data-msg="Please fill in the product"/>
				                            <div class="validation"></div>
				                        </div>

			                    	</div>
			                    </div>
			                    @endif
			                    

			                    @php
			                    	$issues = json_decode($product_service['issues']);
			                    	$count_main_issue = count($issues[0]->issues);

			                    	$due_date = explode(' ',$product_service['due_date']);
									$due_date = $due_date[0];

									$cbx_issues = ["Kerusakan Listrik", "Bersuara/Bergetar", "Heating", "Jatuh, Pecah, Unit Lepas", "Kerusakan Mekanik", "Lainnya..."];
			                    @endphp

			                    <div class="form-group">
				                	<label for="">Issues</label>
				                	<br>
				                	@for($i = 0; $i < count($cbx_issues); $i++)
			                			@if(in_array($cbx_issues[$i], $issues[0]->issues))
				                			<label style="margin-right: 20%;" class="checkbox-inline">
										      	<input id="cbx_issue-{{$i}}-{{$key}}" name="cbx_issue-0" style="margin-right: 10px;" type="checkbox" value="{{$cbx_issues[$i]}}" checked="true" disabled="">{{$cbx_issues[$i]}}
										    </label>
										@else
											<label style="margin-right: 20%;" class="checkbox-inline">
										      	<input id="cbx_issue-{{$i}}-{{$key}}" name="cbx_issue-0" style="margin-right: 10px;" type="checkbox" value="{{$cbx_issues[$i]}}" disabled="">{{$cbx_issues[$i]}}
										    </label>
			                			@endif
			                		@endfor

				                    <textarea class="form-control" id="issues-{{$key}}" rows="5" data-msg="Mohon Isi Issues" placeholder="Description" required="" disabled="">{{$issues[1]->desc}}</textarea>
				                    <div class="validation"></div>
				                </div>

			                    <div class="form-group">
									<label for="">Due Date</label>
									<input type="date" class="form-control" id="due_date-{{$key}}" placeholder="Tanggal Order" value="{{$due_date}}" required data-msg="Mohon Isi Tanggal" disabled="" />
									<div class="validation"></div>
									<span class="invalid-feedback">
										<strong></strong>
									</span>
								</div>
								<hr>			                    
			                </div>
			                <input type="hidden" id="id_productservice-{{$key}}" name="id_productservice" value="{{$product_service['id']}}">
			                <input type="hidden" id="id_service-{{$key}}" name="id_service" value="{{$product_service['service_id']}}">
							@endforeach

							<input type="hidden" id="lastIdSparepart" value="{{$counterSparepart}}">
			                
	              			<div id="errormessage"></div>

	              			@php $total_productservice = count($product_services); @endphp
	              			<div class="form-group">
	              				<input type="hidden" id="total_productservice" value="{{$total_productservice}}">
	              				@if($product_services[0]->service['status'] == "New")
	              					@can('change-status-process-service')
	              						<button id="updateService" type="submit" class="btn btn-gradient-primary mr-2">Process</button>
	              					@endcan
	              				@elseif($product_services[0]->service['status'] == "Process")
	              					<button id="updateService" type="submit" class="btn btn-light">Save</button>
	              					@can('change-status-repaired-service')
	              					<button id="updateServiceRepaired" type="submit" class="btn btn-gradient-primary mr-2 updateServiceRepaired">Repaired</button>
	              					@endcan
	              				@endif
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

		var idSparepart = $('#lastIdSparepart').val();
		var idQtySparepart = $('#lastIdSparepart').val();
		$(document).on("click", ".add_sparepart", function(){
			var getIdParent = $(this).parent().parent().attr('id');
			var id_parent = getIdParent.slice(-1);

			idSparepart++;
			idQtySparepart++;

			var added_sparepart = "<div id='detailSparepart-"+idSparepart+"'><div class='form-group' style='width: 72%; display: inline-block;'><select id='idSparepart-"+idSparepart+"-"+id_parent+"' class='form-control pilihan-sparepart' name='sparepart[]' data-msg='Mohon Pilih Product' required=''><option selected disabled value=''>Choose SPAREPART</option>@foreach($spareparts as $sparepart)<option value='{{ $sparepart['id'] }}'>{{ $sparepart['name'] }}</option>@endforeach<option value='other'>OTHER</option></select><div class='validation'></div></div><div class='form-group' style='width: 16%; display: inline-block;'><input id='idQtySparepart-"+idQtySparepart+"-"+id_parent+"' type='number' class='form-control' name='sparepart_qty' placeholder='Qty'><div class='validation'></div></div><div class='text-center' style='display: inline-block; float: right;'><button class='remove_sparepart btn btn-gradient-danger' type='button' title='Remove Sparepart' style='padding: 0.4em 0.7em;'><i class='mdi mdi-minus'></i></button></div><div class='form-group d-none' style='width: 72%; display: inline-block;'><input type='text' class='form-control' id='sparepart_other-"+idSparepart+"-"+id_parent+"' placeholder='Sparepart Name' data-msg='Please fill in the product'/><div class='validation'></div></div><div class='form-group d-none' style='width: 25%; display: inline-block;'><input type='number' class='form-control' id='price_other-"+idSparepart+"-"+id_parent+"' placeholder='Price (Rp.)' data-msg='Please fill in the product'/><div class='validation'></div></div></div>";

			$(this).parent().parent().parent().append(added_sparepart);
		});

		$(document).on("click", ".remove_sparepart", function(){
			$(this).parent().parent().remove();

			// idSparepart--;
			// idQtySparepart--;
		});

		@if(true)
            $(document).on("change", ".pilihan-sparepart", function(e){
                if($(this).val() == 'other'){
                	console.log($(this).parent().next().next().next());
                	//other sparepart name
                    $(this).parent().next().next().next().removeClass("d-none");
                    $(this).parent().next().next().next().children().attr('required', '');

                    //price
                    $(this).parent().next().next().next().next().removeClass("d-none");
                    $(this).parent().next().next().next().next().children().attr('required', '');
                }
                else{
                	//other sparepart name
                    $(this).parent().next().next().next().addClass("d-none");
                    $(this).parent().next().next().next().children().removeAttr('required', '');

                    //price
                    $(this).parent().next().next().next().next().addClass("d-none");
                    $(this).parent().next().next().next().next().children().removeAttr('required', '');
                }
            });
        @endif



		var frmAdd;
		var repaired = false;

		$(document).on("click", ".updateServiceRepaired", function(){
			repaired = true;
		});

	    $("#actionAdd").on("submit", function (e) {
	        e.preventDefault();
	        frmAdd = _("actionAdd");
	        frmAdd = new FormData(document.getElementById("actionAdd"));
	        frmAdd.enctype = "multipart/form-data";       

	        var arr_productservice = [];
	        for (var i = 0; i < idService; i++) {
	        	var id_product_service = $("#id_productservice-" + i).val();
	        	var id_service = $("#id_service-" + i).val();

	        	var arr_sparepart = [];
	        	for (var s = 0; s < idSparepart + 2; s++) {
	        		var sparepart = $("#idSparepart-"+s+"-"+i).val();
	        		var qty = $("#idQtySparepart-"+s+"-"+i).val();

	        		if(sparepart != null && qty != null){
	        			if(sparepart == 'other'){
	        				var other_sparepart = $("#sparepart_other-"+s+"-"+i).val();
	        				var price = $("#price_other-"+s+"-"+i).val();
	        				arr_sparepart.push([sparepart, qty, other_sparepart, price]);
	        			}else{
	        				arr_sparepart.push([sparepart, qty]);
	        			}
	        		}
	        	}
	        	arr_productservice.push([id_product_service, arr_sparepart, id_service]);
	        }

	        var arr_jsonproductservice = JSON.stringify(arr_productservice);

	        console.log(arr_productservice);

	        frmAdd.append('productservices', arr_jsonproductservice);
	        frmAdd.append('repairedservices', repaired);

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
	        document.getElementById("updateService").innerHTML = "UPLOADING...";
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

	        document.getElementById("updateService").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("updateService").innerHTML = "SAVE";
	    }
	});
	

</script>
@endsection
