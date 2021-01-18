<?php
  $menu_item_page = "homeservice";
  $menu_item_second = "add_home_service";
?>
@extends('admin.layouts.template')

@section('style')
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
</style>
@endsection

@section('content')
<div class="main-panel">
  	<div class="content-wrapper">
    	<div class="page-header">
      		<h3 class="page-title">Add Home Service</h3>
      		<nav aria-label="breadcrumb">
	        	<ol class="breadcrumb">
	          		<li class="breadcrumb-item"><a data-toggle="collapse" href="#cso-dd" aria-expanded="false" aria-controls="cso-dd">Home Service</a></li>
	          		<li class="breadcrumb-item active" aria-current="page">Add Home Service</li>
	        	</ol>
      		</nav>
    	</div>
	    <div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">
						@if(Utils::$lang=='id')
	            		<form id="actionAdd" class="forms-sample" method="POST" action="{{ route('admin_store_homeService') }}">
							{{ csrf_field() }}
							<div class="form-group">
								<span>Type Customer</span>
  								<select id="type_customer" style="margin-top: 0.5em;" class="form-control" style="height: auto;" name="type_customer" value="" required>
                    <option value="VVIP (Type A)">VVIP (Type A)</option>
                    <option value="WAKi Customer (Type B)">WAKi Customer (Type B)</option>
                    <option value="New Customer (Type C)">New Customer (Type C)</option>
  								</select>
								<span class="invalid-feedback">
									<strong></strong>
								</span>
							</div>
							<div class="form-group">
								<span>Type Home Service</span>
  								<select id="type_homeservices" style="margin-top: 0.5em;" class="form-control" style="height: auto;" name="type_homeservices" value="" required>
                    <option value="Home service">Home Service</option>
                    <option value="Home Tele Voucher">Home Tele Voucher</option>
                    <option value="Home Eksklusif Therapy">Home Eksklusif Therapy</option>
                    <option value="Home Free Family Therapy">Home Free Family Therapy</option>
                    <option value="Home Demo Health & Safety with WAKi">Home Demo Health & Safety with WAKi</option>
                    <option value="Home Voucher">Home Voucher</option>
                    <option value="Home Tele Free Gift">Home Tele Free Gift</option>
                    <option value="Home Refrensi Product">Home Refrensi Product</option>
                    <option value="Home Delivery">Home Delivery</option>
                    <option value="Home Free Refrensi Therapy VIP">Home Free Refrensi Therapy VIP</option>
                    <option value="Home WAKi di Rumah Aja">Home WAKi di Rumah Aja</option>
  								</select>
								<span class="invalid-feedback">
									<strong></strong>
								</span>
							</div>
          			<div class="form-group">
                  <label for=""><h2>Data Pelanggan</h2></label><br/>
            			<label for="">No. Member (optional)</label>
            			<input type="number" class="form-control" id="no_member" name="no_member" placeholder="No. Member (optional)">
            			<div class="validation"></div>
          			</div>
          			<div class="form-group">
	                <label for="">Name</label>
	                <input type="text" class="form-control" id="name" name="name" placeholder="Name">
	                <div class="validation"></div>
          			</div>
          			<div class="form-group">
	                <label for="">Phone Number</label>
	                <input type="number" class="form-control" name="phone" id="phone" placeholder="Nomor Telepon" required data-msg="Mohon Isi Nomor Telepon"/>
	                <div class="validation"></div>
          			</div>
          			<div class="form-group">
	                <label for="">Province</label>
								<select class="form-control" id="province" name="province_id" data-msg="Mohon Pilih Provinsi" required>
									<option selected disabled value="">Pilihan Provinsi</option>
									@php
										$result = RajaOngkir::FetchProvince();
										$result = $result['rajaongkir']['results'];
										$arrProvince = [];
										if(sizeof($result) > 0){
											foreach ($result as $value) {
												echo "<option value=\"". $value['province_id']."\">".$value['province']."</option>";
											}
										}
									@endphp
								</select>
                <div class="validation"></div>
                </div>
      			    <div class="form-group">
	                <label for="exampleTextarea1">Kota</label>
	                <select class="form-control" id="city" name="city" data-msg="Mohon Pilih Kota" required>
  									<option selected disabled value="">Pilihan Kota</option>
  								</select>
                  <div class="validation"></div>
                </div>
							<div class="form-group">
                <label for="exampleTextarea1">Kecamatan</label>
                <select class="form-control" id="subDistrict" name="subDistrict" data-msg="Mohon Pilih Kecamatan" required>
									<option selected disabled value="">Pilihan Kecamatan</option>
								</select>
                <div class="validation"></div>
							</div>
							<div class="form-group">
                <label for="exampleTextarea1">Alamat</label>
                <textarea class="form-control" name="address" rows="5" required data-msg="Mohon Isi Alamat" placeholder="Alamat"></textarea>
                <div class="validation"></div>
        			</div>
        			<br>

        			<div class="form-group">
								<label for=""><h2>Data CSO</h2></label><br/>
          				<label for="">Cabang</label>
								  <select class="form-control" id="branch" name="branch_id" data-msg="Mohon Pilih Cabang" required>
                    <option selected disabled value="">Pilihan Cabang</option>
                    @foreach($branches as $branch)
										<option value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
                    @endforeach
					        </select>
			            <div class="validation"></div>
							</div>

							<div class="form-group">
								<label for="">Kode CSO</label>
									<input type="text" class="form-control" name="cso_id" id="cso" placeholder="Kode CSO" required data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase" {{ Auth::user()->roles[0]['slug'] == 'cso' ? "value=".Auth::user()->cso['code'] : "" }}  {{ Auth::user()->roles[0]['slug'] == 'cso' ? "readonly=\"\"" : "" }}/>
									<div class="validation" id="validation_cso"></div>
									<span class="invalid-feedback">
										<strong></strong>
									</span>
			            <div class="validation"></div>
							</div>

							<div class="form-group d-none">
								<label for="">No Telepon CSO</label>
									<input type="number" class="form-control" name="cso_phone" id="cso_phone" placeholder="No. Telepon CSO" data-msg="Mohon Isi Nomor Telepon" {{ Auth::user()->roles[0]['slug'] == 'cso' ? "value=".Auth::user()->cso['phone'] : "" }}  {{ Auth::user()->roles[0]['slug'] == 'cso' ? "readonly=\"\"" : "" }}/>
									<div class="validation"></div>
									<span class="invalid-feedback">
										<strong></strong>
									</span>
			            <div class="validation"></div>
							</div>

							<div class="form-group">
								<label for="">Kode Partner CSO(Optional)</label>
								<input type="text" class="form-control" name="cso2_id" id="cso2" placeholder="Kode Partner CSO (opsional)" style="text-transform:uppercase"/>
								<div class="validation"></div>
								<span class="invalid-feedback">
									<strong></strong>
								</span>
                  <div class="validation"></div>
                </div>

              <label for=""><h2>Waktu Home Service</h2></label><br/>
              <div class="form_appoint_container">
                <div class="form-group">
                  <label for="">Tanggal Janjian</label>
  								  <input type="date" class="form-control" name="date" id="date" placeholder="Tanggal Janjian" value="<?php echo date('Y-m-j'); ?>" required data-msg="Mohon Isi Tanggal" />
  								<div class="validation"></div>
  								<span class="invalid-feedback">
  									<strong></strong>
  								</span>
  							</div>
  							<div class="form-group">
  								<label for="">Jam Janjian</label>
  								<input type="time" class="form-control" name="time" id="time" placeholder="Jam Janjian" value="<?php echo date('H:i'); ?>" required data-msg="Mohon Isi Jam" min="10:00" max="20:00"/>
  								<div class="validation"></div>
  								<span class="invalid-feedback">
  									<strong></strong>
  								</span>
  							</div>
              </div>

        			<div id="errormessage"></div>

        			<div class="form-group">
        				<button id="addHomeService" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
        				<button class="btn btn-light">Cancel</button>
        			</div>

	          </form>
						@elseif(Utils::$lang=='eng')
						<form id="actionAdd" class="forms-sample" method="POST" action="{{ route('admin_store_homeService') }}">
							{{ csrf_field() }}
        			<div class="form-group">
                <label for=""><h2>Data Member</h2></label><br/>
          			<label for="">No. Member (optional)</label>
          			<input type="number" class="form-control" id="no_member" name="no_member" placeholder="No. Member (optional)">
          			<div class="validation"></div>
        			</div>
        			<div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                <div class="validation"></div>
        			</div>
        			<div class="form-group">
                <label for="">Phone Number</label>
                <input type="number" class="form-control" name="phone" id="phone" placeholder="Nomor Telepon" required data-msg="Mohon Isi Nomor Telepon"/>
                <div class="validation"></div>
        			</div>
	              <div class="form-group">
								<input type="text" class="form-control" name="city" id="city" placeholder="City" required data-msg="Please fill the City" />
								<div class="validation"></div>
							</div>
							<div class="form-group">
								<input type="text" class="form-control" name="distric" id="subDistrict" placeholder="Sub District" required data-msg="Please fill the Sub District" />
								<div class="validation"></div>
							</div>
							<div class="form-group">
                <label for="exampleTextarea1">Address</label>
                <textarea class="form-control" name="address" rows="5" required data-msg="Please fill the Address" placeholder="Alamat"></textarea>
                <div class="validation"></div>
        			</div>
        			<br>
	           	<div class="form-group">
								<label for=""><h2>Data CSO</h2></label><br/>
	              	<label for="">Branch</label>
								  <select class="form-control" id="branch" name="branch_id" data-msg="Please choose the Branch" required>
									<option selected disabled value="">Branch Option</option>
									@foreach($branches as $branch)
										<option value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
									@endforeach
								</select>
			          <div class="validation"></div>
							</div>

							<div class="form-group">
								<label for="">Code CSO</label>
									<input type="text" class="form-control" name="cso_id" id="cso" placeholder="Code CSO" required data-msg="Please fill the CSO Code" style="text-transform:uppercase" {{ Auth::user()->roles[0]['slug'] == 'cso' ? "value=".Auth::user()->cso['code'] : "" }}  {{ Auth::user()->roles[0]['slug'] == 'cso' ? "readonly=\"\"" : "" }}/>
									<div class="validation" id="validation_cso"></div>
									<span class="invalid-feedback">
										<strong></strong>
									</span>
                  <div class="validation"></div>
							</div>

							<div class="form-group">
								<label for="">No Telepon CSO</label>
									<input type="number" class="form-control" name="cso_phone" id="cso_phone" placeholder="CSO Phone Number" data-msg="Please fill the CSO Phone Number" {{ Auth::user()->roles[0]['slug'] == 'cso' ? "value=".Auth::user()->cso['phone'] : "" }}  {{ Auth::user()->roles[0]['slug'] == 'cso' ? "readonly=\"\"" : "" }} />
									<div class="validation"></div>
									<span class="invalid-feedback">
										<strong></strong>
									</span>
			            <div class="validation"></div>
							</div>

							<div class="form-group">
								<label for=""><h2>Home Service Appointment</h2></label><br/>
	              	<label for="">Date</label>
								  <input type="date" class="form-control" name="date" id="date" placeholder="Appointment Date" value="<?php echo date('Y-m-j'); ?>" required data-msg="Please fill the Date" />
								<div class="validation"></div>
								<span class="invalid-feedback">
									<strong></strong>
								</span>
							</div>

							<div class="form-group">
								<label for="">Appointment Time</label>
								<input type="time" class="form-control" name="time" id="time" placeholder="Appointment Time" value="<?php echo date('H:i'); ?>" required data-msg="Please fill the time" min="10:00" max="20:00"/>
								<div class="validation"></div>
								<span class="invalid-feedback">
									<strong></strong>
								</span>
							</div>

	            <div id="errormessage"></div>

        			<div class="form-group">
        				<button id="addHomeService" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
        				<button class="btn btn-light">Cancel</button>
        			</div>
          		</form>
						@endif
					     </div>
	        	</div>
	      	</div>
	    </div>
	</div>
</div>

<!-- modal success -->
<div class="modal fade" role="dialog" tabindex="-1" id="modal-Success">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Input Success</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <p id="txt-success">Appointment telah berhasil dibuat.</p>
            </div>
            <div class="modal-footer">
              <button class="btn btn-gradient-primary" type="button" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- modal error -->
<div class="modal fade" role="dialog" tabindex="-1" id="modal-Error">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Input Failed</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <p id="txt-success">"Appointment dengan nomer ini sudah ada!!"</p>
            </div>
            <div class="modal-footer">
              <button class="btn btn-gradient-danger" type="button" data-dismiss="modal">OK</button>
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
	        document.getElementById("addHomeService").innerHTML = "UPLOADING...";
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
	        } else if(hasil['validator'] != null){
              $("#modal-Error").modal("show");
	            // alert("Appointment dengan nomer ini sudah ada!!");
    			} else if (hasil['active'] != null){
    	        alert("Apakah Appointment ini reschadule? Jika iya lakukan edit pada menu edit");
    			}
	        else{
              $("#modal-Success").modal("show");
	            // alert("Input Success !!!");
	            // window.location.reload()
	        }
	        document.getElementById("addHomeService").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("addHomeService").innerHTML = "SAVE";
	    }
    });
</script>
<script>
    $(document).ready(function(){
        $("#cso, #cso2").on("input", function(){
            var txtCso = $(this).val();
            var obj = $('#validation_cso');
            if($(this)[0].id == "cso2"){
                obj = $('#validation_cso2');
            }
            $.get( '{{route("fetchCso")}}', { cso_code: txtCso })
            .done(function( result ) {
                if (result['result'] == 'true' && result['data'].length > 0){
                    obj.html('Kode CSO Benar');
                    obj.css('color', 'green');
					$('#submit').removeAttr('disabled');
					// $('#cso_phone').val(result.data[0].phone);
                }
                else{
                    obj.html('Kode CSO Salah');
                    obj.css('color', 'red');
                    $('#submit').attr('disabled',"");
                }
            });
        });

        $("#province").on("change", function(){
            var id = $(this).val();
            $( "#city" ).html("");
            $.get( '{{ route("fetchCity", ['province' => ""]) }}/'+id )
            .done(function( result ) {
                result = result['rajaongkir']['results'];
                var arrCity = "<option selected disabled value=\"\">Pilihan Kota</option>";
                if(result.length > 0){
                    $.each( result, function( key, value ) {
                        arrCity += "<option value=\""+value['type']+" "+value['city_name']+"\">"+value['type']+" "+value['city_name']+"</option>";
                    });
                    $( "#city" ).append(arrCity);
                }
            });
        });

        $("#province").on("change", function(){
            var id = $(this).val();
            $( "#city" ).html("");
            $.get( '{{ route("fetchCity", ['province' => ""]) }}/'+id )
            .done(function( result ) {
                result = result['rajaongkir']['results'];
                var arrCity = "<option selected disabled value=\"\">Pilihan Kota</option>";
                if(result.length > 0){
                    $.each( result, function( key, value ) {
                        if(value['type'] == "Kota"){
                            arrCity += "<option value=\"Kota "+value['city_name']+"\">Kota "+value['city_name']+"</option>";
                        }
                    });
                    $( "#city" ).append(arrCity);
                }
            });
		});
		$("#city").on("change", function(){
            var id = $(this).val();
			$( "#subDistrict" ).html("");
            $.get( '{{ route("fetchDistrict", ['city' => ""]) }}/'+id )
            .done(function( result ) {
				result = result['rajaongkir']['results'];
				console.log(result);
                var arrSubDistsrict = "<option selected disabled value=\"\">Pilihan Kecamatan</option>";
                if(result.length > 0){
                    $.each( result, function( key, value ) {
                        arrSubDistsrict += "<option value=\""+value['subdistrict_name']+"\">"+value['subdistrict_name']+"</option>";
                    });
                    $( "#subDistrict" ).append(arrSubDistsrict);
                }
            });
        });
    });
</script>
<script>
  $(document).ready(function(){
    var Limit = 1;
    $('#type_homeservices').change(function(){
    if($(this).val() == 'Home Eksklusif Therapy' || $(this).val() == 'Home Family Therapy'){
      if( Limit > 1){
        return false;
      }
      else {
        Limit ++;
        $('.form_appoint_container').append(
          '<div class="form-group optional_appointment">\
            <label for="">Tanggal Janjian 2 (Optional)</label>\
              <input type="date" class="form-control" name="date" id="date" placeholder="Tanggal Janjian" value="<?php echo date('Y-m-j'); ?>" data-msg="Mohon Isi Tanggal" />\
            <div class="validation"></div>\
            <span class="invalid-feedback">\
              <strong></strong>\
            </span>\
          </div>\
          <div class="form-group optional_appointment">\
            <label for="">Jam Janjian 2 (Optional)</label>\
            <input type="time" class="form-control" name="time" id="time" placeholder="Jam Janjian" value="<?php echo date('H:i'); ?>" data-msg="Mohon Isi Jam" min="10:00" max="20:00"/>\
            <div class="validation"></div>\
            <span class="invalid-feedback">\
              <strong></strong>\
            </span>\
          </div>\
          <div class="form-group optional_appointment">\
            <label for="">Tanggal Janjian 3 (Optional)</label>\
              <input type="date" class="form-control" name="date" id="date" placeholder="Tanggal Janjian" value="<?php echo date('Y-m-j'); ?>" data-msg="Mohon Isi Tanggal" />\
            <div class="validation"></div>\
            <span class="invalid-feedback">\
              <strong></strong>\
            </span>\
          </div>\
          <div class="form-group optional_appointment">\
            <label for="">Jam Janjian 3 (Optional)</label>\
            <input type="time" class="form-control" name="time" id="time" placeholder="Jam Janjian" value="<?php echo date('H:i'); ?>" data-msg="Mohon Isi Jam" min="10:00" max="20:00"/>\
            <div class="validation"></div>\
            <span class="invalid-feedback">\
              <strong></strong>\
            </span>\
          </div>');
      }
    }
    else if($(this).val() == 'Home WAKi di Rumah Aja'){
      if( Limit > 1){
        return false;
      }
      else {
        Limit ++;
        $('.form_appoint_container').append(
          '<div class="form-group optional_appointment">\
            <label for="">Tanggal Janjian 2 (Optional)</label>\
              <input type="date" class="form-control" name="date" id="date" placeholder="Tanggal Janjian" value="<?php echo date('Y-m-j'); ?>" data-msg="Mohon Isi Tanggal" />\
            <div class="validation"></div>\
            <span class="invalid-feedback">\
              <strong></strong>\
            </span>\
          </div>\
          <div class="form-group optional_appointment">\
            <label for="">Jam Janjian 2 (Optional)</label>\
            <input type="time" class="form-control" name="time" id="time" placeholder="Jam Janjian" value="<?php echo date('H:i'); ?>" data-msg="Mohon Isi Jam" min="10:00" max="20:00"/>\
            <div class="validation"></div>\
            <span class="invalid-feedback">\
              <strong></strong>\
            </span>\
          </div>\
          <div class="form-group optional_appointment">\
            <label for="">Tanggal Janjian 3 (Optional)</label>\
              <input type="date" class="form-control" name="date" id="date" placeholder="Tanggal Janjian" value="<?php echo date('Y-m-j'); ?>" data-msg="Mohon Isi Tanggal" />\
            <div class="validation"></div>\
            <span class="invalid-feedback">\
              <strong></strong>\
            </span>\
          </div>\
          <div class="form-group optional_appointment">\
            <label for="">Jam Janjian 3 (Optional)</label>\
            <input type="time" class="form-control" name="time" id="time" placeholder="Jam Janjian" value="<?php echo date('H:i'); ?>" data-msg="Mohon Isi Jam" min="10:00" max="20:00"/>\
            <div class="validation"></div>\
            <span class="invalid-feedback">\
              <strong></strong>\
            </span>\
          </div>\
          <div class="form-group optional_appointment">\
            <label for="">Tanggal Janjian 4 (Optional)</label>\
              <input type="date" class="form-control" name="date" id="date" placeholder="Tanggal Janjian" value="<?php echo date('Y-m-j'); ?>" data-msg="Mohon Isi Tanggal" />\
            <div class="validation"></div>\
            <span class="invalid-feedback">\
              <strong></strong>\
            </span>\
          </div>\
          <div class="form-group optional_appointment">\
            <label for="">Jam Janjian 4 (Optional)</label>\
            <input type="time" class="form-control" name="time" id="time" placeholder="Jam Janjian" value="<?php echo date('H:i'); ?>" data-msg="Mohon Isi Jam" min="10:00" max="20:00"/>\
            <div class="validation"></div>\
            <span class="invalid-feedback">\
              <strong></strong>\
            </span>\
          </div>\
          <div class="form-group optional_appointment">\
            <label for="">Tanggal Janjian 5 (Optional)</label>\
              <input type="date" class="form-control" name="date" id="date" placeholder="Tanggal Janjian" value="<?php echo date('Y-m-j'); ?>" data-msg="Mohon Isi Tanggal" />\
            <div class="validation"></div>\
            <span class="invalid-feedback">\
              <strong></strong>\
            </span>\
          </div>\
          <div class="form-group optional_appointment">\
            <label for="">Jam Janjian 5 (Optional)</label>\
            <input type="time" class="form-control" name="time" id="time" placeholder="Jam Janjian" value="<?php echo date('H:i'); ?>" data-msg="Mohon Isi Jam" min="10:00" max="20:00"/>\
            <div class="validation"></div>\
            <span class="invalid-feedback">\
              <strong></strong>\
            </span>\
          </div>');
      }
    }
    else{
        $('.optional_appointment').remove();
    }
    });
  });
</script>
@endsection
