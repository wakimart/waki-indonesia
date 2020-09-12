<?php
    $menu_item_page = "form";
    $menu_item_second = "formhomeservice";
?>
@extends('layouts.template')

@section('content')
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

<section id="intro" class="clearfix">
    <div class="container">
        <div class="row justify-content-center">
            <h2>FORM HOME SERVICE</h2>
        </div>

        @if(session('errors'))
            <div class="alert alert-danger">
                {{ session('errors') }}
            </div>
        @endif
        
        <div class="row justify-content-center">
            <form id="actionAdd" action="{{ route('store_home_service') }}" method="POST" role="form" class="contactForm col-md-9">
                @csrf
                <br>
                <div class="form-group">
                    <span>Type Customer</span>
                    <select id="type_customer" style="margin-top: 0.5em;" class="form-control" style="height: auto;" name="type_customer" value="" required>
                            <option value="Tele Voucher">Tele Voucher</option>
                            <option value="Tele Home Service">Tele Home Service</option>
                            <option value="Home Office Voucher">Home Office Voucher</option>
                            <option value="Home Voucher">Home Voucher</option>
                    </select>
                    <span class="invalid-feedback">
                        <strong></strong>
                    </span>
                </div>
                <div class="form-group">
                    <span>Type Home Service</span>
                    <select id="type_homeservices" style="margin-top: 0.5em;" class="form-control" style="height: auto;" name="type_homeservices" value="" required>
                            <option value="Home service">Home service</option>
                            <option value="Upgrade Member">Upgrade Member</option>
                    </select>
                    <span class="invalid-feedback">
                        <strong></strong>
                    </span>
                </div>
                <h5>Data Pelanggan</h5>
                <div class="form-group">
                    <input type="text" name="no_member" class="form-control" id="no_member" placeholder="No. Member (optional)"/>
                    <div class="validation"></div>
                    <span class="invalid-feedback">
                        <strong></strong>
                    </span>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Nama" required data-msg="Mohon Isi Nama" />
                    <div class="validation"></div>
                    <span class="invalid-feedback">
                        <strong></strong>
                    </span>
                </div>
                <div class="form-group">
                    <input type="number" class="form-control" name="phone" id="phone" placeholder="Nomor Telepon" required data-msg="Mohon Isi Nomor Telepon" />
                    <div class="validation"></div>
                    <span class="invalid-feedback">
                        <strong></strong>
                    </span>
                </div>
                <div class="form-group">
                    {{-- <input type="text" class="form-control" name="city" id="city" placeholder="Kota" required data-msg="Mohon Isi Kota" /> --}}
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
                    <select class="form-control" id="city" name="city" data-msg="Mohon Pilih Kota" required>
                        <option selected disabled value="">Pilihan Kota</option>
                    </select>
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <textarea class="form-control" name="address" rows="5" required data-msg="Mohon Isi Alamat" placeholder="Alamat"></textarea>
                    <div class="validation"></div>
                    <span class="invalid-feedback">
                        <strong></strong>
                    </span>
                </div>
                <br>
                <h5>Data CSO</h5>
                <div class="form-group">
                    <select class="form-control" id="branch" name="branch_id" data-msg="Mohon Pilih Cabang" required>
                        <option selected disabled value="">Pilihan Cabang</option>

                        @foreach($branches as $branch)
                            <option value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
                        @endforeach
                    </select>
                    <div class="validation"></div>
                    <span class="invalid-feedback">
                        <strong></strong>
                    </span>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="cso_id" id="cso" placeholder="Kode CSO" required data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase"/>
                    <div class="validation" id="validation_cso"></div>
                    <span class="invalid-feedback">
                        <strong></strong>
                    </span>
                </div>
                <div class="form-group">
                    <input type="number" class="form-control" name="cso_phone" id="cso_phone" placeholder="No. Telepon CSO" required data-msg="Mohon Isi Nomor Telepon" />
                    <div class="validation"></div>
                    <span class="invalid-feedback">
                        <strong></strong>
                    </span>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="cso2_id" id="cso2" placeholder="Kode Partner CSO (opsional)" style="text-transform:uppercase"/>
                    <div class="validation" id="validation_cso2"></div>
                    <span class="invalid-feedback">
                        <strong></strong>
                    </span>
                </div>

                <br>
                <h5>Waktu Home Service</h5>
                <div class="form-group">
                    <input type="date" class="form-control" name="date" id="date" placeholder="Tanggal Janjian" value="<?php echo date('Y-m-j'); ?>" required data-msg="Mohon Isi Tanggal" />
                    <div class="validation"></div>
                    <span class="invalid-feedback">
                        <strong></strong>
                    </span>
                </div>
                <div class="form-group">
                    <input type="time" class="form-control" name="time" id="time" placeholder="Jam Janjian" value="<?php echo date('H:i'); ?>" required data-msg="Mohon Isi Jam" min="10:00" max="20:00"/>
                    <div class="validation"></div>
                    <span class="invalid-feedback">
                        <strong></strong>
                    </span>
                </div>

                <div id="errormessage"></div>
                <div class="text-center"><button id="submit" type="submit" title="Send Message">Simpan Form Home Service</button></div>
            </form>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script>
    $(document).ready(function(){
        $("#cso, #cso2").on("input", function(){
            var txtCso = $(this).val();
            var obj = $('#validation_cso');
            if($(this)[0].id == "cso2"){
                obj = $('#validation_cso2');
            }
            $.get( '{{route("fetchCso")}}', { txt: txtCso })
            .done(function( result ) {
                if (result == 'true'){
                    obj.html('Kode CSO Benar');
                    obj.css('color', 'green');
                    $('#submit').removeAttr('disabled');
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
                        if(value['type'] == "Kota"){                            
                            arrCity += "<option value=\"Kota "+value['city_name']+"\">Kota "+value['city_name']+"</option>";
                        }
                    });
                    $( "#city" ).append(arrCity);
                }
            });
        });

        $('#submit').click(function(){
            var appointment = 
            $.ajax({
                type: 'POST',
                data: {
                    date: date
                },
                success: function(data){
                    console.log(data.data);
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        });
    });
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
    }
    else{
        alert("Input Success !!!");
        window.location.reload()
    }
}
</script>
@endsection
