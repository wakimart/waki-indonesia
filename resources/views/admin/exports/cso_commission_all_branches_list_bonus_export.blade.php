<table>
    <tr>
        <th colspan="8" style="font-weight: bold; text-align: center;">DAFTAR RINCIAN EXTRA BONUS KOMISI WAKI</th>
    </tr>
</table>
@foreach($branches as $branch)
<table>
    <thead>
        <tr>
            <th colspan="8" style="font-weight: bold; text-align: left;">Cabang : {{ $branch->code }} - {{ $branch->name }}</th>
        </tr>
        <tr>
            <th colspan="8" style="font-weight: bold; text-align: left;">Bulan : {{ $periode }}</th>
        </tr>
        <tr>
            <th colspan="8" style="font-weight: bold; text-align: left;">Sale Netto : Rp. {{ number_format($branch->total_sale['sum_ts_bank_in'] + $branch->total_sale['sum_ts_netto_debit'] + $branch->total_sale['sum_ts_netto_card'] - $branch->cso_commission->sum('cancelPerCso')) }}</th>
        </tr>
        <tr>
            <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">Date</th>
            <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">CSO - Name (%)</th>
            <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">D/O</th>
            <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">Price</th>
            <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">Bonus</th>
            <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">Upgrade</th>
            <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">Lbh Harga</th>
            <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">Product</th>
            <th style="background-color: #ADD8E6; font-weight: bold; border: 1px solid black;">Remarks</th>
        </tr>
    </thead>
    <tbody>
        @php
            $tot_bonus = 0;
            $tot_upgrade = 0;
            $tot_smgtbns = 0;
            $tot_excess = 0;
        @endphp

        @foreach ($branch->cso_commission as $key => $perBranch)
            @php
                $tot_cso_bonus = 0;
                $tot_cso_upgrade = 0;
                $tot_cso_smgtbns = 0;
                $tot_cso_excess = 0;

                $eachOrderCommission = $perBranch->orderCommission->filter(function($valueNya, $keyNya) use ($startDate, $endDate, $branch){
                    $perOr = $valueNya->order;
                    if($perOr['status'] != 'reject' && $perOr['branch_id'] == $branch['id']){
                        if($perOr->orderPayment->sortBy('payment_date')->last()['payment_date'] >= $startDate && $perOr->orderPayment->sortBy('payment_date')->last()['payment_date'] <= $endDate){
                            return $valueNya;
                        }
                    }
                });
            @endphp
            @foreach($eachOrderCommission as $orderPerCommission)
                @php
                    if(!isset($orderPerCommission->order)){
                        continue;
                    }
                    $percentage =  $orderPerCommission->order['30_cso_id'] == $orderPerCommission->order['70_cso_id'] ? "100%" : ($orderPerCommission->order['30_cso_id'] == $perBranch['id'] ? "30%" : "70%");
                    $curnt_beli = 1;
                    $curnt_hadiah = 1;
                    $curnt_upgrade = 1;
                    $product_order = "";
                    $product_beli = "";
                    $product_hadiah = "";
                    $product_upgrade = "";

                    foreach ($orderPerCommission->order->orderDetail as $perProduct) {
                        if($perProduct['type'] == "pembelian"){
                            if(isset($perProduct->product['code'])){
                                $product_beli .= $perProduct->product['code']."-".$perProduct->product['name']." (Pembelian)";
                            }
                            else{
                                $product_beli .= $perProduct['other']." (Pembelian)";
                            }
                            $tempTot = $orderPerCommission->order->orderDetail->where('type', "pembelian")->count();
                            if($tempTot > 1 && $curnt_beli < $tempTot){
                                $curnt_beli++;
                                $product_beli .= "<br>";
                            }
                        }
                        elseif($perProduct['type'] == "prize"){
                            if(isset($perProduct->product['code'])){
                                $product_hadiah .= $perProduct->product['code']."-".$perProduct->product['name']." (Hadiah)";
                            }
                            else{
                                $product_hadiah .= $perProduct['other']." (Hadiah)";
                            }
                            $tempTot = $orderPerCommission->order->orderDetail->where('type', "prize")->count();
                            if($tempTot > 1 && $curnt_hadiah < $tempTot){
                                $curnt_hadiah++;
                                $product_hadiah .= "<br>";
                            }
                        }
                        elseif($perProduct['type'] == "upgrade"){
                            if(isset($perProduct->product['code'])){
                                $product_upgrade .= $perProduct->product['code']."-".$perProduct->product['name']." (Upgrade)";
                            }
                            else{
                                $product_upgrade .= $perProduct['other']." (Upgrade)";
                            }
                            $tempTot = $orderPerCommission->order->orderDetail->where('type', "upgrade")->count();
                            if($tempTot > 1 && $curnt_upgrade < $tempTot){
                                $curnt_upgrade++;
                                $product_upgrade .= "<br>";
                            }
                        }
                    }

                    $tot_cso_bonus += $orderPerCommission['bonus'];
                    $tot_cso_upgrade += $orderPerCommission['upgrade'];
                    $tot_cso_excess += $orderPerCommission['excess_price'];
                @endphp
                <tr>
                    <td style="border: 1px solid black; ">{{ date('j-M', strtotime($orderPerCommission->order['orderDate'])) }}</td>
                    <td style="border: 1px solid black; text-align: left;">
                        {{ $perBranch['code'] }} - {{ $perBranch['name'] }} ({{ $percentage }})
                    </td>
                    <td style="border: 1px solid black; text-align: right;">{{ $orderPerCommission->order['temp_no'] }}</td>
                    <td style="border: 1px solid black; text-align: right;">{{ $orderPerCommission->order['total_payment'] }}</td>
                    <td style="border: 1px solid black; text-align: right;">{{ $orderPerCommission['bonus'] }}</td>
                    <td style="border: 1px solid black; text-align: right;">{{ $orderPerCommission['upgrade'] }}</td>
                    <td style="border: 1px solid black; text-align: right;">{{ $orderPerCommission['excess_price'] }}</td>
                    <td style="border: 1px solid black; text-align: right;">
                        {!! $product_beli !!}<br>{!! $product_hadiah !!}<br>{!! $product_upgrade !!}
                    </td>
                    <td style="border: 1px solid black; text-align: right;">{{ $orderPerCommission['remarks'] }}</td>
                </tr>
            @endforeach
            @php
                $tot_bonus += $tot_cso_bonus;
                $tot_upgrade += $tot_cso_upgrade;
                $tot_smgtbns += $tot_cso_smgtbns;
                $tot_excess += $tot_cso_excess;
            @endphp
            @if(sizeof($eachOrderCommission) > 0)
                <tr>
                    <td colspan="4" style="font-weight: bold; background-color: #AEF6FC; border: 1px solid black; text-align: center;">{{ $perBranch['code'] }} - {{ $perBranch['name'] }}</td>
                    <td style="font-weight: bold; background-color: #AEF6FC; border: 1px solid black; text-align: right;">{{ $tot_cso_bonus }}</td>
                    <td style="font-weight: bold; background-color: #AEF6FC; border: 1px solid black; text-align: right;">{{ $tot_cso_upgrade }}</td>
                    <td style="font-weight: bold; background-color: #AEF6FC; border: 1px solid black; text-align: right;">{{ $tot_cso_excess }}</td>
                    <td style="font-weight: bold; background-color: #AEF6FC; border: 1px solid black; text-align: right;">{{ $tot_cso_bonus + $tot_cso_upgrade + $tot_cso_smgtbns + $tot_cso_excess }}</td>
                </tr>
            @endif
        @endforeach
        <tr>
            <th colspan="4" rowspan="2" style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">TOTAL EXTRA BONUS</th>
            <th style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">{{ $tot_bonus }}</th>
            <th style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">{{ $tot_upgrade }}</th>
            <th style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">{{ $tot_excess }}</th>
            <th rowspan="2" style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">{{ $tot_bonus + $tot_upgrade + $tot_smgtbns + $tot_excess }}</th>
        </tr>
        <tr>
            <th colspan="4" style="background-color: #F9FDD4; border: 1px solid black; text-align: right; font-weight: bold;">{{ $tot_bonus + $tot_upgrade + $tot_smgtbns + $tot_excess }}</th>
        </tr>
    </tbody>
</table>
@endforeach