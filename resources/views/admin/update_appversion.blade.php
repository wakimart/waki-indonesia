<?php
    $menu_item_page = "app";
?>
@extends('admin.layouts.template')
@section('content')
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
  			<h3 class="page-title">Edit App Version</h3>
  				<nav aria-label="breadcrumb">
    				<ol class="breadcrumb">
      					<li class="breadcrumb-item"><a data-toggle="collapse" href="#app-dd" aria-expanded="false" aria-controls="app-dd">CSO</a></li>
      					<li class="breadcrumb-item active" aria-current="page">Edit App version</li>
    				</ol>
  				</nav>
		</div>
		<div class="row">
  			<div class="col-12 grid-margin stretch-card">
    			<div class="card">
      				<div class="card-body">
        					<form id="actionUpdate" class="forms-sample" method="POST" action="{{ route('update_app', ['id' => $version['id']])}}">
                    {{ csrf_field() }}
          					<div class="form-group">
					            <label for="">Version</label>
					            <input type="text" class="form-control" name="version" value="{{$version['version']}}" placeholder="App version" required>
                      <span class="invalid-feedback">
                          <strong></strong>
                      </span>
          					</div>
          					<div class="form-group">
					            <label for="">Description Detail</label>
					            <input type="text" class="form-control" name="detail" placeholder="Description Detail" value="{{$version['detail']}}" required>
                      <span class="invalid-feedback">
                          <strong></strong>
                      </span>
          					</div>
          					<div class="form-group">
					            <label for="">App URLs</label>
					            <input type="text" class="form-control" name="url" placeholder="App URLs here" value="{{$version['url']}}">
          					</div>


				          	<button id="updateAppVer" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
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
	        document.getElementById("updateAppVer").innerHTML = "UPLOADING...";
	    }
	    function completeHandler(event){
            console.log(event);
          var hasil = JSON.parse(event.target.responseText);                    //(event.target.responseText);
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
              alert(hasil['errors']);
              //alert("Input Error!!!");
          }
          else{
              alert("Input Success !!!");
              window.location.reload()
          }

	        document.getElementById("updateAppVer").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("updateAppVer").innerHTML = "SAVE";
	    }
    });
</script>
@endsection
