<?php
$menu_item_page = "petty_cash";

?>
@extends("admin.layouts.template")

@section("style")
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
    integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
<link rel="stylesheet" href="{{ asset("css/lib/select2/select2-bootstrap4.min.css") }}" />
<style type="text/css">
    select {
        color: black !important;
    }

    .ck-editor__editable_inline {
        height: 200px;
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

@section("content")
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Petty Cash Out</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                      <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#petty_cash-dd"
                            aria-expanded="false"
                            aria-controls="petty_cash-dd">
                            Petty Cash 
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Petty Cash Out
                    </li>
                </ol>
            </nav>
        </div>
        <form id="actionAdd"
            method="POST"
            enctype="multipart/form-data"
            action="{{ route("update_petty_cash") }}" autocomplete="off">
            @csrf
            <input type="hidden" name="id" value="{{ $pettyCash->id }}">
            <div class="row">
                <div class="col-12 grid-margin stretch-card mb-3">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" id="type" name="type" value="out">
                            <div class="form-group">
                                <label for="transaction_date">Transaction Date</label>
                                <input type="date" id="transaction_date" name="transaction_date"
                                    class="form-control" 
                                    value="{{ $pettyCash->transaction_date }}"
                                    required />
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="code">Code (Please Select Date)</label>
                                <input type="text" id="code" name="code"
                                    class="form-control" 
                                    value="{{ $pettyCash->code }}"
                                    required readonly />
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="temp_no">Temp No (Optional)</label>
                                <input type="text" id="temp_no" name="temp_no"
                                    class="form-control"
                                    value="{{ $pettyCash->temp_no }}" />
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="bank">Bank Account</label>
                                <input type="hidden" id="bank_id" name="bank" value="{{ $pettyCash->bank_account_id }}">
                                <select id="bank" name="bank"
                                    class="form-control" 
                                    required @if($pettyCash->pettyCashDetail()->count() > 0) disabled @endif>
                                    <option value="" disabled selected>Choose Bank Account</option>
                                    @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}"
                                        @if($pettyCash->bank_account_id == $bank->id) selected @endif>
                                        {{ $bank->code }} - {{ $bank->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 grid-margin stretch-card mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h4><strong>Petty Cash Detail</strong></h4>
                            <table class="table table-bordered col-md-12">
                                <thead>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Nominal</th>
                                    <th colspan="2"></th>
                                </thead>
                                <tbody id="body_petty_cash_detail">
                                    @php 
                                        $ptc_detail_total = 0; 
                                        $bank_petty_cash_type = null;
                                    @endphp
                                    @foreach ($pettyCash->pettyCashDetail as $ptcDetail)
                                    @php $bank_petty_cash_type = $ptcDetail->petty_cash_out_bank_account_id ? "bank" : "account"; @endphp
                                    <tr id="parent_ptc_detail_{{ $ptc_detail_total }}" class="parent_ptc_detail">
                                        <input type="hidden" name="arr_ptc_details[]" value="{{ $ptc_detail_total }}">
                                        <input type="hidden" name="ptc_detail_id[]" value="{{ $ptcDetail->id }}">
                                        <input type="hidden" name="ptc_detail_id_{{ $ptc_detail_total }}" value="{{ $ptcDetail->id }}">
                                        <input type="hidden" id="editPTC-tdetail_bank_{{ $ptc_detail_total }}" class="tdetail_bank" name="tdetail_bank_{{ $ptc_detail_total }}" 
                                            value="{{ $bank_petty_cash_type }}_{{ $bank_petty_cash_type == "bank" ? $ptcDetail->petty_cash_out_bank_account_id : $ptcDetail->petty_cash_out_type_id }}">
                                        <input type="hidden" id="editPTC-tdetail_nominal_{{ $ptc_detail_total }}" name="tdetail_nominal_{{ $ptc_detail_total }}" value="{{ $ptcDetail->nominal }}">
                                        <input type="hidden" id="editPTC-tdetail_description_{{ $ptc_detail_total }}" name="tdetail_description_{{ $ptc_detail_total }}" value="{{ $ptcDetail->description }}">
                                        <td id="editPTC-view-evidence_image_{{ $ptc_detail_total }}" style="display: none;">
                                            @php $arrImages = json_decode($ptcDetail->evidence_image, true) ?? []; @endphp
                                            @foreach ($arrImages as $idxArrImage => $arrImage)
                                            <div class="d-inline-block imgUp" style="position: relative;" data-idx="{{ $ptc_detail_total }}_{{ $idxArrImage }}">
                                                <img class="img-thumbnail"
                                                    src="{{ asset("sources/pettycash/" . $arrImage) }}"
                                                    style="max-height: 300px;" />
                                                <i class="mdi mdi-window-close del"></i>
                                            </div>
                                            @endforeach
                                        </td>
                                        <td id="editPTC-text-tdetail_bank_code_{{ $ptc_detail_total }}">{{ $bank_petty_cash_type == "bank" ? $ptcDetail->pettyCashOutBankAccount['code'] : $ptcDetail->pettyCashOutType['code'] }}</td>
                                        <td id="editPTC-text-tdetail_bank_name_{{ $ptc_detail_total }}">{{ $bank_petty_cash_type == "bank" ? $ptcDetail->pettyCashOutBankAccount['name'] : $ptcDetail->pettyCashOutType['name'] }}</td>
                                        <td id="editPTC-text-tdetail_description_{{ $ptc_detail_total }}">{{ $ptcDetail->description }}</td>
                                        <td id="editPTC-text-tdetail_nominal_{{ $ptc_detail_total }}">{{ number_format($ptcDetail->nominal, 2) }}</td>
                                        <td class="text-center">
                                            <button value="{{ $ptc_detail_total }}"
                                                type="button"
                                                data-toggle="modal"
                                                data-target="#editDetailPettyCashModal"
                                                class="btn p-0 btn-delete btn-edit_petty_cash_detail">
                                                <i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-delete p-0 btn-delete-ptc-detail">
                                                <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @php $ptc_detail_total++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-gradient-success" 
                                    data-toggle="modal"
                                    data-target="#addDetailPettyCashModal" >
                                    Add Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 grid-margin stretch-card mb-3">
                    <div class="card">
                        <div class="card-body">
                            <button id="addPettyCash" type="submit"
                                class="btn btn-gradient-primary">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
                action="" autocomplete="off">
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
                            class="form-control" 
                            data-type="currency"
                            required />
                    </div>
                    <div class="form-group">
                        <label for="">Evidence Image <span id="label_required_tdetail_evidence_image">{{ $bank_petty_cash_type == "bank" ? ' (Optional) ' : '' }}</span></label>
                        <input type="file" id="tdetail_evidence_image" value=""
                            accept=".jpg,.png,.jpeg"
                            multiple
                            class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="">Description (Optional)</label>
                        <textarea id="tdetail_description" class="form-control" rows="5"></textarea>
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
                action="" autocomplete="off">
                <input type="hidden" id="editPTC-tdetail_idx" value="">
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
                            class="form-control" 
                            data-type="currency"
                            required />
                    </div>
                    <div class="form-group">
                        <label for="">Evidence Image</label>
                        <div id="editPTC-parent_tdeail_evidance_image">
                            <input type="file" id="editPTC-tdetail_evidence_image" value=""
                                accept=".jpg,.png,.jpeg"
                                multiple
                                class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Description (Optional)</label>
                        <textarea id="editPTC-tdetail_description" class="form-control" rows="5"></textarea>
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
@endsection

@section("script")
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor5/28.0.0/ckeditor.min.js"
    integrity="sha512-Xrc1M8yBi+D4ih2qiF9Y37IoDwgHiifRwTByqyT0kMoLLfGXwZpfLhdUcSblXY93OTvxNAPdbi/A8VClN9WFmQ=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>
<script>
    var frmAdd;
    $("#actionAdd").on("submit", function (e) {
        e.preventDefault();

        frmAdd = _("actionAdd");
        frmAdd = new FormData(document.getElementById("actionAdd"));
        frmAdd.enctype = "multipart/form-data";
        var URLNya = $("#actionAdd").attr('action');

        // Change numberWithComma before submit
        $('#actionAdd input[data-type="currency"]').each(function() {
            var frmName = $(this).attr('name');
            frmAdd.set(frmName, numberNoCommas(frmAdd.get(frmName)));
        });

        var ajax = new XMLHttpRequest();
        ajax.upload.addEventListener("progress", progressHandler, false);
        ajax.addEventListener("load", completeHandler, false);
        ajax.addEventListener("error", errorHandler, false);
        ajax.open("POST", URLNya);
        ajax.setRequestHeader("X-CSRF-TOKEN",$('meta[name="csrf-token"]').attr('content'));
        ajax.send(frmAdd);
    });
    function progressHandler(event){
        document.getElementById("addPettyCash").innerHTML = "UPLOADING...";
    }
    function completeHandler(event){
        var hasil = JSON.parse(event.target.responseText);

        for (var key of frmAdd.keys()) {
            $("#actionAdd").find("input[name='"+key+"']").removeClass("is-invalid");
            $("#actionAdd").find("select[name='"+key+"']").removeClass("is-invalid");
            $("#actionAdd").find("textarea[name='"+key+"']").removeClass("is-invalid");

            $("#actionAdd").find("input[name='"+key+"']").siblings(".invalid-feedback").text("");
            $("#actionAdd").find("select[name='"+key+"']").siblings(".invalid-feedback").text("");
            $("#actionAdd").find("textarea[name='"+key+"']").siblings(".invalid-feedback").text("");
        }

        if(hasil['errors'] != null){
            for (var key of frmAdd.keys()) {
                if(typeof hasil['errors'][key] === 'undefined') {

                }
                else {
                    $("#actionAdd").find("input[name='"+key+"']").addClass("is-invalid");
                    $("#actionAdd").find("select[name='"+key+"']").addClass("is-invalid");
                    $("#actionAdd").find("textarea[name='"+key+"']").addClass("is-invalid");

                    $("#actionAdd").find("input[name='"+key+"']").siblings(".invalid-feedback").text(hasil['errors'][key]);
                    $("#actionAdd").find("select[name='"+key+"']").siblings(".invalid-feedback").text(hasil['errors'][key]);
                    $("#actionAdd").find("textarea[name='"+key+"']").siblings(".invalid-feedback").text(hasil['errors'][key]);
                }
            }
            alert('Input Error!');
        } else {
            alert("Input Success !!!");
            var kode = hasil['success']['id'];
            var url = "{{ route('detail_petty_cash') }}?id="+kode;
            window.location.href = url;
            // window.location.reload()
        }
        document.getElementById("addPettyCash").innerHTML = "Save";
    }
    function errorHandler(event){
        document.getElementById("addPettyCash").innerHTML = "Save";
    }
</script>
<script>
    var ptc_count = parseInt("{{ $ptc_detail_total }}");
    var ptc_detail_total = parseInt("{{ $ptc_detail_total }}");
    $("#frmAddDetail").submit(function(e) {
        e.preventDefault();
        const petty_cash_type = $("#frmAddDetail input:radio[name='tdetail_radio']:checked").val()
        const selectedBank = $("#tdetail_"+petty_cash_type+" option:selected").text().split(" - ");
        const tdetail_bank = $("#tdetail_"+petty_cash_type).val();
        const tdetail_nominal = numberNoCommas($("#tdetail_nominal").val());
        const tdetail_evidence_image = $("#tdetail_evidence_image").clone();
        tdetail_evidence_image.hide();
        tdetail_evidence_image.attr('id', 'evidence_image_'+ptc_detail_total);
        tdetail_evidence_image.attr('name', 'evidence_image_'+ptc_detail_total+'[]');
        if (petty_cash_type == "account" && selectedBank[2] != undefined && parseFloat(tdetail_nominal) > parseFloat(numberNoCommas(selectedBank[2]))) {
            return alert('Nominal exceeds the limit!');
        }

        var check_unique = false;
        $('#body_petty_cash_detail .tdetail_bank').each(function(value) {
            if ($(this).val() == tdetail_bank) {
                return check_unique = true;
            }
        });
        if (check_unique == true) {
            return alert('Petty Cash Detail Already exist')
        }
        var body_petty_cash = `
            <tr id="parent_ptc_detail_`+ptc_detail_total+`" class="parent_ptc_detail">
                <input type="hidden" name="arr_ptc_details[]" value="`+ptc_detail_total+`">
                <input type="hidden" class="tdetail_bank" name="tdetail_bank_`+ptc_detail_total+`" value="`+ tdetail_bank +`">
                <input type="hidden" name="tdetail_nominal_`+ptc_detail_total+`" value="`+ tdetail_nominal +`">
                <input type="hidden" name="tdetail_description_`+ptc_detail_total+`" value="`+ $("#tdetail_description").val() +`">
                <td>` + selectedBank[0] + `</td>
                <td>` + selectedBank[1] + `</td>
                <td>` + $("#tdetail_description").val() + `</td>
                <td>` + numberWithCommas(tdetail_nominal) + `</td>
                <td colspan="2" class="text-center">
                    <button type="button" class="btn btn-delete p-0 btn-delete-ptc-detail">
                        <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                    </button>
                </td>
            </tr>`;
        $("#body_petty_cash_detail").append(body_petty_cash);
        tdetail_evidence_image.appendTo("#parent_ptc_detail_"+ptc_detail_total);
        ptc_count++;
        ptc_detail_total++;
        clearaddDetailPettyCashModal();
        checkDetailPettyCash();
        $("#addDetailPettyCashModal").modal('hide');
        $(".modal-backdrop").remove();
    });

    $("#frmEditDetail").submit(function(e) {
        e.preventDefault();
        var editptc_tdetail_idx = $("#editPTC-tdetail_idx").val();
        const petty_cash_type = $("#frmEditDetail input:radio[name='tdetail_radio']:checked").val()
        const selectedBank = $("#editPTC-tdetail_"+petty_cash_type+" option:selected").text().split(" - ");
        const tdetail_bank = $("#editPTC-tdetail_"+petty_cash_type).val();
        const tdetail_nominal = numberNoCommas($("#editPTC-tdetail_nominal").val());
        const tdetail_evidence_image = $("#editPTC-tdetail_evidence_image").clone();
        tdetail_evidence_image.hide();
        tdetail_evidence_image.attr('id', 'evidence_image_'+editptc_tdetail_idx);
        tdetail_evidence_image.attr('name', 'evidence_image_'+editptc_tdetail_idx+'[]');
        if (petty_cash_type == "account" && selectedBank[2] != undefined && parseFloat(tdetail_nominal) > parseFloat(numberNoCommas(selectedBank[2]))) {
            return alert('Nominal exceeds the limit!');
        }

        var check_unique = false;
        $('#body_petty_cash_detail .tdetail_bank').each(function(value) {
            if ($(this).val() == tdetail_bank && $(this).attr('id') != "editPTC-tdetail_bank_"+editptc_tdetail_idx) {
                return check_unique = true;
            }
        });
        if (check_unique == true) {
            return alert('Petty Cash Detail Already exist')
        }
        $("#editPTC-tdetail_bank_" + editptc_tdetail_idx).val(tdetail_bank);
        $("#editPTC-tdetail_nominal_" + editptc_tdetail_idx).val(tdetail_nominal);
        $("#editPTC-tdetail_description_" + editptc_tdetail_idx).val($("#editPTC-tdetail_description").val());
        $("#editPTC-text-tdetail_bank_code_" + editptc_tdetail_idx).html(selectedBank[0]);
        $("#editPTC-text-tdetail_bank_name_" + editptc_tdetail_idx).html(selectedBank[1]);
        $("#editPTC-text-tdetail_description_" + editptc_tdetail_idx).html($("#editPTC-tdetail_description").val());
        $("#editPTC-text-tdetail_nominal_" + editptc_tdetail_idx).html(numberWithCommas(tdetail_nominal));
        $("#editPTC-view-evidence_image"+editptc_tdetail_idx).html($("#editPTC-view-evidence_image").html())
        if ($("#evidence_image_"+editptc_tdetail_idx).length > 0) {
            $("#evidence_image_"+editptc_tdetail_idx).remove();
        } 
        if ($('#editPTC-tdetail_evidence_image').get(0).files.length > 0) {
            tdetail_evidence_image.appendTo("#parent_ptc_detail_"+editptc_tdetail_idx);
        }
        $("#editDetailPettyCashModal").modal('hide');
        $(".modal-backdrop").remove();
    });
</script>
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

    $("#bank").on("change", function() {
        const bank_id = $("#bank").val();
        $("#bank_id").val(bank_id);
        if (bank_id) {
            $.ajax({
                method: "GET",
                url: "{{ route('fetch_bank_by_petty_cash_type') }}",
                data: {bank_id},
                success: function(response) {
                    if (response.data) {
                        // petty_cash_type = response.petty_cash_type;
                        var bank_options = `<option selected disabled value="">Choose Bank Account</option>`;
                        if (response.data['banks']) {
                            response.data['banks'].forEach(item => {
                                bank_options += `<option value="bank_`+item.id+`">`
                                    + item.code + ` - ` + item.name 
                                    + `</option>`;
                            });
                        }
                        var acc_options = `<option selected disabled value="">Choose Type</option>`;
                        if (response.data['accs']) {
                            response.data['accs'].forEach(item => {
                                acc_options += `<option value="account_`+item.id+`">`
                                    + item.code + ` - ` + item.name;
                                + item.code + ` - ` + item.name; 
                                    + item.code + ` - ` + item.name;
                                if (item.max) acc_options += ' - ' + numberWithCommas(item.max);
                                acc_options += `</option>`;
                            });
                        }
                        $("#tdetail_bank").html(bank_options);
                        $("#tdetail_account").html(acc_options);
                        $("#editPTC-tdetail_bank").html(bank_options);
                        $("#editPTC-tdetail_account").html(acc_options);
                        $("#tdetail_radio_bank_parent, #tdetail_bank_parent, #tdetail_radio_account_parent, #tdetail_account_parent").hide();
                        $("#editPTC-tdetail_radio_bank_parent, #editPTC-tdetail_bank_parent, #editPTC-tdetail_radio_account_parent, #editPTC-tdetail_account_parent").hide();
                        $("#tdetail_radio_bank, #tdetail_radio_account").prop('checked', false);
                        if (response.data['banks']) {
                            $("#tdetail_radio_bank_parent, #tdetail_bank_parent").show();
                            $("#tdetail_radio_bank").prop('checked', true);
                            checkRadioBank($("#tdetail_radio_bank"));
                            $("#editPTC-tdetail_radio_bank_parent, #editPTC-tdetail_bank_parent").show();
                            $("#editPTC-tdetail_radio_bank").prop('checked', true);
                            checkRadioBank($("#editPTC-tdetail_radio_bank"));
                        }
                        if (response.data['accs']) {
                            $("#tdetail_radio_account_parent, #tdetail_account_parent").show();
                            $("#tdetail_radio_account").prop('checked', true);
                            checkRadioBank($("#tdetail_radio_account"));
                            $("#editPTC-tdetail_radio_account_parent, #editPTC-tdetail_account_parent").show();
                            $("#editPTC-tdetail_radio_account").prop('checked', true);
                            checkRadioBank($("#editPTC-tdetail_radio_account"));
                        }
                    } else {
                        alert(response.data);
                    }
                },
            });
        }
    })

    $(".btn-edit_petty_cash_detail").click(function() {
        cleareditDetailPettyCashModal();
        var editptc_tdetail_idx = $(this).val();
        $("#editPTC-tdetail_idx").val(editptc_tdetail_idx);
        const explode = $("#editPTC-tdetail_bank_" + editptc_tdetail_idx).val().split("_");
        const petty_cash_type = explode[0] == "bank" ? "bank" : "account";
        $("#editPTC-tdetail_radio_"+petty_cash_type).prop('checked', true);
        checkRadioBank($("#editPTC-tdetail_radio_"+petty_cash_type));
        if (petty_cash_type == "bank") {
            $("#editPTC-tdetail_bank").val("bank_"+explode[1]).trigger('change');
        } else {
            $("#editPTC-tdetail_account").val("account_"+explode[1]).trigger('change');
        }
        $("#editPTC-tdetail_nominal").val(numberWithCommas($("#editPTC-tdetail_nominal_" + editptc_tdetail_idx).val()));
        $("#editPTC-tdetail_description").val($("#editPTC-tdetail_description_" + editptc_tdetail_idx).val());
        $("#editPTC-view-evidence_image").html($("#editPTC-view-evidence_image_"+editptc_tdetail_idx).html())
        if ($("#evidence_image_"+editptc_tdetail_idx).length > 0) {
            const clone_evidence_image = $("#evidence_image_"+editptc_tdetail_idx).clone();
            clone_evidence_image.show();
            clone_evidence_image.attr('id', 'editPTC-tdetail_evidence_image');
            $("#editPTC-parent_tdeail_evidance_image").html("");
            clone_evidence_image.appendTo("#editPTC-parent_tdeail_evidance_image");
        }
    })    

    $(document).on("click", "i.del", function () {
        var idxImage = $(this).closest(".imgUp").attr("data-idx").split("_");
        $("#body_petty_cash_detail").append(`<input type='hidden' name='del_image_`+idxImage[0]+`[]' value='` + idxImage[1] + `'>`);
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

$(document).on("click", ".btn-delete-ptc-detail", function() {
    $(this).closest('.parent_ptc_detail').remove();
    ptc_count--;
    checkDetailPettyCash();
})

function checkDetailPettyCash() {
    $("#bank").prop('disabled', ptc_count > 0);
}

function clearaddDetailPettyCashModal() {
    $("#tdetail_bank").val("").trigger('change');
    $("#tdetail_account").val("").trigger('change');
    $("#tdetail_nominal").val("");
    $("#tdetail_evidence_image").val("");
    $("#tdetail_description").val("");
}

function cleareditDetailPettyCashModal() {
    $("#editPTC-tdetail_bank").val("").trigger('change');
    $("#editPTC-tdetail_account").val("").trigger('change');
    $("#editPTC-tdetail_nominal").val("");
    $("#editPTC-tdetail_description").val("");
    $("#editPTC-parent_tdeail_evidance_image").html(`
        <input type="file" id="editPTC-tdetail_evidence_image" value=""
            accept=".jpg,.png,.jpeg"
            multiple
            class="form-control" />`);
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

function ucwords(str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}
</script>
@endsection