<style type="text/css">
    th, td{
        border: 1px solid black;
        text-align: center;
    }
</style>


<table>
    <thead>
        <tr>
            <td colspan="6" style="font-weight: 900; text-align: center;">Laporan History Stock Per Cabang</td>
        </tr>
        <tr>
            <td colspan="6">Cabang : </td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <td>No</td>
            <td>Code</td>
            <td>Date</td>
            <td>Type</td>
            <td>Product Name</td>
            <td>Quantity</td>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 0;
        @endphp

        @if (count($HistoryStocks)!=0)
            @foreach ($HistoryStocks as $historyStock )
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $historyStock->code }}</td>
                <td>{{ date("d-m-Y", strtotime($historyStock->date)) }}</td>
                <td>{{ ucfirst($historyStock->type) }}</td>
                <td>{{ $historyStock->stock->product['name'] }}</td>
                <td>{{ $historyStock->quantity }}</td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>