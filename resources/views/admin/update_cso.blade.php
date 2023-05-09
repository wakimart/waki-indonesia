<?php
    $menu_item_page = "cso";
?>
@extends('admin.layouts.template')
@section('content')
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
              <h3 class="page-title">Edit CSO</h3>
  				<nav aria-label="breadcrumb">
    				<ol class="breadcrumb">
      					<li class="breadcrumb-item"><a data-toggle="collapse" href="#cso-dd" aria-expanded="false" aria-controls="cso-dd">CSO</a></li>
      					<li class="breadcrumb-item active" aria-current="page">Edit CSO</li>
    				</ol>
  				</nav>
		</div>
		<div class="row">
  			<div class="col-12 grid-margin stretch-card">
    			<div class="card">
      				<div class="card-body">
        					<form id="actionUpdate" class="forms-sample" method="POST" action="{{ route('update_cso', ['id' => $csos['id']])}}">
                                <input type="hidden" name="user_code" value="{{auth()->user()->code}}">
          						<div class="form-group d-none">
            						<div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
              							<div class="col-xs-4 col-sm-4" style="padding-left: 0;">
							                <label for="">Tanggal Registrasi</label>
							                <select class="text-uppercase form-control {{ $errors->has('registration_day') ? ' is-invalid' : '' }}" name="registration_day" value="{{ old('registration_day') }} reg_day_cso">
						                        <option value="" selected="selected" disabled="disabled">
						                            HARI
						                            @for ($i = 1; $i <= 31; $i++)
						                                <option value="{{$i}}" id="{{$i . 'c'}}">{{$i}}</option>
						                            @endfor
						                        </option>
						                    </select>
              							</div>
              							<div class="col-xs-4 col-sm-4" style="padding:0;">
							                <label for=""></label>
							                <select class="text-uppercase form-control {{ $errors->has('registration_month') ? ' is-invalid' : '' }} reg_month_cso" name="registration_month" value="{{ old('registration_month') }}">
						                        <option value="" selected="selected" disabled="disabled">
						                            BULAN
						                            @for ($i = 1; $i <= 12; $i++)
						                                <option value="{{$i}}" id="{{$i}}">{{$i}}</option>
						                            @endfor
						                        </option>
						                    </select>
              							</div>
              							<div class="col-xs-4 col-sm-4" style="padding-right: 0;">
							                <label for=""></label>
							                <input type="number" name="registration_year" class="form-control text-uppercase {{ $errors->has('registration_year') ? ' is-invalid' : '' }} reg_year_cso" placeholder="TAHUN" value="{{ old('registration_year') }}">
              							</div>
            						</div>
          						</div>
          						<div class="form-group d-none">
            						<div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
              							<div class="col-xs-4 col-sm-4" style="padding-left: 0;">
							                <label for="">Tanggal Berakhir</label>
							                <select class="text-uppercase form-control {{ $errors->has('unregistration_day') ? ' is-invalid' : '' }} unreg_day_cso" name="unregistration_day" value="{{ old('unregistration_day') }}">
						                        <option value="" selected="selected" disabled="disabled" required>
						                            HARI
						                            @for ($i = 1; $i <= 31; $i++)
						                                <option value="{{$i}}" id="{{$i . 'unc'}}">{{$i}}</option>
						                            @endfor
						                        </option>
						                    </select>
              							</div>
              						<div class="col-xs-4 col-sm-4" style="padding:0;">
                						<label for=""></label>
						                <select class="text-uppercase form-control {{ $errors->has('unregistration_month') ? ' is-invalid' : '' }} unreg_month_cso" name="unregistration_month" value="{{ old('unregistration_month') }}">
					                        <option value="" selected="selected" disabled="disabled" required>
					                            BULAN
					                            @for ($i = 1; $i <= 12; $i++)
					                                <option value="{{$i}}" id="{{$i}}">{{$i}}</option>
					                            @endfor
					                        </option>
					                    </select>
              						</div>
              						<div class="col-xs-4 col-sm-4" style="padding-right: 0;">
                						<label for=""></label>
                						<input type="number" name="unregistration_year" class="form-control text-uppercase {{ $errors->has('unregistration_year') ? ' is-invalid' : '' }} unreg_year_cso" placeholder="TAHUN" value="{{ old('unregistration_year') }}">
              						</div>
            					</div>
          					</div>
          					<div class="form-group">
					            <label for="">Code</label>
                                <input type="hidden" name="previous_code" value="{{$csos['code']}}">
					            <input type="text" class="form-control" name="code" value="{{$csos['code']}}" required>
                      <span class="invalid-feedback">
                          <strong></strong>
                      </span>
          					</div>
          					<div class="form-group">
					            <label for="">Name</label>
					            <input type="text" class="form-control" name="name" value="{{$csos['name']}}" required>
                      <span class="invalid-feedback">
                          <strong></strong>
                      </span>
          					</div>
          					<div class="form-group">
					            <label for="">Phone Number</label>
					            <input type="text" class="form-control" name="phone" placeholder="No. Telepon" value="{{$csos['phone']}}">
          					</div>
          					<div class="form-group d-none">
					            <label for="exampleTextarea1">Address</label>
					            <textarea class="form-control" name="address" rows="4" placeholder="Alamat Lengkap"></textarea>
          					</div>
          					<div class="form-group">
			                	<label for="">Branch</label>
                                <input type="hidden" name="branch_code" id="branch_code">
			                    <select class="form-control" id="branch" name="branch_id" data-msg="Mohon Pilih Cabang" required>
			                        <option selected disabled value="">Choose Cabang</option>

			                        @foreach($branches as $branch)
                                  @if($csos['branch_id'] == $branch['id'])
			                             <option value="{{ $branch['id'] }}" data-code="{{$branch->code}}" selected="true">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
                                  @else
                                    <option value="{{ $branch['id'] }}" data-code="{{$branch->code}}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
                                  @endif
			                        @endforeach
			                    </select>
			                    <div class="validation"></div>
			                </div>

         	 				<div class="form-group d-none">
	                			<div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
	                  				<div class="col-xs-10 col-sm-10" style="padding: 0;display: inline-block;">
					                    <label for="">Bank</label>
					                    <select class="form-control bank_name" name="bank_0" data-msg="Mohon Pilih Bank">
			                            <option selected disabled value="">Choose Bank</option>

			                            @foreach($banks as $key=>$bank)
			                                <option value="{{ $key }}">{{ $bank }}</option>
			                            @endforeach
			                        </select>
	                    				<div class="validation"></div>
	                  				</div>
	                  				<div class="col-xs-2 col-sm-2" style="padding-right: 0;display: inline-block;">
	                    				<label for="">Account Number</label>
	                					<input type="number" class="form-control" name="acc_number" placeholder="No. Rekening">
	                    				<div class="validation"></div>
	                  				</div>
	                			</div>
	              			</div>

                    <input type="hidden" name="idCso" value="{{$csos['id']}}">
				          	<button id="updateCso" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
				          	<button class="btn btn-light">Cancel</button>
        				</form>
      				</div>
    			</div>
  			</div>
		</div>
	</div>
</div>
<!-- Error modal -->
<div class="modal fade"
    id="error-modal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="error-modal-desc"></div>
        </div>
    </div>
</div>
<!-- End Modal View -->
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script type="text/javascript">
    // callback for find waki-indonesia 0ffline (local or ngrok)
    var urlOffline = "{{ env('OFFLINE_URL_2') }}"
    $.ajax({
        url:'https://waki-indonesia-offline.office/cms-admin/login',
        error: function(){
            urlOffline = "{{ env('OFFLINE_URL_2') }}"
        },
        success: function(){
            urlOffline = "{{ env('OFFLINE_URL') }}"
        }
    });

	$(document).ready(function() {
        var frmUpdate;
        var networkValue

	    $("#actionUpdate").on("submit", function (e) {
	        e.preventDefault();
	        frmUpdate = _("actionUpdate");
	        frmUpdate = new FormData(document.getElementById("actionUpdate"));
	        frmUpdate.enctype = "multipart/form-data";
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
	        document.getElementById("updateCso").innerHTML = "UPLOADING...";
	    }
	    function completeHandler(event){
	        var hasil = JSON.parse(event.target.responseText);
	        console.log(hasil);

	        for (var key of frmUpdate.keys()) {
	            $("#actionUpdate").find("input[name="+key+"]").removeClass("is-invalid");
	            $("#actionUpdate").find("select[name="+key+"]").removeClass("is-invalid");
	            $("#actionUpdate").find("textarea[name="+key+"]").removeClass("is-invalid");

	            $("#actionUpdate").find("input[name="+key+"]").next().find("strong").text("");
	            $("#actionUpdate").find("select[name="+key+"]").next().find("strong").text("");
	            $("#actionUpdate").find("textarea[name="+key+"]").next().find("strong").text("");
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
	            alert(hasil['errors']);
              //alert("Input Error!!!");
	        }
	        else{
	            testNetwork(networkValue, function(val){
                    $.ajax({
                        method: "post",
                        url: `${urlOffline}/api/update-cso-data`,
                        data: objectifyForm($('#actionUpdate').serializeArray()),
                        success: function(res){
                            alert("Input Success !!!");                            
	                        window.location.reload()
                        },
                        error: function() {
                            var modal = `
                                <div class="modal-body">
                                    <h5 class="modal-title text-center">Error</h5>
                                    <hr>
                                    <p class="text-center">something went wrong, please contact IT</p>
                                </div>
                            `
                            $('#error-modal-desc').html(modal)
                            $('#error-modal').modal("show")
                        }

                    })
                })
                return false
	        }

	        document.getElementById("updateCso").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("updateCso").innerHTML = "SAVE";
	    }

        function testNetwork(networkValue, response){
            // response();
            $.ajax({
                method: "post",
                url: `${urlOffline}/api/end-point-for-check-status-network`,
                dataType: 'json',
                contentType: 'application/json',
                processData: false,
                headers: {
                    "api-key": "{{ env('API_KEY') }}",
                },
                success: response,
                error: function() {
                    var modal = `
                        <div class="modal-body">
                            <h5 class="modal-title text-center">Error</h5>
                            <hr>
                            <p class="text-center">something went wrong, please contact IT</p>
                        </div>
                    `
                    $('#error-modal-desc').html(modal)
                    $('#error-modal').modal("show")
                }
            });
        };
    });
    function objectifyForm(formArray) {
        //serialize data function
        var returnArray = {};
        for (var i = 0; i < formArray.length; i++){
            returnArray[formArray[i]['name']] = formArray[i]['value'];
        }
        return returnArray;
    }

    $('#branch').change(function(){
        $('#branch_code').val($(this).find(':selected').attr('data-code'))
    })
</script>
<script type="text/javascript">
    //tanggal kabisat

    //reg cso
    $('.reg_month_cso, .reg_year_cso').on("change paste keyup", function() {
            if ($('.reg_month_cso').val()==2) {
                if($('.reg_year_cso').val()%4==0){
                    $("#29c").show();
                    if($('.reg_day_cso').val() > 29){
                        $('.reg_day_cso').val(29);
                    }
                }
                else{
                    $("#29c").hide();
                    if($('.reg_day_cso').val() > 28){
                        $('.reg_day_cso').val(28);
                    }
                }
                $("#30c").hide();
                $("#31c").hide();

                // var test = $('.birth_month').val();
                // var test2 = $('.birth_day').val();
                // console.log("masuk kabisat " + test + " " + test2);
            }
            else if ($('.reg_month_cso').val()==1||$('.reg_month_cso').val()==3||$('.reg_month_cso').val()==5||$('.reg_month_cso').val()==7||
                $('.reg_month_cso').val()==8||$('.reg_month_cso').val()==10||$('.reg_month_cso').val()==12){
                $("#30c").show();
                $("#31c").show();
                // var test = $('.birth_month').val();
                // var test2 = $('.birth_day').val();
                // console.log("tidak kabisat " + test + " " + test2);
            }
            else
            {
                $("#30c").show();
                $("#31c").hide();
                if($('.reg_day_cso').val() > 30){
                    $('.reg_day_cso').val(30);
                }
                // var test = $('.birth_month').val();
                // var test2 = $('.birth_day').val();
                // console.log("kabisat " + test + " " + test2);
            }
            console.log($('.reg_year_cso').val().length);
            if(parseInt($('.reg_year_cso').val()) > parseInt($('.reg_year_cso').attr('max')) && $('.reg_year_cso').val().length > 3){
                $('.reg_year_cso').val($('.reg_year_cso').attr('max'));
            }
            if(parseInt($('.reg_year_cso').val()) < parseInt($('.reg_year_cso').attr('min')) && $('.reg_year_cso').val().length > 3){
                $('.reg_year_cso').val($('.reg_year_cso').attr('min'));
            }  
        });
    //end reg cso

    //update reg cso
    $('.upreg_month_cso, .upreg_year_cso').on("change paste keyup", function() {
        if ($('.upreg_month_cso').val()==2) {
                if($('.upreg_year_cso').val()%4==0){
                    $("#29uc").show();
                    if($('.upreg_day_cso').val() > 29){
                        $('.upreg_day_cso').val(29);
                    }
                }
                else{
                    $("#29uc").hide();
                    if($('.upreg_day_cso').val() > 28){
                        $('.upreg_day_cso').val(28);
                    }
                }
                $("#30uc").hide();
                $("#31uc").hide();

                // var test = $('.birth_month').val();
                // var test2 = $('.birth_day').val();
                // console.log("masuk kabisat " + test + " " + test2);
            }
            else if ($('.upreg_month_cso').val()==1||$('.upreg_month_cso').val()==3||$('.upreg_month_cso').val()==5||$('.upreg_month_cso').val()==7||
                $('.upreg_month_cso').val()==8||$('.upreg_month_cso').val()==10||$('.upreg_month_cso').val()==12){
                $("#30uc").show();
                $("#31uc").show();
                // var test = $('.birth_month').val();
                // var test2 = $('.birth_day').val();
                // console.log("tidak kabisat " + test + " " + test2);
            }
            else
            {
                $("#30uc").show();
                $("#31uc").hide();
                if($('.upreg_day_cso').val() > 30){
                    $('.upreg_day_cso').val(30);
                }
                // var test = $('.birth_month').val();
                // var test2 = $('.birth_day').val();
                // console.log("kabisat " + test + " " + test2);
            }
            console.log($('.upreg_year_cso').val().length);
            if(parseInt($('.upreg_year_cso').val()) > parseInt($('.upreg_year_cso').attr('max')) && $('.upreg_year_cso').val().length > 3){
                $('.upreg_year_cso').val($('.upreg_year_cso').attr('max'));
            }
            if(parseInt($('.upreg_year_cso').val()) < parseInt($('.upreg_year_cso').attr('min')) && $('.upreg_year_cso').val().length > 3){
                $('.upreg_year_cso').val($('.upreg_year_cso').attr('min'));
            }  
        });
    //end update cso

    //unreg cso
    $('.unreg_month_cso, .unreg_year_cso').on("change paste keyup", function() {
            if ($('.unreg_month_cso').val()==2) {
                if($('.unreg_year_cso').val()%4==0){
                    $("#29unc").show();
                    if($('.unreg_day_cso').val() > 29){
                        $('.unreg_day_cso').val(29);
                    }
                }
                else{
                    $("#29unc").hide();
                    if($('.unreg_day_cso').val() > 28){
                        $('.unreg_day_cso').val(28);
                    }
                }
                $("#30unc").hide();
                $("#31unc").hide();

                // var test = $('.birth_month').val();
                // var test2 = $('.birth_day').val();
                // console.log("masuk kabisat " + test + " " + test2);
            }
            else if ($('.unreg_month_cso').val()==1||$('.unreg_month_cso').val()==3||$('.unreg_month_cso').val()==5||$('.unreg_month_cso').val()==7||
                $('.unreg_month_cso').val()==8||$('.unreg_month_cso').val()==10||$('.unreg_month_cso').val()==12){
                $("#30unc").show();
                $("#31unc").show();
                // var test = $('.birth_month').val();
                // var test2 = $('.birth_day').val();
                // console.log("tidak kabisat " + test + " " + test2);
            }
            else
            {
                $("#30unc").show();
                $("#31unc").hide();
                if($('.unreg_day_cso').val() > 30){
                    $('.unreg_day_cso').val(30);
                }
                // var test = $('.birth_month').val();
                // var test2 = $('.birth_day').val();
                // console.log("kabisat " + test + " " + test2);
            }
            console.log($('.unreg_year_cso').val().length);
            if(parseInt($('.unreg_year_cso').val()) > parseInt($('.unreg_year_cso').attr('max')) && $('.unreg_year_cso').val().length > 3){
                $('.unreg_year_cso').val($('.unreg_year_cso').attr('max'));
            }
            if(parseInt($('.unreg_year_cso').val()) < parseInt($('.unreg_year_cso').attr('min')) && $('.unreg_year_cso').val().length > 3){
                $('.unreg_year_cso').val($('.unreg_year_cso').attr('min'));
            }  
        });
    //end unreg cso

    //update unreg cso
    $('.upunreg_month_cso, .upunreg_year_cso').on("change paste keyup", function() {
        if ($('.upunreg_month_cso').val()==2) {
                if($('.upunreg_year_cso').val()%4==0){
                    $("#29ur").show();
                    if($('.upunreg_day_cso').val() > 29){
                        $('.upunreg_day_cso').val(29);
                    }
                }
                else{
                    $("#29ur").hide();
                    if($('.upunreg_day_cso').val() > 28){
                        $('.upunreg_day_cso').val(28);
                    }
                }
                $("#30ur").hide();
                $("#31ur").hide();

                // var test = $('.birth_month').val();
                // var test2 = $('.birth_day').val();
                // console.log("masuk kabisat " + test + " " + test2);
            }
            else if ($('.upunreg_month_cso').val()==1||$('.upunreg_month_cso').val()==3||$('.upunreg_month_cso').val()==5||$('.upunreg_month_cso').val()==7||
                $('.upunreg_month_cso').val()==8||$('.upunreg_month_cso').val()==10||$('.upunreg_month_cso').val()==12){
                $("#30ur").show();
                $("#31ur").show();
                // var test = $('.birth_month').val();
                // var test2 = $('.birth_day').val();
                // console.log("tidak kabisat " + test + " " + test2);
            }
            else
            {
                $("#30ur").show();
                $("#31ur").hide();
                if($('.upunreg_day_cso').val() > 30){
                    $('.upunreg_day_cso').val(30);
                }
                // var test = $('.birth_month').val();
                // var test2 = $('.birth_day').val();
                // console.log("kabisat " + test + " " + test2);
            }
            console.log($('.upunreg_year_cso').val().length);
            if(parseInt($('.upunreg_year_cso').val()) > parseInt($('.upunreg_year_cso').attr('max')) && $('.upunreg_year_cso').val().length > 3){
                $('.upunreg_year_cso').val($('.upunreg_year_cso').attr('max'));
            }
            if(parseInt($('.upunreg_year_cso').val()) < parseInt($('.upunreg_year_cso').attr('min')) && $('.upunreg_year_cso').val().length > 3){
                $('.upunreg_year_cso').val($('.upunreg_year_cso').attr('min'));
            }  
        });
    //end update unreg cso
</script>
@endsection