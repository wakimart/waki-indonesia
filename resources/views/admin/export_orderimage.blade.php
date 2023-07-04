<?php
$menu_item_page = "list_cust_image";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="{{ asset("css/lib/select2/select2-bootstrap4.min.css") }}" />
<style type="text/css">
    .select2-results__options{
        max-height: 15em;
        overflow-y: auto;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Export Customer Image</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#data_sourcing-dd"
                            aria-expanded="false"
                            aria-controls="data_sourcing-dd">
                            Data Menu
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Export Customer Image
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form id="actionAdd"
                            class="forms-sample"
                            method="GET"
                            enctype="multipart/form-data"
                            action="{{ route('download_cust_image') }}">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6 col-12">
                                    <label for="">Month & Year</label>
                                    <input type="month"
                                        class="form-control"
                                        id="filter_month"
                                        name="filter_month"
                                        value="{{ isset($_GET['filter_month']) ? $_GET['filter_month'] : date('Y-m') }}">
                                    <div class="validation"></div>
                                </div>
                                <div class="form-group col-md-3 col-12">
                                    <button id="exportCustImage"
                                        type="submit"
                                        class="form-control btn btn-gradient-primary">
                                        Download
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- partial -->
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
<script type="application/javascript">
    
</script>
@endsection
