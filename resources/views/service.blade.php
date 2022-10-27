<?php
    
?>
@extends('layouts.template')

@section('content')
<style type="text/css">
	.nav > li > a {
      position: relative;
      display: block;
      padding: 15px 20px;
    }
    .nav-tabs {
      border-bottom: 1px solid #ddd;
      border: 0;
      margin: 0 auto;
      padding: 0px 20px;
    }
    .nav-tabs > li {
      float: left;
      background: #f2f3f2;
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
    .nav-tabs li a:hover {background: #fff;}
    .nav-tabs li.active a {color: #30a5ff;}
    .nav-tabs li a {color: #999;}

    #intro {
        padding-top: 2em;
    }
    button{
        background: #1bb1dc;
        border: 0;
        border-radius: 3px;
        padding: 8px 30px;
        color: #fff;
        transition: 0.3s;
    }
    .validation{
        color: red;
        font-size: 9pt;
    }
    input, select, textarea{
        border-radius: 0 !important;
        box-shadow: none !important;
        border: 1px solid #dce1ec !important;
        font-size: 14px !important;
    }
</style>

<section id="intro" class="clearfix">
    <div class="container">
        {{-- <div class="row justify-content-center">
            <h2>{{ $services['code'] }}</h2>
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
		</div> --}}

		<div class="form-group">
            <ul class="nav nav-tabs" style="width: 100%;">
				@php $tabActive = request()->query('tabActive') ?? App\Service::$Area['1'] @endphp
				@foreach ($serviceAreas as $keyArea => $services)
                <li class="nav-pt 
					@if ($tabActive == $keyArea) active @endif">
					<a data-toggle="tab" href="#tab_{{ $keyArea }}">
						{{ ucwords($keyArea) }} ({{ $services->total() }})
					</a>
				</li>
				@endforeach
            </ul>

			<div class="tab-content" name="list_tab">
				@foreach ($serviceAreas as $keyArea => $services)
            	<div id="tab_{{ $keyArea }}" class="tab-pane fade in @if ($tabActive == $keyArea) active show @endif" style="overflow-x:auto;">
					<div class="col-12 grid-margin stretch-card" style="padding: 0;">
						<div class="card">
							<div class="card-body">
								<h5 style="margin-bottom: 0.5em;">Total : {{$services->total()}} data</h5>
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
												<th> Detail </th>
											</tr>
										</thead>
										<tbody>
											@foreach($services as $key => $service)
											<tr>
												<td>{{$key+ $services->firstItem()}}</td>
												<td>{{$service['name']}}</td>
												<td>{{$service['address']}}</td>
												<td>{{$service['phone']}}</td>
												<td>{{$service['service_date']}}</td>
												<td>{{$service['status']}}</td>

												@can('detail-service')
												<td style="text-align: center;">
													<a href="{{ route('track_service' ,['id' => $service['id']]) }}">
														Detail Service
													</a>
												</td>
												@endcan
											</tr>
											@endforeach
										</tbody>
									</table>
									</br>
									{{ $services->appends($url)->appends(['tabActive' => $keyArea])->links() }}
								</div>
							</div>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
    </div>

</section>

<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script>
	$('.nav-tabs').on('click', 'li', function() {
		$('.nav-tabs li.active').removeClass('active');
		$(this).addClass('active');
	});
</script>
@endsection
