<?php
    $menu_item_page = "user";
    $menu_item_second = "add_user";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
	.imagePreview {
      width: 100%;
      height: 150px;
      background-position: center center;
      background-color: #fff;
      background-size: cover;
      background-repeat: no-repeat;
      display: inline-block;
   }

  .del {
      position: absolute;
      top: 0px;
      right: 10px;
      width: 30px;
      height: 30px;
      text-align: center;
      line-height: 30px;
      background-color: rgba(255,255,255,0.6);
      cursor: pointer;
  }
</style>
@endsection

@section('content')
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
			<h3 class="page-title">Add Admin</h3>
				<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a data-toggle="collapse" href="#admin-dd" aria-expanded="false" aria-controls="admin-dd">Admin</a></li>
					<li class="breadcrumb-item active" aria-current="page">Add Admin</li>
				</ol>
			</nav>
		</div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<form id="actionAdd" class="forms-sample" action="{{route('store_useradmin')}}" method="POST">
							{{ csrf_field() }}
							<div class="form-group">
					            <span>ADMIN ROLE</span>
					            <select style="margin-top: 0.5em;" id="role" class="form-control" style="height: auto;" name="role" required>
					                @foreach ($roles as $role)
					                    <option value="{{$role->id}}">{{$role->name}}</option>
					                @endforeach
					            </select>
					            <span class="invalid-feedback">
					                <strong></strong>
					            </span>
					        </div>
							<div class="form-group">
								<label for="">USERNAME ADMIN</label>
								<input type="text" class="form-control" name="username" placeholder="Username Admin" required>
							</div>
							<div class="form-group">
								<label for="">ADMIN'S NAME</label>
								<input type="text" class="form-control" name="name" placeholder="Nama Admin" required>
							</div>
							<div class="form-group">
								<label for="">PASSWORD</label>
								<input type="password" name="password" class="form-control" required>
							</div>
							<div class="form-group">
								<label for="">RE-ENTER PASSWORD</label>
								<input type="password" class="form-control" name="password_confirmation" required>
							</div>
							<div class="form-group">
								<div class="col-xs-12">
									<label>PROFILE IMAGE (750x750 pixel)</label>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row productimg" style="border: 1px solid rgb(221, 221, 221, 0.5); border-radius: 4px; box-shadow: none; margin: 0;">
									<div class="col-xs-12 col-sm-6 imgUp" style="padding: 15px; float: left; text-align: center;">
										<div class="imagePreview" style="background-image: url({{asset('sources/dashboard/no-img-banner.jpg')}});"></div>
										<label class="file-upload-browse btn btn-gradient-primary" style="margin: 15px 0 0; text-align: center;">Upload
										<input name="user_image" type="file" accept=".jpg,.jpeg,.png" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
										</label>
										<i class="mdi mdi-window-close del"></i>
									</div>
								</div>
							</div>

							<button id="addUserAdmin" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
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
	        document.getElementById("addUserAdmin").innerHTML = "UPLOADING...";
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

	        document.getElementById("addUserAdmin").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("addUserAdmin").innerHTML = "SAVE";
	    }
    });
</script>
<script type="text/javascript">
	$(function() {
	    $(document).on("change",".uploadFile", function()
	    {
	        var uploadFile = $(this);
	        var files = !!this.files ? this.files : [];
	        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

	        if (/^image/.test( files[0].type)){ // only image file
	            var reader = new FileReader(); // instance of the FileReader
	            reader.readAsDataURL(files[0]); // read the local file

	            reader.onloadend = function(){ // set image data as background of div
	                //alert(uploadFile.closest(".upimage").find('.imagePreview').length);
	                uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url("+this.result+")");
	            }
	        }

	    });
	});
</script>
@endsection
