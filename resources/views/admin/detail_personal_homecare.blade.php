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

    /* Hide all steps by default: */
    .tab {
        display: none;
    }

    /* Make circles that indicate the steps of the form: */
    .step {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbbbbb;
        border: none;
        border-radius: 50%;
        display: inline-block;
        opacity: 0.5;
    }

    /* Mark the active step: */
    .step.active {
        opacity: 1;
    }

    /* Mark the steps that are finished and valid: */
    .step.finish {
        background-color: #4CAF50;
    }

   .div-CheckboxGroup {
        border:solid 1px rgba(128, 128, 128, 0.32941);
        padding:0px 10px ;
        border-radius:3px;
    }

    input[type='checkbox'], input[type='radio'] {
        margin-left: 0px !important;
    }

    table {
        margin: 1em;
        font-size: 14px;
    }

    table thead {
        background-color: #8080801a;
        text-align: center;
    }

    table td {
        border: 0.5px #8080801a solid;
        padding: 0.5em;
    }

    .center {
        text-align: center;
    }

    .right {
        text-align: right;
    }

    .justify-content-center {
        padding: 0em 1em;
    }

    /*-- mobile --*/
    @media (max-width: 768px) {
        .card-body h2{
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

    @media (min-width: 768px) {
        .table-responsive::-webkit-scrollbar {
            display: none;
        }
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
                            <h2>Customer Data</h2>
                        </div>
                        <div class="row justify-content-center">
                            <div class="table-responsive">
                                <table class="col-md-12 table-borderless">
                                    <thead style="visibility: hidden;">
                                        <th style="width: 30%">Data</th>
                                        <th style="width: 5%">:</th>
                                        <th style="width: 65%">Custpmer</th>
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
                                </table>
                            </div>
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
                            <h2>Product Out Checklist</h2>
                        </div>
                        <div class="row justify-content-center">
                            <div class="table-responsive">
                                <table class="col-md-12">
                                    <thead style="visibility: hidden;">
                                        <th style="width: 30%">Checklist</th>
                                        <th style="width: 70%">Content</th>
                                    </thead>
                                    <tr>
                                        <td>Product</td>
                                        <td>
                                            {{ $personalhomecare->personalHomecareProduct->code }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Schedule Date</td>
                                        <td>
                                            {{ date("d/m/Y", strtotime($personalhomecare['schedule'])) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td rowspan="5">Completeness</td>
                                        <td>
                                            <i class="mdi {{ in_array("machine", 
                                                $personalhomecare->checklistOut['condition']['completeness'][0]) ? 
                                                "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" 
                                                style="font-size: 24px; color: #fed713;">
                                            </i> 
                                            Machine
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="mdi {{ in_array("filter", 
                                                $personalhomecare->checklistOut['condition']['completeness'][0]) ? 
                                                "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" 
                                                style="font-size: 24px; color: #fed713;">
                                            </i> 
                                            Filter
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="mdi {{ in_array("accessories", 
                                                $personalhomecare->checklistOut['condition']['completeness'][0]) ? 
                                                "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" 
                                                style="font-size: 24px; color: #fed713;">
                                            </i>  
                                            Accessories
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="mdi {{ in_array("cable", 
                                                $personalhomecare->checklistOut['condition']['completeness'][0]) ? 
                                                "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" 
                                                style="font-size: 24px; color: #fed713;">
                                            </i>  
                                            Cable
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="mdi mdi-check-box-outline mdi-checkbox-blank-outline" 
                                                style="font-size: 24px; color: #fed713;">
                                            </i>  
                                            Other : 
                                            {{ isset($personalhomecare->checklistOut['condition']['other']) ? 
                                                $personalhomecare->checklistOut['condition']['other'][0] : "-" }}
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
                                            @foreach ($personalhomecare->checklistOut['image'] as $img)
                                                <img src="{{asset('sources/phc-checklist') . '/' . $img}}"
                                                    height="300px" 
                                                    style="margin-bottom: 15px;"
                                                    alt="Product Personal Homecare" />
                                            @endforeach
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (!empty($personalhomecare['checklist_in']))
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>Product In Checklist</h2>
                            </div>
                            <div class="row justify-content-center">
                                <div class="table-responsive">
                                    <table class="col-md-12">
                                        <thead style="visibility: hidden;">
                                            <th style="width: 30%">Checklist</th>
                                            <th style="width: 70%">Content</th>
                                        </thead>
                                        <tr>
                                            <td>Product</td>
                                            <td>
                                                {{ $personalhomecare->ph_product['code'] }} - {{ $personalhomecare->ph_product['name'] }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Schedule Date</td>
                                            <td>
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td rowspan="5">Completeness</td>
                                            <td>
                                                <i class="mdi {{ in_array("machine", 
                                                    $personalhomecare->checklistIn['condition']['completeness'][0]) ? 
                                                    "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" 
                                                    style="font-size: 24px; color: #fed713;">
                                                </i> 
                                                Machine
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <i class="mdi {{ in_array("filter", 
                                                    $personalhomecare->checklistIn['condition']['completeness'][0]) ? 
                                                    "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" 
                                                    style="font-size: 24px; color: #fed713;">
                                                </i> 
                                                Filter
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <i class="mdi {{ in_array("accessories", 
                                                    $personalhomecare->checklistIn['condition']['completeness'][0]) ? 
                                                    "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" 
                                                    style="font-size: 24px; color: #fed713;">
                                                </i>  
                                                Accessories
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <i class="mdi {{ in_array("cable", 
                                                    $personalhomecare->checklistIn['condition']['completeness'][0]) ? 
                                                    "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" 
                                                    style="font-size: 24px; color: #fed713;">
                                                </i>  
                                                Cable
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <i class="mdi mdi-check-box-outline mdi-checkbox-blank-outline" 
                                                    style="font-size: 24px; color: #fed713;">
                                                </i>  
                                                Other : 
                                                {{ isset($personalhomecare->checklistIn['condition']['other']) ? 
                                                    $personalhomecare->checklistIn['condition']['other'][0] : "-" }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Machine Condition</td>
                                            <td>
                                                {{ ucwords($personalhomecare->checklistIn['condition']['machine']) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Physical Condition</td>
                                            <td>
                                                {{ ucwords($personalhomecare->checklistIn['condition']['physical']) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Product Photo</td>
                                            <td>
                                                @foreach ($personalhomecare->checklistIn['image'] as $img)
                                                    <img src="{{asset('sources/phc-checklist') . '/' . $img}}"
                                                        height="300px" 
                                                        style="margin-bottom: 15px;"
                                                        alt="Product Personal Homecare" />
                                                @endforeach
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (strtolower($personalhomecare['status']) == "new" && Gate::check('change-status-approval-personalhomecare') && Gate::check('change-status-reject-personalhomecare'))
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>Status Personal Homecare</h2>
                            </div>
                            <form id="actionAdd"
                                class="forms-sample"
                                method="POST"
                                action="">
                                {{ csrf_field() }}
                                <input type="text"
                                    name="id"
                                    value="{{ $personalhomecare['id'] }}"
                                    hidden />
                                <div class="form-group row justify-content-center">
                                    <button id="upgradeProcess"
                                        type="submit"
                                        class="btn btn-gradient-primary mr-2 btn-lg"
                                        name="status"
                                        value="approved">
                                        Approved
                                    </button>
                                    <button id="upgradeProcess"
                                        type="submit"
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
        @else
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                          <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>Share Personal Homecare</h2>
                            </div>
                            <form class="forms-sample"
                                method="GET"
                                action="https://wa.me/">
                                <div class="form-group row justify-content-center">
                                    <button id="upgradeProcess"
                                        type="submit"
                                        class="btn btn-gradient-primary mr-2 btn-lg"
                                        name="text"
                                        value="">
                                        Share Whatsapp
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Jika bisa melihat history log-->
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
                                            <td>No.</td>
                                            <td>Action</td>
                                            <td>User</td>
                                            <td>Change</td>
                                            <td>Time</td>
                                        </thead>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- end if-->
    </div>
</div>
@endsection
