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
                <h2>DETAIL DELIVERY ORDER</h2>
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

                <a href="whatsapp://send?text={{ route('detail_deliveryorder') }}?code={{ $deliveryOrder['code'] }}" data-action="share/whatsapp/share">Bagikan melalui Whatsapp</a>
            </div>
        </div>
    </section>
@else
    <section id="intro" class="clearfix">
        <div class="container">
            <div class="row justify-content-center">
                <h2>PESANAN TIDAK DITEMUKAN</h2>
            </div>
        </div>
    </section>
@endif
@endsection