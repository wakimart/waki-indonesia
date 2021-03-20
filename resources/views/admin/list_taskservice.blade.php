<?php
    $menu_item_page = "technician";
    $menu_item_second = "list_task";
?>

@extends('admin.layouts.template')

@section('content')
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
  			<h3 class="page-title">List Product Service</h3>
  			<nav aria-label="breadcrumb">
    			<ol class="breadcrumb">
      				<li class="breadcrumb-item"><a data-toggle="collapse" href="#technician-dd" aria-expanded="false" aria-controls="technician-dd">Technician</a></li>
      				<li class="breadcrumb-item active" aria-current="page">List Product Service</li>
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
					              	<th> Type </th>
					              	<th> Product </th>
					              	<th> Issues </th>
					              	<th> Service Date </th>
					              	<th> Due Date </th>
					              	<th> Status </th>
					              	@if(Gate::check('edit-order') || Gate::check('delete-order'))
						              	<th colspan="2"> Edit / Delete </th>
						            @endif
				            	</tr>
							</thead>
							<tbody>
								@foreach($product_services as $key => $product_service)
									@php
										$issues = json_decode($product_service['issues']);
										$count_main_issue = count($issues[0]->issues);

										$service_date = "";
										$due_date= "";
										if($product_service['service_id'] != null){
											$service_date = explode(' ',$product_service->service['service_date']);
											$service_date = $service_date[0];
										}										

										$due_date = explode(' ',$product_service['due_date']);
										$due_date = $due_date[0];
									@endphp
									<tr>
										<td rowspan="{{$count_main_issue}}">{{$key+1}}</td>
										@if($product_service['service_id'] != null)
											<td rowspan="{{$count_main_issue}}">Service</td>
										@elseif($product_service['upgrade_id'] != null)
											<td rowspan="{{$count_main_issue}}">Upgrade</td>
										@endif
										<td rowspan="{{$count_main_issue}}">{{$product_service->product['name']}}</td>
										@for($i = 0; $i < $count_main_issue; $i++)
											@if($issues[0]->issues[$i] != "Lainnya...")
												<td>{{$issues[0]->issues[$i]}}</td>
											@else
												<td>{{$issues[1]->desc}}</td>
											@endif
											
											@php break; @endphp
										@endfor
										<td rowspan="{{$count_main_issue}}">{{$service_date}}</td>
										<td rowspan="{{$count_main_issue}}">{{$due_date}}</td>
										<td rowspan="{{$count_main_issue}}">{{$product_service->service['status']}}</td>
										@can('edit-order')
				                            <td rowspan="{{ $count_main_issue }}" style="text-align: center;"><a href="{{ route('edit_taskservice', ['id' => $product_service['id']])}}"><i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i></a></td>
			                            @endcan
									</tr>
									@php $first = true; @endphp
			                        @for($i = 0; $i < $count_main_issue; $i++)
			                            @php
			                                if($first){
			                                    $first = false;
			                                    continue;
			                                }
			                            @endphp
			                            <tr>
			                                @if($issues[0]->issues[$i] != "Lainnya...")
												<td>{{$issues[0]->issues[$i]}}</td>
											@else
												<td>{{$issues[1]->desc}}</td>
											@endif
			                            </tr>
			                        @endfor
								@endforeach
							</tbody>
						</table>
						</br>
						
	    			</div>
	  			</div>
			 </div>
		</div>

	</div>
</div>
@endsection