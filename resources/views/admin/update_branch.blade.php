<?php
    $menu_item_page = "branch";
?>
@extends('admin.layouts.template')

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
              <form id="actionUpdate" class="forms-sample" method="POST" action="{{route('update_branch')}}">
                  <div class="form-group">
                    <label for="">Code</label>
                    <input type="text" class="form-control" name="code" value="{{$branches['code']}}">
                  </div>
                  <div class="form-group">
                    <label for="">Branch</label>
                    <input type="text" class="form-control" name="name" value="{{$branches['name']}}">
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
</script>
@endsection