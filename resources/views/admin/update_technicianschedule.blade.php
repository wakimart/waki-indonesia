<?php
    $menu_item_page = "technician";
    $menu_item_second = "update_technician_schedule";
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

	.check label{
		width: 25em;
	}
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
      <div class="page-header">
            <h3 class="page-title">Update Technician Schedule</h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a data-toggle="collapse" href="#technician_schedule-dd" aria-expanded="false" aria-controls="technician_schedule-dd">Technician Schedule</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Update Technician Schedule</li>
              </ol>
            </nav>
      </div>

      <div class="row">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                <form id="actionAdd" class="forms-sample" method="POST" action="{{ route('update_technician_schedule') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for=""><h2>Data Pelanggan</h2></label><br/>
                    </div>
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{ isset($autofill) ? $autofill['name'] : "" }}" required>
                        </div>
                        <div class="form-group">
                        <label for="">Phone Number</label>
                        <input type="number" class="form-control" value="{{ isset($autofill) ? $autofill['phone'] : "" }}" name="phone" id="phone" placeholder="Nomor Telepon" required data-msg="Mohon Isi Nomor Telepon"/>
                        <span class="invalid-feedback">
                            <strong></strong>
                        </span>
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
                                        if(isset($autofill) && $value['province_id'] == $autofill['province']){
                                            echo "<option selected value=\"". $value['province_id']."\">".$value['province']."</option>";
                                            continue;
                                        }
                                        echo "<option value=\"". $value['province_id']."\">".$value['province']."</option>";
                                    }
                                }
                            @endphp
                        </select>
                        <span class="invalid-feedback">
                            <strong></strong>
                        </span>
                        <div class="validation"></div>
                    </div>
                    <div class="form-group">
                        <label for="exampleTextarea1">Kota</label>
                        <select class="form-control" id="city" name="city" data-msg="Mohon Pilih Kota" required>
                            <option selected disabled value="">Pilihan Kota</option>
                        </select>
                        <span class="invalid-feedback">
                            <strong></strong>
                        </span>
                        <div class="validation"></div>
                    </div>
                    <div class="form-group">
                        <label for="exampleTextarea1">Kecamatan</label>
                        <select class="form-control" id="subDistrict" name="subDistrict" data-msg="Mohon Pilih Kecamatan" required>
                            <option selected disabled value="">Pilihan Kecamatan</option>
                        </select>
                        <span class="invalid-feedback">
                            <strong></strong>
                        </span>
                        <div class="validation"></div>
                    </div>
                    <div class="form-group">
                        <label for="exampleTextarea1">Alamat</label>
                        <textarea class="form-control" id="address" name="address" rows="5" required data-msg="Mohon Isi Alamat" placeholder="Alamat" required>{{ $autofill['address'] ?? '' }}</textarea>
                        <span class="invalid-feedback">
                            <strong></strong>
                        </span>
                        <div class="validation"></div>
                    </div>
                    <div class="form-group">
                        <label for="">D/O</label>
                        <input type="text" class="form-control" id="d_o" name="d_o" placeholder="D/O" value="{{ $autofill->d_o }}" required>
                        <span class="invalid-feedback">
                            <strong></strong>
                        </span>
                        <div class="validation"></div>
                    </div>
                    <br>
                    <label for=""><h2>Jadwal Waktu</h2></label><br/>
                    <div class="form_appoint_container">
                        <div class="form-group">
                            <label for="">Jadwal Tanggal</label>
                            <input type="date"
                                class="form-control"
                                name="date"
                                id="date"
                                placeholder="Jadwal Tanggal"
                                value="<?php echo date("Y-m-d", strtotime($autofill['appointment'] ?? 'now')); ?>"
                                min="<?php echo date("Y-m-d"); ?>"
                                onchange="setMinAppointmentTime(this)"
                                onload="setMinAppointmentTime(this)"
                                required
                                data-msg="Mohon Isi Tanggal" />
                            <div class="validation"></div>
                            <span class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="">Jadwal Jam</label>
                            <input type="time"
                                class="form-control"
                                name="time"
                                id="time"
                                placeholder="Jadwal Jam"
                                value="<?php echo date('H:i', strtotime($autofill['appointment'] ?? 'now')); ?>"
                                required
                                data-msg="Mohon Isi Jam"
                                min="<?php echo date("H:i") ?>"
                                max="20:00" />
                            <div class="validation"></div>
                            <span class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div id="errormessage"></div>
                    <br>
                    <div class="form-group">
                        <label for=""><h2>Data Teknisi</h2></label><br/>
                        <label for="">Kode CSO Teknisi</label>
                        <input type="text" class="form-control" name="cso_id" id="cso" value="{{ $autofill->cso->code }}" placeholder="Kode CSO Teknisi" required data-msg="Mohon Isi Kode CSO Teknisi" style="text-transform:uppercase" />
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
                    <br>
                    <div class="form-group">
                        <label for="sertivces">Service (Optional)</label>
                        <select class="form-control" id="service_id" name="service_id" data-msg="Pilih Service">
                            <option selected disabled value="">Pilihan Service</option>
                            @foreach($services as $service)
                                <option value="{{ $service['id'] }}" @if($service['id'] == $autofill->service['id']) selected @endif>{{ $service['code'] }}</option>
                            @endforeach
                        </select>
                        <div class="validation"></div>
                    </div>
                    <br>
                    <label for="">PRODUCT SERVICE</label>
                    <div class="text-center" style="display: inline-block;background: #4caf3ab3;float: right;margin-bottom: 20px;">
                        <button class="btn btn-gradient-primary mr-2" id="tambah_productservice" type="button">Add Product Service</button>
                    </div>
                    </br>

                    @foreach($product_tss as $key => $product_ts)
                        <div class="form-group" id="container-productservice-{{$key}}">
                            <input type="hidden" id="productservice_active-{{$key}}" value="{{$product_ts['active']}}">
                            <label for="">Product Service {{$key + 1}}</label>
                            @if($key > 0)
                            <div class="text-center" style="display: inline-block;float: right;margin-bottom: 20px;">
                                <button class="btn btn-gradient-danger remove_productservice" type="button">Remove</button>
                            </div>
                            @endif
                            <div class="form-group">
                                <select class="form-control pilihan-productservice" id="product_service-{{$key}}" data-msg="Mohon Pilih Product" required>
                                    <option selected disabled value="">Choose PRODUCT SERVICE</option>
                                    <option value="other" @if($product_ts['product_id'] == null) selected @endif>OTHER</option>
                                    @foreach($products as $product)
                                        @if($product_ts['product_id'] == $product['id'])
                                            <option value="{{ $product['id'] }}" selected>{{ $product['code'] }} - {{ $product['name'] }}</option>
                                        @else
                                            <option value="{{ $product['id'] }}">{{ $product['code'] }} - {{ $product['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="validation"></div>
                            </div>

                            
                            <div class="form-group @if($product_ts['product_id'] != null) d-none  @endif">
                                <input type="text" class="form-control" id="productservice_other_{{$key}}" placeholder="Product Name" data-msg="Please fill in the product" value="{{$product_ts['other_product']}}"/>
                                <div class="validation"></div>
                            </div>

                            @php
                                $issues = json_decode($product_ts['issues']);
                                $count_main_issue = count($issues[0]->issues);

                                $due_date = explode(' ',$product_ts['due_date']);
                                $due_date = $due_date[0];

                                $cbx_issues = ["Kerusakan Listrik", "Bersuara/Bergetar", "Heating", "Jatuh, Pecah, Unit Lepas", "Kerusakan Mekanik", "Lainnya..."];
                            @endphp

                            <div class="form-group check">
                                <label for="">Issues</label>
                                <br>
                                @for($i = 0; $i < count($cbx_issues); $i++)
                                    @if(in_array($cbx_issues[$i], $issues[0]->issues))
                                        <label class="checkbox-inline">
                                            <input id="cbx_issue-{{$i}}-{{$key}}" name="cbx_issue-{{$key}}" style="margin-right: 10px;" type="checkbox" value="{{$cbx_issues[$i]}}" checked="true">{{$cbx_issues[$i]}}
                                        </label>
                                    @else
                                        <label class="checkbox-inline">
                                            <input id="cbx_issue-{{$i}}-{{$key}}" name="cbx_issue-{{$key}}" style="margin-right: 10px;" type="checkbox" value="{{$cbx_issues[$i]}}">{{$cbx_issues[$i]}}
                                        </label>
                                    @endif
                                @endfor

                                <textarea class="form-control" id="issues-{{$key}}" rows="5" data-msg="Mohon Isi Issues" placeholder="Description" required="">{{$issues[1]->desc}}</textarea>
                                <div class="validation"></div>
                            </div>

                            <div class="form-group">
                                <label for="">Due Date</label>
                                <input type="date" class="form-control" id="due_date-{{$key}}" placeholder="Tanggal Order" required data-msg="Mohon Isi Tanggal" value="{{$due_date}}"/>
                                <div class="validation"></div>
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
                            </div>
                            <br>
                            
                            {{-- ++++++++++++++ ======== ++++++++++++++ --}}			                    
                        </div>
                        <input type="hidden" id="id_product_ts-{{$key}}" name="id_product_ts[]" value="{{$product_ts['id']}}">
                    @endforeach
                        
                    <div id="tambahan_productservice"></div>
                    <input type="hidden" id="service_id_old" name="service_id_old" value="{{$product_ts->service['id']}}">
                    <input type="hidden" id="ts_id" name="ts_id" value="{{ $autofill['id'] ?? '' }}">

                    @php $total_product_ts = count($product_tss); @endphp
                    <input type="hidden" id="total_product_ts" value="{{$total_product_ts}}">

                    <div class="form-group">
                        <button id="addTechnicianSchedule" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
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
<script>
    function setMinAppointmentTime(e) {
        // Tanggal & waktu dari inputan
        const getCurrentDate = new Date(Date.parse(e.value));

        // Tanggal & waktu hari ini
        const today = new Date();

        if (
            getCurrentDate.getFullYear() === today.getFullYear()
            && getCurrentDate.getMonth() === today.getMonth()
            && getCurrentDate.getDate() === today.getDate()
        ) {
            if (today.getHours() < 10) {
                document.getElementById("time").setAttribute("min", "10:00");
            } else if (today.getHours() >= 10 && today.getHours() < 20) {
                document.getElementById("time").setAttribute(
                    "min",
                    ("0" + (getCurrentDate.getHours() + 1)).slice(-2)
                    + ":"
                    + ("0" + (getCurrentDate.getMinutes() + 1)).slice(-2)
                );
            } else if (today.getHours() >= 20) {
                document.getElementById("time").setAttribute("disabled", "");
            }
        } else {
            document.getElementById("time").setAttribute("min", "10:00");
        }
    }

    $(document).ready(function(){
        $("#cso").on("input", function(){
            var txtCso = $(this).val();
            var obj = $('#validation_cso');
            $.get( '{{route("fetchCso")}}', { cso_code: txtCso, branch_id: "{{$branch->id}}" })
            .done(function( result ) {
                if (result['result'] == 'true' && result['data'].length > 0){
                    obj.html('Kode CSO Teknisi Benar');
                    obj.css('color', 'green');
					$('#submit').removeAttr('disabled');
					// $('#cso_phone').val(result.data[0].phone);
                }
                else{
                    obj.html('Kode CSO Teknisi Salah');
                    obj.css('color', 'red');
                    $('#submit').attr('disabled',"");
                }
            });
        });

        var temp_city = "{{ $autofill['city'] ?? null }}";
        var temp_district = "{{ $autofill['district'] ?? null }}";

        $("#province").on("change", function(){
            var id = $(this).val();
            $( "#city" ).html("");
            $.get( '{{ route("fetchCity", ['province' => ""]) }}/'+id )
            .done(function( result ) {
                result = result['rajaongkir']['results'];
                var arrCity = "<option selected disabled value=\"\">Pilihan Kabupaten</option>";
                if(result.length > 0){
                    $.each( result, function( key, value ) {
						if(value['type'] == "Kabupaten"){
							arrCity += "<option value=\""+value['city_id']+"\">"+value['type']+" "+value['city_name']+"</option>";
						}
                    });
                    arrCity += "<option selected disabled value=\"\">Pilihan Kota</option>";
                    $.each( result, function( key, value ) {
                        if(value['type'] == "Kota"){
                            arrCity += "<option value=\""+value['city_id']+"\">Kota "+value['city_name']+"</option>";
                        }
                    });
                    $( "#city" ).append(arrCity);
                }
                if (temp_city != null) {
                    $('#city').val(temp_city).change();
                    temp_city = null;
                }
            });
        });

		$("#city").on("change", function(){
            var id = $(this).val();
			$( "#subDistrict" ).html("");
            $.get( '{{ route("fetchDistrict", ['city' => ""]) }}/'+id )
            .done(function( result ) {
				result = result['rajaongkir']['results'];
                var arrSubDistsrict = "<option selected disabled value=\"\">Pilihan Kecamatan</option>";
                if(result.length > 0){
                    $.each( result, function( key, value ) {
                        arrSubDistsrict += "<option value=\""+value['subdistrict_id']+"\">"+value['subdistrict_name']+"</option>";
                    });
                    $( "#subDistrict" ).append(arrSubDistsrict);
                }
                if (temp_district != null) {
                    $('#subDistrict').val(temp_district).change();
                    temp_district = null;
                }
            });
        });

        $('#province').val("{{ $autofill['province'] }}").change();
        
        var idService = $('#total_product_ts').val();
		var detailProductService = $('#total_product_ts').val() - 1;
		var counter_service = $('#total_product_ts').val();

		$('#tambah_productservice').click(function(e){
			idService++;
			detailProductService++;
			counter_service++;

			if(counter_service <= 3){
				e.preventDefault();
				$('#tambahan_productservice').append('\
					<div id="container-productservice-'+detailProductService+'">\
                        <input type="hidden" id="productservice_active-'+detailProductService+'" value="1">\
						<div class="text-center" style="display: inline-block;float: right;margin-bottom: 20px;">\
							<button class="btn btn-gradient-danger remove_productservice" type="button">Remove</button>\
						</div>\
						<label for="">Product Service '+counter_service+'</label>\
						<div class="form-group">\
							<select class="form-control pilihan-productservice" id="product_service-'+detailProductService+'" data-msg="Mohon Pilih Product" required>\
								<option selected disabled value="">Choose PRODUCT SERVICE</option>\
									@if(true)<option value="other">OTHER</option>@endif\
									@foreach($products as $product)<option value="{{ $product['id'] }}">{{ $product['code'] }} - {{ $product['name'] }}</option>@endforeach\
								</option>\
								@if(true)<option value="OTHER">OTHER</option>@endif\
							</select>\
							<div class="validation"></div>\
						</div>\
						<div class="form-group d-none">\
			                <input type="text" class="form-control" id="productservice_other_'+detailProductService+'" placeholder="Product Name" data-msg="Please fill in the product" />\
			            	<div class="validation"></div>\
			            </div>\
						<div class="form-group check">\
							<label for="">Issues</label>\
							<br>\
							<label class="checkbox-inline">\
						      	<input id="cbx_issue-0-'+detailProductService+'" name="cbx_issue-'+detailProductService+'" style="margin-right: 10px;" type="checkbox" value="Kerusakan Listrik">Kerusakan Listrik\
						    </label>\
						    <label class="checkbox-inline">\
						      	<input id="cbx_issue-1-'+detailProductService+'" name="cbx_issue-'+detailProductService+'" style="margin-right: 10px;" type="checkbox" value="Bersuara/Bergetar">Bersuara/Bergetar\
						    </label>\
						    <label class="checkbox-inline">\
						      	<input id="cbx_issue-2-'+detailProductService+'" name="cbx_issue-'+detailProductService+'" style="margin-right: 10px;" type="checkbox" value="Heating">Heating\
						    </label>\
						    <br>\
						    <label class="checkbox-inline">\
						      	<input id="cbx_issue-3-'+detailProductService+'" name="cbx_issue-'+detailProductService+'" style="margin-right: 10px;" type="checkbox" value="Jatuh, Pecah, Unit Lepas">Jatuh, Pecah, Unit Lepas\
						    </label>\
						    <label class="checkbox-inline">\
						      	<input id="cbx_issue-4-'+detailProductService+'" name="cbx_issue-'+detailProductService+'" style="margin-right: 10px;" type="checkbox" value="Kerusakan Mekanik">Kerusakan Mekanik\
						    </label>\
						    <label class="checkbox-inline">\
						      	<input id="cbx_issue-5-'+detailProductService+'" name="cbx_issue-'+detailProductService+'" style="margin-right: 10px;" type="checkbox" value="Lainnya...">Lainnya...\
						    </label>\
						    \
							<textarea class="form-control" id="issues-'+detailProductService+'" rows="5" data-msg="Mohon Isi Issues" placeholder="Issues" required></textarea>\
							<div class="validation"></div>\
						</div>\
						<div class="form-group">\
							<label for="">Due Date</label>\
							<input type="date" class="form-control" id="due_date-'+detailProductService+'" placeholder="Tanggal Order" value="<?php echo date('Y-m-j'); ?>" required data-msg="Mohon Isi Tanggal" />\
							<div class="validation"></div>\
							<span class="invalid-feedback">\
							<strong></strong>\
							</span>\
						</div><br>\
					</div>\
				');
			}else{
				alert("Service maksimal 3 produk!!!");
			}
		});

		$(document).on("click", ".remove_productservice", function(){
            counter_service--;
			var get_temp_index = $(this).parent().parent().children().attr("id");
			var get_index = get_temp_index.slice(-1);

			$("#productservice_active-"+get_index).val("0");
			$(this).parent().parent().children().addClass("d-none");
		});

		@if(true)
            $(document).on("change", ".pilihan-productservice", function(e){
                if($(this).val() == 'other'){
                    $(this).parent().next().removeClass("d-none");
                    $(this).parent().next().children().attr('required', '');
                }
                else{
                    $(this).parent().next().addClass("d-none");
                    $(this).parent().next().children().removeAttr('required', '');
                }
            });
        @endif

        function convertPhpDatetime(appointment) {
            const appointmentDate = new Date(appointment);
            const dateString = appointmentDate.getFullYear()
                + "-"
                + ("0" + (appointmentDate.getMonth() + 1)).slice(-2)
                + "-"
                + ("0" + (appointmentDate.getDate())).slice(-2);
            const timeString = ("0" + (appointmentDate.getHours())).slice(-2)
                + ":"
                + ("0" + (appointmentDate.getMinutes())).slice(-2);

            return [dateString, timeString];
        }

        $('#service_id').on('change', function() {
            var service_id = $(this).val();
            $.get( '{{route("fetchProductService")}}', { service_id: service_id })
            .done(function( result ) {
                if (result['result'] == 'true' && result['data'].length > 0){
                    var result0 = result['data'][0];
                    if (result0['product_id'] != null) {
                        $('#product_service-0').val(result0['product_id']).change();
                    } else {
                        $('#product_service-0').val("other").change();
                        $('#productservice_other_0').val(result0['other_product']);
                    }
                    var issues = JSON.parse(result0['issues']);
                    var cbx_issues = issues[0].issues;
                    $('#issues-0').val(issues[1].desc);
                    $("input[name='cbx_issue-0']").prop('checked', false);
                    $.each(cbx_issues, function(i, val){
                        $("input[name='cbx_issue-0'][value='" + val + "']").prop('checked', true);
                    });
                    $('#due_date-0').val(convertPhpDatetime(result0['due_date'])[0]);
                    addProductServiceFromServer(result['data'])
                }
                else{
                    console.log("error: " + result);
                }
            });
        });

        // Tambah Product Service dari Server
        function addProductServiceFromServer(product_services) {
            if (product_services.length > 0) {
                $.each(product_services, function (i, product_service) {
                    if (i != 0) {
                        $('#tambah_productservice').trigger('click');
                        if (product_service['product_id'] != null) {
                            $('#product_service-' + detailProductService).val(product_service['product_id']).change();
                        } else {
                            $('#product_service-' + detailProductService).val("other").change();
                            $('#productservice_other_' + detailProductService).val(product_service['other_product']);
                        }
                        var issues = JSON.parse(product_service['issues']);
                        var cbx_issues = issues[0].issues;
                        $('#issues-' + detailProductService).val(issues[1].desc);
                        $("input[name='cbx_issue-" + detailProductService +"']").prop('checked', false);
                        $.each(cbx_issues, function(i, val){
                            $("input[name='cbx_issue-" + detailProductService + "'][value='" + val + "']").prop('checked', true);
                        });
                        $('#due_date-' + detailProductService).val(convertPhpDatetime(product_service['due_date'])[0]);
                    }
                });
            }
        }

		var frmAdd;
	    $("#actionAdd").on("submit", function (e) {
            var arr_productService = [];
            var arr_id_productts = [];
	        e.preventDefault();
	        frmAdd = _("actionAdd");
	        frmAdd = new FormData(document.getElementById("actionAdd"));
	        frmAdd.enctype = "multipart/form-data";       

	        for (var i = 0; i < idService; i++) {
                var id_product_ts = $("#id_product_ts-" + i).val();
	        	var product = $("#product_service-" + i).val();

	        	var other = "";
	        	if(product == "other"){
	        		other = $("#productservice_other_" + i).val();
	        	}
	        	var issues = $("#issues-" + i).val();
	        	var due_date = $("#due_date-" + i).val();
                var active = $("#productservice_active-"+i).val();

	        	var arr_issues = [];
	        	$('input[name="cbx_issue-'+i+'"]:checked').each(function() {
				   arr_issues.push(this.value);
				});

	        	//arr_productService.push([product, arr_sparepart, arr_issues, issues, due_date]);
	        	arr_productService.push([id_product_ts, product, arr_issues, issues, due_date, other, active]);
                arr_id_productts.push(id_product_ts);
	        }
            console.log(arr_productService);

	        var arr_jsonproductservice = JSON.stringify(arr_productService);
            var arr_jsonidproductts = JSON.stringify(arr_id_productts);

	        frmAdd.append('productservices', arr_jsonproductservice);
            frmAdd.append('idproductts', arr_jsonidproductts);

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
	        document.getElementById("addTechnicianSchedule").innerHTML = "UPLOADING...";
	    }
	    function completeHandler(event){
	        var hasil = JSON.parse(event.target.responseText);
	        console.log(hasil);

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
	            var route_to_list = "{{route('list_technician_schedule')}}";
	           	window.location.href = route_to_list;
	        }

	        document.getElementById("addTechnicianSchedule").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("addTechnicianSchedule").innerHTML = "SAVE";
	    }
	});
</script>
@endsection
