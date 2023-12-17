<?php
$menu_item_page = "stock";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    #intro {
        padding-top: 2em;
        min-width: 0;
    }
    input[readonly], textarea[readonly]{
        background-color: #fff !important;
    }
    input[readonly]:focus, textarea[readonly]:focus{
        outline: none !important;
        border:1px solid #ebedf2;
    }
</style>
@endsection

@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="row justify-content-center">
                    <button id="btn-print"
                        type="button"
                        class="btn btn-gradient-info m-1">
                        Print
                        <span>
                            <i class="mdi mdi-printer" 
                                style="margin-left: 5px; 
                                    font-size: 24px; 
                                    vertical-align: middle;">
                            </i>
                        </span>
                    </button>
                </div>
            </div>
            <!-- LAYOUT PRINT -->
            <div id="element-to-print" class="col-12">
                @php $i=1; @endphp
                @foreach ($stockWarehouses as $keyWarehouse => $stockInOuts)
                    <div class="card">
                        <div class="card-body" style="padding-left: 2em; padding-right: 2em;">
                            <h5 class="text-center">Stock In/Out</h5>
                            <h5 class="text-center">{{ date('d F Y', strtotime($selectedDate)) }}</h5>
                            <h5 class="text-center">{{ $keyWarehouse }} - {{ $warehouses[$keyWarehouse]->name }}</h5>
                            @if ($selectedWarehouse)
                            <p class="mb-0">
                                Parent Warehouse : {{ $selectedWarehouse->code }} - {{ $selectedWarehouse->name }}
                                <br />
                            </p>
                            @endif
                            <div class="table-responsive" style="border: 1px solid #ebedf2;">
                                <div class="table-responsive"
                                    style="border: 1px solid #ebedf2;">
                                    <table class="table table-bordered" id="myTable">
                                        <thead>
                                            <tr>
                                                <th style="vertical-align: middle;text-align: center;">No.</th>
                                                <th style="vertical-align: middle;text-align: center;">Code</th>
                                                <th style="vertical-align: middle;text-align: center;">Name</th>
                                                <th style="vertical-align: middle;text-align: center;">
                                                    Qty <br> {{ date('d-m-Y', strtotime("-1 days", strtotime($selectedDate))) }}
                                                </th>
                                                <th style="vertical-align: middle;text-align: center;">In</th>
                                                <th style="vertical-align: middle;text-align: center;">Out</th>
                                                <th style="vertical-align: middle;text-align: center;">Qty Final</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $idxNo = 0; @endphp
                                            @foreach ($stockInOuts as $stockInOut)
                                                @php
                                                    if(!$stockInOut->warehouse_id){
                                                        continue;
                                                    }
                                                    $idxNo++;
                                                @endphp
                                                <tr>
                                                    <td style="vertical-align: top; text-align: right;">{{ $idxNo }}</td>
                                                    <td style="vertical-align: top; text-align: left;">{{ $stockInOut->code }}</td>
                                                    <td style="vertical-align: top; text-align: left;">{{ $stockInOut->name }}</td>
                                                    <td style="vertical-align: top; text-align: right;">
                                                        {{ number_format(
                                                            $stockInOut->sum_current_quantity 
                                                            + $stockInOut->today_out - $stockInOut->today_in
                                                            + $stockInOut->selectedDate_out - $stockInOut->selectedDate_in) }}
                                                    </td>
                                                    <td style="vertical-align: top; text-align: right;">
                                                        {{ number_format($stockInOut->selectedDate_in) }}
                                                    </td>
                                                    <td style="vertical-align: top; text-align: right;">
                                                        {{ number_format($stockInOut->selectedDate_out) }}
                                                    </td>
                                                    <td style="vertical-align: top; text-align: right;">
                                                        {{ number_format( $stockInOut->sum_current_quantity + $stockInOut->today_out - $stockInOut->today_in ) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($i < count($stockWarehouses))
                    <div class="clearfix"></div>
                    <div class="clearfix"></div>
                    <p class="mb-0" style="page-break-after: always;">&nbsp;</p>
                    <p class="mb-0" style="page-break-before: always;">&nbsp;</p>
                    @endif
                    @php $i++; @endphp
                @endforeach
            </div>
            <!-- end layout print -->
        </div>
    </div>
</div>

@endsection

@section("script")
<script type="application/javascript">
$(document).ready(function() {
    print();
    $(document).on("click", '#btn-print', function(){
       print() 
    });
    $("#success-alert").hide();
    function print() {
        $(".hide-print").hide();
        var printContents = document.getElementById("element-to-print").innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        return true;
    }
});
</script>
@endsection