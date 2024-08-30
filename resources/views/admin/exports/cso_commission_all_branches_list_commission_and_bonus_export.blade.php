<table>
    <tr>
        <th colspan="8" style="font-weight: bold; text-align: center;">DAFTAR REKAPAN KOMISI + BONUS WAKI PERIODE {{strtoupper($periode)}}</th>
    </tr>
</table>

@foreach($branches as $branch)
    <table>
        <thead>
            <tr>
                <th colspan="8" style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">{{$branch->code}} - {{$branch->name}}</th>
            </tr>
            <tr>
                <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">No. </th>
                <th style="background-color: #ADD8E6; text-align: left; font-weight: bold; border: 1px solid black;">CSO - Name </th>
                <th style="background-color: #ADD8E6; text-align: left; font-weight: bold; border: 1px solid black;">Bank Account</th>
                <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">Commission</th>
                <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">Bonus</th>
                <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">Bonus Extra</th>
                <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">Tax</th>
                <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">Total Commission Bonus</th>
            </tr>
        </thead>
        <tbody>
            @php
                $tot_commission = 0;
                $tot_bonus = 0;
                $tot_commission_bonus = 0;
            @endphp
            @foreach($branch->cso as $index => $cso)
                @php
                    $tot_commission += $cso->commission - (0.03 * $cso->cancel);
                    $tot_bonus += $cso->bonus;
                    $tot_commission_bonus += $cso->commission + $cso->bonus;
                @endphp
                <tr>
                    <td style="border: 1px solid black; text-align: center">{{$index+1}}</td>
                    <td style="border: 1px solid black; text-align: left;">{{$cso->code}} - {{$cso->name}}</td>
                    <td style="border: 1px solid black; text-align: left;">{{$cso->no_rekening}}</td>
                    <td style="border: 1px solid black; text-align: right;">{{$cso->commission - (0.03 * $cso->cancel)}}</td>
                    <td style="border: 1px solid black; text-align: right;">{{$cso->bonus}}</td>
                    <td style="border: 1px solid black; text-align: right;">0</td>
                    <td style="border: 1px solid black; text-align: right;">0</td>
                    <td style="border: 1px solid black; text-align: right;">{{$cso->commission + $cso->bonus}}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="3" style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">TOTAL</th>
                <th style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">{{$tot_commission}}</th>
                <th style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">{{$tot_bonus}}</th>
                <th style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">0</th>
                <th style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">0</th>
                <th style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">{{$tot_commission_bonus}}</th>
            </tr>
        </tbody>
    </table>
@endforeach