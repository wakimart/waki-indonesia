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
            <th style="font-weight: 900; font-size: 11px">Total : {{ $countOrderReports }} data</th>
        </tr>
        <thead>
            <tr class="text-center">
                <th style="font-weight: 900;"> No. </th>
                <th style="font-weight: 900;" class="text-left"> CSO </th>
                <th style="font-weight: 900;"> Sales Until Yesterday</th>
                <th style="font-weight: 900;"> Sales Today </th>
                <th style="font-weight: 900;"> Total Sales </th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_sale_untilYesterday = 0;
                $total_sale_today = 0;
                $totalSales = 0;
            @endphp
            @foreach ($order_reports as $key => $order_report)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $order_report['code'] }} - {{ $order_report['name'] }}</td>
                    <td class="text-right">Rp. {{ number_format($order_report['total_sale_untilYesterday']) }}</td>
                    <td class="text-right">Rp. {{ number_format($order_report['total_sale_today']) }}</td>
                    <td class="text-right">Rp. {{ number_format($order_report['total_sale_untilYesterday'] + $order_report['total_sale_today']) }}</td>
                </tr>
                @php
                    $total_sale_untilYesterday += $order_report['total_sale_untilYesterday'];
                    $total_sale_today += $order_report['total_sale_today'];
                    // $totalSales += ($order_report['total_sale_untilYesterday'] + $order_report['total_sale_today']);
                @endphp
            @endforeach
            <tr class="text-right">
                <th style="font-weight: 900;" colspan="2">TOTAL SALES</th>
                <th style="font-weight: 900;">Rp. {{ number_format($total_sale_untilYesterday) }}</th>
                <th style="font-weight: 900;">Rp. {{ number_format($total_sale_today) }}</th>
                <th style="font-weight: 900;">Rp. {{ number_format($total_sale_untilYesterday + $total_sale_today) }}</th>
            </tr>
        </tbody>
    </table>
    <br />
</div>
