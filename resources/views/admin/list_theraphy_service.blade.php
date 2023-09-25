<?php
    $menu_item_page = "theraphy_service";
    $menu_item_second = "list_theraphy_service";
?>
@extends('admin.layouts.template')


@section('content')

<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
  			<h3 class="page-title">List Therapy Service</h3>
  			<nav aria-label="breadcrumb">
    			<ol class="breadcrumb">
      				<li class="breadcrumb-item"><a data-toggle="collapse" href="#theraphy_service-dd" aria-expanded="false" aria-controls="theraphy_service-dd">Therapy Service</a></li>
      				<li class="breadcrumb-item active" aria-current="page">List Therapy Service</li>
    			</ol>
  			</nav>
		</div>

		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
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
				<div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
					<div class="form-group">
					<label for="">Filter By Location</label>
						<select class="form-control" id="filter_location" name="filter_location">
						<option value="" selected="">All Location</option>
						@foreach($locations as $location)
							@php
							$selected = "";
							if(isset($_GET['filter_location'])){
								if($_GET['filter_location'] == $location['id']){
									$selected = "selected=\"\"";
								}
							}
							@endphp

							<option {{$selected}} value="{{ $location['id'] }}">{{ $location['name'] }}</option>
						@endforeach
						</select>
						<div class="validation"></div>
					</div>
				</div>
				<div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
					<div class="form-group">
					<label for="">Search By Name, Code, and Phone</label>
					<input class="form-control" id="search" name="search" placeholder="Search By Name and Code">
						<div class="validation"></div>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
					<div class="col-xs-6 col-sm-6" style="padding: 0;display: inline-block;">
						<label for=""></label>
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
      					<ul class="nav nav-tabs" id="myTab" role="tablist">
                           <li class="nav-item">
	                            <a class="nav-link 
	                                @php
	                                    if(isset($_GET['current_tab'])){
	                                        if($_GET['current_tab'] == 'all'){
	                                            echo 'active';
	                                        }
	                                    }
	                                    else{
	                                    	echo 'active';
	                                    }
	                                @endphp"
	                                id="all-tab" 
	                                data-toggle="tab" 
	                                href="#all" 
	                                role="tab" 
	                                aria-controls="all" 
	                                aria-selected="true">
	                                All ({{ $countTheraphyService }})
	                            </a>
	                        </li>
	                        <li class="nav-item">
	                            <a class="nav-link 
	                                @php
	                                    if(isset($_GET['current_tab'])){
	                                        if($_GET['current_tab'] == 'req'){
	                                            echo 'active';
	                                        }
	                                    }
	                                @endphp"
	                                id="req-tab" 
	                                data-toggle="tab" 
	                                href="#req" 
	                                role="tab" 
	                                aria-controls="req" 
	                                aria-selected="true">
	                                Request ({{ $countTheraphyRequest }})
	                            </a>
	                        </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade p-3
                                @php
                                    if(isset($_GET['current_tab'])){
                                        if($_GET['current_tab'] == 'all'){
                                            echo 'show active';
                                        }
                                    }
                                    else{
                                    	echo 'show active';
                                    }
                                @endphp"
                                id="all" role="tabpanel" 
                                aria-labelledby="all-tab">

		      					<h5 style="margin-bottom: 0.5em;">Total : {{ $countTheraphyService }} data</h5>
		        				<div class="table-responsive" style="border: 1px solid #ebedf2;">
		        					<table class="table table-bordered">
		          						<thead>
								            <tr>
								              	<th> No. </th>
								              	<th> Register Date </th>
								              	<th> Code </th>
								              	<th> Name </th>
												<th> Branch </th>
												<th> Status </th>
												<th class="text-center"> Detail / Edit / Delete </th>
											</tr>
		          						</thead>
		          						<tbody>
		          							@foreach($theraphyServices as $key => $theraphyService)
						                        <tr>
						                        	<td>{{$key+1}}</td>
													<td>{{$theraphyService->registered_date}}</td>
													<td>{{$theraphyService->code}}</td>
													<td>{{$theraphyService->name}}</td>
													<td>{{$theraphyService->branch->name}}</td>
													<td>{{$theraphyService->status}}</td>
						                            <td class="text-center">
						                            	@can("detail-therapy_service")
															<a href="{{ route('detail_theraphy_service', $theraphyService->id) }}" target="_blank">
																<i class="mdi mdi-eye mr-3" style="font-size: 24px; color:#636e72;"></i>
															</a>
														@endcan
														@if($theraphyService->status != "success" || Auth::user()->roles[0]['slug'] == 'head-admin')
							                            	@can("edit-therapy_service")
																<a href="{{ route('edit_theraphy_service', $theraphyService->id) }}">
																	<i class="mdi mdi-border-color mr-3" style="font-size: 24px; color:#fed713;"></i>
																</a>
															@endcan
							                            	@can("delete-therapy_service")
																<button onclick="submitDelete(`{{ route('delete_theraphy_service', $theraphyService->id) }}`)" class="btn-delete">
																	<i class="mdi mdi-delete" style="font-size: 24px; color:#fe7c96;"></i>
																</button>
															@endcan
														@endif
													</td>
						                        </tr>
						                    @endforeach
		          						</tbody>
									</table>
									<br />
                                    @php request()->merge(['current_tab'=>'all']); @endphp
									{{ $theraphyServices->appends(request()->all())->Links()}}
		        				</div>
                            </div>

                            <div class="tab-pane fade p-3
                                @php
                                    if(isset($_GET['current_tab'])){
                                        if($_GET['current_tab'] == 'req'){
                                            echo 'show active';
                                        }
                                    }
                                @endphp"
                                id="req" role="tabpanel" 
                                aria-labelledby="req-tab">

		      					<h5 style="margin-bottom: 0.5em;">Total : {{ $countTheraphyRequest }} data</h5>
		        				<div class="table-responsive" style="border: 1px solid #ebedf2;">
		        					<table class="table table-bordered">
		          						<thead>
								            <tr>
								              	<th> No. </th>
								              	<th> Register Date </th>
								              	<th> Code </th>
								              	<th> Name </th>
												<th> Branch </th>
												<th> Status </th>
												<th class="text-center"> Detail / Edit / Delete </th>
											</tr>
		          						</thead>
		          						<tbody>
		          							@foreach($theraphyRequest as $key => $theraphyService)
						                        <tr>
						                        	<td>{{$key+1}}</td>
													<td>{{$theraphyService->registered_date}}</td>
													<td>{{$theraphyService->code}}</td>
													<td>{{$theraphyService->name}}</td>
													<td>{{$theraphyService->branch->name}}</td>
													<td>{{$theraphyService->status}}</td>
						                            <td class="text-center">
						                            	@can("detail-therapy_service")
															<a href="{{ route('detail_theraphy_service', $theraphyService->id) }}" target="_blank">
																<i class="mdi mdi-eye mr-3" style="font-size: 24px; color:#636e72;"></i>
															</a>
														@endcan
														@if($theraphyService->status != "success" || Auth::user()->roles[0]['slug'] == 'head-admin')
							                            	@can("edit-therapy_service")
																<a href="{{ route('edit_theraphy_service', $theraphyService->id) }}">
																	<i class="mdi mdi-border-color mr-3" style="font-size: 24px; color:#fed713;"></i>
																</a>
															@endcan
							                            	@can("delete-therapy_service")
																<button onclick="submitDelete(`{{ route('delete_theraphy_service', $theraphyService->id) }}`)" class="btn-delete">
																	<i class="mdi mdi-delete" style="font-size: 24px; color:#fe7c96;"></i>
																</button>
															@endcan
														@endif
													</td>
						                        </tr>
						                    @endforeach
		          						</tbody>
									</table>
									<br />
                                    @php request()->merge(['current_tab'=>'req']); @endphp
									{{ $theraphyRequest->appends(request()->all())->Links()}}
		        				</div>
                            </div>
                        </div>
      				</div>
    			</div>
  			</div>
		</div>
	</div>
<!-- partial -->
	<!-- Modal Delete -->
	<div class="modal fade" id="deleteTherapyServiceModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          	<div class="modal-content">
            	<div class="modal-header">
              		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                		<span aria-hidden="true">&times;</span>
              		</button>
            	</div>
            	<div class="modal-body">
              		<h5 style="text-align:center;">Are You Sure to Delete this Therapy Service ?</h5>
            	</div>
            	<div class="modal-footer">
            		<form name="frmDelete" method="post" action="">
                    	{{csrf_field()}}
						@method('DELETE')
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
	  if($('#filter_branch').val() != ""){
		urlParamArray.push("filter_branch=" + $('#filter_branch').val());
	  }
	  if($('#filter_location').val() != ""){
		urlParamArray.push("filter_location=" + $('#filter_location').val());
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

	  window.location.href = "{{route('list_theraphy_service')}}" + urlParamStr;
	});

	function submitDelete(url) {
		$('#deleteTherapyServiceModal').modal('show');
		document.frmDelete.action = url;
	}
</script>
@endsection
