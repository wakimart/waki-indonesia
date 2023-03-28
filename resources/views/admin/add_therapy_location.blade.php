<?php
$menu_item_page = "theraphy_service";
$menu_item_second = "add_therapy_location";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
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
    input {
        border-radius: 0 !important;
        box-shadow: none !important;
        border: 1px solid #dce1ec !important;
        font-size: 14px !important;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Add Therapy Location</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            aria-expanded="false">
                            Therapy Location
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Add Therapy Location
                    </li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form id="actionAdd"
                            method="POST"
                            action="{{ route("store_therapy_location") }}">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    placeholder="Nama lokasi"
                                    maxlength="191"
                                    required />
                                <?php if ($errors->has("name")): ?>
                                    <div class="validation">
                                        <?php echo $errors->first("name"); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <button id=""
                                    type="submit"
                                    class="btn btn-gradient-primary">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
