<?php
$menu_item_page = "registerevent";
$menu_item_second = "list_regispromo";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="{{ asset("css/lib/select2/select2-bootstrap4.min.css") }}" />
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

    input, select, textarea{
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

    /* Style the input fields */
    input {
        padding: 10px;
        width: 100%;
        font-size: 17px;
        font-family: Raleway;
        border: 1px solid #aaaaaa;
    }

    /* Mark input boxes that gets an error on validation: */
    input.invalid {
        background-color: #ffdddd;
    }

    .select2-selection__rendered {
        line-height: 45px !important;
    }

    .select2-container .select2-selection--single {
        height: 45px !important;
    }

    .form-control{
        background-color:#FFF !important;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
      <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Add Registration WAKi Di Rumah Aja</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#"
                            aria-expanded="false"
                            aria-controls="registerevent-dd">
                            Registration Event
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Add Registration
                    </li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="">
                            <div class="form-group">
                                <label><h2>Data Pelanggan</h2></label>
                                <br/>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="first_name">First Name</label>
                                        <input type="text"
                                            class="form-control"
                                            id="first_name"
                                            name="first_name"
                                            placeholder="First Name"
                                            readonly value="{{ $registration_promotion->first_name }}"/>
                                        <div class="validation"></div>
                                    </div>
                                    <div class="col-6">
                                        <label for="last_name">Last Name</label>
                                        <input type="text"
                                            class="form-control"
                                            id="last_name"
                                            name="last_name"
                                            placeholder="Last Name"
                                            readonly value="{{ $registration_promotion->last_name }}"/>
                                        <div class="validation"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control"
                                    id="address"
                                    name="address"
                                    rows="4"
                                    placeholder="Full Address" readonly>{{ $registration_promotion->address }}</textarea>
                                <div class="validation"></div>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="number"
                                    class="form-control"
                                    id="phone"
                                    name="phone"
                                    placeholder="Phone Number"
                                    readonly value="{{ $registration_promotion->phone }}"/>
                                <div class="validation"></div>
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="text"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    placeholder="Email Address"
                                    readonly value="{{ $registration_promotion->email }}"/>
                                <div class="validation"></div>
                            </div>

                            <div id="errormessage"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
@endsection
