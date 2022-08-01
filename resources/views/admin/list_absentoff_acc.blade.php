<?php
    $menu_item_page = "absent_off";
    $menu_item_second = "list_acc_absent_off";
?>
@extends('admin.layouts.template')


@section('content')

<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
  			<h3 class="page-title">List Acc Cuti</h3>
  			<nav aria-label="breadcrumb">
    			<ol class="breadcrumb">
      				<li class="breadcrumb-item"><a data-toggle="collapse" href="#absent_off-dd" aria-expanded="false" aria-controls="absent_off-dd">Cuti</a></li>
      				<li class="breadcrumb-item active" aria-current="page">List Acc Cuti</li>
    			</ol>
  			</nav>
		</div>

		<div class="row">
			<div class="col-12 grid-margin">
                <div class="col-xs-12 col-sm-12 row"
                    style="margin: 0;padding: 0;">
                    <div class="col-xs-6 col-sm-4" style="margin-bottom: 0; padding: 0; display: inline-block">
                        <div class="form-group">
                            <label for="">Start Date</label>
                            <input type="date"
                                class="form-control"
                                id="filter_start_date"
                                name="filter_start_date"
                                value="{{ isset($_GET['filter_start_date']) ? $_GET['filter_start_date'] : '' }}">
                            <div class="validation"></div>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-4" style="margin-bottom: 0; padding: 0; display: inline-block">
                        <div class="form-group">
                            <label for="">End Date</label>
                            <input type="date"
                                class="form-control"
                                id="filter_end_date"
                                name="filter_end_date"
                                value="{{ isset($_GET['filter_end_date']) ? $_GET['filter_end_date'] : '' }}">
                            <div class="validation"></div>
                        </div>
                    </div>
                </div>

				@if(Auth::user()->roles[0]['slug'] != 'branch' && Auth::user()->roles[0]['slug'] != 'cso')
                    <div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
                      <div class="form-group">
                        <label for="">Filter By Team</label>
                        <select class="form-control"
                            id="filter_branch"
                            name="filter_branch">
                            <option value="" selected="">
                                All Branch
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
                        <div class="validation"></div>
                      </div>
                    </div>
                    <div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
                        <div class="form-group">
                            <label for="filter_cso">
                                Filter By CSO
                            </label>
                            <input name="filter_cso"
                                id="filter_cso"
                                list="data_cso"
                                class="text-uppercase form-control"
                                placeholder="Search CSO"
                                @isset($currentCso)
                                    value="{{ $currentCso['code'] . "-" . $currentCso['name'] }}"
                                @endisset
                                required />
                            <span class="invalid-feedback">
                                <strong></strong>
                            </span>
        
                            <datalist id="data_cso">
                                <select class="form-control">
                                    <option value="All CSO"></option>
                                    @foreach ($csos as $cso)
                                        <option value="{{ $cso['code'] }}-{{ $cso['name'] }}"></option>
                                    @endforeach
                                </select>
                            </datalist>
                            <div class="validation"></div>
                        </div>
					</div>
				@endif

                <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
					<div class="col-xs-6 col-sm-6" style="padding: 0;display: inline-block;">
						<label for=""></label>
						<div class="form-group">
                            <button id="btn-filter" type="button" class="btn btn-gradient-primary m-1" name="filter" value="-"><span class="mdi mdi-filter"></span> Apply Filter</button>
                            @if(Auth::user()->roles[0]['slug'] != 'branch' && Auth::user()->roles[0]['slug'] != 'cso' && Auth::user()->roles[0]['slug'] != 'area-manager')
                            <a href="{{ route('list_acc_absent_off') }}"
                                class="btn btn-gradient-danger m-1"
                                value="-">
                                <span class="mdi mdi-filter"></span> Reset Filter
                            </a>
                            @endif
                        </div>
					</div>
                </div>

				<div class="col-sm-12 col-md-12" style="padding: 0; border: 1px solid #ebedf2;">
					<div class="col-xs-12 col-sm-11 col-md-6 table-responsive" id="calendarContainer" style="padding: 0; float: left;"></div>
					<div class="col-xs-12 col-sm-11 col-md-6" id="organizerContainer" style="padding: 0; float: left;"></div>
				</div>

			</div>
  			<div class="col-12 grid-margin stretch-card">
    			<div class="card">
      				<div class="card-body">
                        @if ($startDate && $endDate)
                        <h5>{{ date('d F y', strtotime($startDate)) }} - {{ date('d F y', strtotime($endDate)) }}</h5>
                        @else
                        <h5>All Data</h5>
                        @endif
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            @foreach ($statusAbsentOffs as $statusKey => $absentOffs)
                            <li class="nav-item">
                                <a class="nav-link 
                                    @if ($statusKey == App\AbsentOff::$status['1']) active @endif" 
                                    id="{{ $statusKey }}-tab" 
                                    data-toggle="tab" 
                                    href="#{{ $statusKey }}" 
                                    role="tab" 
                                    aria-controls="{{ $statusKey }}" 
                                    aria-selected="true">
                                    {{ ucfirst($statusKey) }} ({{ $absentOffs->total() }})
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            @foreach ($statusAbsentOffs as $statusKey => $absentOffs)
                            <div class="tab-pane fade p-3
                                @if ($statusKey == App\AbsentOff::$status['1']) show active @endif" 
                                id="{{ $statusKey }}" role="tabpanel" 
                                aria-labelledby="{{ $statusKey }}-tab">

                                <h5 class="mb-3">
                                    Cuti Data | Status {{ ucfirst($statusKey) }} Acc (Total: {{ $absentOffs->total() }})
                                </h5>
                                {{-- Display Table --}}
                                <div class="table-responsive" style="border: 1px solid #ebedf2;">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Name </th>
                                                <th> Duration </th>
                                                <th> Branch </th>
                                                <th> Created By </th>
                                                <th colspan="3" class="text-center"> Action </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($absentOffs as $absentOff)
                                            <tr>
                                                <td>{{ $loop->iteration + $absentOffs->firstItem() - 1 }}</td>
                                                <td>{{ $absentOff->cso->name }}
                                                    <br>{{ $absentOff->cso->code }}
                                                </td>
                                                <td>
                                                    Duration : {{ $absentOff->duration_off }} days
                                                    <br> {{ date('d F Y', strtotime($absentOff->start_date)) }} - {{ date('d F Y', strtotime($absentOff->end_date)) }}
                                                </td>
                                                <td>{{ $absentOff->branch->code }} - {{ $absentOff->branch->name }}</td>
                                                <td>
                                                    {{ $absentOff->user->name }}
                                                    <br>{{ $absentOff->created_at }}
                                                </td>
                                                @if (Gate::check('detail-absent_off'))
                                                <td class="text-center">
                                                    <a href="{{ route('detail_absent_off', ['id' => $absentOff['id']])}}"><i class="mdi mdi-eye" style="font-size: 24px; color: rgb(76, 172, 245);"></i></a>
                                                </td>
                                                @endif
                                                @if ($statusKey == App\AbsentOff::$status['1'] && $absentOff->supervisor_id == null && $absentOff->coordinator_id == null)
                                                    @if (Gate::check('edit-absent_off'))
                                                    <td class="text-center">
                                                        <a href="{{ route('edit_absent_off', ['id' => $absentOff['id']])}}"><i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i></a>
                                                    </td>
                                                    @endif
                                                    @if (Gate::check('delete-absent_off'))
                                                    <td class="text-center">
                                                        <a href="{{ route('delete_absent_off', ['id' => $absentOff['id']])}}" data-toggle="modal" data-target="#deleteDoModal" class="btnDelete"><i class="mdi mdi-delete" style="font-size: 24px; color:#fe7c96;"></i></a>
                                                    </td>
                                                    @endif
                                                @endif
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{ $absentOffs->appends(request()->input())->links() }}
                                </div>
                            </div>
                            @endforeach
                        </div>        				
      				</div>
    			</div>
  			</div>
		</div>
	</div>
    <!-- partial -->
	<!-- Modal Delete -->
	<div class="modal fade" id="deleteDoModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          	<div class="modal-content">
            	<div class="modal-header">
              		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                		<span aria-hidden="true">&times;</span>
              		</button>
            	</div>
            	<div class="modal-body">
              		<h5 style="text-align:center;">Are You Sure to Delete this Cuti ?</h5>
            	</div>
            	<div class="modal-footer">
            		<form id="frmDelete" method="post" action="">
                    {{csrf_field()}}
                    	<button type="submit" class="btn btn-gradient-danger mr-2">Yes</button>
                	</form>
              		<button class="btn btn-light" data-dismiss="modal">No</button>
            	</div>
          	</div>
        </div>
    </div>
    <!-- End Modal Delete -->
</div>
@endsection

@section('script')

<script>
	$(document).on("click", "#btn-filter", function(e){
	  var urlParamArray = new Array();
	  var urlParamStr = "";
	  if($('#filter_branch').length && $('#filter_branch').val() != ""){
		urlParamArray.push("filter_branch=" + $('#filter_branch').val());
	  }
      var filterCSO = document.getElementById("filter_cso");
      if (filterCSO) {
        filterCSO = filterCSO.value.trim()
        if (filterCSO !== "All CSO" && filterCSO.length) {
          const getCSOCode = filterCSO.split("-");
          urlParamArray.push("filter_cso=" + getCSOCode[0]);
        }
      }
      if($('#filter_start_date').val() != ""){
		urlParamArray.push("filter_start_date=" + $('#filter_start_date').val());
	  }
      if($('#filter_end_date').val() != ""){
		urlParamArray.push("filter_end_date=" + $('#filter_end_date').val());
	  }
	  for (var i = 0; i < urlParamArray.length; i++) {
		if (i === 0) {
		  urlParamStr += "?" + urlParamArray[i]
		} else {
		  urlParamStr += "&" + urlParamArray[i]
		}
	  }

	  window.location.href = "{{route('list_acc_absent_off')}}" + urlParamStr;
	});
	$(document).on("click", ".btnDelete", function(e){
		$("#frmDelete").attr("action", $(this).attr('href'));
	});
</script>
@endsection
