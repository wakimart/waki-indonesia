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

		<div class="row">
  			<div class="col-12 grid-margin stretch-card">
    			<div class="card">
      				<div class="card-body">
      					<h5 style="margin-bottom: 0.5em;">Total : {{ sizeof($orders) }} data</h5>
      						@if(Auth::user()->roles[0]['slug'] != 'branch' && Auth::user()->roles[0]['slug'] != 'cso')
	      					<div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
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

      					<div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
      						<div class="col-xs-6 col-sm-6" style="padding: 0;display: inline-block;">
      							<div class="form-group">
      								<button id="btn-filter" type="button" class="btn btn-gradient-primary m-1" name="filter" value="-"><span class="mdi mdi-filter"></span> Apply Filter</button>
      							</div>
      						</div>
      					</div>
      					@endif

        				<div class="table-responsive" style="border: 1px solid #ebedf2;">
        					<table class="table table-bordered">
          						<thead>
						            <tr>
						              	<th> No. </th>
						              	<th> Order Code </th>
						              	<th> Order Date </th>
						              	<th> Branch </th>
						              	<th> Member Name </th>
						              	<th colspan="2"> Product </th>
						              	<th> CSO </th>
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
				                            <td rowspan="{{ $totalProduct }}">{{ date("d/m/Y", strtotime($order['created_at'])) }}</td>
				                            <td rowspan="{{ $totalProduct }}">{{ $order->branch['code'] }} - {{ $order->branch['name'] }}</td>
				                            <td rowspan="{{ $totalProduct }}">{{ $order['name'] }}</td>

				                            @foreach($ProductPromos as $ProductPromo)
				                            	@if(is_numeric($ProductPromo['id']))
					                                <td>{{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['code'] }} - {{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['name'] }} ( {{ App\DeliveryOrder::$Promo[$ProductPromo['id']]['harga'] }} )</td>
					                            @else
					                                <td>{{ $ProductPromo['id'] }}</td>
					                            @endif

				                                <td>{{ $ProductPromo['qty'] }}</td>
				                                @php break; @endphp
				                            @endforeach
				                            <td rowspan="{{ $totalProduct }}">{{ $order->cso['code'] }} - {{ $order->cso['name'] }}</td>
				                            @can('edit-order')
					                            <td rowspan="{{ $totalProduct }}" style="text-align: center;"><a href="{{ route('edit_order', ['id' => $order['id']])}}"><i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i></a></td>
				                            @endcan
				                            @can('delete-order')
	                          					<td rowspan="{{ $totalProduct }}" style="text-align: center;"><a href="{{ route('delete_order', ['id' => $order['id']])}}" data-toggle="modal" data-target="#deleteDoModal" class="btnDelete"><i class="mdi mdi-delete" style="font-size: 24px; color:#fe7c96;"></i></a></td>
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
              		<h5 style="text-align:center;">Are You Sure to Delete this Delivery Order ?</h5>
            	</div>
            	<div class="modal-footer">
            		<form id="frmDelete" method="post" action="">
                    {{csrf_field()}}
                    	<button type="submit" class="btn btn-gradient-danger mr-2">Yes</button>
                	</form>
              		<button class="btn btn-light">No</button>
            	</div>
          	</div>
        </div>
    </div>
    <!-- End Modal Delete -->
</div>
@endsection

@section('script')
<script type="text/javascript">
	$(document).ready(function(e){
		$("#filter_branch").on("change", function(){
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
	});

	$(document).on("click", "#btn-filter", function(e){
		var urlParamArray = new Array();
		var urlParamStr = "";
		if($('#filter_branch').val() != ""){
			urlParamArray.push("filter_branch=" + $('#filter_branch').val());
		}
		if($('#filter_cso').val() != ""){
			urlParamArray.push("filter_cso=" + $('#filter_cso').val());
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
