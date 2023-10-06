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
    .div-CheckboxGroup {  padding: 5px;  }
    .black-color { color: black !important; }
    .content-wrapper { background: transparent !important;}
</style>
@endsection

@section('content')
@if( $order['code'] != null)
    <section id="intro" class="clearfix">
      	<div class="container-fluid">
            <div class="text-center">
                <h2>ORDER SUCCESS</h2>
            </div>
            <div class="col-md-12">
                <table class="w-100">
                    <thead>
                        <td>Order Code</td>
                        <td>Temp No</td>
                        <td>Order Date</td>
                    </thead>
                    <tr>
                        <td>{{ $order['code'] }}</td>
                        <td>{{ $order['temp_no'] }}</td>
                        <td class="right">{{ date("d/m/Y", strtotime($order['orderDate'])) }}</td>
                    </tr>
                </table>
                <table class="w-100">
                    <thead>
                        <td>Sales Branch</td>
                        <td colspan="2">Sales Code</td>
                    </thead>
                    <tr class="text-center">
                        <td rowspan="2">{{ $order->branch['code'] }} - {{ $order->branch['name'] }}</td>
                        <td colspan="2">{{ $order->cso['code'] }} - {{ $order->cso['name'] }}</td>
                    </tr>
                    <tr>
                        <td>(30%) {{ $order->cso_id_30['code'] }} - {{ $order->cso_id_30['name'] }}</td>
                        <td>(70%) {{ $order->cso_id_70['code'] }} - {{ $order->cso_id_70['name'] }}</td>
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
                @php
                    $checkedOrderPayment = $order['status'] == \App\Order::$status['2'] && $order->orderPayment->where('status', 'unverified')->count() == 0
                        && $order->orderPayment->where('status', 'verified')->count() > 0;
                    $checkStockInOutODetail = $order['status'] == \App\Order::$status['3'] && $order->orderDetail->where('type', '!=', 'upgrade')->where('stock_id', null)->count() > 0;
                    $totalSales = \App\TotalSale::whereIn('order_payment_id', $order->orderPayment->pluck('id')->toArray())->get();
                    $checkTotalSales = count($totalSales) > 0;
                @endphp
                <table class="w-100">
                    <thead>
                        <td colspan="10">Payment Detail</td>
                    </thead>
                    <thead style="background-color: #80808012 !important">
                        <td>Date</td>
                        <td>Type</td>
                        <td>Total Payment</td>
                        <td>Angsuran</td>
                        <td>Bank In</td>
                        <td>Image</td>
                        <td>Status</td>
                        @if (Gate::check('detail-order') || Gate::check('edit-order') || Gate::check('delete-order'))
                            <td colspan="3">View/Edit/Delete</td>
                        @endif
                    </thead>
                    @foreach ($order->orderPayment as $orderPayment)
                    <tr>
                        <td>{{ $orderPayment->payment_date }}</td>
                        <td>{{ ucfirst($orderPayment->type) }}</td>
                        <td>Rp. {{ number_format($orderPayment->total_payment) }}</td>
                        <td>{{ ($orderPayment->type_payment == 'card installment') ? $orderPayment->creditCard->name : (isset($orderPayment->bank_account_id)) ? $orderPayment->bankAccount->name : $orderPayment->bank->name }} {{ $orderPayment->cicilan }} Bln</td>
                        <td>{{ $order->branch->code }} ({{ $orderPayment->bank['name'] }})</td>
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
                        <td style="text-align: center;">
                            <button value="{{ $orderPayment['id'] }}" class="btn-delete btn-view_order_payment">
                                <i class="mdi mdi-eye" style="font-size: 24px; color:#33b5e5;"></i>
                            </button>
                        </td>
                        @if($orderPayment['status'] == 'verified' && !Auth::user()->inRole("head-admin"))
                            <td style="text-align: center;">
                                <button value="{{ $orderPayment['id'] }}" class="btn-delete btn-edit_order_payment_for_those_who_are_not_head_admin">
                                    <i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
                                </button>
                            </td>
                        @endif
                        @can('edit-order')
                            @if ($order->status != 'new' || Auth::user()->inRole("head-admin"))
                            @if($orderPayment['status'] !== "verified" || Auth::user()->inRole("head-admin"))
                                <td style="text-align: center;">
                                    <button value="{{ $orderPayment['id'] }}"
                                        data-toggle="modal"
                                        data-target="#editPaymentModal"
                                        class="btn-delete btn-edit_order_payment">
                                        <i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
                                    </button>
                                </td>
                            @endif
                            @if($orderPayment['status'] !== "verified" || Auth::user()->inRole("head-admin"))
                                <td style="text-align: center;">
                                    <button value="{{ route('delete_order_payment', ['id' => $orderPayment['id']])}}"
                                        data-toggle="modal"
                                        data-target="#deleteDoModal"
                                        class="btn-delete btn-delete_order_payment">
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
                        <td colspan="7" style="background-color: #f2f2f2;" rowspan="3"></td>
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

                <table class="w-100 d-none">
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

                <div class="w-100" id="addKomisiForm">
                  <div class="card">
                    <div class="card-header text-center p-2" style="font-size:14px;">
                      Commision Add
                    </div>
                    <div class="card-body px-0">
                        <form action="{{route('store_order_commission')}}" method="POST">
                            {{ csrf_field() }}
                            <div>
                                <input type="hidden" name="order_id" value="{{$order->id}}">
                                <input type="hidden" name="cso_id" value="{{$order->cso_id}}">
                                <div class="row no-gutters">
                                    <div class="form-group" style="width: 30%">
                                        <label for="orderUpgrade" class="w-100">Order Upgrade ?</label>
                                        <div class="form-check-inline">
                                            <input class="form-check-input" type="radio" name="" id="orderUpgradeYes" value="" {{$isUpgrade == 1 ? 'checked' : ''}} disabled>
                                            <label class="form-check-label mb-0" for="orderUpgradeYes">Yes</label>
                                        </div>
                                        <div class="form-check-inline">
                                            <input class="form-check-input" type="radio" name="" id="orderUpgradeNo" value="" {{$isUpgrade == 0 ? 'checked' : ''}} disabled>
                                            <label class="form-check-label mb-0" for="orderUpgradeNo">No</label>
                                        </div>
                                    </div>

                                    <div class="form-group" style="width: 30%">
                                        <label for="orderPrize" class="w-100">Order Prize ?</label>
                                        <div class="form-check-inline">
                                            <input class="form-check-input" type="radio" name="" id="orderPrizeYes" value="" {{$isPrize == 1 ? 'checked' : ''}} disabled>
                                            <label class="form-check-label mb-0" for="orderPrizeYes">Yes</label>
                                        </div>
                                        <div class="form-check-inline">
                                            <input class="form-check-input" type="radio" name="" id="orderPrizeNo" value="" {{$isPrize == 0 ? 'checked' : ''}} disabled>
                                            <label class="form-check-label mb-0" for="orderPrizeNo">No</label>
                                        </div>
                                    </div>

                                    <div class="form-group" style="width: 30%">
                                        <label for="orderTakeaway" class="w-100">Order Takeaway ?</label>
                                        <div class="form-check-inline">
                                            <input class="form-check-input" type="radio" name="orderTakeaway" id="orderTakeawayYes" value="" {{$isTakeAway == 1 ? 'checked' : ''}} disabled>
                                            <label class="form-check-label mb-0" for="orderTakeawayYes">Yes</label>
                                        </div>
                                        <div class="form-check-inline">
                                            <input class="form-check-input" type="radio" name="orderTakeaway" id="orderTakeawayNo" value="" {{$isTakeAway == 0 ? 'checked' : ''}} disabled>
                                            <label class="form-check-label mb-0" for="orderTakeawayNo">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Commission Type</label>
                                    <select class="form-control" id="commission_type_select" name="commission_type_id" data-msg="Mohon Pilih Tipe" required>
                                        <option selected disabled value="">
                                            Choose Commission Type
                                        </option>
                                            
                                        @if(Gate::check('add_custom_nominal-order_commission'))
                                            <option value="{{$customCommissionType->id}}" data-description="{{$customCommissionType->description}}" data-nominal="{{number_format($customCommissionType->nominal)}}" data-smgt-nominal="{{number_format($customCommissionType->smgt_nominal)}}">{{$customCommissionType->name}}</option>
                                        @endif

                                        @foreach($commissionTypes as $commType)
                                            <option value="{{$commType->id}}" data-description="{{$commType->description}}" data-nominal="{{number_format($commType->nominal)}}" data-smgt-nominal="{{number_format($commType->smgt_nominal)}}">{{$commType->name}}</option>
                                        @endforeach
                                    </select>
                                    <div class="validation"></div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control"
                                        id="commission_type_description"
                                        name=""
                                        rows="5"
                                        data-msg="Mohon Isi Deskripsi"
                                        placeholder="" readonly></textarea>
                                    <div class="validation"></div>
                                </div>

                                <div class="row m-0 {{ Gate::check('show_nominal-order_commission') ? "" : "d-none" }}">
                                    <div class="form-group col-6">
                                        <label for="">Bonus</label>
                                        <input type="text"
                                            {{ $isUpgrade ? "readonly" : "" }}
                                            class="form-control"
                                            id="commission_type_bonus"
                                            name="bonus"
                                            autocomplete="off"
                                            data-type="currency"
                                            placeholder="Bonus" required/>
                                        <div class="validation"></div>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="">Bonus Semangat</label>
                                        <input type="text"
                                            class="form-control"
                                            id="commission_type_smgt_nominal"
                                            name="smgt_nominal"
                                            autocomplete="off"
                                            data-type="currency"
                                            placeholder="Bonus Semangat" readonly/>
                                        <div class="validation"></div>
                                    </div>

                                    <div class="form-group col-6">
                                        <label for="">Upgrade</label>
                                        <input type="text"
                                            {{ $isUpgrade ? "" : "readonly" }}
                                            class="form-control"
                                            id="commission_type_upgrade"
                                            name="upgrade"
                                            autocomplete="off"
                                            data-type="currency"
                                            placeholder="Upgrade" required/>
                                        <div class="validation"></div>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="">Lebih Harga</label>
                                        <input type="text"
                                            class="form-control"
                                            id="commission_type_excess_price"
                                            name="excess_price"
                                            autocomplete="off"
                                            data-type="currency"
                                            placeholder="Lebih Harga" required/>
                                        <div class="validation"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="row justify-content-center">
                                @if(Gate::check('add-order_commission'))
                                <button type="submit" class="btn btn-success mr-2">
                                    Submit
                                </button>
                                @endif
                                <button type="button" class="btn btn-danger btn-cancel-comms">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>

                <div class="w-100" id="editKomisiForm">
                    @if($order->commissionType)
                    <div class="card">
                        <div class="card-header text-center p-2" style="font-size:14px;">
                            Edit Commision
                        </div>
                        <div class="card-body px-0">
                            <form action="{{route('update_order_commission_type', $order->id)}}" method="post">
                                {{csrf_field()}}
                                {{ method_field('PUT') }}
                                <div>
                                    <div class="row no-gutters">
                                        <div class="form-group" style="width: 30%">
                                            <label for="orderUpgrade" class="w-100">Order Upgrade ?</label>
                                            <div class="form-check-inline">
                                                <input class="form-check-input" type="radio" name="" id="editOrderUpgradeYes" value="" {{$isUpgrade == 1 ? 'checked' : ''}} disabled>
                                                <label class="form-check-label mb-0" for="editOrderUpgradeYes">Yes</label>
                                            </div>
                                            <div class="form-check-inline">
                                                <input class="form-check-input" type="radio" name="" id="editOrderUpgradeNo" value="" {{$isUpgrade == 0 ? 'checked' : ''}} disabled>
                                                <label class="form-check-label mb-0" for="editOrderUpgradeNo">No</label>
                                            </div>
                                        </div>
                                        <div class="form-group" style="width: 30%">
                                            <label for="orderPrize" class="w-100">Order Prize ?</label>
                                            <div class="form-check-inline">
                                                <input class="form-check-input" type="radio" name="" id="orderPrizeYes" value="" {{$isPrize == 1 ? 'checked' : ''}} disabled>
                                                <label class="form-check-label mb-0" for="orderPrizeYes">Yes</label>
                                            </div>
                                            <div class="form-check-inline">
                                                <input class="form-check-input" type="radio" name="" id="orderPrizeNo" value="" {{$isPrize == 0 ? 'checked' : ''}} disabled>
                                                <label class="form-check-label mb-0" for="orderPrizeNo">No</label>
                                            </div>
                                        </div>
                                        <div class="form-group" style="width: 30%">
                                            <label for="orderTakeaway" class="w-100">Order Takeaway ?</label>
                                            <div class="form-check-inline">
                                                <input class="form-check-input" type="radio" name="" id="editOrderTakeawayYes" value="" {{$isTakeAway == 1 ? 'checked' : ''}} disabled>
                                                <label class="form-check-label mb-0" for="editOrderTakeawayYes">Yes</label>
                                            </div>
                                            <div class="form-check-inline">
                                                <input class="form-check-input" type="radio" name="" id="editOrderTakeawayNo" value="" {{$isTakeAway == 0 ? 'checked' : ''}} disabled>
                                                <label class="form-check-label mb-0" for="editOrderTakeawayNo">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Commission Type</label>
                                        <select class="form-control" id="edit_commission_type_select" name="commission_type_id" data-msg="Mohon Pilih Tipe" required>
                                            <option selected disabled value="">
                                                Choose Commission Type
                                            </option>
                                            
                                            @if(Gate::check('add_custom_nominal-order_commission'))
                                                <option value="{{$customCommissionType->id}}" {{$customCommissionType->id == $order->commission_type_id ? 'selected' : ''}} data-description="{{$customCommissionType->description}}" data-nominal="{{number_format($customCommissionType->nominal)}}" data-smgt-nominal="{{number_format($customCommissionType->smgt_nominal)}}">{{$customCommissionType->name}}</option>
                                            @endif

                                            @foreach($commissionTypes as $commType)
                                                <option value="{{$commType->id}}" {{$commType->id == $order->commission_type_id ? 'selected' : ''}} data-description="{{$commType->description}}" data-nominal="{{number_format($commType->nominal)}}" data-smgt-nominal="{{number_format($commType->smgt_nominal)}}">{{$commType->name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="validation"></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control"
                                            id="edit_commission_type_description"
                                            name=""
                                            rows="5"
                                            data-msg="Mohon Isi Deskripsi"
                                            placeholder="" readonly>{{$order->commissionType->description}}</textarea>
                                        <div class="validation"></div>
                                    </div>

                                    <div class="row m-0 {{ Gate::check('show_nominal-order_commission') ? "" : "d-none" }}">
                                        <div class="form-group col-6">
                                            <label for="">Bonus</label>
                                            <input type="text"
                                                {{ $isUpgrade ? "readonly" : "" }}
                                                class="form-control"
                                                id="edit_commission_type_bonus"
                                                name="bonus"
                                                autocomplete="off"
                                                data-type="currency"
                                                placeholder="Bonus" required value="{{ number_format($order->orderCommission->sum('bonus')) }}"/>
                                            <div class="validation"></div>
                                        </div>
                                        <div class="form-group col-6">
                                            <label for="">Bonus Semangat</label>
                                            <input type="text"
                                                class="form-control"
                                                id="edit_commission_type_smgt_nominal"
                                                name="smgt_nominal"
                                                autocomplete="off"
                                                data-type="currency"
                                                placeholder="Bonus Semangat" readonly value="{{ number_format($order->orderCommission->sum('smgt_nominal')) }}"/>
                                            <div class="validation"></div>
                                        </div>

                                        <div class="form-group col-6">
                                            <label for="">Upgrade</label>
                                            <input type="text"
                                                {{ $isUpgrade ? "" : "readonly" }}
                                                class="form-control"
                                                id="edit_commission_type_upgrade"
                                                name="upgrade"
                                                autocomplete="off"
                                                data-type="currency"
                                                placeholder="Upgrade" required value="{{ number_format($order->orderCommission->sum('upgrade')) }}"/>
                                            <div class="validation"></div>
                                        </div>
                                        <div class="form-group col-6">
                                            <label for="">Lebih Harga</label>
                                            <input type="text"
                                                class="form-control"
                                                id="edit_commission_type_excess_price"
                                                name="excess_price"
                                                autocomplete="off"
                                                data-type="currency"
                                                placeholder="Lebih Harga" required value="{{ number_format($order->orderCommission->sum('excess_price')) }}"/>
                                            <div class="validation"></div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row justify-content-center">
                                    @if(Gate::check('edit-order_commission'))
                                    <button type="submit" class="btn btn-warning mr-2">
                                        Update
                                    </button>
                                    @endif
                                    <button type="button" class="btn btn-danger btn-cancel-edit-comms">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>

                @if(Gate::check('detail-order_commission'))
                    <div class="w-100" id="commisionDetailTrue">
                        <h3 class="text-center">Commision Detail</h3>
                          <table class="w-100">
                            <thead style="background-color: #80808012 !important">
                                <td>Date</td>
                                <td>Payment Gross</td>
                                <td>Payment Netto</td>
                                <td>Commission</td>
                            </thead>


                            @foreach($order->orderPayment as $orderPayment)
                                <tr align="center">
                                    @php
                                        $totalSaleGross = 0;
                                        $totalSaleNetto = 0;
                                        if($orderPayment->totalSale['bank_in'] > 0){
                                            $totalSaleGross = $orderPayment->totalSale['bank_in'];
                                            $totalSaleNetto = $orderPayment->totalSale['bank_in'];
                                        }
                                        elseif($orderPayment->totalSale['debit'] > 0){
                                            $totalSaleGross = $orderPayment->totalSale['debit'];
                                            $totalSaleNetto = $orderPayment->totalSale['netto_debit'];
                                        }
                                        elseif($orderPayment->totalSale['card'] > 0){
                                            $totalSaleGross = $orderPayment->totalSale['card'];
                                            $totalSaleNetto = $orderPayment->totalSale['netto_card'];
                                        }
                                    @endphp

                                    <td>{{ $orderPayment['payment_date'] }}</td>
                                    <td>Rp. {{ number_format($totalSaleGross) }}</td>
                                    <td>Rp. {{ number_format($totalSaleNetto) }}</td>
                                    <td>Rp. {{ number_format($orderPayment->commission_percentage) }}</td>
                                </tr>
                            @endforeach

                            <tfoot>
                                <td class="font-weight-bold text-right" colspan="3">Total Commission:</td>
                                <td class="text-center font-weight-bold">Rp. {{ number_format($order->orderPayment->sum('commission_percentage')) }}</td>
                            </tfoot>
                        </table>
                    </div>
                @endif

                @if($order['status'] != \App\Order::$status['1'] && $order['status'] != \App\Order::$status['5'])


                    @if($order->orderCommission->count() > 0 && Gate::check('detail-order_commission'))
                        <div class="w-100" id="commisionDetailTrue"><!-- change id to bonusDetailTrue later to remove ambiguous -->
                            <h3 class="text-center">Bonus Detail</h3>
                              <table class="w-100">
                                <thead style="background-color: #80808012 !important">
                                    <td>Percentage</td>
                                    <td>CSO-Name</td>
                                    <td>Commission</td>
                                    <td>Bonus</td>
                                    <td>Upgrade</td>
                                    <td>Bonus Semangat</td>
                                    <td>Lebih Harga</td>
                                    <td>Edit</td>
                                </thead>
                                <!-- maybe this is not a good way but piye neh... -->
                                @php if($order->orderCommission->count() > 1) { $cso_percentage = [70, 30]; } else { $cso_percentage = [100]; } @endphp
                                @foreach($order->orderCommission as $indexOrderCommission => $orderCommission)
                                    <tr align="center">
                                        <td>{{$cso_percentage[$indexOrderCommission]}}%</td>
                                        <td>{{$orderCommission->cso->code}} - {{$orderCommission->cso->name}}</td>
                                        <td>Rp. {{number_format($orderCommission->commission)}}</td>
                                        <td>Rp. {{number_format($orderCommission->bonus)}}</td>
                                        <td>Rp. {{number_format($orderCommission->upgrade)}}</td>
                                        <td>Rp. {{number_format($orderCommission->smgt_nominal)}}</td>
                                        <td>Rp. {{number_format($orderCommission->excess_price)}}</td>
                                        <td>
                                            @if(Gate::check('add_custom_nominal-order_commission'))
                                            <button value="{{ $orderCommission->id }}" class="btn-delete btn-edit-order-commission">
                                                <i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            <table class="w-100 my-2">
                                <thead style="background-color: #80808012 !important">
                                    <td colspan="2" class="text-left">Commision Type</td>
                                </thead>
                                <tr>
                                    <td>Name</td>
                                    <td>{{$order->commissionType->name}}</td>
                                </tr>
                                <tr>
                                    <td>Description</td>
                                    <!-- <td>Description</td> -->
                                    <td style="width:50%">{{$order->commissionType->description}}</td>
                                </tr>
                                <tr>
                                    <td>Prize</td>
                                    <td>{{$order->commissionType->prize == 1 ? 'Yes' : 'No'}}</td>
                                </tr>
                                <tr>
                                    <td>Takeaway</td>
                                    <td>{{$order->commissionType->takeaway == 1 ? 'Yes' : 'No'}}</td>
                                </tr>
                                <tr>
                                    <td>Upgrade</td>
                                    <td>{{$order->commissionType->upgrade == 1 ? 'Yes' : 'No'}}</td>
                                </tr>
                                <tr>
                                    <td>Nominal</td>
                                    <td>
                                        Rp. {{number_format($order->commissionType->nominal)}} (Default)
                                        @if($order->commissionType->nominal != $order->orderCommission->sum('bonus') && $order->commissionType->upgrade == 0)
                                            <span class="font-weight-bold">| Rp. {{ number_format($order->orderCommission->sum('bonus')) }} (Assign)</span>
                                        @elseif($order->commissionType->nominal != $order->orderCommission->sum('upgrade') && $order->commissionType->upgrade == 1)
                                            <span class="font-weight-bold">| Rp. {{ number_format($order->orderCommission->sum('upgrade')) }} (Assign)</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Semangat Nominal</td>
                                    <td>
                                        Rp. {{number_format($order->commissionType->smgt_nominal)}} (Default)
                                        @if($order->commissionType->smgt_nominal != $order->orderCommission->sum('smgt_nominal'))
                                            <span class="font-weight-bold">| Rp. {{ number_format($order->orderCommission->sum('smgt_nominal')) }} (Assign)</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            <div class="row justify-content-center mb-2">
                                @if(Gate::check('edit-order_commission'))
                                <button type="button" class="btn btn-warning mr-2 btn-edit-comms">
                                    Edit
                                </button>
                                @endif
                                @if(Gate::check('delete-order_commission'))
                                <button type="button" class="btn btn-danger btn-delete-comms"
                                    data-toggle="modal"
                                    data-target="#deleteKomisiConfirm">
                                    Delete
                                </button>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="row justify-content-center">
                        @if(Gate::check('add-order_commission'))
                        <button type="button" class="btn btn-gradient-success mdi mdi-cash-multiple btn-add-comms">
                            Add Commision
                        </button>
                        @endif
                        </div>
                    @endif
                @endif
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
                            @elseif($order['status'] == \App\Order::$status['6'])
                                <span class="badge" style="background-color: #FFD495">Request Stock</span>
                            @elseif($order['status'] == \App\Order::$status['7'])
                                <span class="badge text-white" style="background-color: #C147E9">Stock Approved</span>
                            @elseif ($order['status'] == \App\Order::$status['3'])
                                <span class="badge badge-warning">Delivery</span>
                            @elseif($order['status'] == \App\Order::$status['8'])
                                <span class="badge text-white" style="background-color: #FF6E31">Delivered</span>
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
                    @if (count(json_decode($order->delivered_image, true)) > 0)
                    <tr><td></td></tr>
                    <tr>
                        <td>Delivered Image : </td>
                    </tr>
                    <tr>
                        <td>
                            @foreach (json_decode($order->delivered_image, true) as $orderDeliveredImage)
                            <a href="{{ asset("sources/order/$orderDeliveredImage") }}"
                                target="_blank">
                                <i class="mdi mdi-numeric-{{ $loop->iteration }}-box" style="font-size: 24px; color: #2daaff;"></i>
                            </a>
                            @endforeach
                            @if($order['status'] != \App\Order::$status['4'] && Gate::check('change-status_order') && Gate::check('change-status_order_delivered'))
                            <button value="{{ $order['id'] }}"
                                data-toggle="modal"
                                data-target="#editDeliveredImageModal"
                                class="btn btn-delete ml-2">
                                <i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endif
                    @if (Gate::check('change-status_order'))
                        <tr>
                            <td>
                                <div class="form-group row justify-content-center">
                                    @if ($order['status'] == \App\Order::$status['1'] && Gate::check('change-status_order_process'))
                                    {{-- <button type="button" data-toggle="modal" data-target="#modal-change-status" status-order="{{\App\Order::$status['2']}}"
                                        class="btn btn-gradient-success mr-2 btn-lg btn-change-status-order">
                                        Process Order
                                    </button> --}}
                                    {{-- @elseif ($order['status'] == \App\Order::$status['2'] && Gate::check('change-status_order_stock_request_pending'))
                                    <button type="button" data-toggle="modal" data-target="#modal-change-status" status-order="{{\App\Order::$status['6']}}"
                                        class="btn mr-2 btn-change-status-order" style="background-color: #FFD495;">
                                        Request Stock
                                    </button> --}}
                                    @elseif ( ($checkedOrderPayment == true || $checkStockInOutODetail == true)  && Gate::check('change-status_order_delivery'))
                                    <button type="button" data-toggle="modal" data-target="#modal-change-status" status-order="{{\App\Order::$status['3']}}"
                                        class="btn btn-gradient-warning mr-2 btn-lg btn-change-status-order">
                                        Delivery Order
                                    </button>
                                    @elseif ($order['status'] == \App\Order::$status['3'] && Gate::check('change-status_order_delivered'))
                                    {{-- <button type="button" data-toggle="modal" data-target="#modal-change-status" status-order="{{\App\Order::$status['8']}}"
                                        class="btn mr-2 btn-change-status-order" style="background-color: #FF6E31; color: #FFF">
                                        Delivered Order
                                    </button> --}}
                                    @elseif ($order['status'] == \App\Order::$status['8'] && Gate::check('change-status_order_success'))
                                    {{-- <button type="button" data-toggle="modal" data-target="#modal-change-status" status-order="{{\App\Order::$status['4']}}"
                                        class="btn btn-gradient-primary mr-2 btn-lg btn-change-status-order">
                                        Success Order
                                    </button> --}}
                                    @endif
                                    @if ($order['status'] == \App\Order::$status['5'] && $checkTotalSales == true && Gate::check('change-status_order_reject'))
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
                <div class="row justify-content-center">
                  <a href="whatsapp://send?text={{ Route('order_success') }}?code={{ $order['code'] }}" data-action="share/whatsapp/share"
                  class="btn btn-gradient-primary mr-2">Share to Whatsapp</a>
                  @if (Gate::check('edit-order'))
                  <button type="button" data-toggle="modal" data-target="#addPaymentModal"
                      class="btn btn-gradient-success mdi mdi-cash-multiple">
                      Add Payment
                  </button>
                </div>
                <div class="clearfix"></div>
                @endif
            </div>

            @if($order->stockOrderRequest && $order->stockOrderRequest->stockInOut)
                <div class="col-md-12 my-3">
                    <h2 class="text-center">Out Stock</h2>
                    <table class="w-100">
                        <thead>
                            <td colspan="3">Warehouse (From-To)</td>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">{{ $order->stockOrderRequest->stockInOut->warehouseFrom['code'] }} - {{ $order->stockOrderRequest->stockInOut->warehouseFrom['name'] }}</td>
                                <td class="text-center">=></td>
                                <td class="text-center">{{ $order->stockOrderRequest->stockInOut->warehouseTo['code'] }} - {{ $order->stockOrderRequest->stockInOut->warehouseTo['name'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="w-100">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->stockOrderRequest->stockInout->stockInOutProduct as $sioProduct)
                            <tr>
                                <td>{{ $sioProduct->product['code'] }}</td>
                                <td>{{ $sioProduct->product['name'] }}</td>
                                <td>{{ $sioProduct->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- New Out Stock From Delivery --}}
            @php
                $orderDetailStock = $order->orderDetail->where('stock_id', '!=', null)->sortBy(function($item) {
                    return strtotime($item->stockInOut->date);
                });
                $orderDetailUpgrades = $order->orderDetail->where('type', 'upgrade');
            @endphp
            @if($orderDetailStock->count() > 0)
                <div class="col-md-12 my-3">
                    <h2 class="text-center">Out Stock</h2>
                    @php $oDStockGroupBys = $orderDetailStock->groupBy('stock_id'); @endphp
                    @foreach ($oDStockGroupBys as $oDStockGroupBy)
                        <table class="w-100">
                            <thead>
                                <td colspan="3">
                                    <a href="{{ route('detail_stock_in_out', ['code' => $oDStockGroupBy[0]->stockInOut->code]) }}" target="_blank">
                                        {{ $oDStockGroupBy[0]->stockInOut->code }}
                                    </a>
                                    <br>
                                    {{ $oDStockGroupBy[0]->stockInOut->date }}
                                </td>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">{{ $oDStockGroupBy[0]->stockInOut->warehouseFrom['code'] }} - {{ $oDStockGroupBy[0]->stockInOut->warehouseFrom['name'] }}</td>
                                    <td class="text-center">=></td>
                                    <td class="text-center">{{ $oDStockGroupBy[0]->stockInOut->warehouseTo['code'] }} - {{ $oDStockGroupBy[0]->stockInOut->warehouseTo['name'] }}</td>
                                </tr>
                                @if($oDStockGroupBy[0]->stockInOut['description'] != null)
                                <tr>
                                    <td colspan="3">{{ $oDStockGroupBy[0]->stockInOut['description'] }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <table class="w-100">
                            <tbody>
                                @foreach ($oDStockGroupBy[0]->stockInout->stockInOutProduct as $sioProduct)
                                <tr>
                                    <td>{{ $sioProduct->product['code'] }}</td>
                                    <td>{{ $sioProduct->product['name'] }}</td>
                                    <td>{{ $sioProduct->quantity }}</td>
                                </tr>
                                @endforeach
                                @if ($loop->iteration == 1 && count($orderDetailUpgrades) > 0)
                                    @foreach ($orderDetailUpgrades as $odUpgrade)
                                        <tr>
                                            <td>{{ $odUpgrade->product['code'] ?? $odUpgrade->promo['code'] ?? 'OTHER'  }}</td>
                                            <td>Upgrade : {{ $odUpgrade->product['name'] ?? (($odUpgrade->promo) ? implode(", ", $odUpgrade->promo->productName()) : $odUpgrade->other) }}</td>
                                            <td>{{ $odUpgrade->qty }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <td colspan="3" class="text-center">
                                        <a href="{{ route('pdf_out_from_order', [
                                                'code' => $order['code'],
                                                'stock_in_out' => $oDStockGroupBy[0]->stockInout['id'],
                                                'upgrade' => ($loop->iteration == 1 && count($orderDetailUpgrades) > 0)
                                            ]) }}" class="btn btn-gradient-info">
                                            <span><i class="mdi mdi-file-document"></i></span> PDF
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @endforeach
                </div>
            @endif

            @if($order['reject_stock_id'] !=null)
                <div class="col-md-12 my-3">
                    <h2 class="text-center text-danger">Reject (In) Stock</h2>
                    <table class="w-100">
                        <thead>
                            <td colspan="3">
                                <a href="{{ route('detail_stock_in_out', ['code' => $order->rejectStock->code]) }}" target="_blank">
                                    {{ $order->rejectStock->code }}
                                </a>
                                <br>
                                {{ $order->rejectStock->date }}
                            </td>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">{{ $order->rejectStock->warehouseFrom['code'] }} - {{ $order->rejectStock->warehouseFrom['name'] }}</td>
                                <td class="text-center">=></td>
                                <td class="text-center">{{ $order->rejectStock->warehouseTo['code'] }} - {{ $order->rejectStock->warehouseTo['name'] }}</td>
                            </tr>
                            @if($order->rejectStock['description'] != null)
                            <tr>
                                <td colspan="3">{{ $order->rejectStock['description'] }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <table class="w-100">
                        <tbody>
                            @foreach ($order->rejectStock->stockInOutProduct as $sioProduct)
                            <tr>
                                <td>{{ $sioProduct->product['code'] }}</td>
                                <td>{{ $sioProduct->product['name'] }}</td>
                                <td>{{ $sioProduct->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="col-md-12">
                <h2 class="text-center">ORDER HISTORY LOG</h2>
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
                                {{-- Pilih Date, Warehouse From, Product for Delivery --}}
                                @if (($checkedOrderPayment == true || $checkStockInOutODetail == true)  && Gate::check('change-status_order_delivery'))
                                <div id="delivery-stock">
                                    <input type="hidden" name="to_warehouse_type" value="{{ $order->branch->warehouse['type'] }}">
                                    <input type="hidden" name="to_warehouse_id" value="{{ $order->branch['warehouse_id'] }}">
                                    <input type="hidden" name="temp_no" value="{{ $order->temp_no }}">
                                    <input type="hidden" name="type" value="out">
                                    <div class="form-group mb-3">
                                        <label>Delivery Date</label>
                                        <input type="date" id="delivery_date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Select Warehouse From</label>
                                        <select id="from_warehouse_id" class="form-control" name="from_warehouse_id" style="width: 100%" required>
                                            <option value="">Select Warehouse</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Description (Optional)</label>
                                        <textarea id="delivery_description" class="form-control" name="description"></textarea>
                                    </div>
                                    {{-- Delivery Poduk Order --}}
                                    <div id="orderDetail-product" class="form-group mb-3">
                                        <label>Pilihan Produk</label>
                                        <label style="float: right">(Min: 1)</label>
                                        <table>
                                            <tr>
                                                <th></th>
                                                <th>Code</th>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                            </tr>
                                            @foreach ($order->orderDetail as $orderDetail)
                                                @if($orderDetail->type != "upgrade" && ($orderDetail->product_id != null || $orderDetail->promo_id != null) && $orderDetail->stock_id == null)
                                                <tr>
                                                    <td><input type="checkbox" class="form-control orderDetail-product" name="orderDetail_product[]" value="{{ $orderDetail->id }}"></td>
                                                    <td>{{ $orderDetail->product['code'] ?? $orderDetail->promo['code'] ?? 'OTHER' }}</td>
                                                    <td>{{ $orderDetail->product['name'] ?? (($orderDetail->promo) ? implode(", ", $orderDetail->promo->productName()) : $orderDetail->other) }}</td>
                                                    <td>{{ $orderDetail->qty }}</td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                                @endif

                                @if($order['status'] == \App\Order::$status['5'] && $checkTotalSales == true && $orderDetailStock->count() > 0 && Gate::check('change-status_order_reject'))
                                    <div id="reject-stock">
                                        <h4>Stock In</h4>
                                        <input type="hidden" name="from_warehouse_type" value="{{ $order->branch->warehouse['type'] }}">
                                        <input type="hidden" name="from_warehouse_id" value="{{ $order->branch['warehouse_id'] }}">
                                        <input type="hidden" name="temp_no" value="{{ $order->temp_no }}">
                                        <input type="hidden" name="type" value="in">
                                        <div class="form-group mb-3">
                                            <label>Delivery Date</label>
                                            <input type="date" id="reject_delivery_date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Select Warehouse To</label>
                                            <select id="reject_to_warehouse_id" class="form-control" name="to_warehouse_id" style="width: 100%" required>
                                                <option value="">Select Warehouse</option>
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Description (Optional)</label>
                                            <textarea id="reject_delivery_description" class="form-control" name="description"></textarea>
                                        </div>
                                        @foreach ($order->orderDetail as $orderDetail)
                                            @if($orderDetail->stock_id != null)
                                            <input type="hidden" class="form-control" name="orderDetail_product[]" value="{{ $orderDetail->id }}">
                                            @endif
                                        @endforeach
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
                                        id="add_payment_date"
                                        class="form-control"
                                        name="payment_date"
                                        value="{{ date('Y-m-d') }}"
                                        required>
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
                                    <label for="">Type</label>
                                    <select name="type" class="form-control" id="" required>
                                        <option value="" selected disable>-- select type first --</option>
                                        <option value="order">ORDER</option>
                                        <option value="cash">CASH</option>
                                        <option value="delivery">DELIVERY</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Type Payment</label>
                                    <select name="type_payment" class="form-control" id="typePayment" required>
                                        <option value="" selected disable>-- select type payment first --</option>
                                        <option value="cash">CASH</option>
                                        <option value="debit">DEBIT</option>
                                        <option value="card">CARD</option>
                                        <option value="card installment">CARD INSTALLMENT</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Installment</label>
                                    <select name="credit_card_id" id="selectInstallment" class="form-control select-with-select2 installment-form" disabled>
                                        <option></option>
                                        @foreach($creditCards as $cc)
                                            <option value="{{$cc->id}}">{{$cc->code}} - {{$cc->name}}</option>
                                        @endforeach
                                    </select>
                                    <div id="creditCardName" class="mt-2"></div>
                                    <input type="number" min=1 class="form-control" id="creditCardInstallment" name="cicilan" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Bank</label>
                                    <select name="bank_account_id" id="selectBank" class="form-control select-with-select2">
                                        <option></option>
                                        @foreach($bankAccounts as $bankAccount)
                                            <option value="{{$bankAccount->id}}">{{$bankAccount->code}} - {{$bankAccount->name}} ({{$bankAccount->account_number}})</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="bank_id" id="bank_id">
                                    <div id="bankDesc" class="mt-2"></div>
                                </div>
                                <div class="form-group">
                                    <label for="">Charge (%)</label>
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <input type="number" min=0 class="form-control installment-form" step="any" placeholder="0" name="charge_percentage_company" id="chargePercentageCompany" readonly>
                                        </div>
                                        <div class="col-lg-2 text-center"><h3>+</h3></div>
                                        <div class="col-lg-5">
                                            <input type="number" min=0 class="form-control" step="any" placeholder="0" name="charge_percentage_bank" id="chargePercentageBank">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-1">
                                    <label for="">Estimate Transfer Date</label>
                                    <input type="date" id="estimateTransferDate"
                                        class="form-control"
                                        name="estimate_transfer_date"
                                        value="{{ date('Y-m-d') }}"
                                        required>
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
            <!-- Modal View Payment -->
            <div class="modal fade"
                id="viewPaymentModal"
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
                                Detail Payment
                            </h5>
                            <br>
                            <div class="form-group mb-1">
                                <label for="">Payment Date</label>
                                <input type="date"
                                    id="viewPayment-payment_date"
                                    class="form-control"
                                    value=""
                                    readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Nominal Payment</label>
                                <input type="text"
                                    id="viewPayment-total_payment"
                                    class="form-control downpayment"
                                    readonly
                                    data-type="currency"/>
                            </div>
                            <div class="form-group">
                                <label for="">Type</label>
                                <select name="type" class="form-control black-color" id="viewPayment-select_type" disabled>
                                    <option value=""></option>
                                    <option value="order">ORDER</option>
                                    <option value="cash">CASH</option>
                                    <option value="delivery">DELIVERY</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Type Payment</label>
                                <select name="type_payment" class="form-control black-color" id="viewPayment-select_type_payment" disabled>
                                    <option value=""></option>
                                    <option value="cash">CASH</option>
                                    <option value="debit">DEBIT</option>
                                    <option value="card">CARD</option>
                                    <option value="card installment">CARD INSTALLMENT</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Installment</label>
                                <select id="viewPayment-select_installment" class="form-control black-color" disabled>
                                    <option value=""></option>
                                    @foreach($creditCards as $cc)
                                        <option value="{{$cc->id}}">{{$cc->code}}</option>
                                    @endforeach
                                </select>
                                <div id="viewPayment-credit_card_name" class="mt-2"></div>
                                <input type="number" class="form-control" id="viewPayment-credit_card_installment" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Bank</label>
                                <select id="viewPayment-select_bank" class="form-control black-color" disabled>
                                    <option></option>
                                    @foreach($bankAccounts as $bankAccount)
                                        <option value="{{$bankAccount->id}}">{{$bankAccount->code}}</option>
                                    @endforeach
                                </select>
                                <div id="viewPayment-bank_description" class="mt-2"></div>
                            </div>
                            <div class="form-group">
                                <label for="">Charge (%)</label>
                                <div class="row">
                                    <div class="col-lg-5">
                                        <input type="number" class="form-control" step="any" id="viewPayment-charge_percentage_company" readonly>
                                    </div>
                                    <div class="col-lg-2 text-center"><h3>+</h3></div>
                                    <div class="col-lg-5">
                                        <input type="number" class="form-control" step="any" id="viewPayment-charge_percentage_bank" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Estimate Transfer Date</label>
                                <input type="date" id="viewPayment-estimate_transfer_date" class="form-control" readonly>
                            </div>
                            <div class="form-group mt-2">
                                <label for="">Bukti Pembayaran</label>
                                <div class="clearfix"></div>
                                @for ($i = 0; $i < 3; $i++)
                                    <div class="col-xs-12 col-sm-6 col-md-4 form-group"
                                        style="padding: 15px; float: left;">
                                        <label>Image {{ $i + 1 }}</label>
                                        <div id="viewPayment-image-{{ $i }}" class="imagePreview"
                                            style="background-image: url({{ asset('sources/dashboard/no-img-banner.jpg') }});">
                                        </div>
                                    </div>
                                @endfor
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal View Payment -->
            <!-- Modal Edit Payment For Those Who Are Not Head Admin -->
            <div class="modal fade"
                id="editPaymentModalForThoseWhoAreNotHeadAdmin"
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
                        <form method="post" id="editFormPaymentForThoseWhoAreNotHeadAdmin" action="">
                            @csrf
                            {{ method_field('PUT') }}
                            <div class="modal-body">
                                <h5 style="text-align: center;">
                                    Edit Payment
                                </h5>
                                <br>
                                <div class="form-group">
                                    <label for="">Charge (%)</label>
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <input type="number" step="any"
                                                class="form-control"
                                                id="editPaymentForThoseWhoAreNotHeadAdmin-charge_percentage_company"
                                                name="charge_percentage_company"
                                                required>
                                        </div>
                                        <div class="col-lg-2 text-center"><h3>+</h3></div>
                                        <div class="col-lg-5">
                                            <input type="number" step="any"
                                                class="form-control"
                                                id="editPaymentForThoseWhoAreNotHeadAdmin-charge_percentage_bank"
                                                name="charge_percentage_bank"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Estimate Transfer Date</label>
                                    <input type="date" id="editPaymentForThoseWhoAreNotHeadAdmin-estimate_transfer_date"
                                        class="form-control"
                                        name="estimate_transfer_date"
                                        required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-gradient-success mr-2">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Modal Edit Payment For Which Is Not Head Admin -->
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
                                    <label for="">Type</label>
                                    <select name="type" class="form-control" id="editPayment-select_type" required>
                                        <option value="" selected disable>-- select type first --</option>
                                        <option value="order">ORDER</option>
                                        <option value="cash">CASH</option>
                                        <option value="delivery">DELIVERY</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Type Payment</label>
                                    <select name="type_payment" class="form-control" id="editPayment-select_type_payment" required>
                                        <option value="" selected disable>-- select type payment first --</option>
                                        <option value="cash">CASH</option>
                                        <option value="debit">DEBIT</option>
                                        <option value="card">CARD</option>
                                        <option value="card installment">CARD INSTALLMENT</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Installment</label>
                                    <select name="credit_card_id" id="editPayment-select_installment" class="form-control editPayment-select_with_select2 editPayment-installment_form">
                                        <option></option>
                                        @foreach($creditCards as $cc)
                                            <option value="{{$cc->id}}">{{$cc->code}} - {{$cc->name}}</option>
                                        @endforeach
                                    </select>
                                    <div id="editPayment-credit_card_name" class="mt-2"></div>
                                    <input type="number" min=1 class="form-control" id="editPayment-credit_card_installment" name="cicilan" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Bank</label>
                                    <select name="bank_account_id" id="editPayment-select_bank" class="form-control editPayment-select_with_select2">
                                        <option></option>
                                        @foreach($bankAccounts as $bankAccount)
                                            <option value="{{$bankAccount->id}}">{{$bankAccount->code}} - {{$bankAccount->name}} ({{$bankAccount->account_number}})</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="bank_id" id="editPayment-bank_id">
                                    <div id="editPayment-bank_description" class="mt-2"></div>
                                </div>
                                <div class="form-group">
                                    <label for="">Charge (%)</label>
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <input type="number" min=0 class="form-control editPayment-installment_form" step="any" placeholder="0" name="charge_percentage_company" id="editPayment-charge_percentage_company">
                                        </div>
                                        <div class="col-lg-2 text-center"><h3>+</h3></div>
                                        <div class="col-lg-5">
                                            <input type="number" min=0 class="form-control" step="any" placeholder="0" name="charge_percentage_bank" id="editPayment-charge_percentage_bank">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Estimate Transfer Date</label>
                                    <input type="date" id="editPayment-estimate_transfer_date"
                                        class="form-control"
                                        name="estimate_transfer_date"
                                        value=""
                                        required>
                                </div>

                                <div class="form-group mt-2">
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
                            @if (Gate::check('change-status_payment'))
                            <div id="divUpdateStatusPayment" class="text-center p-3" style="border: 1px solid black">
                                <h5 class="mb-3">Status Payment</h5>
                                <form id="frmUpdateStatusPayment"
                                    method="post"
                                    action="{{ route('update_status_order_payment') }}">
                                    @csrf
                                    <input type="hidden" id="updateStatusPayment-order_id" name="order_id" value="{{ $order['id'] }}">
                                    <input type="hidden" id="updateStatusPayment-order_payment_id" name="order_payment_id" value="">
                                    <div class="btn-action" style="text-align: center;">
                                        @if (Gate::check('change-status_payment_verified'))
                                        <button type="submit" class="btn btn-gradient-primary" name="status_acc" value="true">Verified</button>
                                        @endif
                                        @if (Gate::check('change-status_payment_rejected'))
                                        <button type="submit" class="btn btn-gradient-danger" name="status_acc" value="false">Rejected</button>
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
            <!-- Modal Delete order commission type -->
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
                            <form id="" method="post" action="{{route('delete_order_commission_type', $order->id)}}">
                                {{ method_field('delete') }}
                                {{ csrf_field() }}
                                @if(Gate::check('delete-order_commission'))
                                <button type="submit"
                                    class="btn btn-gradient-danger mr-2">
                                    Yes
                                </button>
                                @endif
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
            @endif

            @else
            <div class="row justify-content-center">
                <h2>CANNOT FIND ORDER</h2>
            </div>
            @endif

            <!-- Modal Edit Order Commission -->
            <div class="modal fade"
                id="editOrderCommissionModal"
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
                            <form id="frmEditOrderCommission"
                                method="post"
                                action="{{route('update_order_commission')}}">
                                @csrf
                                <h5 style="text-align: center;">
                                    Edit Order Commission
                                </h5>
                                <br>
                                <input type="hidden" name="id" id="order_commission_id">
                                <div class="form-group">
                                    <label for="">Bonus</label>
                                    <input type="text"
                                        id="edit_bonus_order_commission"
                                        class="form-control"
                                        name="bonus"
                                        required
                                        autocomplete="off"
                                        data-type="currency"
                                        data-msg="Mohon isi nominal bonus" />
                                </div>
                                <div class="form-group">
                                    <label for="">Upgrade</label>
                                    <input type="text"
                                        id="edit_upgrade_order_commission"
                                        class="form-control"
                                        name="upgrade"
                                        required
                                        autocomplete="off"
                                        data-type="currency"
                                        data-msg="Mohon isi nominal upgrade" />
                                </div>
                                <div class="form-group">
                                    <label for="">Bonus Semangat</label>
                                    <input type="text"
                                        id="edit_smgt_nominal_order_commission"
                                        class="form-control"
                                        name="smgt_nominal"
                                        required
                                        autocomplete="off"
                                        data-type="currency"
                                        data-msg="Mohon isi nominal smgt_nominal" />
                                </div>
                                <div class="form-group">
                                    <label for="">Lebih Harga</label>
                                    <input type="text"
                                        id="edit_excess_price_order_commission"
                                        class="form-control"
                                        name="excess_price"
                                        required
                                        autocomplete="off"
                                        data-type="currency"
                                        data-msg="Mohon isi nominal excess price" />
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer footer-cash">
                            @if(Gate::check('edit-order_commission'))
                            <button type="submit" form="frmEditOrderCommission"
                                id="submitfrmEditOrderCommission"
                                class="btn btn-gradient-success mr-2">
                                Update
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal Edit Payment -->
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
        $("#from_warehouse_id").select2({
            theme: "bootstrap4",
            placeholder: "Choose Warehouse From",
            dropdownParent: $('#modal-change-status .modal-body')
        })
        $("#reject_to_warehouse_id").select2({
            theme: "bootstrap4",
            placeholder: "Choose Warehouse To",
            dropdownParent: $('#modal-change-status .modal-body')
        })
        $(document).on("input", 'input[data-type="currency"]', function() {
            $(this).val(numberWithCommas($(this).val()));
        });
        var statusOrder = "{{ $order['status'] }}";
        $(".btn-change-status-order").click(function(){
            statusOrder = $(this).attr('status-order');
            $('#delivery-cso').hide();
            $('#request-stock').hide();
            $('#delivered-image').hide();
            $('#delivery-stock, #delivery_date, #from_warehouse_id, #delivery_description, #orderDetail-product').hide();
            $('.delivery-cso-id').attr('disabled', true);
            $('#delivery-stock input[type=hidden]').attr('disabled', true);
            $('#delivery_date, #from_warehouse_id, .orderDetail-product').attr('disabled', true);
            $("#reject-stock, #reject_delivery_date, #reject_to_warehouse_id, #reject_delivery_description").hide();
            $('#reject-stock input[type=hidden]').attr('disabled', true);
            $("#reject_delivery_date, #reject_to_warehouse_id, #reject_delivery_description").attr('disabled', true);
            $('.prodCodeName, .prodQty').attr('disabled', true);
            $('.addDelivered-productimg').attr('disabled', true);
            $('#status-order').val(statusOrder);
            if (statusOrder == "{{\App\Order::$status['2']}}") {
                $("#modal-change-status-question").html('Process This Order?');
            } else if (statusOrder == "{{\App\Order::$status['6']}}")  {
                $('#request-stock').show();
                $('.prodCodeName, .prodQty').attr('disabled', false);
                $("#modal-change-status-question").html('Request Stock? \n Choose Product');
            } else if (statusOrder == "{{\App\Order::$status['3']}}")  {
                $('#delivery-cso').show();
                $('.delivery-cso-id').attr('disabled', false);
                $('#delivery-stock, #delivery_date, #from_warehouse_id, #delivery_description, #orderDetail-product').show();
                $('#delivery-stock input[type=hidden]').attr('disabled', false);
                $('#delivery_date, #from_warehouse_id, .orderDetail-product').attr('disabled', false);
                $("#modal-change-status-question").html('Delivery This Order?');
            } else if (statusOrder == "{{\App\Order::$status['8']}}") {
                $('#delivered-image').show();
                $('.addDelivered-productimg').attr('disabled', false);
                $("#modal-change-status-question").html('Delivered This Order?');
            } else if (statusOrder == "{{\App\Order::$status['4']}}") {
                $("#modal-change-status-question").html('Success This Order?');
            } else if (statusOrder == "{{\App\Order::$status['5']}}") {
                $("#reject-stock, #reject_delivery_date, #reject_to_warehouse_id, #reject_delivery_description").show();
                $('#reject-stock input[type=hidden]').attr('disabled', false);
                $("#reject_delivery_date, #reject_to_warehouse_id, #reject_delivery_description").attr('disabled', false);
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

        @if(session('error'))
            $("#error-modal").modal('show');
        @endif

        function getWarehouse(warehouse_type, check_parent, target_element) {
            $(target_element).html("");
            return $.ajax({
                method: "GET",
                url: "{{ route('fetchWarehouse') }}",
                data: {warehouse_type, check_parent},
                success: function(response) {
                    var option_warehouse = `<option value="" selected disabled>Select Warehouse</option>`;
                    response.data.forEach(function(value) {
                        option_warehouse += `<option value="${value.id}">${value.code} - ${value.name}</option>`;
                    })
                    $(target_element).html(option_warehouse);
                },
            });
        }

        @if (($checkedOrderPayment == true || $checkStockInOutODetail == true)  && Gate::check('change-status_order_delivery'))
            const on_submit_orderDelivery = function(e) {
                e.preventDefault();

                // Check Min 1 Delivery Product
                if (statusOrder == "{{\App\Order::$status['3']}}" && $(".orderDetail-product:checked").length < 1) {
                    return alert('Choose min 1 product.');
                } else {
                    $(this).off('submit', on_submit_orderDelivery);
                    $(this).submit();
                }
            };
            $('#actionAdd').on('submit', on_submit_orderDelivery);

            getWarehouse(null, true, '#from_warehouse_id');
        @endif

        @if($order['status'] == \App\Order::$status['5'] && $checkTotalSales == true && $orderDetailStock->count() > 0 && Gate::check('change-status_order_reject'))
            getWarehouse(null, true, "#reject_to_warehouse_id");
        @endif

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
            // $("#frmAddPayment").submit();
        });

        $("#imageAddPayment").on("change", function() {
            if ($("#imageAddPayment")[0].files.length > 3) {
                $("#imageAddPayment").val("")
                $('.custom-file-label').html("Choose image");
                alert("You can select only 3 images");
            }
        });

        // View Order Payment
        $(".btn-view_order_payment").click(function() {
            var url = '{{route("view_order_payment", ":id")}}'
            url = url.replace(':id', $(this).val())
            $.ajax({
                method: "get",
                url: url,
                success: function(data) {
                    $('#viewPaymentModal').modal('show');
                    $("#viewPayment-payment_date").val(data.payment_date);
                    $("#viewPayment-total_payment").val(numberWithCommas(data.total_payment));
                    $("#viewPayment-select_type").val(data.type).change()
                    $("#viewPayment-select_type_payment").val(data.type_payment).change()
                    $("#viewPayment-select_installment").val(data.credit_card_id).change()
                    $('#viewPayment-credit_card_name').html(data.credit_card_name)
                    $('#viewPayment-credit_card_installment').val(data.installment)
                    $("#viewPayment-select_bank").val(data.bank_account_id).change()
                    $('#viewPayment-bank_description').html(`
                        <b>${data.bank_name}</b> ${data.bank_account_name} (${data.bank_account_number})
                    `)
                    $('#viewPayment-charge_percentage_company').val(data.charge_percentage_company)
                    $('#viewPayment-charge_percentage_bank').val(data.charge_percentage_bank)
                    $('#viewPayment-estimate_transfer_date').val(data.estimate_transfer_date)
                    const mainUrlImage = "{{ asset('sources/order') }}";
                    $.each(JSON.parse(data.images), function(index, image) {
                        $("#viewPayment-image-" + index).css('background-image', 'url(' + mainUrlImage + "/" +image + ')');
                    });
                },
                error: function(data) {
                    $('#viewPaymentModal').modal('hide');
                    alert(data.responseJSON.error);
                }
            });
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
                        $("#editPayment-total_payment").val(numberWithCommas(result.total_payment));

                        $("#editPayment-credit_card_name").html('')
                        $("#editPayment-bank_description").html('')
                        if(result.credit_card_id){
                            $("#editPayment-select_installment").val(result.credit_card_id).change()
                            $('#editPayment-select_installment').attr('data-info', 'first');
                            infoFromCreditCard(result.credit_card_id)
                        }
                        if(result.bank_account_id){
                            $("#editPayment-select_bank").val(result.bank_account_id).change()
                            $('#editPayment-select_bank').attr('data-info', 'first');
                            infoFromBankAccount(result.bank_account_id)
                        }

                        $("#editPayment-select_type").val(result.type).change()
                        $("#editPayment-select_type_payment").val(result.type_payment).change()
                        $("#editPayment-credit_card_installment").val(result.cicilan)
                        $("#editPayment-bank_id").val(result.bank_id);
                        $("#editPayment-charge_percentage_company").val(result.charge_percentage_company)
                        $("#editPayment-charge_percentage_bank").val(result.charge_percentage_bank)
                        $("#editPayment-estimate_transfer_date").val(result.estimate_transfer_date)
                        if(result.type_payment == 'card installment' || result.type_payment == 'card'){
                            $('.editPayment-installment_form').prop({'disabled' : false, 'readonly' : false})
                        }else{
                            $('.editPayment-installment_form').prop({'disabled' : true, 'readonly' : true})
                        }

                        const mainUrlImage = "{{ asset('sources/order') }}";
                        $.each(JSON.parse(result.image), function(index, image) {
                            $("#editPayment-productimg-" + index).closest(".imgUp").find(".imagePreview")
                                .css('background-image', 'url(' + mainUrlImage + "/" +image + ')');
                        });

                        @if ($order['status'] != \App\Order::$status['5'] && Gate::check('change-status_payment'))
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

        $(".btn-edit_order_payment_for_those_who_are_not_head_admin").click(function() {
            var id = $(this).val()
            var url = '{{route("view_order_payment", ":id")}}'
            url = url.replace(':id', id)
            $.ajax({
                method: "get",
                url: url,
                success: function(data) {
                    $('#editPaymentModalForThoseWhoAreNotHeadAdmin').modal('show');
                    var urlForm = '{{route("update_order_payment_for_those_who_are_not_head_admin", ":paymentID")}}'
                    urlForm = urlForm.replace(':paymentID', id)
                    $('#editFormPaymentForThoseWhoAreNotHeadAdmin').attr('action', urlForm)
                    $('#editPaymentForThoseWhoAreNotHeadAdmin-charge_percentage_company').val(data.charge_percentage_company)
                    $('#editPaymentForThoseWhoAreNotHeadAdmin-charge_percentage_bank').val(data.charge_percentage_bank)
                    $('#editPaymentForThoseWhoAreNotHeadAdmin-estimate_transfer_date').val(data.estimate_transfer_date)
                },
                error: function(data) {
                    $('#editPaymentModalForThoseWhoAreNotHeadAdmin').modal('hide');
                    alert(data.responseJSON.error);
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

        $("#addKomisiForm").slideUp();
        $("#editKomisiForm").slideUp();

        $(".btn-add-comms").click(function(e) {
            e.preventDefault();
            $("#addKomisiForm").slideDown();
        });
        $(".btn-cancel-comms").click(function(e) {
            e.preventDefault();
            $("#addKomisiForm").slideUp();
        });
        $(".btn-edit-comms").click(function(e) {
            e.preventDefault();
            $("#editKomisiForm").slideDown();
            $("#commisionDetailTrue").slideUp();
        });
        $(".btn-cancel-edit-comms").click(function(e) {
            e.preventDefault();
            $("#editKomisiForm").slideUp();
            $("#commisionDetailTrue").slideDown();
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

        // add
        $('#typePayment').on('change', function() {
            if(this.value == 'card installment' || this.value == 'card'){
                $('.installment-form').prop({'disabled' : false, 'readonly' : false})
            }else{
                $('.installment-form').prop({'disabled' : true, 'readonly' : true})
                $('#selectInstallment').val('').trigger('change')
                $('#creditCardName').html('')
                $('#chargePercentageCompany').val(0)
                $('#creditCardInstallment').val(1)
                if($('#selectBank').val() !== ''){
                    var url = '{{ route("get_bank_account_from_payment_modal", ":id") }}';
                    url = url.replace(':id', $('#selectBank').val());
                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(data){
                            const d = new Date($("#add_payment_date").val());
                            d.setDate(d.getDate() + data.estimate_transfer);
                            var month = d.getMonth() + 1
                            var day = d.getDate()
                            if(month < 10){
                                month = "0"+month
                            }
                            if(day < 10){
                                day = "0"+day
                            }
                            var date = d.getFullYear() + "-" + month + "-" + day
                            $('#estimateTransferDate').val(date)
                        }
                    });
                }else{
                    $('#estimateTransferDate').val('{{date("Y-m-d")}}')
                }
            }
        });
        $(".select-with-select2").select2({
            theme: 'bootstrap4',
            placeholder: '-- select first --',
            dropdownParent: $('#addPaymentModal .modal-content')
        })
        $('#selectInstallment').on('change', function() {
            var url = '{{ route("get_credit_card", ":id") }}';
            url = url.replace(':id', this.value);
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
                    $('#creditCardName').html(`<span>${data.name}</span>`)
                    $('#creditCardInstallment').val(data.cicilan)
                    $('#selectBank').val(data.bank_account.id).trigger('change')
                    $('#bankDesc').html(`
                        <span><b>${data.bank_account.bank.name}</b> ${data.bank_account.name} (${data.bank_account.account_number})</span>
                    `)
                    $('#chargePercentageCompany').val(data.charge_percentage_company)
                    $('#chargePercentageBank').val(data.bank_account.charge_percentage)
                    const d = new Date($("#add_payment_date").val());
                    d.setDate(d.getDate() + data.estimate_transfer);
                    var month = d.getMonth() + 1
                    var day = d.getDate()
                    if(month < 10){
                        month = "0"+month
                    }
                    if(day < 10){
                        day = "0"+day
                    }
                    var date = d.getFullYear() + "-" + month + "-" + day
                    $('#estimateTransferDate').val(date)
                    $('#bank_id').val(data.bank_account.bank_id)
                }
            });
        });
        $('#selectBank').on('change', function() {
            var url = '{{ route("get_bank_account_from_payment_modal", ":id") }}';
            url = url.replace(':id', this.value);
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
                    $('#bankDesc').html(`
                        <span><b>${data.bank.name}</b> ${data.name} (${data.account_number})</span>
                    `)
                    $('#chargePercentageBank').val(data.charge_percentage)
                    if($("#selectInstallment").val() == ''){
                        const d = new Date($("#add_payment_date").val());
                        d.setDate(d.getDate() + data.estimate_transfer);
                        var month = d.getMonth() + 1
                        var day = d.getDate()
                        if(month < 10){
                            month = "0"+month
                        }
                        if(day < 10){
                            day = "0"+day
                        }
                        var date = d.getFullYear() + "-" + month + "-" + day
                        $('#estimateTransferDate').val(date)
                    }
                    $('#bank_id').val(data.bank_id)
                }
            });
        });

        // edit
        $(".editPayment-select_with_select2").select2({
            theme: 'bootstrap4',
            placeholder: '-- select first --',
            dropdownParent: $('#editPaymentModal .modal-content')
        })

        function infoFromCreditCard(id) {
            var url = '{{ route("get_credit_card", ":id") }}';
            url = url.replace(':id', id);
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
                    $('#editPayment-credit_card_name').html(`<span>${data.name}</span>`)
                    $('#editPayment-bank_description').html(`
                       <span><b>${data.bank_account.bank.name}</b> ${data.bank_account.name} (${data.bank_account.account_number})</span>
                    `)
                }
            });
        }
        function infoFromBankAccount(id) {
            var url = '{{ route("get_bank_account_from_payment_modal", ":id") }}';
            url = url.replace(':id', id);
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
                    $('#editPayment-bank_description').html(`
                       <span><b>${data.bank.name}</b> ${data.name} (${data.account_number})</span>
                    `)
                }
            });
        }
        $('#frmAddPayment, #frmEditPayment').bind('submit', function () {
            $('.installment-form, .editPayment-installment_form').prop({'disabled' : false, 'readonly' : false})
        });

        $('#editPayment-select_type_payment').on('change', function() {
            if(this.value == 'card installment' || this.value == 'card'){
                $('.editPayment-installment_form').prop({'disabled' : false, 'readonly' : false})
            }else{
                $('.editPayment-installment_form').prop({'disabled' : true, 'readonly' : true})
                $('#editPayment-select_installment').val('').trigger('change')
                $('#editPayment-credit_card_name').html('')
                $('#editPayment-charge_percentage_company').val(0)
                $('#editPayment-credit_card_installment').val(1)
                if($('#editPayment-select_bank').val() == ''){
                    $('#editPayment-estimate_transfer_date').val('{{date("Y-m-d")}}')
                }
            }
        });

        $('#editPayment-select_installment').on('change', function() {
            if(this.value){
                var url = '{{ route("get_credit_card", ":id") }}';
                url = url.replace(':id', this.value);
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(data){
                        if($('#editPayment-select_installment').attr('data-info') !== 'first'){
                            $('#editPayment-credit_card_name').html(`<span>${data.name}</span>`)
                            $('#editPayment-credit_card_installment').val(data.cicilan)
                            $('#editPayment-select_bank').val(data.bank_account.id).trigger('change')
                            $('#editPayment-bank_description').html(`
                                <span><b>${data.bank_account.bank.name}</b> ${data.bank_account.name} (${data.bank_account.account_number})</span>
                            `)
                            $('#editPayment-charge_percentage_company').val(data.charge_percentage_company)
                            $('#editPayment-charge_percentage_bank').val(data.bank_account.charge_percentage)
                            const d = new Date($("#editPayment-payment_date").val());
                            d.setDate(d.getDate() + data.estimate_transfer);
                            var month = d.getMonth() + 1
                            var day = d.getDate()
                            if(month < 10){
                                month = "0"+month
                            }
                            if(day < 10){
                                day = "0"+day
                            }
                            var date = d.getFullYear() + "-" + month + "-" + day
                            $('#editPayment-estimate_transfer_date').val(date)
                            $('#editPayment-bank_id').val(data.bank_account.bank_id)
                        }
                        $('#editPayment-select_installment').attr('data-info', '')
                    }
                });
            }
        });

        $('#editPayment-select_bank').on('change', function() {
            if(this.value){
                var url = '{{ route("get_bank_account_from_payment_modal", ":id") }}';
                url = url.replace(':id', this.value);
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(data){
                        if($('#editPayment-select_bank').attr('data-info') !== 'first'){
                            $('#editPayment-bank_description').html(`
                                <span><b>${data.bank.name}</b> ${data.name} (${data.account_number})</span>
                            `)
                            $('#editPayment-charge_percentage_bank').val(data.charge_percentage)
                            if($("#editPayment-select_installment").val() == ''){
                                const d = new Date($("#editPayment-payment_date").val());
                                d.setDate(d.getDate() + data.estimate_transfer);
                                var month = d.getMonth() + 1
                                var day = d.getDate()
                                if(month < 10){
                                    month = "0"+month
                                }
                                if(day < 10){
                                    day = "0"+day
                                }
                                var date = d.getFullYear() + "-" + month + "-" + day
                                $('#editPayment-estimate_transfer_date').val(date)
                            }
                            $('#editPayment-bank_id').val(data.bank_id)
                        }
                        $('#editPayment-select_bank').attr('data-info', '')
                    }
                });
            }
        });
    });

    $('#commission_type_select').on('change', function() {
        $("#commission_type_description").val($(this).find(':selected').attr('data-description'))
        if({{$isUpgrade}} == 1){ // upgrade
            $("#commission_type_upgrade").val($(this).find(':selected').attr('data-nominal'))
            $("#commission_type_bonus").val(0)
        }else{ // bonus
            $("#commission_type_bonus").val($(this).find(':selected').attr('data-nominal'))
            $("#commission_type_upgrade").val(0)
        }
        $("#commission_type_smgt_nominal").val($(this).find(':selected').attr('data-smgt-nominal'))
        $("#commission_type_excess_price").val(0)
    });

    $('#edit_commission_type_select').on('change', function() {
        $("#edit_commission_type_description").val($(this).find(':selected').attr('data-description'))
        if({{$isUpgrade}} == 1){ // upgrade
            $("#edit_commission_type_upgrade").val($(this).find(':selected').attr('data-nominal'))
            $("#edit_commission_type_bonus").val(0)
        }else{ // bonus
            $("#edit_commission_type_bonus").val($(this).find(':selected').attr('data-nominal'))
            $("#edit_commission_type_upgrade").val(0)
        }
        $("#edit_commission_type_smgt_nominal").val($(this).find(':selected').attr('data-smgt-nominal'))
        $("#edit_commission_type_excess_price").val(0)
    });

    $('.btn-edit-order-commission').on('click', function(e) {
        var orderCommissionID = $(this).val()
        var url = '{{ route("edit_order_commission", ":id") }}';
        url = url.replace(':id', orderCommissionID);
        $.ajax({
            method: "get",
            url: url,
            success: function(data) {
                $('#order_commission_id').val(data.id)
                $('#edit_bonus_order_commission').val(parseInt(data.bonus).toLocaleString("en-US"))
                $('#edit_upgrade_order_commission').val(parseInt(data.upgrade).toLocaleString("en-US"))
                $('#edit_smgt_nominal_order_commission').val(parseInt(data.smgt_nominal).toLocaleString("en-US"))
                $('#edit_excess_price_order_commission').val(parseInt(data.excess_price).toLocaleString("en-US"))
                $('#editOrderCommissionModal').modal('show');
            },
            error: function(data) {
                alert("Error!!! please call IT");
            }
        });
    })

    $('#orderTakeawayYes').click(function () {
        $('#orderTakeawayYes').attr('checked', true)
        $('#orderTakeawayNo').attr('checked', false)
        window.location.href = "{{url()->current().'?code='.$order->code}}"+'&orderTakeaway=yes'
    })
    $('#orderTakeawayNo').click(function () {
        $('#orderTakeawayNo').attr('checked', true)
        $('#orderTakeawayYes').attr('checked', false)
        window.location.href = "{{url()->current().'?code='.$order->code}}"+'&orderTakeaway=no'
    })    
</script>
@endsection
