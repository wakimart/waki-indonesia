<?php

use App\Promo;

$menu_item_page = "submission";
$menu_item_second = "detail_submission_form";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    #intro {
        padding-top: 2em;
    }

    table {
        margin: 1em;
        font-size: 14px;
    }

    .table-responsive table {
        display: inline-table;
        table-layout:fixed;
        overflow:scroll;
    }

    .table-responsive table td {
        word-wrap:break-word;
    }

    .table-responsive table .form-control {
        height: 32px;
        line-height: 32px;
        box-shadow: none;
        border-radius: 2px;
        margin-bottom: 0;
    }

    .table-responsive table .form-control.error {
        border-color: #f50000;
        border: 1px solid red;
    }

    .table-responsive table td .save {
        display: none;
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

    .pInTable {
        margin-bottom: 6pt !important;
        font-size: 10pt;
    }

    select.form-control {
        color: black !important;
    }
</style>
@endsection

@section('content')
@if ($submission->code !== null)
    <section id="intro" class="clearfix">
        <div class="container">
            <div class="row justify-content-center">
                <h2>SUBMISSION SUCCESS</h2>
            </div>
            <div class="row justify-content-center">
                <table class="col-md-12">
                    <thead>
                        <td class="right">Submission Date</td>
                    </thead>
                    <tr>
                        <td class="right">
                            {{ date("d/m/Y H:i:s", strtotime($submission->created_at)) }}
                        </td>
                    </tr>
                </table>

                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Customer Data</td>
                    </thead>
                    <tr>
                        <td>Member Code: </td>
                        <td>{{ $submission->no_member }}</td>
                    </tr>
                    <tr>
                        <td>Name: </td>
                        <td>{{ $submission->name }}</td>
                    </tr>
                    <tr>
                        <td>Phone Number: </td>
                        <td>{{ $submission->phone }}</td>
                    </tr>
                    <tr>
                        <td rowspan="2">Address: </td>
                        <td>{{ $submission->address }}</td>
                    </tr>
                    <tr>
                        <td>
                            <?php
                            echo $submission->district
                                . ", "
                                . $submission->city
                                . ", "
                                . $submission->province;
                            ?>
                        </td>
                    </tr>
                </table>

                <table class="col-md-12">
                    <thead>
                        <td>Sales Branch</td>
                        <td>Sales Code</td>
                    </thead>
                    <tr>
                        <td style="width:50%; text-align: center">
                            <?php
                            echo $submission->branch_code
                                . " - "
                                . $submission->branch_name;
                            ?>
                        </td>
                        <td style="width:50%; text-align: center">
                            {{ $submission->cso_code }}
                        </td>
                    </tr>
                </table>

                <div class="table-responsive">
                    <table class="col-md-12">
                        <thead>
                            <td colspan="10">Reference</td>
                        </thead>
                        <thead style="background-color: #80808012 !important;">
                            <tr>
                                <td>Name</td>
                                <td>Age</td>
                                <td>Phone</td>
                                <td>Province</td>
                                <td>City</td>
                                <td>Promo 1</td>
                                <td>Qty 1</td>
                                <td>Promo 2</td>
                                <td>Qty 2</td>
                                <td>Image</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($references as $key => $reference): ?>
                                <tr>
                                    <td id="name_{{ $key }}">
                                        {{ $reference->name }}
                                    </td>
                                    <td class="center" id="age_{{ $key }}">
                                        {{ $reference->age }}
                                    </td>
                                    <td class="center" id="phone_{{ $key }}">
                                        {{ $reference->phone }}
                                    </td>
                                    <td id="province_{{ $key }}">
                                        {{ $reference->province }}
                                    </td>
                                    <td id="city_{{ $key }}">
                                        {{ $reference->city }}
                                    </td>
                                    <td>
                                        <?php
                                        if ($reference->promo_1 !== null) {
                                            $queryPromo = Promo::find($reference->promo_1);
                                            $promo = implode(", ", $queryPromo->productCode());
                                            echo $queryPromo->code . " - (" . $promo . ")";
                                        } else {
                                            echo $reference->other_1;
                                        }
                                        ?>
                                    </td>
                                    <td>{{ $reference->qty_1 }}</td>
                                    <td>
                                        <?php
                                        if ($reference->promo_2 !== null) {
                                            $queryPromo = Promo::find($reference->promo_2);
                                            $promo = implode(", ", $queryPromo->productCode());
                                            echo $queryPromo->code . " - (" . $promo . ")";
                                        }

                                        if (
                                            $reference->promo_2 === null
                                            && $reference->other_2 !== null
                                        ) {
                                            echo $reference->other_2;
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($reference->qty_2 !== null) {
                                            echo $reference->qty_2;
                                        }
                                        ?>
                                    </td>
                                    <td class="center">
                                        @for ($i = 1; $i <= 2; $i++)
                                            @if (!empty($reference["image_" . $i]))
                                                <a href="{{ asset("sources/registration/" . $reference["image_" . $i]) }}"
                                                    target="_blank">
                                                    <i class="mdi mdi-numeric-{{ $i }}-box" style="font-size: 24px; color: blue;"></i>
                                                </a>
                                            @endif
                                        @endfor
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($historySubmission->isNotEmpty())
                <div class="row justify-content-center"
                    style="margin-top: 2em;">
                    <h2>SUBMISSION HISTORY LOG</h2>
                </div>
                <div class="row justify-content-center">
                    <table class="col-md-12">
                        <thead>
                            <td class="center">No.</td>
                            <td>Action</td>
                            <td>User</td>
                            <td>Change</td>
                            <td>Time</td>
                        </thead>
                        @foreach ($historySubmission as $key => $history)
                            <?php $dataChange = json_decode($history->meta, true); ?>
                            <tr>
                                <td class="right">{{ $key + 1 }}</td>
                                <td class="center">
                                    {{ $history->method }}
                                </td>
                                <td class="center">
                                    {{ $history->name }}
                                </td>
                                <td>
                                    @foreach ($dataChange["dataChange"] as $key => $value)
                                        <b>{{ $key }}</b>: {{ var_export($value, true) }}
                                        <br>
                                    @endforeach
                                </td>
                                <td class="center">
                                    {{ date("d/m/Y H:i:s", strtotime($history->created_at)) }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif
        </div>
    </section>
@else
    <div class="row justify-content-center">
        <h2>CANNOT FIND SUBMISSION</h2>
    </div>
@endif
@endsection
