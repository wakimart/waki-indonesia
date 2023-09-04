<?php
$menu_item_page = "order";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="{{ asset("css/lib/select2/select2-bootstrap4.min.css") }}" />
<style type="text/css">
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
        background-color: rgba(255,255,255,0.6);
        cursor: pointer;
    }
    #intro {padding-top: 2em;}
    .validation {
        color: red;
        font-size: 9pt;
    }
    button {
        background: #1bb1dc;
        border: 0;
        border-radius: 3px;
        padding: 8px 30px;
        color: #fff;
        transition: 0.3s;
    }
    input, select, textarea {
        border-radius: 0 !important;
        box-shadow: none !important;
        border: 1px solid #dce1ec !important;
        font-size: 14px !important;
    }
    .select2-selection__rendered {
        line-height: 45px !important;
    }
    .select2-container .select2-selection--single {
        height: 45px !important;
    }
    
    .select2-container--bootstrap4 .select2-results__group {
        color: black;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Edit Order</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a data-toggle="collapse" href="#deliveryorder-dd" aria-expanded="false" aria-controls="deliveryorder-dd">Order</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Order</li>
                </ol>
            </nav>
        </div>
        <form id="actionUpdate" class="forms-sample" method="POST" action="{{ route('update_order') }}">
            <input type="hidden" name="user_code" value="{{Auth::user()->code}}">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="order-code">Order Code</label>
                                <input type="text" class="form-control" id="order-code" name="order_code" value="{{ $orders['code'] }}" readonly="">
                                <div class="validation"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <h4><strong>Data Customer</strong></h4>
                                <label for="">Customer Type</label>
                                @php $customer_types = ["VVIP (Type A)", "WAKi Customer (Type B)", "New Customer (Type C)"]; @endphp
                                <select id="customer_type"
                                    style="margin-top: 0.5em; height: auto;"
                                    class="form-control"
                                    name="customer_type"
                                    required>
                                    @foreach ($customer_types as $customer_type)
                                    <option value="{{ $customer_type }}"
                                        @if($customer_type == $orders['customer_type']) selected @endif>
                                        {{ $customer_type }}
                                    </option>
                                    @endforeach
                                    @if (!in_array($orders['customer_type'], $customer_types))
                                    <option value="{{ $orders['customer_type'] }}" selected>
                                        {{ $orders['customer_type'] }}
                                    </option>
                                    @endif
                                </select>
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="">No. Member (optional)</label>
                                <input type="number" class="form-control" id="no_member" name="no_member" value="{{ $orders['no_member'] }}">
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $orders['name'] }}">
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="">Phone Number</label>
                                <input type="number" class="form-control" id="phone" name="phone" value="{{ $orders['phone'] }}">
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="">Province</label>
                                <select class="form-control" id="province" name="province_id" data-msg="Mohon Pilih Provinsi" required>
                                    <option selected value="{{ $orders['district']['province_id'] }}">{{ $orders['district']['province'] }}</option>

                                    @php
                                        $result = RajaOngkir::FetchProvince();
                                        $result = $result['rajaongkir']['results'];
                                        $arrProvince = [];
                                        if(sizeof($result) > 0){
                                            foreach ($result as $value) {
                                                echo "<option value=\"" . $value['province_id'] . "\">" . $value['province'] . "</option>";
                                            }
                                        }
                                    @endphp
                                </select>
                                <div class="validation"></div>
                              </div>
                            <div class="form-group">
                                <label for="">City</label>
                                <select class="form-control" id="city" name="city" data-msg="Mohon Pilih Kota" required>
                                    @php
                                        if (isset($orders['district']['province_id'])) {
                                            $result = RajaOngkir::FetchCity($orders['district']['province_id']);
                                            $result = $result['rajaongkir']['results'];
                                            $arrCity = [];
                                            $arrCity[0] = "<option disabled value=\"\">Pilihan Kabupaten</option>";
                                            $arrCity[1] = "<option disabled value=\"\">Pilihan Kota</option>";
                                            if (sizeof($result) > 0) {
                                                foreach ($result as $value) {
                                                    $terpilihNya = "";
                                                    if (isset($orders['district']['city_id'])) {
                                                        if ($orders['district']['city_id'] == $value['city_id']) {
                                                            $terpilihNya = "selected";
                                                        }
                                                    }

                                                    if ($value['type'] == "Kabupaten") {
                                                        $arrCity[0] .= "<option value=\"".$value['city_id']."\"".$terpilihNya.">".$value['type']." ".$value['city_name']."</option>";
                                                    } else {
                                                        $arrCity[1] .= "<option value=\"".$value['city_id']."\"".$terpilihNya.">".$value['type']." ".$value['city_name']."</option>";
                                                    }
                                                }
                                                echo $arrCity[0];
                                                echo $arrCity[1];
                                            }
                                        }
                                    @endphp
                                </select>
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="">Sub District</label>
                                <select class="form-control" id="subDistrict" name="distric" data-msg="Mohon Pilih Kecamatan" required>
                                    <option selected value="{{$orders['district']['subdistrict_id']}}">{{$orders['district']['subdistrict_name']}}</option>
                                    @php
                                      if(isset($orders['district']['city_id'])){
                                        $result = RajaOngkir::FetchDistrict($orders['district']['city_id']);
                                        $result = $result['rajaongkir']['results'];
                                        if(sizeof($result) > 0){
                                          foreach ($result as $value) {
                                            $terpilihNya = "";
                                            if(isset($orders['district']['subdistrict_id'])){
                                              if($orders['district']['subdistrict_id'] == $value['subdistrict_id']){
                                                $terpilihNya = "selected";
                                              }
                                            }

                                            echo "<option value=\"".$value['subdistrict_id']."\"".$terpilihNya.">".$value['subdistrict_name']."</option>";
                                          }
                                        }
                                      }
                                    @endphp
                                </select>
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="exampleTextarea1">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="4">{{$orders['address']}}</textarea>
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="">Know From</label>
                                <select class="form-control" id="know_from" name="know_from" data-msg="Mohon Pilih Kecamatan" required>
                                    @foreach($from_know as $key=>$value)
                                        @if($value == $orders['know_from'])
                                            <option value="{{ $value }}" selected="true">{{ $value }}</option>
                                        @else
                                            <option value="{{ $value }}">{{ $value }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="validation"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <h4><strong>Product / Promo</strong></h4>
                                <label for="">CASH/UPGRADE</label>
                                <select class="form-control" id="cash_upgarde" name="cash_upgrade" data-msg="Mohon Pilih Tipe" required>
                                    <option selected disabled value="">Choose CASH/UPGRADE</option>

                                    @foreach ($cashUpgrades as $key => $cashUpgrade)
                                        @if ($orders['cash_upgrade'] == $key)
                                            <option value="{{ $key }}" selected>
                                                {{ strtoupper($cashUpgrade) }}
                                            </option>
                                        @else
                                            <option value="{{ $key }}">
                                                {{ strtoupper($cashUpgrade) }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="validation"></div>
                            </div>

                            @if ($orders['cash_upgrade'] == 1 || $orders['cash_upgrade'] == 2)
                            <div id="container-cashupgrade">
                                <?php
                                $ProductPromos = $orders->orderDetail;
                                $totalProduct = count($ProductPromos);

                                $total_product = -1;
                                ?>

                                @foreach ($orders->orderDetail as $orderDetail)
                                @if ($orderDetail->type == App\OrderDetail::$Type['1'])
                                    <?php
                                    $total_product++;
                                    ?>
                                    <input type="hidden" name="productold_{{ $total_product }}" value="{{ $orderDetail['id'] }}">
                                    <input type="hidden" id="promoprice_{{$total_product}}" value="{{$orderDetail->product->price ?? $orderDetail->promo->price ?? 0 }}">

                                    <div id="product_parent_{{ $total_product }}">
                                    {{-- ++++++++++++++ Product ++++++++++++++ --}}
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <input type="hidden" id="orderdetail_{{ $total_product }}" name="orderdetailold[]" value="{{ $orderDetail['id'] }}">
                                                <select class="form-control pilihan-product"
                                                    id="product_{{ $total_product }}"
                                                    name="product_{{ $total_product }}"
                                                    data-msg="Mohon Pilih Product"
                                                    onload="selectOther(this)"
                                                    onchange="selectOther(this)"
                                                    data-sequence="{{ $total_product }}"
                                                    required>
                                                    <option disabled value="">
                                                        Choose Product
                                                    </option>

                                                    <optgroup label="Promo">
                                                        <?php foreach ($promos as $key => $promo): ?>
                                                            <option value="promo_<?php echo $promo["id"]; ?>"
                                                                <?php
                                                                if (
                                                                    $orderDetail->promo_id
                                                                    && $promo["id"] == $orderDetail->promo_id
                                                                ) {
                                                                    echo "selected";
                                                                }
                                                                ?>
                                                                >
                                                                <?php
                                                                echo $promo->code
                                                                    . " - ("
                                                                    . implode(", ", $promo->productName())
                                                                    . ") - Rp. "
                                                                    . number_format($promo->price);
                                                                ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </optgroup>

                                                    <optgroup label="Product">
                                                        @foreach($products as $product)
                                                        <option value="product_{{ $product->id }}"
                                                            @if($orderDetail->product_id && $product['id'] == $orderDetail->product_id) selected @endif>
                                                            {{ $product->code }} 
                                                            - ({{ $product->name }}) 
                                                            - Rp {{ number_format($product->price) }}
                                                        </option>
                                                        @endforeach
                                                    </optgroup>    

                                                    <option value="other" <?php echo $orderDetail->other ? "selected" : "";?>>
                                                        OTHER
                                                    </option>
                                                </select>
                                                <div class="validation"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="number"
                                                    class="form-control"
                                                    name="qty_{{ $total_product }}"
                                                    id="qty_{{ $total_product }}"
                                                    data-sequence="{{ $total_product }}"
                                                    placeholder="Qty"
                                                    value="{{ $orderDetail['qty'] }}"
                                                    required
                                                    min="1"
                                                    oninput="selectQty(this)"
                                                    data-msg="Mohon Isi Jumlah Product" />
                                                <div class="validation"></div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($total_product == 0)
                                        <div class="row">
                                            <div class="col-md-12 text-right"
                                                style="margin-bottom: 1em;">
                                                <button id="tambah_product"
                                                    title="Tambah Product"
                                                    style="padding: 0.4em 0.7em;">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-md-12 text-right"
                                                style="margin-bottom: 1em;">
                                                <button class="hapus_product"
                                                    value="{{ $total_product }}"
                                                    title="Hapus Product"
                                                    style="padding: 0.4em 0.7em; background-color: red;">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($orderDetail->other)
                                        <div class="form-group"
                                            id="product_other_container_{{ $total_product }}">
                                            <input type="text"
                                                class="form-control"
                                                id="product_other_{{ $total_product }}"
                                                name="product_other_{{ $total_product }}"
                                                placeholder="Product Name"
                                                data-msg="Please fill in the product"
                                                value="{{ $orderDetail->other }}" />
                                            <div class="validation"></div>
                                        </div>
                                    @else
                                        <div class="form-group d-none" id="product_other_container_{{ $total_product }}">
                                            <input type="text"
                                                class="form-control"
                                                id="product_other_{{ $total_product }}"
                                                name="product_other_{{ $total_product }}"
                                                placeholder="Product Name"
                                                data-msg="Please fill in the product" />
                                            <div class="validation"></div>
                                        </div>
                                    @endif
                                    </div>
                                @endif
                                @endforeach
                                <div id="tambahan_product"></div>
                                {{-- ++++++++++++++ ======== ++++++++++++++ --}}

                                @if($orders['cash_upgrade'] == 2)
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label for="">Old Product</label>
                                            <select class="form-control"
                                                id="old_product"
                                                name="old_product"
                                                data-msg="Mohon Pilih Produk Lama">
                                                <option selected disabled value="">
                                                    Choose Old Product
                                                </option>

                                                @foreach($products as $product)
                                                <option value="{{ $product->id }}"
                                                    @if($orderDetails['upgrade']['product_id'] && $product->id == $orderDetails['upgrade']['product_id']) selected @endif>
                                                    {{ $product->code }} 
                                                    - ({{ $product->name }}) 
                                                    - Rp {{ number_format($product->price) }}
                                                </option>
                                                @endforeach

                                                <option value="other" 
                                                    @if($orderDetails['upgrade']['other']) selected @endif>
                                                    OTHER
                                                </option>
                                            </select>
                                            <div class="validation"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Qty Old Product</label>
                                            <input type="number"
                                                class="form-control"
                                                name="old_product_qty"
                                                id="old_product_qty"
                                                placeholder="Qty"
                                                value="{{ $orderDetails['upgrade']['qty'] ?? '' }}"
                                                data-msg="Mohon Isi Jumlah Old Product" />
                                            <div class="validation"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" 
                                                @if ($orderDetails['upgrade']['other'] == null) style="display: none;" @endif
                                                class="form-control"
                                                name="old_product_other"
                                                id="old_product_other"
                                                placeholder="Old Product Name"
                                                data-msg="Mohon Isi Produk Lama"
                                                value="{{ $orderDetails['upgrade']['other'] }}"
                                                style="text-transform:uppercase;" />
                                            <div class="validation"></div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label for="">Prize Product</label>
                                            <select class="form-control"
                                                id="prize"
                                                name="prize"
                                                data-msg="Mohon Pilih Prize Produk">
                                                <option selected disabled value="">
                                                    Choose Prize Product
                                                </option>

                                                @foreach($products as $product)
                                                <option value="{{ $product->id }}"
                                                    @if($orderDetails['prize']['product_id'] && $product->id == $orderDetails['prize']['product_id']) selected @endif>
                                                    {{ $product->code }} 
                                                    - ({{ $product->name }}) 
                                                    - Rp {{ number_format($product->price) }}
                                                </option>
                                                @endforeach

                                                <option value="other" @If($orderDetails['prize']['other']) selected @endif>
                                                    OTHER
                                                </option>
                                            </select>
                                            <div class="validation"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="">Qty Prize Product</label>
                                            <input type="number"
                                                class="form-control"
                                                name="prize_qty"
                                                id="prize_qty"
                                                placeholder="Qty"
                                                value="{{ $orderDetails['prize']['qty'] ?? '' }}"
                                                data-msg="Mohon Isi Jumlah Prize" />
                                            <div class="validation"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="text" 
                                                @If($orderDetails['prize']['other'] == null) style="display: none" @endif
                                                class="form-control"
                                                name="prize_other"
                                                id="prize_other"
                                                placeholder="Prize Product Name"
                                                data-msg="Mohon Isi Hadiah"
                                                value="{{ $orderDetails['prize']['other'] ?? '' }}"
                                                style="text-transform: uppercase;" />
                                            <div class="validation"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <h4><strong>Branch & CSO</strong></h4>
                                <label for="">Branch</label>
                                <select class="form-control" id="branch" name="branch_id" data-msg="Mohon Pilih Cabang" required>
                                    <option selected disabled value="">Choose Branch</option>

                                    @foreach($branches as $branch)
                                        @if($orders['branch_id'] == $branch['id'])
                                            <option value="{{ $branch['id'] }}" selected="true">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
                                        @else
                                            <option value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="validation"></div>
                            </div>

                            @if($orders['branch_id'] != null)
                            <div id="container-Cabang">
                                <div class="form-group">
                                    <label for="">CSO Code</label>
                                    <input type="text" class="form-control cso" name="cso_id" id="cso" value="{{$orders->cso['code']}}" required data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase" {{ Auth::user()->roles[0]['slug'] == 'cso' ? "readonly=\"\"" : "" }} />
                                    <input type="hidden" class="csoId" name="idCSO" value="">
                                    <div class="validation"></div>
                                </div>
                                <div class="form-group">
                                    <label for="">CSO Code 30%</label>
                                    <input type="text" class="form-control cso" required name="30_cso_id" id="30_cso" value="{{$orders->cso_id_30['code']}}" data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase"/>
                                    <input type="hidden" class="csoId" name="idCSO30" value="">
                                    <div class="validation"></div>
                                </div>
                                <div class="form-group">
                                    <label for="">CSO Code 70%</label>
                                    <input type="text" class="form-control cso" required name="70_cso_id" id="70_cso" value="{{$orders->cso_id_70['code']}}" data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase"/>
                                    <input type="hidden" class="csoId" name="idCSO70" value="">
                                    <div class="validation"></div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="form-group">
                                    <h4><strong>Payment</strong></h4>
                                    <label for="">Total Price</label>
                                    <input type="text" class="form-control" name="total_payment" id="total_payment" value="{{number_format($orders->total_payment,2)}}" required data-type="currency" data-msg="Mohon Isi Total Harga" style="text-transform:uppercase"/>
                                    <div class="validation"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Payment Method</label>
                                <select class="form-control" id="payment_type" name="payment_type" data-msg="Mohon Pilih Tipe" required>
                                    <option selected disabled value="">Choose Payment Method</option>

                                    @foreach($paymentTypes as $key=>$paymentType)
                                        @if($orders['payment_type'] == $key)
                                            <option value="{{ $key }}" selected="true">{{ strtoupper($paymentType) }}</option>
                                        @else
                                            <option value="{{ $key }}">{{ strtoupper($paymentType) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="validation"></div>
                            </div>

                            @if($orders['payment_type'] == 1 || $orders['payment_type'] == 2)
                            <div id="container-jenispembayaran">
                                {{-- ++++++++ BANK ++++++++ --}}
                                @php $indexPayment = 0; @endphp
                                @foreach($orders->orderPayment as $payment)
                                <input type="hidden" name="bankold_{{ $indexPayment }}" value="{{ $payment['id'] }}">
                                <div class="p-3 mb-2" style="border: 1px solid black" id="bank_{{ $indexPayment }}">
                                    <input type="hidden" name="orderpaymentold[]" value={{ $payment['id'] }}>
                                    <input type="hidden" name="oldorderpaymentamount[]" value="{{$payment['total_payment']}}">
                                    <input type="hidden" name="oldorderpaymentbankid[]" value="{{$payment['bank_id']}}">
                                    <div class="form-group">
                                        <label for="">Payment Type {{ ($indexPayment) != 0 ? ($indexPayment + 1) : '' }}</label>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-8">
                                            <select class="form-control bank_name" name="bank_{{ $indexPayment }}" data-msg="Mohon Pilih Bank">
                                                <option selected disabled value="">Choose Bank</option>

                                                @foreach($banks as $bank)
                                                    <option value="{{ $bank->id }}"
                                                        @if($payment['bank_id'] == $bank['id']) selected @endif>
                                                        {{ $bank->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="validation"></div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <select class="form-control bank_cicilan" name="cicilan_{{ $indexPayment }}" data-msg="Mohon Pilih Jumlah Cicilan">
                                                <option selected value="1">1X</option>
                                                @for($i=2; $i<=12;$i+=2)
                                                    <option class="other_valCicilan" value="{{ $i }}" 
                                                    @if($payment['cicilan'] == $i) selected @endif>
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
                                            <div class="validation"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Total Payment</label>
                                        <input type="text"
                                            class="form-control downpayment"
                                            name="downpayment_{{ $indexPayment }}"
                                            placeholder="Total Payment"
                                            required
                                            data-type="currency"
                                            data-msg="Mohon Isi Total Pembayaran"
                                            value="{{ number_format($payment->total_payment, 2) }}"
                                            style="text-transform: uppercase;" />
                                        <div class="validation"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <label>Bukti Pembayaran</label>
                                            <span style="float: right;">min. 1 picture</span>
                                        </div>
                                        @php $arrPaymentImage = json_decode($payment['image'], true); @endphp
                                        @for ($i = 0; $i < 3; $i++)
                                            <div class="col-xs-12 col-sm-6 col-md-4 form-group imgUp"
                                                style="padding: 15px; float: left;">
                                                <label>Image {{ $i + 1 }}</label>
                                                @if (!empty($arrPaymentImage[$i]))
                                                    <div class="imagePreview"
                                                        style="background-image: url({{ asset("sources/order/" . $arrPaymentImage[$i]) }});"></div>
                                                @else
                                                    <div class="imagePreview"
                                                        style="background-image: url({{ asset('sources/dashboard/no-img-banner.jpg') }});"></div>
                                                @endif
                                                <label class="file-upload-browse btn btn-gradient-primary"
                                                    style="margin-top: 15px;">
                                                    Upload
                                                    <input name="images_{{ $indexPayment }}_{{ $i }}"
                                                        id="gambars-{{ $indexPayment }}-{{ $i }}"
                                                        type="file"
                                                        accept=".jpg,.jpeg,.png"
                                                        class="uploadFile img"
                                                        value="Upload Photo"
                                                        style="width: 0px; height: 0px; overflow: hidden;" />
                                                </label>
                                                <i class="mdi mdi-window-close del"></i>
                                            </div>
                                        @endfor
                                        <div class="validation"></div>
                                    </div>
                                    <div class="text-center">
                                        @If ($indexPayment == 0)
                                        <button id="tambah_bank"
                                            title="Tambah Bank"
                                            type="button">
                                            <i class="mdi mdi-plus"></i> Add Payment Type
                                        </button>
                                        @else
                                        <button style="padding: 0.4em 0.7em; background-color: red"
                                            class="hapus_bank"
                                            title="Hapus Bank"
                                            value="{{ $indexPayment }}"
                                            type="button">
                                            <i class="mdi mdi-minus"></i> Delete Payment Type
                                        </button>
                                        @endif
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                @php $indexPayment++; @endphp
                                @endforeach

                                <div id="tambahan_bank"></div>
                                <div class="form-group">
                                    <label for="">Remaining Payment</label>
                                    <input type="text" class="form-control" name="remaining_payment" id="remaining_payment" value="{{number_format($orders['remaining_payment'])}}" required readonly data-type="currency" data-msg="Mohon Isi Sisa Pembayaran" style="text-transform:uppercase"/>
                                    <div class="validation"></div>
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea class="form-control" name="description" rows="5" data-msg="Mohon Isi Keterangan">{{$orders['description']}}</textarea>
                                <div class="validation"></div>
                            </div>

                            <div id="errormessage"></div>

                            <div class="form-group">
                                <input type="hidden" id="fixed_payment" value="{{$orders['total_payment']}}">
                                <input type="hidden" name="idOrder" value="{{$orders['id']}}">
                                <input type="hidden" id="lastTotalProduct" value="{{$totalProduct}}">
                                <button id="updateOrder" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
                                <button class="btn btn-light">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
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
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
<script type="application/javascript">
    // callback for find waki-indonesia 0ffline (local or ngrok)
var urlOffline = "http://localhost:8001"
$.ajax({
    url:'https://waki-indonesia-offline.office/cms-admin/login',
    error: function(){
        urlOffline = "http://localhost:8001"
    },
    success: function(){
        urlOffline = "http://localhost:8001"
    }
});
let promoOption = `<option selected disabled value="">Choose Product</option>`;
let quantityOption = "";

document.addEventListener("DOMContentLoaded", function () {
    $("#old_product, #prize").select2({
        theme: "bootstrap4",
    });

    // Untuk Tambah Product
    promoOption+=`<optgroup label="Promo">`;
    @foreach ($promos as $promo)
        promoOption += `<option value="promo_{{ $promo->id }}">{{ $promo->code . " - (" . implode(", ", $promo->productName()) . ") - Rp. " . number_format($promo->price) }}</option>`;
    @endforeach
    promoOption+=`</optgroup>`;

    promoOption+=`<optgroup label="Product">`;
    @foreach ($products as $product)
        promoOption += `<option value="product_{{ $product->id }}">{{ $product->code . " - (" . $product->name . ") - Rp. " . number_format($product->price) }}</option>`;
    @endforeach
    promoOption+="</optgroup>";

    promoOption += `<option value="other">OTHER</option>`;

    // const URL = '<?php echo route("fetch_promo_dropdown"); ?>';

    // fetch(
    //     URL,
    //     {
    //         method: "GET",
    //      headers: {
    //          "Accept": "application/json",
    //      },
    //     }
    // ).then(function (response) {
    //  if (!response.ok) {
    //      throw new Error(`HTTP error! status: ${response.status}`);
    //  }

    //  return response.json();
    // }).then(function (response) {
    //  const dataPromo = response.data;

    //     for (const promo in dataPromo) {
    //         promoOption += `<option value="${promo}">${dataPromo[promo].product}</option>`;
    //     }

    //     promoOption += `<option value="other">OTHER</option>`;
    // }).catch(function (error) {
    //  console.error(error);
    // });

    for (let i = 1; i <= 10; i++) {
        quantityOption += `<option value="${i}">${i}</option>`;
    }
}, false);
</script>
<script type="application/javascript">
    const deleted_img = [];
    $(document).ready(function() {
        var frmUpdate;

        $("#actionUpdate").on("submit", function (e) {
            e.preventDefault();

            frmUpdate = _("actionUpdate");
            frmUpdate = new FormData(document.getElementById("actionUpdate"));
            frmUpdate.enctype = "multipart/form-data";

            // frmUpdate.append('total_images', 3);
            deleted_img.forEach((element) => {
                frmUpdate.append('dltimg-' + element, element);
            });

            // Change numberWithComma before submit
            $('input[data-type="currency"]').each(function() {
                var frmName = $(this).attr('name');
                frmUpdate.set(frmName, numberNoCommas(frmUpdate.get(frmName)));
            });

            var URLNya = $("#actionUpdate").attr('action');
            var ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.addEventListener("load", completeHandler, false);
            ajax.addEventListener("error", errorHandler, false);
            ajax.open("POST", URLNya);
            ajax.setRequestHeader("X-CSRF-TOKEN",$('meta[name="csrf-token"]').attr('content'));
            ajax.send(frmUpdate);

            testNetwork(networkValue, function(val){                
                $.ajax({
                    method: "post",
                    url: `${urlOffline}/api/update-order-data`,
                    data: frmUpdate,
                    processData: false,
                    contentType: false,
                    success: function(res){   
                        if(res.status == 'success'){
                            alert("Input Success !!!");
                            var url = "{{ route('detail_order', ['code'=>$orders['code']])}}";
                            window.location.href = url;
                        }else{
                            var modal = `                
                                <div class="modal-body">
                                    <h5 class="modal-title text-center">${res.status}</h5>
                                    <hr>
                                    <p class="text-center">${res.message}</p>
                                </div>                
                            `
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
                        $('#error-modal-desc').html(modal)
                        $('#error-modal').modal("show")
                    }
                    
                })
            })
            return false      
        });

        function progressHandler(event){
            document.getElementById("updateOrder").innerHTML = "UPLOADING...";
        }

        function completeHandler(event){
            var hasil = JSON.parse(event.target.responseText);

            for (var key of frmUpdate.keys()) {
                $("#actionUpdate").find("input[name="+key.name+"]").removeClass("is-invalid");
                $("#actionUpdate").find("select[name="+key.name+"]").removeClass("is-invalid");
                $("#actionUpdate").find("textarea[name="+key.name+"]").removeClass("is-invalid");

                $("#actionUpdate").find("input[name="+key.name+"]").next().find("strong").text("");
                $("#actionUpdate").find("select[name="+key.name+"]").next().find("strong").text("");
                $("#actionUpdate").find("textarea[name="+key.name+"]").next().find("strong").text("");
            }

            if(hasil['errors'] != null){
                for (var key of frmUpdate.keys()) {
                    if(typeof hasil['errors'][key] === 'undefined') {

                    }
                    else {
                        $("#actionUpdate").find("input[name="+key+"]").addClass("is-invalid");
                        $("#actionUpdate").find("select[name="+key+"]").addClass("is-invalid");
                        $("#actionUpdate").find("textarea[name="+key+"]").addClass("is-invalid");

                        $("#actionUpdate").find("input[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
                        $("#actionUpdate").find("select[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
                        $("#actionUpdate").find("textarea[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
                    }
                }
                alert("Input Error !!!");
            }
            else{
                // alert("Input Success !!!");
                var url = "{{ route('detail_order', ['code'=>$orders['code']])}}";
                // window.location.href = url;
                //window.location.reload()
            }

            document.getElementById("updateOrder").innerHTML = "SAVE";
        }

        function errorHandler(event){
            document.getElementById("updateOrder").innerHTML = "SAVE";
        }

        $("#province").on("change", function(){
            var id = $(this).val();
            $( "#city" ).html("");
            $( "#subDistrict" ).html("");
            $( "#subDistrict" ).html("<option selected disabled value=\"\">Pilihan Kecamatan</option>");
            $.get( '{{ route("fetchCity", ['province' => ""]) }}/'+id )
            .done(function( result ) {
                result = result['rajaongkir']['results'];
                var arrCity = "<option selected disabled value=\"\">Pilihan Kota</option>";
                if(result.length > 0){
                    $.each( result, function( key, value ) {
                        if(value['type'] == "Kabupaten"){
                            arrCity += "<option value=\""+value['city_id']+"\">Kabupaten "+value['city_name']+"</option>";
                        }

                        if(value['type'] == "Kota"){
                            arrCity += "<option value=\""+value['city_id']+"\">Kota "+value['city_name']+"</option>";
                        }


                    });
                    $( "#city" ).append(arrCity);
                }
            });
        });

        $("#city").on("change", function(){
            var id = $(this).val();
            $( "#subDistrict" ).html("");
            $.get( '{{ route("fetchDistrict", ['city' => ""]) }}/'+id )
            .done(function( result ) {
                result = result['rajaongkir']['results'];
                console.log(result);
                var arrSubDistsrict = "<option selected disabled value=\"\">Pilihan Kecamatan</option>";
                if(result.length > 0){
                    $.each( result, function( key, value ) {
                        arrSubDistsrict += "<option value=\""+value['subdistrict_id']+"\">"+value['subdistrict_name']+"</option>";
                    });
                    $( "#subDistrict" ).append(arrSubDistsrict);
                }
            });
        });

        $(document).on("click", "i.del", function () {
            $(this).closest(".imgUp").find('.imagePreview').css("background-image", "");
            $(this).closest(".imgUp").find('input[type=text]').removeAttr("required");
            $(this).closest(".imgUp").find('.btn').find('.img').val("");
            $(this).closest(".imgUp").find('.form-control').val("");
            const inputImage = $(this).closest(".imgUp").find(".img");
            if (inputImage.attr("id").split("-")[2] == "0") {
                inputImage.attr("required", "");
            }
            deleted_img.push(inputImage.attr('id').substring(8));
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
            $.ajax({
                method: "post",
                url: `${urlOffline}/api/end-point-for-check-status-network`,
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
    });
</script>
<script type="application/javascript">
    var total_bank = "{{ $indexPayment-1 }}";
    var total_product = "{{ $total_product }}";
    var count = 0;
    var arrBooleanCso = ['false', 'false', 'false'];

    var temp_total_price = parseInt($('#fixed_payment').val()); //buat temporary price lama
    var total_price = parseInt($('#fixed_payment').val());
    var arr_index_temp = [];

    $(document).ready(function () {  
        $(document).on("input", 'input[data-type="currency"]', function() {
            $(this).val(numberWithCommas($(this).val()));
        });

        console.log(total_price);

        for (let i = 0; i <= total_product; i++) {
            $("#product_" + i).select2({
                theme: "bootstrap4",
            });

            //isi array_product dgn product lama
            var t_index = i.toString();
            var t_id_promo = $('#product_'+i).val();
            var t_price = parseInt($('#promoprice_'+i).val());
            var t_qty = $('#qty_'+i).val();

            arr_index_temp.push([t_index, t_id_promo, t_price, t_qty]);
        }

        console.log(arr_index_temp);

        $(".cso").on("input", function () {
            var txtCso = $(this).val();
            var temp = $(this);
            $.get( '{{route("fetchCso")}}', { cso_code: txtCso })
            .done(function( result ) {
                var bool = false;
                console.log(result)
                if (result.result == 'true'){
                    $(temp).parent().children('.validation').html('Kode CSO Benar');
                    $(temp).parent().children('.validation').css('color', 'green');
                    bool = true;
                    $(temp).parent().children('.csoId').val(result.data[0].id);
                }
                else{
                    $(temp).parent().children('.validation').html('Kode CSO Salah');
                    $(temp).parent().children('.validation').css('color', 'red');
                }
                if(temp.attr("id") == 'cso'){
                    arrBooleanCso[0] = bool;
                }
                else if(temp.attr("id") == '30_cso'){
                    arrBooleanCso[1] = bool;
                }
                else if(temp.attr("id") == '70_cso'){
                    arrBooleanCso[2] = bool;
                }
                console.log(arrBooleanCso[0]+" "+arrBooleanCso[1]+" "+arrBooleanCso[2]);
                if(arrBooleanCso[0] == true && arrBooleanCso[1] == true && arrBooleanCso[2] == true){
                    $('#submit').removeAttr('disabled');
                    console.log("masuk");
                }
                else{
                    $('#submit').attr('disabled',"");
                }
            });
        });

        $("#tambah_bank").click(function(e){
            e.preventDefault();
            total_bank++;
            strIsi = `
            <div class="p-3 mb-2" style="border: 1px solid black" id="bank_` + total_bank + `">
                <div class="form-group">
                    <label for="">Payment Type ` + (total_bank + 1) + `</label>
                </div>                                            
                <div class="row">
                    <div class="form-group col-md-8">
                        <select class="form-control bank_name"
                            name="bank_` + total_bank + `"
                            data-msg="Mohon Pilih Bank">
                            <option selected disabled value="">
                                Choose Bank
                            </option>

                            @foreach ($banks as $bank)
                                <option value="{{ $bank->id }}">
                                    {{ $bank->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="validation"></div>
                    </div>
                    <div class="form-group col-md-4">
                        <select class="form-control bank_cicilan"
                            name="cicilan_` + total_bank + `"
                            data-msg="Mohon Pilih Jumlah Cicilan">
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
                        <div class="validation"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Total Payment</label>
                    <input type="text"
                        class="form-control downpayment"
                        name="downpayment_` + total_bank + `"
                        placeholder="Total Payment"
                        required
                        data-type="currency"
                        data-msg="Mohon Isi Total Pembayaran"
                        style="text-transform: uppercase;" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <label>Bukti Pembayaran</label>
                        <span style="float: right;">min. 1 picture</span>
                    </div>
                    @for ($i = 0; $i < 3; $i++)
                        <div class="col-xs-12 col-sm-6 col-md-4 form-group imgUp"
                            style="padding: 15px; float: left;">
                            <label>Image {{ $i + 1 }}</label>
                            <div class="imagePreview"
                                style="background-image: url({{ asset('sources/dashboard/no-img-banner.jpg') }});">
                            </div>
                            <label class="file-upload-browse btn btn-gradient-primary"
                                style="margin-top: 15px;">
                                Upload
                                <input name="images_` + total_bank + `_{{ $i }}"
                                    id="productimg-{{ $i }}"
                                    type="file"
                                    accept=".jpg,.jpeg,.png"
                                    class="uploadFile img"
                                    value="Upload Photo"
                                    style="width: 0px; height: 0px; overflow: hidden;"
                                    {{ $i === 0 ? "required" : "" }} />
                            </label>
                            <i class="mdi mdi-window-close del"></i>
                        </div>
                    @endfor
                    <div class="validation"></div>
                </div>
                <div class="text-center">
                    <button style="padding: 0.4em 0.7em; background-color: red"
                        class="hapus_bank"
                        title="Hapus Bank"
                        value="` + total_bank + `"
                        type="button">
                        <i class="mdi mdi-minus"></i> Delete Payment Type
                    </button>
                </div>
                <div class="clearfix"></div>
            </div>`;
            $('#tambahan_bank').append(strIsi);


            if($("#payment_type").val() == 1){
                $(".other_valCicilan").attr('disabled', "");
                $(".other_valCicilan").hide();
            }
            else{
                $(".other_valCicilan").removeAttr('disabled');
                $(".other_valCicilan").show();
            }
        });

        $(document).on("click",".hapus_bank", function(e){
            e.preventDefault();
            // total_bank--;
            $('#bank_'+$(this).val()).remove();
            $('#cicilan_'+$(this).val()).remove();
            $(this).remove();
            checkRemainingPayment();
        });

        $("#tambah_product").click(function (e) {
            e.preventDefault();
            total_product++;
            count = total_product + 1;

            const newDivProduct = document.createElement("div");
            newDivProduct.className = "form-group";
            // newDivProduct.style = "width: 74%; float: left; display: inline-block;";

            const newSelectProduct = document.createElement("select");
            newSelectProduct.id = `product_${total_product}`;
            newSelectProduct.className = "form-control pilihan-product";
            newSelectProduct.name = `product_${total_product}`;
            newSelectProduct.required = true;
            newSelectProduct.innerHTML = promoOption;
            newSelectProduct.setAttribute("onchange", "selectOther(this)");
            newSelectProduct.setAttribute("data-sequence", total_product);

            const newDivQty = document.createElement("div");
            newDivQty.className = "form-group";
            // newDivQty.style = "width: 14%; float: right; display: inline-block;";

            const newSelectQty = document.createElement("input");
            newSelectQty.type = "number";
            newSelectQty.className = "form-control";
            newSelectQty.id = `qty_${total_product}`;
            newSelectQty.name = `qty_${total_product}`;
            newSelectQty.required = true;
            newSelectQty.value = "1";
            newSelectQty.min = "1";
            // newSelectQty.innerHTML = quantityOption;
            newSelectQty.setAttribute("oninput", "selectQty(this)");
            newSelectQty.setAttribute("data-sequence", total_product);

            const newDivRemove = document.createElement("div");
            // newDivRemove.className = "col-md-12";
            newDivRemove.style = "margin-bottom: 1em; display:flex; justify-content: flex-end; padding: 0;";

            const newButtonRemove = document.createElement("button");
            newButtonRemove.className = "hapus_product";
            newButtonRemove.value = total_product;
            newButtonRemove.title = "Kurangi Produk";
            newButtonRemove.style = "padding: 0.4em 0.7em; background-color: red;";
            newButtonRemove.innerHTML = '<i class="fas fa-minus"></i>';

            const newDivOther = document.createElement("div");
            newDivOther.id = `product_other_container_${total_product}`;
            newDivOther.className = "col-md-12 form-group d-none";

            const newInputOther = document.createElement("input");
            newInputOther.id = `product_other_${total_product}`;
            newInputOther.type = "text";
            newInputOther.className = "form-control";
            newInputOther.name = `product_other_${total_product}`;
            newInputOther.placeholder = "Product Name";

            const newDivCol9 = document.createElement("div");
            newDivCol9.className = "col-md-9 form-group";

            const newDivCol3 = document.createElement("div");
            newDivCol3.className = "col-md-3 form-group";

            const newDivCol12Qty = document.createElement("div");
            newDivCol12Qty.className = "col-md-12 form-group text-right";

            newDivCol9.appendChild(newDivProduct.appendChild(newSelectProduct));
            newDivCol3.appendChild(newDivQty.appendChild(newSelectQty));
            newDivCol12Qty.appendChild(newDivRemove.appendChild(newButtonRemove));
            newDivOther.appendChild(newInputOther);
            
            const newDivParentProduct = document.createElement("div");
            newDivParentProduct.className = "row";
            newDivParentProduct.id = `product_parent_${total_product}`;

            newDivParentProduct.appendChild(newDivCol9);
            newDivParentProduct.appendChild(newDivCol3);
            newDivParentProduct.appendChild(newDivCol12Qty);
            newDivParentProduct.appendChild(newDivOther);

            // document.getElementById("tambahan_product").appendChild(newDivProduct);
            // document.getElementById("tambahan_product").appendChild(newDivQty);
            // document.getElementById("tambahan_product").appendChild(newDivRemove);
            // document.getElementById("tambahan_product").appendChild(newDivOther);
            document.getElementById("tambahan_product").appendChild(newDivParentProduct);

            $("#product_" + total_product).select2({
                theme: "bootstrap4",
            });
        });

        $(document).on("click",".hapus_product", function(e){
            e.preventDefault();
            $('#product_parent_'+$(this).val()).remove();
            // total_product--;
            // $('#product_'+$(this).val()).remove();
            // $('#qty_'+$(this).val()).remove();
            // $(this).remove();

            //kurangi total price
            for (var i = 0; i < arr_index_temp.length; i++) {
                if(arr_index_temp[i][0] == $(this).val()){
                    var min_price = parseInt(arr_index_temp[i][2]);
                    var min_qty = parseInt(arr_index_temp[i][3]);

                    total_price = total_price - (min_price * min_qty);
                    $("#total_payment").val(numberWithCommas(total_price));
                    checkRemainingPayment();
                }
            }

            //remove dari array
            arr_index_temp.splice($(this).val(), 1);
        });

        $("#cash_upgarde").change( function(e){
            $("#container-cashupgrade").show();
            if($(this).val() == 2){
                $("#old_product").parent().show();
                $("#old_product").attr('required', "");
                $("#old_product_qty").parent().show();
                $("#old_product_qty").attr('required', "");
            }
            else{
                $("#old_product").parent().hide();
                $("#old_product").removeAttr('required');
                $("#old_product_qty").parent().hide();
                $("#old_product_qty").removeAttr('required');
            }
        });

        $(document).on("change", "#payment_type", function(e){
            $("#container-jenispembayaran").show();
            $(".other_valCicilan").parent().val('1');
            $('#tambahan_bank').html("");
            if($(this).val() == 1){
                $(".other_valCicilan").attr('disabled', "");
                $(".other_valCicilan").hide();
            }
            else{
                $(".other_valCicilan").removeAttr('disabled');
                $(".other_valCicilan").show();
            }
        });

         $("#branch").change( function(e){
            $("#container-Cabang").show();
        });

        $("#old_product, #prize").change(function() {
            element = $(this);
            if (element.val() == "other") {
                $("#" + element.attr('id') + "_other").show();
                $("#" + element.attr('id') + "_other").attr("required", "");
            } else {
                $("#" + element.attr('id') + "_other").hide();
                $("#" + element.attr('id') + "_other").removeAttr("required");
            }
        });
    });

    function checkProductArray(array, index){
        for (var i = 0; i < array.length; i++) {
            if(array[i][0] === index){
                return true;
            }
        }
        return false;
    }

    function selectOther(e) {
        const sequence = e.dataset.sequence;

        if (e.value === "other") {
            document.getElementById("product_other_container_" + sequence).classList.remove("d-none");
            document.getElementById("product_other_" + sequence).setAttribute("required", "");
            var get_qty = $('#qty_'+sequence).val();
            arr_index_temp.push([sequence, null, 0, get_qty]);
        } else if (e.value !== "other") {
            document.getElementById("product_other_container_" + sequence).classList.add("d-none");
            document.getElementById("product_other_" + sequence).removeAttribute("required");

            //auto fill price and qty
            var promo_id = e.value;
            var get_qty = $('#qty_'+sequence).val();
            $.get( '{{ route("fetchDetailPromo", ['promo' => ""]) }}/'+promo_id )
            .done(function (result){
                if(result.length > 0){
                    var data = JSON.parse(result);
                    var price = parseInt(data['price']);

                    if(arr_index_temp.length == 0){
                        arr_index_temp.push([sequence, promo_id, price, get_qty]);
                        total_price = total_price + (price * get_qty);
                    }else{
                        if(checkProductArray(arr_index_temp, sequence) == true){
                            //kurangi total price dengan harga lama
                            var old_price = parseInt(arr_index_temp[sequence][2]);
                            var old_qty = parseInt(arr_index_temp[sequence][3]);
                            total_price = total_price - (old_price * old_qty);

                            //simpan promo id yg baru & harga
                            arr_index_temp[sequence][1] = promo_id;
                            arr_index_temp[sequence][2] = price;
                            arr_index_temp[sequence][3] = get_qty;

                            //update total price yg baru
                            total_price = total_price + (price * get_qty);
                        }else{
                            //kalau ga exist, push ke array
                            arr_index_temp.push([sequence, promo_id, price, get_qty]);

                            //update total price
                            total_price = total_price + (price * get_qty);
                        }
                    }
                    console.log(arr_index_temp);
                    console.log(total_price);

                    $("#total_payment").val(numberWithCommas(total_price));
                    checkRemainingPayment();
                }
            });
        }
    }

    function selectQty(e){
        const sequence = e.dataset.sequence; //index select nya

        var get_qty = parseInt($('#qty_'+sequence).val());

        if (!get_qty) get_qty = 0;
        if(checkProductArray(arr_index_temp, sequence) == true){
            //kurangi price dengan harga lama
            var old_price = parseInt(arr_index_temp[sequence][2]);
            var old_qty = parseInt(arr_index_temp[sequence][3]);
            total_price = total_price - (old_price * old_qty);

            //simpan qty yg baru
            arr_index_temp[sequence][3] = get_qty;

            //update total price
            total_price = total_price + (old_price * get_qty);
            console.log(total_price);
            $("#total_payment").val(numberWithCommas(total_price));
            checkRemainingPayment();
        }

        console.log(arr_index_temp);

    }

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

    function checkRemainingPayment() {
        var total_downpayment = 0;
        $(".downpayment").each(function(i, obj) {
            total_downpayment += parseFloat(numberNoCommas($(this).val()))
        });
        var total_payment = parseFloat(numberNoCommas($("#total_payment").val()));
         if (total_downpayment > total_payment) {
            total_downpayment = 0;
            $(".downpayment").val('');
            alert("Total Payment cant be higher than the Total Price");
        }
        var remaining_payment = total_payment - total_downpayment;
        $("#remaining_payment").val(numberWithCommas(remaining_payment));
    }

    $(document).on("input", "#total_payment, .downpayment", function() {
        checkRemainingPayment();
    });
    $(document).on("change", "#total_payment, .downpayment", function() {
        var down_payment = parseFloat(numberNoCommas($(this).val()));
        if (down_payment == 0) {
            $(this).val("");
            alert("Total Payment cant be 0");
        }
    });
</script>
<script type="application/javascript" src="{{ asset('js/tags-input.js') }}"></script>
<script type="application/javascript">
    for (let input of document.querySelectorAll('#tags')) {
        tagsInput(input);
    }
</script>
@endsection
