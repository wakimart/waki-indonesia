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
    @if(Utils::$lang=='id')
    <section id="intro" class="clearfix">
        <div class="container">
            <div class="row justify-content-center">
                <h2>PEMESANAN BERHASIL</h2>
            </div>
            <div class="row justify-content-center">
                <table class="col-md-12">
                    <thead>
                        <td>Status Pesanan</td>
                        <td>Kode Pesanan</td>
                        <td>Tanggal Pesanan</td>
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
                        <td class="text-center">{{ $order['code'] }}</td>
                        <td class="right">{{ date("d/m/Y", strtotime($order['orderDate'])) }}</td>
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
                    <tr>
                        <td></td>
                        <td>{{ $order['district'][0]['province'] }}, {{ $order['district'][0]['kota_kab'] }}, {{ $order['district'][0]['subdistrict_name'] }}</td>
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

                    @foreach($order->orderDetail->where('type', 'pembelian') as $productBeli)
                        <tr>
                            @if($productBeli['product_id'])
                                <td>{{ $productBeli->product['code'] }} - {{ $productBeli->product['name'] }}</td>
                            @elseif($productBeli['promo_id'])
                                <td>{{ $productBeli->promo['code'] }} - {{ $productBeli->promo['name'] }}</td>
                            @else
                                <td>{{ $productBeli['other'] }}</td>
                            @endif
                            <td>{{ $productBeli->qty }}</td>
                        </tr>
                    @endforeach

                    @if(count($order->orderDetail->where('type', 'upgrade')) > 0)
                        <thead style="background-color: #80808012 !important">
                            <td>Barang Lama</td>
                            <td>Jumlah</td>
                        </thead>
                    @endif
                    @foreach($order->orderDetail->where('type', 'upgrade') as $productBeli)
                        <tr>
                            @if($productBeli['product_id'])
                                <td>{{ $productBeli->product['code'] }} - {{ $productBeli->product['name'] }}</td>
                            @else
                                <td>{{ $productBeli['other'] }}</td>
                            @endif
                            <td>{{ $productBeli->qty }}</td>
                        </tr>
                    @endforeach

                    @if(count($order->orderDetail->where('type', 'prize')) > 0)
                        <thead style="background-color: #80808012 !important">
                            <td>Barang Hadiah</td>
                            <td>Jumlah</td>
                        </thead>
                    @endif
                    @foreach($order->orderDetail->where('type', 'prize') as $productBeli)
                        <tr>
                            @if($productBeli['product_id'])
                                <td>{{ $productBeli->product['code'] }} - {{ $productBeli->product['name'] }}</td>
                            @else
                                <td>{{ $productBeli['other'] }}</td>
                            @endif
                            <td>{{ $productBeli->qty }}</td>
                        </tr>
                    @endforeach
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
                            @foreach($order->orderPayment as $paymentNya)
                                {{ $paymentNya->bank['name'] }} ({{ $paymentNya['cicilan'] }}X) <br>
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

                <a href="whatsapp://send?text={{ route('detail_order') }}?code={{ $order['code'] }}" data-action="share/whatsapp/share">Bagikan melalui Whatsapp</a>
            </div>
        </div>
    </section>
    @elseif(Utils::$lang=='eng')
    <section id="intro" class="clearfix">
        <div class="container">
            <div class="row justify-content-center">
                <h2>ORDER SUCCESSFUL</h2>
            </div>
            <div class="row justify-content-center">
                <table class="col-md-12">
                    <thead>
                        <td>Order Code</td>
                        <td>Order Date</td>
                    </thead>
                    <tr>
                        <td>{{ $order['code'] }}</td>
                        <td class="right">{{ date("d/m/Y H:i:s", strtotime($order['created_at'])) }}</td>
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
                        <td>City : </td>
                        <td>{{ $order['city'] }}</td>
                    </tr>
                    <tr>
                        <td>Address : </td>
                        <td>{{ $order['address'] }}</td>
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

                    @foreach($order->orderDetail->where('type', 'pembelian') as $productBeli)
                        <tr>
                            @if($productBeli['product_id'])
                                <td>{{ $productBeli->product['code'] }} - {{ $productBeli->product['name'] }}</td>
                            @elseif($productBeli['promo_id'])
                                <td>{{ $productBeli->promo['code'] }} - {{ $productBeli->promo['name'] }}</td>
                            @else
                                <td>{{ $productBeli['other'] }}</td>
                            @endif
                            <td>{{ $productBeli->qty }}</td>
                        </tr>
                    @endforeach

                    @if(count($order->orderDetail->where('type', 'upgrade')) > 0)
                        <thead style="background-color: #80808012 !important">
                            <td>Barang Lama</td>
                            <td>Jumlah</td>
                        </thead>
                    @endif
                    @foreach($order->orderDetail->where('type', 'upgrade') as $productBeli)
                        <tr>
                            @if($productBeli['product_id'])
                                <td>{{ $productBeli->product['code'] }} - {{ $productBeli->product['name'] }}</td>
                            @else
                                <td>{{ $productBeli['other'] }}</td>
                            @endif
                            <td>{{ $productBeli->qty }}</td>
                        </tr>
                    @endforeach

                    @if(count($order->orderDetail->where('type', 'prize')) > 0)
                        <thead style="background-color: #80808012 !important">
                            <td>Barang Hadiah</td>
                            <td>Jumlah</td>
                        </thead>
                    @endif
                    @foreach($order->orderDetail->where('type', 'prize') as $productBeli)
                        <tr>
                            @if($productBeli['product_id'])
                                <td>{{ $productBeli->product['code'] }} - {{ $productBeli->product['name'] }}</td>
                            @else
                                <td>{{ $productBeli['other'] }}</td>
                            @endif
                            <td>{{ $productBeli->qty }}</td>
                        </tr>
                    @endforeach

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
                            @foreach($order->orderPayment as $paymentNya)
                                {{ $paymentNya->bank['name'] }} ({{ $paymentNya['cicilan'] }}X) <br>
                            @endforeach
                        </td>
                    </tr>
                </table>

                <table class="col-md-12">
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
                        <td style="width:50%; text-align: center">{{ $order['cso'] }}</td>
                    </tr>
                </table>

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

                <a href="whatsapp://send?text={{ Route('order_success') }}?code={{ $order['code'] }}" data-action="share/whatsapp/share">Share to Whatsapp</a>
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
                <h2>CANNOT FIND ORDER</h2>
            </div>
        </div>
    </section>
    @endif
@endif
@endsection
