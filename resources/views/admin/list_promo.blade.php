<?php
    $menu_item_page = "promo";
    $menu_item_second = "list_promo";
?>
@extends('admin.layouts.template')

@section('content')
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
  			<h3 class="page-title">List Promo</h3>
  			<nav aria-label="breadcrumb">
    			<ol class="breadcrumb">
      				<li class="breadcrumb-item"><a data-toggle="collapse" href="#order-dd" aria-expanded="false" aria-controls="order-dd">Promo</a></li>
      				<li class="breadcrumb-item active" aria-current="page">List Promo</li>
    			</ol>
  			</nav>
		</div>

		<div class="row">
  			<div class="col-12 grid-margin stretch-card">
    			<div class="card">
      				<div class="card-body">
      					<h5 style="margin-bottom: 0.5em;">Total : {{ sizeof($promos) }} data</h5>
        				<div class="table-responsive" style="border: 1px solid #ebedf2;">
        					<table class="table table-bordered">
          						<thead>
						            <tr>
						              	<th> No. </th>
						              	<th> Code </th>
			                            <th> Image </th>
			                            <th colspan="2"> Product </th>
			                            <th> Price </th>
						              	<th colspan="2"> Edit / Delete </th>
						            </tr>
          						</thead>
          						<tbody>
          							@foreach($promos as $key => $promo)
          								@php
				                            $ProductPromos = json_decode($promo['product'], true);
				                            $totalProduct = count($ProductPromos);
				                        @endphp
				                        <tr>
				                        	<td rowspan="{{ $totalProduct }}">{{$key+1}}</td>
				                        	<td rowspan="{{ $totalProduct }}">{{$promo['code']}}</td>
				                            <td rowspan="{{ $totalProduct }}">
				                            	<div class="product-thumbnail product__image center-block">
                                          			<div class="product-thumbnail__wrapper">
                                              			@php
                                                  			$img = json_decode($promo->image);
                                                  			$defaultImg = asset('sources/promo_images/').'/'.strtolower($promo['code']).'/'.$img[0];
                                              			@endphp
                                              			<img alt="#" class="product-thumbnail__image" src="{{$defaultImg}}">
                                          			</div>
                                      			</div>
				                            </td>

				                            @foreach($ProductPromos as $key => $ProductPromo)
				                                <td>{{ $promo->product_list()[$key]['name']}}</td>
				                                <td>{{ $ProductPromo['qty'] }}</td>
				                                @php break; @endphp
				                            @endforeach

				                            <td rowspan="{{ $totalProduct }}">Rp. {{number_format($promo['price'])}}</td>
				                            <td rowspan="{{ $totalProduct }}" style="text-align: center;"><a href="{{ route('edit_promo', ['id' => $promo['id']])}}"><i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i></a></td>
                          					<td rowspan="{{ $totalProduct }}" style="text-align: center;"><a href="{{ route('delete_promo', ['id' => $promo['id']])}}" data-toggle="modal" data-target="#deleteDoModal" class="btnDelete"><i class="mdi mdi-delete" style="font-size: 24px; color:#fe7c96;"></i></a></td>
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
				                                <td>{{ $promo->product_list()[$key]['name']}}</td>
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
