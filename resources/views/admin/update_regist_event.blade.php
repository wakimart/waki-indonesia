<?php
$menu_item_page = "registerevent";
$menu_item_second = "add_deliveryorder";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="{{ asset("css/lib/select2/select2-bootstrap4.min.css") }}" />
<style type="text/css">
    #intro {
        padding-top: 2em;
    }

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

    input, select, textarea{
        border-radius: 0 !important;
        box-shadow: none !important;
        border: 1px solid #dce1ec !important;
        font-size: 14px !important;
    }

    #regForm {
        background-color: #ffffff;
        margin: 100px auto;
        padding: 40px;
        width: 70%;
        min-width: 300px;
    }

    /* Style the input fields */
    input {
        padding: 10px;
        width: 100%;
        font-size: 17px;
        font-family: Raleway;
        border: 1px solid #aaaaaa;
    }

    /* Mark input boxes that gets an error on validation: */
    input.invalid {
        background-color: #ffdddd;
    }

    .select2-selection__rendered {
        line-height: 45px !important;
    }

    .select2-container .select2-selection--single {
        height: 45px !important;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
      <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Edit Registration WAKi Di Rumah Aja</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#"
                            aria-expanded="false"
                            aria-controls="registerevent-dd">
                            Registration Event
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Registration
                    </li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('update_regispromo', $registration_promotion->id) }}" method="POST">
                            {{csrf_field()}}
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
                                <label><h2>Data Pelanggan</h2></label>
                                <br/>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="first_name">First Name</label>
                                        <input type="text"
                                            class="form-control"
                                            id="first_name"
                                            name="first_name"
                                            placeholder="First Name"
                                            required value="{{ $registration_promotion->first_name }}"/>
                                        <div class="validation"></div>
                                    </div>
                                    <div class="col-6">
                                        <label for="last_name">Last Name</label>
                                        <input type="text"
                                            class="form-control"
                                            id="last_name"
                                            name="last_name"
                                            placeholder="Last Name"
                                            required value="{{ $registration_promotion->last_name }}"/>
                                        <div class="validation"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control"
                                    id="address"
                                    name="address"
                                    rows="4"
                                    placeholder="Full Address">{{ $registration_promotion->address }}</textarea>
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="province">Province</label>
                                <select class="form-control" style="color: black"
                                    id="province"
                                    name="province_id"
                                    data-msg="Mohon Pilih Provinsi"
                                    required>
                                    <option selected disabled value="">
                                        Pilihan Provinsi
                                    </option>

                                    <?php
                                    $result = RajaOngkir::FetchProvince();
                                    $result = $result['rajaongkir']['results'];
                                    if (sizeof($result) > 0) {
                                        foreach ($result as $value) {
                                            if ( $value->province_id == $registration_promotion->province_id ) {
                                                $selected = "selected";
                                            } else {
                                                $selected = "";
                                            }

                                            echo '<option value="' . $value['province_id'] . '"'.$selected.'>'
                                                . $value['province']
                                                . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="city">City</label>
                                <select class="form-control" style="color: black"
                                    id="city"
                                    name="district_id"
                                    data-msg="Mohon Pilih Kota"
                                    required>
                                    <option selected disabled value="">
                                        {{ !empty($registration_promotion->district) ? $registration_promotion->district->type ." ". $registration_promotion->district->city_name : "Pilihan Kota" }}
                                    </option>
                                </select>
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="subDistrict">Sub District</label>
                                <select class="form-control" style="color:black"
                                    id="subDistrict"
                                    name="subdistrict_id"
                                    data-msg="Mohon Pilih Kecamatan"
                                    required>
                                    <option selected disabled value="">
                                        {{ !empty($registration_promotion->subdistrict) ? $registration_promotion->subdistrict->subdistrict_name : "Pilihan Kecamatan" }}
                                    </option>
                                </select>
                                <div class="validation"></div>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="number"
                                    class="form-control"
                                    id="phone"
                                    name="phone"
                                    placeholder="Phone Number"
                                    required value="{{ $registration_promotion->phone }}"/>
                                <div class="validation"></div>
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="text"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    placeholder="Email Address"
                                    required value="{{ $registration_promotion->email }}"/>
                                <div class="validation"></div>
                            </div>

                            <div id="errormessage"></div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-gradient-primary mr-2">Update</button>
                                <button class="btn btn-light" onclick="location.href=`{{route('list_regispromo')}}`" type="button">Cancel</button>
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
<script type="application/javascript">
    $(document).ready(function () {

        $("#province").on("change", function () {
            var id = $(this).val();
            $("#city").html("");

            $.get( '{{ route("fetchCity", ['province' => ""]) }}/'+id )
                .done(function( result ) {
                    result = result['rajaongkir']['results'];
                    var arrCity = "<option selected disabled value=\"\">Pilihan Kota</option>";

                    if (result.length > 0) {
                        $.each( result, function (key, value) {
                            arrCity += `<option value="${value['city_id']}">${value["type"]} ${value['city_name']}</option>`;
                        });

                        $("#city").append(arrCity);
                    }
                });
        });

        $("#city").on("change", function () {
            var id = $(this).val();
            $("#subDistrict").html("");

            $.get('{{ route("fetchDistrict", ['city' => ""]) }}/' + id)
                .done(function (result) {
                    result = result['rajaongkir']['results'];
                    var arrSubDistsrict = '<option selected disabled value="">Pilihan Kecamatan</option>';

                    if (result.length > 0) {
                        $.each(result, function (key, value) {
                            arrSubDistsrict += `<option value="${value['subdistrict_id']}">${value['subdistrict_name']}</option>`;
                        });

                        $("#subDistrict").append(arrSubDistsrict);
                    }
                });
        });

        $('.alert').fadeOut('slow').delay(35000);
    });
</script>
@endsection
