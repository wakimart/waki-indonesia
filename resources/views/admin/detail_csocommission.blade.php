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
    <section id="intro" class="clearfix">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="w-100 my-2" id="commisionDetail" class="">
                    <h3 class="text-center">CSO Commision Detail</h3>
                    <table class="w-100 table-bordered">
                        <tr align="left">
                            <td class="font-weight-bold w-25">Month</td>
                            <td>{{ date('M Y', strtotime($cso_commission->created_at)) }}</td>
                        </tr>
                        <tr align="left"> <!--dummy data -->
                            <td class="font-weight-bold w-25">Branch</td>
                            <td>{{ $cso_commission->cso->branch['code'] }} - {{ $cso_commission->cso->branch['name'] }}</td>
                        </tr>
                        <tr align="left"> <!--dummy data -->
                            <td class="font-weight-bold w-25">CSO Code</td>
                            <td>{{ $cso_commission->cso['code'] }}</td>
                        </tr>
                        <tr align="left"> <!--dummy data -->
                            <td class="font-weight-bold w-25">CSO Name</td>
                            <td>{{ $cso_commission->cso['name'] }}</td>
                        </tr>
                        <tr align="left"> <!--dummy data -->
                            <td class="font-weight-bold w-25">CSO Account Number</td>
                            <td>{{ $cso_commission->cso['no_rekening'] }}</td>
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
                        <tr align="center">
                            @php
                                $bonusCso = $cso_commission->orderCommission->sum(function ($row) {return ($row->bonus);});
                                $upgradeCso = $cso_commission->orderCommission->sum(function ($row) {return ($row->upgrade);});
                                $smgt_nominalCso = $cso_commission->orderCommission->sum(function ($row) {return ($row->smgt_nominal);});
                                $excess_priceCso = $cso_commission->orderCommission->sum(function ($row) {return ($row->excess_price);});
                            @endphp

                            <td>Rp. {{ number_format($cso_commission['commission']) }}</td>
                            <td>Rp. {{ number_format($bonusCso) }}</td>
                            <td>Rp. {{ number_format($upgradeCso) }}</td>
                            <td>Rp. {{ number_format($smgt_nominalCso) }}</td>
                            <td>Rp. {{ number_format($excess_priceCso) }}</td>
                            <td>Rp. {{ number_format($cso_commission['commission'] + $bonusCso + $upgradeCso + $smgt_nominalCso + $excess_priceCso) }}</td>
                        </tr>
                    </table>
                    <br />
                    <table class="w-100 table-bordered my-2">
                        <thead class="font-weight-bold" align="center">
                            <td>Order Date</td>
                            <td>Order DO</td>
                            <td>Percentage</td>
                            <td>Bonus</td>
                            <td>Upgrade</td>
                            <td>Bonus Semangat</td>
                            <td>Lebih Harga</td>
                            <td>Total Bonus</td>
                            <td>View</td>
                        </thead>
                        @foreach($cso_commission->orderCommission as $orderPerCommission)
                            <tr align="center">
                                <td>{{ date('d/m/Y', strtotime($orderPerCommission->order['orderDate'])) }}</td>
                                <td>{{ $orderPerCommission->order['temp_no'] }}</td>
                                <td>
                                    {{ $orderPerCommission->order['30_cso_id'] == $orderPerCommission->order['70_cso_id'] ? "100%" : ($orderPerCommission->order['30_cso_id'] == $cso_commission->cso['id'] ? "30%" : "70%") }}
                                </td>
                                <td>Rp. {{ number_format($orderPerCommission['bonus']) }}</td>
                                <td>Rp. {{ number_format($orderPerCommission['upgrade']) }}</td>
                                <td>Rp. {{ number_format($orderPerCommission['smgt_nominal']) }}</td>
                                <td>Rp. {{ number_format($orderPerCommission['excess_price']) }}</td>
                                <td>Rp. {{ number_format($orderPerCommission['bonus'] + $orderPerCommission['upgrade'] + $orderPerCommission['smgt_nominal'] + $orderPerCommission['excess_price']) }}</td>
                                <td>
                                    <a href="{{ route('detail_order') }}?code={{ $orderPerCommission->order['code'] }}" target="_blank">
                                        <i class="mdi mdi-eye text-info" style="font-size: 24px; color: rgb(99, 110, 114);"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        <tfoot class="font-weight-bold" align="center">
                            <td colspan="2">Total</td>
                            <td>Rp. {{ number_format($bonusCso) }}</td>
                            <td>Rp. {{ number_format($upgradeCso) }}</td>
                            <td>Rp. {{ number_format($smgt_nominalCso) }}</td>
                            <td>Rp. {{ number_format($excess_priceCso) }}</td>
                            <td>Rp. {{ number_format($bonusCso + $upgradeCso + $smgt_nominalCso + $excess_priceCso) }}</td>
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
    </section>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
@endsection
