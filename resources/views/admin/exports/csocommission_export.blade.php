<table>
    <thead>
        <tr>
            <th colspan="7" style="font-weight: bold; text-align: center;">DAFTAR BONUS KOMISI WAKI</th>
        </tr>
        <tr>
            <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">No. </th>
            <th style="background-color: #ADD8E6; text-align: left; font-weight: bold; border: 1px solid black;">CSO - Name </th>
            <th style="background-color: #ADD8E6; text-align: left; font-weight: bold; border: 1px solid black;">Bank Account</th>
            <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">Commission</th>
            <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">Bonus</th>
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
        @endphp

        @foreach ($CsoCommissions as $key => $Cso_Commission)
            @php
                $bonusPerCso = 0;
                $commissionPerCso = 0;
                if(count($Cso_Commission->orderCommission) > 0){

                    $bonusPerCso = $Cso_Commission->orderCommission->sum(function ($row) {return ($row->bonus + $row->upgrade + $row->smgt_nominal + $row->excess_price);});
                    $commissionPerCso = $Cso_Commission->commission == 0 ? $Cso_Commission->orderCommission->sum('commission') : $Cso_Commission->commission;
                }
                $tot_commission += $commissionPerCso;
                $tot_pajak += $Cso_Commission['pajak'];
                $tot_bonus += $bonusPerCso;
                $tot_result += $Cso_Commission['commission'] + $bonusPerCso - $Cso_Commission['pajak'];
            @endphp

            <tr>
                <td style="border: 1px solid black; ">{{ $key+1 }}</td>
                <td style="border: 1px solid black; text-align: left;">{{ $Cso_Commission->cso['code'] }} - {{ $Cso_Commission->cso['name'] }}</td>
                <td style="border: 1px solid black; text-align: left; font-weight: bold;">{{ $Cso_Commission->cso['no_rekening'] }}</td>
                <td style="border: 1px solid black; text-align: right; {{ $Cso_Commission->commission > 0 ? 'background-color: #cde9ff;' : '' }}">{{ $commissionPerCso }}</td>
                <td style="border: 1px solid black; text-align: right;">{{ $bonusPerCso }}</td>
                <td style="border: 1px solid black; text-align: right;">{{ $Cso_Commission['pajak'] }}</td>
                <td style="border: 1px solid black; text-align: right;">{{ $Cso_Commission['commission'] + $bonusPerCso - $Cso_Commission['pajak'] }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="3" style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">TOTAL SALES</th>
            <th style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">{{ $tot_commission }}</th>
            <th style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">{{ $tot_bonus }}</th>
            <th style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">{{ $tot_pajak }}</th>
            <th style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">{{ $tot_result }}</th>
        </tr>
    </tbody>
</table>