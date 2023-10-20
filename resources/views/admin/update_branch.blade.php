<?php
    $menu_item_page = "branch";
?>
@extends('admin.layouts.template')

@section("style")
  <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
      integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer" />
  <style type="text/css">
      select{
          border-radius: 0 !important;
          box-shadow: none !important;
          border: 1px solid #dce1ec !important;
          font-size: 14px !important;
      }

      .select2-selection__rendered {
          line-height: 45px !important;
      }

      .select2-container .select2-selection--single {
          height: 45px !important;
      }

      .select2-container--default
      .select2-selection--single
      .select2-selection__arrow {
          top: 10px;
      }
  </style>
@endsection
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="page-header">
      <h3 class="page-title">Edit Branch</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a data-toggle="collapse" href="#branch-dd" aria-expanded="false" aria-controls="branch-dd">Branch</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Branch</li>
        </ol>
      </nav>
    </div>
    <div class="row">
      <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
              <form id="actionUpdate" class="forms-sample" method="POST" action="{{route('update_branch', ['id' => $branches['id']])}}">
                  <div class="form-group">
                    <label for="">Code</label>
                    <input type="text" class="form-control" name="code" value="{{$branches['code']}}">
                    <span class="invalid-feedback">
                        <strong></strong>
                    </span>
                  </div>
                  <div class="form-group">
                    <label for="">Branch</label>
                    <input type="text" class="form-control" name="name" value="{{$branches['name']}}">
                    <span class="invalid-feedback">
                        <strong></strong>
                    </span>
                  </div>
                  <div class="form-group">
                    <label for="">Warehouse Out Binding</label>
                    <select class="form-control" name="warehouse_id" id="select_warehouse">
                      <option {{isset($branches['warehouse_id']) ? '': 'selected'}} value="">
                        Select Parent Warehouse
                      </option>
                      @foreach($warehouses as $warehouse)
                        <option value="{{$warehouse->id}}" {{$branches['warehouse_id'] == $warehouse->id ? 'selected' : ''}}>{{$warehouse->code}} - {{$warehouse->name}}</option>
                      @endforeach
                    </select>
                    <span class="invalid-feedback">
                      <strong></strong>
                    </span>
                  </div>

                  <input type="hidden" name="idBranch" value="{{$branches['id']}}">
                  <button id="updateBranch" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
  integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
  crossorigin="anonymous"
  referrerpolicy="no-referrer"
  defer></script>
<script type="text/javascript">
  $(document).ready(function() {
        var frmAdd;

      $("#actionUpdate").on("submit", function (e) {
          e.preventDefault();
          frmAdd = _("actionUpdate");
          frmAdd = new FormData(document.getElementById("actionUpdate"));
          frmAdd.enctype = "multipart/form-data";
          var URLNya = $("#actionUpdate").attr('action');
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
          document.getElementById("updateBranch").innerHTML = "UPLOADING...";
      }
      function completeHandler(event){
          var hasil = JSON.parse(event.target.responseText);
          console.log(hasil);

          for (var key of frmAdd.keys()) {
              $("#actionUpdate").find("input[name="+key+"]").removeClass("is-invalid");
              $("#actionUpdate").find("select[name="+key+"]").removeClass("is-invalid");
              $("#actionUpdate").find("textarea[name="+key+"]").removeClass("is-invalid");

              $("#actionUpdate").find("input[name="+key+"]").next().find("strong").text("");
              $("#actionUpdate").find("select[name="+key+"]").next().find("strong").text("");
              $("#actionUpdate").find("textarea[name="+key+"]").next().find("strong").text("");
          }

          if(hasil['errors'] != null){
              for (var key of frmAdd.keys()) {
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

          document.getElementById("updateBranch").innerHTML = "SAVE";
      }
      function errorHandler(event){
          document.getElementById("updateBranch").innerHTML = "SAVE";
      }
    });
  document.addEventListener("DOMContentLoaded", function () {
		$("#select_warehouse").select2();
	});
</script>
@endsection