<?php
    $menu_item_page = "service";
    $menu_item_second = "list_service";
?>

@extends('admin.layouts.template')

@section('content')
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
  			<h3 class="page-title">List Service</h3>
  			<nav aria-label="breadcrumb">
    			<ol class="breadcrumb">
      				<li class="breadcrumb-item"><a data-toggle="collapse" href="#service-dd" aria-expanded="false" aria-controls="service-dd">Service</a></li>
      				<li class="breadcrumb-item active" aria-current="page">List Service</li>
    			</ol>
  			</nav>
		</div>
		
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
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
			</div>
		</div>

		<div class="col-12 grid-margin stretch-card" style="padding: 0;">
			<div class="card">
	  			<div class="card-body">
					<h5 style="margin-bottom: 0.5em;">Total : {{$countServices}} data</h5>
	    			<div class="table-responsive" style="border: 1px solid #ebedf2;">
	    				<table class="table table-bordered">
				     		<thead>
				            	<tr>
					              	<th> No. </th>
					              	<th> Name </th>
					              	<th> Address </th>
					              	<th> Phone </th>
					              	<th> Service Date </th>
					              	<th> Status </th>
					              	@if(Gate::check('edit-order') || Gate::check('delete-order'))
						              	<th colspan="3"> Detail/Edit/Delete </th>
						            @endif
				            	</tr>
							</thead>
							<tbody>
								@foreach($services as $key => $service)
								<tr>
									<td>{{$key+1}}</td>
									<td>{{$service['name']}}</td>
									<td>{{$service['address']}}</td>
									<td>{{$service['phone']}}</td>
									<td>{{$service['service_date']}}</td>
									<td>{{$service['status']}}</td>

									@can('detail-service')
									<td style="text-align: center;">
                                        <a href="{{ route('detail_service' ,['id' => $service['id']]) }}">
                                            <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(76 172 245);"></i>
                                        </a>
                                    </td>
									@endcan
									@can('edit-service')
		                            <td style="text-align: center;">
		                            	<a href="{{route('edit_service', ['id' => $service['id']])}}">
		                            		<i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
		                            	</a>
		                            </td>
		                            @endcan
		                            @can('delete-service')
                      				<td style="text-align: center;">
                      					<button value="" data-toggle="modal" data-target="#deleteDoModal" class="btn-delete" >
                      						<i class="mdi mdi-delete" style="font-size: 24px; color:#fe7c96;"></i>
                      					</button>
                      				</td>
                      				@endcan
								</tr>
								@endforeach
							</tbody>
						</table>
						</br>
						{{ $services->appends($url)->links() }}
	    			</div>
	  			</div>
			 </div>
		</div>

	</div>
</div>
@endsection