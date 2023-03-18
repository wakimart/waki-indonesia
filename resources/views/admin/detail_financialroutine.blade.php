<?php
$menu_item_page = "financial_routine";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    #intro {
        padding-top: 2em;
    }
    button{
        background: #1bb1dc;
        border: 0;
        border-radius: 3px;
        padding: 8px 30px;
        color: #fff;
        transition: 0.3s;
    }
    table{
        margin: 1em;
        font-size: 14px;
    }
    table thead{
        background-color: #8080801a;
        text-align: center;
    }
    table td{
        border: 0.5px #8080801a solid;
        padding: 0.5em;
    }
    .right{
        text-align: right;
    }
    .pInTable{
        margin-bottom: 6pt !important;
        font-size: 10pt;
    }
    input, select, textarea{
        border-radius: 0 !important;
        box-shadow: none !important;
        border: 1px solid #dce1ec !important;
        font-size: 14px !important;
        width: 100% !important;
    }
    .select2-results__options{
        max-height: 15em;
        overflow-y: auto;
    }
    .imagePreview {
        width: 100%;
        height: 150px;
        background-position: center center;
        background-color: #fff;
        background-size: cover;
        background-repeat: no-repeat;
        display: inline-block;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">

                    <section id="intro" class="clearfix">
                        <div class="container">
                            <div class="row justify-content-center">
                                <h2 @if($financialRoutine->remains_saldo < 0) style="color: red;" @endif>
                                    FINANCIAL ROUTINE DETAIL @if($financialRoutine->remains_saldo < 0) (MINUS) @endif
                                </h2>
                            </div>
                            <div class="row justify-content-center">
                                <table class="col-md-12">
                                    <thead>
                                        <td>Financial Routine Date</td>
                                        <td>Financial Routine Bank</td>
                                    </thead>
                                    <tr>
                                        <td>{{ date("d/m/Y", strtotime($financialRoutine->routine_date )) }}</td>
                                        <td class="right">{{ $financialRoutine->bankAccount->code }} - {{ $financialRoutine->bankAccount->name }}</td>
                                    </tr>
                                </table>
                                <table class="col-md-12">
                                    <colgroup>
                                        <col class="col-md-6">
                                        <col class="col-md-6">
                                    </colgroup>
                                    <thead>
                                        <td colspan="2">Last Month Financial Routine Data</td>
                                    </thead>
                                    <tr>
                                        <td>Last Month Remains Saldo : </td>
                                        <td>Rp. {{ number_format($financialRoutine->financialRoutine['remains_saldo']) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Last Month's Sale That Hasn't Been Bank In : </td>
                                        <td>Rp. {{ number_format($financialRoutine->financialRoutine['remains_sales']) }}</td>
                                    </tr>
                                </table>
                                <table class="col-md-12">
                                    <colgroup>
                                        <col class="col-md-6">
                                        <col class="col-md-6">
                                    </colgroup>
                                    <thead>
                                        <td colspan="2">Financial Routine Data</td>
                                    </thead>
                                    <tr>
                                        <td>Total Sale : </td>
                                        <td>Rp. {{ number_format($financialRoutine->total_sale) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Interest / Administration Bank : </td>
                                        <td>Rp. {{ number_format($financialRoutine->bank_interest) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Etc In : </td>
                                        <td>Rp. {{ number_format($financialRoutine->etc_in) }}</td>
                                    </tr>
                                </table>
                                <table class="col-md-12">
                                    <colgroup>
                                        <col class="col-md-6">
                                        <col class="col-md-6">
                                    </colgroup>
                                    <tr>
                                        <td>Tax / Charge Bank : </td>
                                        <td>Rp. {{ number_format($financialRoutine->bank_tax) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Etc Out : </td>
                                        <td>Rp. {{ number_format($financialRoutine->etc_out) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Sale That Hasn't Been Bank In : </td>
                                        <td>Rp. {{ number_format($financialRoutine->remains_sales) }}</td>
                                    </tr>
                                    <tr @if($financialRoutine->remains_saldo < 0) style="color: red; font-weight: 600;" @endif>
                                        <td>Remains Saldo : </td>
                                        <td>Rp. {{ number_format($financialRoutine->remains_saldo) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Created at : </td>
                                        <td>{{ $financialRoutine->created_at }}</td>
                                    </tr>
                                </table>

                                <table class="col-md-12">
                                    <thead>
                                        <td colspan="7">Petty Cash</td>
                                    </thead>
                                    <thead style="background-color: #80808012 !important">
                                        <td>No</td>
                                        <td>Date</td>
                                        <td>Description</td>
                                        <td>Nominal</td>
                                        @if (Gate::check('edit-financial_routine') || Gate::check('delete-financial_routine'))
                                            <td colspan="2">Edit/Delete</td>
                                        @endif
                                    </thead>
                                    @php $sub_total_fr_details = 0; @endphp
                                    @foreach ($financialRoutine->financialRoutineTransaction as $frTransaction)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ date('d/m/Y', strtotime($frTransaction->transaction_date)) }}</td>
                                        <td>
                                            To: {{ $frTransaction->bankAccount['code'] }} ({{ $frTransaction->bankAccount['name'] }})
                                            <br>{{ $frTransaction->description }}
                                        </td>
                                        <td>Rp. {{ number_format($frTransaction->transaction) }}</td>
                                        @can('edit-financial_routine')
                                            <td style="text-align: center;">
                                                <button value="{{ $frTransaction['id'] }}"
                                                    type="button"
                                                    data-toggle="modal"
                                                    data-target="#editDetailTransactionModal"
                                                    class="btn p-0 btn-delete btn-edit_financial_routine_transaction">
                                                    <i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
                                                </button>
                                            </td>
                                        @endcan
                                        @can('delete-financial_routine')
                                            <td style="text-align: center;">
                                                <button value="{{ route('delete_financial_routine_transaction', ['id' => $frTransaction['id']])}}"
                                                    type="button"
                                                    data-toggle="modal"
                                                    data-target="#deleteDoModal"
                                                    class="btn p-0 btn-delete btn-delete_financial_routine_transaction">
                                                    <i class="mdi mdi-delete" style="font-size: 24px; color:#fe7c96;"></i>
                                                </button>
                                            </td>
                                        @endcan
                                    </tr>
                                    @php $sub_total_fr_details += $frTransaction->transaction; @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="3" class="text-right" style="background-color: #80808012 !important">Sub Total</td>
                                        <td>Rp. {{ number_format($sub_total_fr_details) }}</td>
                                        <td colspan="4" style="background-color: #f2f2f2;" rowspan="3"></td>
                                    </tr>
                                </table>

                                @if($financialRoutine->description != null)
                                <table class="col-md-12">
                                    <thead>
                                        <td>Description</td>
                                    </thead>
                                    <tr>
                                        <td><?php echo $financialRoutine->description; ?></td>
                                    </tr>
                                </table>
                                @endif
                                <div class="mb-3">
                                    <button type="button" class="btn btn-gradient-success" 
                                        data-toggle="modal"
                                        data-target="#addDetailTransactionModal" >
                                        Add Detail
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>

                </div>
            </div>

            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <section id="intro" class="clearfix">
                        <div class="container">
                            <div class="row justify-content-center">
                                <h2>FINANCIAL ROUTINE HISTORY LOG</h2>
                            </div>
                            <div class="row justify-content-center table-responsive">
                                <table class="col-md-12">
                                    <thead>
                                        <td>No.</td>
                                        <td>Action</td>
                                        <td>User</td>
                                        <td>Change</td>
                                        <td>Time</td>
                                    </thead>
                                    @if($historyUpdateFR != null)
                                    @foreach($historyUpdateFR as $key => $historyUpdateFR)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$historyUpdateFR->method}}</td>
                                        <td>{{$historyUpdateFR->name}}</td>
                                        <?php $dataChange = json_decode($historyUpdateFR->meta, true);?>
                                        <td>
                                        @foreach ($dataChange['dataChange'] as $key=>$value)
                                            <b>{{$key}}</b>: {{ is_array($value) ? json_encode($value) : $value }}<br/>
                                        @endforeach
                                        </td>
                                        <td>{{ date("d/m/Y H:i:s", strtotime($historyUpdateFR->created_at)) }}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Detail Transaction -->
<div class="modal fade"
    id="addDetailTransactionModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="frmAddDetail"
                method="post"
                action="{{ route("store_financial_routine_transaction") }}" autocomplete="off">
                @csrf
                <input type="hidden" name="financial_routine_id" value="{{ $financialRoutine['id'] }}">
                <div class="modal-header">
                    <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 style="text-align: center;">
                        Add Detail
                    </h5>
                    <br>
                    <div class="form-group">
                        <label for="">Date <span id="tdetail_date_desc">(max {{ $financialRoutine->routine_date }})</span></label>
                        <input type="date" id="tdetail_date" name="tdetail_date" value=""
                            class="form-control" 
                            required max="{{ $financialRoutine->routine_date }}">
                    </div>
                    <div class="form-group">
                        <label for="">Bank</label>
                        <select id="tdetail_bank" name="tdetail_bank"
                            class="form-control" 
                            required>
                            <option selected disabled value="">Choose Bank</option>
                            @foreach ($banks as $bank)
                                @if(Route::currentRouteName() === 'detail_financial_routine_branch')
                                    @if($bank->branch['bank_id'] == $financialRoutine->bank_id)
                                        <option value="{{ $bank->id }}">
                                            {{ $bank->code }} - {{ $bank->name }} ({{ ucwords($bank->type) }})
                                        </option>
                                    @endif
                                @else
                                    <option value="{{ $bank->id }}">
                                        {{ $bank->code }} - {{ $bank->name }} ({{ ucwords($bank->type) }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Nominal</label>
                        <input type="text" id="tdetail_nominal" name="tdetail_nominal" value=""
                            class="form-control" 
                            data-type="currency"
                            required />
                    </div>
                    <div class="form-group">
                        <label for="">Description (Optional)</label>
                        <textarea id="tdetail_description" name="tdetail_description" class="form-control" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer footer-cash">
                    <button type="submit"
                        id="submitFrmAddDetail"
                        class="btn btn-gradient-success mr-2">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Add Detail Transaction -->
<!-- Modal Edit Financial Routine Transaction -->
<div class="modal fade"
    id="editDetailTransactionModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="frmEditDetail"
                method="post"
                action="{{ route("update_financial_routine_transaction") }}" autocomplete="off">
                @csrf
                <input type="hidden" id="editFRT-financial_routine_id" name="financial_routine_id" value="{{ $financialRoutine['id'] }}">
                <input type="hidden" id="editFRT-fr_transaction_id" name="fr_transaction_id" value="">
                <div class="modal-header">
                    <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 style="text-align: center;">
                        Edit Detail
                    </h5>
                    <br>
                    <div class="form-group">
                        <label for="">Date <span id="editFRT-tdetail_date_desc">(max {{ $financialRoutine->routine_date }})</span></label>
                        <input type="date" id="editFRT-tdetail_date" name="tdetail_date" value=""
                            class="form-control" 
                            required max="{{ $financialRoutine->routine_date }}">
                    </div>
                    <div class="form-group">
                        <label for="">Bank</label>
                        <select id="editFRT-tdetail_bank" name="tdetail_bank"
                            class="form-control" 
                            required>
                            <option selected disabled value="">Choose Bank</option>
                            @foreach ($banks as $bank)
                            <option value="{{ $bank->id }}">
                                {{ $bank->code }} - {{ $bank->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Nominal</label>
                        <input type="text" id="editFRT-tdetail_nominal" name="tdetail_nominal" value=""
                            class="form-control" 
                            data-type="currency"
                            required />
                    </div>
                    <div class="form-group">
                        <label for="">Description (Optional)</label>
                        <textarea id="editFRT-tdetail_description" name="tdetail_description" class="form-control" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer footer-cash">
                    <button type="submit"
                        id="submitFrmEditDetail"
                        class="btn btn-gradient-success mr-2">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Edit Financial Routine Transaction -->
 <!-- Modal Delete Financial Routine Transaction -->
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
                <h5 style="text-align: center;">
                    Are You Sure to Delete this Petty Cash?
                </h5>
            </div>
            <div class="modal-footer">
                <form id="frmDelete" method="post" action="">
                    {{ csrf_field() }}
                    <button type="submit"
                        class="btn btn-gradient-danger mr-2">
                        Yes
                    </button>
                </form>
                <button class="btn btn-light" data-dismiss="modal">
                    No
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Delete -->
@endsection
@section('script')
<script>
$(document).ready(function() {
    $(document).on("input", 'input[data-type="currency"]', function() {
        $(this).val(numberWithCommas($(this).val()));
    });

    // Edit Order Payment
    $(".btn-edit_financial_routine_transaction").click(function() {
        cleareditDetailTransactionModal();
        var fr_transaction_id = $(this).val();
        $.ajax({
            method: "post",
            url: "{{ route('edit_financial_routine_transaction') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                financial_routine_id: "{{ $financialRoutine['id'] }}",
                fr_transaction_id: fr_transaction_id
            },
            success: function(data) {
                if (data.status == 'success') {
                    const result = data.result;
                    $("#editFRT-fr_transaction_id").val(fr_transaction_id);
                    $("#editFRT-tdetail_date").val(result.transaction_date);
                    $("#editFRT-tdetail_bank").val(result.bank_account_id);
                    $("#editFRT-tdetail_nominal").val(numberWithCommas(result.transaction));
                    $("#editFRT-tdetail_description").val(result.description);
                } else {
                    alert(data.result);
                }
            },
            error: function(data) {
                alert("Error!");
            }
        });
    });

    $("#frmAddDetail").submit(function() {
        $('#frmAddDetail input[data-type="currency"]').each(function() {
            $(this).val(numberNoCommas($(this).val()));
        });
    });

    $("#frmEditDetail").submit(function() {
        $('#frmEditDetail input[data-type="currency"]').each(function() {
            $(this).val(numberNoCommas($(this).val()));
        });
    });

    $(".btn-delete_financial_routine_transaction").click(function(e) {
        $("#frmDelete").attr("action",  $(this).val());
    });
});

function cleareditDetailTransactionModal() {
    $("#editFRT-fr_transaction_id").val("");
    $("#editFRT-tdetail_date").val("");
    $("#editFRT-tdetail_bank").val("");
    $("#editFRT-tdetail_nominal").val("");
    $("#editFRT-tdetail_description").val("");
}

function numberWithCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
}

function numberNoCommas(x) {
    var parts = x.toString().split(".");
    parts[0] = parts[0].replace(/\D/g, "");
    return parts.join(".");
}
</script>
@endsection
