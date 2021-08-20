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

    .table th{
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
    color: #28a745;
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

    @media (max-width: 575px){
        .table-responsive{
            margin: 1em;
        }
    }
</style>

<section id="intro" class="clearfix">
    <div class="container">
        <div class="row justify-content-center">
            <h4>Personal Homecare</h4>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
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
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Tanggal Pengiriman</th>
                            <th scope="col">Kode Produk</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ date("d/m/Y", strtotime($personalhomecare['schedule'])) }}</td>
                            <td>{{ $personalhomecare->personalHomecareProduct->code }}</td>
                            <td>{{ $personalhomecare['status'] }}</td>
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
