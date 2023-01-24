<html>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<head>
    <style>
        body {
            font-size: 12px;
        }
    </style>
</head>

<body>

@for ($i=1; $i<=2; $i++)
    <div style="display: block; border-bottom: 1px solid black;">
        <img src="{{public_path('sources/Logo Since.png')}}" style="max-width: 140px; height: 50px; float: left;">
        <div style="text-align: right; height: 50px;">
            <div>Kompl. Darmo Park I Blok 2B No. 1-6</div>
            <div>Jl. Mayjend Sungkono - Surabaya</div>
            <div>Telp. 031-5662308</div>
        </div>
    </div>

    <div style="text-align: center; margin-top: 3px; margin-bottom: 5px;">TANDA TERIMA PENGIRIMAN BARANG</div>

    <table style="width: 100%; border-collapse: collapse;">
        <colgroup>
            <col style="width: 10%;">
            <col style="width: 40%;">
            <col style="width: 10%;">
            <col style="width: 10%;">
            <col style="width: 5%;">
            <col style="width: 15%;">
            <col style="width: 10%;">
        </colgroup>
        <tr>
            <td style="text-align: right;">Tanggal :</td>
            <td>{{ date('d/m/Y', strtotime($order->orderDate)) }}</td>
            <td style="text-align: right;">Customer :</td>
            <td colspan="4">{{ $order->name }}</td>
        </tr>
        <tr>
            <td style="text-align: right;">No SP :</td>
            <td>{{ $order->code }} Temp No : {{ $order->temp_no }}</td>
            <td></td>
            <td colspan="4">{{ $order->address }}</td>
        </tr>
        <tr>
            <td style="text-align: right;">Cabang :</td>
            <td>{{ $order->branch['name'] }}</td>
        </tr>
        <tr>
            <td style="text-align: right;">Sales :</td>
            <td>{{ $order->cso['name'] }}</td>
            <td></td>
            <td>{{ $order->phone }}</td>
        </tr>
        <tr>
            <td style="text-align: right;">Keterangan :</td>
            <td>{{ $order->description }}</td>
        </tr>

        <tr>
            <td style="text-align: center; font-weight: 600; border: 2px solid black; border-width: 2px 0px; padding-top: 8px;"></td>
            <td style="text-align: center; font-weight: 600; border: 2px solid black; border-width: 2px 0px; padding-top: 8px;" colspan="3">Nama Barang</td>
            <td style="text-align: center; font-weight: 600; border: 2px solid black; border-width: 2px 0px; padding-top: 8px;">Qty</td>
            <td style="text-align: center; font-weight: 600; border: 2px solid black; border-width: 2px 0px; padding-top: 8px;">Status</td>
            <td style="text-align: center; font-weight: 600; border: 2px solid black; border-width: 2px 0px; padding-top: 8px;">Tanggal</td>
        </tr>

        @foreach ($products as $product)
            <tr>
                <td style="padding: 3px; text-align:center;">{{ $product['code'] }}</td>
                <td style="padding: 3px;" colspan="3">{{ $product['name'] }}</td>
                <td style="padding: 3px; text-align:right;">{{ $product['quantity'] }}</td>
                <td style="padding: 3px;" >{{ $product['status'] }}</td>
                <td style="padding: 3px;" >{{ date('d/m/Y', strtotime($stockInOut->date)) }}</td>
            </tr>
        @endforeach
    </table>

    <br>
    <br>
    <br>
    <div style="widht: 100%; border-top: 1px solid black; margin-bottom: 3px;"></div>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td>
                <small style="font-style: italic;">Lembar ke-{{ $i }} untuk 
                    @if($i == 1) Customer
                    @elseif($i == 2) Arsip Office
                    @endif
                </small>
            </td>
            <td style="text-align: right; color: darkblue; font-style: italic; font-size: 22px; font-weight: bold;" rowspan="2">
                LUNAS
            </td>
        </tr>
        <tr>
            <td><small style="font-style: italic;">Printed : {{ date('d/m/Y') }} Time : {{ date('H:i:s') }}</small></td>
        </tr>
    </table>

    <br>
    <br>
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 5%;">&nbsp;</td>
            <td style="width: 15%; text-align: center;">Pengirim Barang</td>
            <td style="width: 10%;">&nbsp;</td>
            <td style="width: 15%; text-align: center;">Penerima Barang</td>
            <td style="width: 15%;">&nbsp;</td>
            <td style="width: 5%;">&nbsp;</td>
            <td style="width: 35%; text-align: right;">Jam Tiba : .....................Jam Selesai : ....................</td>
        </tr>
        <tr>
            <td colspan="7">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="5">&nbsp;</td>
            <td colspan="2">Demo / Pasang : Belum / Selesai</td>
        </tr>
        <tr>
            <td colspan="7">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="7">&nbsp;</td>
        </tr>
        <tr>
            <td></td>
            <td>( <span style="float: right;">)</span></td>
            <td></td>
            <td>( <span style="float: right;">)</span></td>
        </tr>
    </table>

    @if($i != 2) <div style="margin-bottom: 25px;">&nbsp;</div> @endif
@endfor

</body>

</html>