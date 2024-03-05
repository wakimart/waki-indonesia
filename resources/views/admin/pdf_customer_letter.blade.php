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
            padding: 10px;
            border: 1px solid black;
        }
        .border thead {
            text-align: center;
        }
        div {
            page-break-after: always;
        }
    </style>
</head>
<body>
    @foreach($orders as $order)
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
                            <td>{{ucwords($detail->type)}}</td>
                            @if($detail->product_id)
                                <td>{{$detail->product->code}}</td>
                            @elseif($detail->promo_id)
                                <td>{{$detail->promo->code}}</td>
                            @else
                                <td>{{$detail->other}}</td>
                            @endif
                            <td>{{$detail->qty}}</td>
                            @if($index == 0)
                                <td rowspan={{count($order->orderDetail)}}>Rp {{number_format($order->total_payment)}}</td>
                                @php
                                    $deliveredDate = '';
                                    foreach($order->historyOrders as $history){
                                        $meta = json_decode($history->meta);
                                        if(isset($meta->dataChange->status)){
                                            if($meta->dataChange->status == 'delivered'){
                                                $deliveredDate = $meta->dataChange->updated_at;
                                            }
                                        }
                                    }
                                @endphp
                                <td rowspan={{count($order->orderDetail)}}>{{date("d/m/Y", strtotime($deliveredDate))}}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p>Dan cara pembayarannya adalah :</p>
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
                            <td>{{$payment->type}}</td>
                            <td>Rp {{number_format($payment->total_payment)}}</td>
                            <td>{{date("d/m/Y", strtotime($payment->payment_date))}}</td>
                            <td>{{$payment->type_payment}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p>
                Apabila terdapat ketidaksesuaian antara barang yang Bapak / Ibu terima dengan keterangan tersebut diatas, Bapak / Ibu {{strtoupper($order->name)}} dapat menghubungi kami di pesawat (031)5662308 dalam jangka waktu 1 (satu) minggu setelah penerimaan surat ini. <br>
                Atas kepercayaan yang Bapak / Ibu {{strtoupper($order->name)}} berikan kami ucapkan terima kasih <br>
                Hormat kami <br><br><br><br><br>
                Management <br>
                Email : cs@waki-indonesia.co.id<br>
                Hotline :
            </p>
        </div>
    @endforeach
</body>
</html>