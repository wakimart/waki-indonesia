<?php
$menu_item_page = "history_stock";
$menu_item_second = "detail_history_stock";
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
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body" style="padding-left: 2em; padding-right: 2em;">
                        <div class="row justify-content-center mt-3">
                            <h2>DETAIL HISTORY STOCK</h2>
                        </div>
                        <div class="row justify-content-center">
                            <h3>Code : {{$historystock[0]->code}}</h3>
                        </div>
                        <hr>
                        <div class="row my-5">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Type</label>
                                    <input class="form-control"
                                        type="text"
                                        readonly
                                        value="{{ ucfirst($historystock[0]->type) }}" />
                                </div>
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date"
                                        class="form-control"
                                        id="date"
                                        value="{{ $historystock[0]->date }}"
                                        readonly />
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="from_warehouse_id">From Warehouse</label>
                                            <input type="text"
                                                class="form-control"
                                                id="from_warehouse_id"
                                                value="{{$historystock[0]->stockFrom->warehouse['name'] }}"
                                                readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="to_warehouse_id">To Warehouse</label>
                                            <input type="text"
                                                class="form-control"
                                                id="to_warehouse_id"
                                                value="{{$historystock[0]->stockTo->warehouse['name'] ?? '' }}"
                                                readonly />
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $countHistoryStock = count($historystock);
                                ?>

                                @for ($i = 0; $i < $countHistoryStock; $i++)
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="product_{{ $i }}">Product {{ $i+1 }}</label>
                                            <input type="text"
                                                class="form-control"
                                                id="product_{{ $i }}"
                                                value="{{ $historystock[$i]->stockFrom->product['name']  }}"
                                                readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="quantity_{{ $i }}">
                                                Quantity
                                            </label>
                                            <input id="quantity_{{ $i }}"
                                                class="form-control"
                                                value="{{ $historystock[$i]->quantity }}"
                                                readonly />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="Koli{{ $i }}">
                                                Koli
                                            </label>
                                            <input id="Koli{{ $i }}"
                                                class="form-control"
                                                value="{{ $historystock[$i]->koli }}"
                                                readonly />
                                        </div>
                                    </div>
                                </div>
                                @endfor

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control"
                                        id="description"
                                        rows="2"
                                        readonly>{{ $historystock[0]->description }}
                                    </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center my-5">
                            <button id="btn-print"
                                type="button"
                                class="btn btn-gradient-info m-1">
                                Create PDF
                                <span>
                                    <i class="mdi mdi-file-document" 
                                        style="margin-left: 5px; 
                                            font-size: 24px; 
                                            vertical-align: middle;">
                                    </i>
                                </span>
                            </button>
                        </div>

                        <!-- LAYOUT PRINT -->
                        <div class="row">
                            <div id="element-to-print"
                                class="col-12 grid-margin stretch-card showPrinted">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div>
                                            <h5 style="font-weight: 600;">
                                                Surat Pengantar
                                            <h5>
                                            <div style="margin: auto; text-align: center;">
                                                <h5>
                                                    D/O {{ strtoupper($historystock[0]->type) }}
                                                </h5>
                                            </div>
                                            <br>
                                        </div>
                                        <div class="row">
                                            <div class="col-2">
                                                <div style="margin-bottom: 5px; text-align: right;">
                                                    <h5>Kode :</h5>
                                                </div>
                                            </div>
                                            <div class="col-10" style="padding-left: 0;">
                                                <div style="margin-bottom: 5px;">
                                                    <h5>{{$historystock[0]->code}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2">
                                                <div style="margin-bottom: 5px; text-align: right;">
                                                    <h5>Tanggal :</h5>
                                                </div>
                                            </div>
                                            <div class="col-10" style="padding-left: 0;">
                                                <div style="margin-bottom: 5px;">
                                                    <h5>{{$historystock[0]->date}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2">
                                                <div style="margin-bottom: 5px; text-align: right;">
                                                    <h5>Kode Cabang :</h5>
                                                </div>
                                            </div>
                                            <div class="col-10" style="padding-left: 0;">
                                                <div style="margin-bottom: 5px;">
                                                    <h5>
                                                        {{$historystock[0]->stockFrom->warehouse['code'] }}
                                                        -
                                                        {{$historystock[0]->stockTo->warehouse['code'] ?? '' }}
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2">
                                                <div style="margin-bottom: 5px; text-align: right;">
                                                    <h5>Nama Cabang :</h5>
                                                </div>
                                            </div>
                                            <div class="col-10" style="padding-left: 0;">
                                                <div style="margin-bottom: 5px;">
                                                    <h5>
                                                        {{$historystock[0]->stockFrom->warehouse['name'] }}
                                                        -
                                                        {{$historystock[0]->stockTo->warehouse['name'] ?? ''}}
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center" style="padding: 2em;">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <td class="text-center">No</td>
                                                        <td class="text-center">Kode Produk</td>
                                                        <td class="text-center">Nama Produk</td>
                                                        <td class="text-center">Qty</td>
                                                        <td class="text-center">Koli</td>
                                                    </thead>
                                                    <?php
                                                    $countHistoryStock = count($historystock);
                                                    ?>
                                                    @for ($i = 0; $i < $countHistoryStock; $i++)
                                                    <tr>
                                                        <td class="text-center">{{ $i+1 }}</td>
                                                        <td class="text-center">
                                                            {{ $historystock[$i]->stockFrom->product['code']  }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $historystock[$i]->stockFrom->product['name']  }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $historystock[$i]->quantity }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $historystock[$i]->koli }}
                                                        </td>
                                                    </tr>
                                                    @endfor
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3" style="text-align: right">Total</th>
                                                            <td class="text-center">{{$historystock->sum('quantity')}}</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div style="margin-left: 1em; text-align:justify">
                                            <p>Keterangan : {{$historystock[0]->description}}</p>
                                        </div>
                                    </div>
                                    <div class="card-footer" style="bottom: 3em; background: none; border: none;">
                                        <div class="row mt-5" style="margin-left: 1em;">
                                            <p>Barang-barang tersebut di atas telah diterima dalam keadaan baik.</p>
                                        </div>
                                        <div class="row mt-5" style="margin: auto;">
                                            <div class="col-md-4 text-center">
                                                <p>Dibuat Oleh,</p>
                                                <div style="margin-top: 6em; border-top: 2px solid black;">
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <p>Disetujui Oleh,</p>
                                                <div style="margin-top: 6em; border-top: 2px solid black;">
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <p>Diterima Oleh,</p>
                                                <div style="margin-top: 6em; border-top: 2px solid black;">
                                                    
                                                </div>
                                            </div>
                                                <br><br>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="clearfix"></div>
                                    <p style="page-break-after: always;">&nbsp;</p>
                                    <p style="page-break-before: always;">&nbsp;</p>
                                </div>
                            </div>
                        </div>
                        <!-- end layout print -->

                    </div>
                 </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section("script")
<script type="application/javascript">
$(document).ready(function() {
    $(".showPrinted").hide();
    $("#btn-print").click(function(){
        $(".showPrinted").show();
        $(".hide-print").hide();

        var printContents = document.getElementById("element-to-print").innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;

        $(".showPrinted").hide();
        return true;
    });
    $("#success-alert").hide();
});

</script>
@endsection
