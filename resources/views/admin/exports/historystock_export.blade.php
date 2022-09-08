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
            <th><b>Warehouse (From-To)</b></th>
            <th><b>Date</b></th>
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
            @php 
                $historycodeType = $historycode;
                $historycodeType = $historycodeType->groupBy(["type"])->values();
            @endphp
            <tr>
                <td class="text-center" rowspan="{{ sizeof($historycode) }}">
                    {{ ++$i }}
                </td>
                <td rowspan="{{ sizeof($historycode) }}">
                    {{ $code }}
                </td>
                <td rowspan="{{ sizeof($historycode) }}">
                    {{$historycode[0]->stockFrom->warehouse['name'] }}
                    =>
                    {{$historycode[0]->stockTo->warehouse['name'] ?? '' }}
                </td>
                <td rowspan="{{ sizeof($historycodeType[0]) }}">
                    {{ date("d-m-Y", strtotime($historycodeType[0][0]->date)) }}
                </td>
                <td class="text-center" rowspan="{{ sizeof($historycodeType[0]) }}">
                    {{ ucfirst($historycodeType[0][0]->type) }}
                </td>
                <td>
                    {{ $historycode[0]->stockFrom->product['code'] }}
                </td>
                <td class="text-center">
                    {{ $historycode[0]->quantity }}
                </td>
            </tr>
            @if (sizeof($historycodeType[0]) > 1)
                @for ($i = 1; $i < sizeof($historycodeType[0]); $i++)
                <tr>
                    <td>
                        {{ $historycodeType[0][$i]->stockFrom->product['code'] }}
                    </td>
                    <td class="text-center">
                        {{ $historycodeType[0][$i]->quantity }}
                    </td>
                </tr>
                @endfor
            @endif

            @if(sizeof($historycodeType) > 1)
                @for($j = 1; $j < sizeof($historycodeType); $j++)
                    <tr>
                        <td rowspan="{{ sizeof($historycodeType[$j]) }}">
                            {{ date("d-m-Y", strtotime($historycodeType[$j][0]->date)) }}
                        </td>
                        <td class="text-center" rowspan="{{ sizeof($historycodeType[$j]) }}">
                            {{ ucfirst($historycodeType[$j][0]->type) }}
                        </td>
                        <td>
                            {{ $historycodeType[$j][0]->stockFrom->product['code'] }}
                        </td>
                        <td class="text-right">
                            {{ $historycodeType[$j][0]->quantity }}
                        </td>
                    </tr>
                    @if (sizeof($historycodeType[$j]) > 1)
                        @for ($i = 1; $i < sizeof($historycodeType[$j]); $i++)
                            <tr>
                                <td>
                                    {{ $historycodeType[$j][$i]->stockFrom->product['code'] }}
                                </td>
                                <td class="text-right">
                                    {{ $historycodeType[$j][$i]->quantity }}
                                </td>
                            </tr>
                        @endfor
                    @endif
                @endfor
            @endif
        @endforeach
    </tbody>
</table>