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
            <th style="font-weight: 900; font-size: 14px">Order Report By CSO</th>
        </tr>
        <tr>
            <th style="font-weight: 900; font-size: 12px">Branch : {{ $currentBranch ? $currentBranch['code'] . " - " . $currentBranch['name'] : "All Branch" }}</th>
        </tr>
        <tr>
            <th style="font-weight: 900; font-size: 12px">Cso : {{ $currentCso ? $currentCso['code'] . " - " . $currentCso['name'] : "All Cso" }}</th>
        </tr>
        <tr>
            <th style="font-weight: 900; font-size: 12px">Date :  {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}</th>
        </tr>
        <tr>
            <th style="font-weight: 900; font-size: 11px">Total : {{ $countOrderReports }} data</th>
        </tr>
        <thead>
            <tr class="text-center">
                <th style="font-weight: 900;"> No. </th>
                <th style="font-weight: 900;" class="text-left"> Order Date </th>
                <th style="font-weight: 900;" class="text-left"> Member Name</th>
                <th style="font-weight: 900;">Total Payment</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPayment = 0;
            @endphp
            @foreach ($order_reports as $key => $order_report)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ date("d/m/Y", strtotime($order_report['orderDate'])) }}</td>
                    <td>{{ $order_report['name'] }}</td>
                    <td class="text-right">Rp. {{ number_format($order_report['total_payment']) }}</td>
                </tr>
                @php
                    $totalPayment += $order_report['total_payment'];
                @endphp
            @endforeach
            <tr class="text-right">
                <th style="font-weight: 900;" colspan="3">TOTAL SALES</th>
                <th style="font-weight: 900;">Rp. {{ number_format($totalPayment) }}</th>
            </tr>
        </tbody>
    </table>
    <br />
</div>
