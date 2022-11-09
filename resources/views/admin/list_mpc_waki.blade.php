<?php
    $menu_item_page = "mpc_waki";
?>
@extends('admin.layouts.template')

@section('content')
<div class="main-panel">
	<div class="content-wrapper">
		@if (session('success-api-waki'))
			<div class="alert alert-success div-session">
				{{ session('success-api-waki') }}
			</div>
		@endif
		@if (session('error-api-waki'))
			<div class="alert alert-danger div-session">
				{{ session('error-api-waki') }}
			</div>
		@endif
		<div class="page-header">
  			<h3 class="page-title">List MPC Waki</h3>
  			<nav aria-label="breadcrumb">
    			<ol class="breadcrumb">
      				<li class="breadcrumb-item"><a href="">List MPC Waki</a></li>
    			</ol>
  			</nav>
		</div>

		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
					<div class="form-group">
						<input class="form-control" id="search" name="search" placeholder="Search By Name, Phone Or Code" value="{{isset($_GET['search']) ? $_GET['search'] : ''}}">
						<div class="validation"></div>
					</div>
				</div>
		
				<div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
					<div class="col-xs-6 col-sm-6" style="padding: 0;display: inline-block;">
						<div class="form-group">
							<button id="btn-filter" type="button" class="btn btn-gradient-primary m-1" name="filter" value="-"><span class="mdi mdi-filter"></span> Apply Filter</button>
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
						<h5 style="margin-bottom: 0.5em;">Total : {{ count($MPCWakiData) }} data</h5>
        				<div class="table-responsive" style="border: 1px solid #ebedf2;">
        					<table class="table table-bordered">
          						<thead>
						            <tr>
						              	<th> No. </th>
						              	<th> MPC Code </th>
										<th> Name </th>
										<th> Phone </th>
						            </tr>
          						</thead>
          						<tbody>
									@if(count($MPCWakiData) > 0)
										@foreach($MPCWakiData as $key => $mpcdata)
											<tr>
												<td>{{$key+1}}</td>
												<td>{{$mpcdata['mpc_code']}}</td>
												<td>{{$mpcdata['name']}}</td>
												<td>{{$mpcdata['phone']}}</td>
											</tr>
										@endforeach
									@else
										<tr>
											<td colspan=4 class="text-center">data not available</td>
										</tr>
									@endif
          						</tbody>
							</table>
							<br/>
							@if(count($MPCWakiData) > 0)
								@if($MPCWakiData->hasPages())
									{{ $MPCWakiData->appends(request()->input())->links() }}
								@else
									<ul class="pagination" role="navigation">						
										<li class="page-item disabled" aria-disabled="true" aria-label="« Previous">
											<span class="page-link" aria-hidden="true">‹</span>
										</li>
										<li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
										<li class="page-item disabled" aria-disabled="true" aria-label="Next »">
											<span class="page-link" aria-hidden="true">›</span>
										</li>
									</ul>
								@endif
							@endif
        				</div>
      				</div>
    			</div>
  			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	$(document).on("click", "#btn-filter", function(e){
	  var urlParamArray = new Array();
	  var urlParamStr = "";
	  if($('#search').val() != ""){
		urlParamArray.push("search=" + $('#search').val());
	  }
	  for (var i = 0; i < urlParamArray.length; i++) {
		if (i === 0) {
		  urlParamStr += "?" + urlParamArray[i]
		} else {
		  urlParamStr += "&" + urlParamArray[i]
		}
	  }
	
	  window.location.href = "{{route('mpc_waki_list')}}" + urlParamStr;
	});

	$('.div-session').fadeIn().delay(5000).fadeOut('slow'); 
	
</script>
@endsection