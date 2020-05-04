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
			<h3 class="page-title">Add Product</h3>
			<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a data-toggle="collapse" href="#produk-dd" aria-expanded="false" aria-controls="produk-dd">Product</a></li>
				<li class="breadcrumb-item active" aria-current="page">Add Product</li>
			</ol>
			</nav>
		</div>

		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<form id="actionAdd" class="forms-sample" method="POST" action="{{route('store_product')}}">
							<div class="form-group">
								<label for="">Kode Produk</label>
								<input type="text" class="form-control" name="code" id="exampleInputName1" placeholder="Kode" required>
							</div>
							<div class="form-group">
								<label for="">Nama Produk</label>
								<input type="text" class="form-control" name="name" id="exampleInputName1" placeholder="Nama" required>
							</div>
							<div class="form-group">
								<label for="">Kategori Produk</label>
								<select class="form-control" id="exampleSelectGender" name="category_id" required>
									<option selected disabled value="">Pilihan Kategori</option>
									@foreach($categories as $category)
										<option value="{{$category['id']}}">{{$category['name']}}</option>
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
								<label for="">Harga Produk (Rp.)</label>
								<input type="number" class="form-control" id="exampleInputName1" placeholder="Harga (Rp)" name="price" required>
							</div>


							<div class="form-group">
								<div class="col-xs-12">
									<label>Gambar Produk (720x720 pixel)</label><span style="float: right;">min. 1 picture</span>
								</div>
								@for($i=0;$i<3;$i++)
								<div class="col-xs-12 col-sm-6 col-md-4 form-group imgUp" style="padding: 15px; float: left;">
	                          		<label>Image {{$i+1}}</label>
	                          		<div class="imagePreview" style="background-image: url({{asset('sources/dashboard/no-img-banner.jpg')}});"></div>
	                          		<label class="file-upload-browse btn btn-gradient-primary" style="margin-top: 15px;">Upload
	                              		<input name="images{{$i}}" id="productimg-{{$i}}" type="file" accept=".jpg,.jpeg,.png" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
	                          		</label>
	                          		<i class="mdi mdi-window-close del"></i>
	                      		</div>
								@endfor
							</div>

							<div class="form-group">
								<label for="">URL Video</label>
								<input type="text" class="form-control" id="exampleInputName1" placeholder="URL" name="video" required>
							</div>

							<div class="form-group">
								<label for="">Quick Description</label>
								<textarea id="edit_description" name="quick_desc" class="form-control form-control-sm"  rows="4" placeholder="Deskripsi Produk" required></textarea>
							</div>

							<div class="form-group">
								<label for="">Deskripsi Produk</label>
								<textarea id="edit_description" name="description" class="form-control form-control-sm"  rows="4" placeholder="Deskripsi Produk" required></textarea>
							</div>

							<button id="addProduct" type="submit" class="btn btn-gradient-primary mr-2">Simpan</button>
							<button class="btn btn-light">Batal</button>
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
	        document.getElementById("addProduct").innerHTML = "UPLOADING...";
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

	        document.getElementById("addProduct").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("addProduct").innerHTML = "SAVE";
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