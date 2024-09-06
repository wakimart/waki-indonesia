<?php
    $menu_item_page = "report";
    $menu_item_second = "report_provit";
?>
@extends('admin.layouts.template')


@section('content')

<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
  			<h3 class="page-title">Report Provit & Loss Detail</h3>
  			<nav aria-label="breadcrumb">
    			<ol class="breadcrumb">
      				<li class="breadcrumb-item"><a data-toggle="collapse" href="#cso-dd" aria-expanded="false" aria-controls="cso-dd">Master Report</a></li>
      				<li class="breadcrumb-item active" aria-current="page">Report Provit & Loss Detail</li>
    			</ol>
  			</nav>
		</div>

		<div class="row">
			<div class="col-12 grid-margin stretch-card">
        <div class="col-xs-6 col-sm-3" style="display: inline-block;">
          <div class="form-group">
            <label for="">Filter By Warehouse</label>
              <select class="form-control" id="filter_warehouse" name="filter_warehouse">
                <option value="" selected="">All Warehouse</option>
                @foreach($warehouses as $warehouse)
                  @php
                    $selected = "";
                    if(isset($_GET['filter_warehouse'])){
                      if($_GET['filter_warehouse'] == $warehouse['id']){
                        $selected = "selected=\"\"";
                      }
                    }
                  @endphp
                  <option {{$selected}} value="{{ $warehouse['id'] }}">{{ $warehouse['code'] }} - {{ $warehouse['name'] }}</option>
                @endforeach
              </select>
              <div class="validation"></div>
          </div>
        </div>
        <div class="col-xs-6 col-sm-3" style="display: inline-block;">
          <div class="form-group">
            <label for="">Filter by Year</label>
            <input class="form-control" type="number" name="filter_year" id="filter_year" min="2023" max="{{ date('Y') }}" step="1" value="{{ isset($_GET['filter_year']) ? $_GET['filter_year'] : date('Y') }}" />
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
      					<h5 style="margin-bottom: 0.5em;">Total : {{ $stockOutOrder->count() }} data order</h5>
        				<div class="table-responsive" style="border: 1px solid #ebedf2;">
        					<table class="table table-bordered">
          						<thead>
						            <tr>
						              	<th> No. </th>
						              	<th> Order Code </th>
						              	<th> Temp No </th>
                            <th> Order Date </th>
						              	<th> Member Name </th>
                            <th> Branch </th>
                            <th> Delivery Date </th>
                            <th> View </th>
						            </tr>
          						</thead>
          						<tbody>
                        @php
                          $totalPriceOut = 0;
                        @endphp

          							@foreach($stockOutOrder as $key => $perStock)
                          @php
                            $orderNya = $perStock->orderDetail->order;
                          @endphp
                          <tr>
                          	<td>{{$key+1}}</td>
                              <td>{{ $orderNya['code'] }}</td>
                              <td>{{ $orderNya['temp_no'] }}</td>
                              <td>{{ date("d/m/Y", strtotime($orderNya['orderDate'])) }}</td>
                              <td>{{ $orderNya['name'] }}</td>
                              <td>{{ $orderNya->branch['code'] }} - {{ $orderNya->branch['name'] }}</td>
                              <td>{{ date("d/m/Y", strtotime($perStock->stockInOut['date'])) }}</td>
                              <td class="text-center">
                                <a href="{{ route('detail_provit_and_loss', ['product_id' => $perStock['product_id']]) }}" target="_blank">
                                    <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(99, 110, 114);"></i>
                                </a>
                              </td>
                          </tr>
		                    @endforeach
          						</tbody>
    							</table>
                  <br/>
                  {!! $stockOutOrder->appends(\Request::except('page'))->render() !!}
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
      if($('#filter_warehouse').val() != ""){
        urlParamArray.push("filter_warehouse=" + $('#filter_warehouse').val());
      }
      if($('#filter_year').val() != ""){
        urlParamArray.push("filter_year=" + $('#filter_year').val());
      }
      for (var i = 0; i < urlParamArray.length; i++) {
        if (i === 0) {
          urlParamStr += "?" + urlParamArray[i]
        } else {
          urlParamStr += "&" + urlParamArray[i]
        }
      }

      window.location.href = "{{route('list_provit_and_loss')}}" + urlParamStr;
    });
  </script>
@endsection