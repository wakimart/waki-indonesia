<?php
    $menu_item_page = "stock";
    $menu_item_second = "list_stock";
?>
@extends('admin.layouts.template')

@section('content')
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
  			<h3 class="page-title">List Stock</h3>
  			<nav aria-label="breadcrumb">
    			<ol class="breadcrumb">
      				<li class="breadcrumb-item"><a data-toggle="collapse" href="#stock-dd" aria-expanded="false" aria-controls="stock-dd">Stock</a></li>
      				<li class="breadcrumb-item active" aria-current="page">List Stock</li>
    			</ol>
  			</nav>
		</div>

		<div class="row">
  			<div class="col-12 grid-margin stretch-card">
    			<div class="card">
      				<div class="card-body">
      					<h5 style="margin-bottom: 0.5em;">Total : {{ sizeof($stocks) }} data</h5>
        				<div class="table-responsive" style="border: 1px solid #ebedf2;">
        					<table class="table table-bordered">
          						<thead>
						            <tr>
						              	<th rowspan="2" class="align-middle"> No. </th>
						              	<th rowspan="2" class="align-middle"> Product Name </th>
        								<th colspan="2" class="text-center"> Qty </th>
        								{{-- <th> Warehouse </th> --}}
						            </tr>
									<tr>
										<td class="text-center"> Ready </td>
										<td class="text-center"> Display </td>
									</tr>
          						</thead>
          						<tbody>
          						@foreach($stocks as $key => $stock)
		                        <tr>
		                        	<td>{{$key+1}}</td>

									@if($stock['product_id'] != null)
										@if ($stock->product == reset($stocks )) First Item: @endif
										<td>{{$stock->product['name']}}</td>
									@elseif($stock['product_id'] == null)
										<td>{{$stock['other_product']}}</td>
									@endif

									<td>{{$stock['quantity']}}</td>
   
								
		                        </tr>
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
