<?php
    
?>
@extends('layouts.template')

@section('content')
<style type="text/css">
    #intro {
        padding-top: 2em;
    }

    .card-shadow {
        box-shadow: 0px 0px 9px 0px rgba(0,0,0,0.1);
        border-style: none;
        padding:1em; 
    }

    #table-mob .table thead{
        background-color: #9eabe4;
        background-image: linear-gradient(315deg, #bbdbbe 0%, #deebdd 74%);
        color: #1c1c1c;
        font-size: 1em;
        font-weight: 600; 
    }

    .table-responsive .sk td, .table-responsive .sk th{
        padding-bottom: 0.1rem;
    }

    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link {
        color: #737373; 
    }

    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
        color: #f0bc5e;
    }

    /* Tabs Card */

    .tab-card {
        border:1px solid #eee;
    }

    .tab-card-header {
        background:none;
    }
    .tab-content .tab-pane{
        background: none;
    }
    /* Default mode */
    .tab-card-header > .nav-tabs {
        border: none;
        margin: 0px;
    }
    .tab-card-header > .nav-tabs > li {
        margin-right: 2px;
    }
    .tab-card-header > .nav-tabs > li > a {
        border: 0;
        border-bottom:2px solid transparent;
        margin-right: 0;
        color: #545454; 
        font-weight: 600;
        padding: 2px 10px;
    }

    .tab-card-header > .nav-tabs > li > a.show {
        border-bottom:2px solid #048b32;
        color: #048b32;
    }
    .tab-card-header > .nav-tabs > li > a:hover {
        color: #048b32;
    }

    .tab-card-header > .tab-content {
        padding-bottom: 0;
    }

    .responsive-table{
        list-style-type: none;
        padding-left: 0;
        margin-left: 0;
    }

    .responsive-table li {
        border-radius: 3px;
        padding: 25px 30px;
        display: flex;
        justify-content: space-between;
        margin-bottom: 25px;
    }

    .responsive-table .table-header {
        background-color: #9eabe4;
        background-image: linear-gradient(315deg, #bbdbbe 0%, #deebdd 74%);
        color: #1c1c1c;
        font-size: 16px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .responsive-table .table-row {
        background-color: #ffffff;
        box-shadow: 0px 0px 9px 0px rgba(0,0,0,0.1);
    }

    /* .timeline {
        width: 98%;
        height: 15px;
        text-align: justify;
        position: relative;
        left: 49%;
        top: 50%;
        -webkit-transform: translate(-50%, -50%);
        -moz-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        -o-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        background: -moz-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,0) 45%, rgba(218, 222, 228) 51%, rgba(255,255,255,0) 57%, rgba(255,255,255,0) 100%);
        background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(255,255,255,0)), color-stop(45%, rgba(255,255,255,0)), color-stop(51%, rgba(218, 222, 228)), color-stop(57%, rgba(255,255,255,0)), color-stop(100%, rgba(255,255,255,0)));
        background: -webkit-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,0) 45%, rgba(218, 222, 228) 51%, rgba(255,255,255,0) 57%, rgba(255,255,255,0) 100%);
        background: -o-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,0) 45%, rgba(218, 222, 228) 51%, rgba(255,255,255,0) 57%, rgba(255,255,255,0) 100%);
        background: -ms-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,0) 45%, rgba(218, 222, 228) 51%, rgba(255,255,255,0) 57%, rgba(255,255,255,0) 100%);
        background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,0) 45%, rgba(218, 222, 228) 51%, rgba(255,255,255,0) 57%, rgba(255,255,255,0) 100%);
    }

    .timeline:after {
        display: inline-block; 
        content: ""; 
        width: 100%;
    }

    .timeline li {
        display: inline-block;
        width: 15px;
        height: 15px;
        background: #bbdbbe;
        text-align: center;
        line-height: 1.5;
        position: sticky;
        border-radius: 50%;
    }

    .timeline li p {
        display: inline-block;
        font-size: 14px;
        margin-top: 20px;
        position: absolute;
        left: 50%;
        -webkit-transform: translateX(-50%);
        -moz-transform: translateX(-50%);
        -ms-transform: translateX(-50%);
        -o-transform: translateX(-50%);
        transform: translateX(-50%);
    } */


    .timeline .timeline-item {
        display: inline-block;
        position: relative;
        padding-right: 3em;
    }

    .timeline .timeline-item::before {
        background: #dadee4;
        content: "";
        height: 1px;
        margin-left: 60px;
        position: absolute;
        top: 34px;
        width: 100%;
    }

    .timeline .timeline-item .timeline-icon{
        margin-top: 24px;
        width: 20px;
        height: 20px;
        background: #bbdbbe;
        border-radius: 50%;
        display: flex;
        align-items: center;
        z-index: 1;
        margin-left: 40px;
    }

    .timeline .timeline-item .timeline-content {
        -ms-flex: 1; 
        flex: 1;
        padding: 0 0 0 1rem;
    }

    .timeline .timeline-item-last {
        display: inline-block;
        position: relative;
        padding-right: 5em;
    }

    .timeline .timeline-item-last::before {
        background: #dadee4;
        content: "";
        height: 1px;
        margin-left: 60px;
        position: absolute;
        top: 34px;
        width: 1px;
    }

    .timeline .timeline-icon-last{
        margin-top: 24px;
        width: 20px;
        height: 20px;
        background: #737373;
        border-radius: 50%;
        display: flex;
        align-items: center;
        z-index: 1;
        margin-left: 40px;
    }

    #table-mob{
          display : none;
    }

      #table-desk{
          display : block;
    }
  
    @media (max-width: 767px) {
      #table-mob{
          display : block;
      }

      #table-desk{
          display : none;
      }
    
    }

    @media (max-width: 575px){
        .container{
            padding: 2em;
        }
        .table-responsive{
            margin: 1em;
        }
    }

    @media (min-width: 992px) and (max-width: 1200px) {
        .timeline .timeline-item {
            padding-right: 0;
        }
    }

    @media (max-width: 991px){
        .timeline .timeline-item {
            display: flex;
            position: relative;
        }

        .timeline .timeline-item::before {
            background: #dadee4;
            content: "";
            height: 100%;
            margin-left: 19px;
            position: absolute;
            margin-top: 24px;
            width: 1px;
        }

        .timeline .timeline-item .timeline-icon{
            margin-top: 24px;
            width: 20px;
            height: 20px;
            background: #bbdbbe;
            border-radius: 50%;
            display: flex;
            align-items: center;
            z-index: 1;
            margin-left: 9px;
        }

        .timeline .timeline-item .timeline-content {
            -ms-flex: 1; /* IE 10 */
            flex: 1;
            padding: 0 0 0 1rem;
        }

        .timeline .timeline-item-last {
            display: flex;
            position: relative;
        }

        .timeline .timeline-item-last::before {
            background: #fff;
            content: "";
            height: 100%;
            left: 19px;
            position: absolute;
            top: 20px;
            width: 1px;
            z-index: -1;
        }

        .timeline .timeline-icon-last{
            margin-top: 40px;
            width: 20px;
            height: 20px;
            background: #737373;
            border-radius: 50%;
            display: flex;
            align-items: center;
            z-index: 1;
            margin-left: 9px;
        }
    }
</style>

<section id="intro" class="clearfix">
    <div class="container">
        <div class="row justify-content-center">
            <h2 class="text-center" style="margin-bottom: 0.2em; font-weight: 600; color: #002853;">Program Pinjamin Produk 5 Hari</h2>
        </div>
        <div class="row justify-content-center">
            <h4 class="text-center" style="font-weight: 400;">Coba Produk di Rumah Sendiri</h4>
        </div>
        <br>
        <div class="row justify-content-center mt-5">
            <h4 class="text-center" style="font-size: 50px; font-family: 'Poppins', sans-serif; font-weight: 500; color: #f0bc5e;">
                Selamat Bpk/Ibu {{ $personalhomecare['name'] }}
            </h4>
        </div>
        <div class="row justify-content-center">
            <p class="text-center" style="font-size: 18px; color: #000; font-weight: 500;">
                telah tergabung dalam program
                <span style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #002853;"> Pinjamin Produk 5 Hari</span> 
            </p> 
        </div>
        <div class="row my-5">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                <div class="d-flex" style="align-items: center;">
                    <img style="margin-right: 15px; width: 120px;" src="{{asset('sources/ph-desc.png')}}"> 
                    <span>
                        <p style="margin-top: 2em; margin-bottom: 0.7em; font-weight: bold;">APA ITU PROGRAM PINJAMIN PRODUK 5 HARI?</p>
                        <p>Bapak & Ibu bisa mencoba produk WAKi (Electro/Hepa/HPT) di rumah sendiri selama 5 hari, dimana petugas kami akan mendemokan produk tersebut sebelumnya.</p>
                    </span>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                <div class="d-flex" style="align-items: center;">
                    <img style="margin-right: 15px; width: 120px;" src="{{asset('sources/ph-form.png')}}"> 
                    <span>
                    <p style="margin-top: 2em; margin-bottom: 0.7em; font-weight: bold;">TUJUAN :</p>
                    <p>Visi WAKi adalah Sehat Bersama WAKi menuju kehidupan yang lebih baik. Dengan kondisi seperti ini, program ini dibuat khusus untuk meminimalisirkan adanya kontak fisik secara langsung. </p>
                    </span>
                </div>
            </div>
        </div>
        <div class="row">
            
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <div class="card card-shadow mt-3">
                    <div class="card-header" style="background: none;">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" 
                                    style="font-weight: 600; font-size: 1em;" 
                                    id="one-tab" 
                                    data-toggle="tab" 
                                    href="#one" 
                                    role="tab" 
                                    aria-controls="One" 
                                    aria-selected="true">
                                    Data Customer
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active p-3" id="one" role="tabpanel" aria-labelledby="one-tab">
                            <div class="row">
                                <div class="col-3">
                                    <label style="color: #737373; font-weight: 600; font-size: 0.9em; ">
                                        Nama
                                    </label>

                                </div>
                                <div class="col-9">
                                    <label style="color: #737373; font-weight: 600; font-size: 0.9em; ">
                                       : {{ $personalhomecare['name'] }}
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <label style="color: #737373; font-weight: 600; font-size: 0.9em; ">
                                        Nomor Telepon
                                    </label>

                                </div>
                                <div class="col-9">
                                    <label style="color: #737373; font-weight: 600; font-size: 0.9em; ">
                                       : {{ $personalhomecare['phone'] }}
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <label style="color: #737373; font-weight: 600; font-size: 0.9em;  ">
                                        Alamat
                                    </label>

                                </div>
                                <div class="col-9">
                                    <label style="color: #737373; font-weight: 600; font-size: 0.9em; ">
                                       : {{ $personalhomecare['address'] }},
                                        {{ $personalhomecare->getProvinceName() }}, 
                                        {{ $personalhomecare->getCityFullName() }}, 
                                        {{ $personalhomecare->getDistrictName() }}
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <label style="color: #737373; font-weight: 600; font-size: 0.9em;  ">
                                        Branch
                                    </label>

                                </div>
                                <div class="col-9">
                                    <label style="color: #737373; font-weight: 600; font-size: 0.9em; ">
                                        : {{ $personalhomecare->branch->code }} - {{ $personalhomecare->branch->name }}
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    <label style="color: #737373; font-weight: 600; font-size: 0.9em;  ">
                                        CSO
                                    </label>

                                </div>
                                <div class="col-9">
                                    <label style="color: #737373; font-weight: 600; font-size: 0.9em; ">
                                        : {{ $personalhomecare->cso->code }} - {{ $personalhomecare->cso->name }}
                                    </label>
                                </div>
                            </div>
                        </div>      
                    </div>
                </div>
            </div>
        </div><br>

        <div class="row mt-5 mb-5">
            <div id="table-desk" class="col-md-12 col-sm-12 col-xs-12">
                <ul class="responsive-table">
                    <li class="table-header aqua-gradient color-block">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">Jadwal</div>
                        <div class="col-lg-4 col-md-3 col-sm-3 col-xs-3">Nama Produk</div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">Kode Produk</div>
                    </li>
                    <li class="table-row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" data-label="Jadwal">
                            {{ date("d/m/Y", strtotime($personalhomecare->schedule)) }} - {{ date("d/m/Y", strtotime($personalhomecare->schedule . "+5 days")) }}
                        </div>
                        <div class="col-lg-4 col-md-3 col-sm-3 col-xs-3" data-label="Nama Produk">
                            {{ $personalhomecare->personalHomecareProduct->product['code'] }} - {{ $personalhomecare->personalHomecareProduct->product['name'] }}
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3" data-label="Kode Produk">
                            {{ $personalhomecare->personalHomecareProduct->code }}
                        </div>
                    </li>
                </ul>
            </div>
            <div id="table-mob" class="table-responsive">
                <table class="table col-md-12">
                    <thead>
                        <tr>
                            <th>Jadwal</th>
                            <th>Nama Produk</th>
                            <th>Kode Produk</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                {{ date("d/m/Y", strtotime($personalhomecare->schedule)) }} - {{ date("d/m/Y", strtotime($personalhomecare->schedule . "+5 days")) }}
                            </td>
                            <td>
                                {{ $personalhomecare->personalHomecareProduct->product['code'] }} - {{ $personalhomecare->personalHomecareProduct->product['name'] }}
                            </td>
                            <td>
                                {{ $personalhomecare->personalHomecareProduct->code }}
                            </td>
                        </tr>
                    </tbody>    
                </table>
            </div>
        </div>

        <div class="row justify-content-center mb-5">
            <div class="col-md-12">
                <div class="card card-shadow">
                    <div class="card-header" style="background-color: #fff; padding-bottom: 0; padding-top: 20px;">
                        <h6 style="font-weight: 600;">Status</h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline clearfix">
                            @foreach ($histories as $history)
                                <?php $dataChange = json_decode($history->meta, true); ?>
                                @foreach ($dataChange["dataChange"] as $value)
                                <div class="timeline-item">
                                    <div class="timeline-icon"></div>
                                    <div class="timeline-content">
                                        <div class="card-body">
                                            <p style="font-weight: 600; margin-bottom: 10px;">{{ var_export($value, true) }}</p>
                                            <p style="font-weight: 500;">
                                                {{ date("d/m/Y H:i:s", strtotime($history->created_at)) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endforeach

                            <!-- dummy -->
                            <div class="timeline-item">
                                <div class="timeline-icon">
                                </div>
                                <div class="timeline-content">
                                    <div class="card-body">
                                        <p style="font-weight: 600; margin-bottom: 10px;">New</p>
                                        <p style="font-weight: 500;">date</p>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon">
                                </div>
                                <div class="timeline-content">
                                    <div class="card-body">
                                        <p style="font-weight: 600; margin-bottom: 10px;">Approved_out</p>
                                        <p style="font-weight: 500;">date</p>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon">
                                </div>
                                <div class="timeline-content">
                                    <div class="card-body">
                                        <p style="font-weight: 600; margin-bottom: 10px;">Process</p>
                                        <p style="font-weight: 500;">date</p>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon">
                                </div>
                                <div class="timeline-content">
                                    <div class="card-body">
                                        <p style="font-weight: 600; margin-bottom: 10px;">Waiting_in</p>
                                        <p style="font-weight: 500;">date</p>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon">
                                </div>
                                <div class="timeline-content">
                                    <div class="card-body">
                                        <p style="font-weight: 600; margin-bottom: 10px;">Done</p>
                                        <p style="font-weight: 500;">date</p>
                                    </div>
                                </div>
                            </div>
                            <!-- end dummy -->


                            <div class="timeline-item-last">
                                <div class="timeline-icon-last">
                                </div>
                                <div class="timeline-content" style="visibility: hidden;">
                                    <div class="card-body">
                                        <p style="font-weight: 600; margin-bottom: 10px;"></p>
                                        <p style="font-weight: 500;"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                            <td>Program pinjamin produk 5 hari (PP5H) tidak dipungut biaya apapun.</td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td>Sudah menjadi member WAKimart dan minimal berusia 35 tahun.</td>
                        </tr>
                        <tr>
                            <td>3.</td>
                            <td>Apabila ada kekurangan, kehilangan ataupun kerusakan pada produk saat pengembalian, berarti membeli barang dengan harga normal sesuai ketentuan yang ada.</td>
                        </tr>
                        <tr>
                            <td>4.</td>
                            <td>Kekurangan aksesoris produk harap dikembalikan paling lambat 7 hari setelah program PP5H berakhir.</td>
                        </tr>
                        <tr>
                            <td>5.</td>
                            <td>Syarat dan Ketentuan dapat berubah tanpa pemeberitahuan sebelumnya.</td>
                        </tr>
                        <tr>
                            <td>6.</td>
                            <td>Kenyamanan dan keamanan konsumen kami adalah prioritas pertama.
                            Apabila ada sesuatu atau ada pertanyaan bisa menghubungi melalui facebook page 
                            WAKi Indonesia atau customer care kami di : +62 815-5467-3357</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>

<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
@endsection
