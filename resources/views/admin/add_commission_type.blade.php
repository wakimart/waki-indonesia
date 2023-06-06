<?php
$menu_item_page = "commstype";
$menu_item_second = "add_commstype";
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
        background-color: rgba(255, 255, 255, 0.6);
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Add Commission Type</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#commstype-dd"
                            aria-expanded="false"
                            aria-controls="commstype-dd">
                            Commision Type
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Add Commision Type
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form id="actionAdd" class="forms-sample" method="POST" action="">
                            @csrf
                              <div>
                                <div class="row no-gutters">
                                  <div class="form-group w-50">
                                    <label for="orderUpgrade" class="w-100">Upgrade ?</label>
                                    <div class="form-check-inline">
                                      <input class="form-check-input" type="radio" name="orderUpgradeOptions" id="orderUpgradeYes" value="orderUpgradeYes">
                                      <label class="form-check-label mb-0" for="orderUpgradeYes">Yes</label>
                                    </div>
                                    <div class="form-check-inline">
                                      <input class="form-check-input" type="radio" name="orderUpgradeOptions" id="orderUpgradeNo" value="orderUpgradeNo">
                                      <label class="form-check-label mb-0" for="orderUpgradeNo">No</label>
                                    </div>
                                  </div>

                                  <div class="form-group w-50">
                                    <label for="orderTakeaway" class="w-100">Takeaway ?</label>
                                    <div class="form-check-inline">
                                      <input class="form-check-input" type="radio" name="orderTakeawayOptions" id="orderTakeawayYes" value="orderTakeawayYes">
                                      <label class="form-check-label mb-0" for="orderTakeawayYes">Yes</label>
                                    </div>
                                    <div class="form-check-inline">
                                      <input class="form-check-input" type="radio" name="orderTakeawayOptions" id="orderTakeawayNo" value="orderTakeawayNo">
                                      <label class="form-check-label mb-0" for="orderTakeawayNo">No</label>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Commision Type Name</label>
                                    <div class="form-group">
                                        <input type="text"
                                            class="form-control"
                                            id="bonus"
                                            name="bonus"
                                            placeholder="Commision Type Name" />
                                        <div class="validation"></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control"
                                        id="description"
                                        name="description"
                                        rows="5"
                                        data-msg="Mohon Isi Description"
                                        placeholder="Description"></textarea>
                                    <div class="validation"></div>
                                </div>

                                <div class="form-group">
                                    <input type="text"
                                        class="form-control"
                                        id="nominal"
                                        name="nominal"
                                        placeholder="Nominal" />
                                    <div class="validation"></div>
                                </div>

                                <div class="form-group">
                                    <input type="text"
                                        class="form-control"
                                        id="semangatnominal"
                                        name="semangatnominal"
                                        placeholder="Semangat Nominal" />
                                    <div class="validation"></div>
                                </div>

                              </div>
                              <div class="row justify-content-center">
                                <button type="button" id="addCommsType" class="btn btn-success mr-2">
                                    Submit
                                </button>
                              </div>
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
	        document.getElementById("addCommsType").innerHTML = "UPLOADING...";
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

	        document.getElementById("addCommsType").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("addCommsType").innerHTML = "SAVE";
	    }
    });
</script>
@endsection
