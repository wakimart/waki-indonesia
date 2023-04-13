<?php
    $menu_item_page = "theraphy_service";
    $menu_item_second = "list_theraphy_service";
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
	.check label{ width: 25em;	}
	table {
        margin: 1em;
        font-size: 14px;
    }
    table thead {
        background-color: #8080801a;
        text-align: center;
    }
    table td {
        border: 0.5px #8080801a solid;
        padding: 0.5em;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
  	<div class="content-wrapper">
    	<div class="page-header">
      		<h3 class="page-title">Edit Therapy</h3>
      		<nav aria-label="breadcrumb">
	        	<ol class="breadcrumb">
	          		<li class="breadcrumb-item"><a data-toggle="collapse" href="#theraphy_service-dd" aria-expanded="false" aria-controls="theraphy_service-dd">Therapy</a></li>
	          		<li class="breadcrumb-item active" aria-current="page">Edit Therapy</li>
	        	</ol>
      		</nav>
    	</div>
	    <div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">
	            		<form class="forms-sample" method="POST" action="{{ route('update_theraphy_service', $theraphyService->id) }}">
							{{ csrf_field() }}
                            {{method_field('PUT')}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="">Therapy Type</label>
                                <select class="form-control" id="type" name="type" data-msg="Mohon Pilih Type" required >
                                    <option value="sehat_bersama" {{$theraphyService->type == 'sehat_bersama' ? 'selected' : ''}}>Program Happy Sehat Bersama WAKi</option>
                                    <option value="free" {{$theraphyService->type == 'free' ? 'selected' : ''}}>Free Therapy</option>
                                </select>
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="">Branch</label>
                                <select class="form-control" id="branch" name="branch_id" data-msg="Mohon Pilih Cabang" required>
                                    @foreach($branches as $branch)
                                        <option {{ $theraphyService->branch_id == $branch['id'] ? 'selected' : '' }} value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
                                    @endforeach
                                </select>
                                <div class="validation"></div>
                            </div>
							<div class="form-group">
								<label for="">Register Date</label>
								<input type="date" class="form-control" name="registered_date" id="registered_date" placeholder="Tanggal Daftar" value="{{ $theraphyService->registered_date }}" required data-msg="Mohon Isi Tanggal" />
							</div>
                            <div class="form-group therapy_type {{ $theraphyService->type == 'free' ? 'd-none' : ''}}">
                                <label for="">Expired Date</label>
                                <input type="date" readonly="" class="form-control" name="expired_date" id="expired_date" placeholder="Tanggal Berlaku" value="{{$theraphyService->expired_date}}"/>
                            </div>
                            <div class="form-group therapy_type {{ $theraphyService->type == 'free' ? 'd-none' : ''}}">
                                <label for="">Therapy Location</label>
                                <select class="form-control" id="therapy_location" name="therapy_location_id" data-msg="Mohon Pilih Therapy Location"></select>
                                <div class="validation"></div>
                            </div>
	              			<div class="form-group">
				                <label for="">Name</label>
				                <input type="text" class="form-control" id="name" name="name" placeholder="Name" required value="{{ $theraphyService->name }}">
	              			</div>
	              			<div class="form-group">
				                <label for="">Phone Number</label>
				                <input type="number" class="form-control" id="phone" name="phone" placeholder="Phone Number" required value="{{ $theraphyService->phone }}">
	              			</div>
	              			<div class="form-group">
                                <label for="province">Province</label>
                                <select class="form-control" id="province" name="province_id" data-msg="Mohon Pilih Provinsi" required>
                                    <option {{ $theraphyService->province_id == null ? 'selected' : ''}} disabled value="" hidden>Pilihan Provinsi</option>
                                    <?php
	                                    $resultProvince = RajaOngkir::FetchProvince();
	                                    $resultProvince = $resultProvince['rajaongkir']['results'];
	                                    if (sizeof($resultProvince) > 0) {
	                                        foreach ($resultProvince as $value) {
	                                        	$selected = '';
	                                        	if($theraphyService->province_id == $value['province_id']){
	                                        		$selected = 'selected';
	                                        	}

	                                            echo '<option value="'
	                                                . $value['province_id']
	                                                . '" '
	                                                . $selected
	                                                .'>'
	                                                . $value['province']
	                                                . "</option>";
	                                        }
	                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="city">City</label>
                                <select class="form-control" id="city" name="city_id" data-msg="Mohon Pilih Kota" required>
                                    @php
                                        $resultCity = RajaOngkir::FetchCity($theraphyService->province_id);
                                        $resultCity = $resultCity['rajaongkir']['results'];
                                        $arrCity = [];
                                        $arrCity[0] = "";
                                        $arrCity[1] = "";
                                        if (sizeof($resultCity) > 0) {
                                            foreach ($resultCity as $value) {
                                                $terpilihNya = "";
                                                if ($theraphyService->city_id == $value['city_id']) {
                                                    $terpilihNya = "selected";
                                                }

                                                if ($value['type'] == "Kabupaten") {
                                                    $arrCity[0] .= "<option value=\"".$value['city_id']."\"".$terpilihNya.">".$value['type']." ".$value['city_name']."</option>";
                                                } else {
                                                    $arrCity[1] .= "<option value=\"".$value['city_id']."\"".$terpilihNya.">".$value['type']." ".$value['city_name']."</option>";
                                                }
                                            }
                                            echo $arrCity[0];
                                            echo $arrCity[1];
                                        }
                                    @endphp
                                </select>
                            </div>
                            
	              			<br>
	              			<div class="form-group">
	              				<h3>Keterangan Therapy</h3>
	              			</div>
	              			<div class="table-responsive">
                                <table class="col-md-12" width="max-content">
                                    <thead>
                                        <td>No.</td>
                                        <td></td>
                                        <td>Ya</td>
                                        <td>Tidak</td>
                                        <td>Keterangan</td>
                                    </thead>
                                    @foreach($meta_default as $idxNya => $listMeta)
                                        @php
                                            $checked = '';
                                            $desc = '';
                                        @endphp
                                        @foreach($theraphyService->meta_condition as $meta)
                                            @foreach($meta as $key => $val)
                                                @php 
                                                    if($key == $listMeta){
                                                        $checked = $val[0];
                                                        $desc = $val[1];
                                                    }
                                                @endphp
                                            @endforeach
                                        @endforeach
                                    	<tr>
                                    		<td class="col-1">{{ $idxNya < 6 ? ($idxNya+1).'.' : '' }}</td>
                                    		<td class="col-5 {{ $idxNya > 5 ? 'text-right' : '' }}" {{ $idxNya == 5 ? 'colspan=4' : '' }}>{{ $listMeta }}</td>
                                    		@if($idxNya != '5')
	                                    		<td class="col-1 text-center">
	                                    			<input style="width: 1.3em; height: 1.3em;" type="radio" name="rdaChoose-{{ $idxNya }}" value="1" required="" {{ $checked == '1' ? 'checked' : '' }}>
	                                    		</td>
	                                    		<td class="col-1 text-center">
	                                    			<input style="width: 1.3em; height: 1.3em;" type="radio" name="rdaChoose-{{ $idxNya }}" value="0" {{ $checked == '0' ? 'checked' : '' }}>
	                                    		</td>
	                                    		<td class="col-4"><textarea class="form-control" name="desc-{{ $idxNya }}" rows="2" placeholder="Keterangan (opsional)">{{ $desc }}</textarea></td>
                                    		@endif
                                    	</tr>
                                    @endforeach
                            	</table>
	              			</div>

	              			<br>
	              			<div class="form-group">
	              				<button id="addService" type="submit" class="btn btn-gradient-primary mr-2">Update</button>
	              				<button class="btn btn-light">Cancel</button>
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
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script type="text/javascript">
    
    getTherapyLocationByBranch()
	
    $(document).ready(function(){
		$("#province").on("change", function () {
	        const id = $(this).val();
	        $("#city").html("");

	        $.get('{{ route("fetchCity", ['province' => ""]) }}/' + id)
            .done(function (result) {
                result = result['rajaongkir']['results'];
                let arrCity = "<option selected disabled value=\"\" hidden>Pilihan Kota</option>";

                if (result.length > 0) {
                    $.each(result, function (key, value) {
                        if (value['type'] == "Kabupaten") {
                            arrCity += `<option value="${value['city_id']}">Kabupaten ${value['city_name']}</option>`;
                        }

                        if (value['type'] == "Kota") {
                            arrCity += `<option value="${value['city_id']}">Kota ${value['city_name']}</option>`;
                        }
                    });

                    $( "#city" ).append(arrCity);
				    @if(old('province_id'))
	                    $( "#city" ).val({{ old('city_id') }}).trigger('change');
	                @endif
                }
            });
	    });

	    $("#city").on("change", function () {
	        const id = $(this).val();
	        $("#subDistrict").html("");

	        $.get('{{ route("fetchDistrict", ['city' => ""]) }}/' + id)
            .done(function (result) {
                result = result['rajaongkir']['results'];
                let arrSubDistsrict = "<option selected disabled value=\"\" hidden>Pilihan Kecamatan</option>";

                if (result.length > 0) {
                    $.each( result, function (key, value) {
                        arrSubDistsrict += "<option value=\"" + value['subdistrict_id'] + "\">" + value['subdistrict_name'] + "</option>";
                    });

                    $("#subDistrict").append(arrSubDistsrict);
				    @if(old('province_id'))
	                    $( "#subDistrict" ).val({{ old('subdistrict_id') }});
					@endif
                }
            });
	    });

	    @if(old('province_id'))
			$("#province").trigger('change');
		@endif

        $("#type").on("change", function(){
            whenTypeChange()
        });

        $('#branch').on('change', function () {
            getTherapyLocationByBranch()
        })
	});

    function getTherapyLocationByBranch() {
        $('#therapy_location').html('')
        var url = '{{ route("get_therapy_location_data_by_branch", ":id") }}'
        url = url.replace(':id', $('#branch').val());
        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            success: function(data){
                var html = ''
                for(var i=0; i<data.length; i++){
                    html += `<option value="${data[i]['id']}">${data[i]['name']}</option>`
                }
                $('#therapy_location').html(html)
            }, error: function(){
                alert("there's something wrong, please call IT")
            }
        })
    }

    function whenTypeChange(){
        if($('#type').val() == "free"){
            $(".therapy_type").addClass("d-none");
            $(".therapy_type :input").each(function(){
                $('#type')[0].removeAttr("required");
            });
        }else{
            $(".therapy_type").removeClass("d-none");
            $(".therapy_type :input").each(function(){
                $('#type')[0].prop("required");
            });
        }
    }

    var is_from_submission_reference = "{{isset($_GET['reference_id']) ? $_GET['reference_id'] : ''}}"
    if(is_from_submission_reference !== ''){
        $('#type').val('sehat_bersama').change()
        $('#type').prop('disabled', true)
        whenTypeChange()
    }

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) 
            month = '0' + month;
        if (day.length < 2) 
            day = '0' + day;

        return [year, month, day].join('-');
    }

    $('#registered_date').change(function() {
        var registeredDate = new Date($(this).val())
        registeredDate.setDate(registeredDate.getDate()+24)
        $('#expired_date').val(formatDate(registeredDate))
    });
</script>
@endsection
