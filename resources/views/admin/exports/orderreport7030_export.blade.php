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
            <th style="font-weight: 900; font-size: 14px">Total Sale</th>
        </tr>
        <tr>
            <th style="font-weight: 900; font-size: 12px">Date : {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}</th>
        </tr>
        <tr>
            <th style="font-weight: 900; font-size: 11px">Total : {{ $countCso }} data</th>
        </tr>
        <thead>
            <tr class="text-center">
                <th style="font-weight: 900;">No.</th>
                <th style="font-weight: 900;">Branch</th>
                <th style="font-weight: 900;" class="text-left">CSO - Name</th>
                <th style="font-weight: 900;">Total Set 70%</th>
                <th style="font-weight: 900;">Total Set 30%</th>
                {{-- <th style="font-weight: 900;">Total Order/Set</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($allCso as $key => $perCso)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $perCso->branch['code'] }}</td>
                    <td>{{ $perCso['code'] }} - {{ $perCso['name'] }}</td>

                    @php
                        $total_70_sales = $perCso->order_70_sales->where('orderDate', '>=', $startDate)
                            ->where('orderDate', '<=', $endDate)
                            ->whereNotIn('status', [App\Order::$status['1'], App\Order::$status['5'], App\Order::$status['6'], App\Order::$status['7'] ]);
                        $total_30_sales = $perCso->order_30_sales->where('orderDate', '>=', $startDate)
                            ->where('orderDate', '<=', $endDate)
                            ->whereNotIn('status', [App\Order::$status['1'], App\Order::$status['5'], App\Order::$status['6'], App\Order::$status['7'] ]);

                    @endphp
                    {{-- <td class="text-right">{{ $total_70_sales->sum('total_payment') * 0.7 }}</td> --}}
                    {{-- <td class="text-right">{{ $total_30_sales->sum('total_payment') * 0.3 }}</td> --}}
                    {{-- <td class="text-right">{{ ($total_70_sales->sum('total_payment') * 0.7) + ($total_30_sales->sum('total_payment') * 0.3) }}</td> --}}
                    <td>{{ sizeof($total_70_sales) }} Set Order</td>
                    <td>{{ sizeof($total_30_sales) }} Set Order</td>
                    {{-- <td>{{ $perCso->order_sales->where('orderDate', '>=', $startDate)
                            ->where('orderDate', '<=', $endDate)
                            ->whereNotIn('status', [App\Order::$status['1'], App\Order::$status['5'], App\Order::$status['6'], App\Order::$status['7'] ])->sum('total_payment') }}</td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
    <br />
</div>
