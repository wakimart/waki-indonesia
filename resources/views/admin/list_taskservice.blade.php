<?php
    $menu_item_page = "technician";
    $menu_item_second = "list_task";
?>

@extends('admin.layouts.template')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/chart/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/metisMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/image-uploader.css') }}">
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
@endsection

@section('style')
<style type="text/css">
	.nav > li > a {
      position: relative;
      display: block;
      padding: 15px 20px;
    }
    .nav-tabs {
      border-bottom: 1px solid #ddd;
      background: #f2f3f2;
      border: 0;
      margin: 0 auto;
      padding: 0px 20px;
    }
    .nav-tabs > li {
      float: left;
      margin-bottom: -1px;
    }
    .nav-tabs > li > a {
      margin-right: 2px;
      line-height: 1.42857143;
      border: 1px solid transparent;
      border-radius: 4px 4px 0 0;
    }
    .nav-tabs > li > a:hover {
      border-color: #eee #eee #ddd;
    }
    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:hover,
    .nav-tabs > li.active > a:focus {
      color: #555;
      cursor: default;
      background-color: #fff;
      border-bottom-color: transparent;
      border: 0;
      padding: 15px 20px;
    }
    .nav-tabs.nav-justified {
      width: 100%;
      border-bottom: 0;
    }
    .nav-tabs.nav-justified > li {
      float: none;
    }
    .nav-tabs.nav-justified > li > a {
      margin-bottom: 5px;
      text-align: center;
    }
    .nav-tabs.nav-justified > .dropdown .dropdown-menu {
      top: auto;
      left: auto;
    }
    .nav-tabs li a:hover {
      background: #fff;
    }
    .nav-tabs li.active a {
      color: #30a5ff;
    }
    .nav-tabs li a {
      color: #999;
    }

	/*-- mobile --*/
	@media (max-width: 768px){
		#desktop{
			display: none;
		}

		#mobile{
			display: block;
		}

		#page-title-desk{
			display: none;
		}

		#mobile .filter{
			padding-top: 15px;
		}

		.page-header h5{
			font-size: 1rem;
		}
		.breadcrumb {
			padding: 0.56rem 0.7rem;
			text-align: right;
		}

		.breadcrumb-item + .breadcrumb-item {
			padding-left: 0.5rem;
		}
	}

	@media (min-width: 768px){
		#desktop{
			display: block;
		}

		#page-title-mob{
			display: none;
		}

		#mobile{
			display: none;
		}
	}

	@media (min-width: 410px){
		#desktop{
			display: none;
		}

		#mobile{
			display: block;
		}

		#mobile .filter{
			padding-top: 0;
		}
	}
</style>
@endsection

@section('content')
<style>
.dataTables_scrollHeadInner, .dtDynamicVerticalScrollExample{
  width: 500px !important;
}
</style>
<div class="main-panel">
	<div class="content-wrapper">
		<!-- page header desktop -->
		<div id="page-title-desk">
			<div class="page-header">
				<h3 class="page-title">List Product Service</h3>
				<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
						<li class="breadcrumb-item"><a data-toggle="collapse" href="#technician-dd" aria-expanded="false" aria-controls="technician-dd">Technician</a></li>
						<li class="breadcrumb-item active" aria-current="page">List Product Service</li>
				</ol>
				</nav>
			</div>
		</div>

		<!-- page header & filter mobile -->
		<div id="mobile">
			<div id="page-title-mob">
				<h3 class="text-center">List Product Service</h3>
				<div class="row">
					<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
							<li class="breadcrumb-item"><a data-toggle="collapse" href="#technician-dd" aria-expanded="false" aria-controls="technician-dd">Technician</a></li>
							<li class="breadcrumb-item active" aria-current="page">List Product Service</li>
					</ol>
					</nav>
				</div>
			</div>
			<div class="row">
				<div class="col-12 grid-margin stretch-card">
					<div class="col-xs-12">
						<div class="form-group">
							<label for="">Search By Name, Code, and Phone</label>
							<input class="form-control" id="search" name="search" placeholder="Search By Name and Code">
							<div class="validation"></div>
						</div>
					</div>
					<div class="col-xs-12 filter">
						<label for=""></label>
							<div class="form-group">
								<button id="btn-filter" type="button" class="btn btn-gradient-primary m-1" style="font-size: 0.8em; padding: 1.1em;" name="filter" value="-"><span class="mdi mdi-filter"></span> Apply Filter</button>
							</div>
					</div>
				</div>
			</div>
		</div>

		<!-- filter desktop -->
		<div id="desktop">
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
		</div>

		<div class="form-group">
            <ul class="nav nav-tabs" style="width: 100%;">
                <li class="nav-pt active"><a data-toggle="tab" href="#tab_services">Services</a></li>
                <li class="nav-pt"><a data-toggle="tab" href="#tab_upgrades">Upgrades</a></li>
            </ul>

            <div class="tab-content" name="list_tab">
            	<div id="tab_services" class="tab-pane fade in active show" style="overflow-x:auto;">
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

														@if($product_service['product_id'] != null)
															<td>{{$product_service->product['name']}}</td>
														@elseif($product_service['product_id'] == null)
															<td>{{$product_service['other_product']}}</td>
														@endif

														<td>{{implode(",",$issues[0]->issues)}}</td>
														<td>{{$service_date}}</td>
														<td>{{$due_date}}</td>
														@php break; @endphp
													@endforeach
													<td rowspan="{{$count_productservices}}">{{$service['status']}}</td>
													@can('edit-service')
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
						                            	@if($service->product_services[$i]['product_id'] != null)
						                            		<td>{{$service->product_services[$i]->product['name']}}</td>
						                            	@elseif($service->product_services[$i]['product_id'] == null)
						                            		<td>{{$service->product_services[$i]['other_product']}}</td>
						                            	@endif

						                                <td>{{implode(",",$issues_sec[0]->issues)}}</td>
						                                <td>{{$service_date}}</td>
						                                <td>{{$due_date_sec}}</td>
						                            </tr>
						                        @endfor
											@endforeach
										</tbody>
									</table>
									<br>
				    			</div>
				  			</div>
						 </div>
					</div>
            	</div>

            	<div id="tab_upgrades" class="tab-pane fade" style="overflow-x:auto;">
            		<div class="col-12 grid-margin stretch-card" style="padding: 0;">
						<div class="card">
				  			<div class="card-body">
								<h5 style="margin-bottom: 0.5em;">Total : {{$countUpgrades}} data</h5>
				    			<div class="table-responsive" style="border: 1px solid #ebedf2;">
				    				<table class="table table-bordered">
							     		<thead>
							            	<tr>
								              	<th> No. </th>
								              	<th> Type </th>
								              	<th> Product </th>
								              	<th> Task </th>
								              	<th> Upgrade Date </th>
								              	<th> Due Date </th>
								              	<th> Status </th>
								              	@if(Gate::check('edit-order'))
									              	<th colspan="2"> Edit </th>
									            @endif
							            	</tr>
										</thead>
										<tbody>
											@foreach($upgrades as $key => $upgrade)
												@php
													$upgrade_date = explode(' ',$upgrade['created_at']);
													$upgrade_date = $upgrade_date[0];

													$due_date = explode(' ',$upgrade['due_date']);
													$due_date = $due_date[0];
												@endphp
												<tr>
													<td>{{$key+1}}</td>
													<td>Upgrade</td>

													@if($upgrade->acceptance['oldproduct_id'] != null)
														<td>{{$upgrade->acceptance->oldproduct['name']}}</td>
													@elseif($upgrade->acceptance['oldproduct_id'] == null)
														<td>{{$upgrade->acceptance['other_product']}}</td>
													@endif

													<td>{{$upgrade['task']}}</td>
													<td>{{$upgrade_date}}</td>
													<td>{{$due_date}}</td>
													<td>{{$upgrade['status']}}</td>
													@can('edit-service')
							                            <td style="text-align: center;"><a href="{{ route('edit_taskupgrade', ['id' => $upgrade['id']])}}"><i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i></a></td>
						                            @endcan
												</tr>
											@endforeach
										</tbody>
									</table>
									<br>
				    			</div>
				  			</div>
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
	$(document).ready(function(){
		$('.nav-tabs').on('click', 'li', function() {
	        $('.nav-tabs li.active').removeClass('active');
	        $(this).addClass('active');
	        // $('.dataTables_scrollHeadInner').css({"width": "100%"});
	        // $('.dtDynamicVerticalScrollExample').css({"width": "100%"});
	        console.log("dhuaaarr");
	    });
	});

</script>
@endsection
