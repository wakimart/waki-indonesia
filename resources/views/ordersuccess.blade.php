@extends('layouts.template')

@section('content')
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

    .pInTable {
        margin-bottom: 6pt !important;
        font-size: 10pt;
    }
</style>

@if ( $deliveryOrder['code'] != null)
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
                        <td class="text-right">
                            {{ date("d/m/Y H:i:s", strtotime($deliveryOrder['created_at'])) }}
                        </td>
                    </tr>
                </table>
                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Data Pemesan</td>
                    </thead>
                    <tr>
                        <td>Member Code: </td>
                        <td>{{ $deliveryOrder['no_member'] }}</td>
                    </tr>
                    <tr>
                        <td>Name: </td>
                        <td>{{ $deliveryOrder['name'] }}</td>
                    </tr>
                    <tr>
                        <td>Phone Number: </td>
                        <td>{{ $deliveryOrder['phone'] }}</td>
                    </tr>
                    <tr>
                        <td>Address: </td>
                        <td>{{ $deliveryOrder['address'] }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            {{ $deliveryOrder['district'][0]['province'] }}, {{ $deliveryOrder['district'][0]['kota_kab'] }}, {{ $deliveryOrder['district'][0]['subdistrict_name'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>Purchasing Branch: </td>
                        <td>{{  $deliveryOrder->branch['code'] }} - {{  $deliveryOrder->branch['name'] }}</td>
                    </tr>
                    <tr>
                        <td>CSO Code: </td>
                        <td>{{ $deliveryOrder->cso['code'] }} - {{ $deliveryOrder->cso['name'] }}</td>
                    </tr>
                </table>
                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Order Detail</td>
                    </thead>
                    <thead style="background-color: #80808012 !important">
                        <td>Product Name</td>
                        <td>Quantity</td>
                    </thead>

                    @foreach (json_decode($deliveryOrder['arr_product']) as $promo)
                        <tr>
                            @if (is_numeric($promo->id))
                                <td>
                                    {{ App\DeliveryOrder::$Promo[$promo->id]['code'] }} - {{ App\DeliveryOrder::$Promo[$promo->id]['name'] }} ( {{ App\DeliveryOrder::$Promo[$promo->id]['harga'] }} )
                                </td>
                            @else
                                <td>{{ $promo->id }}</td>
                            @endif
                            <td>{{ $promo->qty }}</td>
                        </tr>
                    @endforeach
                </table>

                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Terms and Conditions</td>
                    </thead>
                    <tr>
                        <td>
                            <p class="pInTable">1. This registration form is only valid for 1 month after this form is published.</p>
                            <p class="pInTable">2. Other than the above prices, there are no agreements outside this order letter.</p>
                            <p class="pInTable">3. After cancellation, the registration down payment can be withdrawn within 7 working days.</p>
                            <p class="pInTable">4. WAKi has the right to change the terms and conditions without giving notice.</p>
                        </td>
                    </tr>
                </table>

                <a href="whatsapp://send?text={{ route('successorder') }}?code={{ $deliveryOrder['code'] }}"
                    data-action="share/whatsapp/share">
                    Share to Whatsapp
                </a>
            </div>
        </div>
    </section>
@else
    <section id="intro" class="clearfix">
        <div class="container">
            <div class="row justify-content-center">
                <h2>ORDER NOT FOUND</h2>
            </div>
        </div>
    </section>
@endif
@endsection
