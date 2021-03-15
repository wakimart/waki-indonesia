<?php
$menu_item_page = "submission";
$menu_item_second = "detail_submission_form";
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

</style>
@endsection

@section('content')
@if ($deliveryOrder['code'] != null)
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
                            {{ date("d/m/Y H:i:s", strtotime($deliveryOrder['created_at'])) }}
                        </td>
                    </tr>
                </table>
                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Customer Data</td>
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
                            <?php
                            echo $deliveryOrder['district'][0]['province'];
                            echo ", ";
                            echo $deliveryOrder['district'][0]['kota_kab'];
                            echo ", ";
                            echo $deliveryOrder['district'][0]['subdistrict_name'];
                            ?>
                        </td>
                    </tr>
                </table>

                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Detail Order</td>
                    </thead>
                    <thead style="background-color: #80808012 !important">
                        <td>Promo Name</td>
                        <td>Quantity</td>
                    </thead>

                    @foreach (json_decode($deliveryOrder['arr_product'], true) as $promo)
                        <tr>
                            @if (is_numeric($promo['id']) && $promo['id'] < 8)
                                <td>
                                    <?php
                                    echo App\DeliveryOrder::$Promo[$promo['id']]['code'];
                                    echo " - ";
                                    echo App\DeliveryOrder::$Promo[$promo['id']]['name'];
                                    echo " (";
                                    echo App\DeliveryOrder::$Promo[$promo['id']]['harga'];
                                    echo ")";
                                    ?>
                                </td>
                            @else
                                <td>{{ $promo['id'] }}</td>
                            @endif

                            <td>{{ $promo['qty'] }}</td>
                        </tr>
                    @endforeach
                </table>

                <table class="col-md-12">
                    <thead>
                        <td>Sales Branch</td>
                        <td>Sales Code</td>
                    </thead>
                    <tr>
                        <td style="width:50%; text-align: center">
                            <?php
                            echo $deliveryOrder->branch['code'];
                            echo " - ";
                            echo $deliveryOrder->branch['name'];
                            ?>
                        </td>
                        <td style="width:50%; text-align: center">
                            {{ $deliveryOrder->cso['code'] }}
                        </td>
                    </tr>
                </table>

                <table class="col-md-12">
                    <thead>
                        <td colspan="4">Reference</td>
                    </thead>
                    <thead style="background-color: #80808012 !important">
                        <td>Name</td>
                        <td>Age</td>
                        <td>Phone</td>
                        <td>City</td>
                    </thead>
                    <?php foreach ($references as $key => $reference): ?>
                        <tr>
                            <td>
                                <?php echo $reference->name; ?>
                            </td>
                            <td class="center">
                                <?php echo $reference->age; ?>
                            </td>
                            <td class="center">
                                <?php echo $reference->phone; ?>
                            </td>
                            <td>
                                <?php echo $referencesCityAndProvince[$key]; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

        <div class="container">
            <div class="row justify-content-center" style="margin-top: 2em;">
                <h2>SUBMISSION HISTORY LOG</h2>
            </div>
            <div class="row justify-content-center">
                <table class="col-md-12">
                    <thead>
                        <td>No.</td>
                        <td>Action</td>
                        <td>User</td>
                        <td>Change</td>
                        <td>Time</td>
                    </thead>
                    @if ($historyUpdateDeliveryOrder != null)
                        @foreach ($historyUpdateDeliveryOrder as $key => $historyUpdateDeliveryOrder)
                            <tr>
                                <td class="center">{{ $key + 1 }}</td>
                                <td class="center">
                                    {{ $historyUpdateDeliveryOrder->method }}
                                </td>
                                <td class="center">
                                    {{ $historyUpdateDeliveryOrder->name }}
                                </td>
                                <?php
                                $dataChange = json_decode($historyUpdateDeliveryOrder->meta, true);
                                ?>
                                <td>
                                    @foreach ($dataChange['dataChange'] as $key=>$value)
                                        <b>{{ $key }}</b>: {{ $value}}
                                        <br>
                                    @endforeach
                                </td>
                                <td class="center">
                                    {{ date("d/m/Y H:i:s", strtotime($historyUpdateDeliveryOrder->created_at)) }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </section>
@else
    <div class="row justify-content-center">
        <h2>CANNOT FIND DELIVERY ORDER</h2>
    </div>
@endif
@endsection
