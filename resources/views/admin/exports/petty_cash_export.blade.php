<style type="text/css">
    th,
    td {
        border: 1px solid black;
        text-align: center;
    }
</style>
<div style="border: 1px solid #ebedf2;">
    <table class="table table-bordered">               
        <tr>
            <th style="font-weight: 900; font-size: 14px">Petty Cash Report</th>
        </tr>
        <tr>
            <th style="font-weight: 900; font-size: 12px">Bank : {{ $currentBank->code }} - {{ $currentBank->name }}</th>
        </tr>
        <tr>
            <th style="font-weight: 900; font-size: 12px">Periode : {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}</th>
        </tr>
        <thead>
            <tr class="text-center">                
                <th style="font-weight: 900;">Date</th>
                <th style="font-weight: 900;">Code</th>
                <th style="font-weight: 900;">Bank/Type</th>
                <th style="font-weight: 900;">Out</th>
                <th style="font-weight: 900;">In</th>
                <th style="font-weight: 900;">Saldo</th>
                <th style="font-weight: 900;">Description</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_nominal_in = 0;
                $total_nominal_out = 0;
                $total_nominal_saldo = 0;
            @endphp
            @if($lastPTCClosedBook)
                <tr>
                    <td class="text-center">
                        {{ date('01/m/Y', strtotime($startDate)) }}
                    </td>
                    <td class="text-center">-</td>
                    <td class="text-center">SALDO</td>
                    <td class="text-right">0</td>
                    <td class="text-right">0</td>
                    <td class="text-right">{{ number_format($lastPTCClosedBook->nominal) }}</td>
                    <td></td>
                </tr>
                @php $total_nominal_saldo += $lastPTCClosedBook->nominal; @endphp
            @endif
            @foreach($pettyCashes as $pettyCash)
            @php
                $bank_petty_cash_type = $pettyCash->petty_cash_out_bank_account_id ? "bank" : "account";
            @endphp
            <tr>
                <td class="text-center">
                    {{ date('d/m/Y', strtotime($pettyCash->transaction_date)) }}
                </td>
                <td class="text-center">
                    {{ $pettyCash->code }}
                </td>
                @if($pettyCash->type == "out")
                <td class="text-center">{{ $bank_petty_cash_type == "bank"
                    ? $pettyCash->pettyCashOutBankAccount['code'] . ' - ' . $pettyCash->pettyCashOutBankAccount['name']
                    : $pettyCash->pettyCashOutType['code'] . ' - ' . $pettyCash->pettyCashOutType['name'] }}
                </td>
                @else
                <td class="text-center">{{ $pettyCash->pettyCash->bankAccount['code'] }} - {{ $pettyCash->pettyCash->bankAccount['name'] }}</td>
                @endif
                <td class="text-right">
                    @if($pettyCash->type == "out")
                        @php
                            $total_nominal_out += $pettyCash->nominal;
                            $total_nominal_saldo -= $pettyCash->nominal;
                        @endphp
                        {{ number_format($pettyCash->nominal) }}
                    @else
                        0
                    @endif
                </td>
                <td class="text-right">
                    @if($pettyCash->type == "in")
                        @php
                            $total_nominal_in += $pettyCash->nominal;
                            $total_nominal_saldo += $pettyCash->nominal
                        @endphp
                        {{ number_format($pettyCash->nominal) }}
                    @else
                        0
                    @endif
                </td>
                <td class="text-right">
                    {{ number_format($total_nominal_saldo) }}
                </td>
                <td>
                    {{ $pettyCash->description }}
                </td>
            </tr>
            @endforeach
            <tr>
                <th colspan="3" class="text-right">Total</th>
                <th class="text-right">{{ number_format($total_nominal_out) }}</th>
                <th class="text-right">{{ number_format($total_nominal_in) }}</th>
                <th></th>
                <th></th>
            </tr>
        </tbody>
    </table>
    <br />
</div>
