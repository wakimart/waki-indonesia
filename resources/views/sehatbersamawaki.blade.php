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
    table{
        width: 95%;
        margin:0 auto;
        margin-bottom: 1em;
        font-size: 16px;
    }
    table thead{
        background-color: #8080801a;
        text-align: center;
    }
    table td{
        border: 0.5px #8080801a solid;
        padding: 0.5em;
    }

    .produk td{
        text-transform: uppercase;
        font-weight: 600;
    }

    .right{
        text-align: right;
    }
    .pInTable{
        margin-bottom: 6pt !important;
        font-size: 14px;
    }


    @media (min-width: 576px) { 
		#desktop {
            display: block;
        }
        #smmobile {
            display: none;
        }

	}

    @media (max-width: 575px) { 
		#desktop {
            display: none;
        }
        #smmobile {
            display: block;
        }

        #smmobile .no-gutters {
            margin-right: auto;
            margin-left: auto;
            padding-left: 15px;
            padding-right: 15px;
        }
	}

	@media (min-width: 768px) { 
		.table-responsive::-webkit-scrollbar {
            display: none;
        }
	}


</style>


<section id="intro" class="clearfix">
    <div class="container">
        <div class="row justify-content-center no-gutters">
            <h2 style="margin: 0 5px 0 5px;">
               Program Refrensi Sehat Bersama WAKi
            </h2>
        </div>

        <div id="smmobile" class="row justify-content-center mt-5 pt-3 no-gutters">
            <div class="col-12 no-gutters">
                <div class="row">
                    <div class="col-6">
                        <b><p>Cabang</p></b>
                    </div>
                    <div class="col-6">
                        <p>: {{$submission->branch['code']}} - {{$submission->branch['name']}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <b><p>Tanggal</p></b>
                    </div>
                    <div class="col-6">
                        <p>: {{$submission['created_at']}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <b><p>Nama Customer</p></b>
                    </div>
                    <div class="col-6">
                        <p>: {{$submission['name']}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <b><p>Alamat</p></b>
                    </div>
                    <div class="col-6">
                        <p>: {{$submission['address']}} <br>
                            {{$submission->province_obj['province']}}, {{$submission->city_obj['city_name']}}, {{$submission->district_obj['subdistrict_name']}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <b><p>Telp. / HP</p></b>
                    </div>
                    <div class="col-6">
                        <p>: {{$submission['phone']}}</p>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-6">
                        <b><p>Produk WAKi</p></b>
                    </div>
                    <div class="col-6">
                        <p>: WKA2021 ( AIR WASHER )</p>
                    </div>
                </div> --}}
                <div class="row">
                    <div class="col-6">
                        <b><p>No. MPC</p></b>
                    </div>
                    <div class="col-6">
                        <p>: {{$submission['no_mpc']}}</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="desktop" class="row justify-content-center mt-5 pt-3 no-gutters">
            <div class="col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-md-2 col-sm-2">
                        <b><p>Cabang</p></b>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <p>: {{$submission->branch['code']}} - {{$submission->branch['name']}}</p>
                    </div>
                    <div class="col-md-2 col-sm-2">
                        <b><p>Tanggal</p></b>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <p>: {{$submission['created_at']}}</p>
                    </div>
                    <div class="col-md-2 col-sm-2">
                        <b><p>Nama Customer</p></b>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <p>: {{$submission['name']}}</p>
                    </div>
                    <div class="col-md-2 col-sm-2">
                        <b><p>No. MPC</p></b>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <p>: {{$submission['no_mpc']}}</p>
                    </div>
                    <div class="col-md-2 col-sm-2">
                        <b><p>Alamat</p></b>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <p>: {{$submission['address']}} <br>
                            {{$submission->province_obj['province']}}, {{$submission->city_obj['city_name']}}, {{$submission->district_obj['subdistrict_name']}}</p>                    </div>
                    <div class="col-md-2 col-sm-2">
                        <b><p>Telp. / HP</p></b>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <p>: {{$submission['phone']}}</p>
                    </div>
                    {{-- <div class="col-md-2 col-sm-2">
                        <b><p>Produk WAKi</p></b>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <p>: WKA2021 ( AIR WASHER )</p>
                    </div> --}}
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-3 no-gutters">
                <div class="table-responsive">
                    <table class="table table-borderless produk" style="border:1px #ccc solid;">
                        <tr class="text-center" style="border-bottom: 1px solid #ccc;">
                            <th colspan="3">Produk Hadiah</th>
                        </tr>
                        <tr>
                            <td width="30%"><li>FACIAL MASSAGE</li></td>
                            <td width="30%"><li>VACCU CUPPING</li></td>
                            <td width="30%"><li>SLIPPER MASSAGE</li></td>
                        </tr>
                        <tr>
                            <td width="30%"><li>MULTI MASSAGE</li></td>
                            <td width="30%"><li>NIFIR UNDERWEAR</li></td>
                            <td width="30%"><li>E-VOUCHER Rp. 270.000,-</li></td>
                        </tr>
                    </table>
                </div>
        </div>

        <div class="row justify-content-center mt-3 no-gutters">
          <table class="col-md-12">
              <tr>
                  <td>
                        <p class="pInTable text-center">Isi produk yang dipilih di kolom yang tersedia. Jika 1 refrensi berhasil dikunjungi,
                                            maka preferensi berhak mendapatkan 1 produk.
                        </p>
                  </td>
              </tr>
          </table>
        </div>

        <div class="row justify-content-center no-gutters">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr class='text-center'>
                        <th>No</th>
                        <th>Nama</th>
                        <th>No. Tlp /HP</th>
                        <th>Kota</th>
                        <th>Produk yang dipilih (Pereferensi)</th>
                        <th>Tanggal Appointment</th>
                    </tr>
                    @foreach($submission->reference as $keyNya => $referenceNya)
                        @php
                            $Reference_HS = $referenceNya->reference_souvenir->fetch_hs();
                            $totReference_HS = 0;
                            if($Reference_HS != null){
                                $totReference_HS = sizeof($Reference_HS);
                            }
                        @endphp

                        <tr>
                            <td rowspan="{{ $totReference_HS > 0 ? $totReference_HS : 1 }}">{{ $keyNya+1 }}</td>
                            <td rowspan="{{ $totReference_HS > 0 ? $totReference_HS : 1 }}">{{ $referenceNya['name'] }}</td>
                            <td rowspan="{{ $totReference_HS > 0 ? $totReference_HS : 1 }}">{{ $referenceNya['phone'] }}</td>
                            <td rowspan="{{ $totReference_HS > 0 ? $totReference_HS : 1 }}">{{ $referenceNya->getCityName() }}</td>
                            <td rowspan="{{ $totReference_HS > 0 ? $totReference_HS : 1 }}">{{ $referenceNya->reference_souvenir->souvenir['name'] }}</td>
                            <td>{{ $totReference_HS > 0 ? $Reference_HS[0]['appointment'] : "-" }}</td>
                        </tr>
                        @for($i = 1; $i < $totReference_HS; $i++)
                            <tr>
                                <td>{{ $Reference_HS[$i]['appointment'] }}</td>
                            </tr>
                        @endfor
                    @endforeach
                </table>
            </div>
        
            <div class="table-responsive sk">
                <table class="table table-bordered">
                    <tr>
                        <th>Syarat dan ketentuan :</th>
                    </tr>
                    <tr>
                        <td>
                            <p class="pInTable">1. Sudah mempunyai produk WAKi minimum WK2079 (di atas 21.900).</p>
                            <p class="pInTable">2. Minimal usia 35 tahun.</p>
                            <p class="pInTable">3. Harus sudah menjadi member WAKimart.</p>
                            <p class="pInTable">4. Memberikan screenshot bukti appointment (share Link) telah dijadwalkan untuk kunjungan.</p>
                            <p class="pInTable">5. Undangan Refrensi Salam Perkenalan WAKimart harus diisi lengkap dan benar adanya.</p>
                            <p class="pInTable">6. Voucher belanja dan produk tidak dapat ditukarkan dalam bentuk tunai.</p>
                            <p class="pInTable">7. Produk yang dipilih harus 6 produk pertama, berlaku kelipatan.</p>
                            <p class="pInTable">8. Berlaku setelah mengikuti 3 hari coba produk di rumah.</p>
                            <p class="pInTable">9. Syarat dan ketentuan dapat berubah tanpa pemberitahuan sebelumnya.</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
