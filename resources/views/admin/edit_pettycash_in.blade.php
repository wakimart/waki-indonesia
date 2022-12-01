<?php
$menu_item_page = "petty_cash";

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
</style>
@endsection

@section("content")
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Edit Petty Cash In</h3>
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
                        Edit Petty Cash In
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
                            <input type="hidden" id="type" name="type" value="in">
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
                                <label for="bank">Bank</label>
                                <select id="bank" name="bank"
                                    class="form-control" 
                                    required >
                                    <option value="" disabled selected>Choose Bank</option>
                                    @foreach ($banks as $bank)
                                    <option value="{{ $bank->id }}"
                                        @if($bank->id == $pettyCash->bank_id) selected @endif>
                                        {{ $bank->code }} - {{ $bank->name }} ({{ ucwords($bank->type) }})
                                    </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="nominal">Nominal</label>
                                <input type="text" id="nominal" name="nominal"
                                    class="form-control" 
                                    data-type="currency"
                                    value="{{ number_format($pettyCash->pettyCashDetail[0]->nominal, 2) }}"
                                    required />
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="evidence_image_0">Evidence Image</label>
                                <input type="file" id="evidence_image_0" name="evidence_image_0[]"
                                    accept=".jpg,.png,.jpeg"
                                    multiple
                                    class="form-control"  />
                                <div class="invalid-feedback"></div>
                            </div>
                            <div id="imageList" class="form-group">
                                @php $arrImages = json_decode($pettyCash->pettyCashDetail[0]->evidence_image, true) ?? []; @endphp
                                @foreach ($arrImages as $idxArrImage => $arrImage)
                                <div class="d-inline-block imgUp" style="position: relative;" data-idx="0_{{ $idxArrImage }}">
                                    <img class="img-thumbnail"
                                        src="{{ asset("sources/pettycash/" . $arrImage) }}"
                                        style="max-height: 300px;" />
                                    <i class="mdi mdi-window-close del"></i>
                                </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label for="description">Description (Optional)</label>
                                <textarea id="description" name="description" rows="5" class="form-control">{{ $pettyCash->pettyCashDetail[0]->description }}</textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                            <input type="hidden" name="arr_ptc_details[]" value="0">
                            <input type="hidden" name="ptc_detail_id[]" value="{{ $pettyCash->pettyCashDetail[0]->id }}">
                            <input type="hidden" name="ptc_detail_id_0" value="{{ $pettyCash->pettyCashDetail[0]->id }}">
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
@endsection

@section("script")
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor5/28.0.0/ckeditor.min.js"
    integrity="sha512-Xrc1M8yBi+D4ih2qiF9Y37IoDwgHiifRwTByqyT0kMoLLfGXwZpfLhdUcSblXY93OTvxNAPdbi/A8VClN9WFmQ=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"></script>
<script type="application/javascript">
// let editorDescription;
// document.addEventListener("DOMContentLoaded", function () {
//     ClassicEditor.create(document.getElementById("description"), {
//         toolbar: ["bold", "italic", "|", "bulletedList", "numberedList"],
//     }).then(function (editor) {
//         editorDescription = editor;
//         // console.log(Array.from(editor.ui.componentFactory.names()));
//     }).catch(function (error) {
//         console.error(error);
//     });
// });
</script>
<script>
    var frmAdd;
    $("#actionAdd").on("submit", function (e) {
        e.preventDefault();

        frmAdd = _("actionAdd");
        frmAdd = new FormData(document.getElementById("actionAdd"));
        frmAdd.enctype = "multipart/form-data";
        var URLNya = $("#actionAdd").attr('action');
        // frmAdd.append('description', editorDescription.getData());

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
$(document).ready(function() {
    $(document).on("input", 'input[data-type="currency"]', function() {
        $(this).val(numberWithCommas($(this).val()));
    });

    $(document).on("click", "i.del", function () {
        var idxImage = $(this).closest(".imgUp").attr("data-idx").split("_");
        $("#imageList").append(`<input type='hidden' name='del_image_`+idxImage[0]+`[]' value='` + idxImage[1] + `'>`);
        $(this).closest(".imgUp").remove();
    });
});

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