<?php
$menu_item_page = "public_homecare";
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
        height: 30px;
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
                <h3 class="page-title">Detail Public Homecare</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a data-toggle="collapse"
                                href="#"
                                aria-expanded="false"
                                aria-controls="public_homecare-dd">
                                Public Homecare
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
                            <h2>Status Public Homecare</h2>
                        </div>
                        <div class="row justify-content-center">
                            <h3>{{ ucwords($publichomecare['status']) }}</h3>
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
                            <h2>Instation Data</h2>
                        </div>
                        <div class="row justify-content-center">
                            <div class="table-responsive">
                                <table class="col-md-12 table-borderless">
                                    <thead style="visibility: hidden;">
                                        <th style="width: 30%;">Data</th>
                                        <th style="width: 5%;">:</th>
                                        <th style="width: 65%;">Instation</th>
                                    </thead>
                                    <tr>
                                        <td>Instation Name</td>
                                        <td>:</td>
                                        <td>{{ $publichomecare['name'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Instation Phone</td>
                                        <td>:</td>
                                        <td>{{ $publichomecare['phone'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Instation Address</td>
                                        <td>:</td>
                                        <td>
                                            {{ $publichomecare['address'] }},
                                            {{ $publichomecare->getProvinceName() }},
                                            {{ $publichomecare->getCityFullName() }},
                                            {{ $publichomecare->getDistrictName() }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Branch</td>
                                        <td>:</td>
                                        <td>
                                            {{ $publichomecare->branch->code }} - {{ $publichomecare->branch->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>CSO</td>
                                        <td>:</td>
                                        <td>
                                            {{ $publichomecare->cso->code }} - {{ $publichomecare->cso->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>CSO 2</td>
                                        <td>:</td>
                                        <td>
                                            {{ $publichomecare->csoOptional['code'] }} - {{ $publichomecare->csoOptional['name'] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Approval Letter Image</td>
                                        <td>:</td>
                                        <td style="text-align: center;">
                                            <img style="height: 300px" src="{{ asset('sources/puhc') . '/' . $publichomecare['approval_letter'] }}">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @foreach ($publichomecare->publicHomecareProduct as $publichomecareproduct)
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card" style="padding: 1em;">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <h2 class="text-center">Product Checklist</h2>
                        </div>
                        <div class="row justify-content-center mt-3">
                            <div class="table-responsive">
                                <table class="table-bordered col-md-12">
                                    <thead style="background: none;">
                                        <th colspan="2" style="width: 30%;">Checklist</th>
                                        <th style="width: 35%;">Out</th>
                                        <th style="width: 35%;">In</th>
                                    </thead>
                                    <tr>
                                        <td colspan="2">Product</td>
                                        <td colspan="2">
                                            {{ $publichomecareproduct->personalHomecareProduct->code }} ({{ $publichomecareproduct->personalHomecareProduct->product['code'] }} - {{ $publichomecareproduct->personalHomecareProduct->product['name'] }})
                                        </td>
                                    </tr>
                                    <tr>
                                        @php 
                                            $txtRes = 'Schedule';
                                        @endphp
                                        <td colspan="2">{{ $txtRes }}</td>
                                        <td class="text-center">
                                            {{ date("d/m/Y", strtotime($publichomecare['start_date'])) }}
                                        </td>
                                        <td class="text-center">
                                            {{ date("d/m/Y", strtotime($publichomecare['end_date'])) }}
                                        </td>
                                    </tr>
                                    @php
                                        $prd_firstLetter = substr($publichomecareproduct->personalHomecareProduct['code'], 0, 1);
                                        $arr_completness = App\PersonalHomecareChecklist::$completeness_list[$prd_firstLetter];
                                        $total = 0;
                                    @endphp
                                    <tr>
                                        <td rowspan="{{ sizeof($arr_completness)}}">Completeness</td>
                                        <td>{{$arr_completness[0]}}</td>
                                        <td class="text-center">
                                            <i class="mdi
                                                @if(isset($publichomecareproduct->checklistOut['condition']['completeness']))
                                                    {{ in_array($arr_completness[0],
                                                    $publichomecareproduct->checklistOut['condition']['completeness']) ?
                                                    "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                @else
                                                    mdi-checkbox-blank-outline"
                                                @endif
                                                style="font-size: 24px; color: #fed713;">
                                            </i>
                                        </td>
                                        <td class="text-center">
                                            <i class="mdi
                                                @if(isset($publichomecareproduct->checklistIn['condition']['completeness']))
                                                    {{ in_array($arr_completness[0],
                                                    $publichomecareproduct->checklistIn['condition']['completeness']) ?
                                                    "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                @else
                                                    mdi-checkbox-blank-outline"
                                                @endif
                                                style="font-size: 24px; color: #fed713;">
                                            </i>
                                        </td>
                                    </tr>
                                    @for($idx_i = 1; $idx_i < sizeof($arr_completness); $idx_i++)
                                        <tr>
                                            <td>
                                                {{ $arr_completness[$idx_i] }}
                                            </td>
                                            <td class="text-center">
                                                <i class="mdi
                                                    @if(isset($publichomecareproduct->checklistOut['condition']['completeness']))
                                                        {{ in_array($arr_completness[$idx_i],
                                                        $publichomecareproduct->checklistOut['condition']['completeness']) ?
                                                        "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                    @else
                                                        mdi-checkbox-blank-outline"
                                                    @endif
                                                    style="font-size: 24px; color: #fed713;">
                                                </i>
                                            </td>
                                            <td class="text-center">
                                                <i class="mdi
                                                    @if(isset($publichomecareproduct->checklistIn['condition']['completeness']))
                                                        {{ in_array($arr_completness[$idx_i],
                                                        $publichomecareproduct->checklistIn['condition']['completeness']) ?
                                                        "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                    @else
                                                        mdi-checkbox-blank-outline"
                                                    @endif
                                                    style="font-size: 24px; color: #fed713;">
                                                </i>
                                            </td>
                                        </tr>
                                    @endfor
                                    <tr>
                                        <td colspan="2">Total Completeness Checklist</td>
                                        <td class="text-center">
                                            {{ isset($publichomecareproduct->checklistOut['condition']['completeness']) ?
                                                sizeof($publichomecareproduct->checklistOut['condition']['completeness']) : "0" }}
                                        </td>
                                        <td class="text-center">
                                            {{ isset($publichomecareproduct->checklistIn['condition']['completeness']) ?
                                                sizeof($publichomecareproduct->checklistIn['condition']['completeness']) : "0" }}

                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Machine Condition</td>
                                        <td>
                                            {{ ucwords($publichomecareproduct->checklistOut['condition']['machine']) }}
                                        </td>
                                        <td>
                                            {{ isset($publichomecareproduct->checklistIn['condition']['machine']) ? ucwords($publichomecareproduct->checklistIn['condition']['machine']) : "-" }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Physical Condition</td>
                                        <td>
                                            {{ ucwords($publichomecareproduct->checklistOut['condition']['physical']) }}
                                        </td>
                                        <td>
                                            {{ isset($publichomecareproduct->checklistIn['condition']['physical']) ? ucwords($publichomecareproduct->checklistIn['condition']['physical']) : "-" }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Product Photo</td>
                                        <td>
                                            @if(isset($publichomecareproduct->checklistOut['image']))
                                                @foreach ($publichomecareproduct->checklistOut['image'] as $img)
                                                    <img src="{{asset('sources/phc-checklist') . '/' . $img}}"
                                                        height="300px"
                                                        style="margin-bottom: 15px;"
                                                        alt="Product Personal Homecare" />
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($publichomecareproduct->checklistIn['image']))
                                                @foreach ($publichomecareproduct->checklistIn['image'] as $img)
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

                        @if(strtolower($publichomecare['status']) == "process" && !$publichomecareproduct->checklist_in_id)
                            <div class="row justify-content-center mt-4">
                                <div class="form-group"
                                    style="margin-bottom: 0;">
                                    <button type="button"
                                        class="btn btn-gradient-primary mr-2 btn-lg"
                                        data-toggle="modal"
                                        data-target="#modal-checklist-in"
                                        onclick='changeModahCheckInBody("{{ $publichomecareproduct->id }}")'>
                                        Check In Product
                                    </button>
                                </div>
                            </div>
                        @endif

                        @if((strtolower($publichomecare['status']) == "waiting_in" || (strtolower($publichomecare['status']) == "process" && $publichomecareproduct->checklist_in_id)) && Gate::check('change-status-checkin-publichomecare'))
                            <div class="row justify-content-center mt-4">
                                <div class="form-group"
                                    style="margin-bottom: 0;">
                                    <button type="button"
                                        class="btn btn-gradient-primary mr-2 btn-lg"
                                        data-toggle="modal"
                                        data-target="#modal-checklist-in"
                                        onclick='changeModahCheckInBody("{{ $publichomecareproduct->id }}")'>
                                        Update Product Check In
                                    </button>
                                </div>
                            </div>
                        @endif

                        {{-- MODAL BODY CHECKLIST IN --}}
                        @if(strtolower($publichomecare['status']) == "process" || strtolower($publichomecare['status']) == "waiting_in")
                        <div id="modal-checklist-in-body-{{ $publichomecareproduct->id }}" style="display: none;">
                            @if(($publichomecare['status'] == "waiting_in" || $publichomecareproduct->checklist_in_id) && $publichomecareproduct->personalHomecareProduct['status'] == "pending")
                                <input type="hidden"
                                    name="id_checklist"
                                    value="{{ $publichomecareproduct->checklist_in_id }}" />
                            @endif

                            @php
                                $isWaitingInChecklist = (($publichomecare['status'] == "waiting_in" || (strtolower($publichomecare['status']) == "process" && $publichomecareproduct->checklist_in_id)) && $publichomecareproduct->personalHomecareProduct['status'] == "pending");
                                $phcproducts = $publichomecareproduct->personalHomecareProduct;
                            @endphp

                            <div class="modal-body">
                                <input type="hidden"
                                    name="id"
                                    value="{{ $publichomecare['id'] }}" />
                                <input type="hidden"
                                    name="id_product"
                                    value="{{ $publichomecareproduct['id'] }}">
                                <div class="form-group">
                                    <span style="display: block;">Completeness</span>
                                    <div class="div-CheckboxGroup">

                                        @foreach($arr_completness as $checklistInput)
                                            <div class="form-check">
                                                <label for="completeness-{{$checklistInput}}-{{ $publichomecareproduct['id'] }}"
                                                    class="form-check-label">
                                                    <input type="checkbox"
                                                        name="completeness[]"
                                                        id="completeness-{{$checklistInput}}-{{ $publichomecareproduct['id'] }}"
                                                        value="{{$checklistInput}}"
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
                                            <label for="completeness-other-{{ $publichomecareproduct['id'] }}"
                                                class="form-check-label">
                                                <input type="checkbox"
                                                    name="completeness[]"
                                                    id="completeness-other-{{ $publichomecareproduct['id'] }}"
                                                    value="other"
                                                    onchange="showOtherInput(this, '{{ $publichomecareproduct['id'] }}')"
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
                                                class="form-control {{ $isWaitingInChecklist ? '' : 'd-none' }}"
                                                placeholder="Other description"
                                                name="other_completeness"
                                                id="other-text-{{ $publichomecareproduct['id'] }}"
                                                value="{{ $isWaitingInChecklist ? $phcproducts->currentChecklist['condition']['other'] : "" }}"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <span style="display: block;">Machine Condition</span>
                                    <div class="div-CheckboxGroup">
                                        <div class="form-check">
                                            <label for="machine-condition-normal-{{ $publichomecareproduct['id'] }}"
                                                class="form-check-label">
                                                <input type="radio"
                                                    class="form-check-input"
                                                    name="machine_condition"
                                                    id="machine-condition-normal-{{ $publichomecareproduct['id'] }}"
                                                    value="normal"
                                                    required
                                                    {{ $phcproducts->currentChecklist
                                                    ['condition']['machine'] == "normal" && $isWaitingInChecklist ?
                                                    "checked" : "" }}/>
                                                Normal
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label for="machine-condition-need-repair-{{ $publichomecareproduct['id'] }}"
                                                class="form-check-label">
                                                <input type="radio"
                                                    class="form-check-input"
                                                    name="machine_condition"
                                                    id="machine-condition-need-repair-{{ $publichomecareproduct['id'] }}"
                                                    value="need_repair"
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
                                            <label for="physical-condition-new-{{ $publichomecareproduct['id'] }}"
                                                class="form-check-label">
                                                <input type="radio"
                                                    class="form-check-input"
                                                    name="physical_condition"
                                                    id="physical-condition-new-{{ $publichomecareproduct['id'] }}"
                                                    value="new"
                                                    required
                                                    {{ $phcproducts->currentChecklist
                                                    ['condition']['physical'] == "new" && $isWaitingInChecklist ?
                                                    "checked" : "" }}/>
                                                New
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label for="physical-condition-moderate-{{ $publichomecareproduct['id'] }}"
                                                class="form-check-label">
                                                <input type="radio"
                                                    class="form-check-input"
                                                    name="physical_condition"
                                                    id="physical-condition-moderate-{{ $publichomecareproduct['id'] }}"
                                                    value="moderate"
                                                    required
                                                    {{ $phcproducts->currentChecklist
                                                    ['condition']['physical'] == "moderate" && $isWaitingInChecklist ?
                                                    "checked" : "" }}/>
                                                Moderate
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label for="physical-condition-need-repair-{{ $publichomecareproduct['id'] }}"
                                                class="form-check-label">
                                                <input type="radio"
                                                    class="form-check-input"
                                                    name="physical_condition"
                                                    id="physical-condition-need-repair-{{ $publichomecareproduct['id'] }}"
                                                    value="need_repair"
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
                                        <label for="product-photo-1-{{ $publichomecareproduct['id'] }}">Product Photo 1</label>
                                        <input type="file"
                                            class="form-control"
                                            accept="image/jpeg, image/png"
                                            name="product_photo_1"
                                            id="product-photo-1-{{ $publichomecareproduct['id'] }}"
                                            required />
                                    </div>

                                    <div class="form-group">
                                        <label for="product-photo-2-{{ $publichomecareproduct['id'] }}">Product Photo 2</label>
                                        <input type="file"
                                            class="form-control"
                                            accept="image/jpeg, image/png"
                                            name="product_photo_2"
                                            id="product-photo-2-{{ $publichomecareproduct['id'] }}"
                                            required />
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card" style="padding: 1em;">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <h2 class="text-center">Other Product</h2>
                        </div>
                        <div class="row justify-content-center mt-3">
                            {{ $publichomecare->other_product }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (strtolower($publichomecare['status']) == "new" && Gate::check('change-status-verified-publichomecare'))
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>Status Public Homecare (Is Verified ?)</h2>
                            </div>
                            <form id="actionAdd"
                                class="forms-sample"
                                method="POST"
                                action="{{ route("update_public_homecare_status") }}">
                                @csrf
                                <input type="hidden"
                                    name="id"
                                    value="{{ $publichomecare['id'] }}" />
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
        @elseif (strtolower($publichomecare['status']) == "verified" && Gate::check('change-status-checkout-publichomecare'))
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>Status Public Homecare (Can Checkout ?)</h2>
                            </div>
                            <form id="actionAdd"
                                class="forms-sample"
                                method="POST"
                                action="{{ route("update_public_homecare_status") }}">
                                @csrf
                                <input type="hidden"
                                    name="id"
                                    value="{{ $publichomecare['id'] }}" />
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
        @elseif (strtolower($publichomecare['status']) == "approve_out" || strtolower($publichomecare['status']) == "approve_out_res")
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>Upload Receipt Photo with Instation</h2>
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
        @elseif (strtolower($publichomecare['status']) == "waiting_in" && Gate::check('change-status-checkin-publichomecare'))
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>Status Public Homecare (Check In)</h2>
                            </div>
                            <form id="actionAdd"
                                class="forms-sample"
                                method="POST"
                                action="{{ route("update_public_homecare_status") }}">
                                @csrf
                                <input type="hidden"
                                    name="id"
                                    value="{{ $publichomecare['id'] }}" />
                                <input type="hidden"
                                    name="id_products"
                                    value="{{ $publichomecare->publicHomecareProduct->pluck('ph_product_id') }}">
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

        @if (strtolower($publichomecare['status']) != "new" && strtolower($publichomecare['status']) != "rejected" )
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                          <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>
                                    Public Homecare Option
                                </h2>
                            </div>
                            @php
                                $urlShareWa = route('public_homecare', ['id' => $publichomecare->id]);
                                if(strtolower($publichomecare['status']) == "done"){
                                    $urlShareWa = route('thankyou_puh', ["id" => $publichomecare->id]);
                                }
                            @endphp
                            <form class="forms-sample"
                                method="GET"
                                action="https://api.whatsapp.com/send"
                                target="_blank">
                                <div class="form-group row justify-content-center mt-3">
                                    <button id="upgradeProcess"
                                        type="submit"
                                        class="btn btn-gradient-primary m-1"
                                        name="text"
                                        value="Terima Kasih telah mengikuti *Program Hospitality Waki*. Berikut adalah tautan bukti formulir ( {{ $urlShareWa }} )">
                                        Share WhatsApp
                                        <span><i class="mdi mdi-whatsapp menu-icon" style="margin-left: 5px; font-size: 24px; vertical-align: middle;"></i></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (strtolower($publichomecare['status']) != "new" && strtolower($publichomecare['status']) != "rejected" && strtolower($publichomecare['status']) != "verified" )
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
                                        id="btn-print"
                                        class="btn btn-gradient-info mr-2 btn-lg">
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
            <!-- <div class="col-12 grid-margin stretch-card"> -->
                <div class="card">
                    <div class="card-body">
                        <div style="background-color: #008349; height: 15px;"></div>
                        <div style="width: 200px; margin: auto;">
                            <img style="width: 100%;"
                                src="{{ asset('sources/logosince.svg') }}"
                                alt="logo" />
                        </div>
                        <div style="width: 100%; display: flex; flex-wrap: wrap;">
                            <div style="width: 95%; margin: auto; text-align: center;">
                                <h2 style="font-weight: 700;">
                                    SURAT TANDA TERIMA BARANG
                                </h2>
                                <h5>
                                    PROGRAM PINJAMIN PRODUK UNTUK INSTANSI
                                <h5>
                            </div>
                            <div style="width: 95%; margin: auto; text-align: justify;">
                                <p>Saya yang bertanda tangan, menyatakan telah menerima barang dalam keadaan baik dan berfungsi normal sesuai data di bawah ini. Demikian surat tanda terima ini dibuat sebagai bukti yang sah.
                            </div>
                            <div style="width: 100%; background-color: #b4d9c4; padding: 0.25em; margin-bottom: 0.5em;">
                                <h4 style="padding-left: 5%;margin: 0;">
                                    DATA INSTANSI
                                </H4>
                            </div>
                            <div style="width: 95%; margin: auto; display: table; margin-bottom: 1.5em;">
                                <div style="width: 98%; margin-right: 3%; margin-bottom: 5px; float: left; border-bottom: 1px solid black;">
                                    <h5>NAMA: {{ $publichomecare['name'] }}</h5>
                                </div>
                                <div style="width: 98%; margin-right: 3%; margin-bottom: 5px; float: left; border-bottom: 1px solid black;">
                                    <h5>
                                        NO.TELP: {{ $publichomecare['phone'] }}
                                    </h5>
                                </div>
                                <div style="width: 98%; margin-bottom: 5px; float: left; border-bottom: 1px solid black;">
                                    <h5>
                                        CABANG: {{ $publichomecare->branch->code }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div style="width: 100%; display: flex; flex-wrap: wrap;">
                            <div style="width: 100%; background-color: #b4d9c4; padding: 0.25em; margin-bottom: 0.5em;">
                                <h4 style="padding-left: 5%;margin: 0;">
                                    DATA PRODUK
                                </H4>
                            </div>
                            <div style="width: 95%; margin: auto; display: table; margin-bottom: 1.5em;">
                                <div style="width: 98%; margin-bottom:5px; float: left; border-bottom:1px solid black;">
                                    <h5>
                                        TGL PENGIRIMAN: {{ $publichomecare['start_date'] }}
                                    </h5>
                                </div>
                                <div style="width: 98%; margin-bottom: 5px; float: left; border-bottom: 1px solid black;">
                                    <h5>
                                        TGL PENGAMBILAN: {{ date("Y-m-d", strtotime($publichomecare['end_date'])) }}
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <br><br><br><br><br>
                        <div style="width: 100%; margin: auto; text-align: justify;">
                            <div style="width: 24%; float: left;">
                                <div style="width: 70%; margin: auto; border-top: 4px solid black; text-align: center;">
                                    <p>Tanda Tangan Instansi Penerima</p>
                                </div>
                            </div>
                            <div style="width: 24%; float: left;">
                                <div style="width: 70%; margin: auto; border-top: 4px solid black; text-align: center;">
                                    <p>Tanda Tangan Pengirim</p>
                                </div>
                            </div>
                            <div style="width: 24%; float: left;">
                                <div style="width: 70%; margin: auto; border-top: 4px solid black; text-align: center;">
                                    <p>Tanda Tangan Instansi Kembali</p>
                                </div>
                            </div>
                            <div style="width: 24%; float: left;">
                                <div style="width: 70%; margin: auto; border-top: 4px solid black; text-align: center;">
                                    <p>Tanda Tangan Pengambil</p>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="clearfix"></div>
                        <div style="width:90%;margin:1.5em auto 0;text-align:justify">
                            <p>Demikian surat tanda terima ini dibuat sebagai bukti yang sah.</p>
                            <h5 style="margin-bottom:0.2em;">SYARAT & KETENTUAN:</h5>
                            <div style="width: 90%; margin: auto;">
                                <ol type="1">
                                    <li>Program pinjamin produk 5 hari (PP5H) tidak dipungut biaya apapun.</li>
                                    <li>Sudah menjadi member WAKimart dan minimal berusia 35 tahun</li>
                                    <li>Apabila ada kekurangan, kehilangan ataupun kerusakan pada produk saat pengembalian, berarti membeli barang dengan harga normal sesuai ketentuan yang ada.</li>
                                    <li>Kekurangan aksesoris produk harap dikembalikan paling lambat 7 hari setelah program PP5H berakhir.</li>
                                    <li>Syarat dan Ketentuan dapat berubah tanpa pemeberitahuan sebelumnya.</li>
                                    <li>Kenyamanan dan keamanan konsumen kami adalah prioritas pertama.</li>
                                </ol>
                            </div>
                            <p>Apabila ada sesuatu atau ada pertanyaan, bisa menghubungi kami melalui facebook page WAKi Indonesia atau customer care kami : 0815-5467-3357</p>
                        </div>

                        <div class="clearfix"></div>
                        <div class="clearfix"></div>
                        <p style="page-break-after: always;">&nbsp;</p>
                        <p style="page-break-before: always;">&nbsp;</p>
                        
                        @foreach ($publichomecare->publicHomecareProduct as $publichomecareproduct)
                        <div style="margin:auto;">
                            <div style="margin-top: 1em;">
                                <table class="col-md-12" style="border: 1px solid black; width: 100%;">
                                    <thead>
                                        <td colspan="4" class="text-center" style="border: 1px solid black;margin: 0;">
                                            <h4>{{ $publichomecareproduct->personalHomecareProduct->code }} ({{ $publichomecareproduct->personalHomecareProduct->product['code'] }} - {{ $publichomecareproduct->personalHomecareProduct->product['name'] }})</h4>
                                        </td>
                                    </thead>
                                    <thead>
                                        <td class="text-center" style="border: 1px solid black;margin: 0;"><h4>KELENGKAPAN</h4></td>
                                        <td class="text-center" style="border: 1px solid black;margin: 0;"><h4>OUT</h4></td>
                                        <td class="text-center" style="border: 1px solid black;margin: 0;"><h4>IN</h4></td>
                                        <td class="text-center" style="border: 1px solid black;margin: 0;"><h4>CATATAN</h4></td>
                                    </thead>
                                    @php
                                        $prd_firstLetter = substr($publichomecareproduct->personalHomecareProduct['code'], 0, 1);
                                        $arr_completness = App\PersonalHomecareChecklist::$completeness_list[$prd_firstLetter];
                                        $total = 0;
                                    @endphp
                                    @for($idx_i = 1; $idx_i < sizeof($arr_completness); $idx_i++)
                                        <tr>
                                            <td style="border: 1px solid black; border-width: 0 1px;">
                                                {{ $arr_completness[$idx_i] }}
                                            </td>
                                            <td style="border: 1px solid black; border-width: 0 1px;">
                                                <i class="mdi
                                                    @if(isset($publichomecareproduct->checklistOut['condition']['completeness']))
                                                        {{ in_array($arr_completness[$idx_i],
                                                        $publichomecareproduct->checklistOut['condition']['completeness']) ?
                                                        "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                    @else
                                                        mdi-checkbox-blank-outline"
                                                    @endif
                                                    style="font-size: 24px; color: #fed713;">
                                                </i>
                                            </td>
                                            <td style="border: 1px solid black; border-width: 0 1px;">
                                                <i class="mdi
                                                    @if(isset($publichomecareproduct->checklistIn['condition']['completeness']))
                                                        {{ in_array($arr_completness[$idx_i],
                                                        $publichomecareproduct->checklistIn['condition']['completeness']) ?
                                                        "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                    @else
                                                        mdi-checkbox-blank-outline"
                                                    @endif
                                                    style="font-size: 24px; color: #fed713;">
                                                </i>
                                            </td>
                                            
                                            <td style="border: 1px solid black; border-width: 0 1px;">
                                                
                                            </td>
                                        </tr>
                                    @endfor
                                    <tr>
                                        <td class="text-right" style="border: 1px solid black;">
                                          <h4>TOTAL CHECK:</h4>
                                        </td>
                                        <td class="text-center" style="border: 1px solid black;">
                                        {{ isset($publichomecareproduct->checklistOut['condition']['completeness']) ?
                                                sizeof($publichomecareproduct->checklistOut['condition']['completeness']) : "0" }}
                                        </td>
                                        <td class="text-center" style="border: 1px solid black;">
                                        
                                            {{ isset($publichomecareproduct->checklistIn['condition']['completeness']) ?
                                                sizeof($publichomecareproduct->checklistIn['condition']['completeness']) : "0" }}
                                        </td>
                                        <td class="text-center" style="border: 1px solid black;">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        @endforeach

                        <h5 class="mt-3">Other Product</h5>
                        <p>{{ $publichomecare->other_product }}</p>
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
                                <h2>Public Homecare History Log</h2>
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
                                                        <b>{{ $dataKey }}</b>: {{ is_array($value) ? json_encode($value) : $value }}
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
                <h5 class="modal-title">Upload Receipt Photo with Instation</h5>
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
                action="{{ route("update_public_homecare_status") }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden"
                        name="id"
                        value="{{ $publichomecare['id'] }}" />
                    <input type="hidden"
                        name="status"
                        value="process" />

                    @foreach($publichomecare->publicHomecareProduct as $productNyaPH)
                        <div class="form-group">
                            <label for="product-photo-{{ $productNyaPH['id'] }}">Product Photo ({{ $productNyaPH->personalHomecareProduct['code'] }})</label>
                            <input type="file"
                                class="form-control"
                                accept="image/jpeg, image/png"
                                name="product_photo_{{ $productNyaPH['id'] }}"
                                id="product-photo-{{ $productNyaPH['id'] }}"
                                required />
                        </div>
                    @endforeach

                    <div class="form-group">
                        <label for="product-photo-inst">Product Photo at Instation</label>
                        <input type="file"
                            class="form-control"
                            accept="image/jpeg, image/png"
                            name="product_photo_inst"
                            id="product-photo-inst"
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

<div id="modal-checklist-in-temp"></div>
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
                action="{{ route("update_public_homecare_checklist_in") }}">
                @csrf

                <div id="modal-checklist-in-body"></div>

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
@endsection

@section("script")
<script type="application/javascript">
function showOtherInput(e, publichomecareproduct_id) {
    if (e.checked) {
        document.getElementById("other-text-"+publichomecareproduct_id).classList.remove("d-none");
        document.getElementById("other-text-"+publichomecareproduct_id).setAttribute("required", "");
    } else {
        document.getElementById("other-text-"+publichomecareproduct_id).removeAttribute("required");
        document.getElementById("other-text-"+publichomecareproduct_id).classList.add("d-none");
    }
}

$(document).ready(function() {
    $(".showPrinted").hide();
    $("#btn-print").click(function(){
        $(".showPrinted").show();
        $(".hide-print").hide();

        var printContents = document.getElementById("element-to-print").innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        window.location = "{{ route('detail_public_homecare', ['id' => $publichomecare['id']]) }}";

        document.body.innerHTML = originalContents;

        $(".showPrinted").hide();
        return true;
    });
    $("#success-alert").hide();    
});
var modal_checklist_in_temp;
function changeModahCheckInBody(publichomecareproduct_id) {
    $("#modal-checklist-in-temp").html(modal_checklist_in_temp);

    const modal_checklist_in_body_product_html = $("#modal-checklist-in-body-"+publichomecareproduct_id).html();
    $("#modal-checklist-in-body-"+publichomecareproduct_id).appendTo($("#modal-checklist-in-temp"));
    modal_checklist_in_temp = $("#modal-checklist-in-temp").html();
    $("#modal-checklist-in-temp").html("");
    
    $("#modal-checklist-in-body").html(modal_checklist_in_body_product_html);
}
</script>
@endsection
