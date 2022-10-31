<style type="text/css">
    th,
    td {
        border: 1px solid black;
        text-align: center;
    }
</style>
<div class="table-responsive" style="border: 1px solid #ebedf2;">
    <table class="table table-bordered">               
        <tr>
            <th style="font-weight: 900; font-size: 14px">Order Report By Branch</th>
        </tr>
        <tr>
            <th style="font-weight: 900; font-size: 12px">Branch : {{ $currentBranch ? $currentBranch['code'] . " - " . $currentBranch['name'] : "All Branch" }}</th>
        </tr>
        <tr>
            <th style="font-weight: 900; font-size: 12px">Date : {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}</th>
        </tr>
        <tr>
            <th style="font-weight: 900; font-size: 11px">Total : {{ $countTotalSales }} data</th>
        </tr>
        <thead>
            <tr class="text-center">
                <th style="font-weight: 900;"> No. </th>
                <th style="font-weight: 900;" class="text-left"> Cso </th>
                <th style="font-weight: 900;"> Bank In</th>
                <th style="font-weight: 900;"> Debit</th>
                <th style="font-weight: 900;"> Netto Debit</th>
                <th style="font-weight: 900;"> Card</th>
                <th style="font-weight: 900;"> Netto Card</th>
                <th style="font-weight: 900;"> Total Sale </th>
                <th style="font-weight: 900;"> Total Sale Netto </th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalSale = 0;
                $totalSaleNetto = 0;
            @endphp
            @foreach ($total_sales as $key => $total_sale)
                @php
                    $totalSale_temp = $total_sale['sum_ts_bank_in'] + $total_sale['sum_ts_debit'] + $total_sale['sum_ts_card'];
                    $totalSaleNetto_temp = $total_sale['sum_ts_netto_debit'] + $total_sale['sum_ts_netto_card'];
                    $totalSale += $totalSale_temp;
                    $totalSaleNetto += $totalSaleNetto_temp;
                @endphp
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $total_sale['code'] }} - {{ $total_sale['name'] }}</td>
                    <td class="text-right">Rp. {{ number_format($total_sale['sum_ts_bank_in']) }}</td>
                    <td class="text-right">Rp. {{ number_format($total_sale['sum_ts_debit']) }}</td>
                    <td class="text-right">Rp. {{ number_format($total_sale['sum_ts_netto_debit']) }}</td>
                    <td class="text-right">Rp. {{ number_format($total_sale['sum_ts_card']) }}</td>
                    <td class="text-right">Rp. {{ number_format($total_sale['sum_ts_netto_card']) }}</td>
                    <td class="text-right">Rp. {{ number_format($totalSale_temp) }}</td>
                    <td class="text-right">Rp. {{ number_format($totalSaleNetto_temp) }}</td>
                </tr>
            @endforeach
            <tr class="text-right">
                <th style="font-weight: 900;" colspan="2">TOTAL SALES</th>
                <th style="font-weight: 900;">Rp. {{ number_format($total_sales->sum('sum_ts_bank_in')) }}</th>
                <th style="font-weight: 900;">Rp. {{ number_format($total_sales->sum('sum_ts_debit')) }}</th>
                <th style="font-weight: 900;">Rp. {{ number_format($total_sales->sum('sum_ts_netto_debit')) }}</th>
                <th style="font-weight: 900;">Rp. {{ number_format($total_sales->sum('sum_ts_card')) }}</th>
                <th style="font-weight: 900;">Rp. {{ number_format($total_sales->sum('sum_ts_netto_card')) }}</th>
                <th style="font-weight: 900;">Rp. {{ number_format($totalSale) }}</th>
                <th style="font-weight: 900;">Rp. {{ number_format($totalSaleNetto) }}</th>
            </tr>
        </tbody>
    </table>
    <br />
</div>
