<?php
    $menu_item_page = "theraphy_service";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
<style>
    .bordered_table th, .bordered_table td {
        border: 1px solid #bdc3c7 !important;
    }

    #meta_condition_table {
        font-size: 14px;
    }
    #meta_condition_table thead {
        background-color: #8080801a;
        text-align: center;
    }
    #meta_condition_table td {
        border: 0.5px #8080801a solid;
        padding: 0.5em;
    }

    .souvenir-template {
        list-style-type: none;
        margin: 25px 0 0 0;
    }

    .souvenir-template li {
        margin: 0 5px 0 0;
        width: auto;
        height: 45px;
        position: relative;
    }

    .souvenir-template label,
    .souvenir-template input {
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }

    .souvenir-template input[type="radio"] {
        opacity: 0.01;
        z-index: 100;
    }

    .souvenir-template input[type="radio"]:checked+label,
    .Checked+label {
        background: #2ecc71;
    }

    .souvenir-template label {
        padding: 5px;
        border: 1px solid #CCC;
        cursor: pointer;
        z-index: 90;
    }

    .souvenir-template label:hover {
        background: #DDD;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body text-center table-responsive">
                    <h3>Therapy Service</h3>
                    <h5> {{ ucwords(str_replace("_", " ", $theraphyService->type)) }} Therapy (type)</h5>

                    <table class="table mt-5 table-bordered text-left bordered_table">
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

                    <table class="mt-4 text-left" width="max-content" id="meta_condition_table">
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
                                        <input style="width: 1.3em; height: 1.3em;" type="radio" name="rdaChoose-{{ $idxNya }}" value="1" disabled {{ $checked == '1' ? 'checked' : '' }}>
                                    </td>
                                    <td class="col-1 text-center">
                                        <input style="width: 1.3em; height: 1.3em;" type="radio" name="rdaChoose-{{ $idxNya }}" value="0" disabled {{ $checked == '0' ? 'checked' : '' }}>
                                    </td>
                                    <td class="col-4"><textarea class="form-control" name="desc-{{ $idxNya }}" rows="2" readonly>{{ $desc }}</textarea></td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body text-center table-responsive">
                    <h3>Therapy History</h3>

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
        </div>
        @if($theraphyService->type !== 'free')
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body text-center table-responsive">
                        <h3>Therapy Souvenir</h3>
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
            </div>
        @endif
        @if(Auth::user()->roles[0]['slug'] != 'cso' && Auth::user()->roles[0]['slug'] != 'branch')
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body text-center">
                        <h3>Status : {{ucwords($theraphyService->status)}}</h3>

                        @if($theraphyService->status == 'process')
                            <form action="{{route('update_status_theraphy_service', $theraphyService->id)}}" method="post">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-gradient-primary m-3" name="status" value="success">Success</button>
                                <button class="btn btn-gradient-danger m-3" name="status" value="reject">Reject</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endif
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
@endsection

@section('script')
<script>

</script>
@endsection
