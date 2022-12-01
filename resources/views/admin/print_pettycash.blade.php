<?php
$menu_item_page = "petty_cash";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    #intro {
        padding-top: 2em;
        min-width: 0;
    }
    input[readonly], textarea[readonly]{
        background-color: #fff !important;
    }
    input[readonly]:focus, textarea[readonly]:focus{
        outline: none !important;
        border:1px solid #ebedf2;
    }
    .table td{
      padding: 0.75rem !important;
      white-space:normal !important;
    }

    @media screen {
        div.divFooter {
            display: none;
        }
    }
    @media print {
        div.divFooter {
            position: fixed;
            bottom: 0;
        }
    }
</style>
@endsection

@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 mb-3">
                <div class="row justify-content-center">
                    <button id="btn-print"
                        type="button"
                        class="btn btn-gradient-info m-1">
                        Print
                        <span>
                            <i class="mdi mdi-printer"
                                style="margin-left: 5px;
                                    font-size: 24px;
                                    vertical-align: middle;">
                            </i>
                        </span>
                    </button>
                </div>
            </div>
            <!-- LAYOUT PRINT -->
            <div id="element-to-print" class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="text-center">Petty Cash {{ ucwords($keyType) }}</h5>
                        <h5>Bank : {{ $currentBank->code }} - {{ $currentBank->name }}</h5>
                        <h5>Periode : {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}</h5>
                        {{-- Tab Statement --}}
                        @if($keyType == "statement")
                        <div class="table-responsive"
                            style="border: 1px solid #ebedf2;">
                            <table class="table table-bordered w-100" id="myTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Code</th>
                                        <th>Bank/Type</th>
                                        <th>Out</th>
                                        <th>In</th>
                                        <th>Saldo</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_nominal_in = 0;
                                        $total_nominal_out = 0;
                                        $total_nominal_saldo = 0;
                                    @endphp
                                    @foreach($pettyCashes as $pettyCash)
                                    @php
                                        $bank_petty_cash_type = $pettyCash->petty_cash_out_bank_account_id ? "bank" : "account";
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ date('d/m/Y', strtotime($pettyCash->transaction_date)) }}
                                        </td>
                                        <td>
                                            {{ $pettyCash->code }}
                                        </td>
                                        @if($pettyCash->type == "out")
                                        <td>{{ $bank_petty_cash_type == "bank"
                                            ? $pettyCash->pettyCashOutBankAccount['code'] . ' - ' . $pettyCash->pettyCashOutBankAccount['name']
                                            : $pettyCash->pettyCashOutType['code'] . ' - ' . $pettyCash->pettyCashOutType['name'] }}
                                        </td>
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
                                            <?php echo $pettyCash->description ?>
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
                            <br>
                        </div>

                        {{-- Tab In/Out --}}
                        @else
                        <div class="table-responsive"
                            style="border: 1px solid #ebedf2;">
                            <table class="table table-bordered" id="myTable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Code</th>
                                        <th>Bank/Account</th>
                                        <th>Description</th>
                                        <th>Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total_nominal = 0; @endphp
                                    @foreach($pettyCashes as $pettyCash)
                                    @php
                                        $count_ptcd = $pettyCash->pettyCashDetail->count();
                                        $bank_petty_cash_type = $pettyCash->pettyCashDetail[0]->petty_cash_out_bank_account_id ? "bank" : "account";
                                    @endphp
                                    <tr>
                                        <td rowspan="{{ $count_ptcd }}">
                                            {{ date('d/m/Y', strtotime($pettyCash->transaction_date)) }}
                                        </td>
                                        <td rowspan="{{ $count_ptcd }}">
                                            {{ $pettyCash->code }}
                                        </td>
                                        @if($keyType == "out")
                                        <td>{{ $bank_petty_cash_type == "bank"
                                            ? $pettyCash->pettyCashDetail[0]->pettyCashOutBankAccount['code'] . ' - ' . $pettyCash->pettyCashDetail[0]->pettyCashOutBankAccount['name']
                                            : $pettyCash->pettyCashDetail[0]->pettyCashOutType['code'] . ' - ' . $pettyCash->pettyCashDetail[0]->pettyCashOutType['name'] }}
                                        </td>
                                        @endif
                                        <td>
                                            <?php echo $pettyCash->pettyCashDetail[0]->description ?>
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($pettyCash->pettyCashDetail[0]->nominal) }}
                                        </td>
                                    </tr>
                                    @php $total_nominal += $pettyCash->pettyCashDetail[0]->nominal; @endphp
                                        @if ($count_ptcd > 1)
                                            @for ($i = 1; $i < $count_ptcd; $i++)
                                                @php
                                                    $bank_petty_cash_type = $pettyCash->pettyCashDetail[$i]->petty_cash_out_bank_account_id ? "bank" : "account";
                                                @endphp
                                                <tr>
                                                    @if($keyType == "out")
                                                    <td>{{ $bank_petty_cash_type == "bank"
                                                        ? $pettyCash->pettyCashDetail[$i]->pettyCashOutBankAccount['code'] . ' - ' . $pettyCash->pettyCashDetail[$i]->pettyCashOutBankAccount['name']
                                                        : $pettyCash->pettyCashDetail[$i]->pettyCashOutType['code'] . ' - ' . $pettyCash->pettyCashDetail[$i]->pettyCashOutType['name'] }}
                                                    </td>
                                                    @endif
                                                    <td>
                                                        {{ $pettyCash->pettyCashDetail[$i]->description }}
                                                    </td>
                                                    <td class="text-right">
                                                        {{ number_format($pettyCash->pettyCashDetail[$i]->nominal) }}
                                                    </td>
                                                </tr>
                                                @php $total_nominal += $pettyCash->pettyCashDetail[$i]->nominal; @endphp
                                            @endfor
                                        @endif
                                    @endforeach
                                    <tr>
                                        <th colspan="{{ $keyType == "out" ? '4' : '3' }}" class="text-right">Total</th>
                                        <th class="text-right">{{ number_format($total_nominal) }}</th>
                                        <th colspan="3"></th>
                                    </tr>
                                </tbody>
                            </table>
                            <br>
                        </div>
                        @endif
                        <div class="divFooter">Print : {{ date('d/m/Y H:i:s') }}</div>
                    </div>
                </div>
            </div>
            <!-- end layout print -->
        </div>
    </div>
</div>

@endsection

@section("script")
<script type="application/javascript">
$(document).ready(function() {
    $(document).on("click", '#btn-print', function(){
        $("#success-alert").hide();
        $(".hide-print").hide();
        var printContents = document.getElementById("element-to-print").innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        return true;
    });
});
</script>
@endsection
