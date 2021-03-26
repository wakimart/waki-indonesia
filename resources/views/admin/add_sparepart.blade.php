<?php
$menu_item_page = "service";
$menu_item_second = "add_sparepart";
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

    input, select, textarea {
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
            <h3 class="page-title">Add Sparepart</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            aria-expanded="false">
                            Service
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Add Sparepart
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
                            method="POST"
                            action="{{ route("store_sparepart") }}">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    placeholder="Nama"
                                    required />
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="number"
                                    class="form-control"
                                    id="price"
                                    name="price"
                                    placeholder="Harga"
                                    required />
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <button id="add-sparepart"
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
