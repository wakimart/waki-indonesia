<table>
    <thead>
        <tr>
            <th colspan="9" style="font-weight: bold; text-align: center;">DAFTAR BONUS KOMISI WAKI</th>
        </tr>
        <tr>
            <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">No. </th>
            <th style="background-color: #ADD8E6; text-align: left; font-weight: bold; border: 1px solid black;">CSO - Name </th>
            <th style="background-color: #ADD8E6; text-align: left; font-weight: bold; border: 1px solid black;">Bank Account</th>
            <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">Commission</th>
            <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">Bonus</th>
            <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">Bonus Extra</th>
            <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">Cancel</th>
            <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">Tax</th>
            <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">Total Commission</th>
        </tr>
    </thead>
    <tbody>
        @php
            $tot_commission = 0;
            $tot_pajak = 0;
            $tot_result = 0;
            $tot_bonus = 0;
            $tot_cancel = 0;
        @endphp

        @foreach ($allCsoCommission as $key => $perCsoCommission)
            @php
                $bonusPerCso = floor($perCsoCommission['bonusPerCso']);
                $commissionPerCso = floor($perCsoCommission['commissionPerCso']);
                $cancelPerCso = floor($perCsoCommission['cancelPerCso']);
                $tot_commission += $commissionPerCso;
                $tot_bonus += $bonusPerCso;
                $tot_result += $commissionPerCso + $bonusPerCso - $cancelPerCso;
                $tot_cancel += $cancelPerCso;
            @endphp

            <tr>
                <td style="border: 1px solid black; ">{{ $key+1 }}</td>
                <td style="border: 1px solid black; text-align: left;">{{ $perCsoCommission['code'] }} - {{ $perCsoCommission['name'] }}</td>
                <td style="border: 1px solid black; text-align: left; font-weight: bold;">{{ $perCsoCommission['no_rekening'] }}</td>
                <td style="border: 1px solid black; text-align: right;">{{ $commissionPerCso }}</td>
                <td style="border: 1px solid black; text-align: right;">{{ $bonusPerCso }}</td>
                <td style="border: 1px solid black; text-align: right;">0</td>
                <td style="border: 1px solid black; text-align: right;">{{$cancelPerCso}}</td>
                <td style="border: 1px solid black; text-align: right;">0</td>
                <td style="border: 1px solid black; text-align: right;">{{ $commissionPerCso + $bonusPerCso - $cancelPerCso}}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="3" style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">TOTAL SALES</th>
            <th style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">{{ $tot_commission }}</th>
            <th style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">{{ $tot_bonus }}</th>
            <th style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">0</th>
            <th style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">{{ $tot_cancel }}</th>
            <th style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">0</th>
            <th style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">{{ $tot_result }}</th>
        </tr>
    </tbody>
</table>