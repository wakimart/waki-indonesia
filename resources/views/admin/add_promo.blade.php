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

  	#intro {
        padding-top: 2em;
    }
    
    .validation{
        color: red;
        font-size: 9pt;
    }
    button{
        background: #1bb1dc;
        border: 0;
        border-radius: 3px;
        padding: 8px 30px;
        color: #fff;
        transition: 0.3s;
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
			<h3 class="page-title">Add Promo</h3>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a data-toggle="collapse" href="#promo-dd" aria-expanded="false" aria-controls="promo-dd">Promo</a></li>
					<li class="breadcrumb-item active" aria-current="page">Tambah Promo</li>
				</ol>
			</nav>
		</div>
		<div class="row">

			<div class="col-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<form id="actionAdd" class="forms-sample" method="POST" action="{{route('store_promo')}}">
							<div class="form-group">
								<label for="">Kode</label>
								<input type="text" class="form-control" id="exampleInputName1" placeholder="Kode" name="code" required="">
							</div>
							<div class="form-group">
								<div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
	                  				<div class="form-group" style="width: 72%; display: inline-block;">
					                    <label for="">Product</label>
					                    <select class="form-control" name="product_0" data-msg="Mohon Pilih Product" required="">
			                            <option selected disabled value="">Pilihan Product</option>

			                            @foreach($products as $key=>$product)
			                                <option value="{{ $product['id'] }}">{{ $product['code'] }} - {{ $product['name'] }}</option>
			                            @endforeach
			                        </select>
	                    				<div class="validation"></div>
	                  				</div>
	                  				<div class="form-group" style="width: 16%; display: inline-block;">
	                    				<label for="">Jumlah</label>
		                					<select class="form-control" name="qty_0" data-msg="Mohon Pilih Jumlah" required="">
				                            <option selected value="1">1</option>

				                            @for($i=2; $i<=10;$i++)
				                                <option value="{{ $i }}">{{ $i }}</option>
				                            @endfor
				                        </select>
				                        <div class="validation"></div>
	                  				</div>

	                  				<div class="text-center" style="display: inline-block; float: right;"><button id="tambah_product" title="Tambah Product" style="padding: 0.4em 0.7em;"><i class="fas fa-plus"></i></button></div>

	                  				<div id="tambahan_product"></div>
	                			</div>								
							</div>

							<div class="form-group d-none">
								<label for="">Berat Promo (KG)</label>
								<input type="text" class="form-control" id="exampleInputName1" placeholder="Berat (Kg)">
							</div>
							<div class="form-group d-none">
								<label for="">Jumlah Promo</label>
								<input type="text" class="form-control" id="exampleInputName1" placeholder="Jumlah">
							</div>
							<div class="form-group">
								<label for="">Harga (Rp.)</label>
								<input type="number" class="form-control" id="exampleInputName1" placeholder="Harga (Rp)" name="price" required="">
							</div>


							<div class="form-group">
								<div class="col-xs-12">
									<label>Gambar Promo (720x720 pixel)</label><span style="float: right;">min. 1 picture</span>
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

							<div class="form-group d-none">
								<label for="">Coming Soon ?</label>
								<select class="form-control" id="exampleSelectGender">
									<option>Yes</option>
									<option>No</option>
								</select>
							</div>

							<button id="addPromo" type="submit" class="btn btn-gradient-primary mr-2">Simpan</button>
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
	        document.getElementById("addPromo").innerHTML = "UPLOADING...";
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

	        document.getElementById("addPromo").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("addPromo").innerHTML = "SAVE";
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
<script type="text/javascript">
	$(document).ready(function(){
		var total_product = 0;

		$("#tambah_product").click(function(e){
	        e.preventDefault();
	        total_product++;
	        strIsi = "<div id=\"product_"+total_product+"\" class=\"form-group\" style=\"width: 72%; display: inline-block;\"><select class=\"form-control\" name=\"product_"+total_product+"\" data-msg=\"Mohon Pilih Product\" required=\"\"><option selected disabled value=\"\">Pilihan Product</option> @foreach($products as $key=>$product) <option value=\"{{ $product['id'] }}\">{{ $product['code'] }} - {{ $product['name'] }}</option> @endforeach </select><div class=\"validation\"></div></div><div id=\"qty_"+total_product+"\" class=\"form-group\" style=\"width: 16%; display: inline-block;\"><select class=\"form-control\" name=\"qty_"+total_product+"\" data-msg=\"Mohon Pilih Jumlah\" required=\"\"><option selected value=\"1\">1</option> @for($i=2; $i<=10;$i++) <option value=\"{{ $i }}\">{{ $i }}</option> @endfor </select><div class=\"validation\"></div></div><div class=\"text-center\" style=\"display: inline-block; float: right;\"><button class=\"hapus_product\" value=\""+total_product+"\" title=\"Tambah Product\" style=\"padding: 0.4em 0.7em; background-color: red;\"><i class=\"fas fa-minus\"></i></button></div>";
	        $('#tambahan_product').html($('#tambahan_product').html()+strIsi);
	    });
	    $(document).on("click",".hapus_product", function(e){
	        e.preventDefault();
	        total_product--;
	        $('#product_'+$(this).val()).remove();
	        $('#qty_'+$(this).val()).remove();
	        $(this).remove();
	    });
	});
</script>
@endsection