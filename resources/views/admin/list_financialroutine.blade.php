<?php
$menu_item_page = "financial_routine";
$menu_item_second = "list_financial_routine";
?>
@extends("admin.layouts.template")

@section('style')
<style>
    .bar {
        padding: 10px;
        color: #333;
        background: #fafafa;
        border: 1px solid #ccc;
    }
    .error {
        color: #ba3939;
        background: #ffe0e0;
        border: 1px solid #a33a3a;
    }
</style>
@endsection
@section("content")
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Financial Routine List</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#"
                            aria-expanded="false">
                            Financial Routine
                        </a>
                    </li>
                    <li class="breadcrumb-item active"
                        aria-current="page">
                        Financial Routine List
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="col-xs-12 col-sm-12 row"
                    style="margin: 0;padding: 0;">
                    <div class="col-xs-6 col-sm-4" style="margin-bottom: 0; padding: 0; display: inline-block">
                        <div class="form-group">
                            <label for="">Date</label>
                            <input type="date"
                                class="form-control"
                                id="filter_date"
                                name="filter_date"
                                value="{{ isset($_GET['filter_date']) ? $_GET['filter_date'] : '' }}">
                            <div class="validation"></div>
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
                        <div class="form-group">
                            <label for="filter_bank">
                                Filter By Bank Account
                            </label>
                            <input name="filter_bank"
                                id="filter_bank"
                                list="data_bank"
                                class="text-uppercase form-control"
                                placeholder="Search Bank Account"
                                @if($currentBank)
                                    value="{{ $currentBank['code'] . "-" . $currentBank['name'] }}"
                                @endif />
                            <span class="invalid-feedback">
                                <strong></strong>
                            </span>
        
                            <datalist id="data_bank">
                                <select class="form-control">
                                    <option value="All Bank"></option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank['code'] }}-{{ $bank['name'] }}"></option>
                                    @endforeach
                                </select>
                            </datalist>
                            <div class="validation"></div>
                        </div>
                    </div>
                    
                </div>

                <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
                    <div class="col-xs-6 col-sm-6" style="padding: 0;display: inline-block;">
                        <label for=""></label>
                        <div class="form-group">
                            <button id="btn-filter" type="button" class="btn btn-gradient-primary m-1"><span class="mdi mdi-filter"></span> Apply Filter</button>
                            <a href="{{ route('list_financial_routine') }}"
                                class="btn btn-gradient-danger m-1"
                                value="-">
                                <span class="mdi mdi-filter"></span> Reset Filter
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-12 grid-margin stretch-card" style="padding: 0;">
            <div class="card">
                <div class="card-body">
                    <h5 style="margin-bottom: 0.5em;">
                        Total: {{ $financialRoutines->total() }} data
                    </h5>
                    <div class="table-responsive"
                        style="border: 1px solid #ebedf2;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-right">No.</th>
                                    <th>Date</th>
                                    <th>Bank Account</th>
                                    <th class="text-center">Print</th>
                                    <th class="text-center" colspan="3">View/Edit/Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 0; @endphp
                                @foreach ($financialRoutines as $financialRoutine)
                                    <tr @if($financialRoutine->remains_saldo < 0) style="background-color: #ffe6e6" @endif>
                                        <td class="text-right">{{ ++$i }}</td>
                                        <td>{{ date('d/m/Y', strtotime($financialRoutine->routine_date)) }}</td>
                                        <td>
                                            {{ $financialRoutine->bankAccount->code }}
                                        </td>
                                        @can('detail-financial_routine')
                                        <td class="text-center">
                                            <a href="{{ route("print_financial_routine", ["id" => $financialRoutine->id]) }}"
                                                target="_blank">
                                                <i class="mdi mdi-printer" style="font-size: 24px; color: #1c1c1c;"></i>
                                            </a>
                                        </td>
                                        @endcan
                                        @can('detail-financial_routine')
                                        <td class="text-center">
                                            <a href="{{ route("detail_financial_routine", ["id" => $financialRoutine->id]) }}">
                                                <i class="mdi mdi-eye" style="font-size: 24px; color: #33b5e5;"></i>
                                            </a>
                                        </td>
                                        @endcan
                                        @can('edit-financial_routine')
                                        <td class="text-center">
                                            <a href="{{ route("edit_financial_routine", ["id" => $financialRoutine->id]) }}">
                                                <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                            </a>
                                        </td>
                                        @endcan
                                        @can('delete-financial_routine')
                                        <td class="text-center">
                                            <a class="btn-delete"
                                                data-toggle="modal"
                                                href="#delete-modal"
                                                onclick="submitDelete(this)"
                                                data-id="{{ $financialRoutine->id }}">
                                                <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                            </a>
                                        </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {!! $financialRoutines->appends(\Request::except('page'))->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade"
    id="delete-modal"
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
                    Are you sure you want to delete this Financial Routine?
                </h5>
            </div>
            <div class="modal-footer">
                <form method="POST"
                    action="{{ route("delete_financial_routine") }}">
                    @csrf
                    <input type="hidden" name="id" id="id-delete" />
                    <button type="submit" class="btn btn-gradient-danger">
                        Yes
                    </button>
                </form>
                <button class="btn btn-light" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).on("click", "#btn-filter", function(e){
	  var urlParamArray = new Array();
	  var urlParamStr = "";
      var filterBankAcc = document.getElementById("filter_bank");
      if (filterBankAcc) {
        filterBankAcc = filterBankAcc.value.trim()
        if (filterBankAcc !== "All Bank" && filterBankAcc.length) {
          const getBankCode = filterBankAcc.split("-");
          urlParamArray.push("filter_bank=" + getBankCode[0]);
        }
      }
      if($('#filter_date').val() != ""){
		urlParamArray.push("filter_date=" + $('#filter_date').val());
	  }
	  for (var i = 0; i < urlParamArray.length; i++) {
		if (i === 0) {
		  urlParamStr += "?" + urlParamArray[i]
		} else {
		  urlParamStr += "&" + urlParamArray[i]
		}
	  }

	  window.location.href = "{{route('list_financial_routine')}}" + urlParamStr;
	});
    function submitDelete(e) {
        document.getElementById("id-delete").value = e.dataset.id;
    }
</script>
@endsection
