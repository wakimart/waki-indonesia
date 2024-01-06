<?php
$menu_item_page = "financial_routine";
$isBranch = false;
if(Route::currentRouteName() === 'edit_financial_routine_branch'){
    $isBranch = true;
}
?>
@extends("admin.layouts.template")

@section("style")
<style type="text/css">
    select {
        color: black !important;
    }

    .ck-editor__editable_inline {
        height: 200px;
    }
</style>
@endsection

@section("content")
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Edit Financial Routine</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                      <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#financial_routine-dd"
                            aria-expanded="false"
                            aria-controls="financial_routine-dd">
                            Financial Routine
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Financial Routine
                    </li>
                </ol>
            </nav>
        </div>
        <form id="actionAdd"
            method="POST"
            enctype="multipart/form-data"
            action="{{ route("update_financial_routine") }}" autocomplete="off">
            @csrf
            <input type="hidden" name="idFinancialRoutine" value="{{ $financialRoutine->id }}">
            <div class="row">
                <div class="col-12 grid-margin stretch-card mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="routine_date">Date</label>
                                    <input type="date"
                                        id="routine_date"
                                        name="routine_date"
                                        value="{{ $financialRoutine->routine_date }}"
                                        class="form-control" 
                                        required />
                                    <div class="invalid-feedback"></div>
                                    <small id="financialroutine_error" style="color: red"></small>
                                    <small id="financialroutine_success" style="color: green"></small>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="bank">Bank Account</label>
                                    <select id="bank" name="bank"
                                        class="form-control" 
                                        required >
                                        <option value="" disabled selected>Choose Bank</option>
                                        @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}"
                                            @if($bank->id == $financialRoutine->bank_account_id) selected @endif>
                                            {{ $bank->code }} - {{ $bank->name }} ({{ ucwords($bank->type) }})
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="fr_data" class="row" style="display: auto;">
                <div class="col-12 grid-margin stretch-card mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h4><strong>Data Bank Account</strong></h4>
                            <input type="hidden" id="last_financial_routine_id" name="last_financial_routine_id" value="{{ $financialRoutine->financial_routine_id }}">
                            <div class="form-group">
                                <label for="total_sale">Total Sale</label>
                                <input type="text" id="total_sale" name="total_sale" value="{{ number_format($totalSale, 2) }}"
                                    placeholder="Total Sale"
                                    class="form-control" 
                                    data-type="currency"
                                    required readonly />
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="last_month_remains_saldo">Last Month Remains Saldo</label>
                                <input type="text" id="last_month_remains_saldo" name="last_month_remains_saldo" value="{{ number_format($financialRoutine->financialROutine['remains_saldo'], 2) }}"
                                    placeholder="Last Month Remains Saldo"
                                    class="form-control" 
                                    data-type="currency"
                                    required readonly />
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="last_month_remains_sale">Last Month's Sale That Hasn't Been Bank In</label>
                                <input type="text" id="last_month_remains_sale" name="last_month_remains_sale" value="{{ number_format($financialRoutine->financialRoutine['remains_sales'], 2) }}"
                                    placeholder="Last Month's Sale That Hasn't Been Bank In"
                                    class="form-control" 
                                    data-type="currency"
                                    required readonly />
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 grid-margin stretch-card mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="bank_interest">Interest / Administration Bank</label>
                                <input type="text" id="bank_interest" name="bank_interest" value="{{ number_format($financialRoutine->bank_interest, 2) }}"
                                    placeholder="Interest / Administration Bank"
                                    class="form-control" 
                                    data-type="currency"
                                    required />
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="etc_in">Etc In</label>
                                <input type="text" id="etc_in" name="etc_in" value="{{ number_format($financialRoutine->etc_in, 2) }}"
                                    placeholder="Etc In"
                                    class="form-control" 
                                    data-type="currency"
                                    required />
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 grid-margin stretch-card mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="bank_tax">Tax / Charge Bank</label>
                                <input type="text" id="bank_tax" name="bank_tax" value="{{ number_format($financialRoutine->bank_tax, 2) }}"
                                    placeholder="Tax / Charge Bank"
                                    class="form-control" 
                                    data-type="currency"
                                    required />
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="etc_out">Etc Out</label>
                                <input type="text" id="etc_out" name="etc_out" value="{{ number_format($financialRoutine->etc_out, 2) }}"
                                    placeholder="Etc In"
                                    class="form-control" 
                                    data-type="currency"
                                    required />
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="remains_sale">Sale That Hasn't Been Bank In</label>
                                <input type="text" id="remains_sale" name="remains_sale" value="{{ number_format($financialRoutine->remains_sales, 2) }}"
                                    placeholder="Sale That Hasn't Been Bank In"
                                    class="form-control" 
                                    data-type="currency"
                                    required />
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 grid-margin stretch-card mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h4><strong>Petty Cash</strong></h4>
                            <table class="table table-bordered col-md-12">
                                <thead>
                                    <th>Date</th>
                                    <th>Bank Code</th>
                                    <th>Nominal</th>
                                    <th colspan="2"></th>
                                </thead>
                                <tbody id="body_petty_cash">
                                    @php $fr_tdetail_total = 0; @endphp
                                    @foreach ($financialRoutine->financialRoutineTransaction as $frTransaction)
                                        <tr class="parent_fr_detail">
                                            <input type="hidden" name="arr_fr_tdetails[]" value="{{ $fr_tdetail_total }}">
                                            <input type="hidden" name="fr_tdetail_id[]" value="{{ $frTransaction->id }}">
                                            <input type="hidden" name="fr_tdetail_id_{{ $fr_tdetail_total }}" value="{{ $frTransaction->id }}">
                                            <input type="hidden" id="editFRT-tdetail_date_{{ $fr_tdetail_total }}" name="tdetail_date_{{ $fr_tdetail_total }}" value="{{ $frTransaction->transaction_date }}">
                                            <input type="hidden" id="editFRT-tdetail_bank_{{ $fr_tdetail_total }}" name="tdetail_bank_{{ $fr_tdetail_total }}" value="{{ $frTransaction->bank_account_id }}">
                                            <input type="hidden" id="editFRT-tdetail_nominal_{{ $fr_tdetail_total }}" name="tdetail_nominal_{{ $fr_tdetail_total }}" value="{{ $frTransaction->transaction }}">
                                            <input type="hidden" id="editFRT-tdetail_description_{{ $fr_tdetail_total }}" name="tdetail_description_{{ $fr_tdetail_total }}" value="{{ $frTransaction->description }}">
                                            <td id="editFRT-text-tdetail_date_{{ $fr_tdetail_total }}">{{ $frTransaction->transaction_date }}</td>
                                            <td id="editFRT-text-tdetail_bank_{{ $fr_tdetail_total }}">{{ $frTransaction->bankAccount['code'] }} - {{ $frTransaction->bankAccount['name'] }}</td>
                                            <td id="editFRT-text-tdetail_nominal_{{ $fr_tdetail_total }}">{{ number_format($frTransaction->transaction, 2) }}</td>
                                            <td class="text-center">
                                                <button value="{{ $fr_tdetail_total }}"
                                                    type="button"
                                                    data-toggle="modal"
                                                    data-target="#editDetailTransactionModal"
                                                    class="btn p-0 btn-delete btn-edit_financial_routine_transaction">
                                                    <i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
                                                </button>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-delete p-0 btn-delete-fr-detail">
                                                    <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @php $fr_tdetail_total++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-gradient-success" 
                                    data-toggle="modal"
                                    data-target="#addDetailTransactionModal" >
                                    Add Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 grid-margin stretch-card mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="description">Description (Optional)</label>
                                <textarea id="description" name="description" row="4" class=""><?php echo $financialRoutine->description; ?></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                            <button id="addFinancialRoutine" type="submit"
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
                        Add Detail
                    </h5>
                    <br>
                    <div class="form-group">
                        <label for="">Date <span id="tdetail_date_desc">(max {{ $financialRoutine->routine_date }})</span></label>
                        <input type="date" id="tdetail_date" value=""
                            class="form-control" 
                            required max="{{ $financialRoutine->routine_date }}">
                    </div>
                    <div class="form-group">
                        <label for="">Bank</label>
                        <select id="tdetail_bank"
                            class="form-control" 
                            required>
                            <option selected disabled value="">Choose Bank</option>
                            @foreach ($banksPettyCash as $bank)
                            <option @if($isBranch) class="branch_{{ $bank->branch['bank_id'] }}" style="display: {{ ($bank->branch['bank_id'] == $financialRoutine->bank_id) ? "inherit" : "none" }};" @endif value="{{ $bank->id }}">
                                {{ $bank->code }} - {{ $bank->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Nominal</label>
                        <input type="text" id="tdetail_nominal" value=""
                            class="form-control" 
                            data-type="currency"
                            required />
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
                action="" autocomplete="off">
                <input type="hidden" id="editFRT-tdetail_idx" value="">
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
                            @foreach ($banksPettyCash as $bank)
                            <option @if($isBranch) class="branch_{{ $bank->branch['bank_id'] }}" @endif value="{{ $bank->id }}">
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
@endsection

@section("script")
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor5/28.0.0/ckeditor.min.js"
    integrity="sha512-Xrc1M8yBi+D4ih2qiF9Y37IoDwgHiifRwTByqyT0kMoLLfGXwZpfLhdUcSblXY93OTvxNAPdbi/A8VClN9WFmQ=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>
<script type="application/javascript">
let editorDescription;
document.addEventListener("DOMContentLoaded", function () {
    ClassicEditor.create(document.getElementById("description"), {
        toolbar: ["bold", "italic", "|", "bulletedList", "numberedList"],
    }).then(function (editor) {
        editorDescription = editor;
        // console.log(Array.from(editor.ui.componentFactory.names()));
    }).catch(function (error) {
        console.error(error);
    });
});
</script>
<script>
    var frmAdd;
    $("#actionAdd").on("submit", function (e) {
        e.preventDefault();

        frmAdd = _("actionAdd");
        frmAdd = new FormData(document.getElementById("actionAdd"));
        frmAdd.enctype = "multipart/form-data";
        var URLNya = $("#actionAdd").attr('action');
        frmAdd.append('description', editorDescription.getData());

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
        document.getElementById("addFinancialRoutine").innerHTML = "UPLOADING...";
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
            var url = "{{  $isBranch ? route("detail_financial_routine_branch") : route("detail_financial_routine") }}?id={{ $financialRoutine->id }}";
            window.location.href = url;
            // window.location.reload()
        }
        document.getElementById("addFinancialRoutine").innerHTML = "Save";
    }
    function errorHandler(event){
        document.getElementById("addFinancialRoutine").innerHTML = "Save";
    }
</script>
<script>
    var fr_tdetail_total = parseInt("{{ $fr_tdetail_total }}") + 1;
    $("#frmAddDetail").submit(function(e) {
        e.preventDefault();
        var body_petty_cash = `
            <tr class="parent_fr_detail">
                <input type="hidden" name="arr_fr_tdetails[]" value="`+fr_tdetail_total+`">
                <input type="hidden" name="tdetail_date_`+fr_tdetail_total+`" value="`+ $("#tdetail_date").val() +`">
                <input type="hidden" name="tdetail_bank_`+fr_tdetail_total+`" value="`+ $("#tdetail_bank").val() +`">
                <input type="hidden" name="tdetail_nominal_`+fr_tdetail_total+`" value="`+ numberNoCommas($("#tdetail_nominal").val()) +`">
                <input type="hidden" name="tdetail_description_`+fr_tdetail_total+`" value="`+ $("#tdetail_description").val() +`">
                <td>` + $("#tdetail_date").val() + `</td>
                <td>` + $("#tdetail_bank option:selected").text() + `</td>
                <td>` + $("#tdetail_nominal").val() + `</td>
                <td colspan="2" class="text-center">
                    <button type="button" class="btn btn-delete p-0 btn-delete-fr-detail">
                        <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                    </button>
                </td>
            </tr>`;
        $("#body_petty_cash").append(body_petty_cash);
        fr_tdetail_total++;
        $("#addDetailTransactionModal").modal('hide');
        $(".modal-backdrop").remove();
        clearaddDetailTransactionModal();
    });

    $("#frmEditDetail").submit(function(e) {
        e.preventDefault();
        var editfrt_tdetail_idx = $("#editFRT-tdetail_idx").val();
        $("#editFRT-tdetail_date_" + editfrt_tdetail_idx).val($("#editFRT-tdetail_date").val());
        $("#editFRT-tdetail_bank_" + editfrt_tdetail_idx).val($("#editFRT-tdetail_bank").val());
        $("#editFRT-tdetail_nominal_" + editfrt_tdetail_idx).val(numberNoCommas($("#editFRT-tdetail_nominal").val()));
        $("#editFRT-tdetail_description_" + editfrt_tdetail_idx).val($("#editFRT-tdetail_description").val());
        $("#editFRT-text-tdetail_date_" + editfrt_tdetail_idx).html($("#editFRT-tdetail_date").val());
        $("#editFRT-text-tdetail_bank_" + editfrt_tdetail_idx).html($("#editFRT-tdetail_bank option:selected").text());
        $("#editFRT-text-tdetail_nominal_" + editfrt_tdetail_idx).html(numberWithCommas($("#editFRT-tdetail_nominal").val()));
        $("#editDetailTransactionModal").modal('hide');
        $(".modal-backdrop").remove();
    });
</script>
<script>
$(document).ready(function() {
    $(document).on("input", 'input[data-type="currency"]', function() {
        $(this).val(numberWithCommas($(this).val()));
    });

    $("#routine_date").on("change", function() {
        $("#tdetail_date").attr('max', $(this).val());
        $("#tdetail_date_desc").html("(max " + $(this).val() + ")");
        $("#editFRT-tdetail_date").attr('max', $(this).val());
        $("#editFRT-tdetail_date_desc").html("(max " + $(this).val() + ")");
    });

    $("#routine_date, #bank").on("change", function(){
        var idFinancialRoutine = "{{ $financialRoutine->id }}";
        var routine_date = $("#routine_date").val()
        var bank = $("#bank").val();
        if (routine_date && bank) {
            $("#financialroutine_error").html("");
            $("#financialroutine_success").html("");
            $.get( '{{ $isBranch ? route("check_routine_date_branch") : route("check_routine_date") }}', { idFinancialRoutine, routine_date, bank })
            .done(function( result ) {
                if (result['status'] == 'success'){                
                    $("#last_financial_routine_id").val(result['lastMonthFinancialRoutine']['id'])
                    $("#total_sale").val(numberWithCommas(result['totalSale']['total_sale']));
                    $("#last_month_remains_saldo").val(numberWithCommas(result['lastMonthFinancialRoutine']['remains_saldo']));
                    $("#last_month_remains_sale").val(numberWithCommas(result['lastMonthFinancialRoutine']['remains_sales']));
                    $("#financialroutine_success").html("New Financial Routine");
                    $("#fr_data").show();
                    @if($isBranch) changeListDetailBank(bank); @endif
                } else if(result['same_data']) {
                    $("#financialroutine_error").html(result['same_data']);   
                    $("#fr_data").hide();
                } else {
                    alert('Error!');
                }
            })
            .fail(function( result ) {
                var err = JSON.parse(result.responseText)
                alert(err.message);
                $("#fr_data").hide();
            });
        } 
    });

    $(".btn-edit_financial_routine_transaction").click(function() {
        cleareditDetailTransactionModal();
        var editfrt_tdetail_idx = $(this).val();
        $("#editFRT-tdetail_idx").val(editfrt_tdetail_idx);
        $("#editFRT-tdetail_date").val($("#editFRT-tdetail_date_" + editfrt_tdetail_idx).val());
        $("#editFRT-tdetail_bank").val($("#editFRT-tdetail_bank_" + editfrt_tdetail_idx).val());
        $("#editFRT-tdetail_nominal").val(numberWithCommas($("#editFRT-tdetail_nominal_" + editfrt_tdetail_idx).val()));
        $("#editFRT-tdetail_description").val($("#editFRT-tdetail_description_" + editfrt_tdetail_idx).val());
    });
});

$(document).on("click", ".btn-delete-fr-detail", function() {
    $(this).closest('.parent_fr_detail').remove();
})

function clearaddDetailTransactionModal() {
    $("#tdetail_date").val("");
    $("#tdetail_bank").val("");
    $("#tdetail_nominal").val("");
    $("#tdetail_description").val("");
}

function cleareditDetailTransactionModal() {
    $("#editFRT-tdetail_idx").val("");
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

function ucwords(str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}

@if($isBranch)
    function changeListDetailBank(id){
        @foreach($banks as $bankNya)
            $(".branch_{{ $bankNya['id'] }}").css("display", "none");
        @endforeach
        $(".branch_"+id).css("display", "inherit");
    }
@endif
</script>
@endsection