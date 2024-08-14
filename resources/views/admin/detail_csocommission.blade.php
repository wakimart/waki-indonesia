<?php
    $menu_item_page = "order";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="{{ asset("css/lib/select2/select2-bootstrap4.min.css") }}" />
<style type="text/css">
    #intro { padding-top: 2em; }
    button{
        background: #1bb1dc;
        border: 0;
        border-radius: 3px;
        padding: 8px 30px;
        color: #fff;
        transition: 0.3s;
    }
    table{
        margin-top: 0.5em;
        margin-bottom: 0.5em;
        font-size: 14px;
    }
    table thead{
        background-color: #8080801a;
        text-align: center;
    }
    table td{
        border: 0.5px #8080801a solid;
        padding: 0.5em;
        word-break: break-word;
    }
    .right{ text-align: right; }
    .pInTable{
        margin-bottom: 6pt !important;
        font-size: 10pt;
    }
    .select2-results__options{
        max-height: 15em;
        overflow-y: auto;
    }
    .div-CheckboxGroup {  padding: 5px;  }
    .black-color { color: black !important; }
    .content-wrapper { background: transparent !important;}
</style>
@endsection

@section('content')
    <section id="intro" class="clearfix w-100">
        <div class="container-fluid">
            <div class="text-center">
                <h2>CSO Commision Detail</h2>
            </div>
            <div class="col-md-12">
                <table class="w-100 table-bordered">
                    <tr align="left">
                        <td class="font-weight-bold w-25">Month</td>
                        <td>{{ date('M Y', strtotime($cso_commission->created_at)) }}</td>
                    </tr>
                    <tr align="left"> <!--dummy data -->
                        <td class="font-weight-bold w-25">Branch</td>
                        <td>{{ $cso_commission->cso->branch['code'] }} - {{ $cso_commission->cso->branch['name'] }}</td>
                    </tr>
                    <tr align="left"> <!--dummy data -->
                        <td class="font-weight-bold w-25">CSO Code</td>
                        <td>{{ $cso_commission->cso['code'] }}</td>
                    </tr>
                    <tr align="left"> <!--dummy data -->
                        <td class="font-weight-bold w-25">CSO Name</td>
                        <td>{{ $cso_commission->cso['name'] }}</td>
                    </tr>
                    <tr align="left"> <!--dummy data -->
                        <td class="font-weight-bold w-25">CSO Account Number</td>
                        <td>{{ $cso_commission->cso['no_rekening'] }}</td>
                    </tr>
                </table>
                <br />
                <table class="w-100 table-bordered my-2">
                    <thead class="font-weight-bold" align="center">
                        <td>Netto Sale</td>
                        <td>Cancel</td>
                        <td>Commission</td>
                        <td>Bonus</td>
                        <td>Tax</td>
                        <td>Total Commission Bonus</td>
                    </thead>
                    <tr align="center">
                        @php
                            $netto_sale = floor($cso_commission['total_sales']);
                            $cancel_cso = floor($cso_commission['cancel_cso']);
                            $commission = floor($cso_commission['commission']);
                            $bonus_cso = floor($cso_commission['bonus_cso']);
                        @endphp
                        <td>Rp. {{number_format($netto_sale)}}</td>
                        <td>Rp. {{number_format($cancel_cso)}}</td>
                        <td>Rp. {{ number_format($commission - (0.03*$cancel_cso)) }}</td>
                        <td>Rp. {{number_format($bonus_cso)}}</td>
                        <td>Rp. 0</td>
                        <td>Rp. {{ number_format($commission + $bonus_cso) }}</td>
                    </tr>
                </table>
                <br />
                <table class="w-100 table-bordered my-2">
                    <thead class="font-weight-bold" align="center">
                        <td>Order Date</td>
                        <td>Percentage</td>
                        <td>Order DO</td>
                        <td>Price</td>
                        <td>Bonus</td>
                        <td>Upgrade</td>
                        <td>Lebih Harga</td>
                        <td>Product</td>
                        <td>View</td>
                    </thead>
                    <tbody>
                       @foreach($orders as $val)
                            <tr align="center">
                                <td>{{date('j-M', strtotime($val->order->orderDate))}}</td>
                                <td>{{$val->order['30_cso_id'] == $val->order['70_cso_id'] ? "100%" : ($val->order['30_cso_id'] == $val->cso_id ? "30%" : "70%")}}</td>
                                <td>{{$val->order->temp_no}}</td>
                                <td>Rp. {{number_format($val->order->total_payment)}}</td>
                                <td>Rp. {{number_format($val->bonus)}}</td>
                                <td>Rp. {{number_format($val->upgrade)}}</td>
                                <td>Rp. {{number_format($val->excess_price)}}</td>
                                @php
                                    $curnt_beli = 1;
                                    $curnt_hadiah = 1;
                                    $curnt_upgrade = 1;
                                    $product_beli = "";
                                    $product_hadiah = "";
                                    $product_upgrade = "";

                                    foreach($val->order->orderDetail as $order_detail){
                                        if($order_detail['type'] == "pembelian"){
                                            if(isset($order_detail->product['code'])){
                                                $product_beli .= $order_detail->product['code']."-".$order_detail->product['name']." (Pembelian)";
                                            }else{
                                                $product_beli .= $order_detail['other']." (Pembelian)";
                                            }
                                            $tempTot = $val->order->orderDetail->where('type', "pembelian")->count();
                                            if($tempTot > 1 && $curnt_beli < $tempTot){
                                                $curnt_beli++;
                                                $product_beli .= "<br>";
                                            }
                                        }elseif($order_detail['type'] == "prize"){
                                            if(isset($order_detail->product['code'])){
                                                $product_hadiah .= $order_detail->product['code']."-".$order_detail->product['name']." (Hadiah)";
                                            }
                                            else{
                                                $product_hadiah .= $order_detail['other']." (Hadiah)";
                                            }
                                            $tempTot = $val->order->orderDetail->where('type', "prize")->count();
                                            if($tempTot > 1 && $curnt_hadiah < $tempTot){
                                                $curnt_hadiah++;
                                                $product_hadiah .= "<br>";
                                            }
                                        }elseif($order_detail['type'] == "upgrade"){
                                            if(isset($order_detail->product['code'])){
                                                $product_upgrade .= $order_detail->product['code']."-".$order_detail->product['name']." (Upgrade)";
                                            }
                                            else{
                                                $product_upgrade .= $order_detail['other']." (Upgrade)";
                                            }
                                            $tempTot = $val->order->orderDetail->where('type', "upgrade")->count();
                                            if($tempTot > 1 && $curnt_upgrade < $tempTot){
                                                $curnt_upgrade++;
                                                $product_upgrade .= "<br>";
                                            }
                                        }
                                    }
                                @endphp
                                <td style="font-size: 12px">
                                    {!! $product_beli !!}<br>{!! $product_hadiah !!}<br>{!! $product_upgrade !!}
                                </td>
                                <td>
                                    <a href="{{ route('detail_order') }}?code={{ $val->order->code }}" target="_blank">
                                        <i class="mdi mdi-eye text-info" style="font-size: 24px; color: rgb(99, 110, 114);"></i>
                                    </a>
                                </td>
                            </tr>
                       @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        <!-- Modal Delete Payment -->
        <div class="modal fade"
            id="deleteKomisiConfirm"
            tabindex="-1"
            role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 style="text-align: center;">
                            Are You Sure to Delete this Commision?
                        </h5>
                    </div>
                    <div class="modal-footer">
                        <form id="frmDelete" method="post" action="">
                            {{ csrf_field() }}
                            <button type="submit"
                                class="btn btn-gradient-danger mr-2">
                                Yes
                            </button>
                        </form>
                        <button class="btn btn-light" data-dismiss="modal">
                            No
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Delete -->
        <!-- Error modal -->
        <div class="modal fade"
            id="error-modal"
            tabindex="-1"
            role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="error-modal-desc">
                        @if(session('error'))
                        <div class="modal-body">
                            <h5 class="modal-title text-center">Error</h5>
                            <hr>
                            <p class="text-center">{{ session('error') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal View -->
    </section>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
@endsection
