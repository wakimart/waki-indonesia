@extends('layouts.template')

@section('content')
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
    <section id="intro" class="clearfix">
        <div class="container">
            <div class="row justify-content-center">
                <h2>LIST REGISTRATION</h2>
            </div>
            <div class="row justify-content-left">
                <h5 style="margin-bottom: 0.5em;">Total : {{ sizeof($deliveryOrders) }} data</h5>
            </div>
            <div class="row justify-content-center">
                <table class="col-md-12">
                    <thead>
                        <td>Code Order</td>
                        <td>Tangal Order</td>
                        <td>Name</td>
                        <td colspan="2">Product</td>
                        <td>Branch</td>
                        <td>CSO</td>
                    </thead>
                    @foreach($deliveryOrders as $deliveryOrder)
                        @php 
                            $ProductPromos = json_decode($deliveryOrder['arr_product'], true);
                            $totalProduct = count($ProductPromos);
                        @endphp
                        <tr>
                            <td rowspan="{{ $totalProduct }}"><a href="{{ Route('successorder') }}?code={{ $deliveryOrder['code'] }}">{{ $deliveryOrder['code'] }}</a></td>
                            <td rowspan="{{ $totalProduct }}">{{ date("d/m/Y", strtotime($deliveryOrder['created_at'])) }}</td>
                            <td rowspan="{{ $totalProduct }}">{{ $deliveryOrder['name'] }}</td>

                            @foreach($ProductPromos as $ProductPromo)
                                <td>{{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['code'] }} - {{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['name'] }} ( {{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['harga'] }} )</td>
                                <td>{{ $ProductPromo['qty'] }}</td>
                                @php break; @endphp
                            @endforeach
                            <td rowspan="{{ $totalProduct }}">{{ $deliveryOrder->branch['code'] }} - {{ $deliveryOrder->branch['name'] }}</td>
                            <td rowspan="{{ $totalProduct }}">{{ $deliveryOrder->cso['code'] }} - {{ $deliveryOrder->cso['name'] }}</td>
                        </tr>
                        @php $first = true; @endphp
                        @foreach($ProductPromos as $ProductPromo)
                            @php
                                if($first){
                                    $first = false;
                                    continue;
                                }
                            @endphp
                            <tr>
                                <td>{{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['code'] }} - {{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['name'] }} ( {{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['harga'] }} )</td>
                                <td>{{ $ProductPromo['qty'] }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </table>
            </div>
        </div>
    </section>
@endsection
