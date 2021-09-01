<?php
    
?>
@extends('layouts.template')

@section('content')
<style type="text/css">
    #intro {
        padding-top: 2em;
    }

    .card-shadow {
        box-shadow: 0 0 10px 0 rgba(100, 100, 100, 0.26);
        padding:1em; 
    }

    #table-mob .table thead{
        background-color: #9eabe4;
        background-image: linear-gradient(315deg, #9eabe4 0%, #77eed8 74%);
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
        color: #63a4ff;
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
        background-image: linear-gradient(315deg, #9eabe4 0%, #77eed8 74%);
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
        <div class="row mb-5">
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
        <div class="row justify-content-center mt-5">
            <h3 class="text-center" style="font-family: 'Poppins', sans-serif; font-weight: 500; color: #63a4ff;">
                Selamat anda telah tergabung dalam program pinjamin produk 5 hari
                <span><img class="img-fluid" style="margin-right: 15px; width: 30px; vertical-align: text-top;" src="{{asset('sources/congrats.png')}}"></span>
            </h3>
             
        </div>
        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <div class="card card-shadow mt-3 ">
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
                                Data Customer</a>
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

        <div class="row mt-5">
            <div id="table-desk" class="col-md-12 col-sm-12 col-xs-12">
                <ul class="responsive-table">
                    <li class="table-header aqua-gradient color-block">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">Jadwal</div>
                        <div class="col-lg-5 col-md-3 col-sm-3 col-xs-3">Nama Produk</div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">Kode Produk</div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3">Status</div>
                    </li>
                    <li class="table-row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" data-label="Jadwal">
                            {{ date("d/m/Y", strtotime($personalhomecare->schedule)) }} - {{ date("d/m/Y", strtotime($personalhomecare->schedule . "+5 days")) }}
                        </div>
                        <div class="col-lg-5 col-md-3 col-sm-3 col-xs-3" data-label="Nama Produk">
                            {{ $personalhomecare->personalHomecareProduct->product['code'] }} - {{ $personalhomecare->personalHomecareProduct->product['name'] }}
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3" data-label="Kode Produk">
                            {{ $personalhomecare->personalHomecareProduct->code }}
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-3 col-xs-3" data-label="Status">
                            {{ $personalhomecare['status'] }}
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
                            <th>Status</th>
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
                            <td>
                                {{ $personalhomecare['status'] }}
                            </td>
                        </tr>
                    </tbody>    
                </table>
            </div>
        </div>

        <div class="row mt-5">
            <div class="table-responsive">
                <table class="table table-borderless sk">
                    <thead>
                        <th colspan="2">Syarat dan Ketentuan</th>
                    </thead>
                    <tbody class="sk">
                        <tr>
                            <td>1.</td>
                            <td>Program pinjamin produk 5 hari tidak dipungut biaya apapun</td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td>Sudah menjadi member WAKimart</td>
                        </tr>
                        <tr>
                            <td>3.</td>
                            <td>Minimal berusia 35 tahun</td>
                        </tr>
                        <tr>
                            <td>4.</td>
                            <td>Biaya akan dikenakan kepada konsumen jika ada sparepart ataupun kerusakan di luar persetujuan</td>
                        </tr>
                        <tr>
                            <td>5.</td>
                            <td>Syarat dan Ketentuan dapat berubah tanpa pemeberitahuan sebelumnya</td>
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
