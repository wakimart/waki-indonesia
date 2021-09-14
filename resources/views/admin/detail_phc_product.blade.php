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
                <h3 class="page-title">Detail Product PHC</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a data-toggle="collapse"
                                href="#"
                                aria-expanded="false"
                                aria-controls="acceptance-dd">
                                Personal Homecare Product
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Detail Product
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
                            <h2>Product Detail</h2>
                        </div>
                        <div class="row justify-content-center">
                            <div class="table-responsive">
                                <table class="col-md-12 table-borderless">
                                    <tr>
                                        <td>Product Code</td>
                                        <td>:</td>
                                        <td>
                                            {{ $phcproducts->code}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Warehouse</td>
                                        <td>:</td>
                                        <td>{{ $phcproducts['warehouse'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Branch</td>
                                        <td>:</td>
                                        <td>{{ $phcproducts->branch->code }} - {{ $phcproducts->branch->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Product</td>
                                        <td>:</td>
                                        <td>
                                            {{ $phcproducts->product->code }} - {{ $phcproducts->product->name }}
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
                            <h2>Product Current Checklist</h2>
                        </div>
                        <div class="row justify-content-center">
                            <div class="table-responsive">
                                <table class="col-md-12">

                                    @php
                                        $prd_firstLetter = substr($phcproducts['code'], 0, 1);
                                        $arr_completness = App\PersonalHomecareChecklist::$completeness_list[$prd_firstLetter];
                                    @endphp

                                    <tr>
                                        <td rowspan="{{ sizeof($arr_completness)+1 }}">Completeness</td>
                                        <td>
                                            <i class="mdi {{ in_array($arr_completness[0],
                                                $phcproducts->currentChecklist['condition']['completeness']) ?
                                                "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                style="font-size: 24px; color: #fed713;">
                                            </i>
                                            {{$arr_completness[0]}}
                                        </td>
                                    </tr>

                                    @for($idx_i = 1; $idx_i < sizeof($arr_completness); $idx_i++)
                                        <tr>
                                            <td>
                                                <i class="mdi {{ in_array($arr_completness[$idx_i],
                                                    $phcproducts->currentChecklist['condition']['completeness']) ?
                                                    "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                    style="font-size: 24px; color: #fed713;">
                                                </i>
                                                {{$arr_completness[$idx_i]}}
                                            </td>
                                        </tr>
                                    @endfor

                                    <tr>
                                        <td>
                                            <i class="mdi {{ isset($phcproducts->currentChecklist['condition']['other']) ?
                                                "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}"
                                                style="font-size: 24px; color: #fed713;">
                                            </i>
                                            Other :
                                            {{ isset($phcproducts->currentChecklist['condition']['other']) ?
                                                $phcproducts->currentChecklist['condition']['other'] : "-" }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Machine Condition</td>
                                        <td>
                                            {{ ucwords($phcproducts->currentChecklist['condition']['machine']) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Physical Condition</td>
                                        <td>
                                            {{ ucwords($phcproducts->currentChecklist['condition']['physical']) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Product Photo</td>
                                        <td>
                                            @foreach ($phcproducts->currentChecklist['image'] as $img)
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

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <h2>Status Product Homecare ({{ ucwords($phcproducts->status) }})</h2>
                        </div>
                        <form id="actionAdd"
                            class="forms-sample"
                            method="POST"
                            action="{{ route("update_phc_product") }}">
                            @csrf
                            <input type="hidden"
                                name="id"
                                value="{{ $phcproducts['id'] }}" />
                            <div class="form-group row justify-content-center">
                                @if($phcproducts['status'] != "available")
                                    <button type="submit"
                                        class="btn btn-gradient-primary mr-2 btn-lg"
                                        name="status"
                                        value="available">
                                        Available
                                    </button>
                                @endif
                                @if($phcproducts['status'] != "unavailable")
                                    <button type="submit"
                                        class="btn btn-gradient-danger mr-2 btn-lg"
                                        name="status"
                                        value="unavailable">
                                        Unavailable
                                    </button>
                                @endif
                                @if($phcproducts['status'] != "pending")
                                    <button type="submit"
                                        class="btn btn-gradient-warning mr-2 btn-lg"
                                        name="status"
                                        value="pending">
                                        Pending
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if ($histories->isNotEmpty())
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>Product Homecare History Log</h2>
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
    </div>
</div>
@endsection

@section("script")
<script type="application/javascript">

</script>
@endsection
