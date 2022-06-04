<?php
$menu_item_page = "data_sourcing";
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
            <h3 class="page-title">Edit Data Sourcing</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#data_sourcing-dd"
                            aria-expanded="false"
                            aria-controls="data_sourcing-dd">
                            Data Sourcing
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Add Data Sourcing
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
                            action="{{ route('update_data_sourcing') }}">
                            @csrf
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text"
                                    class="form-control"
                                    name="name"
                                    id="name"
                                    value="{{ $data_sourcings['name'] }}"
                                    placeholder="Name"
                                    required />
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="">Phone Number</label>
                                <input type="number" class="form-control" name="phone" id="phone" value="{{ $data_sourcings['phone'] }}" placeholder="Nomor Telepon" required data-msg="Mohon Isi Nomor Telepon"/>
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="exampleTextarea1">Alamat</label>
                                <textarea class="form-control" id="address" name="address" rows="5" data-msg="Mohon Isi Alamat" placeholder="Alamat">{{ $data_sourcings['address'] }}</textarea>
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
                            </div>

                            <div class="form-group">
				                <label for="">Type Customer</label>
				                <select class="form-control" id="typecustomer" name="type_customer_id" required data-msg="Mohon Pilih Type Customer" >
				                  	<option selected disabled value="">Choose Type Customer</option>
			                        @foreach($type_customers as $type_customer)
			                            <option value="{{ $type_customer['id'] }}" @if($type_customer['id'] == $data_sourcings['type_customer_id']) selected @endif>
                                            {{ $type_customer['name'] }}
                                        </option>
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
			                            <option value="{{ $branch['id'] }}" @if($branch['id'] == $data_sourcings['branch_id']) selected @endif>
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
                                        <option value="{{ $cso->id }}" @if($cso['id'] == $data_sourcings['cso_id']) selected @endif>
                                            {{ $cso->code }} - {{ $cso->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
	              			</div>

                            <input type="hidden"
                                name="idDataSourcing"
                                value="{{ $data_sourcings['id'] }}" />

                            <button id="addDataSourcing"
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
	        document.getElementById("addDataSourcing").innerHTML = "UPLOADING...";
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

	        document.getElementById("addDataSourcing").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("addDataSourcing").innerHTML = "SAVE";
	    }
    });
</script>
@endsection
