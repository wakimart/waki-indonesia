<?php
    $menu_item_page = "technician";
    $menu_item_second = "list_task";
?>

@extends('admin.layouts.template')

@section('style')
<style type="text/css">
	.nav {
	  display: -webkit-box;
	  display: -ms-flexbox;
	  display: flex;
	  -ms-flex-wrap: wrap;
	  flex-wrap: wrap;
	  padding-left: 0;
	  margin-bottom: 0;
	  list-style: none; }

	.nav-tabs .nav-link {
  background-color: #ececec;
  color :#5c5c5c;
}

.nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
  font-style: normal;
  text-decoration: underline;
  font-weight: 500;
}

.nav-tabs{
  max-width: 660px;
  width: 90%;
  margin: 0 auto;
  padding: 0px 20px;
}
</style>
@endsection

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

		<div class="form-group">
			<span>SELECT PRODUCT</span>
            <ul class="nav nav-tabs" style="width: 100%;">
                <li class="nav-pt active"><a data-toggle="tab" href="#WAKimart">WAKimart</a></li>
                <li class="nav-pt"><a data-toggle="tab" href="#Serba50000">Serba 50000</a></li>
                <li class="nav-pt"><a data-toggle="tab" href="#Serba100000">Serba 100000</a></li>
                <li class="nav-pt"><a data-toggle="tab" href="#Promo">Promo</a></li>
            </ul>
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
					              	@if(Gate::check('edit-order'))
						              	<th colspan="2"> Edit </th>
						            @endif
				            	</tr>
							</thead>
							<tbody>
								@foreach($services as $key => $service)
									@php
										$service_date = explode(' ',$service['service_date']);
										$service_date = $service_date[0];

										$count_productservices = count($service->product_services);
									@endphp
									<tr>
										<td rowspan="{{$count_productservices}}">{{$key+1}}</td>
										<td rowspan="{{$count_productservices}}">Service</td>
										@foreach($service->product_services as $product_service)
											@php
												$issues = json_decode($product_service['issues']);
												$count_main_issue = count($issues[0]->issues);								

												$due_date = explode(' ',$product_service['due_date']);
												$due_date = $due_date[0];
											@endphp
											<td>{{$product_service->product['name']}}</td>
											<td>{{implode(",",$issues[0]->issues)}}</td>
											<td>{{$service_date}}</td>
											<td>{{$due_date}}</td>
											@php break; @endphp
										@endforeach
										<td rowspan="{{$count_productservices}}">{{$service['status']}}</td>
										@can('edit-order')
				                            <td rowspan="{{$count_productservices}}" style="text-align: center;"><a href="{{ route('edit_taskservice', ['id' => $service['id']])}}"><i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i></a></td>
			                            @endcan
									</tr>
									@php $first = true; @endphp
			                        @for($i = 0; $i < $count_productservices; $i++)
			                            @php
			                                if($first){
			                                    $first = false;
			                                    continue;
			                                }
			                                $issues_sec = json_decode($service->product_services[$i]['issues']);
			                                $due_date_sec = explode(' ',$service->product_services[$i]['due_date']);
											$due_date_sec = $due_date_sec[0];
			                            @endphp
			                            <tr>
			                                <td>{{$service->product_services[$i]->product['name']}}</td>
			                                <td>{{implode(",",$issues_sec[0]->issues)}}</td>
			                                <td>{{$service_date}}</td>
			                                <td>{{$due_date_sec}}</td>
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