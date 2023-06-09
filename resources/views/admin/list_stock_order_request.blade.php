<?php
$menu_item_page = "stock";
$menu_item_second = "list_stock_order_request";
?>
@extends('admin.layouts.template')

@section('content')
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
  			<h3 class="page-title">List Stock Order Request</h3>
  			<nav aria-label="breadcrumb">
    			<ol class="breadcrumb">
      				<li class="breadcrumb-item"><a data-toggle="collapse" href="#stock_order_request-dd" aria-expanded="false" aria-controls="stock_order_request-dd">Stock</a></li>
      				<li class="breadcrumb-item active" aria-current="page">List Stock Order Request</li>
    			</ol>
  			</nav>
		</div>

		<div class="row">
			<div class="col-12 grid-margin stretch-card">
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
						<label for="">Search By Code, Temp No</label>
                        <input class="form-control" id="search" name="search" placeholder="Search By Code, Temp No" value="{{ request()->input('search') }}">
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
  			<div class="col-12 grid-margin stretch-card">
    			<div class="card">
      				<div class="card-body">
      					<h5 style="margin-bottom: 0.5em;">Total : {{ $stockOrderRequests->total() }} data</h5>
        				<div class="table-responsive" style="border: 1px solid #ebedf2;">
        					<table class="table table-bordered">
          						<thead>
						            <tr>
						              	<th> Order Date </th>
						              	<th> Order Code </th>
						              	<th> Order Temp No </th>
						              	<th> Branch </th>
                                        <th> Status </th>
						              	<th colspan="2"> Process Stock </th>
						            </tr>
          						</thead>
          						<tbody>
          							@foreach($stockOrderRequests as $sor)
				                        <tr>
				                            <td>{{ date("d/m/Y", strtotime($sor->order['orderDate'])) }}</td>
				                            <td><a href="{{ route('detail_order') }}?code={{ $sor->order['code'] }}">{{$sor->order['code']}}</a></td>
                                            <td>{{$sor->order['temp_no']}}</td>
											<td>{{$sor->order->branch['code']}} - {{$sor->order->branch['name']}}</td>
											@if($sor['status'] == \App\StockOrderRequest::$status['1']) {{-- Pending --}}
                                            <td>
                                                <span class="badge badge-warning">{{ucwords($sor['status'])}}</span>
                                            </td>
				                            <td style="text-align: center;">
                                                <a href="{{ route('add_stock_out') }}?sor={{ $sor['id'] }}">
                                                    <i class="mdi mdi-eye" style="font-size: 24px; color:#33b5e5;"></i>
                                                </a>
                                            </td>
											@elseif($sor['status'] == \App\StockOrderRequest::$status['2']) {{-- Approved --}}
											<td>
												<span class="badge badge-success">{{ucwords($sor['status'])}}</span>
											</td>
				                            <td style="text-align: center;">
												<a href="{{ route('detail_stock_in_out', ['code' => $sor->stockInOut['code']]) }}">
                                                    <i class="mdi mdi-eye" style="font-size: 24px; color:#33b5e5;"></i>
												</a>
											</td>
											@endif
				                        </tr>
				                    @endforeach
          						</tbody>
							</table>
							<br />
							{{ $stockOrderRequests->appends(request()->input())->links()}}
        				</div>
      				</div>
    			</div>
  			</div>
		</div>
	</div>
<!-- partial -->
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

	  window.location.href = "{{route('list_stock_order_request')}}" + urlParamStr;
	});
</script>
@endsection
