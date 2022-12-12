<?php
    $menu_item_page = "order";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<style type="text/css">
    #intro {padding-top: 2em;}
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
    .content-wrapper{ background:transparent !important;}
    .right{text-align: right;}
    .pInTable{
        margin-bottom: 6pt !important;
        font-size: 10pt;
    }
    .select2-results__options{
        max-height: 15em;
        overflow-y: auto;
    }
    .imagePreview {
        width: 100%;
        height: 150px;
        background-position: center center;
        background-color: #fff;
        background-size: cover;
        background-repeat: no-repeat;
        display: inline-block;
    }
    .del {
        position: absolute;
        top: 0px;
        right: 10px;
        width: 30px;
        height: 30px;
        text-align: center;
        line-height: 30px;
        background-color: rgba(255, 255, 255, 0.6);
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="main-panel">

@if( $order['code'] != null)
    <section id="intro" class="clearfix">
        <div class="content-wrapper">
            <div class="row justify-content-center">
                <h2>ORDER SUCCESS</h2>
            </div>
            <div class="row justify-content-center">
                <table class="w-100">
                    <thead>
                        <td>Order Code</td>
                        <td>Order Date</td>
                    </thead>
                    <tr>
                        <td>{{ $order['code'] }}</td>
                        <td class="right">{{ date("d/m/Y", strtotime($order['orderDate'])) }}</td>
                    </tr>
                </table>
                <table class="w-100">
                    <thead>
                        <td>Sales Branch</td>
                        <td>Sales Code</td>
                    </thead>
                    <tr class="text-center">
                        <td>{{ $order->branch['code'] }} - {{ $order->branch['name'] }}</td>
                        <td>{{ $order->cso['code'] }} - {{ $order->cso['name'] }}</td>
                    </tr>
                </table>
                <table class="w-100">
                    <thead>
                        <td colspan="2">Customer Data</td>
                    </thead>
                    <tr>
                        <td>Type Customer</td>
                        <td>{{ $order['customer_type'] ?? '' }}</td>
                    </tr>
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
                <table class="w-100">
                    <thead>
                        <td colspan="4">Detail Order</td>
                    </thead>
                    <thead style="background-color: #80808012 !important">
                        <td>Code</td>
                        <td>Product Name</td>
                        <td>Quantity</td>
                        <td>Type</td>
                    </thead>
                    @foreach ($order->orderDetail as $orderDetail)
                        <tr>
                            <td>{{ $orderDetail->product['code'] ?? $orderDetail->promo['code'] ?? 'OTHER' }}</td>
                            <td>{{ $orderDetail->product['name'] ?? (($orderDetail->promo) ? implode(", ", $orderDetail->promo->productName()) : $orderDetail->other) }}</td>
                            <td>{{ $orderDetail->qty }}</td>
                            <td>{{ ucwords($orderDetail->type) }}</td>
                        </tr>
                    @endforeach
                </table>
                <div class="table-responsive">
                  <table class="w-100">
                      <thead>
                          <td colspan="7">Payment Detail</td>
                      </thead>
                      <thead style="background-color: #80808012 !important">
                          <td>Date</td>
                          <td>Bank</td>
                          <td>Total Payment</td>
                          <td>Image</td>
                          <td>Status</td>
                          @if (Gate::check('detail-order') || Gate::check('edit-order') || Gate::check('delete-order'))
                              <td colspan="2">Edit/Delete</td>
                          @endif
                      </thead>
                      @foreach ($order->orderPayment as $orderPayment)
                      <tr>
                          <td>{{ $orderPayment->payment_date }}</td>
                          <td>{{ $orderPayment->bank['name'] }} ({{ $orderPayment->cicilan }}x)</td>
                          <td>Rp. {{ number_format($orderPayment->total_payment) }}</td>
                          <td>
                              @foreach (json_decode($orderPayment->image, true) as $orderPaymentImage)
                              <a href="{{ asset("sources/order/$orderPaymentImage") }}"
                                  target="_blank">
                                  <i class="mdi mdi-numeric-{{ $loop->iteration }}-box" style="font-size: 24px; color: #2daaff;"></i>
                              </a>
                              @endforeach
                          </td>
                          <td class="text-center">
                              @if ($orderPayment['status'] == "unverified")
                                  <span class="badge badge-warning">Unverified</span>
                              @elseif ($orderPayment['status'] == "verified")
                                  <span class="badge badge-success">Verified</span>
                              @elseif ($orderPayment['status'] == "rejected")
                                  <span class="badge badge-danger">Rejected</span>
                              @endif
                          </td>
                          @can('edit-order')
                              @if ($order->status != 'new' || Auth::user()->inRole("head-admin"))
                              @if($orderPayment['status'] !== "verified" || Auth::user()->inRole("head-admin"))
                                  <td style="text-align: center;">
                                      <button value="{{ $orderPayment['id'] }}"
                                          data-toggle="modal"
                                          data-target="#editPaymentModal"
                                          class="btn btn-delete btn-edit_order_payment">
                                          <i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
                                      </button>
                                  </td>
                              @endif
                              @if($orderPayment['status'] !== "verified" || Auth::user()->inRole("head-admin"))
                                  <td style="text-align: center;">
                                      <button value="{{ route('delete_order_payment', ['id' => $orderPayment['id']])}}"
                                          data-toggle="modal"
                                          data-target="#deleteDoModal"
                                          class="btn btn-delete btn-delete_order_payment">
                                          <i class="mdi mdi-delete" style="font-size: 24px; color:#fe7c96;"></i>
                                      </button>
                                  </td>
                              @endif
                              @endif
                          @endcan
                      </tr>
                      @endforeach
                      <tr>
                          <td colspan="2" class="text-right" style="background-color: #80808012 !important">Total Payment</td>
                          <td>Rp. {{ number_format($order->down_payment) }}</td>
                          <td colspan="4" style="background-color: #f2f2f2;" rowspan="3"></td>
                      </tr>
                      <tr>
                          <td colspan="2" class="text-right" style="background-color: #80808012 !important">Total Price</td>
                          <td>Rp. {{ number_format($order['total_payment']) }}</td>
                      </tr>
                      <tr>
                          <td colspan="2" class="text-right" style="background-color: #80808012 !important">Remaining Payment</td>
                          <td>Rp. {{ number_format($order['remaining_payment']) }}</td>
                      </tr>
                  </table>
                </div>

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
                @if($order['description'] != null)
                    <table class="w-100">
                        <thead>
                            <td>Description</td>
                        </thead>
                        <tr>
                            <td>{{ $order['description'] }}</td>
                        </tr>
                    </table>
                @endif
                @if($order['image'] != null)
                    <table class="w-100">
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

                <table class="w-100">
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
                                        class="btn btn-gradient-success mr-2 btn-change-status-order">
                                        Process Order
                                    </button>
                                    @elseif ($order['status'] == \App\Order::$status['2'] && Gate::check('change-status_order_delivery'))
                                    <button type="button" data-toggle="modal" data-target="#modal-change-status" status-order="{{\App\Order::$status['3']}}"
                                        class="btn btn-gradient-warning mr-2 btn-change-status-order">
                                        Delivery Order
                                    </button>
                                    @elseif ($order['status'] == \App\Order::$status['3'] && Gate::check('change-status_order_success'))
                                    <button type="button" data-toggle="modal" data-target="#modal-change-status" status-order="{{\App\Order::$status['4']}}"
                                        class="btn btn-gradient-primary mr-2 btn-change-status-order">
                                        Success Order
                                    </button>
                                    @endif
                                    @if (($order['status'] == \App\Order::$status['1'] || $order['status'] == \App\Order::$status['2']) && Gate::check('change-status_order_reject'))
                                    <button type="button" data-toggle="modal" data-target="#modal-change-status" status-order="{{\App\Order::$status['5']}}"
                                        class="btn btn-gradient-danger mr-2 btn-change-status-order">
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
                @if (Gate::check('edit-order') && $order->remaining_payment > 0)
                <button type="button" data-toggle="modal" data-target="#addPaymentModal"
                    class="btn btnappoint btn-gradient-success mdi mdi-cash-multiple btn-homeservice-cash">
                    Add Payment
                </button>
                @endif
            </div>

            <div class="row justify-content-center" style="margin-top: 2em;">
                <h2>ORDER HISTORY LOG</h2>
            </div>
            <div class="row justify-content-center">
              <div class="table-responsive">
                <table class="w-100">
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
                            <b>{{$key}}</b>: {{ is_array($value) ? json_encode($value) : $value }}<br/>
                        @endforeach
                        </td>
                        <td>{{ date("d/m/Y H:i:s", strtotime($historyUpdateOrder->created_at)) }}</td>
                    </tr>
                    @endforeach
                    @endif
                </table>
              </div>
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
                                    type="button"
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

            @if (Gate::check('edit-order'))
            <!-- Modal Add Payment -->
            <div class="modal fade"
                id="addPaymentModal"
                tabindex="-1"
                role="dialog"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form id="frmAddPayment"
                            method="post"
                            action="{{ route('store_order_payment') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order['id'] }}">
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
                                    Add Payment
                                </h5>
                                <br>
                                <div class="form-group mb-1">
                                    <label for="">Payment Date</label>
                                    <input type="date"
                                        class="form-control"
                                        name="payment_date"
                                        value="{{ date('Y-m-d') }}"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="">Bank</label>
                                    <select class="form-control"
                                        name="bank_id"
                                        data-msg="Mohon Pilih Bank" required>
                                        <option selected disabled value="">
                                            Choose Bank
                                        </option>

                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">
                                                {{ $bank->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Cicilan</label>
                                    <select class="form-control bank_cicilan"
                                        name="cicilan"
                                        data-msg="Mohon Pilih Jumlah Cicilan" required>
                                        <option selected value="1">1X</option>
                                        @for ($i = 2; $i <= 12; $i += 2)
                                            <option class="other_valCicilan"
                                                value="{{ $i }}">
                                                {{ $i }}X
                                            </option>
                                        @endfor
                                        <option class="other_valCicilan"
                                            value="18">
                                            18X
                                        </option>
                                        <option class="other_valCicilan"
                                            value="24">
                                            24X
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Nominal Payment</label>
                                    <input type="text"
                                        class="form-control downpayment"
                                        name="total_payment"
                                        placeholder="Nominal Payment"
                                        required
                                        autocomplete="off"
                                        data-type="currency"
                                        data-msg="Mohon Isi Total Pembayaran" />
                                </div>
                                <div class="form-group">
                                    <label for="">Foto:</label>
                                    <label style="float: right">(Min: 1) (Max: 3)</label>
                                    <input type="file"
                                        class="form-control"
                                        id="imageAddPayment"
                                        name="images[]"
                                        accept="image/*"
                                        placeholder="Bukti Foto"
                                        multiple
                                        required
                                        data-msg="Mohon Sertakan Foto"
                                        style="text-transform: uppercase;" />
                                </div>
                            </div>
                            <div class="modal-footer footer-cash">
                                <button type="submit"
                                    id="submitFrmAddPayment"
                                    class="btn btn-gradient-success mr-2">
                                    Add
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Modal Add Payment -->
            <!-- Modal Edit Payment -->
            <div class="modal fade"
                id="editPaymentModal"
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
                            <form id="frmEditPayment"
                                method="post"
                                action="{{ route('update_order_payment') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" id="editPayment-order_id" name="order_id" value="{{ $order['id'] }}">
                                <input type="hidden" id="editPayment-order_payment_id" name="order_payment_id" value="">
                                <h5 style="text-align: center;">
                                    Edit Payment
                                </h5>
                                <br>
                                <div class="form-group mb-1">
                                    <label for="">Payment Date</label>
                                    <input type="date"
                                        id="editPayment-payment_date"
                                        class="form-control"
                                        name="payment_date"
                                        value=""
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="">Bank</label>
                                    <select class="form-control"
                                        id="editPayment-bank_id"
                                        name="bank_id"
                                        data-msg="Mohon Pilih Bank" required>
                                        <option selected disabled value="">
                                            Choose Bank
                                        </option>

                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">
                                                {{ $bank->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Cicilan</label>
                                    <select class="form-control bank_cicilan"
                                        id="editPayment-cicilan"
                                        name="cicilan"
                                        data-msg="Mohon Pilih Jumlah Cicilan" required>
                                        <option selected value="1">1X</option>
                                        @for ($i = 2; $i <= 12; $i += 2)
                                            <option class="other_valCicilan"
                                                value="{{ $i }}">
                                                {{ $i }}X
                                            </option>
                                        @endfor
                                        <option class="other_valCicilan"
                                            value="18">
                                            18X
                                        </option>
                                        <option class="other_valCicilan"
                                            value="24">
                                            24X
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Nominal Payment</label>
                                    <input type="text"
                                        id="editPayment-total_payment"
                                        class="form-control downpayment"
                                        name="total_payment"
                                        placeholder="Nominal Payment"
                                        required
                                        autocomplete="off"
                                        data-type="currency"
                                        data-msg="Mohon Isi Total Pembayaran" />
                                </div>
                                <div class="form-group">
                                    <label for="">Bukti Pembayaran</label>
                                    <label style="float: right">(Min: 1) (Max: 3)</label>
                                    <div class="clearfix"></div>
                                    @for ($i = 0; $i < 3; $i++)
                                        <div class="col-xs-12 col-sm-6 col-md-4 form-group imgUp"
                                            style="padding: 15px; float: left;">
                                            <label>Image {{ $i + 1 }}</label>
                                            <div class="imagePreview"
                                                style="background-image: url({{ asset('sources/dashboard/no-img-banner.jpg') }});">
                                            </div>
                                            <label class="file-upload-browse btn btn-gradient-primary"
                                                style="margin-top: 15px; padding: 10px">
                                                Upload
                                                <input name="images_{{ $i }}"
                                                    id="editPayment-productimg-{{ $i }}"
                                                    type="file"
                                                    accept=".jpg,.jpeg,.png"
                                                    class="uploadFile img"
                                                    value="Upload Photo"
                                                    style="width: 0px; height: 0px; overflow: hidden; border: none !important;" />
                                            </label>
                                            <i class="mdi mdi-window-close del"></i>
                                        </div>
                                    @endfor
                                </div>
                            </form>
                            <div class="clearfix"></div>
                            @if (Gate::check('change-status_payment') && $order->status != 'new')
                            <div id="divUpdateStatusPayment" class="text-center p-3" style="border: 1px solid black">
                                <h5 class="mb-3">Status Payment</h5>
                                <form id="frmUpdateStatusPayment"
                                    method="post"
                                    action="{{ route('update_status_order_payment') }}">
                                    @csrf
                                    <input type="hidden" id="updateStatusPayment-order_id" name="order_id" value="{{ $order['id'] }}">
                                    <input type="hidden" id="updateStatusPayment-order_payment_id" name="order_payment_id" value="">
                                    <input type="hidden" id="statusACC" name="status_acc">
                                    <div class="btn-action" style="text-align: center;">
                                        @if (Gate::check('change-status_payment_verified'))
                                        <button type="button" class="btn btn-gradient-primary" id="btn-update-status-payment-true">Verified</button>
                                        @endif
                                        @if (Gate::check('change-status_payment_rejected'))
                                        <button type="button" class="btn btn-gradient-danger" id="btn-update-status-payment-false">Rejected</button>
                                        @endif
                                    </div>
                                </form>
                            </div>
                            @endif
                        </div>
                        <div class="modal-footer footer-cash">
                            <button type="submit" form="frmEditPayment"
                                id="submitFrmEditPayment"
                                class="btn btn-gradient-success mr-2">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal Edit Payment -->
            <!-- Modal Delete Payment -->
            <div class="modal fade"
                id="deleteDoModal"
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
                                Are You Sure to Delete this Payment?
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
                        <div id="error-modal-desc"></div>
                    </div>
                </div>
            </div>
            <!-- End Modal View -->
            @endif

            @else
            <div class="row justify-content-center">
                <h2>CANNOT FIND ORDER</h2>
            </div>
            @endif
        </div>

    </section>

</div>
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
        $(document).on("input", 'input[data-type="currency"]', function() {
            $(this).val(numberWithCommas($(this).val()));
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

        function numberWithCommas(x) {
            var parts = x.toString().split(".");
            parts[0] = parts[0].replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
        }

        function numberNoCommas(x) {
            var parts = x.toString().split(".");
            parts[0] = parts[0].replace(/\D/g, "");
            return parts.join(".");
        }

        $("#submitFrmAddPayment").on("click", function(e) {
            // Change numberWithComma before submit
            $("#frmAddPayment").find('input[data-type="currency"]').each(function() {
                $(this).val(numberNoCommas($(this).val()));
            });
            $("#frmAddPayment").submit();
        });

        $("#imageAddPayment").on("change", function() {
            if ($("#imageAddPayment")[0].files.length > 3) {
                $("#imageAddPayment").val("")
                $('.custom-file-label').html("Choose image");
                alert("You can select only 3 images");
            }
        });

        // Edit Order Payment
        $(".btn-edit_order_payment").click(function() {
            $("#submitFrmEditPayment").hide();
            $("#divUpdateStatusPayment").hide();
            var order_payment_id = $(this).val();
            $.ajax({
                method: "post",
                url: "{{ route('edit_order_payment') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    order_id: "{{ $order['id'] }}",
                    order_payment_id: order_payment_id
                },
                success: function(data) {
                    if (data.status == 'success') {
                        const result = data.result;
                        $("#editPayment-order_payment_id").val(order_payment_id);
                        $("#editPayment-payment_date").val(result.payment_date);
                        $("#editPayment-bank_id").val(result.bank_id);
                        $("#editPayment-cicilan").val(result.cicilan);
                        $("#editPayment-total_payment").val(numberWithCommas(result.total_payment));

                        const mainUrlImage = "{{ asset('sources/order') }}";
                        $.each(JSON.parse(result.image), function(index, image) {
                            $("#editPayment-productimg-" + index).closest(".imgUp").find(".imagePreview")
                                .css('background-image', 'url(' + mainUrlImage + "/" +image + ')');
                        });

                        @if (Gate::check('change-status_payment'))
                        if (result.status != "verified") {
                            $("#updateStatusPayment-order_payment_id").val(order_payment_id);
                            $("#divUpdateStatusPayment").show();
                        }
                        @endif

                        $("#submitFrmEditPayment").show();
                    } else {
                        alert(data.result);
                    }
                },
                error: function(data) {
                    alert("Error!");
                }
            });
        });

        $("#submitFrmEditPayment").on("click", function(e) {
            // Change numberWithComma before submit
            $("#frmEditPayment").find('input[data-type="currency"]').each(function() {
                $(this).val(numberNoCommas($(this).val()));
            });
            $("#frmEditPayment").submit();
        });

        $(".btn-delete_order_payment").click(function(e) {
            $("#frmDelete").attr("action",  $(this).val());
        });

        $(document).on("click", "i.del", function () {
            $(this).closest(".imgUp").find('.imagePreview').css("background-image", "");
            $(this).closest(".imgUp").find('input[type=text]').removeAttr("required");
            $(this).closest(".imgUp").find('.btn').find('.img').val("");
            $(this).closest(".imgUp").find('.form-control').val("");
            const inputImage = $(this).closest(".imgUp").find(".img");
            const inputImageId = inputImage.attr("id").split("-")[2];
            if (inputImageId == "0") {
                inputImage.attr("required", "");
            }
            $('<input>').attr({
                type: 'hidden',
                name: 'dltimg-' + inputImageId,
                value: inputImageId
            }).appendTo('#frmEditPayment');
        });

        $(function () {
            $(document).on("change", ".uploadFile", function () {
                const uploadFile = $(this);
                const files = this.files ? this.files : [];

                // no file selected, or no FileReader support
                if (!files.length || !window.FileReader) {
                    return;
                }

                // only image file
                if (/^image/.test(files[0].type)) {
                    // instance of the FileReader
                    const reader = new FileReader();
                    // read the local file
                    reader.readAsDataURL(files[0]);

                    // set image data as background of div
                    reader.onloadend = function () {
                        uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url(" + this.result + ")");
                    };
                }

            });
        });

        var networkValue
        function testNetwork(networkValue, response){
            // response();
            $.ajax({
                method: "post",
                url: "http://{{ env('OFFLINE_URL') }}/api/end-point-for-check-status-network",
                dataType: 'json',
                contentType: 'application/json',
                processData: false,
                headers: {
                    "api-key": "{{ env('API_KEY') }}",
                },
                success: response,
                error: function(xhr, status, error) {
                    var modal = `
                        <div class="modal-body">
                            <h5 class="modal-title text-center">${xhr.responseJSON.status}</h5>
                            <hr>
                            <p class="text-center">${xhr.responseJSON.message}</p>
                        </div>
                    `
                    $('#modal-change-status').modal("hide")
                    $('.modal-backdrop').remove();
                    $('#error-modal-desc').html(modal)
                    $('#error-modal').modal("show")
                }
            });
        };

        $('#btn-edit-status-order').on('click', function() {
            testNetwork(networkValue, function(val){
                console.log("masuk");
                var order_details = []
                @foreach($order->orderDetail as $detail)
                    var row = {
                        'product_id':'{{$detail->product_id}}',
                        'promo_id':'{{$detail->promo_id}}',
                        'qty':'{{$detail->qty}}',
                        'type':'{{$detail->type}}',
                        'other':'{{$detail->other}}'
                    }
                    order_details.push(row)
                @endforeach
                var formSerialize = $('#actionAdd').serializeArray()
                var deliveryCSOID = []
                for(var i = 0; i < formSerialize.length; i++){
                    if(formSerialize[i].name == 'delivery_cso_id[]'){
                        deliveryCSOID.push(formSerialize[i].value)
                    }
                }
                var order = {
                    'code':'{{$order->code}}',
                    'no_member':'{{$order->no_member}}',
                    'name':'{{$order->name}}',
                    'address':'{{$order->address}}',
                    'cash_upgrade':'{{$order->cash_upgrade}}',
                    'total_payment':'{{$order->total_payment}}',
                    'down_payment':'{{$order->down_payment}}',
                    'remaining_payment':'{{$order->remaining_payment}}',
                    'cso_id':'{{$order->cso_id}}',
                    'branch_id':'{{$order->branch_id}}',
                    '30_cso_id':"{{$order['30_cso_id']}}",
                    '70_cso_id':"{{$order['70_cso_id']}}",
                    'customer_type':'{{$order->customer_type}}',
                    'description':'{{$order->description}}',
                    'phone':'{{$order->phone}}',
                    'orderDate':'{{$order->orderDate}}',
                    'know_from':'{{$order->know_from}}',
                    'province':'{{$order->province}}',
                    'city':'{{$order->city}}',
                    'distric':'{{$order->distric}}',
                    'status':$('#status-order').val(),
                    'delivery_cso_id':deliveryCSOID,
                    'order_details':order_details,
                    'user_id':'{{Auth::user()->code}}'
                }
                // $('#actionAdd').submit();

                $.ajax({
                    method: "post",
                    url: "http://{{ env('OFFLINE_URL') }}/api/replicate-order-data",
                    data: order,
                    success: function(res){
                        if(res.status == 'success'){
                            $('#actionAdd').submit();
                        }else{
                            var modal = `
                                <div class="modal-body">
                                    <h5 class="modal-title text-center">${res.status}</h5>
                                    <hr>
                                    <p class="text-center">${res.message}</p>
                                </div>
                            `
                            $('#modal-change-status').modal("hide")
                            $('.modal-backdrop').remove();
                            $('#error-modal-desc').html(modal)
                            $('#error-modal').modal("show")
                        }
                    },
                    error: function(xhr){
                        var modal = `
                            <div class="modal-body">
                                <h5 class="modal-title text-center">${xhr.responseJSON.status}</h5>
                                <hr>
                                <p class="text-center">${xhr.responseJSON.message}</p>
                            </div>
                        `
                        $('#modal-change-status').modal("hide")
                        $('.modal-backdrop').remove();
                        $('#error-modal-desc').html(modal)
                        $('#error-modal').modal("show")
                    }

                })
            })
            return false
        });

        $('#btn-update-status-payment-true, #btn-update-status-payment-false').on('click', function() {
            var classPayment = $(this).attr('class')
            var isVerified = false
            if(classPayment == 'btn btn-gradient-primary'){
                $('#statusACC').val('true')
                isVerified = true
            }else{
                $('#statusACC').val('false')
            }

            if(isVerified){
                testNetwork(networkValue, function(val){
                    var orderPaymentData = {
                        'order_code':'{{$order->code}}',
                        'total_payment':'',
                        'payment_date':'',
                        'bank_id':'',
                        'cicilan':'',
                        'image':'',
                        'user_id':'{{Auth::user()->code}}'
                    }
                    @foreach($order->orderPayment as $payment)
                        if($('#updateStatusPayment-order_payment_id').val() == '{{$payment->id}}'){
                            orderPaymentData.total_payment = '{{$payment->total_payment}}'
                            orderPaymentData.payment_date = '{{$payment->payment_date}}'
                            orderPaymentData.bank_id = '{{$payment->bank_id}}'
                            orderPaymentData.cicilan = '{{$payment->cicilan}}'
                            orderPaymentData.image = <?= $payment->image; ?>
                        }
                    @endforeach
                    for(var i = 0; i < orderPaymentData.image.length; i++){
                        orderPaymentData['order_payment_file_'+i] = `{{ asset('sources/order/${orderPaymentData.image[i]}') }}`
                    }
                    // $('#frmUpdateStatusPayment').submit();                    

                    $.ajax({
                        method: "post",
                        url: "http://{{ env('OFFLINE_URL') }}/api/replicate-order-payment-data",
                        data: orderPaymentData,
                        success: function(res){
                            $('#frmUpdateStatusPayment').submit();
                        },
                        error: function(xhr){
                            var modal = `
                                <div class="modal-body">
                                    <h5 class="modal-title text-center">${xhr.responseJSON.status}</h5>
                                    <hr>
                                    <p class="text-center">${xhr.responseJSON.message}</p>
                                </div>
                            `
                            $('#error-modal-desc').html(modal)
                            $('#error-modal').modal("show")
                        }

                    })
                })
                return false
            }else{
                $('#frmUpdateStatusPayment').submit();
            }
        });
    });

</script>
@endsection