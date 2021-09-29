<?php
$menu_item_page = "personal_homecare";
?>
@extends('admin.layouts.template')

@section('style')
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

    input, select, textarea {
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

    /* Mark input boxes that gets an error on validation: */
    input.invalid {
        background-color: #ffdddd;
    }

    input[type='checkbox'], input[type='radio'] {
        margin-left: 0px !important;
    }

    .checkin{
        display: block;
    }

    .checkin-mob{
        display: none;
    }

    .wrap {
        display: flex;
        flex-flow: wrap;
        flex-direction: initial;
        padding-bottom: 2em;
    }
    
    .cell-wrap {
        vertical-align: top;
    }

    .cell-wrap.left {
        width: 50%;
        padding-right: 20px;  /* if you need some whitespace */
    }

    .cell-wrap.right {
        flex: 1;
    }

    table {
        font-size: 14px;
        height: 100%;
        width: 100%;
    }

    table thead {
        background-color: #00000030;
        text-align: center;
    }

    table td {
        border: 0.5px #8080801a solid;
        padding: 0.5em;
    }

    .justify-content-center {
        padding: 0em 1em;
    }

    .div-CheckboxGroup {
        border: solid 1px rgba(128, 128, 128, 0.32941);
        padding: 10px;
        border-radius: 3px;
        background-color: white;
    }

    .btn:hover{
        color: #fff !important;
    }

    /*-- mobile --*/
    @media (max-width: 768px) {
        .card-body h2 {
            font-size: 1.3em;
        }

        .btn {
            display: inline-block;
            padding-left: 2em !important;
            padding-right: 2em !important;
            margin-top: 1em;
        }

        img {
            height: 150px;
        }
    }

    @media (max-width: 782px) {
        .checkin{
            display: none;
        }

        .checkin-mob{
            display: block;
        }

        .cell-wrap {
            display: block;
            height: auto;
        }

        .cell-wrap.left {
            width: 100%;
            padding-right: 0;
        }

        .cell-wrap.right {
            width: 100%;
            margin-top: 2em;
        }

        .row-btn {
            margin-top: 1em;
        }
    }

    @media (min-width: 768px) {
        .table-responsive::-webkit-scrollbar {
            display: none;
        }
    }
    #element-to-print p{
      font-size: 1.1em;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div id="header">
            <div class="page-header">
                <h3 class="page-title">Detail Personal Homecare</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a data-toggle="collapse"
                                href="#"
                                aria-expanded="false"
                                aria-controls="acceptance-dd">
                                Personal Homecare
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Detail
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <h2>Status Personal Homecare</h2>
                        </div>
                        <div class="row justify-content-center">
                            <h3>{{ ucwords($personalhomecare['status']) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <h2>Customer Data</h2>
                        </div>
                        <div class="row justify-content-center">
                            <div class="table-responsive">
                                <table class="col-md-12 table-borderless">
                                    <thead style="visibility: hidden;">
                                        <th style="width: 30%;">Data</th>
                                        <th style="width: 5%;">:</th>
                                        <th style="width: 65%;">Customer</th>
                                    </thead>
                                    <tr>
                                        <td>Customer Name</td>
                                        <td>:</td>
                                        <td>{{ $personalhomecare['name'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Customer Phone</td>
                                        <td>:</td>
                                        <td>{{ $personalhomecare['phone'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Customer Address</td>
                                        <td>:</td>
                                        <td>
                                            {{ $personalhomecare['address'] }},
                                            {{ $personalhomecare->getProvinceName() }},
                                            {{ $personalhomecare->getCityFullName() }},
                                            {{ $personalhomecare->getDistrictName() }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Branch</td>
                                        <td>:</td>
                                        <td>
                                            {{ $personalhomecare->branch->code }} - {{ $personalhomecare->branch->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>CSO</td>
                                        <td>:</td>
                                        <td>
                                            {{ $personalhomecare->cso->code }} - {{ $personalhomecare->cso->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Id Card Image</td>
                                        <td>:</td>
                                        <td style="text-align: center;">
                                            <img style="height: 300px" src="{{ asset('sources/phc') . '/' . $personalhomecare['id_card'] }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Wakimart Member Image</td>
                                        <td>:</td>
                                        <td style="text-align: center;">
                                            <img style="height: 300px" src="{{ asset('sources/phc') . '/' . $personalhomecare['member_wakimart'] }}">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card" style="padding: 1em;">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h2 class="text-center">Product Out Checklist</h2>
                            </div>
                            <div class="col-md-6 checkin">
                                <h2 class="text-center">Product In Checklist</h2>
                            </div>
                        </div>
                        <div class="wrap mt-3">
                            <div class="cell-wrap left">
                                <table class="table-bordered">
                                    <thead style="display: none;">
                                        <th style="width: 30%;">Checklist</th>
                                        <th style="width: 70%;">Content</th>
                                    </thead>
                                    <tr>
                                        <td>Product</td>
                                        <td>
                                            {{ $personalhomecare->personalHomecareProduct->code }} ({{ $personalhomecare->personalHomecareProduct->product['code'] }} - {{ $personalhomecare->personalHomecareProduct->product['name'] }})
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Schedule Date</td>
                                        <td>
                                            {{ date("d/m/Y", strtotime($personalhomecare['schedule'])) }}
                                        </td>
                                    </tr>

                                    @php
                                        $prd_firstLetter = substr($personalhomecare->personalHomecareProduct['code'], 0, 1);
                                        $arr_completness = App\PersonalHomecareChecklist::$completeness_list[$prd_firstLetter];
                                    @endphp

                                    <tr>
                                        <td rowspan="{{ sizeof($arr_completness)+1 }}">Completeness</td>
                                        <td>
                                            <i class="mdi 
                                                @if(isset($personalhomecare->checklistOut['condition']['completeness']))
                                                    {{ in_array($arr_completness[0],
                                                    $personalhomecare->checklistOut['condition']['completeness']) ?
                                                    "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                @else
                                                    mdi-checkbox-blank-outline"
                                                @endif
                                                style="font-size: 24px; color: #fed713;">
                                            </i>
                                            {{$arr_completness[0]}}
                                        </td>
                                    </tr>

                                    @for($idx_i = 1; $idx_i < sizeof($arr_completness); $idx_i++)
                                        <tr>
                                            <td>
                                                <i class="mdi 
                                                    @if(isset($personalhomecare->checklistOut['condition']['completeness']))
                                                        {{ in_array($arr_completness[$idx_i],
                                                        $personalhomecare->checklistOut['condition']['completeness']) ?
                                                        "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                    @else
                                                        mdi-checkbox-blank-outline"
                                                    @endif
                                                    style="font-size: 24px; color: #fed713;">
                                                </i>
                                                {{$arr_completness[$idx_i]}}
                                            </td>
                                        </tr>
                                    @endfor

                                    <tr>
                                        <td>
                                            <i class="mdi {{ isset($personalhomecare->checklistOut['condition']['other']) ?
                                                "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                style="font-size: 24px; color: #fed713;">
                                            </i>
                                            Other :
                                            {{ isset($personalhomecare->checklistOut['condition']['other']) ?
                                                $personalhomecare->checklistOut['condition']['other'] : "-" }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Machine Condition</td>
                                        <td>
                                            {{ ucwords($personalhomecare->checklistOut['condition']['machine']) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Physical Condition</td>
                                        <td>
                                            {{ ucwords($personalhomecare->checklistOut['condition']['physical']) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Product Photo</td>
                                        <td>
                                            @if(isset($personalhomecare->checklistOut['image']))
                                                @foreach ($personalhomecare->checklistOut['image'] as $img)
                                                    <img src="{{asset('sources/phc-checklist') . '/' . $img}}"
                                                        height="300px"
                                                        style="margin-bottom: 15px;"
                                                        alt="Product Personal Homecare" />
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                </table>                          
                            </div>
                            <div class="cell-wrap right">
                                <div class="row justify-content-center">
                                    <h2 class="text-center checkin-mob">Product In Checklist</h2>
                                </div>
                                <table class="table-bordered">
                                    <thead style="display: none;">
                                        <th style="width: 30%;">
                                            Checklist
                                        </th>
                                        <th style="width: 70%;">
                                            Content
                                        </th>
                                    </thead>
                                    <tr>
                                        <td>Product</td>
                                        <td>
                                            {{ $personalhomecare->personalHomecareProduct->code }} ({{ $personalhomecare->personalHomecareProduct->product['code'] }} - {{ $personalhomecare->personalHomecareProduct->product['name'] }})
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Schedule Date</td>
                                        <td>
                                            {{ isset($personalhomecare->checklistIn["created_at"]) ? $personalhomecare->checklistIn["created_at"] : "-" }}
                                        </td>
                                    </tr>

                                    @php
                                        $prd_firstLetter = substr($personalhomecare->personalHomecareProduct['code'], 0, 1);
                                        $arr_completness = App\PersonalHomecareChecklist::$completeness_list[$prd_firstLetter];
                                    @endphp

                                    <tr>
                                        <td rowspan="{{ sizeof($arr_completness)+1 }}">Completeness</td>
                                        <td>
                                            <i class="mdi {{ isset($arr_completness[0],
                                                $personalhomecare->checklistIn['condition']['completeness']) ?
                                                "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                style="font-size: 24px; color: #fed713;">
                                            </i>
                                            {{ $arr_completness[0] }}
                                        </td>
                                    </tr>

                                    @for($idx_i = 1; $idx_i < sizeof($arr_completness); $idx_i++)
                                        <tr>
                                            <td>
                                                <i class="mdi {{ isset($arr_completness[$idx_i],
                                                    $personalhomecare->checklistIn['condition']['completeness']) ?
                                                    "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                    style="font-size: 24px; color: #fed713;">
                                                </i>
                                                {{ $arr_completness[$idx_i] }}
                                            </td>
                                        </tr>
                                    @endfor

                                    <tr>
                                        <td>
                                            <i class="mdi {{ isset($personalhomecare->checklistIn['condition']['other']) ?
                                                "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                style="font-size: 24px; color: #fed713;">
                                            </i>
                                            Other :
                                            {{ isset($personalhomecare->checklistIn['condition']['other']) ?
                                                $personalhomecare->checklistIn['condition']['other'] : "-" }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Machine Condition</td>
                                        <td>
                                            {{ isset($personalhomecare->checklistIn['condition']['machine']) ? ucwords($personalhomecare->checklistIn['condition']['machine']) : "-" }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Physical Condition</td>
                                        <td>
                                            {{ isset($personalhomecare->checklistIn['condition']['physical']) ? ucwords($personalhomecare->checklistIn['condition']['physical']) : "-" }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Product Photo</td>
                                        <td>
                                            @php
                                                $image = $personalhomecare->checklistIn['image'];
                                            @endphp

                                            @if(isset($image))
                                                @foreach ($image as $img)
                                                    <img src="{{asset('sources/phc-checklist') . '/' . $img}}"
                                                        height="300px"
                                                        style="margin-bottom: 15px;"
                                                        alt="Product Personal Homecare" />
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        @if(strtolower($personalhomecare['status']) == "process" || strtolower($personalhomecare['status']) == "process_extend")
                            <div class="row justify-content-center row-btn">
                                <div class="form-group"
                                    style="margin-bottom: 0;">
                                    <button type="button"
                                        class="btn btn-gradient-primary mr-2 btn-lg"
                                        data-toggle="modal"
                                        data-target="#modal-checklist-in">
                                        Check In Product
                                    </button>
                                </div>
                            </div>
                        @endif

                        @if(strtolower($personalhomecare['status']) == "waiting_in" && Gate::check('change-status-checkin-personalhomecare'))
                            <div class="row justify-content-center row-btn">
                                <div class="form-group"
                                    style="margin-bottom: 0;">
                                    <button type="button"
                                        class="btn btn-gradient-primary mr-2 btn-lg"
                                        data-toggle="modal"
                                        data-target="#modal-checklist-in">
                                        Update Product Check In
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if (strtolower($personalhomecare['status']) == "new" && Gate::check('change-status-verified-personalhomecare'))
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>Status Personal Homecare (Is Verified ?)</h2>
                            </div>
                            <form id="actionAdd"
                                class="forms-sample"
                                method="POST"
                                action="{{ route("update_personal_homecare_status") }}">
                                @csrf
                                <input type="hidden"
                                    name="id"
                                    value="{{ $personalhomecare['id'] }}" />
                                <input type="hidden"
                                    name="id_product"
                                    value="{{ $personalhomecare->personalHomecareProduct['id'] }}" />
                                <div class="form-group row justify-content-center">
                                    <button type="submit"
                                        class="btn btn-gradient-primary mr-2 btn-lg"
                                        name="status"
                                        value="verified">
                                        Approved
                                    </button>
                                    <button type="submit"
                                        class="btn btn-gradient-danger mr-2 btn-lg"
                                        name="status"
                                        value="rejected">
                                        Reject
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @elseif (strtolower($personalhomecare['status']) == "verified" && Gate::check('change-status-checkout-personalhomecare'))
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>Status Personal Homecare (Can Checkout ?)</h2>
                            </div>
                            <form id="actionAdd"
                                class="forms-sample"
                                method="POST"
                                action="{{ route("update_personal_homecare_status") }}">
                                @csrf
                                <input type="hidden"
                                    name="id"
                                    value="{{ $personalhomecare['id'] }}" />
                                <input type="hidden"
                                    name="id_product"
                                    value="{{ $personalhomecare->personalHomecareProduct['id'] }}" />
                                <div class="form-group row justify-content-center">
                                    <button type="submit"
                                        class="btn btn-gradient-primary mr-2 btn-lg"
                                        name="status"
                                        value="approve_out">
                                        Approved
                                    </button>
                                    <button type="submit"
                                        class="btn btn-gradient-danger mr-2 btn-lg"
                                        name="status"
                                        value="rejected">
                                        Reject
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @elseif (strtolower($personalhomecare['status']) == "approve_out")
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>Upload Receipt Photo with Customer</h2>
                            </div>
                            <div class="form-group row justify-content-center">
                                <button type="button"
                                        data-toggle="modal"
                                        data-target="#modal-upload-receipt-photo" 
                                        class="btn btn-gradient-primary mr-2 btn-lg">
                                    Upload Photo
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif (strtolower($personalhomecare['status']) == "waiting_in" && Gate::check('change-status-checkin-personalhomecare'))
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>Status Personal Homecare (Check In)</h2>
                            </div>
                            <form id="actionAdd"
                                class="forms-sample"
                                method="POST"
                                action="{{ route("update_personal_homecare_status") }}">
                                @csrf
                                <input type="hidden"
                                    name="id"
                                    value="{{ $personalhomecare['id'] }}" />
                                <input type="hidden"
                                    name="id_product"
                                    value="{{ $personalhomecare->personalHomecareProduct['id'] }}" />
                                <div class="form-group row justify-content-center">
                                    <button type="submit"
                                        class="btn btn-gradient-primary mr-2 btn-lg"
                                        name="status"
                                        value="done">
                                        Approved
                                    </button>
                                    <button type="submit"
                                        class="btn btn-gradient-danger mr-2 btn-lg"
                                        name="status"
                                        value="pending_product">
                                        Reject
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($personalhomecare['reschedule_date'] != null && Gate::check('acc-reschedule-personalhomecare'))
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>Reschedule from {{ date("d/m/Y", strtotime($personalhomecare['schedule'])) }} to {{ date("d/m/Y", strtotime($personalhomecare['reschedule_date'])) }}</h2>
                            </div>
                            <form id="actionAdd"
                                class="forms-sample"
                                method="POST"
                                action="{{ route("reschedule_personal_homecare") }}">
                                @csrf
                                <input type="hidden"
                                    name="id"
                                    value="{{ $personalhomecare['id'] }}" />
                                <div class="form-group row justify-content-center">
                                    <button type="submit"
                                        class="btn btn-gradient-primary mr-2 btn-lg"
                                        name="status"
                                        value="acceptance">
                                        Approved
                                    </button>
                                    <button type="submit"
                                        class="btn btn-gradient-danger mr-2 btn-lg"
                                        name="status"
                                        value="rejected">
                                        Reject
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if ($personalhomecare['is_extend'] && Gate::check('acc-extend-personalhomecare'))
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>Extended from {{ date("Y-m-d", strtotime($personalhomecare['schedule'] .  " + 5 days")) }} to {{ date("Y-m-d", strtotime($personalhomecare['schedule'] .  " + 8 days")) }}</h2>
                            </div>
                            <form id="actionAdd"
                                class="forms-sample"
                                method="POST"
                                action="{{ route("update_personal_homecare_status") }}">
                                @csrf
                                <input type="hidden"
                                    name="id"
                                    value="{{ $personalhomecare['id'] }}" />
                                <div class="form-group row justify-content-center">
                                    <button type="submit"
                                        class="btn btn-gradient-primary mr-2 btn-lg"
                                        name="status"
                                        value="process_extend">
                                        Approved
                                    </button>
                                    <button type="submit"
                                        class="btn btn-gradient-danger mr-2 btn-lg"
                                        name="status"
                                        value="">
                                        Reject
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (strtolower($personalhomecare['status']) != "new" && strtolower($personalhomecare['status']) != "rejected" )
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                          <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>
                                    Personal Homecare Option
                                </h2>
                            </div>
                            @php
                                $urlShareWa = route('personal_homecare', ['id' => $personalhomecare->id]);
                                if(strtolower($personalhomecare['status']) == "done"){
                                    $urlShareWa = route('thankyou_ph', ["id" => $personalhomecare->id]);
                                }
                            @endphp
                            <form class="forms-sample"
                                method="GET"
                                action="https://wa.me/"
                                target="_blank">
                                <div class="form-group row justify-content-center mt-3">
                                    <button id="upgradeProcess"
                                        type="submit"
                                        class="btn btn-gradient-primary m-1"
                                        name="text"
                                        value="Terima Kasih telah mengikuti *Program Pinjamin Produk 5 Hari*. Berikut adalah tautan bukti formulir ( {{ $urlShareWa }} )">
                                        Share WhatsApp
                                        <span><i class="mdi mdi-whatsapp menu-icon" style="margin-left: 5px; font-size: 24px; vertical-align: middle;"></i></span>
                                    </button>
                                    <button type="button"
                                        class="btn btn-gradient-info m-1"
                                        style="margin-left: 1em !important;"
                                        data-toggle="modal"
                                        data-target="#modal-extend">
                                        Extend
                                        <span><i class="mdi mdi-calendar-text menu-icon" style="margin-left: 5px; font-size: 24px; vertical-align: middle;"></i></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (strtolower($personalhomecare['status']) != "new" && strtolower($personalhomecare['status']) != "rejected" && strtolower($personalhomecare['status']) != "verified" )
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                          <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>
                                    Create To PDF
                                </h2>
                            </div>
                            <form class="forms-sample"
                                method="GET"
                                action=""
                                target="_blank">
                                <div class="form-group row justify-content-center mt-3">
                                    <button type="button"
                                        class="btn btn-gradient-info mr-2 btn-lg"
                                        data-toggle="modal"
                                        data-target="#modal-pre-print">
                                        Create PDF
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div id="element-to-print" class="col-12 grid-margin stretch-card showPrinted">
                <div class="card">
                    <div class="card-body">
                        <div style="background-color: #008349; height: 15px;"></div>
                        <div style="width: 200px; margin: auto;">
                            <img style="width: 100%;"
                                src="{{ asset('sources/logosince.svg') }}"
                                alt="logo" />
                        </div>
                        <div>
                            <div style="width: 80%; margin: auto; text-align: center;">
                                <h1 style="font-weight: 700;">
                                    SURAT TANDA TERIMA BARANG
                                </h1>
                                <h4>
                                    PROGRAM PINJAMIN PRODUK 5 HARI : COBA PRODUK DI RUMAH SENDIRI
                                <h4>
                            </div>
                            <br>
                            <div style="width: 90%; margin: auto; text-align: justify;">
                                <p style="font-size:1.1em">Saya yang bertanda tangan, menyatakan telah menerima barang dalam keadaan baik dan berfungsi normal sesuai data di bawah ini. Demikian surat tanda terima ini dibuat sebagai bukti yang sah.
                            </div>
                            <br>
                            <div>
                                <div style="width: 300px; background-color: #b4d9c4;">
                                    <h4 style="padding-left: 15%;">
                                        DATA CUSTOMER
                                    </H4>
                                </div>
                            </div>
                        </div>
                        <div style="width: 90%; margin: auto; display: table; margin-bottom: 1.5em;">
                            <div style="width: 48%; margin-right: 3%; margin-bottom: 5px; float: left; border-bottom: 1px solid black;">
                                <h5>NAMA: {{ $personalhomecare['name'] }}</h5>
                            </div>
                            <div style="width: 48%; margin-bottom: 5px; float: left; border-bottom: 1px solid black;">
                                <h5>
                                    CABANG: {{ $personalhomecare->branch->code }}
                                </h5>
                            </div>
                            <div style="width: 48%; margin-right: 3%; margin-bottom: 5px; float: left; border-bottom: 1px solid black;">
                                <h5>
                                    NO.TELP: {{ $personalhomecare['phone'] }}
                                </h5>
                            </div>
                            <div style="width: 48%; margin-bottom:5px; float: left; border-bottom:1px solid black;">
                                <h5>
                                    TGL PENGIRIMAN: {{ $personalhomecare['schedule'] }}
                                </h5>
                            </div>
                            <div style="width: 48%; margin-right: 3%; margin-bottom: 5px; float: left; border-bottom: 1px solid black;">
                                <h5>NO.MEMBER:</h5>
                            </div>
                            <div style="width: 48%; margin-bottom: 5px; float: left; border-bottom: 1px solid black;">
                                <h5>
                                    TGL PENGAMBILAN: {{ date("Y-m-d", strtotime($personalhomecare['schedule'] .  " + 5 days")) }}
                                </h5>
                            </div>
                        </div>
                        <div style="margin:auto;">
                            <div>
                                <table class="col-md-12" style="border: 1px solid black; width: 100%;">
                                    <thead>
                                        <td class="text-center" style="border: 1px solid black;">Jumlah (Qty)</td>
                                        <td class="text-center" style="border: 1px solid black;">Kode Produk</td>
                                        <td class="text-center" style="border: 1px solid black;">Nama Produk</td>
                                        <td class="text-center" style="border: 1px solid black;">Kelengkapan</td>
                                        <td class="text-center" style="border: 1px solid black;">Kondisi</td>
                                        <td class="text-center" style="border: 1px solid black;">Keterangan</td>
                                    </thead>
                                    <tr>
                                        <td class="text-center" style="border: 1px solid black;">1</td>
                                        <td class="text-center" style="border: 1px solid black;">
                                            {{ $personalhomecare->personalHomecareProduct->code }}
                                        </td>
                                        <td class="text-center" style="border: 1px solid black;">
                                            {{ $personalhomecare->personalHomecareProduct->product->name }}
                                        </td>
                                        <td style="border: 1px solid black;">
                                            <ul>
                                                @php
                                                    $prd_firstLetter = substr($personalhomecare->personalHomecareProduct['code'], 0, 1);
                                                    $arr_completness = App\PersonalHomecareChecklist::$completeness_list[$prd_firstLetter];
                                                @endphp
                                                @foreach ($arr_completness as $completeness)
                                                    @if ($completeness !== "other")
                                                        <li style="line-height: 1.3;">
                                                            {{ ucwords($completeness) }}
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td style="border: 1px solid black;">
                                            <b>MESIN</b>
                                            <ul>
                                                <li>Normal</li>
                                                <li>Need Repair</li>
                                            </ul>
                                            <b>FISIK</b>
                                            <ul>
                                                <li>New</li>
                                                <li>Moderate</li>
                                                <li>Need Repair</li>
                                            </ul>
                                        </td>
                                        <td class="text-center" style="border: 1px solid black;"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div style="width:90%;margin:1.5em auto 0;text-align:justify">
                            <p style="font-size:1.1em">Demikian surat tanda terima ini dibuat sebagai bukti yang sah.
                            <h5>SYARAT & KETENTUAN:</h5>
                            <div style="width: 90%; margin: auto;">
                                <ol type="1" style="font-size:1.1em">
                                    <li>Program pinjamin produk 5 hari (PP5H) tidak dipungut biaya apapun.</li>
                                    <li>Sudah menjadi member WAKimart dan minimal berusia 35 tahun</li>
                                    <li>Apabila ada kekurangan, kehilangan ataupun kerusakan pada produk saat pengembalian, berarti membeli barang dengan harga normal sesuai ketentuan yang ada.</li>
                                    <li>Kekurangan aksesoris produk harap dikembalikan paling lambat 7 hari setelah program PP5H berakhir.</li>
                                    <li>Syarat dan Ketentuan dapat berubah tanpa pemeberitahuan sebelumnya.</li>
                                    <li>Kenyamanan dan keamanan konsumen kami adalah prioritas pertama.</li>
                                </ol>
                                <p style="font-size:1.1em">Apabila ada sesuatu atau ada pertanyaan, bisa menghubungi kami melalui facebook page WAKi Indonesia ataau customer care kami : 0815-5467-3357</p>
                            </div>
                        </div>
                        <br><br><br><br><br><br><br>
                        <div style="width: 80%; margin: auto; text-align: justify;">
                            <div style="width: 48%; float: left;">
                                <div style="width: 70%; margin: auto; border-top: 4px solid black; text-align: center;">
                                    <p>Tanda Tangan Penerima</p>
                                </div>
                            </div>
                            <div style="width: 48%; float: left;">
                                <div style="width: 70%; margin: auto; border-top: 4px solid black; text-align: center;">
                                    <p>Tanda Tangan Pengirim</p>
                                </div>
                            </div>
                        </div>
                        <br><br>
                        <div class="clearfix"></div>
                        <div class="clearfix"></div>
                        <p style="page-break-after: always;">&nbsp;</p>
                        <p style="page-break-before: always;">&nbsp;</p>
                        <div>
                            <div style="width: 80%; margin: auto; text-align: center;">
                                <h1 style="font-weight: 700;">
                                    SURAT PENGAMBILAN BARANG
                                </h1>
                                <h4>
                                    PROGRAM PINJAMIN PRODUK 5 HARI : COBA PRODUK DI RUMAH SENDIRI
                                <h4>
                            </div>
                        </div>
                        <br>
                        <div style="margin:auto">
                            <div>
                                <table class="col-md-12" style="border: 1px solid black;width: 100%;">
                                    <thead>
                                        <td class="text-center" style="border: 1px solid black;">Jumlah (QTY)</td>
                                        <td class="text-center" style="border: 1px solid black;">Kode Produk</td>
                                        <td class="text-center" style="border: 1px solid black;">Nama Produk</td>
                                        <td class="text-center" style="border: 1px solid black;">Kelengkapan</td>
                                        <td class="text-center" style="border: 1px solid black;">Kondisi</td>
                                        <td class="text-center" style="border: 1px solid black;">Keterangan</td>
                                    </thead>
                                    <tr>
                                        <td class="text-center" style="border: 1px solid black;">1</td>
                                        <td class="text-center" style="border: 1px solid black;">
                                            {{ $personalhomecare->personalHomecareProduct->code }}
                                        </td>
                                        <td class="text-center" style="border: 1px solid black;">
                                            {{ $personalhomecare->personalHomecareProduct->product->name }}
                                        </td>
                                        <td style="border: 1px solid black;">
                                            <ul style="list-style-type: circle;">
                                                @php
                                                    $prd_firstLetter = substr($personalhomecare->personalHomecareProduct['code'], 0, 1);
                                                    $arr_completness = App\PersonalHomecareChecklist::$completeness_list[$prd_firstLetter];
                                                @endphp
                                                @foreach ($arr_completness as $completeness)
                                                    @if ($completeness !== "other")
                                                        <li style="line-height: 1.3;">
                                                            {{ ucwords($completeness) }}
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td style="border: 1px solid black;">
                                            <b>MESIN</b>
                                            <ul style="list-style-type: circle;">
                                                <li>Normal</li>
                                                <li>Need Repair</li>
                                            </ul>
                                            <b>FISIK</b>
                                            <ul style="list-style-type: circle;">
                                                <li>New</li>
                                                <li>Moderate</li>
                                                <li>Need Repair</li>
                                            </ul>
                                        </td>
                                        <td class="text-center" style="border: 1px solid black;"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                         <br><br><br><br><br><br><br><br>
                        <div style="width: 80%; margin: auto; text-align: justify;">
                            <div style="width: 48%; float: left;">
                                <div style="width: 70%; margin: auto; border-top: 4px solid black; text-align: center;">
                                    <p>Tanda Tangan Customer</p>
                                </div>
                            </div>
                            <div style="width: 48%; float: left;">
                                <div style="width: 70%; margin: auto; border-top: 4px solid black; text-align: center;">
                                    <p>Tanda Tangan Pengambil</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jika bisa melihat history log-->
        @if ($histories->isNotEmpty())
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>Personal Homecare History Log</h2>
                            </div>
                            <div class="row justify-content-center">
                                <div class="table-responsive">
                                    <table class="col-md-12">
                                        <thead>
                                            <td class="text-center">No.</td>
                                            <td>Action</td>
                                            <td>User</td>
                                            <td>Change</td>
                                            <td class="text-center">Time</td>
                                        </thead>
                                        @foreach ($histories as $key => $history)
                                            <?php $dataChange = json_decode($history->meta, true); ?>
                                            <tr>
                                                <td class="text-right">
                                                    {{ $key + 1 }}
                                                </td>
                                                <td>{{ $history->method }}</td>
                                                <td>{{ $history->name }}</td>
                                                <td>
                                                    @foreach ($dataChange["dataChange"] as $dataKey => $value)
                                                        <b>{{ $dataKey }}</b>: {{ var_export($value, true) }}
                                                        <br>
                                                    @endforeach
                                                </td>
                                                <td class="text-center">
                                                    {{ date("d/m/Y H:i:s", strtotime($history->created_at)) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!-- end if-->
    </div>
</div>

<div class="modal" id="modal-upload-receipt-photo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Receipt Photo with Customer</h5>
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="upload-receipt-photo"
                method="POST"
                enctype="multipart/form-data"
                action="{{ route("update_personal_homecare_status") }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden"
                        name="id"
                        value="{{ $personalhomecare['id'] }}" />
                    <input type="hidden"
                        name="id_product"
                        value="{{ $personalhomecare->personalHomecareProduct['id'] }}" />
                    <input type="hidden"
                        name="status"
                        value="process" />

                    <div class="form-group">
                        <label for="product-photo-1">Product Photo</label>
                        <input type="file"
                            class="form-control"
                            accept="image/jpeg, image/png"
                            name="product_photo_1"
                            id="product-photo-1"
                            required />
                    </div>

                    <div class="form-group">
                        <label for="product-photo-2">Product Photo with CSO and Customer</label>
                        <input type="file"
                            class="form-control"
                            accept="image/jpeg, image/png"
                            name="product_photo_2"
                            id="product-photo-2"
                            required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary" id="btn-checkin">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal-checklist-in" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Checklist In</h5>
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="add-phc"
                method="POST"
                enctype="multipart/form-data"
                action="{{ route("update_personal_homecare_checklist_in") }}">
                @csrf

                @if($personalhomecare['status'] == "waiting_in" && $personalhomecare->personalHomecareProduct['status'] == "pending")
                    <input type="hidden"
                        name="id_checklist"
                        value="{{ $personalhomecare->checklistIn['id'] }}" />
                @endif

                @php
                    $isWaitingInChecklist = ($personalhomecare['status'] == "waiting_in" && $personalhomecare->personalHomecareProduct['status'] == "pending");
                    $phcproducts = $personalhomecare->personalHomecareProduct;
                @endphp
                
                <div class="modal-body">
                    <input type="hidden"
                        name="id"
                        value="{{ $personalhomecare['id'] }}" />
                    <div class="form-group">
                        <span style="display: block;">Completeness</span>
                        <div class="div-CheckboxGroup">

                            @foreach($arr_completness as $checklistInput)
                                <div class="form-check">
                                    <label for="completeness-{{$checklistInput}}"
                                        class="form-check-label">
                                        <input type="checkbox"
                                            name="completeness[]"
                                            id="completeness-{{$checklistInput}}"
                                            value="{{$checklistInput}}"
                                            form="add-phc" 
                                            @if($phcproducts->currentChecklist != null)
                                                {{ in_array($checklistInput, $phcproducts->
                                                currentChecklist['condition']['completeness']) && $isWaitingInChecklist ?
                                                "checked" : "" }}
                                            @endif
                                            />
                                        {{$checklistInput}}
                                    </label>
                                </div>
                            @endforeach
                            
                            <div class="form-check">
                                <label for="completeness-other"
                                    class="form-check-label">
                                    <input type="checkbox"
                                        name="completeness[]"
                                        id="completeness-other"
                                        value="other"
                                        form="add-phc"
                                        onchange="showOtherInput(this)" 
                                        @if($phcproducts->currentChecklist != null)
                                            {{ in_array("other", $phcproducts->
                                            currentChecklist['condition']['completeness']) && $isWaitingInChecklist ?
                                            "checked" : "" }}
                                        @endif
                                        />
                                    Other
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="text"
                                    class="form-control d-none"
                                    placeholder="Other description"
                                    name="other_completeness"
                                    id="other-text"
                                    form="add-phc" 
                                    value="{{ $isWaitingInChecklist ? $phcproducts->currentChecklist['condition']['other'] : "" }}"/>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <span style="display: block;">Machine Condition</span>
                        <div class="div-CheckboxGroup">
                            <div class="form-check">
                                <label for="machine-condition-normal"
                                    class="form-check-label">
                                    <input type="radio"
                                        class="form-check-input"
                                        name="machine_condition"
                                        id="machine-condition-normal"
                                        value="normal"
                                        form="add-phc"
                                        required 
                                        {{ $phcproducts->currentChecklist
                                        ['condition']['machine'] == "normal" && $isWaitingInChecklist ?
                                        "checked" : "" }}/>
                                    Normal
                                </label>
                            </div>
                            <div class="form-check">
                                <label for="machine-condition-need-repair"
                                    class="form-check-label">
                                    <input type="radio"
                                        class="form-check-input"
                                        name="machine_condition"
                                        id="machine-condition-need-repair"
                                        value="need_repair"
                                        form="add-phc"
                                        required 
                                        {{ $phcproducts->currentChecklist
                                        ['condition']['machine'] == "need_repair" && $isWaitingInChecklist ?
                                        "checked" : "" }}/>
                                    Need Repair
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <span style="display: block;">Physical Condition</span>
                        <div class="div-CheckboxGroup">
                            <div class="form-check">
                                <label for="physical-condition-new"
                                    class="form-check-label">
                                    <input type="radio"
                                        class="form-check-input"
                                        name="physical_condition"
                                        id="physical-condition-new"
                                        value="new"
                                        form="add-phc"
                                        required 
                                        {{ $phcproducts->currentChecklist
                                        ['condition']['physical'] == "new" && $isWaitingInChecklist ?
                                        "checked" : "" }}/>
                                    New
                                </label>
                            </div>
                            <div class="form-check">
                                <label for="physical-condition-moderate"
                                    class="form-check-label">
                                    <input type="radio"
                                        class="form-check-input"
                                        name="physical_condition"
                                        id="physical-condition-moderate"
                                        value="moderate"
                                        form="add-phc"
                                        required 
                                        {{ $phcproducts->currentChecklist
                                        ['condition']['physical'] == "moderate" && $isWaitingInChecklist ?
                                        "checked" : "" }}/>
                                    Moderate
                                </label>
                            </div>
                            <div class="form-check">
                                <label for="physical-condition-need-repair"
                                    class="form-check-label">
                                    <input type="radio"
                                        class="form-check-input"
                                        name="physical_condition"
                                        id="physical-condition-need-repair"
                                        value="need_repair"
                                        form="add-phc"
                                        required 
                                        {{ $phcproducts->currentChecklist
                                        ['condition']['physical'] == "need_repair" && $isWaitingInChecklist ?
                                        "checked" : "" }}/>
                                    Need Repair
                                </label>
                            </div>
                        </div>
                    </div>

                    @if(!$isWaitingInChecklist)
                        <div class="form-group">
                            <label for="product-photo-1">Product Photo 1</label>
                            <input type="file"
                                class="form-control"
                                accept="image/jpeg, image/png"
                                name="product_photo_1"
                                id="product-photo-1"
                                form="add-phc"
                                required />
                        </div>

                        <div class="form-group">
                            <label for="product-photo-2">Product Photo 2</label>
                            <input type="file"
                                class="form-control"
                                accept="image/jpeg, image/png"
                                name="product_photo_2"
                                id="product-photo-2"
                                form="add-phc"
                                required />
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary" id="btn-checkin">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal-pre-print" tabindex="-2">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Member Code for Print</h5>
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="pre-print-pdf">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="product-photo-2">Member Code</label>
                        <input type="text"
                            class="form-control"
                            id="wakimart-member-code"
                            placeholder="Member Code" 
                            required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-print">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade"
        id="modal-extend"
        tabindex="-1"
        role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 style="text-align:center;">
                        Are you sure to extend personal homecare? (+3 Days)
                    </h5>
                </div>
                <div class="modal-footer">
                    <form id="frmExtend"
                        method="post"
                        action="{{ route('extend_personal_homecare', ["id" => $personalhomecare['id']]) }}">
                        @csrf
                        <input type="hidden" name="id" id="id-delete" />
                        <button type="submit"
                            class="btn btn-gradient-danger mr-2">
                            Yes
                        </button>
                    </form>
                    <button class="btn btn-light">No</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
<script type="application/javascript">
function showOtherInput(e) {
    if (e.checked) {
        document.getElementById("other-text").classList.remove("d-none");
        document.getElementById("other-text").setAttribute("required", "");
    } else {
        document.getElementById("other-text").removeAttribute("required");
        document.getElementById("other-text").classList.add("d-none");
    }
}

$(document).ready(function() {
    $(".showPrinted").hide();
    $("#btn-print").click(function(){
        let mbr_code = $("#wakimart-member-code").val();
        $("#member_code").html(mbr_code);

        $(".showPrinted").show();
        $(".hide-print").hide();

        var printContents = document.getElementById("element-to-print").innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        window.location = "{{ route('detail_personal_homecare', ['id' => $personalhomecare['id']]) }}";
        // setTimeout(function () { window.close(); }, 100);

        document.body.innerHTML = originalContents;

        $(".showPrinted").hide();
        return true;
    });
    $("#success-alert").hide();
});

// $( document ).ready(function() {
//     $("#btn-pdf").click(function(){
//         $(".showPrinted").show();
//         $(".hide-print").hide();
//
//         var pdfContent = document.getElementById("element-to-print").innerHTML;
//         html2pdf(pdfContent);
//     });
//     $("#success-alert").hide();
// });
</script>
@endsection
