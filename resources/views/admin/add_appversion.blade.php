<?php
    $menu_item_page = "app";
    $menu_item_second = "add_appver";
?>
@extends('admin.layouts.template')
@section('content')
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
  			<h3 class="page-title">Add App Version</h3>
  				<nav aria-label="breadcrumb">
    				<ol class="breadcrumb">
      					<li class="breadcrumb-item"><a data-toggle="collapse" href="#app-dd" aria-expanded="false" aria-controls="app-dd">CSO</a></li>
      					<li class="breadcrumb-item active" aria-current="page">Add App version</li>
    				</ol>
  				</nav>
		</div>
		<div class="row">
  			<div class="col-12 grid-margin stretch-card">
    			<div class="card">
      				<div class="card-body">
        					<form id="actionAdd" class="forms-sample" method="POST" action="{{route('store_appVersion')}}">
                    {{ csrf_field() }}
          					<div class="form-group">
					            <label for="">Version</label>
					            <input type="text" class="form-control" name="version" placeholder="App version" required>
                      <span class="invalid-feedback">
                          <strong></strong>
                      </span>
          					</div>
          					<div class="form-group">
					            <label for="">Description Detail</label>
					            <input type="text" class="form-control" name="detail" placeholder="Description Detail" required>
                      <span class="invalid-feedback">
                          <strong></strong>
                      </span>
          					</div>
          					<div class="form-group">
					            <label for="">App URLs</label>
					            <input type="text" class="form-control" name="url" placeholder="App URLs here">
          					</div>


				          	<button id="addAppVer" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
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
	        document.getElementById("addAppVer").innerHTML = "UPLOADING...";
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

	        document.getElementById("addAppVer").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("addAppVer").innerHTML = "SAVE";
	    }
    });
</script>
@endsection
