<?php
$menu_item_page = "promo";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
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

    .validation {
        color: red;
        font-size: 9pt;
    }

    button {
        background: #1bb1dc;
        border: 0;
        border-radius: 3px;
        padding: 8px 30px;
        color: #fff;
        transition: 0.3s;
    }

    input, select, textarea {
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
			<h3 class="page-title">Edit Promo</h3>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#promo-dd"
                            aria-expanded="false"
                            aria-controls="promo-dd">
                            Promo
                        </a>
                    </li>
					<li class="breadcrumb-item active" aria-current="page">
                        Edit Promo
                    </li>
				</ol>
			</nav>
		</div>
		<div class="row">

			<div class="col-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">
						<form id="actionUpdate"
                            class="forms-sample"
                            method="POST"
                            action="{{ route('update_promo') }}">
							<div class="form-group">
								<label for="">Code</label>
								<input type="text"
                                    class="form-control"
                                    id="code"
                                    value="{{ $promos['code'] }}"
                                    name="code"
                                    readonly />
							</div>

							<div id="container">
								@php
		                            $ProductPromos = json_decode($promos['product'], true);
		                            $totalProduct = count($ProductPromos);
		                            $total_product = -1;
		                        @endphp
			                    {{-- ++++++++++++++ Product ++++++++++++++ --}}

			                    @foreach($ProductPromos as $key_p => $ProductPromo)
									@php
										$total_product++;
									@endphp
                                    <div id="product_{{ $total_product }}"
                                        class="form-group"
                                        style="width: 72%; display: inline-block;">
                                        <label for="product_{{ $total_product }}">
                                            Product {{ $key_p + 1 }}
                                        </label>
                                        <select class="form-control"
                                            name="product_{{ $total_product }}"
                                            id="product_{{ $total_product }}"
                                            name="product_{{ $total_product }}"
                                            data-msg="Mohon Pilih Product"
                                            required>
                                            <option selected disabled value="">
                                                Choose Product
                                            </option>

                                            @foreach ($products as $key => $product)
                                                @if ($promos->product_list()[$key_p]['id'] == $product['id'])
                                                    <option value="{{ $product['id'] }}"
                                                        selected>
                                                        {{ $product['code'] }} - {{ $product['name'] }}
                                                    </option>
                                                @else
                                                    <option value="{{ $product['id'] }}">
                                                        {{ $product['code'] }} - {{ $product['name'] }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <div class="validation"></div>
                                    </div>
                                    <div id="qty_{{ $total_product }}"
                                        class="form-group"
                                        style="width: 16%; display: inline-block;">
                                        <select class="form-control"
                                            name="qty_{{ $total_product }}"
                                            data-msg="Mohon Pilih Jumlah"
                                            required>
                                            <option selected value="1">
                                                1
                                            </option>

                                            @for ($i = 2; $i <= 10; $i++)
                                                @if ($ProductPromo['qty'] == $i)
                                                    <option value="{{ $i }}"
                                                        selected>
                                                        {{ $i }}
                                                    </option>
                                                @else
                                                    <option value="{{ $i }}">
                                                        {{ $i }}
                                                    </option>
                                                @endif
                                            @endfor
                                        </select>
                                        <div class="validation"></div>
                                    </div>

                                    @if ($total_product == 0)
                                        <div class="text-center"
                                            style="display: inline-block; float: right;">
                                            <button id="tambah_product"
                                                title="Tambah Product"
                                                style="padding: 0.4em 0.7em;">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    @else
                                        <div class="text-center"
                                            style="display: inline-block; float: right;">
                                            <button class="hapus_product"
                                                value="{{ $total_product }}"
                                                title="Hapus Product"
                                                style="padding: 0.4em 0.7em; background-color: red;">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    @endif

			                    @endforeach

			                    <div id="tambahan_product"></div>
			                </div>

							<div class="form-group d-none">
								<label for="weight">Berat Promo (KG)</label>
								<input type="text"
                                    class="form-control"
                                    id="weight"
                                    placeholder="Berat (Kg)" />
							</div>
							<div class="form-group d-none">
								<label for="amount">Jumlah Promo</label>
								<input type="text"
                                    class="form-control"
                                    id="amount"
                                    placeholder="Jumlah" />
							</div>
							<div class="form-group">
								<label for="price">Price (Rp.)</label>
								<input type="number"
                                    class="form-control"
                                    id="price"
                                    placeholder="Promo Price (Rp)"
                                    name="price"
                                    value="{{ (int) $promos["price"] }}"
                                    required />
							</div>

							{{-- <div class="form-group">
								@php
					                $img = json_decode($promos['image']);
					                $defaultImg = asset('sources/promo_images/').'/'.strtolower($promos['code']);
					            @endphp
								<div class="col-xs-12">
									<label>Promo Image (720x720 pixel)</label>
								</div>
								@for ($i = 0; $i < 3; $i++)
                                    <div class="col-xs-12 col-sm-6 col-md-4 form-group imgUp" style="padding: 15px; float: left;">
                                        <label>Image {{$i+1}}</label>
                                        @if(!empty($img[$i]))
                                            <div class="imagePreview"
                                                style="background-image: url({{ $defaultImg . '/' . $img[$i] }});">
                                            </div>
                                        @else
                                            <div class="imagePreview"
                                                style="background-image: url({{ asset('sources/dashboard/no-img-banner.jpg') }});">
                                            </div>
                                        @endif
                                        <label class="file-upload-browse btn btn-gradient-primary"
                                            style="margin-top: 15px;">
                                            Upload
                                            <input name="arr_image[]"
                                                data-name="arr_image"
                                                id="gambars-{{ $i }}"
                                                type="file"
                                                accept=".jpg,.jpeg,.png"
                                                class="uploadFile img"
                                                value="Upload Photo"
                                                style="width: 0px;height: 0px;overflow: hidden;">
                                        </label>
                                        <i class="mdi mdi-window-close del"></i>
                                    </div>
								@endfor
							</div> --}}

							<div class="form-group d-none">
								<label for="coming-soon">Coming soon?</label>
								<select class="form-control" id="coming-soon">
									<option>Yes</option>
									<option>No</option>
								</select>
							</div>

							<input type="hidden"
                                name="idPromo"
                                value="{{ $promos['id'] }}" />
							<input type="hidden"
                                id="lastTotalProduct"
                                value="{{$total_product}}" />
							<button id="updatePromo"
                                type="submit"
                                class="btn btn-gradient-primary mr-2">
                                Save
                            </button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="application/javascript">
	$(document).ready(function() {
        var frmUpdate;

	    $("#actionUpdate").on("submit", function (e) {
	        e.preventDefault();
	        frmUpdate = _("actionUpdate");
	        frmUpdate = new FormData(document.getElementById("actionUpdate"));
	        frmUpdate.enctype = "multipart/form-data";

	        /* for (var i = 0; i < 3; i++) {
	            frmUpdate.append('images'+i, $("#gambars-"+i)[0].files[0]);
	            if( $("#gambars-" + i)[0].files[0] != null) {
	                for(var j = 0; j < deleted_img.length; j++) {
	                    //console.log()
	                    if(deleted_img[j] == i) {
	                        deleted_img.splice(j, 1);
	                    }
	                }
	            }
	            console.log("test" + i);
	        }

	        frmUpdate.append('total_images', 3);
	        frmUpdate.append('dlt_img', deleted_img);
	        console.log(deleted_img); */

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
	        document.getElementById("updatePromo").innerHTML = "UPLOADING...";
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

	        document.getElementById("updatePromo").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("updatePromo").innerHTML = "SAVE";
	    }
    });
</script>
<script type="application/javascript">
	var deleted_img=[];

	$(document).on("click", "i.del" , function() {
	    $(this).closest(".imgUp").find('.imagePreview').css("background-image", "");
	    $(this).closest(".imgUp").find('input[type=text]').removeAttr("required");
	    $(this).closest(".imgUp").find('.btn').find('.img').val("");
	    $(this).closest(".imgUp").find('.form-control').val("");
	    deleted_img.push($(this).closest(".imgUp").find(".img").attr('id').substring(8));
	    console.log($(this).closest(".imgUp").find('.btn').find('.img').val());
	    console.log("yg dihapus: " + deleted_img);
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
<script type="application/javascript">
	$(document).ready(function(){
		var total_product = $('#lastTotalProduct').val();
		var count = 0;

		$("#tambah_product").click(function(e){
	        e.preventDefault();
	        total_product++;
	        count = total_product + 1;

	        strIsi = "<div id=\"product_"+total_product+"\" class=\"form-group\" style=\"width: 72%; display: inline-block;\"><label>Product "+count+" </label><select class=\"form-control\" name=\"product_"+total_product+"\" data-msg=\"Mohon Pilih Product\" required=\"\"><option selected disabled value=\"\">Choose Product</option> @foreach($products as $key=>$product) <option value=\"{{ $product['id'] }}\">{{ $product['code'] }} - {{ $product['name'] }}</option> @endforeach </select><div class=\"validation\"></div></div><div id=\"qty_"+total_product+"\" class=\"form-group\" style=\"width: 16%; display: inline-block;\"><select class=\"form-control\" name=\"qty_"+total_product+"\" data-msg=\"Mohon Pilih Jumlah\" required=\"\"><option selected value=\"1\">1</option> @for($i=2; $i<=10;$i++) <option value=\"{{ $i }}\">{{ $i }}</option> @endfor </select><div class=\"validation\"></div></div><div class=\"text-center\" style=\"display: inline-block; float: right;\"><button class=\"hapus_product\" value=\""+total_product+"\" title=\"Tambah Product\" style=\"padding: 0.4em 0.7em; background-color: red;\"><i class=\"fas fa-minus\"></i></button></div>";
	        $('#tambahan_product').html($('#tambahan_product').html()+strIsi);
	    });

	    $(document).on("click",".hapus_product", function(e){
	        e.preventDefault();
	        //total_product--;
	        console.log($('#product_'+$(this).val()));
	        $('#product_'+$(this).val()).remove();
	        $('#qty_'+$(this).val()).remove();

	        $(this).remove();
	    });
	});
</script>
@endsection
