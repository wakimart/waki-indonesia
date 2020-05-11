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
        				<div class="table-responsive" style="border: 1px solid #ebedf2;">
        					<table class="table table-bordered">
          						<thead>
						            <tr>
						              	<th> No. </th>
						              	<th> Order Code </th>
						              	<th> Order Date </th>
						              	<th> Member Name </th>
						              	<th colspan="2"> Product </th>
						              	<th> Branch </th>
						              	<th> CSO </th>
						              	<th colspan="2"> Edit / Delete </th>
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
				                            <td rowspan="{{ $totalProduct }}">{{ $order->branch['code'] }} - {{ $order->branch['name'] }}</td>
				                            <td rowspan="{{ $totalProduct }}">{{ $order->cso['code'] }} - {{ $order->cso['name'] }}</td>
				                            <td rowspan="{{ $totalProduct }}" style="text-align: center;"><a href="{{ route('edit_order', ['id' => $order['id']])}}"><i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i></a></td>
                          					<td rowspan="{{ $totalProduct }}" style="text-align: center;"><a href="{{ route('delete_order', ['id' => $order['id']])}}" data-toggle="modal" data-target="#deleteDoModal" class="btnDelete"><i class="mdi mdi-delete" style="font-size: 24px; color:#fe7c96;"></i></a></td>
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