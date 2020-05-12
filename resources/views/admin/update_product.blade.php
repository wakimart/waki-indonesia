<?php
    $menu_item_page = "product";
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
			<h3 class="page-title">Edit Product</h3>
			<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a data-toggle="collapse" href="#produk-dd" aria-expanded="false" aria-controls="produk-dd">Product</a></li>
				<li class="breadcrumb-item active" aria-current="page">Edit Product</li>
			</ol>
			</nav>
		</div>

		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<form id="actionUpdate" class="forms-sample" method="POST" action="{{route('update_product')}}">
							<div class="form-group">
								<label for="">Code</label>
								<input type="text" class="form-control" name="code" id="exampleInputName1" value="{{$products['code']}}" readonly>
							</div>
							<div class="form-group">
								<label for="">Name</label>
								<input type="text" class="form-control" name="name" id="exampleInputName1" value="{{$products['name']}}" required>
							</div>
							<div class="form-group">
								<label for="">Category</label>
								<select class="form-control" id="exampleSelectGender" name="category_id" required>
									<option selected disabled value="">Choose Category</option>
									@foreach($categories as $category)
										@if($products['category_id'] == $category['id'])
											<option value="{{$category['id']}}" selected="true">{{$category['name']}}</option>
										@else
											<option value="{{$category['id']}}">{{$category['name']}}</option>
										@endif
									@endforeach
								</select>
							</div>
							<div class="form-group d-none">
								<label for="">Berat Produk (KG)</label>
								<input type="text" class="form-control" id="exampleInputName1" placeholder="Berat (Kg)">
							</div>
							<div class="form-group d-none">
								<label for="">Jumlah Produk</label>
								<input type="text" class="form-control" id="exampleInputName1" placeholder="Jumlah">
							</div>
							<div class="form-group">
								<label for="">Price (Rp.)</label>
								<input type="number" class="form-control" id="exampleInputName1" value="{{$products['price']}}" name="price" required>
							</div>


							<div class="form-group">
								@php
					                $img = json_decode($products['image']);
					                $defaultImg = asset('sources/product_images/').'/'.strtolower($products['code']);
					            @endphp
								<div class="col-xs-12">
									<label>Product Image (720x720 pixel)</label>
								</div>
								@for($i=0;$i<3;$i++)
								<div class="col-xs-12 col-sm-6 col-md-4 form-group imgUp" style="padding: 15px; float: left;">
	                          		<label>Image {{$i+1}}</label>
	                          		@if(!empty($img[$i]))
                					<div class="imagePreview" style="background-image: url({{$defaultImg.'/'. $img[$i]}});"></div>
                					@else
	                          		<div class="imagePreview" style="background-image: url({{asset('sources/dashboard/no-img-banner.jpg')}});"></div>
	                          		@endif
	                          		<label class="file-upload-browse btn btn-gradient-primary" style="margin-top: 15px;">Upload
	                              		<input name="arr_image[]" data-name="arr_image" id="gambars-{{$i}}" type="file" accept=".jpg,.jpeg,.png" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
	                          		</label>
	                          		<i class="mdi mdi-window-close del"></i>
	                      		</div>
								@endfor
							</div>

							<div class="form-group">
								<label for="">URL Video</label>
								<input type="text" class="form-control" id="exampleInputName1" value="{{$products['video']}}" name="video" required>
							</div>

							<div class="form-group">
								<label for="">Quick Description</label>
								<textarea id="edit_description" name="quick_desc" class="form-control form-control-sm"  rows="4" value="{{$products['quick_desc']}}" required>{{$products['quick_desc']}}</textarea>
							</div>

							<div class="form-group">
								<label for="">Description</label>
								<textarea id="edit_description" name="description" class="form-control form-control-sm"  rows="4" value="{{$products['description']}}" required>{{$products['description']}}</textarea>
							</div>

							<input type="hidden" name="idProduct" value="{{$products['id']}}">
							<button id="updateProduct" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
							<button class="btn btn-light">Cancel</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- partial -->
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

	        for (var i = 0; i < 3; i++)
	        {
	            frmUpdate.append('images'+i, $("#gambars-"+i)[0].files[0]);
	            if($("#gambars-"+i)[0].files[0]!=null)
	            {
	                for(var j=0; j<deleted_img.length;j++)
	                {
	                    console.log()
	                    if(deleted_img[j]==i)
	                    {
	                        deleted_img.splice(j,1);
	                    }
	                }
	            }
	            console.log("test" + i);
	        }

	        frmUpdate.append('total_images', 3);
	        frmUpdate.append('dlt_img', deleted_img);

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
	        document.getElementById("updateProduct").innerHTML = "UPLOADING...";
	    }
	    function completeHandler(event){
	        var hasil = JSON.parse(event.target.responseText);
	        console.log(hasil);

	        for (var key of frmUpdate.keys()) {
	            $("#actionUpdate").find("input[name="+key.name+"]").removeClass("is-invalid");
	            $("#actionUpdate").find("select[name="+key.name+"]").removeClass("is-invalid");
	            $("#actionUpdate").find("textarea[name="+key.name+"]").removeClass("is-invalid");

	            $("#actionUpdate").find("input[name="+key.name+"]").next().find("strong").text("");
	            $("#actionUpdate").find("select[name="+key.name+"]").next().find("strong").text("");
	            $("#actionUpdate").find("textarea[name="+key.name+"]").next().find("strong").text("");
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
	            alert("Input Error !!!");
	        }
	        else{
	            alert("Input Success !!!");
	            window.location.reload()
	        }

	        document.getElementById("updateProduct").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("updateProduct").innerHTML = "SAVE";
	    }
    });
</script>
<script type="text/javascript">
	var deleted_img=[];

	$(document).on("click", "i.del" , function() {
	    $(this).closest(".imgUp").find('.imagePreview').css("background-image", "");
	    $(this).closest(".imgUp").find('input[type=text]').removeAttr("required");
	    $(this).closest(".imgUp").find('.btn').find('.img').val("");
	    $(this).closest(".imgUp").find('.form-control').val("");
	    deleted_img.push($(this).closest(".imgUp").find(".img").attr('id').substring(8));
	    console.log($(this).closest(".imgUp").find('.btn').find('.img').val());
	    console.log(deleted_img);
	});

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