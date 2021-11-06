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
               HEALTHY LOVE SHARING PROGRAM WITH WAKI
            </h2>
        </div>
        <h5 style="text-align: center;">{{ $submission['code'] }}</h5>

        <div id="smmobile" class="row justify-content-center mt-5 pt-3 no-gutters">
            <div class="col-12 no-gutters">
                <div class="row">
                    <div class="col-6">
                        <b><p>Branch</p></b>
                    </div>
                    <div class="col-6">
                        <p>: {{$submission->branch['code']}} - {{$submission->branch['name']}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <b><p>Date</p></b>
                    </div>
                    <div class="col-6">
                        <p>: {{$submission['created_at']}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <b><p>Customer Name</p></b>
                    </div>
                    <div class="col-6">
                        <p>: {{$submission['name']}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <b><p>Address</p></b>
                    </div>
                    <div class="col-6">
                        <p>: {{$submission['address']}} <br>
                            {{$submission->province_obj['province']}}, {{$submission->city_obj['city_name']}}, {{$submission->district_obj['subdistrict_name']}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <b><p>Phone Number</p></b>
                    </div>
                    <div class="col-6">
                        <p>: {{$submission['phone']}}</p>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-6">
                        <b><p>WAKi Product</p></b>
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
                        <p>: {{$submission['no_member']}}</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="desktop" class="row justify-content-center mt-5 pt-3 no-gutters">
            <div class="col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-md-2 col-sm-2">
                        <b><p>Branch</p></b>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <p>: {{$submission->branch['code']}} - {{$submission->branch['name']}}</p>
                    </div>
                    <div class="col-md-2 col-sm-2">
                        <b><p>Date</p></b>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <p>: {{$submission['created_at']}}</p>
                    </div>
                    <div class="col-md-2 col-sm-2">
                        <b><p>Customer Name</p></b>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <p>: {{$submission['name']}}</p>
                    </div>
                    <div class="col-md-2 col-sm-2">
                        <b><p>No. MPC</p></b>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <p>: {{$submission['no_member']}}</p>
                    </div>
                    <div class="col-md-2 col-sm-2">
                        <b><p>Address</p></b>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <p>: {{$submission['address']}} <br>
                            {{$submission->province_obj['province']}}, {{$submission->city_obj['city_name']}}, {{$submission->district_obj['subdistrict_name']}}</p>
                    </div>
                    <div class="col-md-2 col-sm-2">
                        <b><p>Phone Number</p></b>
                    </div>
                    <div class="col-md-4 col-sm-4">
                        <p>: {{$submission['phone']}}</p>
                    </div>
                    {{-- <div class="col-md-2 col-sm-2">
                        <b><p>WAKi Product</p></b>
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
                            <th colspan="3">Prize Product</th>
                        </tr>
                        <tr>
                            @php
                                $j = 0;
                            @endphp

                            @while(isset($souvenirs[$j]) && $j < 3)
                                <td width="30%"><li>{{ $souvenirs[$j]['name'] }}</li></td>
                                @php $j++; @endphp
                            @endwhile
                        </tr>
                        <tr>
                            @while(isset($souvenirs[$j]) && $j < 6)
                                <td width="30%"><li>{{ $souvenirs[$j]['name'] }}</li></td>
                                @php $j++; @endphp
                            @endwhile
                        </tr>
                    </table>
                </div>
        </div>

        <div class="row justify-content-center mt-3 no-gutters">
          <table class="col-md-12">
              <tr>
                  <td>
                        <p class="pInTable text-center">Fill the selected product in the column provided. If 1 reference is successfully visited,
                                             then the preference is entitled to 1 product.
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
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>City</th>
                        <th>Selected product (Reference)</th>
                        <th>Appointment Date</th>
                        <th>Reference Status</th>
                        <th>Status Product</th>
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
                            <td rowspan="{{ $totReference_HS > 0 ? $totReference_HS : 1 }}"><a href="{{ $referenceNya->reference_souvenir['status'] == "success" ? 'http://wakimart.co.id/?success_voucher=' : ''}}">{{ ucfirst($referenceNya->reference_souvenir['status']) }}</a></td>
                            <td rowspan="{{ $totReference_HS > 0 ? $totReference_HS : 1 }}">{{ ucfirst($referenceNya->reference_souvenir['delivery_status']) }}</td>
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
                        <th>Terms and conditions :</th>
                    </tr>
                    <tr>
                        <td>
                            <p class="pInTable">1. Must already be a WAKimart member.</p>
                            <p class="pInTable">2. Minimum age 35 years per family of friends or relatives.</p>
                            <p class="pInTable">3. Provide a screenshot of proof of the appointment (share link) that has been scheduled for a visit (minimum 1x).</p>
                            <p class="pInTable">4. The Healthy Program with WAKi must be filled out completely and correctly.</p>
                            <p class="pInTable">5. Shopping vouchers and products cannot be exchanged for cash.</p>
                            <p class="pInTable">6. Purchases using the E-Voucher must be more than the value of the E-Voucher.</p>
                            <p class="pInTable">7. Terms and conditions are subject to change without prior notice.</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
