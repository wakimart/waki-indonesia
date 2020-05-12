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

@if( $deliveryOrder['code'] != null)
    @if(Utils::$lang=='id')
    <section id="intro" class="clearfix">
        <div class="container">
            <div class="row justify-content-center">
                <h2>REGISTRASI BERHASIL</h2>
            </div>
            <div class="row justify-content-center">
                <table class="col-md-12">
                    <thead>
                        <td>Kode Pesanan</td>
                        <td>Tanggal Pesanan</td>
                    </thead>
                    <tr>
                        <td>{{ $deliveryOrder['code'] }}</td>
                        <td class="right">{{ date("d/m/Y H:i:s", strtotime($deliveryOrder['created_at'])) }}</td>
                    </tr>
                </table>
                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Data Pemesan</td>
                    </thead>
                    <tr>
                        <td>No. Member : </td>
                        <td>{{ $deliveryOrder['no_member'] }}</td>
                    </tr>
                    <tr>
                        <td>Nama : </td>
                        <td>{{ $deliveryOrder['name'] }}</td>
                    </tr>
                    <tr>
                        <td>No. Telp : </td>
                        <td>{{ $deliveryOrder['phone'] }}</td>
                    </tr>
                    <tr>
                        <td>Kota : </td>
                        <td>{{ $deliveryOrder['city'] }}</td>
                    </tr>
                    <tr>
                        <td>Alamat : </td>
                        <td>{{ $deliveryOrder['address'] }}</td>
                    </tr>
                    <tr>
                        <td>Cabang Pembelian : </td>
                        <td>{{  $deliveryOrder->branch['code'] }} - {{  $deliveryOrder->branch['name'] }}</td>
                    </tr>
                    <tr>
                        <td>Kode CSO : </td>
                        <td>{{ $deliveryOrder->cso['code'] }} - {{ $deliveryOrder->cso['name'] }}</td>
                    </tr>
                </table>
                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Detail Pesanan</td>
                    </thead>
                    <thead style="background-color: #80808012 !important">
                        <td>Jenis Barang</td>
                        <td>Jumlah</td>
                    </thead>

                    @foreach(json_decode($deliveryOrder['arr_product']) as $promo)
                        <tr>
                            {{-- khusus Philipin --}}
                            @if(is_numeric($promo->id))
                                <td>{{ App\DeliveryOrder::$Promo[$promo->id]['code'] }} - {{ App\DeliveryOrder::$Promo[$promo->id]['name'] }} ( {{ App\DeliveryOrder::$Promo[$promo->id]['harga'] }} )</td>
                            @else
                                <td>{{ $promo->id }}</td>
                            @endif
                            <td>{{ $promo->qty }}</td>
                        </tr>
                    @endforeach
                </table>

                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Syarat dan Ketentuan</td>
                    </thead>
                    <tr>
                        <td>
                            <p class="pInTable">1. Form registrasi ini hanya berlaku selama 1 bulan setelah form ini diterbitkan.</p>
                            <p class="pInTable">2. Selain harga tersebut diatas, tidak ada perjanjian diluar surat pesanan ini.</p>
                            <p class="pInTable">3. Selepas pembatalan, uang muka registerasi ini dapat ditarik kembali dalam 7 hari kerja.</p>
                            <p class="pInTable">4. WAKi berhak merubah syarat dan ketentuan tanpa memberikan notis.</p>
                        </td>
                    </tr>
                </table>

                <a href="whatsapp://send?text={{ Route('successorder') }}?code={{ $deliveryOrder['code'] }}" data-action="share/whatsapp/share">Bagikan melalui Whatsapp</a>
            </div>
        </div>
    </section>
    @elseif(Utils::$lang=='eng')
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
                        <td>{{ $deliveryOrder['cso'] }}</td>
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

                <table class="col-md-12">
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
    @endif
@else
    @if(Utils::$lang=='id')
    <section id="intro" class="clearfix">
        <div class="container">
            <div class="row justify-content-center">
                <h2>PESANAN TIDAK DITEMUKAN</h2>
            </div>
        </div>
    </section>
    @elseif(Utils::$lang=='eng')
    <section id="intro" class="clearfix">
        <div class="container">
            <div class="row justify-content-center">
                <h2>CANNOT FIND REGISTRATION</h2>
            </div>
        </div>
    </section>
    @endif
@endif
@endsection
