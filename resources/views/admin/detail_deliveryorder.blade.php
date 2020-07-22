<?php
    $menu_item_page = "deliveryorder";
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


@if( $deliveryOrder['code'] != null)
    <section id="intro" class="clearfix">
        <div class="container">
            <div class="row justify-content-center">
                <h2>REGISTRATION SUCCESS</h2>
            </div>
            <div class="row justify-content-center">
                <table class="col-md-12">
                    <thead>
                        <td>Order Code</td>
                        <td>Order Date</td>
                    </thead>
                    <tr>
                        <td>{{ $deliveryOrder['code'] }}</td>
                        <td class="right">{{ date("d/m/Y H:i:s", strtotime($deliveryOrder['created_at'])) }}</td>
                    </tr>
                </table>
                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Customer Data</td>
                    </thead>
                    <tr>
                        <td>Member Code : </td>
                        <td>{{ $deliveryOrder['no_member'] }}</td>
                    </tr>
                    <tr>
                        <td>Name : </td>
                        <td>{{ $deliveryOrder['name'] }}</td>
                    </tr>
                    <tr>
                        <td>Phone Number : </td>
                        <td>{{ $deliveryOrder['phone'] }}</td>
                    </tr>
                    <tr>
                        <td>Address : </td>
                        <td>{{ $deliveryOrder['address'] }}</td>
                    </tr>
                    <tr>
                        <td>Registration Branch : </td>
                        <td>{{  $deliveryOrder->branch['code'] }} - {{  $deliveryOrder->branch['name'] }}</td>
                    </tr>
                    <tr>
                        <td>CSO Code : </td>
                        <td>{{ $deliveryOrder->cso['code'] }}</td>
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

                    @foreach(json_decode($deliveryOrder['arr_product']) as $promo)
                        <tr>
                            <td>{{ App\DeliveryOrder::$Promo[$promo->id]['code'] }} - {{ App\DeliveryOrder::$Promo[$promo->id]['name'] }} ( {{ App\DeliveryOrder::$Promo[$promo->id]['harga'] }} )</td>
                            <td>{{ $promo->qty }}</td>
                        </tr>
                    @endforeach
                </table>

                <table class="col-md-12 d-none">
                    <thead>
                        <td colspan="2">Terms and Conditions</td>
                    </thead>
                    <tr>
                        <td>
                            <p class="pInTable">1. This registration form is only valid for 1 month after this form is published.</p>
                            <!--<p class="pInTable">2. I have agreed to pay 10% of the value of the package as a registration fee and receive the items listed above and am willing to pay the remaining payment at the time of receipt of goods. (For out of city shipments, items will be shipped after payment)</p>-->
                            <p class="pInTable">2. Other than the above prices, there are no agreements outside this order letter.</p>
                            <p class="pInTable">3. After cancellation, the registration down payment can be withdrawn within 7 working days.</p>
                            <p class="pInTable">4. WAKi has the right to change the terms and conditions without giving notice.</p>
                        </td>
                    </tr>
                </table>

                <a href="whatsapp://send?text={{ Route('successorder') }}?code={{ $deliveryOrder['code'] }}" data-action="share/whatsapp/share">Share to Whatsapp</a>
            </div>
        </div>
    </section>
    <section id="intro" class="clearfix">
        <h2>REGISTRATION HISTORY LOG</h2>
        <table class="col-md-12">
            <thead>
                <td>No.</td>
                <td>Action</td>
                <td>User</td>
                <td>Change</td>
                <td>Time</td>
            </thead>
            @if($historyUpdateDeliveryOrder != null)
            @foreach($historyUpdateDeliveryOrder as $key => $historyUpdateDeliveryOrder)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$historyUpdateDeliveryOrder->method}}</td>
                <td>{{$historyUpdateDeliveryOrder->name}}</td>
                <td>{{$historyUpdateDeliveryOrder->meta['dataChange']}}</td>
                <td>{{ date("d/m/Y H:i:s", strtotime($historyUpdateDeliveryOrder->created_at)) }}</td>
            </tr>
            @endforeach
            @endif
        </table>
    </section>
@else
    <section id="intro" class="clearfix">
        <div class="container">
            <div class="row justify-content-center">
                <h2>CANNOT FIND DELIVERY ORDER</h2>
            </div>
        </div>
    </section>
@endif
@endsection