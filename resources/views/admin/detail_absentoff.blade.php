<?php
$menu_item_page = "absent_off";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    #intro {
        padding-top: 2em;
    }

    #regForm {
        background-color: #ffffff;
        margin: 100px auto;
        padding: 40px;
        width: 70%;
        min-width: 300px;
    }

    table {
        margin: 1em;
        font-size: 14px;
    }

    table thead {
        background-color: #8080801a;
        text-align: center;
    }

    table td {
        border: 0.5px #8080801a solid;
        padding: 0.5em;
    }

    .center {
        text-align: center;
    }

    .right {
        text-align: right;
    }

    .justify-content-center {
        padding: 0em 1em;
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
        <div class="page-header">
            <h3 class="page-title">Detail Cuti</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#absent_off-dd"
                            aria-expanded="false"
                            aria-controls="absent_off-dd">
                            Cuti
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Detail Cuti
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <h2>Detail Cuti</h2>
                        </div>
                        <div class="row justify-content-center">
                            <div class="table-responsive">
                                <table class="col-md-12">
                                    <thead>
                                        <td colspan="2">Data</td>
                                    </thead>
                                    <tr>
                                        <td>Name</td>
                                        <td>{{ $absentOff->cso->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Branch</td>
                                        <td>{{ $absentOff->branch->code }} - {{ $absentOff->branch->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Cso</td>
                                        <td>{{ $absentOff->cso->code }}</td>
                                    </tr>
                                    <tr>
                                        <td>Division</td>
                                        <td>{{ $absentOff->division }}</td>
                                    </tr>
                                    <tr>
                                        <td>Work Since</td>
                                        <td>{{ $absentOff->duration_work }}</td>
                                    </tr>
                                    <tr>
                                        <td>Duration</td>
                                        <td>{{ $absentOff->duration_off }} days</td>
                                    </tr>
                                    <tr>
                                        <td>Date</td>
                                        <td>{{ date('d F Y', strtotime($absentOff->start_date)) }} - {{ date('d F Y', strtotime($absentOff->end_date)) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Work On</td>
                                        <td>{{ date('d F Y', strtotime($absentOff->work_on)) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Reason Absent</td>
                                        <td>{{ $absentOff->desc }}</td>
                                    </tr>
                                    <tr>
                                        <td>Created By</td>
                                        <td>{{ $absentOff->user->name }} ({{ $absentOff->created_at }})</td>
                                    </tr>
                                    @php 
                                        $historyUpdates = App\HistoryUpdate::where([['type_menu', 'Absent Off'], ['menu_id', $absentOff['id']]])
                                            ->orderBy('id', 'asc')->get();
                                    @endphp
                                    @if (!$historyUpdates->isEmpty())
                                        <tr>
                                            <td>Updated By</td>
                                            <td>
                                                @foreach ($historyUpdates as $historyUpdate)
                                                {{ $historyUpdate->user->name }} ({{ $historyUpdate['meta']['createdAt'] }})<br>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                                <table class="col-md-12">
                                    <thead>
                                        <td>Status Cuti</td>
                                    </thead>
                                    <tr>
                                        <td class="text-center">
                                            @if ($absentOff['status'] == \App\AbsentOff::$status['1'])
                                                <span class="badge badge-primary">New</span>
                                            @elseif ($absentOff['status'] == \App\AbsentOff::$status['2'])
                                                <span class="badge badge-success">Approved</span>
                                            @elseif ($absentOff['status'] == \App\AbsentOff::$status['3'])
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @php 
            $statusAcc = [];
            if ($absentOff->supervisor_id || $absentOff->coordinator_id) {
                foreach ($absentOff->historyUpdateAcc() as $historyUpdateAcc) {
                    $statusAcc[$historyUpdateAcc['meta']['acc_cuti_type']] = $historyUpdateAcc;
                }
            }
            $accCutiTypes = ["supervisor", "coordinator"];
            @endphp
            @foreach ($accCutiTypes as $accCutiType)
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group text-center">
                            <p>Status Acc by {{ ucwords($accCutiType) }}</p>
                            @if (isset($statusAcc[$accCutiType]))
                                @if ($statusAcc[$accCutiType]['meta']['status_acc'] == "true")
                                <p style="color: green;">
                                    Approved : {{ $statusAcc[$accCutiType]['meta']['createdAt'] }}
                                    <br class="break"> Approved by : {{ $statusAcc[$accCutiType]['u_name'] }}
                                </p>
                                @else
                                <p style="color: red;">
                                    Rejected : {{ $statusAcc[$accCutiType]['meta']['createdAt'] }}
                                    <br class="break"> Rejected by : {{ $statusAcc[$accCutiType]['u_name'] }}
                                </p>
                                @endif
                            @elseif ($absentOff->status == App\AbsentOff::$status['3'])
                                <p style="color: red;">Rejected Automatically</p>
                            @else
                            <p>Wait to Approve</p>
                                @if (Gate::check('acc-absent_off'))
                                    @if (
                                        ($accCutiType == "supervisor" && (Gate::check('acc-spv_absent_off') || Gate::check('acc-reject_spv_absent_off')))
                                        || ($accCutiType == "coordinator" && (Gate::check('acc-coor_absent_off') || Gate::check('acc-reject_coor_absent_off')))
                                    )
                                        <form id="formAccAbsentOff" method="POST" action="{{ route('update_acc_absent_off') }}" style="margin: auto;">
                                            @csrf
                                            <div class="form-group">
                                                <input type="hidden" id="hiddenInput" name="acc_cuti_type" value="{{ $accCutiType }}" />
                                                <input type="hidden" id="input_id_hidden" name="id" value="{{ $absentOff->id }}" />

                                                <div style="text-align: center; font-weight: bold;">
                                                    <p>Do you approved it as {{ ucwords($accCutiType) }} ?</p>
                                                </div>

                                                <div class="btn-action" style="text-align: center;">
                                                    @if (
                                                        ($accCutiType == "supervisor" && Gate::check('acc-spv_absent_off'))
                                                        || ($accCutiType == "coordinator" && Gate::check('acc-coor_absent_off'))
                                                    )
                                                        <button type="submit" class="btn btn-gradient-primary" name="status_acc" value="true">Yes</button>
                                                    @endif
                                                    @if (
                                                        ($accCutiType == "supervisor" && Gate::check('acc-reject_spv_absent_off'))
                                                        || ($accCutiType == "coordinator" && Gate::check('acc-reject_coor_absent_off'))
                                                    )
                                                        <button type="submit" class="btn btn-gradient-danger" name="status_acc" value="false">No</button>
                                                    @endif
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
