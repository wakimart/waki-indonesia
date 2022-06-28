<?php
    $menu_item_page = "order";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
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
    input, select, textarea{
        border-radius: 0 !important;
        box-shadow: none !important;
        border: 1px solid #dce1ec !important;
        font-size: 14px !important;
        width: 100% !important;
    }
    .select2-results__options{
        max-height: 15em;
        overflow-y: auto;
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
                        <td class="right">{{ date("d/m/Y", strtotime($order['orderDate'])) }}</td>
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
                        <td>Address : </td>
                        <td>{{ $order['address'] }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>{{ $order['district']['province'] }}, {{ $order['district']['kota_kab'] }}, {{ $order['district']['subdistrict_name'] }}</td>
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

                    @foreach(json_decode($order['product'], true) as $ProductPromo)
                        <tr>
                            @if(is_numeric($ProductPromo['id']) && $ProductPromo['id'] < 8)
                                <td>{{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['code'] }} - {{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['name'] }} ( {{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['harga'] }} )</td>
                            @else
                                <td>{{ $ProductPromo['id'] }}</td>
                            @endif

                            <td>{{ $ProductPromo['qty'] }}</td>
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
                @if($order['customer_type'] != null)
                    <table class="col-md-12">
                        <thead>
                            <td>Description</td>
                        </thead>
                        <tr>
                            <td>{{ $order['customer_type'] }}</td>
                        </tr>
                    </table>
                @endif
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
                @if($order['image'] != null)
                    <table class="col-md-12">
                        <thead>
                            <td>Payment Proof</td>
                        </thead>
                        <tr>
                            <td>
                                @foreach($order['image'] as $key => $payment)
                                    <a href="{{ asset('sources/order/' . $payment) }}"
                                        target="_blank">
                                        <i class="mdi mdi-numeric-{{ $key+1 }}-box" style="font-size: 24px; color: blue;"></i>
                                    </a>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                @endif

                <table class="col-md-12">
                    <thead>
                        <td>Status Order</td>
                    </thead>
                    <tr>
                        <td class="text-center">
                            @if ($order['status'] == \App\Order::$status['1'])
                                <span class="badge badge-secondary">New</span>
                            @elseif ($order['status'] == \App\Order::$status['2'])
                                <span class="badge badge-primary">Process</span>
                            @elseif ($order['status'] == \App\Order::$status['3'])
                                <span class="badge badge-warning">Delivery</span>
                            @elseif ($order['status'] == \App\Order::$status['4'])
                                <span class="badge badge-success">Success</span>
                            @elseif ($order['status'] == \App\Order::$status['5'])
                                <span class="badge badge-danger">Reject</span>
                            @endif
                        </td>
                    </tr>
                    @if (count($csoDeliveryOrders) > 0)
                    <tr>
                        <td>Cso Delivery Order : </td>
                    </tr>
                    @foreach ($csoDeliveryOrders as $csoDeliveryOrder)
                        <tr>
                            <td>{{ $csoDeliveryOrder['code'] }} - {{ $csoDeliveryOrder['name'] }}</td>
                        </tr>
                    @endforeach
                    @endif
                    @if (Gate::check('change-status_order'))
                        <tr>
                            <td>
                                <div class="form-group row justify-content-center">
                                    @if ($order['status'] == \App\Order::$status['1'] && Gate::check('change-status_order_process'))
                                    <button type="button" data-toggle="modal" data-target="#modal-change-status" status-order="{{\App\Order::$status['2']}}"
                                        class="btn btn-gradient-success mr-2 btn-lg btn-change-status-order">
                                        Process Order
                                    </button>
                                    @elseif ($order['status'] == \App\Order::$status['2'] && Gate::check('change-status_order_delivery'))
                                    <button type="button" data-toggle="modal" data-target="#modal-change-status" status-order="{{\App\Order::$status['3']}}"
                                        class="btn btn-gradient-warning mr-2 btn-lg btn-change-status-order">
                                        Delivery Order
                                    </button>
                                    @elseif ($order['status'] == \App\Order::$status['3'] && Gate::check('change-status_order_success'))
                                    <button type="button" data-toggle="modal" data-target="#modal-change-status" status-order="{{\App\Order::$status['4']}}"
                                        class="btn btn-gradient-primary mr-2 btn-lg btn-change-status-order">
                                        Success Order
                                    </button>
                                    @endif
                                    @if (($order['status'] == \App\Order::$status['1'] || $order['status'] == \App\Order::$status['2']) && Gate::check('change-status_order_reject'))
                                    <button type="button" data-toggle="modal" data-target="#modal-change-status" status-order="{{\App\Order::$status['5']}}"
                                        class="btn btn-gradient-danger mr-2 btn-lg btn-change-status-order">
                                        Reject Order
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endif
                </table>
                <a href="whatsapp://send?text={{ Route('order_success') }}?code={{ $order['code'] }}" data-action="share/whatsapp/share"
                class="btn btn-gradient-primary mr-2">Share to Whatsapp</a>
            </div>


            <div class="row justify-content-center" style="margin-top: 2em;">
                <h2>ORDER HISTORY LOG</h2>
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
                  @if($historyUpdateOrder != null)
                  @foreach($historyUpdateOrder as $key => $historyUpdateOrder)
                  @php

                  @endphp
                  <tr>
                      <td>{{$key+1}}</td>
                      <td>{{$historyUpdateOrder->method}}</td>
                      <td>{{$historyUpdateOrder->name}}</td>
                      <?php $dataChange = json_decode($historyUpdateOrder->meta, true);?>
                      <td>
                      @foreach ($dataChange['dataChange'] as $key=>$value)
                          <b>{{$key}}</b>: {{$value}}<br/>
                      @endforeach
                      </td>
                      <td>{{ date("d/m/Y H:i:s", strtotime($historyUpdateOrder->created_at)) }}</td>
                  </tr>
                  @endforeach
                  @endif
              </table>
            </div>

            <!-- Modal Change Status Order -->
            <div class="modal fade"
                id="modal-change-status"
                tabindex="-1"
                role="dialog"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form id="actionAdd"
                            class="forms-sample"
                            method="POST"
                            action="{{ route('update_status_order') }}">
                            @csrf
                            <div class="modal-header">
                                <button type="button"
                                    class="close"
                                    data-dismiss="modal"
                                    aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <h5 id="modal-change-status-question" class="modal-title text-center">Process This Order?</h5>
                                <hr>
                                {{-- Pilih CSO Untuk Delivery --}}
                                @if ($order['status'] == \App\Order::$status['2'])
                                <div id="delivery-cso">
                                    <div id="form-cso" class="row">
                                        <div class="form-group mb-3 col-10">
                                            <label>Select CSO Delivery</label>
                                            <select id="delivery-cso-id" class="form-control delivery-cso-id" name="delivery_cso_id[]" style="width: 100%" required>
                                                <option value="">Choose CSO Delivery</option>
                                                @foreach ($csos as $cso)
                                                <option value="{{ $cso['id'] }}">{{ $cso['code'] }} - {{ $cso['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="text-center"
                                            style="display: inline-block; float: right;">
                                            <button id="tambah_cso"
                                                title="Add Cso"
                                                style="padding: 0.4em 0.7em;">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div id="tambahan_cso"></div>
                                </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <input id="order-id" name="orderId" hidden="hidden" value="{{ $order['id'] }}">
                                <input id="status-order" name="status_order" hidden="hidden">
                                <button id="btn-edit-status-order"
                                    type="submit"
                                    class="btn btn-gradient-primary mr-2">
                                    Yes
                                </button>
                                <button class="btn btn-light"
                                    data-dismiss="modal"
                                    aria-label="Close">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Modal View -->

            @else
            <div class="row justify-content-center">
                <h2>CANNOT FIND ORDER</h2>
            </div>
            @endif
        </div>

    </section>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
<script>
    $(document).ready(function() {
        $(".delivery-cso-id").select2({
            theme: "bootstrap4",
            placeholder: "Choose CSO Delivery",
            dropdownParent: $('#modal-change-status .modal-body')
        });
        var statusOrder = "{{ $order['status'] }}";
        $(".btn-change-status-order").click(function(){
            statusOrder = $(this).attr('status-order');
            $('#delivery-cso').hide();
            $('.delivery-cso-id').attr('disabled', true);
            $('#status-order').val(statusOrder);
            if (statusOrder == "{{\App\Order::$status['2']}}") {
                $("#modal-change-status-question").html('Process This Order?');
            } else if (statusOrder == "{{\App\Order::$status['3']}}") {
                $('#delivery-cso').show();
                $('.delivery-cso-id').attr('disabled', false);
                $("#modal-change-status-question").html('Delivery This Order? \n Choose CSO');
            } else if (statusOrder == "{{\App\Order::$status['4']}}") {
                $("#modal-change-status-question").html('Success This Order?');
            } else if (statusOrder == "{{\App\Order::$status['5']}}") {
                $("#modal-change-status-question").html('Reject This Order?');
            }
        });

        $('#tambah_cso').click(function(e){
            e.preventDefault();
            strIsi = `
                <div class="row form-cso">
                    <div class="form-group mb-3 col-10">
                        <label>Select CSO Delivery</label>
                        <select class="form-control delivery-cso-id" name="delivery_cso_id[]" style="width: 100%" required>
                            <option value="">Choose CSO Delivery</option>
                            @foreach ($csos as $cso)
                            <option value="{{ $cso['id'] }}">{{ $cso['code'] }} - {{ $cso['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-center"
                        style="display: inline-block; float: right;">
                        <button class="hapus_cso"
                            title="Hapus CSO"
                            style="padding: 0.4em 0.7em; background-color: red;">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>`;
            $('#tambahan_cso').append(strIsi);
            $(".delivery-cso-id").select2({
                theme: "bootstrap4",
                placeholder: "Choose CSO Delivery",
                dropdownParent: $('#modal-change-status .modal-body')
            });
        });

        $(document).on("click", ".hapus_cso", function (e) {
            e.preventDefault();
            $(this).parents(".form-cso")[0].remove();
        });
    });

</script>
@endsection
