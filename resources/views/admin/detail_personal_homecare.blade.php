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

    .justify-content-center {
        padding: 0em 1em;
    }

    .div-CheckboxGroup {
        border: solid 1px rgba(128, 128, 128, 0.32941);
        padding: 10px;
        border-radius: 3px;
        background-color: white;
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
                                        <th style="width: 30%;">Checklist</th>
                                        <th style="width: 70%;">Content</th>
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
                                                $personalhomecare->checklistOut['condition']['completeness']) ?
                                                "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                style="font-size: 24px; color: #fed713;">
                                            </i>
                                            Machine
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="mdi {{ in_array("filter",
                                                $personalhomecare->checklistOut['condition']['completeness']) ?
                                                "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                style="font-size: 24px; color: #fed713;">
                                            </i>
                                            Filter
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="mdi {{ in_array("accessories",
                                                $personalhomecare->checklistOut['condition']['completeness']) ?
                                                "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                style="font-size: 24px; color: #fed713;">
                                            </i>
                                            Accessories
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="mdi {{ in_array("cable",
                                                $personalhomecare->checklistOut['condition']['completeness']) ?
                                                "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                style="font-size: 24px; color: #fed713;">
                                            </i>
                                            Cable
                                        </td>
                                    </tr>
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
                                                {{ $personalhomecare->personalHomecareProduct->code }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Schedule Date</td>
                                            <td>
                                                {{ $personalhomecare->checklistIn["created_at"] }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td rowspan="5">Completeness</td>
                                            <td>
                                                <i class="mdi {{ in_array("machine",
                                                    $personalhomecare->checklistIn['condition']['completeness']) ?
                                                    "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                    style="font-size: 24px; color: #fed713;">
                                                </i>
                                                Machine
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <i class="mdi {{ in_array("filter",
                                                    $personalhomecare->checklistIn['condition']['completeness']) ?
                                                    "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                    style="font-size: 24px; color: #fed713;">
                                                </i>
                                                Filter
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <i class="mdi {{ in_array("accessories",
                                                    $personalhomecare->checklistIn['condition']['completeness']) ?
                                                    "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                    style="font-size: 24px; color: #fed713;">
                                                </i>
                                                Accessories
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <i class="mdi {{ in_array("cable",
                                                    $personalhomecare->checklistIn['condition']['completeness']) ?
                                                    "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                    style="font-size: 24px; color: #fed713;">
                                                </i>
                                                Cable
                                            </td>
                                        </tr>
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

        @if (strtolower($personalhomecare['status']) == "new" && Gate::check('change-status-checkin-personalhomecare'))
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>Status Personal Homecare (Check Out)</h2>
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
        @elseif (strtolower($personalhomecare['status']) == "waiting_in" && Gate::check('change-status-checkout-personalhomecare'))
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

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                      <div class="card-body">
                        <div class="row justify-content-center">
                            <h2>{{ strtolower($personalhomecare['status']) == "done" ? "Share Thank You Letter" : "Share Personal Homecare Status" }}</h2>
                        </div>
                        <form class="forms-sample"
                            method="GET"
                            action="https://wa.me/"
                            target="_blank">
                            <div class="form-group row justify-content-center">
                                <button id="upgradeProcess"
                                    type="submit"
                                    class="btn btn-gradient-primary mr-2 btn-lg"
                                    name="text"
                                    value="Terima Kasih telah mengikuti *Program Pinjamin Produk 5 Hari*. Berikut adalah tautan bukti formulir ( {{ route('personal_homecare', ['id' => $personalhomecare->id]) }} )">
                                    Share Whatsapp
                                </button>
                                 <button id="btn-print"  class="btn btn-gradient-primary mr-2 btn-lg">Create Pdf</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div id="element-to-print" class="col-12 grid-margin stretch-card showPrinted">
                <div class="card">
                    <div class="card-body">
                        <div style="background-color:#008349;height:15px;"></div>
                        <div style="width:200px;margin:auto;">
                          <img style="width:100%;" src="{{asset('sources/logosince.svg')}}" alt="logo" />
                        </div>
                        <div>
                          <div style="width:80%;margin:auto;text-align:center">
                            <h1 style="font-weight:700;">SURAT TANDA TERIMA BARANG</h1>
                            <h5>PROGRAM PINJAMIN PRODUK 5 HARI : COBA PRODUK DI RUMAH SENDIRI<h5>
                          </div>
                          <br>
                          <div style="width:90%;margin:auto;text-align:justify">
                            <p>Saya yang bertanda tangan, menyatakan telah menerima barang dalam keadaan baik dan berfungsi normal sesuai data di bawah ini. Demikian surat tanda terima ini dibuat sebagai bukti yang sah.
                          </div>
                          <br>
                          <div>
                            <div style="width:300px;background-color:#b4d9c4;">
                              <h4 style="padding-left:15%;">DATA CUSTOMER</H4>
                            </div>
                          </div>
                        </div>
                        <div style="width:90%;margin:auto;">
                          <div style="width:48%;margin-right:3%;margin-bottom:5px;float:left;border-bottom:1px solid black">
                            <h5>NAMA:</h5>
                          </div>
                          <div style="width:48%;margin-bottom:5px;float:left;border-bottom:1px solid black">
                            <h5>CABANG:</h5>
                          </div>
                          <div style="width:48%;margin-right:3%;margin-bottom:5px;float:left;border-bottom:1px solid black">
                            <h5>NO.TELP:</h5>
                          </div>
                          <div style="width:48%;margin-bottom:5px;float:left;border-bottom:1px solid black">
                            <h5>TGL PENGIRIMAN:</h5>
                          </div>
                          <div style="width:48%;margin-right:3%;margin-bottom:5px;float:left;border-bottom:1px solid black">
                            <h5>NO.MEMBER:</h5>
                          </div>
                          <div style="width:48%;margin-bottom:5px;float:left;border-bottom:1px solid black">
                            <h5>TGL PENGAMBILAN:</h5>
                          </div>
                        </div>
                        <div style="width:100%" class="row justify-content-center">
                            <div class="table-responsive">
                                <table class="col-md-12">
                                    <thead>
                                        <td class="text-center">Jumlah (QTY)</td>
                                        <td class="text-center">Kode Produk</td>
                                        <td class="text-center">Nama Produk</td>
                                        <td class="text-center">Kelengkapan</td>
                                        <td class="text-center">Kondisi</td>
                                        <td class="text-center">Keterangan</td>
                                    </thead>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td class="text-center">ASD</td>
                                            <td class="text-center">ASD</td>
                                            <td>ASD</td>
                                            <td>ASD</td>
                                            <td class="text-center">ASD</td>
                                        </tr>
                                </table>
                            </div>
                        </div>
                        <div style="width:90%;margin:auto;text-align:justify">
                          <p>Demikian surat tanda terima ini dibuat sebagai bukti yang sah.
                          <h5>SYARAT & KETENTUAN :</h5>
                          <div style="width:90%;margin:auto;">
                            <ol type = "1">
                               <li>Program pinjaman produk 5 hari tidak dipungut biaya apapun</li>
                               <li>Sudah menjadi member Wakimart</li>
                               <li>Minimal berusia 35 tahun</li>
                               <li>Biaya akan dikenakan kepada konsumen jika ada sparepart ataupun kerusakan di luar persetujuan</li>
                               <li>Syarat dan Ketentuan dapat berubah tanpa pemberitahuan sebelumnya</li>
                               <li>Kenyamanan dan keamanan konsumen kami adalah prioritas pertama</li>
                            </ol>
                            <p>Apabila ada sesuatu atau ada pertanyaan, bisa menghubungi kami melalui facebook page WAKi Indonesia ataau customer care kami : 0815-5467-3357</p>
                          </div>
                        </div>
                        <br><br><br><br><br>
                        <div style="width:80%;margin:auto;text-align:justify">
                          <div style="width:48%;float:left;">
                            <div style="width:70%;margin:auto;border-top:4px solid black;text-align:center;">
                              <p>Tandatangan Penerima</p>
                            </div>
                          </div>
                          <div style="width:48%;float:left;">
                            <div style="width:70%;margin:auto;border-top:4px solid black;text-align:center;">
                              <p>Tandatangan Pengirim</p>
                            </div>
                          </div>
                        </div>
                        <br><br>
                        <div class="clearfix"></div>
                        <div style="width:100%;border-bottom:dotted;"></div>
                        <div class="clearfix"></div>
                        <br><br>
                        <div>
                          <div style="width:80%;margin:auto;text-align:center">
                            <h1 style="font-weight:700;">SURAT PENGAMBILAN BARANG</h1>
                            <h5>PROGRAM PINJAMIN PRODUK 5 HARI : COBA PRODUK DI RUMAH SENDIRI<h5>
                          </div>
                          <br>
                        </div>
                        <div style="width:100%" class="row justify-content-center">
                            <div class="table-responsive">
                                <table class="col-md-12">
                                    <thead>
                                        <td class="text-center">Jumlah (QTY)</td>
                                        <td class="text-center">Kode Produk</td>
                                        <td class="text-center">Nama Produk</td>
                                        <td class="text-center">Kelengkapan</td>
                                        <td class="text-center">Kondisi</td>
                                        <td class="text-center">Keterangan</td>
                                    </thead>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td class="text-center">ASD</td>
                                            <td class="text-center">ASD</td>
                                            <td>ASD</td>
                                            <td>ASD</td>
                                            <td class="text-center">ASD</td>
                                        </tr>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        @if (strtolower($personalhomecare['status']) == "process")
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                      <div class="card-body">
                        <div class="row justify-content-center">
                            <h2>Check In Product</h2>
                        </div>
                        <div class="form-group row justify-content-center">
                            <button type="button"
                                class="btn btn-gradient-primary mr-2 btn-lg"
                                data-toggle="modal"
                                data-target="#modal-checklist-in">
                                Check In Form
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

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
                <div class="modal-body">
                    <input type="hidden"
                        name="id"
                        value="{{ $personalhomecare['id'] }}" />
                    <div class="form-group">
                        <span style="display: block;">Completeness</span>
                        <div class="div-CheckboxGroup">
                            <div class="form-check">
                                <label for="completeness-machine"
                                    class="form-check-label">
                                    <input type="checkbox"
                                        name="completeness[]"
                                        id="completeness-machine"
                                        value="machine"
                                        form="add-phc" />
                                    Machine
                                </label>
                            </div>
                            <div class="form-check">
                                <label for="completeness-filter"
                                    class="form-check-label">
                                    <input type="checkbox"
                                        name="completeness[]"
                                        id="completeness-filter"
                                        value="filter"
                                        form="add-phc" />
                                    Filter
                                </label>
                            </div>
                            <div class="form-check">
                                <label for="completeness-accessories"
                                    class="form-check-label">
                                    <input type="checkbox"
                                        name="completeness[]"
                                        id="completeness-accessories"
                                        value="accessories"
                                        form="add-phc" />
                                    Accessories
                                </label>
                            </div>
                            <div class="form-check">
                                <label for="completeness-cable"
                                    class="form-check-label">
                                    <input type="checkbox"
                                        name="completeness[]"
                                        id="completeness-cable"
                                        value="cable"
                                        form="add-phc" />
                                    Cable
                                </label>
                            </div>
                            <div class="form-check">
                                <label for="completeness-other"
                                    class="form-check-label">
                                    <input type="checkbox"
                                        name="completeness[]"
                                        id="completeness-other"
                                        value="other"
                                        form="add-phc"
                                        onchange="showOtherInput(this)" />
                                    Other
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="text"
                                    class="form-control d-none"
                                    placeholder="Other description"
                                    name="other_completeness"
                                    id="other-text"
                                    form="add-phc" />
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
                                        required />
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
                                        required />
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
                                        required />
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
                                        required />
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
                                        required />
                                    Need Repair
                                </label>
                            </div>
                        </div>
                    </div>

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

$( document ).ready(function() {
    $(".showPrinted").hide();
    $("#btn-print").click(function(){
        $(".showPrinted").show();
        $(".hide-print").hide();

        var printContents = document.getElementById("element-to-print").innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;

        return true;
    });
    $("#success-alert").hide();
});

//
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
