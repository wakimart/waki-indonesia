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

@if( $order['code'] != null)
    <section id="intro" class="clearfix">
        <div class="container">
            <div class="row justify-content-center">
                <h2>PEMESANAN BERHASIL</h2>
            </div>
            <div class="row justify-content-center">
                <table class="col-md-12">
                    <thead>
                        <td>Kode Pesanan</td>
                        <td>Tanggal Pesanan</td>
                    </thead>
                    <tr>
                        <td>{{ $order['code'] }}</td>
                        <td class="right">{{ date("d/m/Y H:i:s", strtotime($order['created_at'])) }}</td>
                    </tr>
                </table>
                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Data Pemesan</td>
                    </thead>
                    <tr>
                        <td>No. Member : </td>
                        <td>{{ $order['no_member'] }}</td>
                    </tr>
                    <tr>
                        <td>Nama : </td>
                        <td>{{ $order['name'] }}</td>
                    </tr>
                    <tr>
                        <td>No. Telp : </td>
                        <td>{{ $order['phone'] }}</td>
                    </tr>
                    <tr>
                        <td>Alamat : </td>
                        <td>{{ $order['address'] }}</td>
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

                    @foreach(json_decode($order['product']) as $promo)
                        <tr>
                            <td>{{ App\DeliveryOrder::$Promo[$promo->id]['code'] }} - {{ App\DeliveryOrder::$Promo[$promo->id]['name'] }} ( {{ App\DeliveryOrder::$Promo[$promo->id]['harga'] }} )</td>
                            <td>{{ $promo->qty }}</td>
                        </tr>
                    @endforeach
                    @if($order['old_product'] != null)
                        <thead style="background-color: #80808012 !important">
                            <td colspan="2">Barang Lama</td>
                        </thead>
                        <tr>
                            <td colspan="2">{{$order['old_product']}}</td>
                        </tr>
                    @endif
                    @if($order['prize'] != null)
                        <thead style="background-color: #80808012 !important">
                            <td colspan="2">Barang Hadiah</td>
                        </thead>
                        <tr>
                            <td colspan="2">{{$order['prize']}}</td>
                        </tr>
                    @endif

                </table>

                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Detail Pembayaran</td>
                    </thead>
                    <tr>
                        <td>TOTAL PEMBELIAN : </td>
                        <td>Rp. {{ number_format($order['total_payment']) }}</td>
                    </tr>
                    <tr>
                        <td>UANG MUKA : </td>
                        <td>Rp. {{ number_format($order['down_payment']) }} (LUNAS)</td>
                    </tr>
                    <tr>
                        <td>SISA PEMBAYARAN : </td>
                        <td>Rp. {{ number_format($order['remaining_payment']) }}</td>
                    </tr>
                    <tr>
                        <td>BANK : </td>
                        <td>
                            @foreach(json_decode($order['bank']) as $key=>$bank)
                                {{ App\Order::$Banks[$bank->id] }} ({{ $bank->cicilan }}X)
                                @if(sizeof(json_decode($order['bank'], true)) > $key+1) +  @endif
                            @endforeach
                        </td>
                    </tr>
                </table>

                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Syarat dan Ketentuan</td>
                    </thead>
                    <tr>
                        <td>
                            <p class="pInTable">1. Saya telah membaca surat pesanan ini dan menyetujui untuk membeli serta menerima barang yang tercantum diatas dan bersedia melunasi sisa pembayaran pada waktu penerimaan barang.<br>(Khusus luar kota, barang dikirim setelah pelunasan bank)</p>
                            <p class="pInTable">2. Saya maklumi barang-barang ini tidak dijual dengan percobaan.</p>
                            <p class="pInTable">3. Surat pesanan/pengiriman juga berlaku sebagai kuitansi yang sah</p>
                            <p class="pInTable">4. Selain harga tersebut diatas, tidak ada perjanjian lain diluar surat pesanan ini.</p>
                            <p class="pInTable">5. Uang muka yang sudah dibayar tidak dapat ditarik kembali.</p>
                            <p class="pInTable">6. Barang yang sudah dibeli tidak dapat ditukar kembali.</p>
                            <p class="pInTable">7. Barang pesanan selama tiga bulan tidak diambil berarti dibatalkan.</p>
                            <p class="pInTable">8. Ongkos kirim berlaku bagi customer.</p>
                            <p class="pInTable">9. Ongkos kirim berlaku bagi member untuk pembelanjaan dibawah 500.000.</p>
                        </td>
                    </tr>
                </table>

                <table class="col-md-12">
                    <thead>
                        <td>Cabang Sales</td>
                        <td>Kode Sales</td>
                    </thead>
                    <tr>
                        <td style="width:50%; text-align: center">{{ $order->branch['code'] }} - {{ $order->branch['name'] }}</td>
                        <td style="width:50%; text-align: center">{{ $order->cso['code'] }} - {{ $order->cso['name'] }}</td>
                    </tr>
                </table>

                @if($order['description'] != null)
                    <table class="col-md-12">
                        <thead>
                            <td>Keterangan</td>
                        </thead>
                        <tr>
                            <td>{{ $order['description'] }}</td>
                        </tr>
                    </table>
                @endif

                <a href="whatsapp://send?text={{ Route('successorder') }}?code={{ $order['code'] }}" data-action="share/whatsapp/share">Bagikan melalui Whatsapp</a>
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
