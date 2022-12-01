<?php
    $menu_item_page = "petty_cash_type";
?>
@extends('admin.layouts.template')

@section('content')
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
			<h3 class="page-title">Edit Petty Cash Type</h3>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
				  	<li class="breadcrumb-item"><a data-toggle="collapse" href="#petty_cash_type-dd" aria-expanded="false" aria-controls="petty_cash_type-dd">Petty Cash Type</a></li>
				  	<li class="breadcrumb-item active" aria-current="page">Edit Petty Cash Type</li>
				</ol>
			</nav>
		</div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
				  	<div class="card-body">
					    <form id="actionAdd" class="forms-sample" method="POST" action="{{route('update_petty_cash_type', ['id' => $pettyCashTypes['id']])}}" autocomplete="off">
					    	{{ csrf_field() }}
					      	<div class="form-group">
					        	<label for="">Code</label>
					        	<input type="text" class="form-control" name="code" placeholder="Petty Cash Account Code" required
                                    value="{{ $pettyCashTypes->code }}">
					        	<span class="invalid-feedback">
			                        <strong></strong>
			                    </span>
					      	</div>
					      	<div class="form-group">
					        	<label for="">Name</label>
					        	<input type="text" class="form-control" name="name" placeholder="Petty Cash Account Name" required
                                    value="{{ $pettyCashTypes->name }}">
					        	<span class="invalid-feedback">
			                        <strong></strong>
			                    </span>
					      	</div>
                            <div class="form-group">
					        	<label for="">Max Nominal (Optional)</label>
					        	<input type="text" class="form-control" name="max" data-type="currency" placeholder="Max Nominal"
                                    value="{{ $pettyCashTypes->max ? number_format($pettyCashTypes->max) : '' }}">
					        	<span class="invalid-feedback">
			                        <strong></strong>
			                    </span>
					      	</div>

					      	<button id="addBranch" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
					      	<button class="btn btn-light">Cancel</button>
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

            // Change numberWithComma before submit
            $('#actionAdd input[data-type="currency"]').each(function() {
                var frmName = $(this).attr('name');
                frmAdd.set(frmName, numberNoCommas(frmAdd.get(frmName)));
            });

	        var ajax = new XMLHttpRequest();
	        ajax.upload.addEventListener("progress", progressHandler, false);
	        ajax.addEventListener("load", completeHandler, false);
	        ajax.addEventListener("error", errorHandler, false);
	        ajax.open("POST", URLNya);
	        ajax.setRequestHeader("X-CSRF-TOKEN",$('meta[name="csrf-token"]').attr('content'));
	        ajax.send(frmAdd);
	    });
	    function progressHandler(event){
	        document.getElementById("addBranch").innerHTML = "UPLOADING...";
	    }
	    function completeHandler(event){
	        var hasil = JSON.parse(event.target.responseText);

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

	        document.getElementById("addBranch").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("addBranch").innerHTML = "SAVE";
	    }
    });
</script>
<script>
$(document).ready(function() {
    $(document).on("input", 'input[data-type="currency"]', function() {
        $(this).val(numberWithCommas($(this).val()));
    });
});

function numberWithCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}

function numberNoCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\D/g, "");
    return parts.join(".");
}
</script>
@endsection
