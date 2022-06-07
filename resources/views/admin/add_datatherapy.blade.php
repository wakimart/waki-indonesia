<?php
$menu_item_page = "data_therapy";
$menu_item_second = "add_data_therapy";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="{{ asset("css/lib/select2/select2-bootstrap4.min.css") }}" />
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
        background-color: rgba(255, 255, 255, 0.6);
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Add Data Therapy</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#data_therapy-dd"
                            aria-expanded="false"
                            aria-controls="data_therapy-dd">
                            Data Therapy
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Add Data Therapy
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form id="actionAdd"
                            class="forms-sample"
                            method="POST"
                            action="{{ route('store_data_therapy') }}">
                            @csrf
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text"
                                    class="form-control"
                                    name="name"
                                    id="name"
                                    placeholder="Name"
                                    required />
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="">No KTP</label>
                                <input type="number" class="form-control" name="no_ktp" id="no_ktp" placeholder="Nomor KTP" required data-msg="Mohon Isi Nomor KTP"/>
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="">Phone Number</label>
                                <input type="number" class="form-control" name="phone" id="phone" placeholder="Nomor Telepon" required data-msg="Mohon Isi Nomor Telepon"/>
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="exampleTextarea1">Alamat (Optional)</label>
                                <textarea class="form-control" id="address" name="address" rows="5" data-msg="Mohon Isi Alamat" placeholder="Alamat"></textarea>
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
                            </div>

                            <div class="form-group">
				                <label for="">Type Customer</label>
				                <select class="form-control" id="typecustomer" name="type_customer_id" required data-msg="Mohon Pilih Type Customer" >
				                  	<option value="">Choose Type Customer</option>
			                        @foreach($type_customers as $type_customer)
			                            <option value="{{ $type_customer['id'] }}">{{ $type_customer['name'] }}</option>
			                        @endforeach
	                			</select>
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
	              			</div>

                            <div class="form-group">
				                <label for="">Branch</label>
				                <select class="form-control" id="branch" name="branch_id" data-msg="Mohon Pilih Cabang" >
				                  	<option selected disabled value="">Choose Branch</option>
			                        @foreach($branches as $branch)
			                            <option value="{{ $branch['id'] }}">
                                            {{ $branch['code'] }} - {{ $branch['name'] }}
                                        </option>
			                        @endforeach
	                			</select>
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
	              			</div>
                              
	              			<div class="form-group">
	                			<label for="">CSO</label>
                                <select id="cso" name="cso_id" class="form-control pilihan-product" data-msg="Mohon Pilih CSO" required>
                                    <option value="">Choose CSO</option>
                                    @foreach ($csos as $cso)
                                        <option value="{{ $cso->id }}">
                                            {{ $cso->code }} - {{ $cso->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
	              			</div>

                            <div class="form-group row">
                                <div class="col-xs-12 col-sm-6 col-md-4 imgUp"
                                    style="padding: 15px; float: left;">
                                    <label>Photo KTP</label>
                                    <div class="imagePreview"
                                        style="background-image: url({{ asset('sources/dashboard/no-img-banner.jpg') }});">
                                    </div>
                                    <label class="file-upload-browse btn btn-gradient-primary"
                                        style="margin-top: 15px;">
                                        Upload
                                        <input name="image"
                                            id="image"
                                            type="file"
                                            accept=".jpg,.jpeg,.png"
                                            class="uploadFile img"
                                            value="Upload Photo"
                                            style="width: 0px; height: 0px; overflow: hidden;"
                                            required />
                                    </label>
                                </div>
                            </div>
                            
                            <button id="addDataTherapy"
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
<script type="application/javascript">
    $(document).ready(function() {
        $("#cso").select2({
            theme: "bootstrap4",
            placeholder: "Choose CSO"
        });

        $("#typecustomer").select2({
            theme: "bootstrap4",
            placeholder: "Choose Type Customer"
        });

        var frmAdd;

	    $("#actionAdd").on("submit", function (e) {
	        e.preventDefault();
	        frmAdd = _("actionAdd");
	        frmAdd = new FormData(document.getElementById("actionAdd"));
	        frmAdd.enctype = "multipart/form-data";
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
	        document.getElementById("addDataTherapy").innerHTML = "UPLOADING...";
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

	        document.getElementById("addDataTherapy").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("addDataTherapy").innerHTML = "SAVE";
	    }
    });
</script>
<script type="application/javascript">
    $(document).on("change", ".uploadFile", function () {
        const uploadFile = $(this);
        const files = this.files ? this.files : [];

        // no file selected, or no FileReader support
        if (!files.length || !window.FileReader) {
            return;
        }

        // only image file
        if (/^image/.test(files[0].type)) {
            // instance of the FileReader
            const reader = new FileReader();
            // read the local file
            reader.readAsDataURL(files[0]);

            // set image data as background of div
            reader.onloadend = function () {
                uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url(" + this.result + ")");
            };
        }
    });
</script>
@endsection
