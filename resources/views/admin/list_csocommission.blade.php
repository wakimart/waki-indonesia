<?php
$menu_item_page_sub = "cso_commission";
?>
@extends('admin.layouts.template')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">List Commission</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#order_report_branch-dd"
                            aria-expanded="false"
                            aria-controls="order_report_branch-dd">
                            Cso Commission
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        List Commission
                    </li>
                </ol>
            </nav>
        </div>

        <div class="col-12 grid-margin" style="padding: 0;">
            <div class="col-xs-12 col-sm-12 row">
                <div class="col-xs-6 col-sm-4" style="display: inline-block;">
                    <div class="form-group">
                        <label for="">Month & Year</label>
                        <input type="month"
                            class="form-control"
                            id="filter_month"
                            name="filter_month"
                            value="{{ isset($_GET['filter_month']) ? $_GET['filter_month'] : date('Y-m') }}">
                        <div class="validation"></div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-4" style="display: inline-block;">
                    <div class="form-group">
                        <label for="filter_branch">
                            Filter By Branch
                        </label>
                        <select class="form-control"
                            id="filter_branch"
                            name="filter_branch">
                            <option value="" selected="">
                                Please select branch
                            </option>
                            <option value="all" {{isset($_GET['filter_branch']) ? $_GET['filter_branch'] == 'all' ? 'selected' : '' : ''}}>All Branch</option>
                            @foreach ($branches as $branch)
                                @php
                                $selected = "";

                                if (isset($_GET['filter_branch'])) {
                                    if ((int) $_GET['filter_branch'] === (int) $branch['id']) {
                                        $selected = "selected";
                                    }
                                }
                                @endphp

                                <option {{ $selected }}
                                    value="{{ $branch['id'] }}">
                                    {{ $branch['code'] }} - {{ $branch['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 row"
                style="margin: 0; padding: 0;">
                <div class="col-xs-8 col-sm-8"
                    style="padding: 0; display: inline-block;">
                    <div class="form-group">
                        <button id="btn-filter"
                            type="button"
                            class="btn btn-gradient-primary m-1"
                            name="filter"
                            value="-">
                            <span class="mdi mdi-filter"></span> Apply Filter
                        </button>
                        <a href="{{ route('list_cso_commission') }}"
                            class="btn btn-gradient-danger m-1"
                            value="-">
                            <span class="mdi mdi-filter"></span> Reset Filter
                        </a>
                        @php
                            $exportParameter = null;
                            if (isset($_GET['filter_branch'])) {
                                $exportParameter['filter_branch'] = $_GET['filter_branch'];
                            }
                            if (isset($_GET['filter_month'])) {
                                $exportParameter['filter_month'] = $_GET['filter_month'];
                            }
                        @endphp
                        <a href="{{ route('exportCsoCommission', $exportParameter) }}"
                            class="btn btn-gradient-info m-1 {{ $exportParameter != null ? "" : "d-none" }}">
                            <span class="mdi mdi-file-document"></span>
                            Export Commission
                        </a>
                        @if(isset($_GET['filter_branch']) && isset($_GET['filter_month']) && Gate::check('cut_custom_nominal-order_commission'))
                            <button id="btn-commisison-cut"
                                type="button"
                                class="btn btn-gradient-warning m-1"
                                name="commisison-cut"
                                value="{{ $_GET['filter_branch'] }}">
                                <span class="mdi mdi-percent"></span> Commission Cut
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12"
                style="padding: 0; border: 1px solid #ebedf2;">
                <div class="col-xs-12 col-sm-11 col-md-6 table-responsive"
                    id="calendarContainer"
                    style="padding: 0; float: left;"></div>
                <div class="col-xs-12 col-sm-11 col-md-6"
                    id="organizerContainer"
                    style="padding: 0; float: left;"></div>
            </div>
        </div>

        <div class="col-12 grid-margin stretch-card" style="padding: 0;">
            <div class="card">
                <div class="card-body">
                    @if($allCsoCommission)
                        @if($_GET['filter_branch'] !== 'all')
                            <div class="mb-3">
                                <h4>Branch : {{ $branches->where('id', $_GET['filter_branch']) ? $branches->where('id', $_GET['filter_branch'])->first()['code'].' - '.$branches->where('id', $_GET['filter_branch'])->first()['name'] : "-"}}</h4>
                            </div>
                            <h5>Date: {{ date("d/m/Y", strtotime($startDate)) }} - {{ date("d/m/Y", strtotime($endDate)) }}</h5>
                            <h5 style="margin-bottom: 0.5em;">
                                Total : {{ count($allCsoCommission) }} data
                            </h5>
                            <div class="table-responsive" style="border: 1px solid #ebedf2;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th>No. </th>
                                            <th class="text-left">CSO - Name </th>
                                            <th>Bank Account</th>
                                            <th>Netto Sale</th>
                                            <th>Cancel</th>
                                            <th>Commission</th>
                                            <th>Bonus</th>
                                            <th>Tax</th>
                                            <th>Total Commission Bonus</th>
                                            <th colspan="3">Detail/Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $tot_netto_sale = 0;
                                            $tot_commission = 0;
                                            $tot_pajak = 0;
                                            $tot_result = 0;
                                            $tot_bonus = 0;
                                            $tot_cancel = 0;
                                        @endphp

                                        @foreach ($allCsoCommission as $key => $perCsoCommission)
                                            @php
                                                $netto_sale = floor($perCsoCommission['total_sales']);
                                                $bonusPerCso = floor($perCsoCommission['bonusPerCso']);
                                                $commissionPerCso = floor($perCsoCommission['commissionPerCso']);
                                                $cancelPerCso = floor($perCsoCommission['cancelPerCso']);

                                                $tot_netto_sale += $netto_sale;
                                                $tot_commission += $commissionPerCso;
                                                $tot_bonus += $bonusPerCso;
                                                $tot_cancel += $cancelPerCso;

                                                $tot_result += $commissionPerCso + $bonusPerCso - $cancelPerCso;

                                                $getCsoCommission = 0;
                                                foreach($perCsoCommission->csoCommission as $value){
                                                    if($startDate." 00:00:00" == $value->created_at){
                                                        $getCsoCommission = $value->id;
                                                    }
                                                }
                                            @endphp

                                            <tr>
                                                <td class="text-center">{{ $key+1 }}</td>
                                                <td>{{ $perCsoCommission['code'] }} - {{ $perCsoCommission['name'] }}</td>
                                                <td>{{ $perCsoCommission['no_rekening'] }}</td>
                                                <td class="text-right">Rp. {{ number_format($netto_sale) }}</td>
                                                <td class="text-right">Rp. {{ number_format($cancelPerCso) }}</td>
                                                <td class="text-right">Rp. {{ number_format($commissionPerCso - (0.03*$cancelPerCso)) }}</td>
                                                <td class="text-right">Rp. {{ number_format($bonusPerCso) }}</td>
                                                <td class="text-right">Rp. 0</td>
                                                <td class="text-right">Rp. {{ number_format($commissionPerCso + $bonusPerCso) }}</td>
                                                <td class="text-center">
                                                    @if(Gate::check('detail-cso_commission') && $getCsoCommission)
                                                    <a href="{{ route('detail_cso_commission', ['cso_commission_id' => $getCsoCommission, 'branch_id' => $_GET['filter_branch']]) }}" target="_blank">
                                                        <i class="mdi mdi-eye text-info" style="font-size: 24px;"></i>
                                                    </a>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if(Gate::check('edit-cso_commission') && $getCsoCommission)
                                                    <button class="btn-delete btn-edit_cso_commission" value="{{ $getCsoCommission }}">
                                                        <i class="mdi mdi-border-color text-warning" style="font-size: 24px;"></i>
                                                    </button>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if(Gate::check('delete-cso_commission') && $getCsoCommission)
                                                    <button class="btn-delete btn-delete_cso_commission" value="{{ $getCsoCommission }}">
                                                        <i class="mdi mdi-delete text-danger" style="font-size: 24px;"></i>
                                                    </button>
                                                    @endif
                                                </td>
                                            </tr>
                                            @php

                                            @endphp
                                        @endforeach
                                        <tr class="text-right">
                                            <th colspan="3" rowspan="2">TOTAL COMMISSION</th>
                                            <th>Rp. {{ number_format($tot_netto_sale) }}</th>
                                            <th>Rp. {{ number_format($tot_cancel) }}</th>
                                            <th>Rp. {{ number_format($tot_commission) }}</th>
                                            <th>Rp. {{ number_format($tot_bonus) }}</th>
                                            <th>Rp. 0</th>
                                            <th>Rp. {{ number_format($tot_result) }}</th>
                                        </tr>
                                        {{-- <tr class="text-right">
                                            <th colspan="4">Rp. {{ number_format($tot_commission + $tot_bonus + $tot_pajak + $tot_result) }}</th>
                                        </tr> --}}
                                    </tbody>
                                </table>
                                <br/>
                            </div>
                        @endif
                    @else
                        <h2 class="text-info text-center m-0">Please Select Month & Branch Filter First</h2>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit PaymentCSO Commissiont Head Admin -->
<div class="modal fade"
    id="editCsoCommision"
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
            <form method="post" id="editFormCsoCommision" action="">
                @csrf
                {{ method_field('PUT') }}
                <div class="modal-body">
                    <h5 style="text-align: center;">
                        Edit CSO Commission
                    </h5>
                    <br>
                    <input type="hidden" name="id" value="">
                    <div class="form-group">
                        <label for="">Month</label>
                        <input type="month"
                            id="editCsoCommision-month"
                            class="form-control"
                            readonly="" />
                    </div>
                    <div class="form-group">
                        <label for="">CSO - Name</label>
                        <input type="text"
                            id="editCsoCommision-cso"
                            class="form-control"
                            readonly="" />
                    </div>
                    <div class="form-group">
                        <label for="">Nominal Commmission</label>
                        <input type="text"
                            name="commission"
                            id="editCsoCommision-commission"
                            class="form-control"
                            data-type="currency"/>
                    </div>
                    <div class="form-group">
                        <label for="">Cancel <small style="color: red;">*temp</small></label>
                        <input type="text"
                            name="cancel"
                            id="editCsoCommision-cancel"
                            class="form-control"
                            data-type="currency"/>
                    </div>
                    <div class="form-group">
                        <label for="">Nominal Tax</label>
                        <input type="text"
                            name="pajak"
                            id="editCsoCommision-pajak"
                            class="form-control"
                            data-type="currency"/>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer">
                    @if(Gate::check('edit-cso_commission'))
                    <button id="submitFormEditCommission" type="submit" class="btn btn-gradient-success mr-2">Save</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Modal Edit CSO Commission -->

<!-- Modal Delete Payment -->
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
                    Are You Sure to Delete this Payment?
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

<!-- Modal Cut Commission for Certain Branch Head Admin -->
<div class="modal fade"
    id="commissionCutModal"
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
            <form method="post" id="cutCommissionForm" action="{{ route('cut_commission_order') }}">
                @csrf
                {{ method_field('PUT') }}
                <div class="modal-body">
                    <h5 style="text-align: center;">
                        Cut Commission for <span id="commisison-cut-branch"></span>
                    </h5>
                    <br>
                    <input type="hidden" name="commissionCut-month" value="{{ isset($_GET['filter_month']) ? $_GET['filter_month'] : date('Y-m') }}">
                    <input type="hidden" name="commissionCut-branch" value="{{ isset($_GET['filter_branch']) ? $_GET['filter_branch'] : "" }}">
                    <input type="hidden" name="commissionCut-totalSaleBranch" value="{{ isset($totalSaleBranch) ? $totalSaleBranch : 0 }}">
                    <div class="form-group">
                        <label for="">Month</label>
                        <input type="month"
                            id="commissionCut-month"
                            class="form-control"
                            value="{{ isset($_GET['filter_month']) ? $_GET['filter_month'] : date('Y-m') }}"
                            disabled="" />
                    </div>
                    <div class="form-group">
                        <label for="commissionCut-branch">
                            Filter By Branch
                        </label>
                        <select class="form-control"
                            id="commissionCut-branch"
                            disabled="">
                            <option value="" selected="">
                                Please select branch
                            </option>
                            @foreach ($branches as $branch)
                                @php
                                $selected = "";

                                if (isset($_GET['filter_branch'])) {
                                    if ((int) $_GET['filter_branch'] === (int) $branch['id']) {
                                        $selected = "selected";
                                    }
                                }
                                @endphp

                                <option {{ $selected }}
                                    value="{{ $branch['id'] }}">
                                    {{ $branch['code'] }} - {{ $branch['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Total Sale Netto Branch</label>
                        <input type="text"
                            id="commissionCut-totalSaleBranch"
                            class="form-control"
                            data-type="currency"
                            disabled="" 
                            value="Rp. {{ number_format($totalSaleBranch) }}" />
                    </div>

                    <div class="col-12">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Minimal Total Sale</label>
                                    <input type="text"
                                        name="commissionCut-minimalTotalSale"
                                        id="commissionCut-minimalTotalSale"
                                        class="form-control"
                                        data-type="currency"
                                        value="{{ number_format(250000000) }}" />
                                </div>                            
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Cut Percentage (%)</label>
                                    <input type="number"
                                        min="0"
                                        max="100"
                                        value="40" 
                                        name="commissionCut-cutPercentage"
                                        id="commissionCut-cutPercentage"
                                        class="form-control"/>
                                </div>
                            </div>
                        </div>
                    </div>                    
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer">
                    @if(Gate::check('edit-cso_commission'))
                    <button id="submitFormCutCommission" type="submit" class="btn btn-gradient-success mr-2">Save</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Cut Commission for Certain Branch Head Admin -->

@endsection

@section('script')
<script>
    $(document).on("input", 'input[data-type="currency"]', function() {
        $(this).val(numberWithCommas($(this).val()));
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

    $(".btn-edit_cso_commission").click(function() {
        var id = $(this).val()
        var url = '{{route("edit_cso_commission", ":id")}}'
        url = url.replace(':id', id);
        $.ajax({
            method: "get",
            url: url,
            success: function(data) {
                $('#editCsoCommision').modal('show');
                var urlForm = '{{route("update_cso_commission", ":id")}}'
                urlForm = urlForm.replace(':id', id)
                $('#editFormCsoCommision').attr('action', urlForm);
                $('#editCsoCommision-month').val(data['month']);
                $('#editCsoCommision-cso').val(data['cso']);
                $('#editCsoCommision-commission').val(numberWithCommas(data['commission']));
                $('#editCsoCommision-pajak').val(numberWithCommas(data['pajak']));
            },
            error: function(data) {
                $('#editCsoCommision').modal('hide');
                alert(data.responseJSON.error);
            }
        });
    });

    $(".btn-delete_cso_commission").click(function() {
        var id = $(this).val();
        var urlForm = '{{route("delete_cso_commission", ":id")}}';
        urlForm = urlForm.replace(':id', id)
        $('#frmDelete').attr('action', urlForm);
        $('#deleteDoModal').modal('show');
    });

    $("#submitFormEditCommissio").on("click", function(e) {
        $("#editFormCsoCommision").find('input[data-type="currency"]').each(function() {
            $(this).val(numberNoCommas($(this).val()));
        });
        $("#editFormCsoCommision").submit();
        $("#submitFormEditCommission").html("Loading...");
        $("#submitFormEditCommission").attr("disabled", true);
    });

    $("#submitFormCutCommission").on("click", function(e) {
        $("#cutCommissionForm").find('input[data-type="currency"]').each(function() {
            $(this).val(numberNoCommas($(this).val()));
        });
        $("#cutCommissionForm").submit();
        $("#submitFormCutCommission").html("Loading...");
        $("#submitFormCutCommission").attr("disabled", true);
    });

    $("#btn-commisison-cut").click(function() {
        var branch_id = $(this).val()
        $('#commissionCutModal').modal('show');
    });

    $(document).on("click", "#btn-filter", function (e) {
        var urlParamArray = new Array();
        var urlParamStr = "";

        if ($('#filter_month').val() != "") {
            urlParamArray.push("filter_month=" + $('#filter_month').val());
        }

        if ($('#filter_branch').val() != "") {
            urlParamArray.push("filter_branch=" + $('#filter_branch').val());
        }

        for (var i = 0; i < urlParamArray.length; i++) {
            if (i === 0) {
                urlParamStr += "?" + urlParamArray[i]
            } else {
                urlParamStr += "&" + urlParamArray[i]
            }
        }

        window.location.href = "{{route('list_cso_commission')}}" + urlParamStr;
    });
</script>
@endsection
