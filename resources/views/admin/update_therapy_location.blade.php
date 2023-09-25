<?php
$menu_item_page = "theraphy_service";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    button {
        background: #1bb1dc;
        border: 0;
        border-radius: 3px;
        padding: 8px 30px;
        color: #fff;
        transition: 0.3s;
    }

    .validation {
        color: red;
        font-size: 9pt;
    }

    input {
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
            <h3 class="page-title">Edit Therapy Location</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            aria-expanded="false">
                            Therapy Location
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Therapy Location
                    </li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('update_therapy_location', $therapyLocation->id) }}">
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
                                <label for="name">Name</label>
                                <input type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    maxlength="191"
                                    value="{{$therapyLocation->name}}"
                                    required />
                            </div>
                            <div class="form-group">
                                <label for="">Branch</label>
                                <select class="form-control" id="branch" name="branch_id" data-msg="Mohon Pilih Cabang">
                                    @foreach($branches as $branch)
                                        <option {{ $therapyLocation->branch_id == $branch['id'] ? 'selected' : '' }} value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
                                    @endforeach
                                </select>
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="province">Province</label>
                                <select class="form-control" id="province" name="province_id" data-msg="Mohon Pilih Provinsi">
                                    <option {{ $therapyLocation->province_id == null ? 'selected' : ''}} disabled value="" hidden>Pilihan Provinsi</option>
                                    <?php
	                                    $resultProvince = RajaOngkir::FetchProvince();
	                                    $resultProvince = $resultProvince['rajaongkir']['results'];
	                                    if (sizeof($resultProvince) > 0) {
	                                        foreach ($resultProvince as $value) {
	                                        	$selected = '';
	                                        	if($therapyLocation->province_id == $value['province_id']){
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
                                <select class="form-control" id="city" name="city_id" data-msg="Mohon Pilih Kota">
                                    @php
                                        $resultCity = RajaOngkir::FetchCity($therapyLocation->province_id);
                                        $resultCity = $resultCity['rajaongkir']['results'];
                                        $arrCity = [];
                                        $arrCity[0] = "";
                                        $arrCity[1] = "";
                                        if (sizeof($resultCity) > 0) {
                                            foreach ($resultCity as $value) {
                                                $terpilihNya = "";
                                                if ($therapyLocation->city_id == $value['city_id']) {
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
                            <div class="form-group">
                                <label for="subDistrict">Sub District</label>
                                <select class="form-control" id="subDistrict" name="subdistrict_id" data-msg="Mohon Pilih Kecamatan">
                                    @php
                                        $result = RajaOngkir::FetchDistrict($therapyLocation->city_id);
                                        $result = $result['rajaongkir']['results'];
                                        if(sizeof($result) > 0){
                                            foreach ($result as $value) {
                                                $terpilihNya = "";
                                                if(isset($therapyLocation->subdistrict_id)){
                                                    if($therapyLocation->subdistrict_id == $value['subdistrict_id']){
                                                        $terpilihNya = "selected";
                                                    }
                                                }

                                                echo "<option value=\"".$value['subdistrict_id']."\"".$terpilihNya.">".$value['subdistrict_name']."</option>";
                                            }
                                        }
                                    @endphp
                                </select>
                            </div>
                            <div class="form-group">
				                <label for="exampleTextarea1">Address</label>
				                <textarea class="form-control" id="address" name="address" rows="4" placeholder="Address">{{ $therapyLocation->address }}</textarea>
	              			</div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-gradient-primary">
                                    Update
                                </button>
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
	});

</script>
@endsection