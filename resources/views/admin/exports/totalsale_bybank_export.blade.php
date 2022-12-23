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
            <th colspan="9" style="font-weight: 900; font-size: 20px; text-align: center;">
                Report Bank In Sales (by Bank)
            </th>
        </tr>
        <tr>
            <th colspan="9" style="font-weight: 900; font-size: 16px; text-align: center;">
                Date Periode : {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }} 
            </th>
        </tr>
        <tr><td colspan="9"></td></tr>
        @php 
            $idxBank = 0;
            $idxBranch = 0;
            $totalSaleGross = 0;
            $totalSaleNetto = 0;
        @endphp
        @foreach ($total_sales as $bank)
            @if ($idxBank != 0)
            <tr><td colspan="9" style="border: 1px solid #000000;"></td></tr>
            @endif
            <tr>
                <th colspan="9" style="font-weight: 900; text-align: center; border: 1px solid #000000;">
                    Bank : {{ $bank['code'] }} - {{ $bank['name'] }}
                </th>
            </tr>
            @php 
                $totalBankIn = 0;
                $totalDebit = 0;
                $totalNettoDebit = 0;
                $totalCard = 0;
                $totalNettoCard = 0;
            @endphp
            @foreach ($bank['branches'] as $branch)
                @if ($idxBranch != 0)
                <tr><td colspan="9" style="border: 1px solid #000000;"></td></tr>
                @endif
                <tr>
                    <th colspan="9" style="font-weight: 900; border: 1px solid #000000;">
                        Branch : {{ $branch['br_code'] }} - {{ $branch['br_name'] }}
                    </th>
                </tr>
                <tr>
                    <td style="border: 1px solid #000000; font-size: 9px;">No.</td>
                    <td style="text-align: center; border: 1px solid #000000; font-size: 9px;">Order Payment Date</td>
                    <td style="text-align: center; border: 1px solid #000000; font-size: 9px;">Estimate Date</td>
                    <td style="text-align: center; border: 1px solid #000000; font-size: 9px;">Order Code</td>
                    <td style="text-align: center; border: 1px solid #000000; font-size: 9px;">Bank In</td>
                    <td style="text-align: center; border: 1px solid #000000; font-size: 9px;">Debit</td>
                    <td style="text-align: center; border: 1px solid #000000; font-size: 9px;">Netto Debit</td>
                    <td style="text-align: center; border: 1px solid #000000; font-size: 9px;">Card</td>
                    <td style="text-align: center; border: 1px solid #000000; font-size: 9px;">Netto Card</td>
                </tr>
                @php 
                    $subtotalBankIn = 0;
                    $subtotalDebit = 0;
                    $subtotalNettoDebit = 0;
                    $subtotalCard = 0;
                    $subtotalNettoCard = 0;
                @endphp
                @foreach ($branch['orders'] as $key => $order)
                    <tr>
                        <td style="text-align: right; border: 1px solid #000000; font-size: 9px;">{{ $key+1 }}</td>
                        <td style="text-align: right; border: 1px solid #000000; font-size: 9px;">
                            {{ date('d/m/Y', strtotime($order['op_payment_date'])) }}
                        </td>
                        <td style="text-align: right; border: 1px solid #000000; font-size: 9px;">
                            {{ $order['op_estimate_transfer_date'] ? date("d/m/Y", strtotime($order['op_estimate_transfer_date'])) : date('d/m/Y', strtotime('+'.$order['estimate_transfer'].' days', strtotime($order['o.created_at']))) }}
                        </td>
                        <td style="text-align: right; border: 1px solid #000000; font-size: 9px;">
                            {{ $order['o_code'] }}
                        </td>
                        <td style="text-align: right; border: 1px solid #000000; font-size: 9px;">
                            Rp {{ number_format($order['ts_bank_in']) }}
                        </td>
                        <td style="text-align: right; border: 1px solid #000000; font-size: 9px;">
                            Rp {{ number_format($order['ts_debit']) }}
                        </td>
                        <td style="text-align: right; border: 1px solid #000000; font-size: 9px;">
                            Rp {{ number_format($order['ts_netto_debit']) }}
                        </td>
                        <td style="text-align: right; border: 1px solid #000000; font-size: 9px;">
                            Rp {{ number_format($order['ts_card']) }}
                        </td>
                        <td style="text-align: right; border: 1px solid #000000; font-size: 9px;">
                            Rp {{ number_format($order['ts_netto_card']) }}
                        </td>
                    </tr>
                    @php 
                        $subtotalBankIn += $order['ts_bank_in'];
                        $subtotalDebit += $order['ts_debit'];
                        $subtotalNettoDebit += $order['ts_netto_debit'];
                        $subtotalCard += $order['ts_card'];
                        $subtotalNettoCard += $order['ts_netto_card'];
                    @endphp
                @endforeach
                <tr>
                    <td colspan="4" style="background-color: #ffffd7; text-align: right; border: 1px solid #000000; font-size: 9px;">
                        Sub Total
                    </td>
                    <td style="background-color: #ffffd7; text-align: right; border: 1px solid #000000; font-size: 9px;">
                        Rp {{ number_format($subtotalBankIn) }}
                    </td>
                    <td style="background-color: #ffffd7; text-align: right; border: 1px solid #000000; font-size: 9px;">
                        Rp {{ number_format($subtotalDebit) }}
                    </td>
                    <td style="background-color: #ffffd7; text-align: right; border: 1px solid #000000; font-size: 9px;">
                        Rp {{ number_format($subtotalNettoDebit) }}
                    </td>
                    <td style="background-color: #ffffd7; text-align: right; border: 1px solid #000000; font-size: 9px;">
                        Rp {{ number_format($subtotalCard) }}
                    </td>
                    <td style="background-color: #ffffd7; text-align: right; border: 1px solid #000000; font-size: 9px;">
                        Rp {{ number_format($subtotalNettoCard) }}
                    </td>
                </tr>
                @php 
                    $idxBranch++;
                    $totalBankIn += $subtotalBankIn;
                    $totalDebit += $subtotalDebit;
                    $totalNettoDebit += $subtotalNettoDebit;
                    $totalCard += $subtotalCard;
                    $totalNettoCard += $subtotalNettoCard;
                @endphp
            @endforeach
            <tr>
                <td colspan="4" style="background-color: #afd095; text-align: right; border: 1px solid #000000; font-size: 9px;">
                    Total
                </td>
                <td style="background-color: #afd095; text-align: right; border: 1px solid #000000; font-size: 9px;">
                    Rp {{ number_format($totalBankIn) }}
                </td>
                <td style="background-color: #afd095; text-align: right; border: 1px solid #000000; font-size: 9px;">
                    Rp {{ number_format($totalDebit) }}
                </td>
                <td style="background-color: #afd095; text-align: right; border: 1px solid #000000; font-size: 9px;">
                    Rp {{ number_format($totalNettoDebit) }}
                </td>
                <td style="background-color: #afd095; text-align: right; border: 1px solid #000000; font-size: 9px;">
                    Rp {{ number_format($totalCard) }}
                </td>
                <td style="background-color: #afd095; text-align: right; border: 1px solid #000000; font-size: 9px;">
                    Rp {{ number_format($totalNettoCard) }}
                </td>
            </tr>
            @php 
                $idxBank++; 
                $idxBranch = 0;
                $totalSaleGross += $totalBankIn + $totalDebit + $totalCard;
                $totalSaleNetto += $totalNettoDebit + $totalNettoCard;
            @endphp
        @endforeach
        <tr><td colspan="9"></td></tr>
        <tr>
            <td colspan="4" style="font-weight: 900; text-align: right; font-size: 12px;">
                Total Sale Gross
            </td>
            <td style="font-weight: 900; text-align: left; font-size: 12px;">
                Rp {{ number_format($totalSaleGross) }}
            </td>
        </tr>
        <tr>
            <td colspan="4" style="font-weight: 900; text-align: right; color: #ff0000; font-size: 12px;">
                Total Charge
            </td>
            <td style="font-weight: 900; text-align: left; color: #ff0000; font-size: 12px;">
                Rp {{ number_format(($totalSaleGross - $totalSaleNetto)) }}
            </td>
        </tr>
        <tr>
            <td colspan="4" style="font-weight: 900; text-align: right; color: #2a6099; font-size: 12px;">
                Total Sale Netto
            </td>
            <td style="font-weight: 900; text-align: left; color: #2a6099; font-size: 12px;">
                Rp {{ number_format($totalSaleNetto) }}
            </td>
        </tr>
    </table>
    <br />
</div>
