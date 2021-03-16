<?php
$menu_item_page = "upgrade";
$menu_item_second = "new_upgrade_form";
?>
@extends('admin.layouts.template')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">New Upgrade List</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#"
                            aria-expanded="false"
                            aria-controls="upgrade-dd">
                            Upgrade
                        </a>
                    </li>
                    <li class="breadcrumb-item active"
                        aria-current="page">
                        New Upgrade List
                    </li>
                </ol>
            </nav>
        </div>

        <div class="col-12 grid-margin stretch-card" style="padding: 0;">
            <div class="card">
                <div class="card-body">
                    <h5 style="margin-bottom: 0.5em;">
                        Total: {{ $upgrades->count() }} data
                    </h5>
                    <div class="table-responsive"
                        style="border: 1px solid #ebedf2;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Upgrade Date</th>
                                    <th>Member Name</th>
                                    <th>Upgrade Product</th>
                                    <th>Branch</th>
                                    <th>CSO</th>
                                    {{-- @if(Gate::check('edit-deliveryorder') || Gate::check('delete-deliveryorder')) --}}
                                        <th colspan="2">Process</th>
                                    {{-- @endif --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upgrades as $key => $upgrade)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            {{ date("d/m/Y", strtotime($upgrade->acceptance->upgrade_date)) }}
                                        </td>
                                        <td>
                                            {{ $upgrade->acceptance->name }}
                                        </td>
                                        <td>
                                            {{ $upgrade->acceptance->oldproduct['code'] }} - {{ $upgrade->acceptance->oldproduct['name'] }}
                                        </td>
                                        <td>
                                            {{ $upgrade->acceptance->branch->code }} - {{ $upgrade->acceptance->branch->name }}
                                        </td>
                                        <td>
                                            {{ $upgrade->acceptance->cso->code }} - {{ $upgrade->acceptance->cso->name }}
                                        </td>
                                        {{-- @can('edit-deliveryorder') --}}
                                            <td style="text-align: center;">
                                                <a href="{{ route('add_upgrade_form' ,['id' => $upgrade['id']]) }}">
                                                    <i class="mdi mdi-timer-sand" style="font-size: 24px; color: #fed713;"></i>
                                                </a>
                                            </td>
                                        {{-- @endcan --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{ $upgrades->appends($url)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- partial -->
@endsection

@section('script')
<script>
$(document).ready(function (e) {
    $(".btn-delete").click(function (e) {
        $("#frmDelete").attr("action",  $(this).val());
    });
});
</script>
@endsection
