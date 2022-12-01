<?php
$menu_item_page = "petty_cash";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
    integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
<link rel="stylesheet" href="{{ asset("css/lib/select2/select2-bootstrap4.min.css") }}" />
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
    .select2-selection__rendered {
        line-height: 40px !important;
    }
    .select2-container--default
    .select2-selection--single
    .select2-selection__arrow {
        top: 10px;
    }
    .select2-container .select2-selection--single {
        height: 45px !important;
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
    .del {
        position: absolute;
        top: 0px;
        right: 10px;
        width: 30px;
        height: 30px;
        text-align: center;
        line-height: 30px;
        background-color: rgba(255, 255, 255, 0.6);
        cursor: pointer;
    }
    .imgUp {
        max-height: 150px;
        max-width: 150px;
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
                                <h2>PETTY CASH DETAIL {{ strtoupper($pettyCash->type) }}</h2>
                            </div>
                            <div class="row justify-content-center">
                                <table class="col-md-12">
                                    <thead>
                                        <td>Petty Cash Code</td>
                                        <td>Petty Cash Transaction Date</td>
                                    </thead>
                                    <tr>
                                        <td>{{ $pettyCash->code }}</td>
                                        <td class="right">{{ date("d/m/Y", strtotime($pettyCash->transaction_date )) }}</td>
                                    </tr>
                                </table>
                                <table class="col-md-12">
                                    <colgroup>
                                        <col class="col-md-6">
                                        <col class="col-md-6">
                                    </colgroup>
                                    <thead>
                                        <td colspan="2">Petty Cash</td>
                                    </thead>
                                    <tr>
                                        <td>Type : </td>
                                        <td>{{ ucwords($pettyCash->type) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Temp No : </td>
                                        <td>{{ $pettyCash->temp_no }}</td>
                                    </tr>
                                    <tr>
                                        <td>Bank Account :  </td>
                                        <td>{{ $pettyCash->bankAccount->code }} - {{ $pettyCash->bankAccount->name }}</td>
                                    </tr>
                                </table>


                                {{-- Petty Cash Detail Type == IN --}}
                                @if ($pettyCash->type == "in")
                                    <table class="col-md-12 mt-0">
                                        <colgroup>
                                            <col class="col-md-6">
                                            <col class="col-md-6">
                                        </colgroup>
                                        <tr>
                                            <td>Nominal : </td>
                                            <td>Rp. {{ number_format($pettyCash->pettyCashDetail[0]->nominal) }}</td>
                                        </tr>
                                    </table>
                                    <table class="col-md-12">
                                        <thead>
                                            <td>Description</td>
                                        </thead>
                                        <tr>
                                            <td><?php echo $pettyCash->pettyCashDetail[0]->description; ?></td>
                                        </tr>
                                    </table>
                                    <table class="col-md-12">
                                        <thead>
                                            <td>Image Evidence</td>
                                        </thead>
                                        <tr>
                                            <td>
                                                @php $arrImages = json_decode($pettyCash->pettyCashDetail[0]->evidence_image, true) ?? []; @endphp
                                                @foreach ($arrImages as $arrImage)
                                                <div class="d-inline-block imgUp" >
                                                    <a href="{{ asset("sources/pettycash/" . $arrImage) }}" target="_blank" >
                                                    <img class="img-thumbnail"
                                                    src="{{ asset("sources/pettycash/" . $arrImage) }}"
                                                    style="max-height: 300px;" />
                                                    </a>
                                                </div>
                                                @endforeach
                                            </td>
                                        </tr>
                                    </table>
                                @endif

                                
                                {{-- Petty Cash Detail Type == OUT --}}
                                @if($pettyCash->type == "out")
                                    <table id="body_petty_cash_detail" class="col-md-12">
                                        <thead>
                                            <td colspan="7">Petty Cash Detail</td>
                                        </thead>
                                        <thead style="background-color: #80808012 !important">
                                            <td>Code</td>
                                            <td>Name</td>
                                            <td>Description</td>
                                            <td>Nominal</td>
                                            <td>Image</td>
                                            @if (Gate::check('edit-petty_cash_out') || Gate::check('delete-petty_cash'))
                                                <td colspan="2">Edit/Delete</td>
                                            @endif
                                        </thead>
                                        @php 
                                            $sub_total_ptc_details = 0;
                                            $bank_petty_cash_type = null;
                                        @endphp
                                        @foreach ($pettyCash->pettyCashDetail as $ptcDetail)
                                        @php $bank_petty_cash_type = $ptcDetail->petty_cash_out_bank_account_id ? "bank" : "account"; @endphp
                                        <tr>
                                            <input type="hidden" id="editPTC-tdetail_bank_{{ $ptcDetail['id'] }}" class="tdetail_bank"
                                                value="{{ $bank_petty_cash_type }}_{{ $bank_petty_cash_type == "bank" ? $ptcDetail->petty_cash_out_bank_account_id : $ptcDetail->petty_cash_out_type_id }}">
                                            <td>{{ $bank_petty_cash_type == "bank" ? $ptcDetail->pettyCashOutBankAccount->code : $ptcDetail->pettyCashOutType->code }}</td>
                                            <td>{{ $bank_petty_cash_type == "bank" ? $ptcDetail->pettyCashOutBankAccount->name : $ptcDetail->pettyCashOutType->name }}</td>
                                            <td>{{ $ptcDetail->description }}</td>
                                            <td>Rp. {{ number_format($ptcDetail->nominal) }}</td>
                                            <td>
                                                @foreach (json_decode($ptcDetail->evidence_image, true) as $evidenceImage)
                                                <a href="{{ asset("sources/pettycash/$evidenceImage") }}"
                                                    target="_blank">
                                                    <i class="mdi mdi-numeric-{{ $loop->iteration }}-box" style="font-size: 24px; color: #2daaff;"></i>
                                                </a>
                                                @endforeach
                                            </td>
                                            @can('edit-petty_cash_out')
                                                <td style="text-align: center;">
                                                    <button value="{{ $ptcDetail['id'] }}"
                                                        type="button"
                                                        data-toggle="modal"
                                                        data-target="#editDetailPettyCashModal"
                                                        class="btn p-0 btn-delete btn-edit_petty_cash_detail">
                                                        <i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
                                                    </button>
                                                </td>
                                            @endcan
                                            @can('delete-petty_cash')
                                                <td style="text-align: center;">
                                                    <button value="{{ route('delete_petty_cash_detail', ['id' => $ptcDetail['id']])}}"
                                                        type="button"
                                                        data-toggle="modal"
                                                        data-target="#deleteDoModal"
                                                        class="btn p-0 btn-delete btn-delete_petty_cash_detail">
                                                        <i class="mdi mdi-delete" style="font-size: 24px; color:#fe7c96;"></i>
                                                    </button>
                                                </td>
                                            @endcan
                                        </tr>
                                        @php $sub_total_ptc_details += $ptcDetail->nominal; @endphp
                                        @endforeach
                                        <tr>
                                            <td colspan="3" class="text-right" style="background-color: #80808012 !important">Sub Total</td>
                                            <td>Rp. {{ number_format($sub_total_ptc_details) }}</td>
                                            <td colspan="4" style="background-color: #f2f2f2;" rowspan="3"></td>
                                        </tr>
                                    </table>
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-gradient-success" 
                                            data-toggle="modal"
                                            data-target="#addDetailPettyCashModal" >
                                            Add Detail
                                        </button>
                                    </div>
                                @endif

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
                                <h2>PETTY CASH HISTORY LOG</h2>
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
                                    @if($historyUpdate != null)
                                    @foreach($historyUpdate as $key => $historyUpdate)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$historyUpdate->method}}</td>
                                        <td>{{$historyUpdate->name}}</td>
                                        <?php $dataChange = json_decode($historyUpdate->meta, true);?>
                                        <td>
                                        @foreach ($dataChange['dataChange'] as $key=>$value)
                                            <b>{{$key}}</b>: {{ is_array($value) ? json_encode($value) : $value }}<br/>
                                        @endforeach
                                        </td>
                                        <td>{{ date("d/m/Y H:i:s", strtotime($historyUpdate->created_at)) }}</td>
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
<!-- Modal Add Detail Petty Cash -->
<div class="modal fade"
    id="addDetailPettyCashModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="frmAddDetail"
                method="post"
                action="{{ route('store_petty_cash_detail') }}" autocomplete="off"
                enctype="multipart/form-data" >
                @csrf
                <input type="hidden" name="petty_cash_id" value="{{ $pettyCash->id }}">
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
                        Add Petty Cash Detail
                    </h5>
                    <br>
                    <div class="form-group">
                        <label for="">Bank/Type</label>
                        <div class="d-flex mb-2">
                            <div id="tdetail_radio_bank_parent" class="form-check-inline"
                                style="@if(!$pettyCashBanks['banks']) display: none; @endif">
                                <input type="radio" 
                                    id="tdetail_radio_bank" 
                                    name="tdetail_radio"
                                    value="bank" 
                                    @if($pettyCashBanks['banks']) checked @endif
                                    required>
                                <label for="tdetail_radio_bank" class="mb-0 ml-2">Bank</label>
                            </div>
                            <div id="tdetail_radio_account_parent" class="form-check-inline"
                                style="@if(!$pettyCashBanks['accs']) display: none; @endif">
                                <input type="radio"
                                    id="tdetail_radio_account" 
                                    name="tdetail_radio"
                                    @if($pettyCashBanks['accs']) checked @endif
                                    value="account">
                                <label for="tdetail_radio_account" class="mb-0 ml-2">Type</label>
                            </div>
                        </div>
                        <div>
                            <div id="tdetail_bank_parent"
                                style="@if(!$pettyCashBanks['banks']) display: none; @endif">
                                <select id="tdetail_bank"
                                    name="tdetail_bank"
                                    class="form-control" 
                                    style="width: 100%"
                                    required>
                                    <option selected disabled value="">Choose Bank Account</option>
                                    @if($pettyCashBanks['banks'])
                                    @foreach ($pettyCashBanks['banks'] as $pettyCashBank)
                                    <option value="bank_{{ $pettyCashBank->id }}">
                                        {{ $pettyCashBank->code }} - {{ $pettyCashBank->name }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div id="tdetail_account_parent" 
                                style="@if(!$pettyCashBanks['accs']) display: none; @endif">
                                <select id="tdetail_account"
                                    name="tdetail_bank"
                                    class="form-control" 
                                    style="width: 100%;"
                                    required>
                                    <option selected disabled value="">Choose Type</option>
                                    @if($pettyCashBanks['accs'])
                                    @foreach ($pettyCashBanks['accs'] as $pettyCashBank)
                                    <option value="account_{{ $pettyCashBank->id }}">
                                        {{ $pettyCashBank->code }} - {{ $pettyCashBank->name }}
                                        @if($pettyCashBank->max)
                                        - {{ number_format($pettyCashBank->max, 2) }}
                                        @endif
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Nominal</label>
                        <input type="text" id="tdetail_nominal" value=""
                            name="tdetail_nominal"
                            class="form-control" 
                            data-type="currency"
                            required />
                    </div>
                    <div class="form-group">
                        <label for="">Evidence Image <span id="label_required_tdetail_evidence_image"></span></label>
                        <input type="file" id="tdetail_evidence_image" value=""
                            name="evidence_image[]"
                            accept=".jpg,.png,.jpeg"
                            multiple
                            class="form-control" />
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
                        Add
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Add Detail Petty Cash -->
<!-- Modal Edit Detail Petty Cash -->
<div class="modal fade"
    id="editDetailPettyCashModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="frmEditDetail"
                method="post"
                action="{{ route('update_petty_cash_detail') }}" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="petty_cash_id" value="{{ $pettyCash['id'] }}">
                <input type="hidden" id="editPTC-ptc_detail_id" name="ptc_detail_id" value="">
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
                        Edit Petty Cash Detail
                    </h5>
                    <br>
                    <div class="form-group">
                        <label for="">Bank/Type</label>
                        <div class="d-flex mb-2">
                            <div id="editPTC-tdetail_radio_bank_parent" class="form-check-inline"
                                style="@if(!$pettyCashBanks['banks']) display: none; @endif">
                                <input type="radio" 
                                    id="editPTC-tdetail_radio_bank" 
                                    name="tdetail_radio"
                                    value="bank" 
                                    @if($pettyCashBanks['banks']) checked @endif
                                    required>
                                <label for="editPTC-tdetail_radio_bank" class="mb-0 ml-2">Bank</label>
                            </div>
                            <div id="editPTC-tdetail_radio_account_parent" class="form-check-inline"
                                style="@if(!$pettyCashBanks['accs']) display: none; @endif">
                                <input type="radio"
                                    id="editPTC-tdetail_radio_account" 
                                    name="tdetail_radio"
                                    @if($pettyCashBanks['accs']) checked @endif
                                    value="account">
                                <label for="editPTC-tdetail_radio_account" class="mb-0 ml-2">Type</label>
                            </div>
                        </div>
                        <div>
                            <div id="editPTC-tdetail_bank_parent"
                                style="@if(!$pettyCashBanks['banks']) display: none; @endif">
                                <select id="editPTC-tdetail_bank"
                                    name="tdetail_bank"
                                    class="form-control" 
                                    style="width: 100%"
                                    required>
                                    <option selected disabled value="">Choose Bank Account</option>
                                    @if($pettyCashBanks['banks'])
                                    @foreach ($pettyCashBanks['banks'] as $pettyCashBank)
                                    <option value="bank_{{ $pettyCashBank->id }}">
                                        {{ $pettyCashBank->code }} - {{ $pettyCashBank->name }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div id="editPTC-tdetail_account_parent" 
                                style="@if(!$pettyCashBanks['accs']) display: none; @endif">
                                <select id="editPTC-tdetail_account"
                                    name="tdetail_bank"
                                    class="form-control" 
                                    style="width: 100%;"
                                    required>
                                    <option selected disabled value="">Choose Type</option>
                                    @if($pettyCashBanks['accs'])
                                    @foreach ($pettyCashBanks['accs'] as $pettyCashBank)
                                    <option value="account_{{ $pettyCashBank->id }}">
                                        {{ $pettyCashBank->code }} - {{ $pettyCashBank->name }}
                                        @if($pettyCashBank->max)
                                        - {{ number_format($pettyCashBank->max, 2) }}
                                        @endif
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Nominal</label>
                        <input type="text" id="editPTC-tdetail_nominal" value=""
                            name="tdetail_nominal"
                            class="form-control" 
                            data-type="currency"
                            required />
                    </div>
                    <div class="form-group">
                        <label for="">Evidence Image</label>
                        <div id="editPTC-parent_tdeail_evidance_image">
                            <input type="file" id="editPTC-tdetail_evidence_image" value=""
                                name="evidence_image[]"
                                accept=".jpg,.png,.jpeg"
                                multiple
                                class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Description (Optional)</label>
                        <textarea id="editPTC-tdetail_description" name="tdetail_description" class="form-control" rows="5"></textarea>
                    </div>
                    <div id="editPTC-view-evidence_image"></div>
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
<!-- End Modal Edit Detail Petty Cash -->
<!-- Modal Delete Detail Petty Cash -->
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
                    Are You Sure to Delete this Petty Cash Detail?
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
<script>
$(document).ready(function() {
    $(document).on("input", 'input[data-type="currency"]', function() {
        $(this).val(numberWithCommas($(this).val()));
    });

    $("input:radio[name='tdetail_radio']").each(function() {
        checkRadioBank($(this));
    })

    $("#tdetail_bank").select2({
        theme: "bootstrap4",
        placeholder: "Choose Bank Account",
        dropdownParent: $('#addDetailPettyCashModal .modal-body')
    });
    $("#tdetail_account").select2({
        theme: "bootstrap4",
        placeholder: "Choose Type",
        dropdownParent: $('#addDetailPettyCashModal .modal-body')
    });
    $("#editPTC-tdetail_bank").select2({
        theme: "bootstrap4",
        placeholder: "Choose Bank Account",
        dropdownParent: $('#editDetailPettyCashModal .modal-body')
    });
    $("#editPTC-tdetail_account").select2({
        theme: "bootstrap4",
        placeholder: "Choose Type",
        dropdownParent: $('#editDetailPettyCashModal .modal-body')
    });

    // Edit Order Payment
    $(".btn-edit_petty_cash_detail").click(function() {
        cleareditDetailPettyCashModal();
        var ptc_detail_id = $(this).val();
        $.ajax({
            method: "post",
            url: "{{ route('edit_petty_cash_detail') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                petty_cash_id: "{{ $pettyCash['id'] }}",
                ptc_detail_id: ptc_detail_id
            },
            success: function(data) {
                if (data.status == 'success') {
                    const result = data.result;
                    const petty_cash_type = result.petty_cash_out_bank_account_id ? "bank" : "account";
                    $("#editPTC-ptc_detail_id").val(ptc_detail_id);
                    $("#editPTC-tdetail_radio_"+petty_cash_type).prop('checked', true);
                    checkRadioBank($("#editPTC-tdetail_radio_"+petty_cash_type));
                    if (petty_cash_type == "bank") {
                        $("#editPTC-tdetail_bank").val("bank_"+result.petty_cash_out_bank_account_id).trigger('change');
                    } else {
                        $("#editPTC-tdetail_account").val("account_"+result.petty_cash_out_type_id).trigger('change');
                    }
                    $("#editPTC-tdetail_nominal").val(numberWithCommas(result.nominal));
                    $("#editPTC-tdetail_description").val(result.description);
                    var view_evidence_image = ``;
                    const arr_evidecen_image = JSON.parse(result.evidence_image);
                    $.each(arr_evidecen_image, function(index, value) {
                        view_evidence_image += `
                            <div class="d-inline-block imgUp" style="position: relative;" data-idx="`+index+`">
                                <img class="img-thumbnail"
                                    src="{{ asset("sources/pettycash/") }}/`+value+`"
                                    style="max-height: 300px;" />
                                <i class="mdi mdi-window-close del"></i>
                            </div>`;
                    });
                    $("#editPTC-view-evidence_image").html(view_evidence_image);
                } else {
                    alert(data.result);
                }
            },
            error: function(data) {
                alert("Error!");
            }
        });
    });

    $("#frmAddDetail").submit(function(e) {
        const petty_cash_type = $("#frmAddDetail input:radio[name='tdetail_radio']:checked").val()
        const selectedBank = $("#tdetail_"+petty_cash_type+" option:selected").text().split(" - ");
        const tdetail_bank = $("#tdetail_"+petty_cash_type).val();
        const tdetail_nominal = numberNoCommas($("#tdetail_nominal").val());
        if (petty_cash_type == "account"&& selectedBank[2] != undefined  && parseFloat(tdetail_nominal) > parseFloat(numberNoCommas(selectedBank[2]))) {
            alert('Nominal exceeds the limit!');
            return false;
        }
        var check_unique = false;
        $('#body_petty_cash_detail .tdetail_bank').each(function(value) {
            if ($(this).val() == tdetail_bank) {
                return check_unique = true;
            }
        });
        if (check_unique == true) {
            alert('Petty Cash Detail Already exist')
            return false;
        }

        $('#frmAddDetail input[data-type="currency"]').each(function() {
            $(this).val(numberNoCommas($(this).val()));
        });
    });

    $("#frmEditDetail").submit(function() {
        var editptc_tdetail_idx = $("#editPTC-ptc_detail_id").val();
        const petty_cash_type = $("#frmEditDetail input:radio[name='tdetail_radio']:checked").val()
        const selectedBank = $("#editPTC-tdetail_"+petty_cash_type+" option:selected").text().split(" - ");
        const tdetail_bank = $("#editPTC-tdetail_"+petty_cash_type).val();
        const tdetail_nominal = numberNoCommas($("#editPTC-tdetail_nominal").val());
        if (petty_cash_type == "account"&& selectedBank[2] != undefined  && parseFloat(tdetail_nominal) > parseFloat(numberNoCommas(selectedBank[2]))) {
            alert('Nominal exceeds the limit!');
            return false;
        }
        var check_unique = false;
        $('#body_petty_cash_detail .tdetail_bank').each(function(value) {
            if ($(this).val() == tdetail_bank && $(this).attr('id') != "editPTC-tdetail_bank_"+editptc_tdetail_idx) {
                return check_unique = true;
            }
        });
        if (check_unique == true) {
            alert('Petty Cash Detail Already exist')
            return false;
        }

        $('#frmEditDetail input[data-type="currency"]').each(function() {
            $(this).val(numberNoCommas($(this).val()));
        });
    });

    $(".btn-delete_petty_cash_detail").click(function(e) {
        $("#frmDelete").attr("action",  $(this).val());
    });

    $(document).on("click", "i.del", function () {
        var idxImage = $(this).closest(".imgUp").attr("data-idx");
        $("#editPTC-view-evidence_image").append(`<input type='hidden' name='del_image[]' value='` + idxImage + `'>`);
        $(this).closest(".imgUp").remove();
    });
});

$(document).on('change', "input:radio[name='tdetail_radio']", function() {
    checkRadioBank($(this));
})

function checkRadioBank(element) {
    const el_val = element.val();
    console.log('aw')
    if (element.is(":checked")) {
        const prefix_edit = element.attr('id').includes("editPTC") ? "editPTC-" : "";
        $("#"+prefix_edit+"tdetail_bank_parent, #"+prefix_edit+"tdetail_account_parent").hide();
        $("#"+prefix_edit+"tdetail_bank, #"+prefix_edit+"tdetail_account").prop('disabled', true);
        $("#"+prefix_edit+"tdetail_"+el_val+"_parent").show();
        $("#"+prefix_edit+"tdetail_"+el_val).prop('disabled', false);
        if (prefix_edit == "") {
            if (el_val == "bank") {
                $("#"+prefix_edit+"tdetail_evidence_image").prop('required', false);
                $("#"+prefix_edit+"label_required_tdetail_evidence_image").html(' (Optional) ');
            } else {
                $("#"+prefix_edit+"tdetail_evidence_image").prop('required', true);
                $("#"+prefix_edit+"label_required_tdetail_evidence_image").html('');
            }
        }
    }
}

function cleareditDetailPettyCashModal() {
    $("#editPTC-ptc_detail_id").val("");
    $("#editPTC-tdetail_bank").val("").trigger('change');
    $("#editPTC-tdetail_account").val("").trigger('change');
    $("#editPTC-tdetail_nominal").val("");
    $("#editPTC-tdetail_evidence_image").val("");
    $("#editPTC-tdetail_description").val("");
    $("#editPTC-view-evidence_image").html("");
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
