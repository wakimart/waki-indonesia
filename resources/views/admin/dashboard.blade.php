<?php
    use App\Order;
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    .wrapper {
        overflow: auto;
        width: auto;
        max-height: 600px;
        white-space: nowrap;
        padding-bottom: 10px;
        padding-top: 10px;
    }

    .btn-outline-success.disabled, .btn-outline-success:disabled:hover {
        color: #1bcfb4 !important;
        background: transparent !important;
    }

    .btn-outline-danger.disabled, .btn-outline-danger:disabled:hover {
        color: #fe7c96 !important;
        background: transparent !important;
    }

    .btn-action button{
        margin-bottom: 0.6em;
    }

    @media(max-width: 468px){
        .btn-action button{
            margin-bottom: 0.6em;
        }
    }
</style>
@endsection

@section('content')
@can('show-dashboard')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-home"></i>
                </span> Dashboard
            </h3>
        </div>
        <div class="row">
            <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-danger card-img-holder text-white">
                    <div class="card-body">
                        <img src="{{ asset('sources/dashboard/circle.svg') }}"
                            class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">
                            Month Sale
                            <i class="mdi mdi-chart-line mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">
                            <?php
                            echo "Rp. " . number_format($order->total_payment);
                            ?>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-info card-img-holder text-white">
                    <div class="card-body">
                        <img src="{{ asset('sources/dashboard/circle.svg') }}"
                            class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">
                            Today Home Service
                            <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">
                            <?php echo $homeServiceToday->count; ?>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-success card-img-holder text-white">
                    <div class="card-body">
                        <img src="{{ asset('sources/dashboard/circle.svg') }}"
                            class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">
                            Total Registration This Month
                            <i class="mdi mdi-diamond mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">
                            <?php echo $registration->count; ?>
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        @if(Gate::check('change-status-checkin-personalhomecare') == true || Gate::check('change-status-checkout-personalhomecare') == true || Gate::check('change-status-verified-personalhomecare') == true || Gate::check('acc-reschedule-personalhomecare') == true || Gate::check('acc-extend-personalhomecare') == true)
            <div class="col-12 grid-margin stretch-card px-0">
                <div class="card">
                    <div class="card-header" style="background: none;">
                        <h4 style="margin-bottom: 1em;">
                            Personal Homecare ACC
                        </h4>
                        <ul class="nav nav-tabs card-header-tabs">
                            @foreach($personalHomecares as $keyNya => $arrPP5H)
                                <li class="nav-item">
                                    <a class="nav-link {{ $keyNya == "new" ? 'active' : '' }}"
                                        style="font-weight: 500; font-size: 1em;"
                                        id="{{ $keyNya }}-tab"
                                        data-toggle="tab"
                                        href="#tabs-{{ $keyNya }}"
                                        role="tab"
                                        aria-controls="{{ $keyNya }}"
                                        aria-selected="true">
                                        {{ ucwords(str_replace("_", " ",$keyNya)) }} ({{ sizeof($arrPP5H) }})
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-body wrapper">
                        <div class="tab-content" id="myTabContent">
                            @foreach($personalHomecares as $keyNya => $arrPP5H)
                                <div class="tab-pane fade show {{ $keyNya == "new" ? 'active' : '' }} p-3" id="tabs-{{ $keyNya }}" role="tabpanel" aria-labelledby="{{ $keyNya }}-tab">
                                    <h5 class="mb-3">
                                        Personal Homecare Data | Status {{ ucwords(str_replace("_", " ",$keyNya)) }} (Total: {{ sizeof($arrPP5H) }})
                                    </h5>
                                    <div class="d-flex flex-wrap mt-4" style="align-items: center;">
                                        <p>Terpilih : <span id="checkedPH" class="checkedPH"></span></p>
                                        <div style="margin-left: auto;">
                                            @if($keyNya == "new")
                                                <form id="formSelectAllNew" method="POST" action="{{ route('update_personal_homecare_status') }}">
                                            @elseif($keyNya == "verified")
                                                <form id="formSelectAllVerified" method="POST" action="{{ route('update_personal_homecare_status') }}">
                                            @elseif($keyNya == "waiting_in")
                                                <form id="formSelectAllWaitingIn" method="POST" action="{{ route('update_personal_homecare_status') }}">
                                            @elseif($keyNya == "reschedule_acc")                                            
                                                <form id="formSelectAllReschedule" method="POST" action="{{ route('reschedule_personal_homecare') }}">
                                            @elseif($keyNya == "extend_acc")
                                                <form id="formSelectAllExtend" method="POST" action="{{ route('update_personal_homecare_status') }}">
                                            @elseif($keyNya == "cancel_acc")
                                                <form id="formSelectAllCancel" method="POST" action="{{ route('delete_personal_homecare') }}">
                                            @else
                                                <form id="" method="POST" action="">
                                            @endif
                                                @csrf
                                                <div class="form-group btn-action">
                                                    <div class="d-flex flex-wrap" style="justify-content: right;">
                                                    @if($keyNya == "new")
                                                        <button type="submit" id="AccPH-{{ $keyNya }}" class="btn btn-md btn-outline-success" name="status" value="verified">Approved All</button>
                                                        <button type="submit" id="CancelPH-{{ $keyNya }}" class="btn btn-md btn-outline-danger ml-2" name="status" value="rejected">Reject All</button>
                                                    @elseif($keyNya == "verified")
                                                        <button type="submit" id="AccPH-{{ $keyNya }}" class="btn btn-md btn-outline-success" name="status" value="approve_out">Approved All</button>
                                                        <button type="submit" id="CancelPH-{{ $keyNya }}" class="btn btn-md btn-outline-danger ml-2" name="status" value="rejected">Reject All</button>
                                                    @elseif($keyNya == "waiting_in")
                                                        <button type="submit" id="AccPH-{{ $keyNya }}" class="btn btn-md btn-outline-success" name="status" value="done">Approved All</button>
                                                        <button type="submit" id="CancelPH-{{ $keyNya }}" class="btn btn-md btn-outline-danger ml-2" name="status" value="pending_product">Reject All</button>
                                                    @elseif($keyNya == "reschedule_acc")
                                                        <button type="submit" id="AccPH-{{ $keyNya }}" class="btn btn-md btn-outline-success" name="status" value="acceptance">Approved All</button>
                                                        <button type="submit" id="CancelPH-{{ $keyNya }}" class="btn btn-md btn-outline-danger ml-2" name="status" value="rejected">Reject All</button>
                                                    @elseif($keyNya == "extend_acc")
                                                        <button type="submit" id="AccPH-{{ $keyNya }}" class="btn btn-md btn-outline-success" name="status" value="process_extend">Approved All</button>
                                                        <button type="submit" id="CancelPH-{{ $keyNya }}" class="btn btn-md btn-outline-danger ml-2" name="status" value="process_extend_reject">Reject All</button>
                                                    @elseif($keyNya == "cancel_acc")
                                                        <button type="submit" id="AccPH-{{ $keyNya }}" class="btn btn-md btn-outline-success" name="status" value="cancel_approved">Approved All</button>
                                                        <button type="submit" id="CancelPH-{{ $keyNya }}" class="btn btn-md btn-outline-danger ml-2" name="status" value="cancel_rejected">Reject All</button>
                                                    @else
                                                        <button type="button" id="AccPH-{{ $keyNya }}" class="btn btn-md btn-outline-success" name="status" value="acceptance">Approved All</button>
                                                        <button type="button" id="CancelPH-{{ $keyNya }}" class="btn btn-md btn-outline-danger ml-2" name="status" value="rejected">Reject All</button>
                                                    @endif
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="table-responsive table-PP5H"
                                        style="border: 1px solid #ebedf2;">
                                        <table class="table table-bordered table-wrap">
                                            <thead style="text-align: center; background-color: aliceblue;">
                                                <tr>
                                                    <td class="text-center">
                                                        <div class="form-group mb-0">
                                                            <input type="checkbox" id="{{$keyNya}}" name="" value="true"
                                                                class="form-control SelectAll_Dynamic checkBoxPH"
                                                                style="position: relative; width: 16px; margin: auto;"/>
                                                        </div>
                                                    </td>
                                                    <td>Customer <br class="break">(Branch - CSO)</td>
                                                    <td>Product</td>
                                                    <td>Acc/Reject/Detail</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($arrPP5H as $personalHomecare)
                                                    <tr>
                                                        <td class="text-center">
                                                            <div class="form-group">
                                                                <input type="checkbox" name=""
                                                                    class="form-control checkBoxPH"
                                                                    id="{{$keyNya}}"
                                                                    value="{{$personalHomecare['id']}}"
                                                                    style="position: relative; width: 16px; margin: auto;"/>
                                                            </div>
                                                        </td>
                                                        <td style="white-space: normal;">
                                                            {{ $personalHomecare['name'] }} - {{ $personalHomecare['phone'] }}
                                                            <br class="break">
                                                            ({{ $personalHomecare->branch['code'] }} - {{ $personalHomecare->cso['code'] }})

                                                            @if ($keyNya == "extend_acc" && $personalHomecare['is_extend'] && Gate::check('acc-extend-personalhomecare'))
                                                            <br class="break">
                                                            <div class="extendReason" style="font-weight:bold;">
                                                              <p>{{ $personalHomecare["extend_reason"] }}</p>
                                                            </div>
                                                            @endif

                                                            @if ($keyNya == "cancel_acc" && $personalHomecare['is_cancel'] && Gate::check('acc-extend-personalhomecare'))
                                                            <br class="break">
                                                            <div class="extendReason" style="font-weight:bold;">
                                                              <p>{{ $personalHomecare["cancel_desc"] }}</p>
                                                            </div>
                                                            @endif

                                                            @if ($keyNya == "reschedule_acc" && $personalHomecare['reschedule_date'] != null && Gate::check('acc-reschedule-personalhomecare'))
                                                            <br class="break">
                                                            <div class="extendReason" style="font-weight:bold;">
                                                              <p>{{ $personalHomecare["reschedule_reason"] }}</p>
                                                            </div>
                                                            @endif
                                                        </td>
                                                        <td>{{ $personalHomecare->personalHomecareProduct['code'] }} - {{ $personalHomecare->personalHomecareProduct->product['code'] }}</td>
                                                        <td style="text-align: center; white-space: normal;">
                                                          @if(Auth::user()->inRole("head-admin"))
                                                            @if($personalHomecare->status == "new")
                                                                <form method="POST" action="{{ route('update_personal_homecare_status') }}" style="margin: auto;">
                                                            @elseif($personalHomecare->status == "verified")
                                                                <form method="POST" action="{{ route('update_personal_homecare_status') }}" style="margin: auto;">
                                                            @elseif($personalHomecare->status == "waiting_in")
                                                                <form method="POST" action="{{ route('update_personal_homecare_status') }}" style="margin: auto;">
                                                                    <input type="hidden" name="id_product" value="{{$personalHomecare->personalHomecareProduct['id']}}">
                                                            @elseif(!empty($personalHomecare->reschedule_date))
                                                                <form method="POST" action="{{ route('reschedule_personal_homecare') }}" style="margin: auto;">
                                                            @elseif($personalHomecare->is_extend)
                                                                <form method="POST" action="{{ route('update_personal_homecare_status') }}" style="margin: auto;">
                                                            @elseif($personalHomecare->is_cancel)
                                                                <form method="POST" action="{{ route('delete_personal_homecare') }}" style="margin: auto;">
                                                            @else
                                                                <form id="formUpdateStatusHS" method="POST" action="" style="margin: auto;">
                                                            @endif
                                                              @csrf
                                                              <div class="form-group">

                                                                  <input type="hidden" id="hiddenInput" name="cancel" value="1" />
                                                                  <input type="hidden" id="input_id_hs_hidden" name="id" value="{{ $personalHomecare->id }}" />

                                                                  <div style="text-align: center;">
                                                                    <p>Do you approved it ?</p>
                                                                  </div>
                                                                  <div class="btn-action" style="text-align: center;">
                                                                  
                                                                  @if($personalHomecare->status == "new")
                                                                    <button type="submit" class="btn btn-gradient-primary" name="status" value="verified">Yes</button>
                                                                    <button type="submit" class="btn btn-gradient-danger" name="status" value="rejected">No</button>
                                                                  @elseif($personalHomecare->status == "verified")
                                                                    <button type="submit" class="btn btn-gradient-primary" name="status" value="approve_out">Yes</button>
                                                                    <button type="submit" class="btn btn-gradient-danger" name="status" value="rejected">No</button>
                                                                  @elseif($personalHomecare->status == "waiting_in")
                                                                    <button type="submit" class="btn btn-gradient-primary" name="status" value="done">Yes</button>
                                                                    <button type="submit" class="btn btn-gradient-danger" name="status" value="pending_product">No</button>
                                                                  @elseif(!empty($personalHomecare->reschedule_date))
                                                                    <button type="submit" class="btn btn-gradient-primary" name="status" value="acceptance">Yes</button>
                                                                    <button type="submit" class="btn btn-gradient-danger" name="status" value="rejected">No</button>
                                                                  @elseif($personalHomecare->is_extend)
                                                                    <button type="submit" class="btn btn-gradient-primary" name="status" value="process_extend">Yes</button>
                                                                    <button type="submit" class="btn btn-gradient-danger" name="status" value="process_extend_reject">No</button>
                                                                  @elseif($personalHomecare->is_cancel)
                                                                    <button type="submit" class="btn btn-gradient-primary" name="status" value="cancel_approved">Yes</button>
                                                                    <button type="submit" class="btn btn-gradient-danger" name="status" value="cancel_rejected">No</button>
                                                                  @else
                                                                    <button type="button" class="btn btn-gradient-primary" name="status" value="acceptance">Yes</button>
                                                                    <button type="button" class="btn btn-gradient-danger" name="status" value="rejected">No</button>
                                                                  @endif
                                                                  </div>
                                                              </div>
                                                          </form>
                                                          <a href="{{ route('detail_personal_homecare', ['id' => $personalHomecare['id']]) }}">
                                                              <i class="mdi mdi-eye" style="font-size: 12px; text-decoration:none;">More Detail</i>
                                                          </a>
                                                          @else
                                                            <a href="{{ route('admin_list_homeService', ["id_hs"=>$perHomeservice['id']]) }}">
                                                                <i class="mdi mdi-eye" style="font-size: 24px;"></i>
                                                            </a>
                                                          @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <br>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header" style="background: none;">
                        <h4 style="margin-bottom: 1em;">
                            Homeservice ACC
                        </h4>
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link active"
                                    style="font-weight: 500; font-size: 1em;"
                                    id="reschedulehs-tab"
                                    data-toggle="tab"
                                    href="#tabs-reschedulehs"
                                    role="tab"
                                    aria-controls="reschedulehs"
                                    aria-selected="true">
                                    Reschedule Acc ({{ sizeof($accRescheduleHS) }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                    style="font-weight: 500; font-size: 1em;"
                                    id="cancelhs-tab"
                                    data-toggle="tab"
                                    href="#tabs-cancelhs"
                                    role="tab"
                                    aria-controls="cancelhs"
                                    aria-selected="true">
                                    Cancel Acc ({{ sizeof($accDeleteHS) }})
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body wrapper">
                        <div class="tab-content" id="myTabContentHS">
                            <div class="tab-pane fade show active p-3" id="tabs-reschedulehs" role="tabpane2" aria-labelledby="reschedulehs-tab">
                                <h5 class="mb-3">
                                    Homeservice Data | Status Reschedule Acc (Total: {{ sizeof($accRescheduleHS) }})
                                </h5>
                                <div class="d-flex flex-wrap mt-4" style="align-items: center;">
                                    <p>Terpilih : <span id="checkedPH" class="checkedPH"></span></p>
                                    <div style="margin-left: auto;">
                                        <form id="formSelectAllHomeServiceReschedule" method="POST" action="{{ route('update_homeService') }}">
                                            @csrf
                                            <div class="form-group btn-action">
                                                <div class="d-flex flex-wrap" style="justify-content: right;">
                                                    <input type="hidden" name="acc_hs_type" value="reschedulehs">
                                                    <button type="submit" id="AccPH-reschedulehs" class="btn btn-md btn-outline-success" name="status_acc" value="true">Approved All</button>
                                                    <button type="submit" id="CancelPH-reschedulehs" class="btn btn-md btn-outline-danger ml-2" name="status_acc" value="false">Reject All</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-responsive tableHS wrapper" style="border: 1px solid #ebedf2; padding-top: 0;">
                                    <table class="table table-bordered">
                                        <thead style="text-align: center; background-color: aliceblue;">
                                            <tr>
                                                <td class="text-center">
                                                    <div class="form-group mb-0">
                                                        <input type="checkbox" id="reschedulehs" name="" value="true"
                                                            class="form-control SelectAll_Dynamic checkBoxPH"
                                                            style="position: relative; width: 16px; margin: auto;"/>
                                                    </div>
                                                </td>
                                                <td>Schedule</td>
                                                <td>Detail & Reschedule Reason Home Service</td>
                                                <td>Acc/Reject</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($accRescheduleHS as $perHomeservice)
                                                <tr>
                                                    <td class="text-center" rowspan="2">
                                                        <div class="form-group">
                                                            <input type="checkbox" name=""
                                                                class="form-control checkBoxPH checkBoxHS"
                                                                id="reschedulehs"
                                                                value="{{$perHomeservice['id']}}"
                                                                style="position: relative; width: 16px; margin: auto;"/>
                                                        </div>
                                                    </td>
                                                    <td style="border-bottom: 0.2px solid #2f2f2f; background-color: #E0DECA">
                                                        Old
                                                        <br class="break">
                                                        {{ date_format(date_create($perHomeservice['appointment']), 'd/m/Y H:i') }}
                                                    </td>
                                                    <td style="white-space: normal; border-bottom: 0.2px solid #2f2f2f;">
                                                      <div class="detailHs">
                                                        Type HS : {{ $perHomeservice['type_homeservices'] }}
                                                        <br class="break">
                                                        {{ $perHomeservice['name'] }} - {{ $perHomeservice['phone'] }}
                                                        <br class="break">
                                                        ({{ $perHomeservice->branch['code'] }} - {{ $perHomeservice->cso['code'] }})
                                                        <br class="break">
                                                      </div>
                                                    </td>
        
                                                    <td style="text-align: center; white-space: normal;" rowspan="2">
                                                      @if(Auth::user()->inRole("head-admin"))
                                                      <form id="formUpdateStatusHS" method="POST" action="{{ route('update_homeService') }}" style="margin: auto;">
                                                          @csrf
                                                          <div class="form-group">
                                                              <input type="hidden" name="acc_hs_type" value="reschedulehs">
                                                              <input type="hidden" id="hiddenInput" name="cancel" value="1" />
                                                              <input type="hidden" id="input_id_hs_hidden" name="id" value="{{ $perHomeservice->id }}" />
        
                                                              <div style="text-align: center;">
                                                                <p>Do you approved it ?</p>
                                                              </div>
        
                                                              <div class="btn-action" style="text-align: center;">
                                                                  <button type="submit" class="btn btn-gradient-primary" name="status_acc" value="true">Yes</button>
                                                                  <button type="submit" class="btn btn-gradient-danger" name="status_acc" value="false">No</button>
                                                              </div>
                                                          </div>
                                                      </form>
                                                      @else
                                                        <a href="{{ route('admin_list_homeService', ["id_hs"=>$perHomeservice['id']]) }}">
                                                            <i class="mdi mdi-eye" style="font-size: 24px;"></i>
                                                        </a>
                                                      @endif
                                                    </td>
                                                </tr>
                                                <tr style="border-bottom: 2px solid #000">
                                                    <td style="background-color: #E3FCBF">
                                                        New
                                                        <br class="break">
                                                        {{ date_format(date_create($perHomeservice['resc_acc']), 'd/m/Y H:i') }}
                                                    </td>
                                                    <td>
                                                      <div class="cancelReason" style="font-weight:bold;">
                                                        <p>{{ $perHomeservice['resc_desc'] }}<p>
                                                      </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade show p-3" id="tabs-cancelhs" role="tabpane2" aria-labelledby="cancelhs-tab">
                                <h5 class="mb-3">
                                    Homeservice Data | Status Cancel Acc (Total: {{ sizeof($accDeleteHS) }})
                                </h5>
                                <div class="d-flex flex-wrap mt-4" style="align-items: center;">
                                    <p>Terpilih : <span id="checkedPH" class="checkedPH"></span></p>
                                    <div style="margin-left: auto;">
                                        <form id="formSelectAllHomeServiceCancel" method="POST" action="{{ route('update_homeService') }}">
                                            @csrf
                                            <div class="form-group btn-action">
                                                <div class="d-flex flex-wrap" style="justify-content: right;">
                                                    <input type="hidden" name="acc_hs_type" value="cancelhs">
                                                    <button type="submit" id="AccPH-cancelhs" class="btn btn-md btn-outline-success" name="status_acc" value="true">Approved All</button>
                                                    <button type="submit" id="CancelPH-cancelhs" class="btn btn-md btn-outline-danger ml-2" name="status_acc" value="false">Reject All</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-responsive tableHS wrapper" style="border: 1px solid #ebedf2; padding-top: 0;">
                                    <table class="table table-bordered">
                                        <thead style="text-align: center; background-color: aliceblue;">
                                            <tr>
                                                <td class="text-center">
                                                    <div class="form-group mb-0">
                                                        <input type="checkbox" id="cancelhs" name="" value="true"
                                                            class="form-control SelectAll_Dynamic checkBoxPH"
                                                            style="position: relative; width: 16px; margin: auto;"/>
                                                    </div>
                                                </td>
                                                <td>Schedule</td>
                                                <td>Detail & Cancel Reason Home Service</td>
                                                <td>Acc/Reject</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($accDeleteHS as $perHomeservice)
                                                <tr>
                                                    <td class="text-center">
                                                        <div class="form-group">
                                                            <input type="checkbox" name=""
                                                                class="form-control checkBoxPH checkBoxHS"
                                                                id="cancelhs"
                                                                value="{{$perHomeservice['id']}}"
                                                                style="position: relative; width: 16px; margin: auto;"/>
                                                        </div>
                                                    </td>
                                                    <td>{{ date_format(date_create($perHomeservice['appointment']), 'd/m/Y H:i') }}</td>
                                                    <td style="white-space: normal;">
                                                      <div class="detailHs" style="border-bottom: 0.2px solid #2f2f2f">
                                                        Type HS : {{ $perHomeservice['type_homeservices'] }}
                                                        <br class="break">
                                                        {{ $perHomeservice['name'] }} - {{ $perHomeservice['phone'] }}
                                                        <br class="break">
                                                        ({{ $perHomeservice->branch['code'] }} - {{ $perHomeservice->cso['code'] }})
                                                        <br class="break">
                                                      </div>
        
                                                      <br class="break">
                                                      <div class="cancelReason" style="font-weight:bold;">
                                                        <p>{{ $perHomeservice['cancel_desc'] }}<p>
                                                      </div>
                                                    </td>
        
                                                    <td style="text-align: center; white-space: normal;">
                                                      @if(Auth::user()->inRole("head-admin"))
                                                      <form id="formUpdateStatusHS" method="POST" action="{{ route('update_homeService') }}" style="margin: auto;">
                                                          @csrf
                                                          <div class="form-group">
                                                              <input type="hidden" name="acc_hs_type" value="cancelhs">
                                                              <input type="hidden" id="hiddenInput" name="cancel" value="1" />
                                                              <input type="hidden" id="input_id_hs_hidden" name="id" value="{{ $perHomeservice->id }}" />
        
                                                              <div style="text-align: center;">
                                                                <p>Do you approved it ?</p>
                                                              </div>
        
                                                              <div class="btn-action" style="text-align: center;">
                                                                  <button type="submit" class="btn btn-gradient-primary" name="status_acc" value="true">Yes</button>
                                                                  <button type="submit" class="btn btn-gradient-danger" name="status_acc" value="false">No</button>
                                                              </div>
                                                          </div>
                                                      </form>
                                                      @else
                                                        <a href="{{ route('admin_list_homeService', ["id_hs"=>$perHomeservice['id']]) }}">
                                                            <i class="mdi mdi-eye" style="font-size: 24px;"></i>
                                                        </a>
                                                      @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::id() == 1)
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body wrapper">
                        <div class="clearfix">
                            <h4 class="card-title float-left">
                                Reference Souvenir to Acc (total : {{ sizeof($refSouvenirs) }})
                            </h4>
                        </div>
                        {{-- <canvas id="homeservice-chart" class="mt-4"></canvas> --}}
                        <div class="table-responsive wrapper" style="border: 1px solid #ebedf2;">
                            <table class="table table-bordered">
                                <thead style="text-align: center; background-color: aliceblue;">
                                    <tr>
                                        <td rowspan="2">Submission Code</td>
                                        <td colspan="4">Reference Souvenir Data</td>
                                        <td rowspan="2">View</td>
                                    </tr>
                                    <tr>
                                        <td>Name - Phone</td>
                                        <td>Branch - CSO</td>
                                        <td>Order</td>
                                        <td>Status Prize</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($refSouvenirs as $refSouvenir)
                                        <tr>
                                            <td>{{ $refSouvenir->reference->submission['code'] }}</td>
                                            <td>{{ $refSouvenir->reference['name'] }} - {{ $refSouvenir->reference['phone'] }}</td>
                                            <td>{{ $refSouvenir->reference->submission->branch['code'] }} - {{ $refSouvenir->reference->submission->cso['name'] }}</td>
                                            <td>
                                                <?php
                                                if (!empty($refSouvenir['order_id'])) {
                                                    $order = Order::select("id", "code")
                                                        ->where("id", $refSouvenir['order_id'])
                                                        ->first();
                                                    echo '<a href="'
                                                        . route("detail_order", ["code" => $order->code])
                                                        . '">'
                                                        . $order->code
                                                        . '</a>';
                                                }
                                                ?>
                                            </td>
                                            <td>{{ $refSouvenir['status_prize'] }}</td>
                                            <td style="text-align: center;">
                                                <a href="{{ route("detail_submission_form", ['id'=>$refSouvenir->reference->submission['id'], 'type'=>"mgm", "id_ref"=>$refSouvenir->reference['id']]) }}">
                                                    <i class="mdi mdi-eye" style="font-size: 24px;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="clearfix">
                            <h4 class="card-title float-left">
                                New Reference to Review (total : {{ sizeof($references) }})
                            </h4>
                        </div>
                        {{-- <canvas id="homeservice-chart" class="mt-4"></canvas> --}}
                        <div class="table-responsive"
                            style="border: 1px solid #ebedf2;">
                            <table class="table table-bordered">
                                <thead style="text-align: center; background-color: aliceblue;">
                                    <tr>
                                        <td rowspan="2">Submission Code</td>
                                        <td colspan="3">Reference Data</td>
                                        <td rowspan="2">View</td>
                                    </tr>
                                    <tr>
                                        <td>Name</td>
                                        <td>Phone</td>
                                        <td>Link HS</td>
                                        {{-- <td>Order</td> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($references as $ref)
                                        @if($ref->reference['active'] == true && $ref->reference->submission['active'] == true )
                                            <tr>
                                                <td>{{ $ref->reference->submission['code'] }}</td>
                                                <td>{{ $ref->reference['name'] }}</td>
                                                <td>{{ $ref->reference['phone'] }}</td>

                                                @php
                                                    if (!empty($ref->link_hs)) {
                                                        $i = 1;
                                                        $link_hs = json_decode(
                                                            $ref->link_hs,
                                                            JSON_THROW_ON_ERROR
                                                        );
                                                    }
                                                @endphp
                                                <td>
                                                    @foreach ($link_hs as $value)
                                                        @if (is_numeric($value))
                                                            <?php
                                                            $hs = App\HomeService::select("code")->where("id", $value)->first();
                                                            ?>
                                                            <a href="{{ route("homeServices_success", ["code" => $hs->code]) }}"
                                                                target="_blank">
                                                                <i class="mdi mdi-numeric-{{ $i }}-box" style="font-size: 24px; color: #2daaff;"></i>
                                                            </a>
                                                        @else
                                                            <a href="{{ $value }}"
                                                                target="_blank">
                                                                <i class="mdi mdi-numeric-{{ $i }}-box" style="font-size: 24px; color: red;"></i>
                                                            </a>
                                                        @endif
                                                        <?php $i++; ?>
                                                    @endforeach
                                                </td>

                                                {{-- <td>
                                                    @if($ref['order_id'] != null)
                                                        <a href="{{ route('detail_order') }}?code={{ $ref->order['code'] }}" target="_blank">
                                                            {{ $ref->order['code'] }}
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </td> --}}
                                                <td style="text-align: center;">
                                                    <a href="{{ route("detail_submission_form", ["id" => $ref->reference->submission['id'], "type" => 'referensi']) }}">
                                                        <i class="mdi mdi-eye" style="font-size: 24px;"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="clearfix">
                            <h4 class="card-title float-left">
                                Home Service - <?php echo date("F Y"); ?>
                            </h4>
                        </div>
                        <canvas id="homeservice-chart" class="mt-4"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Welcome, {{ Auth::user()->name }}
            </h3>
        </div>

        @if(Gate::check('change-status-checkin-personalhomecare') == true || Gate::check('change-status-checkout-personalhomecare') == true || Gate::check('change-status-verified-personalhomecare') == true || Gate::check('acc-reschedule-personalhomecare') == true || Gate::check('acc-extend-personalhomecare') == true)
            <div class="col-12 grid-margin stretch-card px-0">
                <div class="card">
                    <div class="card-header" style="background: none;">
                        <h4 style="margin-bottom: 1em;">
                            Personal Homecare ACC
                        </h4>
                        <ul class="nav nav-tabs card-header-tabs">
                            @foreach($personalHomecares as $keyNya => $arrPP5H)
                                <li class="nav-item">
                                    <a class="nav-link {{ $keyNya == "new" ? 'active' : '' }}"
                                        style="font-weight: 500; font-size: 1em;"
                                        id="{{ $keyNya }}-tab"
                                        data-toggle="tab"
                                        href="#tabs-{{ $keyNya }}"
                                        role="tab"
                                        aria-controls="{{ $keyNya }}"
                                        aria-selected="true">
                                        {{ ucwords(str_replace("_", " ",$keyNya)) }} ({{ sizeof($arrPP5H) }})
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            @foreach($personalHomecares as $keyNya => $arrPP5H)
                                <div class="tab-pane fade show {{ $keyNya == "new" ? 'active' : '' }} p-3" id="tabs-{{ $keyNya }}" role="tabpanel" aria-labelledby="{{ $keyNya }}-tab">
                                    <h5 style="margin-bottom: 0.5em;">
                                        Status {{ ucwords(str_replace("_", " ",$keyNya)) }} | Total: {{ sizeof($arrPP5H) }}
                                    </h5>
                                    <div class="table-responsive"
                                        style="border: 1px solid #ebedf2;">
                                        <table class="table table-bordered">
                                            <thead style="text-align: center; background-color: aliceblue;">
                                                <tr>
                                                    <td colspan="2">Personal Homecare Data</td>
                                                    <td rowspan="2">View</td>
                                                </tr>
                                                <tr>
                                                    <td>Customer <br class="break">(Branch - CSO)</td>
                                                    <td>Product</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($arrPP5H as $personalHomecare)
                                                    <tr>
                                                        <td>
                                                            {{ $personalHomecare['name'] }} - {{ $personalHomecare['phone'] }}
                                                            <br class="break">
                                                            ({{ $personalHomecare->branch['code'] }} - {{ $personalHomecare->cso['code'] }})
                                                        </td>
                                                        <td>{{ $personalHomecare->personalHomecareProduct['code'] }} - {{ $personalHomecare->personalHomecareProduct->product['code'] }}</td>
                                                        <td style="text-align: center;">
                                                            <a href="{{ route('detail_personal_homecare', ['id' => $personalHomecare['id']]) }}">
                                                                <i class="mdi mdi-eye" style="font-size: 24px;"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <br>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(Auth::user()->roles[0]["slug"] !== "area-manager")
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header" style="background: none;">
                        <h4 style="margin-bottom: 1em;">
                            Homeservice ACC
                        </h4>
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link active"
                                    style="font-weight: 500; font-size: 1em;"
                                    id="reschedulehs-tab"
                                    data-toggle="tab"
                                    href="#tabs-reschedulehs"
                                    role="tab"
                                    aria-controls="reschedulehs"
                                    aria-selected="true">
                                    Reschedule Acc ({{ sizeof($accRescheduleHS) }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link"
                                    style="font-weight: 500; font-size: 1em;"
                                    id="cancelhs-tab"
                                    data-toggle="tab"
                                    href="#tabs-cancelhs"
                                    role="tab"
                                    aria-controls="cancelhs"
                                    aria-selected="true">
                                    Cancel Acc ({{ sizeof($accDeleteHS) }})
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body wrapper">
                        <div class="tab-content" id="myTabContentHS">
                            <div class="tab-pane fade show active p-3" id="tabs-reschedulehs" role="tabpane2" aria-labelledby="reschedulehs-tab">
                                <h5 class="mb-3">
                                    Homeservice Data | Status Reschedule Acc (Total: {{ sizeof($accRescheduleHS) }})
                                </h5>
                                <div class="d-flex flex-wrap mt-4" style="align-items: center;">
                                    <p>Terpilih : <span id="checkedPH" class="checkedPH"></span></p>
                                    <div style="margin-left: auto;">
                                        <form id="formSelectAllHomeServiceReschedule" method="POST" action="{{ route('update_homeService') }}">
                                            @csrf
                                            <div class="form-group btn-action">
                                                <div class="d-flex flex-wrap" style="justify-content: right;">
                                                    <input type="hidden" name="acc_hs_type" value="reschedulehs">
                                                    <button type="submit" id="AccPH-reschedulehs" class="btn btn-md btn-outline-success" name="status_acc" value="true">Approved All</button>
                                                    <button type="submit" id="CancelPH-reschedulehs" class="btn btn-md btn-outline-danger ml-2" name="status_acc" value="false">Reject All</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-responsive tableHS wrapper" style="border: 1px solid #ebedf2; padding-top: 0;">
                                    <table class="table table-bordered">
                                        <thead style="text-align: center; background-color: aliceblue;">
                                            <tr>
                                                <td class="text-center">
                                                    <div class="form-group mb-0">
                                                        <input type="checkbox" id="reschedulehs" name="" value="true"
                                                            class="form-control SelectAll_Dynamic checkBoxPH"
                                                            style="position: relative; width: 16px; margin: auto;"/>
                                                    </div>
                                                </td>
                                                <td>Schedule</td>
                                                <td>Detail & Reschedule Reason Home Service</td>
                                                <td>Acc/Reject</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($accRescheduleHS as $perHomeservice)
                                                <tr>
                                                    <td class="text-center" rowspan="2">
                                                        <div class="form-group">
                                                            <input type="checkbox" name=""
                                                                class="form-control checkBoxPH checkBoxHS"
                                                                id="reschedulehs"
                                                                value="{{$perHomeservice['id']}}"
                                                                style="position: relative; width: 16px; margin: auto;"/>
                                                        </div>
                                                    </td>
                                                    <td style="border-bottom: 0.2px solid #2f2f2f; background-color: #E0DECA">
                                                        Old
                                                        <br class="break">
                                                        {{ date_format(date_create($perHomeservice['appointment']), 'd/m/Y H:i') }}
                                                    </td>
                                                    <td style="white-space: normal; border-bottom: 0.2px solid #2f2f2f;">
                                                      <div class="detailHs">
                                                        Type HS : {{ $perHomeservice['type_homeservices'] }}
                                                        <br class="break">
                                                        {{ $perHomeservice['name'] }} - {{ $perHomeservice['phone'] }}
                                                        <br class="break">
                                                        ({{ $perHomeservice->branch['code'] }} - {{ $perHomeservice->cso['code'] }})
                                                        <br class="break">
                                                      </div>
                                                    </td>
        
                                                    <td style="text-align: center; white-space: normal;" rowspan="2">
                                                      @if(Auth::user()->inRole("head-admin"))
                                                      <form id="formUpdateStatusHS" method="POST" action="{{ route('update_homeService') }}" style="margin: auto;">
                                                          @csrf
                                                          <div class="form-group">
                                                              <input type="hidden" name="acc_hs_type" value="reschedulehs">
                                                              <input type="hidden" id="hiddenInput" name="cancel" value="1" />
                                                              <input type="hidden" id="input_id_hs_hidden" name="id" value="{{ $perHomeservice->id }}" />
        
                                                              <div style="text-align: center;">
                                                                <p>Do you approved it ?</p>
                                                              </div>
        
                                                              <div class="btn-action" style="text-align: center;">
                                                                  <button type="submit" class="btn btn-gradient-primary" name="status_acc" value="true">Yes</button>
                                                                  <button type="submit" class="btn btn-gradient-danger" name="status_acc" value="false">No</button>
                                                              </div>
                                                          </div>
                                                      </form>
                                                      @else
                                                        <a href="{{ route('admin_list_homeService', ["id_hs"=>$perHomeservice['id']]) }}">
                                                            <i class="mdi mdi-eye" style="font-size: 24px;"></i>
                                                        </a>
                                                      @endif
                                                    </td>
                                                </tr>
                                                <tr style="border-bottom: 2px solid #000">
                                                    <td style="background-color: #E3FCBF">
                                                        New
                                                        <br class="break">
                                                        {{ date_format(date_create($perHomeservice['resc_acc']), 'd/m/Y H:i') }}
                                                    </td>
                                                    <td>
                                                      <div class="cancelReason" style="font-weight:bold;">
                                                        <p>{{ $perHomeservice['resc_desc'] }}<p>
                                                      </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade show p-3" id="tabs-cancelhs" role="tabpane2" aria-labelledby="cancelhs-tab">
                                <h5 class="mb-3">
                                    Homeservice Data | Status Cancel Acc (Total: {{ sizeof($accDeleteHS) }})
                                </h5>
                                <div class="d-flex flex-wrap mt-4" style="align-items: center;">
                                    <p>Terpilih : <span id="checkedPH" class="checkedPH"></span></p>
                                    <div style="margin-left: auto;">
                                        <form id="formSelectAllHomeServiceCancel" method="POST" action="{{ route('update_homeService') }}">
                                            @csrf
                                            <div class="form-group btn-action">
                                                <div class="d-flex flex-wrap" style="justify-content: right;">
                                                    <input type="hidden" name="acc_hs_type" value="cancelhs">
                                                    <button type="submit" id="AccPH-cancelhs" class="btn btn-md btn-outline-success" name="status_acc" value="true">Approved All</button>
                                                    <button type="submit" id="CancelPH-cancelhs" class="btn btn-md btn-outline-danger ml-2" name="status_acc" value="false">Reject All</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-responsive tableHS wrapper" style="border: 1px solid #ebedf2; padding-top: 0;">
                                    <table class="table table-bordered">
                                        <thead style="text-align: center; background-color: aliceblue;">
                                            <tr>
                                                <td class="text-center">
                                                    <div class="form-group mb-0">
                                                        <input type="checkbox" id="cancelhs" name="" value="true"
                                                            class="form-control SelectAll_Dynamic checkBoxPH"
                                                            style="position: relative; width: 16px; margin: auto;"/>
                                                    </div>
                                                </td>
                                                <td>Schedule</td>
                                                <td>Detail & Cancel Reason Home Service</td>
                                                <td>Acc/Reject</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($accDeleteHS as $perHomeservice)
                                                <tr>
                                                    <td class="text-center">
                                                        <div class="form-group">
                                                            <input type="checkbox" name=""
                                                                class="form-control checkBoxPH checkBoxHS"
                                                                id="cancelhs"
                                                                value="{{$perHomeservice['id']}}"
                                                                style="position: relative; width: 16px; margin: auto;"/>
                                                        </div>
                                                    </td>
                                                    <td>{{ date_format(date_create($perHomeservice['appointment']), 'd/m/Y H:i') }}</td>
                                                    <td style="white-space: normal;">
                                                      <div class="detailHs" style="border-bottom: 0.2px solid #2f2f2f">
                                                        Type HS : {{ $perHomeservice['type_homeservices'] }}
                                                        <br class="break">
                                                        {{ $perHomeservice['name'] }} - {{ $perHomeservice['phone'] }}
                                                        <br class="break">
                                                        ({{ $perHomeservice->branch['code'] }} - {{ $perHomeservice->cso['code'] }})
                                                        <br class="break">
                                                      </div>
        
                                                      <br class="break">
                                                      <div class="cancelReason" style="font-weight:bold;">
                                                        <p>{{ $perHomeservice['cancel_desc'] }}<p>
                                                      </div>
                                                    </td>
        
                                                    <td style="text-align: center; white-space: normal;">
                                                      @if(Auth::user()->inRole("head-admin"))
                                                      <form id="formUpdateStatusHS" method="POST" action="{{ route('update_homeService') }}" style="margin: auto;">
                                                          @csrf
                                                          <div class="form-group">
                                                              <input type="hidden" name="acc_hs_type" value="cancelhs">
                                                              <input type="hidden" id="hiddenInput" name="cancel" value="1" />
                                                              <input type="hidden" id="input_id_hs_hidden" name="id" value="{{ $perHomeservice->id }}" />
        
                                                              <div style="text-align: center;">
                                                                <p>Do you approved it ?</p>
                                                              </div>
        
                                                              <div class="btn-action" style="text-align: center;">
                                                                  <button type="submit" class="btn btn-gradient-primary" name="status_acc" value="true">Yes</button>
                                                                  <button type="submit" class="btn btn-gradient-danger" name="status_acc" value="false">No</button>
                                                              </div>
                                                          </div>
                                                      </form>
                                                      @else
                                                        <a href="{{ route('admin_list_homeService', ["id_hs"=>$perHomeservice['id']]) }}">
                                                            <i class="mdi mdi-eye" style="font-size: 24px;"></i>
                                                        </a>
                                                      @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endcan
@endsection

@section("script")
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.1.1/dist/chart.js"></script>
<script type="application/javascript">
document.addEventListener('DOMContentLoaded', function () {
    const URL = '<?php echo route("dashboard_hs"); ?>';

    fetch(
        URL,
        {
            method: "GET",
            headers: {
                "Accept": "application/json",
                "Content-type": "application/json",
            },
        }
    ).then(function (response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    }).then(function (response) {
        const data = response.data;
        const today = Date.now();
        const currentDate = new Date(today);
        const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 0).getDate();
        const arrayData = [];
        const arrayLabel = [];

        for (let i = 0; i < lastDay; i++) {
            arrayData.push(0);
            arrayLabel.push(`${i + 1}`);
        }

        data.forEach(function (currentValue) {
            arrayData[currentValue.appointment_date - 1] = currentValue.data_count;
        });

        new Chart(
            document.getElementById("homeservice-chart"),
            {
                type: "bar",
                data: {
                    labels: arrayLabel,
                    datasets: [
                        {
                            backgroundColor: "rgba(173, 216, 230, 0.9)",
                            borderColor: "rgba(173, 216, 230, 1)",
                            data: arrayData,
                            label: "Jumlah",
                        }
                    ],
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: "Date",
                            },
                        },
                        y: {
                            ticks: {
                                stepSize: 20,
                            },
                        },
                    },
                },
            }
        );
    }).catch(function (error) {
        console.error(error);
    });
}, false);
</script>

{{-- checkbox untuk PP5H --}}
<script type="text/javascript">
    $(document).ready(function() {

        let checkboxes = $('.checkBoxPH').closest('div[id^="tabs-"]').find('input:checkbox');
        let allCheckboxes = $('.SelectAll_Dynamic').closest('div[id^="tabs-"]').find('input:checkbox').not(":eq(0)");
        let flag = true;

        $.each(allCheckboxes, function( i, val ) {
            if($(val).prop('checked') == false)
            flag = false;
        });

        if(flag)
        $('.SelectAll_Dynamic').prop('checked', true);

        $('.SelectAll_Dynamic').click(function() {
            const select_Id = this.id;
            const checked = $(this).prop('checked');
            $('#tabs-' + select_Id).find('input:checkbox').prop('checked', checked);
            checkedLength($(this));
        });

        $('.tab-pane').find('input:checkbox:not(:first)').click(function() {
            if (!$(this).is(':checked')) {
                $(this).closest('.tab-pane').find('input:checkbox:first').prop('checked', false);
                checkedLength($(this));
            } else {
                const checkbox_length = $(this).closest('.tab-pane').find('input:checkbox:not(:first)').length;
                const checked_check_box_length = $(this).closest('.tab-pane').find('input:checkbox:not(:first):checked').length;
                if (checkbox_length == checked_check_box_length) {
                    $(this).closest('.tab-pane').find('input:checkbox:first').prop('checked', true);
                }
                checkedLength($(this));
            }
        });
        
        let checkedLength = function(element) {
            const checkedlength = element.closest('.tab-pane.active').find('input:checkbox:not(:first):checked').length;
            if (checkedlength > 0) {
                element.closest('.tab-pane.active').find('#checkedPH').text(checkedlength);
            } else {
                element.closest('.tab-pane.active').find('#checkedPH').text(' ');
            }
        };

        checkboxes.change(function () {
            const lengthchecked = $(this).closest('.tab-pane.active').find('input:checkbox:not(:first):checked').length;
            const tabs_id = this.id;
            $('#AccPH-' + tabs_id).prop('disabled', lengthchecked < 2);
            $('#CancelPH-' + tabs_id).prop('disabled', lengthchecked < 2);
        });
        checkboxes.change();

        $('#formSelectAllNew, #formSelectAllVerified, #formSelectAllWaitingIn, #formSelectAllReschedule, #formSelectAllExtend, #formSelectAllCancel').submit(function(eventObj) {
            var personalHomecareID =[]
            $('.checkBoxPH:checked').each(function(i){
                personalHomecareID[i] = $(this).val();
            });
            $(this).append(`<input type="hidden" name="phcData" value="${personalHomecareID}" /> `);
            return true;
        });
    });



</script>

{{-- checkbox untuk HS --}}
<script type="text/javascript">
     $(document).ready(function() {
    //     $('#terpilihRescheduleHS').text(' ');

    //     var jumlahTerpilih = function() {
    //         var terpilih = $(".checkHS:checked").length;
    //         if (terpilih > 0) {
    //             $("#terpilih").text(terpilih);
    //         } else {
    //             $("#terpilih").text(' ');
    //         }
    //     };

    //     $("#checkAll").click(function() {
    //         $('.checkHS').prop('checked', this.checked);
    //         jumlahTerpilih();
    //     });

    //     $('.checkHS').change(function() {
    //         $("#checkAll").prop("checked", $(".checkHS").length === $(".checkHS:checked").length);
    //         jumlahTerpilih();
    //     });

    //     var checkBoxes = $('.tableHS .checkBoxes');
    //     checkBoxes.change(function () {
    //         $('#AccHS').prop('disabled', checkBoxes.filter(':checked').length < 2);
    //         $('#CancelHS').prop('disabled', checkBoxes.filter(':checked').length < 2);
    //     });
    //     $('.tableHS .checkBoxes').change();

        $('#formSelectAllHomeServiceReschedule, #formSelectAllHomeServiceCancel').submit(function(eventObj) {
            var homeServiceID =[]
            $(this).closest('div[id^="tabs-"]').find('.checkBoxHS:checked').each(function(i){
                homeServiceID[i] = $(this).val();
            });
            $(this).append(`<input type="hidden" name="homeServiceData" value="${homeServiceID}" /> `);
            return true;
        });
    });
</script>
@endsection
