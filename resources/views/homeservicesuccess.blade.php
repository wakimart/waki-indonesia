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
        margin: 1em;
        font-size: 14px;
    }
    table thead{
        background-color: #8080801a;
        text-align: center;
    }
    table td{
        border: 0.5px #8080801a solid;
        padding: 0.5em;
    }
    .right{
        text-align: right;
    }
    .pInTable{
        margin-bottom: 6pt !important;
        font-size: 14px;
    }
</style>


<section id="intro" class="clearfix">
    <div class="container">
        <div class="row justify-content-center">
            <h2 style="margin: 0 5px 0 5px;">
                @if($homeService->type_homeservices != "Soft Launching WAKimart Apps")
                    REGISTRASI {{strtoUpper($homeService->type_homeservices)}} BERHASIL
                @else
                    REGISTRATION "Invitation Soft Launching WAKimart Apps" SUCCESSFUL!
                @endif
            </h2>
        </div>

        <div class="row justify-content-center">
          <table class="col-md-12">
              <tr>
                  <td>
                      <p class="pInTable">Thank you, dear sir/madam, for your support and encouragement
                       given to WAKi Philippines in order to develop and become better in the future.</p>
                        @if($homeService->type_homeservices == "Home service")
                          <p class="pInTable">The purpose of Home Service is to close the relationship between
                             ladies and gentlemen and WAKi and so that you understand better how to use it
                             WAKi the product to achieve a better impression.</p>
                            
                            
                          <p class="pInTable">The WAKi Home Service team will contact you first
                             before leaving for your place. For more information
                             or changes to the home service schedule, you can contact WAKi
                             Home Service Department is back at number (+639989888899) or
                            {{ $homeService->cso['name'] }}, {{ $homeService['cso_phone'] }}, {{ $homeService->branch['code'] }} - {{ $homeService->branch['name'] }}.</p>
                        @elseif($homeService->type_homeservices == "Soft Launching WAKimart Apps")
                            <p class="pInTable">Dear Sir/Madam, have the opportunity to become a member
                             The following WAKimart with a "Greetings of Introduction" voucher
                             without any charge. The purpose of the Invitation Invitation Soft Launching WAKimart Apps is to make it easier for you
                             in the process of activating the "Greetings of Introduction" voucher and to better understand
                             WAKimart member benefits well.</p>
                            
                            
                          <p class="pInTable">The WAKi Soft Launching WAKimart Apps team will contact you first
                             before leaving for your place. For more information
                             or changes to the schedule for the Invitation Soft Launching of WAKimart Apps, please contact WAKi
                             Soft Launching of WAKimart Apps Department is back at number (+639989888899) or
                            {{ $homeService->cso['name'] }}, {{ $homeService['cso_phone'] }}, {{ $homeService->branch['code'] }} - {{ $homeService->branch['name'] }}.</p>
                        @else
                            <p class="pInTable">The purpose of {{$homeService->type_homeservices}} is to close the relationship between
                             Sir/Madam with WAKi and so that you can understand better
                             WAKi member benefits to achieve a better impression.</p>
                            
                            
                          <p class="pInTable">Team WAKi {{$homeService->type_homeservices}} will contact first
                             before leaving for your place. For more information
                             or schedule changes {{$homeService->type_homeservices}}, can contact WAKi
                             {{$homeService->type_homeservices}} Department back at (+639989888899) or
                            {{ $homeService->cso['name'] }}, {{ $homeService->cso['phone'] }}, {{ $homeService->branch['code'] }} - {{ $homeService->branch['name'] }}.</p>
                        @endif
                  </td>
              </tr>
          </table>
        </div>

        <div class="row justify-content-center">
            <table class="col-md-12">
                <thead>
                    <td colspan="2">Customer Information </td>
                </thead>
                <tr>
                    <td>No. Member : </td>
                    <td>{{ $homeService['no_member'] }}</td>
                </tr>
                <tr>
                    <td>Name : </td>
                    <td>{{ $homeService['name'] }}</td>
                </tr>
                <tr>
                    <td>Phone Number : </td>
                    <td>{{ $homeService['phone'] }}</td>
                </tr>
                <tr>
                    <td>Address : </td>
                    <td>{{ $homeService['address'] }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>{{ $homeService['district'][0]['province'] }}, {{ $homeService['district'][0]['kota_kab'] }}, {{ $homeService['district'][0]['subdistrict_name'] }}</td>
                </tr>
            </table>
            @foreach ($samephones as $key => $samephone)
                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Appointment {{$key + 1}}</td>
                    </thead>

                    @php
                        $dt = new DateTime($samephone['appointment']);
                    @endphp

                    <tr>
                        <td>Date : </td>
                        <td>{{ $dt->format('j/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td>Time : </td>
                        <td>{{ $dt->format('H:i') }}</td>
                    </tr>

                </table>
            @endforeach
            <table class="col-md-12">
                <thead>
                    <td colspan="2">WAKi Home Service Policy</td>
                </thead>
                <tr>
                    <td>
                        @if($homeService->type_homeservices == "Home service")
                          <p class="pInTable">1. Home Service from WAKi is free of charge.</p>
                           <p class="pInTable">2. Fees will be charged to consumers if there are spare parts or damage
                             beyond MPC approval / Warranty.</p>
                           <p class="pInTable">3. The comfort and safety of our consumers is our first priority. If
                             anything can contact us via Home Service department:
                             +639989888899.</p>
                           <p class="pInTable">4. This form will be given a carbon copy (CC) to the Customer, Officer, Branch Manager,
                             Home Service Department.</p>
                        @elseif($homeService->type_homeservices == "Soft Launching WAKimart Apps")
                          <p class="pInTable">1. Soft Launching of WAKimart Apps from WAKi is free of charge.</p>
                           <p class="pInTable">2. Consumers will benefit in accordance with applicable regulations.</p>
                           <p class="pInTable">3. The comfort and safety of our consumers is our first priority. If
                             there is something you can contact us through the Soft Launching WAKimart Apps department:
                             +6399898888899.</p>
                           <p class="pInTable">4. This form will be given a carbon copy (CC) to the Customer, Officer, Branch Manager,
                             Soft Launching of WAKimart Apps Department.</p>
                        @else
                          <p class="pInTable">1. Member upgrade from WAKi is free of charge.</p>
                           <p class="pInTable">2. Consumers will benefit in accordance with applicable regulations.</p>
                           <p class="pInTable">3. The comfort and safety of our consumers is our first priority. If
                             there is something can contact us via Upgrade Member department:
                             +6399898888899.</p>
                           <p class="pInTable">4. This form will be given a carbon copy (CC) to the Customer, Officer, Branch Manager,
                             Upgrade Member Department.</p>
                        @endif
                    </td>
                </tr>
            </table>
            <a href="whatsapp://send?text={{ Route('homeServices_success') }}?code={{ $homeService['code'] }}" data-action="share/whatsapp/share">Share to Whatsapp</a>
        </div>
    </div>
</section>
@endsection
