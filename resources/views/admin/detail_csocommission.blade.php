<?php
    $menu_item_page = "order";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="{{ asset("css/lib/select2/select2-bootstrap4.min.css") }}" />
<style type="text/css">
    #intro { padding-top: 2em; }
    button{
        background: #1bb1dc;
        border: 0;
        border-radius: 3px;
        padding: 8px 30px;
        color: #fff;
        transition: 0.3s;
    }
    table{
        margin-top: 0.5em;
        margin-bottom: 0.5em;
        font-size: 14px;
    }
    table thead{
        background-color: #8080801a;
        text-align: center;
    }
    table td{
        border: 0.5px #8080801a solid;
        padding: 0.5em;
        word-break: break-word;
    }
    .right{ text-align: right; }
    .pInTable{
        margin-bottom: 6pt !important;
        font-size: 10pt;
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
    .div-CheckboxGroup {  padding: 5px;  }
    .black-color { color: black !important; }
    .content-wrapper { background: transparent !important;}
</style>
@endsection

@section('content')
@if( $order['code'] != null)
    <section id="intro" class="clearfix">
      	<div class="container-fluid">
            <div class="text-center">
                <h2>ORDER SUCCESS</h2>
            </div>
            <div class="col-md-12">
              <div class="w-100 my-2" id="commisionDetail" class="">
                <h3 class="text-center">CSO Commision Detail</h3>
                <table class="w-100 table-bordered">
                  <tr align="left"> <!--dummy data -->
                      <td class="font-weight-bold w-25">Month</td>
                      <td>Juni 2023</td>
                  </tr>
                  <tr align="left"> <!--dummy data -->
                      <td class="font-weight-bold w-25">Branch</td>
                      <td>F48</td>
                  </tr>
                  <tr align="left"> <!--dummy data -->
                      <td class="font-weight-bold w-25">CSO Code</td>
                      <td>WK2315</td>
                  </tr>
                  <tr align="left"> <!--dummy data -->
                      <td class="font-weight-bold w-25">CSO Name</td>
                      <td>Bambang Sudipto</td>
                  </tr>
                  <tr align="left"> <!--dummy data -->
                      <td class="font-weight-bold w-25">CSO Account Number</td>
                      <td>156165651</td>
                  </tr>
                </table>
                <br />
                <table class="w-100 table-bordered my-2">
                  <thead class="font-weight-bold" align="center">
                      <td>Commision</td>
                      <td>Bonus</td>
                      <td>Upgrade</td>
                      <td>Bonus Semangat</td>
                      <td>Lebih Harga</td>
                      <td>Total Commision</td>
                  </thead>
                  <tr align="center"> <!--dummy data -->
                      <td>70%</td>
                      <td>Rp. 15.000.000</td>
                      <td>Rp. 10</td>
                      <td>Rp. 1.000.000</td>
                      <td>Rp. 1.500.000</td>
                      <td>Rp. 15.000.000</td>
                  </tr>
                </table>
                <br />
                <table class="w-100 table-bordered my-2">
                  <thead class="font-weight-bold" align="center">
                      <td>Order DO</td>
                      <td>Percentage</td>
                      <td>Bonus</td>
                      <td>Upgrade</td>
                      <td>Bonus Semangat</td>
                      <td>Lebih Harga</td>
                      <td>Total Bonus</td>
                      <td>View</td>
                  </thead>
                  <tr align="center"> <!--dummy data -->
                      <td>15643</td>
                      <td>70%</td>
                      <td>Rp. 15.000.000</td>
                      <td>Rp. 10</td>
                      <td>Rp. 1.000.000</td>
                      <td>Rp. 1.500.000</td>
                      <td>Rp. 15.000.000</td>
                      <td>
                        <a href="#" target="_blank">
                          <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(99, 110, 114);"></i>
                        </a>
                      </td>
                  </tr>
                  <tfoot class="font-weight-bold" align="center">
                      <td colspan="2">Total</td>
                      <td>Rp. 15.000.000</td>
                      <td>Rp. 10</td>
                      <td>Rp. 1.000.000</td>
                      <td>Rp. 1.500.000</td>
                      <td>Rp. 15.000.000</td>
                      <td></td>
                  </tfoot>
                </table>
              </div>
            </div>

        </div>

            <!-- Modal Delete Payment -->
            <div class="modal fade"
                id="deleteKomisiConfirm"
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
                                Are You Sure to Delete this Commision?
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
            <!-- Error modal -->
            <div class="modal fade"
                id="error-modal"
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
                        <div id="error-modal-desc">
                            @if(session('error'))
                            <div class="modal-body">
                                <h5 class="modal-title text-center">Error</h5>
                                <hr>
                                <p class="text-center">{{ session('error') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal View -->
            @endif

            @else
            <div class="row justify-content-center">
                <h2>CANNOT FIND ORDER</h2>
            </div>
            @endif

    </section>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
<script>
    $(document).ready(function() {
        $(".delivery-cso-id").select2({
            theme: "bootstrap4",
            placeholder: "Choose CSO Delivery",
            dropdownParent: $('#modal-change-status .modal-body')
        });
        $("#from_warehouse_id").select2({
            theme: "bootstrap4",
            placeholder: "Choose Warehouse From",
            dropdownParent: $('#modal-change-status .modal-body')
        })
        $("#reject_to_warehouse_id").select2({
            theme: "bootstrap4",
            placeholder: "Choose Warehouse To",
            dropdownParent: $('#modal-change-status .modal-body')
        })
        $(document).on("input", 'input[data-type="currency"]', function() {
            $(this).val(numberWithCommas($(this).val()));
        });
        var statusOrder = "{{ $order['status'] }}";
        $(".btn-change-status-order").click(function(){
            statusOrder = $(this).attr('status-order');
            $('#delivery-cso').hide();
            $('#request-stock').hide();
            $('#delivered-image').hide();
            $('#delivery-stock, #delivery_date, #from_warehouse_id, #delivery_description, #orderDetail-product').hide();
            $('.delivery-cso-id').attr('disabled', true);
            $('#delivery-stock input[type=hidden]').attr('disabled', true);
            $('#delivery_date, #from_warehouse_id, .orderDetail-product').attr('disabled', true);
            $("#reject-stock, #reject_delivery_date, #reject_to_warehouse_id, #reject_delivery_description").hide();
            $('#reject-stock input[type=hidden]').attr('disabled', true);
            $("#reject_delivery_date, #reject_to_warehouse_id, #reject_delivery_description").attr('disabled', true);
            $('.prodCodeName, .prodQty').attr('disabled', true);
            $('.addDelivered-productimg').attr('disabled', true);
            $('#status-order').val(statusOrder);
            if (statusOrder == "{{\App\Order::$status['2']}}") {
                $("#modal-change-status-question").html('Process This Order?');
            } else if (statusOrder == "{{\App\Order::$status['6']}}")  {
                $('#request-stock').show();
                $('.prodCodeName, .prodQty').attr('disabled', false);
                $("#modal-change-status-question").html('Request Stock? \n Choose Product');
            } else if (statusOrder == "{{\App\Order::$status['3']}}")  {
                $('#delivery-cso').show();
                $('.delivery-cso-id').attr('disabled', false);
                $('#delivery-stock, #delivery_date, #from_warehouse_id, #delivery_description, #orderDetail-product').show();
                $('#delivery-stock input[type=hidden]').attr('disabled', false);
                $('#delivery_date, #from_warehouse_id, .orderDetail-product').attr('disabled', false);
                $("#modal-change-status-question").html('Delivery This Order?');
            } else if (statusOrder == "{{\App\Order::$status['8']}}") {
                $('#delivered-image').show();
                $('.addDelivered-productimg').attr('disabled', false);
                $("#modal-change-status-question").html('Delivered This Order?');
            } else if (statusOrder == "{{\App\Order::$status['4']}}") {
                $("#modal-change-status-question").html('Success This Order?');
            } else if (statusOrder == "{{\App\Order::$status['5']}}") {
                $("#reject-stock, #reject_delivery_date, #reject_to_warehouse_id, #reject_delivery_description").show();
                $('#reject-stock input[type=hidden]').attr('disabled', false);
                $("#reject_delivery_date, #reject_to_warehouse_id, #reject_delivery_description").attr('disabled', false);
                $("#modal-change-status-question").html('Reject This Order?');
            }
        });

        $('#tambah_cso').click(function(e){
            e.preventDefault();
            strIsi = `
                <div class="row form-cso">
                    <div class="form-group mb-3 col-10">
                        <label>Select CSO Delivery</label>
                        <select class="form-control delivery-cso-id" name="delivery_cso_id[]" style="width: 100%" required>
                            <option value="">Choose CSO Delivery</option>
                            @foreach ($csos as $cso)
                            <option value="{{ $cso['id'] }}">{{ $cso['code'] }} - {{ $cso['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-center"
                        style="display: inline-block; float: right;">
                        <button class="hapus_cso"
                            title="Hapus CSO"
                            style="padding: 0.4em 0.7em; background-color: red;">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>`;
            $('#tambahan_cso').append(strIsi);
            $(".delivery-cso-id").select2({
                theme: "bootstrap4",
                placeholder: "Choose CSO Delivery",
                dropdownParent: $('#modal-change-status .modal-body')
            });
        });

        $(document).on("click", ".hapus_cso", function (e) {
            e.preventDefault();
            $(this).parents(".form-cso")[0].remove();
        });

        @if(session('error'))
            $("#error-modal").modal('show');
        @endif

        function getWarehouse(warehouse_type, check_parent, target_element) {
            $(target_element).html("");
            return $.ajax({
                method: "GET",
                url: "{{ route('fetchWarehouse') }}",
                data: {warehouse_type, check_parent},
                success: function(response) {
                    var option_warehouse = `<option value="" selected disabled>Select Warehouse</option>`;
                    response.data.forEach(function(value) {
                        option_warehouse += `<option value="${value.id}">${value.code} - ${value.name}</option>`;
                    })
                    $(target_element).html(option_warehouse);
                },
            });
        }

        @if (($checkedOrderPayment == true || $checkStockInOutODetail == true)  && Gate::check('change-status_order_delivery'))
            const on_submit_orderDelivery = function(e) {
                e.preventDefault();

                // Check Min 1 Delivery Product
                if (statusOrder == "{{\App\Order::$status['3']}}" && $(".orderDetail-product:checked").length < 1) {
                    return alert('Choose min 1 product.');
                } else {
                    $(this).off('submit', on_submit_orderDelivery);
                    $(this).submit();
                }
            };
            $('#actionAdd').on('submit', on_submit_orderDelivery);

            getWarehouse(null, true, '#from_warehouse_id');
        @endif

        @if($order['status'] == \App\Order::$status['5'] && $checkTotalSales == true && $orderDetailStock->count() > 0 && Gate::check('change-status_order_reject'))
            getWarehouse(null, true, "#reject_to_warehouse_id");
        @endif

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

        $("#submitFrmAddPayment").on("click", function(e) {
            // Change numberWithComma before submit
            $("#frmAddPayment").find('input[data-type="currency"]').each(function() {
                $(this).val(numberNoCommas($(this).val()));
            });
            // $("#frmAddPayment").submit();
        });

        $("#imageAddPayment").on("change", function() {
            if ($("#imageAddPayment")[0].files.length > 3) {
                $("#imageAddPayment").val("")
                $('.custom-file-label').html("Choose image");
                alert("You can select only 3 images");
            }
        });

        // View Order Payment
        $(".btn-view_order_payment").click(function() {
            var url = '{{route("view_order_payment", ":id")}}'
            url = url.replace(':id', $(this).val())
            $.ajax({
                method: "get",
                url: url,
                success: function(data) {
                    $('#viewPaymentModal').modal('show');
                    $("#viewPayment-payment_date").val(data.payment_date);
                    $("#viewPayment-total_payment").val(numberWithCommas(data.total_payment));
                    $("#viewPayment-select_type").val(data.type).change()
                    $("#viewPayment-select_type_payment").val(data.type_payment).change()
                    $("#viewPayment-select_installment").val(data.credit_card_id).change()
                    $('#viewPayment-credit_card_name').html(data.credit_card_name)
                    $('#viewPayment-credit_card_installment').val(data.installment)
                    $("#viewPayment-select_bank").val(data.bank_account_id).change()
                    $('#viewPayment-bank_description').html(`
                        <b>${data.bank_name}</b> ${data.bank_account_name} (${data.bank_account_number})
                    `)
                    $('#viewPayment-charge_percentage_company').val(data.charge_percentage_company)
                    $('#viewPayment-charge_percentage_bank').val(data.charge_percentage_bank)
                    $('#viewPayment-estimate_transfer_date').val(data.estimate_transfer_date)
                    const mainUrlImage = "{{ asset('sources/order') }}";
                    $.each(JSON.parse(data.images), function(index, image) {
                        $("#viewPayment-image-" + index).css('background-image', 'url(' + mainUrlImage + "/" +image + ')');
                    });
                },
                error: function(data) {
                    $('#viewPaymentModal').modal('hide');
                    alert(data.responseJSON.error);
                }
            });
        });

        // Edit Order Payment
        $(".btn-edit_order_payment").click(function() {
            $("#submitFrmEditPayment").hide();
            $("#divUpdateStatusPayment").hide();
            var order_payment_id = $(this).val();
            $.ajax({
                method: "post",
                url: "{{ route('edit_order_payment') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    order_id: "{{ $order['id'] }}",
                    order_payment_id: order_payment_id
                },
                success: function(data) {
                    if (data.status == 'success') {
                        const result = data.result;
                        $("#editPayment-order_payment_id").val(order_payment_id);
                        $("#editPayment-payment_date").val(result.payment_date);
                        $("#editPayment-total_payment").val(numberWithCommas(result.total_payment));

                        $("#editPayment-credit_card_name").html('')
                        $("#editPayment-bank_description").html('')
                        if(result.credit_card_id){
                            $("#editPayment-select_installment").val(result.credit_card_id).change()
                            $('#editPayment-select_installment').attr('data-info', 'first');
                            infoFromCreditCard(result.credit_card_id)
                        }
                        if(result.bank_account_id){
                            $("#editPayment-select_bank").val(result.bank_account_id).change()
                            $('#editPayment-select_bank').attr('data-info', 'first');
                            infoFromBankAccount(result.bank_account_id)
                        }

                        $("#editPayment-select_type").val(result.type).change()
                        $("#editPayment-select_type_payment").val(result.type_payment).change()
                        $("#editPayment-credit_card_installment").val(result.cicilan)
                        $("#editPayment-bank_id").val(result.bank_id);
                        $("#editPayment-charge_percentage_company").val(result.charge_percentage_company)
                        $("#editPayment-charge_percentage_bank").val(result.charge_percentage_bank)
                        $("#editPayment-estimate_transfer_date").val(result.estimate_transfer_date)
                        if(result.type_payment == 'card installment' || result.type_payment == 'card'){
                            $('.editPayment-installment_form').prop({'disabled' : false, 'readonly' : false})
                        }else{
                            $('.editPayment-installment_form').prop({'disabled' : true, 'readonly' : true})
                        }

                        const mainUrlImage = "{{ asset('sources/order') }}";
                        $.each(JSON.parse(result.image), function(index, image) {
                            $("#editPayment-productimg-" + index).closest(".imgUp").find(".imagePreview")
                                .css('background-image', 'url(' + mainUrlImage + "/" +image + ')');
                        });

                        @if ($order['status'] != \App\Order::$status['5'] && Gate::check('change-status_payment'))
                        if (result.status != "verified") {
                            $("#updateStatusPayment-order_payment_id").val(order_payment_id);
                            $("#divUpdateStatusPayment").show();
                        }
                        @endif

                        $("#submitFrmEditPayment").show();
                    } else {
                        alert(data.result);
                    }
                },
                error: function(data) {
                    alert("Error!");
                }
            });
        });

        $(".btn-edit_order_payment_for_those_who_are_not_head_admin").click(function() {
            var id = $(this).val()
            var url = '{{route("view_order_payment", ":id")}}'
            url = url.replace(':id', id)
            $.ajax({
                method: "get",
                url: url,
                success: function(data) {
                    $('#editPaymentModalForThoseWhoAreNotHeadAdmin').modal('show');
                    var urlForm = '{{route("update_order_payment_for_those_who_are_not_head_admin", ":paymentID")}}'
                    urlForm = urlForm.replace(':paymentID', id)
                    $('#editFormPaymentForThoseWhoAreNotHeadAdmin').attr('action', urlForm)
                    $('#editPaymentForThoseWhoAreNotHeadAdmin-charge_percentage_company').val(data.charge_percentage_company)
                    $('#editPaymentForThoseWhoAreNotHeadAdmin-charge_percentage_bank').val(data.charge_percentage_bank)
                    $('#editPaymentForThoseWhoAreNotHeadAdmin-estimate_transfer_date').val(data.estimate_transfer_date)
                },
                error: function(data) {
                    $('#editPaymentModalForThoseWhoAreNotHeadAdmin').modal('hide');
                    alert(data.responseJSON.error);
                }
            });
        });

        $("#submitFrmEditPayment").on("click", function(e) {
            // Change numberWithComma before submit
            $("#frmEditPayment").find('input[data-type="currency"]').each(function() {
                $(this).val(numberNoCommas($(this).val()));
            });
            $("#frmEditPayment").submit();
        });

        $(".btn-delete_order_payment").click(function(e) {
            $("#frmDelete").attr("action",  $(this).val());
        });

        $("#addKomisiForm").slideUp();
        $("#editKomisiForm").slideUp();

        $(".btn-add-comms").click(function(e) {
            e.preventDefault();
            $("#addKomisiForm").slideDown();
        });
        $(".btn-cancel-comms").click(function(e) {
            e.preventDefault();
            $("#addKomisiForm").slideUp();
        });
        $(".btn-edit-comms").click(function(e) {
            e.preventDefault();
            $("#editKomisiForm").slideDown();
            $("#commisionDetailTrue").slideUp();
        });
        $(".btn-cancel-edit-comms").click(function(e) {
            e.preventDefault();
            $("#editKomisiForm").slideUp();
            $("#commisionDetailTrue").slideDown();
        });

        $(document).on("click", "i.del", function () {
            $(this).closest(".imgUp").find('.imagePreview').css("background-image", "");
            $(this).closest(".imgUp").find('input[type=text]').removeAttr("required");
            $(this).closest(".imgUp").find('.btn').find('.img').val("");
            $(this).closest(".imgUp").find('.form-control').val("");
            const inputImage = $(this).closest(".imgUp").find(".img");
            const inputImageId = inputImage.attr("id").split("-")[2];
            if (inputImageId == "0") {
                inputImage.attr("required", "");
            }
            $('<input>').attr({
                type: 'hidden',
                name: 'dltimg-' + inputImageId,
                value: inputImageId
            }).appendTo('#frmEditPayment');
        });

        $(function () {
            $(document).on("change", ".uploadFile", function () {
                const uploadFile = $(this);
                const files = this.files ? this.files : [];

                // no file selected, or no FileReader support
                if (!files.length || !window.FileReader) {
                    return;
                }

                // only image file
                if (/^image/.test(files[0].type)) {
                    // instance of the FileReader
                    const reader = new FileReader();
                    // read the local file
                    reader.readAsDataURL(files[0]);

                    // set image data as background of div
                    reader.onloadend = function () {
                        uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url(" + this.result + ")");
                    };
                }

            });
        });

        // add
        $('#typePayment').on('change', function() {
            if(this.value == 'card installment' || this.value == 'card'){
                $('.installment-form').prop({'disabled' : false, 'readonly' : false})
            }else{
                $('.installment-form').prop({'disabled' : true, 'readonly' : true})
                $('#selectInstallment').val('').trigger('change')
                $('#creditCardName').html('')
                $('#chargePercentageCompany').val(0)
                $('#creditCardInstallment').val(1)
                if($('#selectBank').val() !== ''){
                    var url = '{{ route("get_bank_account_from_payment_modal", ":id") }}';
                    url = url.replace(':id', $('#selectBank').val());
                    $.ajax({
                        type: "GET",
                        url: url,
                        success: function(data){
                            const d = new Date($("#add_payment_date").val());
                            d.setDate(d.getDate() + data.estimate_transfer);
                            var month = d.getMonth() + 1
                            var day = d.getDate()
                            if(month < 10){
                                month = "0"+month
                            }
                            if(day < 10){
                                day = "0"+day
                            }
                            var date = d.getFullYear() + "-" + month + "-" + day
                            $('#estimateTransferDate').val(date)
                        }
                    });
                }else{
                    $('#estimateTransferDate').val('{{date("Y-m-d")}}')
                }
            }
        });
        $(".select-with-select2").select2({
            theme: 'bootstrap4',
            placeholder: '-- select first --',
            dropdownParent: $('#addPaymentModal .modal-content')
        })
        $('#selectInstallment').on('change', function() {
            var url = '{{ route("get_credit_card", ":id") }}';
            url = url.replace(':id', this.value);
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
                    $('#creditCardName').html(`<span>${data.name}</span>`)
                    $('#creditCardInstallment').val(data.cicilan)
                    $('#selectBank').val(data.bank_account.id).trigger('change')
                    $('#bankDesc').html(`
                        <span><b>${data.bank_account.bank.name}</b> ${data.bank_account.name} (${data.bank_account.account_number})</span>
                    `)
                    $('#chargePercentageCompany').val(data.charge_percentage_company)
                    $('#chargePercentageBank').val(data.bank_account.charge_percentage)
                    const d = new Date($("#add_payment_date").val());
                    d.setDate(d.getDate() + data.estimate_transfer);
                    var month = d.getMonth() + 1
                    var day = d.getDate()
                    if(month < 10){
                        month = "0"+month
                    }
                    if(day < 10){
                        day = "0"+day
                    }
                    var date = d.getFullYear() + "-" + month + "-" + day
                    $('#estimateTransferDate').val(date)
                    $('#bank_id').val(data.bank_account.bank_id)
                }
            });
        });
        $('#selectBank').on('change', function() {
            var url = '{{ route("get_bank_account_from_payment_modal", ":id") }}';
            url = url.replace(':id', this.value);
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
                    $('#bankDesc').html(`
                        <span><b>${data.bank.name}</b> ${data.name} (${data.account_number})</span>
                    `)
                    $('#chargePercentageBank').val(data.charge_percentage)
                    if($("#selectInstallment").val() == ''){
                        const d = new Date($("#add_payment_date").val());
                        d.setDate(d.getDate() + data.estimate_transfer);
                        var month = d.getMonth() + 1
                        var day = d.getDate()
                        if(month < 10){
                            month = "0"+month
                        }
                        if(day < 10){
                            day = "0"+day
                        }
                        var date = d.getFullYear() + "-" + month + "-" + day
                        $('#estimateTransferDate').val(date)
                    }
                    $('#bank_id').val(data.bank_id)
                }
            });
        });

        // edit
        $(".editPayment-select_with_select2").select2({
            theme: 'bootstrap4',
            placeholder: '-- select first --',
            dropdownParent: $('#editPaymentModal .modal-content')
        })

        function infoFromCreditCard(id) {
            var url = '{{ route("get_credit_card", ":id") }}';
            url = url.replace(':id', id);
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
                    $('#editPayment-credit_card_name').html(`<span>${data.name}</span>`)
                    $('#editPayment-bank_description').html(`
                       <span><b>${data.bank_account.bank.name}</b> ${data.bank_account.name} (${data.bank_account.account_number})</span>
                    `)
                }
            });
        }
        function infoFromBankAccount(id) {
            var url = '{{ route("get_bank_account_from_payment_modal", ":id") }}';
            url = url.replace(':id', id);
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
                    $('#editPayment-bank_description').html(`
                       <span><b>${data.bank.name}</b> ${data.name} (${data.account_number})</span>
                    `)
                }
            });
        }
        $('#frmAddPayment, #frmEditPayment').bind('submit', function () {
            $('.installment-form, .editPayment-installment_form').prop({'disabled' : false, 'readonly' : false})
        });

        $('#editPayment-select_type_payment').on('change', function() {
            if(this.value == 'card installment' || this.value == 'card'){
                $('.editPayment-installment_form').prop({'disabled' : false, 'readonly' : false})
            }else{
                $('.editPayment-installment_form').prop({'disabled' : true, 'readonly' : true})
                $('#editPayment-select_installment').val('').trigger('change')
                $('#editPayment-credit_card_name').html('')
                $('#editPayment-charge_percentage_company').val(0)
                $('#editPayment-credit_card_installment').val(1)
                if($('#editPayment-select_bank').val() == ''){
                    $('#editPayment-estimate_transfer_date').val('{{date("Y-m-d")}}')
                }
            }
        });

        $('#editPayment-select_installment').on('change', function() {
            if(this.value){
                var url = '{{ route("get_credit_card", ":id") }}';
                url = url.replace(':id', this.value);
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(data){
                        if($('#editPayment-select_installment').attr('data-info') !== 'first'){
                            $('#editPayment-credit_card_name').html(`<span>${data.name}</span>`)
                            $('#editPayment-credit_card_installment').val(data.cicilan)
                            $('#editPayment-select_bank').val(data.bank_account.id).trigger('change')
                            $('#editPayment-bank_description').html(`
                                <span><b>${data.bank_account.bank.name}</b> ${data.bank_account.name} (${data.bank_account.account_number})</span>
                            `)
                            $('#editPayment-charge_percentage_company').val(data.charge_percentage_company)
                            $('#editPayment-charge_percentage_bank').val(data.bank_account.charge_percentage)
                            const d = new Date($("#editPayment-payment_date").val());
                            d.setDate(d.getDate() + data.estimate_transfer);
                            var month = d.getMonth() + 1
                            var day = d.getDate()
                            if(month < 10){
                                month = "0"+month
                            }
                            if(day < 10){
                                day = "0"+day
                            }
                            var date = d.getFullYear() + "-" + month + "-" + day
                            $('#editPayment-estimate_transfer_date').val(date)
                            $('#editPayment-bank_id').val(data.bank_account.bank_id)
                        }
                        $('#editPayment-select_installment').attr('data-info', '')
                    }
                });
            }
        });

        $('#editPayment-select_bank').on('change', function() {
            if(this.value){
                var url = '{{ route("get_bank_account_from_payment_modal", ":id") }}';
                url = url.replace(':id', this.value);
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(data){
                        if($('#editPayment-select_bank').attr('data-info') !== 'first'){
                            $('#editPayment-bank_description').html(`
                                <span><b>${data.bank.name}</b> ${data.name} (${data.account_number})</span>
                            `)
                            $('#editPayment-charge_percentage_bank').val(data.charge_percentage)
                            if($("#editPayment-select_installment").val() == ''){
                                const d = new Date($("#editPayment-payment_date").val());
                                d.setDate(d.getDate() + data.estimate_transfer);
                                var month = d.getMonth() + 1
                                var day = d.getDate()
                                if(month < 10){
                                    month = "0"+month
                                }
                                if(day < 10){
                                    day = "0"+day
                                }
                                var date = d.getFullYear() + "-" + month + "-" + day
                                $('#editPayment-estimate_transfer_date').val(date)
                            }
                            $('#editPayment-bank_id').val(data.bank_id)
                        }
                        $('#editPayment-select_bank').attr('data-info', '')
                    }
                });
            }
        });
    });

    $('#commission_type_select').on('change', function() {
        $("#commission_type_description").val($(this).find(':selected').attr('data-description'))
        if({{$isUpgrade}} == 1){ // upgrade
            $("#commission_type_upgrade").val($(this).find(':selected').attr('data-nominal'))
            $("#commission_type_bonus").val(0)
        }else{ // bonus
            $("#commission_type_bonus").val($(this).find(':selected').attr('data-nominal'))
            $("#commission_type_upgrade").val(0)
        }
        $("#commission_type_smgt_nominal").val($(this).find(':selected').attr('data-smgt-nominal'))
        $("#commission_type_excess_price").val(0)
    });
</script>
@endsection
