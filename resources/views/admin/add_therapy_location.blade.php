<?php
$menu_item_page = "theraphy_service";
$menu_item_second = "add_therapy_location";
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
            <h3 class="page-title">Add Therapy Location</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            aria-expanded="false">
                            Therapy Location
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Add Therapy Location
                    </li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form id="actionAdd"
                            method="POST"
                            action="{{ route("store_therapy_location") }}">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    placeholder="Nama lokasi"
                                    maxlength="191"
                                    required />
                                <?php if ($errors->has("name")): ?>
                                    <div class="validation">
                                        <?php echo $errors->first("name"); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="">Branch</label>
                                <select class="form-control" id="branch" name="branch_id" data-msg="Mohon Pilih Cabang">
                                    <option {{ old('branch_id') == null ? 'selected' : ''}} disabled value="">Choose Branch</option>
                                    @foreach($branches as $branch)
                                        <option {{ old('branch_id') == $branch['id'] ? 'selected' : '' }} value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
                                    @endforeach
                                </select>
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="province">Province</label>
                                <select class="form-control" id="province" name="province_id" data-msg="Mohon Pilih Provinsi">
                                    <option {{ old('province_id') == null ? 'selected' : ''}} disabled value="" hidden>Pilihan Provinsi</option>
                                    <?php
	                                    $result = RajaOngkir::FetchProvince();
	                                    $result = $result['rajaongkir']['results'];
	                                    if (sizeof($result) > 0) {
	                                        foreach ($result as $value) {
	                                        	$selected = '';
	                                        	if(old('province_id') == $value['province_id']){
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
                                    <option selected disabled value="" hidden>Pilihan Kota</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="subDistrict">Sub District</label>
                                <select class="form-control" id="subDistrict" name="subdistrict_id" data-msg="Mohon Pilih Kecamatan">
                                    <option selected disabled value="" hidden>Pilihan Kecamatan</option>
                                </select>
                            </div>
                            <div class="form-group">
				                <label for="exampleTextarea1">Address</label>
				                <textarea class="form-control" id="address" name="address" rows="4" placeholder="Address">{{ old('address') }}</textarea>
	              			</div>
                            <div class="form-group">
                                <button id=""
                                    type="submit"
                                    class="btn btn-gradient-primary">
                                    Save
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
