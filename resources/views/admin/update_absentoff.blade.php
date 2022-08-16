<?php
    $menu_item_page = "absent_off";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="{{ asset("css/lib/select2/select2-bootstrap4.min.css") }}" />
<style type="text/css">
    #intro {
        padding-top: 2em;
    }
    button{
        background: #1bb1dc;
        border: 0;
        border-radius: 3px;
        padding: 8px 30px;
        color: #fff;
        transition: 0.3s;
    }
    .validation{
        color: red;
        font-size: 9pt;
    }
    input, select, textarea{
        border-radius: 0 !important;
        box-shadow: none !important;
        border: 1px solid #dce1ec !important;
        font-size: 14px !important;
    }

	.check label{
		width: 25em;
	}
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
      <div class="page-header">
            <h3 class="page-title">Edit Cuti</h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a data-toggle="collapse" href="#absent_off-dd" aria-expanded="false" aria-controls="absent_off-dd">Cuti</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Cuti</li>
              </ol>
            </nav>
      </div>

      <div class="row">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                <form id="actionAdd" class="forms-sample" method="POST" action="{{ route('update_absent_off') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for=""><h2>Form Ijin Cuti</h2></label><br/>
                    </div>
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{ $absentOff->cso->name }}" disabled required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="">Branch</label>
                            @if (Auth::user()->roles[0]['slug'] === 'cso')
                                <input type="hidden" name="branch_id" value="{{ Auth::user()->cso['branch_id'] }}">
                                <input type="hidden" name="cso_id" value="{{ Auth::user()->cso_id }}">
                            @endif
                            <select class="form-control" id="branch" name="branch_id" data-msg="Mohon Pilih Branch" required
                                @if (Auth::user()->roles[0]['slug'] === 'cso') disabled @endif >
                                @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" 
                                    @if($branch->id == $absentOff->branch_id) selected @endif >
                                    {{ $branch->code }} - {{ $branch->name }}
                                </option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback">
                                <strong></strong>
                            </span>
                            <div class="validation"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Cso</label>
                            <select class="form-control" id="cso" name="cso_id" data-msg="Mohon Pilih Cso" required
                                @if (Auth::user()->roles[0]['slug'] === 'cso') disabled @endif >
                                <option selected disabled value="">Pilihan Cso</option>
                                @foreach ($csos as $cso)
                                <option value="{{ $cso->id }}"
                                    @if($cso->id == $absentOff->cso_id) selected @endif>
                                    {{ $cso->code }} - {{ $cso->name }}
                                </option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback">
                                <strong></strong>
                            </span>
                            <div class="validation"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Division</label>
                        <input type="text" class="form-control" id="division" name="division" placeholder="Division" value="{{ $absentOff->division }}" required data-msg="Mohon Isi Division"/>
                        <span class="invalid-feedback">
                            <strong></strong>
                        </span>
                        <div class="validation"></div>
                    </div>
                    <div class="form-group">
                        <label for="">Work Since</label>
                        <input type="text" class="form-control" id="duration_work" name="duration_work" placeholder="Work Since" value="{{ $absentOff->duration_work }}" required data-msg="Mohon Isi Work Since"/>
                        <span class="invalid-feedback">
                            <strong></strong>
                        </span>
                        <div class="validation"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $absentOff->start_date}}" required data-msg="Mohon Isi Start Date"/>
                            <span class="invalid-feedback">
                                <strong></strong>
                            </span>
                            <div class="validation"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $absentOff->end_date }}" required data-msg="Mohon Isi End Date"/>
                            <span class="invalid-feedback">
                                <strong></strong>
                            </span>
                            <div class="validation"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="">Duration Off</label>
                            <input type="number" class="form-control" id="duration_off" name="duration_off" value="{{ $absentOff->duration_off }}" required data-msg="Mohon Isi Duration Off"/>
                            <span class="invalid-feedback">
                                <strong></strong>
                            </span>
                            <div class="validation"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Work On</label>
                            <input type="date" class="form-control" id="work_on" name="work_on" value="{{ $absentOff->work_on }}" required data-msg="Mohon Isi Work On"/>
                            <span class="invalid-feedback">
                                <strong></strong>
                            </span>
                            <div class="validation"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleTextarea1">Reason of Absent</label>
                        <textarea class="form-control" id="desc" name="desc" rows="5" required data-msg="Mohon Isi Reason" placeholder="Reason of absent" required>{{ $absentOff->desc }}</textarea>
                        <span class="invalid-feedback">
                            <strong></strong>
                        </span>
                        <div class="validation"></div>
                    </div>

                    <div class="form-group">
                        <button id="addAbsentOff" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
                    </div>

                </form>
                </div>
              </div>
            </div>
      </div>
  </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
<script>
    $(document).ready(function(){
        var csos = <?php echo json_encode($csos) ?>;
        $("#cso").select2({
            theme: "bootstrap4",
            placeholder: "Choose CSO"
        });

        $("#cso").on("select2:select", function(e) { 
            var selectedCsoId = $(this).val();
            var found_cso = csos.find((cso) => {
                return cso.id == selectedCsoId; 
            })
            if (found_cso) {
                $("#name").val(found_cso.name);
            }
        });

		var frmAdd;
        var arr_productService = [];

	    $("#actionAdd").on("submit", function (e) {
	        e.preventDefault();
	        frmAdd = _("actionAdd");
	        frmAdd = new FormData(document.getElementById("actionAdd"));
	        frmAdd.enctype = "multipart/form-data";
            frmAdd.append('id', "{{ $absentOff['id'] }}");

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
	        document.getElementById("addAbsentOff").innerHTML = "UPLOADING...";
	    }
	    function completeHandler(event){
	        var hasil = JSON.parse(event.target.responseText);

	        for (var key of frmAdd.keys()) {
	            $("#actionAdd").find("input[name="+key.name+"]").removeClass("is-invalid");
	            $("#actionAdd").find("select[name="+key.name+"]").removeClass("is-invalid");
	            $("#actionAdd").find("textarea[name="+key.name+"]").removeClass("is-invalid");

	            $("#actionAdd").find("input[name="+key.name+"]").next().find("strong").text("");
	            $("#actionAdd").find("select[name="+key.name+"]").next().find("strong").text("");
	            $("#actionAdd").find("textarea[name="+key.name+"]").next().find("strong").text("");
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
	            alert(hasil['errors']);
	        }
	        else{ 
	            alert("Input Success !!!");
	            var route_to_list = "{{route('list_acc_absent_off')}}";
	           	window.location.href = route_to_list;
	        }

	        document.getElementById("addAbsentOff").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("addAbsentOff").innerHTML = "SAVE";
	    }
	});
</script>
@endsection
