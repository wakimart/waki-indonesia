<?php
$menu_item_page = "petty_cash";
$menu_item_second = "list_petty_cash";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    .table th img, .table td img {
        border-radius: 0% !important;
    
    .nav > li > a {
      position: relative;
      display: block;
      padding: 15px 20px;
    }
    .nav-tabs {
      border-bottom: 1px solid #ddd;
      background: #f2f3f2;
      border: 0;
      margin: 0 auto;
      padding: 0px 20px;
    }
    .nav-tabs > li {
      float: left;
      margin-bottom: -1px;
    }
    .nav-tabs > li > a {
      margin-right: 2px;
      line-height: 1.42857143;
      border: 1px solid transparent;
      border-radius: 4px 4px 0 0;
    }
    .nav-tabs > li > a:hover {
      border-color: #eee #eee #ddd;
    }
    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:hover,
    .nav-tabs > li.active > a:focus {
      color: #555;
      cursor: default;
      background-color: #fff;
      border-bottom-color: transparent;
      border: 0;
      padding: 15px 20px;
    }
    .nav-tabs.nav-justified {
      width: 100%;
      border-bottom: 0;
    }
    .nav-tabs.nav-justified > li {
      float: none;
    }
    .nav-tabs.nav-justified > li > a {
      margin-bottom: 5px;
      text-align: center;
    }
    .nav-tabs.nav-justified > .dropdown .dropdown-menu {
      top: auto;
      left: auto;
    }
    .nav-tabs li a:hover {background: #fff;}
    .nav-tabs li.active a {color: #30a5ff;}
    .nav-tabs li a {color: #999;}
    tbody p {
        margin: 0;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">List Petty Cash</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#order-dd"
                            aria-expanded="false"
                            aria-controls="order-dd">
                            Petty Cash
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        List
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12" style="margin-bottom: 0;">
                <div class="col-xs-12 col-sm-12 row"
                    style="margin: 0;padding: 0;">
                    <div class="col-xs-6 col-sm-4" style="display: inline-block;">
                        <div class="form-group">
                            <label for="">Start Date</label>
                            <input type="date"
                                class="form-control"
                                id="filter_start_date"
                                name="filter_start_date"
                                value="{{ isset($_GET['filter_start_date']) ? $_GET['filter_start_date'] : '' }}">
                            <div class="validation"></div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-4" style="display: inline-block;">
                        <div class="form-group">
                            <label for="">End Date</label>
                            <input type="date"
                                class="form-control"
                                id="filter_end_date"
                                name="filter_end_date"
                                value="{{ isset($_GET['filter_end_date']) ? $_GET['filter_end_date'] : '' }}">
                            <div class="validation"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3" style="display: inline-block;">
                    <div class="form-group">
                        <label for="">Filter By Bank Account</label>
                        <select class="form-control"
                            id="filter_bank"
                            name="filter_bank">
                            <option value="" selected="" disabled>
                                Choose Bank Account
                            </option>
                            @foreach ($banks as $bank)
                                <option @if(request()->input('filter_bank') == $bank->id) selected @endif
                                    value="{{ $bank['id'] }}">
                                    {{ $bank['code'] }} - {{ $bank['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <div class="validation"></div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3" style="display: inline-block;">
                    <div class="form-group">
                        <label for="">Filter By Type</label>
                        <select class="form-control"
                            id="filter_type"
                            name="filter_type">
                            <option value="" selected="">
                                All Type
                            </option>
                            @foreach ($pettyCashOutTypes as $pettyCashOutType)
                                <option @if(request()->input('filter_type') == $pettyCashOutType->id) selected @endif
                                    value="{{ $pettyCashOutType['id'] }}">
                                    {{ $pettyCashOutType['code'] }} - {{ $pettyCashOutType['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <div class="validation"></div>
                    </div>
                </div>

                <div class="col-xs-6 col-sm-6"
                    style="padding: 0; display: inline-block;">
                    <div class="form-group">
                        <button id="btn-filter"
                            type="button"
                            class="btn btn-gradient-primary m-1"
                            name="filter"
                            value="-">
                            <span class="mdi mdi-filter"></span>
                            Apply Filter
                        </button>
                        <button id="btn-filter_reset"
                            type="button"
                            class="btn btn-gradient-danger m-1"
                            name="filter_reset"
                            value="-">
                            <span class="mdi mdi-refresh"></span>
                            Reset Filter
                        </button>
                    </div>
                </div>
            </div>


            <div class="col-12">
                @if($currentBank)
                <h5>Bank : {{ $currentBank->code }} - {{ $currentBank->name }}</h5>
                <h5>Periode : {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}</h5>
                @else
                <h5>Please Choose Bank Account</h5>
                @endif

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    @php $tabActive = request()->query('tabActive') ?? "statement" @endphp
                    @foreach ($pettyCashTypes as $keyType => $pettyCashes)
                    <li class="nav-item">
                        <a class="nav-link @if ($tabActive == $keyType) active @endif"
                            data-toggle="tab" href="#tab_{{ $keyType }}">
                            {{ ucwords($keyType) }} @if($keyType != "statement") ({{ count($pettyCashes) }}) @endif
                        </a>
                    </li>
                    @endforeach
                </ul>

                <div class="tab-content" id="myTabContent">
                    @foreach ($pettyCashTypes as $keyType => $pettyCashes)
                    <div id="tab_{{ $keyType }}" class="tab-pane fade in @if ($tabActive == $keyType) active show @endif" style="overflow-x:auto;">
                        <div class="col-12 grid-margin stretch-card" style="padding: 0;">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row justify-content-center mb-3">
                                        <a href="{{ route('print_petty_cash', array_merge(request()->input(), ['type' => $keyType])) }}"
                                            target="_blank" class="btn btn-gradient-info m-1 btn-print">
                                            Print {{ ucwords($keyType) }}
                                        </a>
                                    </div>
                                    {{-- Tab Statement --}}
                                    @if($keyType == "statement")
                                    <div class="table-responsive" style="border: 1px solid #ebedf2;">
                                        <div class="table-responsive"
                                            style="border: 1px solid #ebedf2;">
                                            <table class="table table-bordered" id="myTable">
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
                                                    @if($lastPTCClosedBook)
                                                        <tr>
                                                            <td>
                                                                {{ date('01/m/Y', strtotime($startDate)) }}
                                                            </td>
                                                            <td>-</td>
                                                            <td>SALDO</td>
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
                                                        @else
                                                        <td>{{ $pettyCash->pettyCash->bankAccount['code'] }} - {{ $pettyCash->pettyCash->bankAccount['name'] }}</td>
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
                                    </div>

                                    {{-- Tab In/Out --}}
                                    @else
                                    <h5 style="margin-bottom: 0.5em;">Total : {{count($pettyCashes)}} data</h5>
                                    <div class="table-responsive" style="border: 1px solid #ebedf2;">
                                        <div class="table-responsive"
                                            style="border: 1px solid #ebedf2;">
                                            <table class="table table-bordered" id="myTable">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Code</th>
                                                        @if($keyType == "out")
                                                            <th>Bank/Type</th>
                                                        @endif
                                                        <th>Description</th>
                                                        <th>Nominal</th>
                                                        <th colspan="3" class="text-center">
                                                            View/Edit/Delete
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $total_nominal = 0; @endphp
                                                    @foreach($pettyCashes as $pettyCash)
                                                    @php
                                                        $count_ptcd = $pettyCash->pettyCashDetail->count();
                                                        $bank_petty_cash_type = $pettyCash->pettyCashDetail->count() > 0 ? $pettyCash->pettyCashDetail[0]->petty_cash_out_bank_account_id ? "bank" : "account" : "";
                                                        if ($count_ptcd == 0) $count_ptcd = 1;
                                                    @endphp
                                                    <tr>
                                                        <td rowspan="{{ $count_ptcd }}">
                                                            {{ date('d/m/Y', strtotime($pettyCash->transaction_date)) }}
                                                        </td>
                                                        <td rowspan="{{ $count_ptcd }}">
                                                            {{ $pettyCash->code }}
                                                        </td>
                                                        @if($bank_petty_cash_type)
                                                        @if($keyType == "out")
                                                        <td>{{ $bank_petty_cash_type == "bank"
                                                            ? $pettyCash->pettyCashDetail[0]->pettyCashOutBankAccount['code'] . ' - ' . $pettyCash->pettyCashDetail[0]->pettyCashOutBankAccount['name']
                                                            : $pettyCash->pettyCashDetail[0]->pettyCashOutType['code'] . ' - ' . $pettyCash->pettyCashDetail[0]->pettyCashOutType['name'] }}
                                                        </td>
                                                        @endif
                                                        @else
                                                        <td></td>
                                                        @endif
                                                        <td>
                                                            <?php echo $pettyCash->pettyCashDetail[0]->description ?? '' ?>
                                                        </td>
                                                        <td class="text-right">
                                                            {{ number_format($pettyCash->pettyCashDetail[0]->nominal ?? 0) }}
                                                        </td>
                                                        <td class="text-center" rowspan="{{ $count_ptcd }}">
                                                            @if (Gate::check('detail-petty_cash'))
                                                                <a href="{{ route('detail_petty_cash', ['id' => $pettyCash->id]) }}">
                                                                    <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(76 172 245);"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td class="text-center" rowspan="{{ $count_ptcd }}">
                                                            @if ($pettyCash->type == "in")
                                                                @if (Gate::check('edit-petty_cash_in') && !$pettyCash->petty_cash_closed_book_id)
                                                                <a href="{{ route('edit_petty_cash_in', ['id' => $pettyCash->id]) }}">
                                                                    <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                                                </a>
                                                                @endif
                                                            @else
                                                                @if (Gate::check('edit-petty_cash_out') && !$pettyCash->petty_cash_closed_book_id)
                                                                <a href="{{ route('edit_petty_cash_out', ['id' => $pettyCash->id]) }}">
                                                                    <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                                                </a>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td class="text-center" rowspan="{{ $count_ptcd }}">
                                                            @if (Gate::check('delete-petty_cash') && !$pettyCash->petty_cash_closed_book_id)
                                                                <a class="btn-delete disabled"
                                                                    data-toggle="modal"
                                                                    href="#deleteDoModal"
                                                                    onclick="submitDelete(this)"
                                                                    data-id="{{ $pettyCash->id }}">
                                                                    <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @php $total_nominal += $pettyCash->pettyCashDetail[0]->nominal ?? 0; @endphp
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
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Delete -->
<div class="modal fade"
    id="deleteDoModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 style="text-align:center;">
                    Are you sure to delete this petty cash?
                </h5>
            </div>
            <div class="modal-footer">
                <form id="frmDelete"
                    method="post"
                    action="{{ route('delete_petty_cash') }}">
                    @csrf
                    <input type="hidden" name="id" id="id-delete" />
                    <button type="submit"
                        class="btn btn-gradient-danger mr-2">
                        Yes
                    </button>
                </form>
                <button class="btn btn-light" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Delete -->
@endsection

@section("script")
<script type="application/javascript">
function submitDelete(e) {
    document.getElementById("id-delete").value = e.dataset.id;
}
$(document).ready(function (e) {
    $("#btn-filter").click(function (e) {
        var urlParamArray = new Array();
        var urlParamStr = "";
        if($('#filter_bank').val()){
            urlParamArray.push("filter_bank=" + $('#filter_bank').val());
        }
        if($('#filter_type').val()){
            urlParamArray.push("filter_type=" + $('#filter_type').val());
        }
        if($('#filter_start_date').val() != ""){
            urlParamArray.push("filter_start_date=" + $('#filter_start_date').val());
        }
        if($('#filter_end_date').val() != ""){
            urlParamArray.push("filter_end_date=" + $('#filter_end_date').val());
        }
        for (var i = 0; i < urlParamArray.length; i++) {
            if (i === 0) {
                urlParamStr += "?" + urlParamArray[i]
            } else {
                urlParamStr += "&" + urlParamArray[i]
            }
        }
        window.location.href = "{{ route('list_petty_cash') }}" + urlParamStr;
    });
    $("#btn-filter_reset").click(function (e) {
        window.location.href = "{{ route('list_petty_cash') }}";
    });
});
</script>
@endsection
