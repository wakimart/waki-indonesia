<?php

?>
@extends('layouts.template')

@section('content')
<style>
    #intro { padding-top: 2em; }
    .bordered_table th, .bordered_table td { border: 1px solid #bdc3c7 !important; }
    .card-shadow {
        box-shadow: 0px 0px 9px 0px rgba(0,0,0,0.1);
        border-style: none;
        padding:1em;
    }
    @media (max-width: 575px){
        .container{ padding: 2em; }
        .table-responsive{ margin: 1em; }
    }
</style>

<section id="intro" class="clearfix">
    <div class="container">
        <div class="row justify-content-center">
          <h2 class="text-center" style="margin-bottom: 0.2em; font-weight: 600; color: #002853;">Therapy Service</h2>
        </div>
        <div class="row justify-content-center col-12 col-md-10 m-auto">
          <h6 class="text-center" style="font-weight: 400;">Untuk kesehatan Anda dan keluarga, kami mengundang Anda sekeluarga beserta sanak saudara untuk mengikuti Program FREE Terapi Kesehatan WAKI!</h6>
        </div>
        <br>
        <div class="card card-shadow">
            <div class="card-body text-center table-responsive">

                <table class="table table-bordered text-left bordered_table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Register Date</th>
                            <th>Expired Date</th>
                            <th>Branch</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$theraphyService->code}}</td>
                            <td>{{ date("d-m-Y", strtotime($theraphyService->registered_date)) }}</td>
                            <td>{{ $theraphyService->expired_date ? date("d-m-Y", strtotime($theraphyService->expired_date)) : '' }}</td>
                            <td>{{$theraphyService->branch->name}}</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered mt-4 text-left bordered_table">
                    <thead>
                        <tr>
                            <th style="width: 35%"></th>
                            <th>Customer Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Name</td>
                            <td>{{$theraphyService->name}}</td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td>{{$theraphyService->phone}}</td>
                        </tr>
                        <tr>
                            <td rowspan="2">location</td>
                            <td>{{$theraphyService->therapy_location_id ? $theraphyService->therapyLocation->name : ''}}</td>
                        </tr>
                        <tr>
                            @if($theraphyService->therapyLocation)
                                @if($theraphyService->therapyLocation->subdistrict_id)
                                    <td>{{$theraphyService->therapyLocation->subdistrictCityProvince->subdistrict_name}}, {{$theraphyService->therapyLocation->subdistrictCityProvince->city}}, {{$theraphyService->therapyLocation->subdistrictCityProvince->province}}</td>
                                @else
                                    <td></td>
                                @endif
                            @else
                                <td></td>
                            @endif
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

        <div class="card card-shadow mt-3">
            <div class="card-body text-center table-responsive">
                <h6 style="font-weight: 600;">Therapy History</h6>

                <table class="table table-bordered mt-4 text-left bordered_table" style="width: 33%; margin-left: auto; margin-right: auto">
                    @foreach($theraphyService->theraphySignIn as $index => $theraphySignInData)
                        <tr>
                            <th style="width: 5%">{{$index+1}}</th>
                            <th>{{ date("d-m-Y", strtotime($theraphySignInData->therapy_date)) }}</th>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

        @if($theraphyService->type !== 'free')
        <div class="card card-shadow mt-3 mb-4">
            <div class="card-body text-center table-responsive">
                <h6 style="font-weight: 600;">Therapy Souvenir</h6>
                <table class="table mt-5 table-bordered text-left bordered_table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Souvenir</th>
                            <th>Created At</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($theraphyService->therapyServiceSouvenir) > 0)
                            @foreach($theraphyService->therapyServiceSouvenir as $index => $therapyServiceSouvenirData)
                                <tr>
                                    <td>{{$index + 1}}</td>
                                    <td>{{$therapyServiceSouvenirData->souvenir->name}}</td>
                                    <td>{{date("d-m-Y h:m", strtotime($therapyServiceSouvenirData->created_at))}}</td>
                                    <td>{{$therapyServiceSouvenirData->user->name}}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan=4 class="text-center">no data</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <!-- max souvenir -->
                @php
                    $signIn = count($theraphyService->theraphySignIn);
                    $max = round($signIn/3);
                @endphp
                @if(count($theraphyService->therapyServiceSouvenir) < $max && Auth::user()->roles[0]['slug'] != 'cso' && Auth::user()->roles[0]['slug'] != 'branch')
                    <button type="button" class="btn btn-primary mt-5" data-toggle="modal" data-target="#addTherapyServiceSouvenirModal">
                        Add Souvenir
                    </button>
                @elseif(count($theraphyService->therapyServiceSouvenir) < $max && !$theraphyService->request)
                    <form action="{{route('update_theraphy_service', $theraphyService->id)}}" method="post">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-primary mt-5" name="request" value="1">
                            Request Souvenir
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @endif

        <hr>
        <div class="row mt-4">
            <div class="table-responsive">
                <table class="table table-borderless sk">
                    <thead>
                        <th colspan="2">Syarat dan Ketentuan</th>
                    </thead>
                    <tbody class="sk">
                        <tr>
                            <td>1.</td>
                            <td>Kartu ini hanya berlaku sesuai dengan nama dan tanggal yang tercetak pada kartu.</td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td>Kartu ini tidak dapat dipindah tangankan.</td>
                        </tr>
                        <tr>
                            <td>3.</td>
                            <td>Terapi hanya bisa dilakukan 1x dalam 1 hari.</td>
                        </tr>
                        <tr>
                            <td>4.</td>
                            <td>Syarat dan Ketentuan dapat berubah tanpa pemeberitahuan sebelumnya.</td>
                        </tr>
                        <tr>
                            <td>5.</td>
                            <td>Kenyamanan dan keamanan konsumen kami adalah prioritas pertama.
                            Apabila ada sesuatu atau ada pertanyaan bisa menghubungi melalui facebook page
                            WAKi Indonesia atau customer care kami di : +62 815-5467-3357</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addTherapyServiceSouvenirModal" tabindex="-1" role="dialog" aria-labelledby="addTherapyServiceSouvenirModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTherapyServiceSouvenirModalLabel">Add Therapy Service Souvenir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('add_therapy_service_souvenir')}}" method="post">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="therapy_service_id" value="{{$theraphyService->id}}">
                    <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
                    <ul class="souvenir-template">
                        @foreach($souvenirs as $index => $souvenir)
                            <li>
                                <input type="radio" id="souvenir_{{$index}}" name="souvenir_id" value="{{$souvenir->id}}">
                                <label for="souvenir_{{$index}}">{{$souvenir->name}}</label>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
</section>
@endsection

@section('script')
<script>

</script>
@endsection
