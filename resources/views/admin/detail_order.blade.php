<?php
    $menu_item_page = "order";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    #intro {
        padding-top: 2em;
    }
    button{
        background: #1bb1dc;
        border: 0;
        border-radius: 3px;
        padding: 8px 30px;
        color: #fff;
        transition: 0.3s;
    }
    table{
        margin: 1em;
        font-size: 14px;
    }
    table thead{
        background-color: #8080801a;
        text-align: center;
    }
    table td{
        border: 0.5px #8080801a solid;
        padding: 0.5em;
    }
    .right{
        text-align: right;
    }
    .pInTable{
        margin-bottom: 6pt !important;
        font-size: 10pt;
    }
</style>
@endsection

@section('content')
@if( $order['code'] != null)
    <section id="intro" class="clearfix">
        <div class="container">
            <div class="row justify-content-center">
                <h2>ORDER SUCCESS</h2>
            </div>
            <div class="row justify-content-center">
                <table class="col-md-12">
                    <thead>
                        <td>Order Code</td>
                        <td>Order Date</td>
                    </thead>
                    <tr>
                        <td>{{ $order['code'] }}</td>
                        <td class="right">{{ date("d/m/Y H:i:s", strtotime($order['created_at'])) }}</td>
                    </tr>
                </table>
                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Customer Data</td>
                    </thead>
                    <tr>
                        <td>Member Code : </td>
                        <td>{{ $order['no_member'] }}</td>
                    </tr>
                    <tr>
                        <td>Name : </td>
                        <td>{{ $order['name'] }}</td>
                    </tr>
                    <tr>
                        <td>Phone Number : </td>
                        <td>{{ $order['phone'] }}</td>
                    </tr>
                    <tr>
                        <td>City : </td>
                        <td>{{ $order['city'] }}</td>
                    </tr>
                    <tr>
                        <td>Address : </td>
                        <td>{{ $order['address'] }}</td>
                    </tr>
                </table>
                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Detail Order</td>
                    </thead>
                    <thead style="background-color: #80808012 !important">
                        <td>Product Name</td>
                        <td>Quantity</td>
                    </thead>

                    @foreach(json_decode($order['product']) as $promo)
                        <tr>
                            <td>{{ App\DeliveryOrder::$Promo[$promo->id]['code'] }} - {{ App\DeliveryOrder::$Promo[$promo->id]['name'] }} ( {{ App\DeliveryOrder::$Promo[$promo->id]['harga'] }} )</td>
                            <td>{{ $promo->qty }}</td>
                        </tr>
                    @endforeach
                    @if($order['old_product'] != null)
                        <thead style="background-color: #80808012 !important">
                            <td colspan="2">Old Product</td>
                        </thead>
                        <tr>
                            <td colspan="2">{{$order['old_product']}}</td>
                        </tr>
                    @endif
                    @if($order['prize'] != null)
                        <thead style="background-color: #80808012 !important">
                            <td colspan="2">Prize Product</td>
                        </thead>
                        <tr>
                            <td colspan="2">{{$order['prize']}}</td>
                        </tr>
                    @endif

                </table>

                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Payment Detail</td>
                    </thead>
                    <tr>
                        <td>Total Payment : </td>
                        <td>Rp. {{ number_format($order['total_payment']) }}</td>
                    </tr>
                    <tr>
                        <td>Down Payment : </td>
                        <td>Rp. {{ number_format($order['down_payment']) }} (PAID OFF)</td>
                    </tr>
                    <tr>
                        <td>Remaining Payment : </td>
                        <td>Rp. {{ number_format($order['remaining_payment']) }}</td>
                    </tr>
                    <tr>
                        <td>Bank : </td>
                        <td>
                            @foreach(json_decode($order['bank']) as $key=>$bank)
                                {{ $bank->id }} ({{ $bank->cicilan }}X)
                                @if(sizeof(json_decode($order['bank'], true)) > $key+1) +  @endif
                            @endforeach
                        </td>
                    </tr>
                </table>

                <table class="col-md-12 d-none">
                    <thead>
                        <td colspan="2">Terms and Condition</td>
                    </thead>
                    <tr>
                        <td>
                            <p class="pInTable">1. I have read this order letter and agree to buy and accept the items listed above and am willing to pay off the remaining payment at the time of receipt of the goods.<br>(Especially out of town, the goods are sent after the bank's repayment)</p>
                            <p class="pInTable">2. I understand these items are not sold by trial.</p>
                            <p class="pInTable">3. Order / delivery letters also act as valid receipts.</p>
                            <p class="pInTable">4. Other than the above prices, there is no other agreement outside this order letter.</p><p class = "pInTable"> 5. Advances that have been paid cannot be withdrawn.</p>
                            <p class="pInTable">6. Items that have been purchased cannot be exchanged.</p>
                            <p class="pInTable">7. Goods ordered for three months are not taken means canceled.</p>
                            <p class="pInTable">8. Shipping costs apply to customers.</p>
                            <p class="pInTable">9. Shipping costs apply to members for purchases under 500,000.</p>
                        </td>
                    </tr>
                </table>

                <table class="col-md-12">
                    <thead>
                        <td>Sales Branch</td>
                        <td>Sales Code</td>
                    </thead>
                    <tr>
                        <td style="width:50%; text-align: center">{{ $order->branch['code'] }} - {{ $order->branch['name'] }}</td>
                        <td style="width:50%; text-align: center">{{ $order->cso['code'] }}</td>
                    </tr>
                </table>

                @if($order['description'] != null)
                    <table class="col-md-12">
                        <thead>
                            <td>Description</td>
                        </thead>
                        <tr>
                            <td>{{ $order['description'] }}</td>
                        </tr>
                    </table>
                @endif

                <a href="whatsapp://send?text={{ Route('order_success') }}?code={{ $order['code'] }}" data-action="share/whatsapp/share">Share to Whatsapp</a>
            </div>
        </div>
    </section>
    <section id="intro" class="clearfix">
        <h2>ORDER HISTORY LOG </h2>
        <table class="col-md-12">
            <thead>
                <td>No.</td>
                <td>Action</td>
                <td>User</td>
                <td>Time</td>
            </thead>
            @if($historyUpdateOrder != null)
            @foreach($historyUpdateOrder as $key => $historyUpdateOrder)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$historyUpdateOrder->method}}</td>
                <td>{{$historyUpdateOrder->name}}</td>
                <td>{{ date("d/m/Y H:i:s", strtotime($historyUpdateOrder->created_at)) }}</td>
            </tr>
            @endforeach
            @endif
        </table>
    </section>
@else
    <section id="intro" class="clearfix">
        <div class="container">
            <div class="row justify-content-center">
                <h2>CANNOT FIND ORDER</h2>
            </div>
        </div>
    </section>
@endif
@endsection