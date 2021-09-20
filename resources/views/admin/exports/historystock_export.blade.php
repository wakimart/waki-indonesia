<style type="text/css">
    th, td{
        border: 1px solid black;
        text-align: center;
    }
</style>


<table>
    <?php
    $historystocks = $HistoryStocks->groupBy("code");
    ?>
    <thead>
        <tr>
            <td colspan="6" style="font-weight: 900; text-align: center;">Laporan History Stock</td>
        </tr>
        <tr>
            <td colspan="6">Date Range : {{date('d-m-Y', strtotime($dateRange[0]))}} - {{date('d-m-Y', strtotime($dateRange[1]))}}</td>
        </tr>
        <tr><td></td></tr>
        <tr>
            <th><b>No</b></th>
            <th><b>Code</b></th>
            <th><b>Date</b></th>
            <th><b>Warehouse</b></th>
            <th><b>Type</b></th>
            <th><b>Product</b></th>
            <th><b>Quantity</b></th>
        </tr>
    </thead>
    <tbody>
        @php
            $i = 0;
        @endphp

        @foreach ($historystocks as $code => $historycode)
            <tr>
                <td class="text-center" rowspan="{{ sizeof($historycode) }}">
                    {{ ++$i }}
                </td>
                <td rowspan="{{ sizeof($historycode) }}">
                    {{ $code }}
                </td>
                <td rowspan="{{ sizeof($historycode) }}">
                    {{ date("d-m-Y", strtotime($historycode[0]->date)) }}
                </td>
                <td rowspan="{{ sizeof($historycode) }}">
                    {{$historycode[0]->stock->warehouse['name'] }}
                </td>
                <td class="text-center" rowspan="{{ sizeof($historycode) }}">
                    {{ ucfirst($historycode[0]->type) }}
                </td>
                <td>
                    {{ $historycode[0]->stock->product['code'] }}
                </td>
                <td class="text-center">
                    {{ $historycode[0]->quantity }}
                </td>
            </tr>
            @if (sizeof($historycode) > 1)
                @for ($i = 1; $i < sizeof($historycode); $i++)
                <tr>
                    <td>
                        {{ $historycode[$i]->stock->product['code'] }}
                    </td>
                    <td class="text-center">
                        {{ $historycode[$i]->quantity }}
                    </td>
                </tr>
                @endfor
            @endif
        @endforeach
    </tbody>
</table>