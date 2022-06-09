<?php
$menu_item_page = "data_therapy";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    #intro {
        padding-top: 2em;
    }

    #regForm {
        background-color: #ffffff;
        margin: 100px auto;
        padding: 40px;
        width: 70%;
        min-width: 300px;
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

    .imagePreview {
        width: 100%;
        height: 150px;
        background-position: center center;
        background-color: #fff;
        background-size: cover;
        background-repeat: no-repeat;
        display: inline-block;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Detail Data Therapy</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#data_therapy-dd"
                            aria-expanded="false"
                            aria-controls="data_therapy-dd">
                            Data Therapy
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Detail Data Therapy
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <h2>Detail Data Therapy</h2>
                        </div>
                        <div class="row justify-content-center">
                            <div class="table-responsive">
                                <table class="col-md-12">
                                    <thead>
                                        <td colspan="2">Data</td>
                                    </thead>
                                    <tr>
                                        <td>Nama</td>
                                        <td>
                                            {{ $data_therapy['name'] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Nomor KTP</td>
                                        <td>{{ $data_therapy['no_ktp'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Branch</td>
                                        <td>{{ $data_therapy->branch['code'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>CSO</td>
                                        <td>
                                            {{ $data_therapy->cso['name'] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Phone</td>
                                        <td>{{ $data_therapy['phone'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td>{{ $data_therapy['address'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Type Customer</td>
                                        <td>
                                            {{ $data_therapy->type_customer['name'] }}
                                            @if ($data_therapy['created_at'] == null && $data_therapy['updated_at'] == null)
                                            (old data)
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                                <div class="form-group">
                                    <div class="col-xs-12 col-sm-6 col-md-4 imgUp"
                                        style="padding: 15px; float: left;">
                                        <label>Photo KTP</label>
                                        @if ($data_therapy->img_ktp)
                                        @php $defaultImg = asset('sources/therapy_images/'); @endphp
                                            <div class="imagePreview"
                                                style="background-image: url({{ $defaultImg . '/' . $data_therapy->img_ktp }})"></div>
                                        @else
                                            <div class="imagePreview"
                                                style="background-image: url({{ asset('sources/dashboard/no-img-banner.jpg') }});"></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
