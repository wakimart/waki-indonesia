<?php
    $menu_item_page = "order";
    $menu_item_second = "list_order";
?>
@extends('admin.layouts.template')

@section('content')
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
  			<h3 class="page-title">List Order</h3>
  			<nav aria-label="breadcrumb">
    			<ol class="breadcrumb">
      				<li class="breadcrumb-item"><a data-toggle="collapse" href="#order-dd" aria-expanded="false" aria-controls="order-dd">Order</a></li>
      				<li class="breadcrumb-item active" aria-current="page">List Order</li>
    			</ol>
  			</nav>
		</div>

    <div class="col-12 grid-margin" style="padding: 0;">
	  @if(Auth::user()->roles[0]['slug'] != 'branch' && Auth::user()->roles[0]['slug'] != 'cso')
		@if(Utils::$lang=='id')
		<div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
			<div class="form-group">
			<label for="">Filter By City</label>
				<select class="form-control" id="filter_province" name="filter_province">
					<option value="" selected="">All Province</option>
					@php
					$result = RajaOngkir::FetchProvince();
					$result = $result['rajaongkir']['results'];
					$arrProvince = [];
					if(sizeof($result) > 0){
						foreach ($result as $value) {
							echo "<option value=\"". $value['province_id']."\">".$value['province']."</option>";
						}
					}
					@endphp
				</select>
				<div class="validation"></div>
			</div>
		</div>
		<div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
			<div class="form-group">
			<label style="opacity: 0;" for=""> s</label>
				<select class="form-control" id="filter_city" name="filter_city">
				<option value="">All City</option>
				@if(isset($_GET['filter_city']))
					<option selected="" value="{{$_GET['filter_city']}}">{{$_GET['filter_city']}}</option>
				@endif
				</select>
				<div class="validation"></div>
			</div>
		</div>
		<div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
			<div class="form-group">
			<label style="opacity: 0;" for=""> s</label>
				<select class="form-control" id="filter_district" name="filter_district">
				<option value="">All District</option>
				@if(isset($_GET['filter_district']))
					<option selected="" value="{{$_GET['filter_district']}}">{{$_GET['filter_district']}}</option>
				@endif
				</select>
				<div class="validation"></div>
			</div>
		</div>
		@endif
        <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
		  <div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
            <div class="form-group">
              <label for="">Filter By Type Customer</label>
                <select class="form-control" id="filter_type" name="filter_type">
                  <option value="">All Type</option>
                  @php
                    $selected = "";
                    if(isset($_GET['filter_type'])){
                      $selected = $_GET['filter_type'];
                    }
                  @endphp
                  <option {{ $selected == "Tele Voucher" ? "selected=\"\"" : "" }} value="Tele Voucher">Tele Voucher</option>
                  <option {{ $selected == "Tele Home Service" ? "selected=\"\"" : "" }} value="Tele Home Service">Tele Home Service</option>
                  <option {{ $selected == "Home Office Voucher" ? "selected=\"\"" : "" }} value="Home Office Voucher">Home Office Voucher</option>
                  <option {{ $selected == "Home Voucher" ? "selected=\"\"" : "" }} value="Home Voucher">Home Voucher</option>
                </select>
                <div class="validation"></div>
            </div>
          </div>
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
              <label for="">Filter By CSO</label>
                <select class="form-control" id="filter_cso" name="filter_cso">
                  <option value="">All CSO</option>
                  @php
                    if(isset($_GET['filter_branch'])){
                      $csos = App\Cso::Where('branch_id', $_GET['filter_branch'])->where('active', true)->get();

                      foreach ($csos as $cso) {
                        if(isset($_GET['filter_cso'])){
                          if($_GET['filter_cso'] == $cso['id']){
                            echo "<option selected=\"\" value=\"".$cso['id']."\">".$cso['code']." - ".$cso['name']."</option>";
                            continue;
                          }
                        }
                        echo "<option value=\"".$cso['id']."\">".$cso['code']." - ".$cso['name']."</option>";
                      }
                    }
                  @endphp
                </select>
                <div class="validation"></div>
            </div>
          </div>
        </div>
      @endif

      @if(Auth::user()->roles[0]['slug'] != 'branch' && Auth::user()->roles[0]['slug'] != 'cso' && Auth::user()->roles[0]['slug'] != 'area-manager')
        <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
        <div class="col-xs-6 col-sm-6" style="padding: 0;display: inline-block;">
          <label for=""></label>
          <div class="form-group">
          <button id="btn-filter" type="button" class="btn btn-gradient-primary m-1" name="filter" value="-"><span class="mdi mdi-filter"></span> Apply Filter</button>
          <button id="btn-report" type="button" class="btn btn-gradient-info m-1" name="report" value="-" data-toggle="modal" data-target="#reportOrderModal"><span class="mdi mdi-filter"></span> Report Order</button>
          </div>
        </div>
        </div>
      @endif

      <div class="col-sm-12 col-md-12" style="padding: 0; border: 1px solid #ebedf2;">
        <div class="col-xs-12 col-sm-11 col-md-6 table-responsive" id="calendarContainer" style="padding: 0; float: left;"></div>
        <div class="col-xs-12 col-sm-11 col-md-6" id="organizerContainer" style="padding: 0; float: left;"></div>
      </div>
    </div>

		<div class="row">


  			<div class="col-12 grid-margin stretch-card">
    			<div class="card">
      			<div class="card-body">
						<h5 style="margin-bottom: 0.5em;">Total : {{ $countOrders }} data</h5>
        				<div class="table-responsive" style="border: 1px solid #ebedf2;">
        					<table class="table table-bordered">
          						<thead>
						            <tr>
						              	<th> No. </th>
						              	<th> Order Code </th>
						              	<th> @sortablelink('orderDate', 'Order Date') </th>
						              	<th> Branch </th>
						              	<th> Member Name </th>
						              	<th colspan="2"> Product </th>
						              	<th> CSO </th>
						              	<th> Type Customer </th>
						              	@if(Gate::check('edit-order') || Gate::check('delete-order'))
							              	<th colspan="2"> Edit / Delete </th>
							            @endif
						            </tr>
          						</thead>
          						<tbody>

          							@foreach($orders as $key => $order)
				                        @php
				                            $ProductPromos = json_decode($order['product'], true);
				                            $totalProduct = count($ProductPromos);
				                        @endphp
				                        <tr>
				                        	<td rowspan="{{ $totalProduct }}">{{$key+1}}</td>
				                            <td rowspan="{{ $totalProduct }}"><a href="{{ route('detail_order') }}?code={{ $order['code'] }}">{{ $order['code'] }}</a></td>
				                            <td rowspan="{{ $totalProduct }}">{{ date("d/m/Y", strtotime($order['orderDate'])) }}</td>
				                            <td rowspan="{{ $totalProduct }}">{{ $order->branch['code'] }} - {{ $order->branch['name'] }}</td>
				                            <td rowspan="{{ $totalProduct }}">{{ $order['name'] }}</td>

				                            @foreach($ProductPromos as $ProductPromo)
				                            	@if(isset(App\DeliveryOrder::$Promo[$ProductPromo['id']]))
					                                <td>{{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['code'] }} - {{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['name'] }} ( {{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['harga'] }} )</td>
					                            @else
					                                <td>{{ $ProductPromo['id'] }}</td>
					                            @endif

				                                <td>{{ $ProductPromo['qty'] }}</td>
				                                @php break; @endphp
				                            @endforeach
				                            <td rowspan="{{ $totalProduct }}">{{ $order->cso['code'] }} - {{ $order->cso['name'] }}</td>
				                            <td rowspan="{{ $totalProduct }}">{{ $order['customer_type'] }}</td>
				                            @can('edit-order')
					                            <td rowspan="{{ $totalProduct }}" style="text-align: center;"><a href="{{ route('edit_order', ['id' => $order['id']])}}"><i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i></a></td>
				                            @endcan
				                            @can('delete-order')
	                          					<td style="text-align: center;"><button value="{{ route('delete_order', ['id' => $order['id']])}}" data-toggle="modal" data-target="#deleteDoModal" class="btn-delete" ><i class="mdi mdi-delete" style="font-size: 24px; color:#fe7c96;"></i></button></td>
	                          				@endcan
				                        </tr>
				                        @php $first = true; @endphp
				                        @foreach($ProductPromos as $ProductPromo)
				                            @php
				                                if($first){
				                                    $first = false;
				                                    continue;
				                                }
				                            @endphp
				                            <tr>
				                                @if(is_numeric($ProductPromo['id']))
					                                <td>{{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['code'] }} - {{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['name'] }} ( {{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['harga'] }} )</td>
					                            @else
					                                <td>{{ $ProductPromo['id'] }}</td>
					                            @endif

				                                <td>{{ $ProductPromo['qty'] }}</td>
				                            </tr>
				                        @endforeach
				                    @endforeach
          						</tbody>
							</table>
							<br/>
							{!! $orders->appends(\Request::except('page'))->render() !!}
							{{-- {{ $orders->appends(['sort' => 'created_at'])->links()}} --}}
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
              		<h5 style="text-align:center;">Are You Sure to Delete this Order ?</h5>
            	</div>
            	<div class="modal-footer">
            		<form id="frmDelete" method="post" action="">
                    {{csrf_field()}}
                    	<button type="submit" class="btn btn-gradient-danger mr-2">Yes</button>
                	</form>
              		<button class="btn btn-light" data-dismiss="modal">No</button>
            	</div>
          	</div>
        </div>
    </div>
	<!-- End Modal Delete -->

	<!-- Modal Report -->
	<div class="modal fade" id="reportOrderModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      	<div class="modal-content">
        	<div class="modal-header">
					<label>Report Order</label>
              		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                		<span aria-hidden="true">&times;</span>
              		</button>
        	</div>
        	<div class="modal-body">
					<div class="form-group">
					  <label>Pick a Date</label>
					  <input type="date" class="form-control" name="orderDate" id="orderDate" placeholder="Awal Tanggal" required data-msg="Mohon Isi Tanggal" onload="getDate()" />
					  <div class="validation"></div>
				  </div>
				  {{-- <div class="form-group">
						<label for="">Product Category</label>
						<select class="form-control" id="categoryReport" name="categoryReport">
							<option selected disabled value="">Choose Category</option>
							@foreach($categories as $category)
								<option value="{{$category['id']}}">{{$category['name']}}</option>
							@endforeach
						</select>
					</div> --}}
          <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
  					<div class="col-xs-6 col-sm-6" style="padding: 0;display: inline-block;">
  						<div class="form-group">
  							<label for="">Province</label>
  							<select class="form-control" id="province" name="province_id" data-msg="Mohon Pilih Provinsi" >
  								<option selected disabled value="">Pilihan Provinsi</option>

  								@php
  									$result = RajaOngkir::FetchProvince();
  									$result = $result['rajaongkir']['results'];
  									$arrProvince = [];
  									if(sizeof($result) > 0){
  										foreach ($result as $value) {
  											echo "<option value=\"". $value['province_id']."\">".$value['province']."</option>";
  										}
  									}
  								@endphp
  							</select>
  							<div class="validation"></div>
  					   </div>
				      </div>
    					<div class="col-xs-6 col-sm-6" style="padding: 0;display: inline-block;">
    						<div class="form-group">
    							<label for="">City</label>
    							<select class="form-control" id="city" name="city" data-msg="Mohon Pilih Kota">
    								<option selected disabled value="">Pilihan Kota</option>
    							</select>
    							<div class="validation"></div>
    						</div>
    					</div>
            </div>
            <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
              <div class="col-xs-6 col-sm-6" style="padding: 0;display: inline-block;">
                <div class="form-group">
                  <label for="">Filter By Team</label>
                  <select class="form-control" id="filter_branch_modal" name="filter_branch_modal">
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
  					  <div class="col-xs-6 col-sm-6" style="padding: 0;display: inline-block;">
    						<div class="form-group">
    						  <label for="">Filter By CSO</label>
    							<select class="form-control" id="report_cso_modal" name="report_cso_modal">
    							  <option value="">All CSO</option>
    							  @php
    								if(isset($_GET['filter_branch'])){
    								  $csos = App\Cso::Where('branch_id', $_GET['filter_branch'])->where('active', true)->get();

    								  foreach ($csos as $cso) {
    									if(isset($_GET['filter_cso_modal'])){
    									  if($_GET['filter_cso_model'] == $cso['id']){
    										echo "<option selected=\"\" value=\"".$cso['id']."\">".$cso['code']." - ".$cso['name']."</option>";
    										continue;
    									  }
    									}
    									echo "<option value=\"".$cso['id']."\">".$cso['code']." - ".$cso['name']."</option>";
    								  }
    								}
    							  @endphp
    							</select>
    							<div class="validation"></div>
    						</div>
    					</div>
            </div>

            	<div class="modal-footer">
                    {{csrf_field()}}
                    <button type="submit" id="exportButton" class="btn btn-gradient-primary mr-2">Download</button>
              		<button class="btn btn-light" data-dismiss="modal">No</button>
            	</div>
          	</div>
        </div>
    </div>
    <!-- End Modal Report -->
</div>
@endsection



@section('script')

<script>
	$(document).ready(function(e){
		$("#filter_branch").on("change", function(){
		  console.log("test")
		  var id = $(this).val();
		  $.get( '{{ route("fetchCsoByIdBranch", ['branch' => ""]) }}/'+id )
		  .done(function( result ) {
			  $( "#filter_cso" ).html("");
			  var arrCSO = "<option selected value=\"\">All CSO</option>";
			  if(result.length > 0){
				  $.each( result, function( key, value ) {
					arrCSO += "<option value=\""+value['id']+"\">"+value['code']+" - "+value['name']+"</option>";
				  });
				  $( "#filter_cso" ).append(arrCSO);
				}
			});
		if(id == ""){
		  $( "#filter_cso" ).html("<option selected value=\"\">All CSO</option>");
	  }
	  });

	  $("#filter_province").on("change", function(){
      var id = $(this).val();
      $( "#filter_city" ).html("");
      $.get( '{{ route("fetchCity", ['province' => ""]) }}/'+id )
      .done(function( result ) {
          result = result['rajaongkir']['results'];
          var arrCity = "<option selected value=\"\">All City</option>";
          if(result.length > 0){
              $.each( result, function( key, value ) {
                  if(value['type'] == "Kota"){                            
                      arrCity += "<option value=\"Kota "+value['city_name']+"\">Kota "+value['city_name']+"</option>";
                  }
              });
              $( "#filter_city" ).append(arrCity);
            }
        });
    });
    $("#filter_city").on("change", function(){
      var id = $(this).val();
      $( "#filter_district" ).html("");
      $.get( '{{ route("fetchDistrict", ['city' => ""]) }}/'+id )
      .done(function( result ) {
          result = result['rajaongkir']['results'];
          var arrdistrict = "<option selected value=\"\">All District</option>";
          if(result.length > 0){
              $.each( result, function( key, value ) {                            
                arrdistrict += "<option value=\""+value['subdistrict_name']+"\">Kota "+value['subdistrict_name']+"</option>";  
              });
              $( "#filter_district" ).append(arrdistrict);
            }
        });
    });

	  $(".btn-delete").click(function(e) {
		$("#frmDelete").attr("action",  $(this).val());
	  });

	  $("#exportButton").on("click", function(){
		var urlParamArray = new Array();
		var urlParamStr = "";
		if($('#orderDate').val() != ""){
			urlParamArray.push("orderDate=" + $('#orderDate').val());
		}
		if($('#report_cso_modal').val() != ""){
			urlParamArray.push("report_cso_modal=" + $('#report_cso_modal').val());
		}
		// if($('#categoryReport').val() != "" || $('#categoryReport').val() != null){
		// 	urlParamArray.push("categoryReport=" + $('#categoryReport').val());
		// }
		for (var i = 0; i < urlParamArray.length; i++) {
			if (i === 0) {
			urlParamStr += "?" + urlParamArray[i]
			} else {
			urlParamStr += "&" + urlParamArray[i]
			}
		}
		console.log(urlParamStr);
		window.location.href = "{{route('order_export-to-xls')}}" + urlParamStr;
	  });
	  $("#province").on("change", function(){
		var id = $(this).val();
		$( "#city" ).html("");
		$.get( '{{ route("fetchCity", ['province' => ""]) }}/'+id )
		.done(function( result ) {
			result = result['rajaongkir']['results'];
			var arrCity = "<option selected disabled value=\"\">Pilihan Kota</option>";
			if(result.length > 0){
				$.each( result, function( key, value ) {
					if(value['type'] == "Kota"){
						arrCity += "<option value=\"Kota "+value['city_name']+"\">Kota "+value['city_name']+"</option>";
					}
				});
				$( "#city" ).append(arrCity);
			}
			});
		});

		$("#filter_branch_modal").on("change", function(){
		  console.log("test")
		  var id = $(this).val();
		  $.get( '{{ route("fetchCsoByIdBranch", ['branch' => ""]) }}/'+id )
		  .done(function( result ) {
			  $( "#report_cso_modal" ).html("");
			  var arrCSO = "<option selected value=\"\">All CSO</option>";
			  if(result.length > 0){
				  $.each( result, function( key, value ) {
					arrCSO += "<option value=\""+value['id']+"\">"+value['code']+" - "+value['name']+"</option>";
				  });
				  $( "#report_cso_modal" ).append(arrCSO);
				}
			});
		if(id == ""){
		  $( "#report_cso_modal" ).html("<option selected value=\"\">All CSO</option>");
	  	}
	  });
	});
	$(document).on("click", "#btn-filter", function(e){
	  var urlParamArray = new Array();
	  var urlParamStr = "";
	  if($('#filter_city').val() != ""){
		urlParamArray.push("filter_city=" + $('#filter_city').val());
	  }
	  if($('#filter_district').val() != ""){
		urlParamArray.push("filter_district=" + $('#filter_district').val());
	  }	
	  if($('#filter_branch').val() != ""){
		urlParamArray.push("filter_branch=" + $('#filter_branch').val());
	  }
	  if($('#filter_cso').val() != ""){
		urlParamArray.push("filter_cso=" + $('#filter_cso').val());
	  }
	  if($('#filter_type').val() != ""){
		urlParamArray.push("filter_type=" + $('#filter_type').val());
	  }
	  for (var i = 0; i < urlParamArray.length; i++) {
		if (i === 0) {
		  urlParamStr += "?" + urlParamArray[i]
		} else {
		  urlParamStr += "&" + urlParamArray[i]
		}
	  }

	  window.location.href = "{{route('admin_list_order')}}" + urlParamStr;
	});
	</script>
@endsection
