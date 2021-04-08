<?php
    
?>
@extends('layouts.template')

@section('content')
<style type="text/css">
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
						            <th> Detail </th>
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
						{{ $services->appends($url)->links() }}
	    			</div>
	  			</div>
			 </div>
		</div>
    </div>

</section>

<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script>

</script>
@endsection
