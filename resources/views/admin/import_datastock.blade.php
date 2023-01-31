<?php
$menu_item_page = "data_stock";
$menu_item_second = "import_data_stock";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="{{ asset("css/lib/select2/select2-bootstrap4.min.css") }}" />
<style type="text/css">
    .select2-results__options{
        max-height: 15em;
        overflow-y: auto;
    }

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
            <h3 class="page-title">Import Data Stock</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#data_sourcing-dd"
                            aria-expanded="false"
                            aria-controls="data_sourcing-dd">
                            Data Stock
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Import Data Stock
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
                            enctype="multipart/form-data"
                            action="{{ route('store_import_data_stock') }}">
                            @csrf
                            <div class="form-group">
				                <label for="">Choose Warehouse To</label>
				                <select id="to_warehouse_id" class="form-control" name="to_warehouse_id" style="width: 100%" required>
                                    <option value="">Select Warehouse</option>
                                </select>
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
	              			</div>

                            <div class="form-group">
                                <label for="">File (csv)</label>
                                <input type="file" name="file" class="form-control" accept=".csv" required>
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
                            </div>

                            <button id="addImportDataSourcing"
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
        $("#to_warehouse_id").select2({
            theme: "bootstrap4",
            placeholder: "Choose Warehouse To",
        })

        function getWarehouse(warehouse_type, check_parent, target_element) {
            $(target_element).html("");
            return $.ajax({
                method: "GET",
                url: "{{ route('fetchWarehouse') }}",
                data: {warehouse_type, check_parent},
                success: function(response) {
                    var option_warehouse = `<option value="" selected disabled>Select Warehouse</option>`;
                    response.data.forEach(function(value) {
                        option_warehouse += `<option value="${value.id}">${value.code} - ${value.name}</option>`;
                    })
                    $(target_element).html(option_warehouse);
                },
            });
        }

        getWarehouse(null, true, '#to_warehouse_id'); 
    });
    $(document).ready(function() {
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
	        document.getElementById("addImportDataSourcing").innerHTML = "UPLOADING...";
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

	        document.getElementById("addImportDataSourcing").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("addImportDataSourcing").innerHTML = "SAVE";
	    }
    });
</script>
@endsection
