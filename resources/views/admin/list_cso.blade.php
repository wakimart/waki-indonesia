<?php
    $menu_item_page = "cso";
    $menu_item_second = "list_cso";
?>
@extends('admin.layouts.template')


@section('content')

<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
  			<h3 class="page-title">List CSO</h3>
  			<nav aria-label="breadcrumb">
    			<ol class="breadcrumb">
      				<li class="breadcrumb-item"><a data-toggle="collapse" href="#cso-dd" aria-expanded="false" aria-controls="cso-dd">CSO</a></li>
      				<li class="breadcrumb-item active" aria-current="page">List CSO</li>
    			</ol>
  			</nav>
		</div>

		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				@if(Auth::user()->roles[0]['slug'] != 'branch' && Auth::user()->roles[0]['slug'] != 'cso')
                    <div class="col-xs-6 col-sm-3" style="display: inline-block;">
                      <div class="form-group">
                        <label for="">Filter By Team</label>
                          <select class="form-control" id="filter_branch" name="filter_branch">
                            <option value="" selected="">All Branch</option>
                            @foreach($branches as $branch)
                              @php
                                $selected = "";
                                if(isset($_GET['filter_branch'])){
                                  if($_GET['filter_branch'] == $branch['id']){
                                    $selected = "selected=\"\"";
                                  }
                                }
                              @endphp

                              <option {{$selected}} value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
                            @endforeach
                          </select>
                          <div class="validation"></div>
                      </div>
                    </div>
                    <div class="col-xs-6 col-sm-3" style="display: inline-block;">
                      <div class="form-group">
						<label for="">Search By Name, Code, and Phone</label>
                        <input class="form-control" id="search" name="search" placeholder="Search By Name and Code">
                          <div class="validation"></div>
                      </div>
					</div>
				@endif

				@if(Auth::user()->roles[0]['slug'] != 'branch' && Auth::user()->roles[0]['slug'] != 'cso' && Auth::user()->roles[0]['slug'] != 'area-manager')
				  <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
					<div class="col-xs-6 col-sm-6" style="padding: 0;display: inline-block;">
						<label for=""></label>
						<div class="form-group">
						<button id="btn-filter" type="button" class="btn btn-gradient-primary m-1" name="filter" value="-"><span class="mdi mdi-filter"></span> Apply Filter</button>
					  </div>
					</div>
				  </div>
				@endif

				<div class="col-sm-12 col-md-12" style="padding: 0; border: 1px solid #ebedf2;">
					<div class="col-xs-12 col-sm-11 col-md-6 table-responsive" id="calendarContainer" style="padding: 0; float: left;"></div>
					<div class="col-xs-12 col-sm-11 col-md-6" id="organizerContainer" style="padding: 0; float: left;"></div>
				</div>

			</div>
  			<div class="col-12 grid-margin stretch-card">
    			<div class="card">
      				<div class="card-body">
      					<h5 style="margin-bottom: 0.5em;">Total : {{ $countCso }} data</h5>
        				<div class="table-responsive" style="border: 1px solid #ebedf2;">
        					<table class="table table-bordered">
          						<thead>
						            <tr>
						              	<th> No. </th>
						              	<th> Code </th>
						              	<th> Name </th>
						              	<th> Branch </th>
						              	<th colspan="2"> Edit / Delete </th>
						            </tr>
          						</thead>
          						<tbody>
          							@foreach($csos as $key => $cso)
				                        <tr>
				                        	<td>{{$key+1}}</td>
				                            <td>{{$cso['code']}}</td>
				                            <td>{{$cso['name']}}</td>
											<td>{{$cso->branch['name']}}</td>
				                            <td style="text-align: center;"><a href="{{ route('edit_cso', ['id' => $cso['id']])}}"><i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i></a></td>
                          					<td style="text-align: center;"><a href="{{ route('delete_cso', ['id' => $cso['id']])}}" data-toggle="modal" data-target="#deleteDoModal" class="btnDelete"><i class="mdi mdi-delete" style="font-size: 24px; color:#fe7c96;"></i></a></td>
				                        </tr>
				                    @endforeach
          						</tbody>
							</table>
							<br />
							{{ $csos->appends($url)->Links()}}
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
              		<h5 style="text-align:center;">Are You Sure to Delete this CSO ?</h5>
            	</div>
            	<div class="modal-footer">
            		<form id="frmDelete" method="post" action="">
                    {{csrf_field()}}
                    	<button type="submit" class="btn btn-gradient-danger mr-2">Yes</button>
                	</form>
              		<button class="btn btn-light">No</button>
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
	  if($('#filter_branch').val() != ""){
		urlParamArray.push("filter_branch=" + $('#filter_branch').val());
	  }
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

	  window.location.href = "{{route('list_cso')}}" + urlParamStr;
	});
	$(document).on("click", ".btnDelete", function(e){
		$("#frmDelete").attr("action", $(this).attr('href'));
	});
</script>
@endsection
