<?php
$menu_item_page = "acceptance";
$menu_item_second = "list_acceptance_form";
?>
@extends('admin.layouts.template')
@section('style')
<style type="text/css">

	/*-- mobile --*/
	@media (max-width: 768px){

		#desktop{
			display: none;
		}

		#mobile{
			display: block;
		}
	}

	@media (min-width: 768px){
		#desktop{
			display: block;
		}

		#mobile{
			display: none;
		}
	}

</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <!-- header mobile -->
		<div id="mobile">
			<h3 class="text-center">Acceptances List</h3>
			<div class="row">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a data-toggle="collapse" href="#" aria-expanded="false" aria-controls="acceptance-dd">Acceptances</a></li>
						<li class="breadcrumb-item active" aria-current="page">Acceptances List</li>
					</ol>
				</nav>
		  	</div>
	  	</div>

		<!-- header desktop -->
        <div id="desktop">
            <div class="page-header">
                <h3 class="page-title">Acceptances List</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a data-toggle="collapse"
                                href="#"
                                aria-expanded="false"
                                aria-controls="acceptance-dd">
                                Acceptances
                            </a>
                        </li>
                        <li class="breadcrumb-item active"
                            aria-current="page">
                            Acceptances List
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-12" style="padding: 0;">
            <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
                <div class="col-xs-6 col-sm-4" style="padding: 0;display: inline-block;">
                    <div class="form-group">
                        <label for="">Filter By Status</label>
                        <select class="form-control" id="filter_status" name="filter_status">
                            <option value="">All</option>
                            <option value="new" {{ isset($_GET['status']) ? ($_GET['status'] == "new" ? "selected" : "") : ""}}>New</option>
                            <option value="approved" {{ isset($_GET['status']) ? ($_GET['status'] == "approved" ? "selected" : "") : ""}}>Approved</option>
                            <option value="rejected" {{ isset($_GET['status']) ? ($_GET['status'] == "rejected" ? "selected" : "") : ""}}>Rejected</option>
                        </select>
                        <div class="validation"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
            <div class="col-xs-6 col-sm-6" style="padding: 0;display: inline-block;">
                <label for=""></label>
                <div class="form-group">
                    <button id="btn-filter" type="button" class="btn btn-gradient-primary m-1" name="filter" value="-"><span class="mdi mdi-filter"></span> Apply Filter</button>

                    @if (Auth::user()->roles[0]->slug !== "branch" && Auth::user()->roles[0]->slug !== "cso")
                        <button id="btn-export" type="button" class="btn btn-gradient-info m-1" name="export" data-toggle="modal" href="#modal-export-excel" value="-"><span class="mdi mdi-file-document"></span>Export XLS</button>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 grid-margin stretch-card" style="padding: 0;">
            <div class="card">
                <div class="card-body">
                    <h5 style="margin-bottom: 0.5em;">
                        Total: {{ $acceptances->count() }} data
                    </h5>
                    <div class="table-responsive"
                        style="border: 1px solid #ebedf2;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Acceptance Date</th>
                                    <th>Member Name</th>
                                    <th>Acc Type</th>
                                    <th>Acceptance Product</th>
                                    <th>Branch - CSO</th>
                                    <th>Status</th>
                                    {{-- @if(Gate::check('edit-deliveryorder') || Gate::check('delete-deliveryorder')) --}}
                                        <th colspan="3">Detail/Edit/Delete</th>
                                    {{-- @endif --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($acceptances as $key => $acceptance)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            {{ date("d/m/Y", strtotime($acceptance->upgrade_date)) }}
                                        </td>
                                        <td>
                                            {{ $acceptance->name }}
                                        </td>
                                        <td>
                                            Upgrade
                                        </td>
                                        <td>
                                            {{ $acceptance['other_product'] == null ? $acceptance->oldproduct['code'] : $acceptance['other_product'] }} <i class="mdi mdi-arrow-right-bold" style="font-size: 18px; color: #fed713;"></i> {{ $acceptance->newproduct['code'] }}
                                        </td>
                                        <td>
                                            {{ $acceptance->branch->code }} - {{ $acceptance->cso->code }}
                                        </td>
                                        <td>
                                            @if(strtolower($acceptance['status']) == "new")
                                                <span class="badge badge-primary">New</span>
                                            @elseif(strtolower($acceptance['status']) == "approved")
                                                <span class="badge badge-success">Approved by : {{ $acceptance->acceptanceLog[sizeof($acceptance->acceptanceLog)-1]->user['name'] }}</span>
                                            @elseif(strtolower($acceptance['status']) == "rejected")
                                                <span class="badge badge-danger">Rejected by : {{ $acceptance->acceptanceLog[sizeof($acceptance->acceptanceLog)-1]->user['name'] }}</span>
                                            @endif
                                        </td>
                                        @can('detail-acceptance')
                                            <td style="text-align: center;">
                                                <a href="{{ route('detail_acceptance_form' ,['id' => $acceptance['id']]) }}">
                                                    <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(76 172 245);"></i>
                                                </a>
                                            </td>
                                        @endcan
                                        @can('edit-acceptance')
                                            <td style="text-align: center;">
                                                @if(strtolower($acceptance['status']) == "new")
                                                    <a href="{{ route('edit_acceptance_form' ,['id' => $acceptance['id']]) }}">
                                                        <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        @endcan
                                        @can('delete-acceptance')
                                            <td style="text-align: center;">
                                                @if(strtolower($acceptance['status']) == "new")
                                                    <a class="btn-delete disabled" data-toggle="modal" href="#deleteDoModal" value="{{ route('delete_acceptance_form', ['id' => $acceptance->id]) }}">
                                                        <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{ $acceptances->appends($url)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- partial -->

<!-- Modal Export XLS -->
<div class="modal fade"
    id="modal-export-excel"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <label for="">Pick a Date</label>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Filter Acceptance</label>
                    <select class="form-control" id="filter_statusexport" required="">
                        <option value="">All</option>
                        <option value="new">New</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal Awal</label>
                    <input type="date"
                        class="form-control"
                        name="date"
                        id="filter_startDate"
                        placeholder="Awal Tanggal"
                        required
                        data-msg="Mohon Isi Tanggal"
                        onload="getDate()" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <label>Tanggal Akhir</label>
                    <input type="date"
                        class="form-control"
                        name="date"
                        id="filter_endDate"
                        placeholder="Akhir Tanggal"
                        required
                        data-msg="Mohon Isi Tanggal"
                        onload="getDate()" />
                    <div class="validation"></div>
                </div>
            </div>
            <div class="modal-footer">
                {{ csrf_field() }}
                <input type="hidden"
                    id="hiddenInput"
                    name="cancel"
                    value="1" />
                <button type="submit"
                    data-dismiss="modal"
                    id="btn-exportByDate"
                    class="btn btn-gradient-danger mr-2"
                    name="id"
                    value="-"
                    onclick="submitExportXLSWithDate();">
                    Export
                </button>
                <button type="button"
                    data-dismiss="modal"
                    class="btn btn-light">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Export XLS -->

<!-- Modal Delete -->
@can('delete-acceptance')
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
                    <h5 style="text-align:center;">
                        Are you sure you want to delete this?
                    </h5>
                </div>
                <div class="modal-footer">
                    <form id="frmDelete" method="post" action="">
                        {{csrf_field()}}
                        <button type="submit" class="btn btn-gradient-danger mr-2">
                            Yes
                        </button>
                    </form>
                    <button class="btn btn-light">No</button>
                </div>
            </div>
        </div>
    </div>
@endcan
<!-- End Modal Delete -->
@endsection

@section('script')
<script>
$(document).ready(function (e) {
    $("#btn-filter").click(function (e) {
        var urlParamArray = new Array();
        var urlParamStr = "";
        if($('#filter_status').val() != ""){
            urlParamArray.push("status=" + $('#filter_status').val());
        }
        for (var i = 0; i < urlParamArray.length; i++) {
            if (i === 0) {
                urlParamStr += "?" + urlParamArray[i]
            } else {
                urlParamStr += "&" + urlParamArray[i]
            }
        }
        window.location.href = "{{route('list_acceptance_form')}}" + urlParamStr;
    });
    $(".btn-delete").click(function (e) {
        $("#frmDelete").attr("action",  $(this).attr('value'));
    });
});

function submitExportXLSWithDate() {
    console.log("masuk");
    let urlParamStr = "";

    let startDate = document.getElementById("filter_startDate").value;
    let endDate = document.getElementById("filter_endDate").value;
    let status = document.getElementById("filter_statusexport").value;

    urlParamStr += "filter_startDate=" + startDate + "&";
    urlParamStr += "filter_endDate=" + endDate + "&";
    urlParamStr += "filter_statusexport=" + status;


    window.location.href = "<?php echo route('acceptance_export-to-xls-by-date'); ?>" + "?" + urlParamStr;
}
</script>
@endsection
