<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-size: 12px;
            font-family: verdana, sans-serif;
            line-height: 1.6;
        }        
        table {
            width: 100%;
        }
        .text-right {
            text-align: right;
        }
        .border{
            border-collapse: collapse;
        }
        .border td {
            padding: 3px;
            border: 1px solid black;
        }
        .border thead {
            text-align: center;
        }
        .footer {
            width: 100%;
            bottom: 60px;
            position: fixed;
        }
        div {
            page-break-after: always;
        }
    </style>
</head>
<body>
    @foreach($orders as $order)
        @php
            if($order->orderDetail->where('stock_id', '!=', null)->count() == 0){
                continue;
            }
        @endphp
        <div>
            <h1 style="font-family: 'Times New Roman', Times, serif">WAKI</h1>
            <p>DARMO PARK I BLOK 2B NO 1-2 <br> JL. MAYJEN SUNGKONO <br> SURABAYA</p>
            <hr>
            <table>
                <tr>
                    <td>
                        SURABAYA, {{date('d F Y')}} <br><br>
                        Kepada Yth. <br>
                        Bapak / Ibu {{strtoupper($order->name)}} <br>
                        {{strtoupper($order->address)}}<br>
                        @php
                            $address = $order->getDistrict();
                        @endphp
                        {{strtoupper($address['subdistrict_name'].", ".$address['kota_kab'].", ".$address['province'])}}
                    </td>
                    <td style="vertical-align: top" class="text-right">{{$order->code}}/K-CUST/MW/{{date('y')}}</td>
                </tr>
            </table>
            <p>
                Dengan hormat, <br>
                Sebelumnya kami berterima kasih atas kepercayaan Bapak / Ibu {{strtoupper($order->name)}} yang telah memilih produk WAKI. <br>
                Untuk meningkatkan pelayanan purna jual, kami akan mengkonfirmasikan kembali barang yang telah Bapak / Ibu {{strtoupper($order->name)}} terima, yaitu sebagai berikut:
            </p>
            <table class="border">
                <thead>
                    <tr>
                        <td>No. {{$order->temp_no}}</td>
                        <td>Barang</td>
                        <td>Jumlah</td>
                        <td>Harga</td>
                        <td>Tgl Terima</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderDetail as $index => $detail)
                        <tr>
                            <td>
                                @if($detail->type == 'pembelian')
                                    Pembelian
                                @elseif($detail->type == 'upgrade')
                                    Tarik-Upgrade
                                @else
                                    Hadiah
                                @endif
                            </td>
                            @if($detail->product_id)
                                <td>({{$detail->product->code}}) {{$detail->product->name}}</td>
                            @elseif($detail->promo_id)
                                <td>{{$detail->promo->code}}</td>
                            @else
                                <td>{{$detail->other}}</td>
                            @endif
                            <td>{{$detail->qty}} Set</td>
                            @if($index == 0)
                                <td rowspan="{{count($order->orderDetail)}}" style="text-align: end;">Rp {{number_format($order->total_payment)}}</td>
                                @php
                                    $deliveredDate = '';
                                    foreach($order->historyOrders as $history){
                                        $meta = json_decode($history->meta);
                                        if(isset($meta->dataChange->status)){
                                            if($meta->dataChange->status == 'delivery'){
                                                $deliveredDate = $meta->dataChange->updated_at;
                                            }
                                        }
                                    }
                                @endphp
                            @endif
                            @if($detail->type == 'upgrade')
                                <td>{{ date("d/m/Y", strtotime($deliveredDate)) }}</td>
                            @else
                                <td>{{ $detail->stockInOut['date'] != null ? date("d/m/Y", strtotime($detail->stockInOut['date'])) : '-' }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p>Dan cara pembayarannya adalah :</p>
            @if($order->orderPayment->count() <= 7)
                <table class="border" style="width: 60%; margin-left: auto; margin-right: auto;">
                    <thead>
                        <tr>
                            <td style="border-top: 0px; border-left: 0px;"></td>
                            <td>Jumlah</td>
                            <td>Tanggal</td>
                            <td>Keterangan</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderPayment as $payment)
                            <tr>
                                <td>{{ $payment->type == 'order' ? 'Uang Muka' : 'Pelunasan' }}</td>
                                <td style="text-align: right;">Rp {{number_format($payment->total_payment)}}</td>
                                <td>{{date("d/m/Y", strtotime($payment->payment_date))}}</td>
                                <td>{{ ucwords($payment->type_payment) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                @for($i = 0; $i < 2; $i++)
                    <table class="border" style="width: 45%; float: left;">
                        <thead>
                            <tr>
                                <td style="border-top: 0px; border-left: 0px;"></td>
                                <td>Jumlah</td>
                                <td>Tanggal</td>
                                <td>Keterangan</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderPayment as $payIdx => $payment)
                                @php
                                    if($i == 0 && $payIdx > 6){
                                        continue;
                                    }
                                    elseif($i == 1 && $payIdx <= 6) {
                                        continue;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $payment->type == 'order' ? 'Uang Muka' : 'Pelunasan' }}</td>
                                    <td style="text-align: right;">Rp {{number_format($payment->total_payment)}}</td>
                                    <td>{{date("d/m/Y", strtotime($payment->payment_date))}}</td>
                                    <td>{{ ucwords($payment->type_payment) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($i == 0)
                        <span style="width: 6%; float: left;"></span>
                    @endif
                @endfor
                {{-- <div style="clear: both;"></div> --}}
            @endif
            <p style="clear: left;">
                Apabila terdapat ketidaksesuaian antara barang yang Bapak / Ibu terima dengan keterangan tersebut diatas, Bapak / Ibu {{strtoupper($order->name)}} dapat menghubungi kami di pesawat (031)5662308 dalam jangka waktu 1 (satu) minggu setelah penerimaan surat ini. <br>
                Atas kepercayaan yang Bapak / Ibu {{strtoupper($order->name)}} berikan kami ucapkan terima kasih <br><br>
                Hormat kami <br><br><br><br><br>
                Management <br>
                Email : cs@waki-indonesia.co.id<br>
                Hotline : +62 899-3199-999
            </p>

            
            <table class="footer">
                <tr>
                    <td>
                        <span style="margin-bottom: 15px;">Kepada Yth. </span><br>
                        Bapak / Ibu {{strtoupper($order->name)}} <br>
                        {{strtoupper($order->address)}}<br>
                        @php
                            $address = $order->getDistrict();
                        @endphp
                        {{strtoupper($address['subdistrict_name'].", ".$address['kota_kab'].", ".$address['province'])}}
                    </td>
                </tr>
            </table>
        </div>
    @endforeach
</body>
</html>