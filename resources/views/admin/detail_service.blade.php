<?php
$menu_item_page = "service";
$menu_item_second = "detail_service";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    #intro {
        padding-top: 2em;
    }
    button {
        background: #1bb1dc;
        border: 0;
        border-radius: 3px;
        padding: 8px 30px;
        color: #fff;
        transition: 0.3s;
    }

	table {
        margin: 1em;
        font-size: 14px;
    }

    table thead {
        background-color: #8080801a;
        text-align: center;
    }

    table td {
        border: 0.5px #8080801a solid;
        padding: 0.5em;
    }

    .center {
        text-align: center;
    }

    .right {
        text-align: right;
    }

    .justify-content-center{
    	padding: 0em 1em;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
  	<div class="content-wrapper">
    	<div class="page-header">
      		<h3 class="page-title">Detail Service</h3>
      		<nav aria-label="breadcrumb">
	        	<ol class="breadcrumb">
	          		<li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#"
                            aria-expanded="false"
                            aria-controls="upgrade-dd">
                            Service
                        </a>
                    </li>
	          		<li class="breadcrumb-item active"
                        aria-current="page">
                        Detail Service
                    </li>
	        	</ol>
      		</nav>
    	</div>

    	<div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">
	          			<div class="row justify-content-center">
	          				<h2>Detail Service</h2>
	          			</div>
	          			<div class="row justify-content-center">
	          				<table class="col-md-12">
	          					<thead>
	          						<td>Status</td>
	          						<td>Service Code</td>
	          						<td>Service Date</td>
	          					</thead>
	          					<tr>
	          						<td class="center">
	          							@if (strtolower($services['status']) == "new")
                                            <span class="badge badge-secondary">
                                                New
                                            </span>
                                        @elseif (strtolower($services['status']) == "process")
                                            <span class="badge badge-primary">
                                                Process by: {{ $services->statusBy("process")->user_id['name'] }}
                                            </span>
                                        @elseif (strtolower($services['status']) == "repaired")
                                            <span class="badge badge-warning">
                                                Repaired by: {{ $services->statusBy("repaired")->user_id['name'] }}
                                            </span>
                                        @elseif (strtolower($services['status']) == "quality control")
                                            <span class="badge badge-warning">
                                                Quality Control by: {{ $services->statusBy("quality_control")->user_id['name'] }}
                                            </span>
                                        @elseif (strtolower($services['status']) == "delivery")
                                            <span class="badge badge-info">
                                                Delivery by: {{ $services->statusBy("delivery")->user_id['name'] }}
                                            </span>
                                        @elseif (strtolower($services['status']) == "take away")
                                            <span class="badge badge-info">
                                                Take Away by: {{ $services->statusBy("take_away")->user_id['name'] }}
                                            </span>
                                        @elseif (strtolower($services['status']) == "completed")
                                            <span class="badge badge-success">
                                                Completed by: {{ $services->statusBy("completed")->user_id['name'] }}
                                            </span>
                                        @endif
	          						</td>
	          						<td class="center">
                                        {{ $services['code'] }}
                                    </td>
	          						<td class="center">
	          							{{ date("d/m/Y", strtotime($services['service_date'])) }}
	          						</td>
	          					</tr>
	          				</table>
	          			</div>
	          			<div class="row justify-content-center">
	          				<table class="col-md-12">
	          					<thead>
	          						<td colspan="2">Data Service</td>
	          					</thead>
	          					<tr>
	          						<td>No. MPC: </td>
	          						@if($services['no_mpc'] != null)
	          							<td>{{ $services['no_mpc'] }}</td>
	          						@else
	          							<td>-</td>
	          						@endif
	          					</tr>
	          					<tr>
	          						<td>Customer Name: </td>
	          						<td>{{ $services['name'] }}</td>
	          					</tr>
	          					<tr>
	          						<td>Customer Phone: </td>
	          						<td>{{ $services['phone'] }}</td>
	          					</tr>
	          					<tr>
	          						<td>Customer Address: </td>
	          						<td>{{ $services['address'] }}</td>
	          					</tr>
	          				</table>
	          			</div>
	          			<div class="row justify-content-center">
	          				@foreach($services->product_services as $key => $product_service)
	          				<table class="col-md-12">
	          					<thead>
	          						<td colspan="5">Data Product Service {{$key + 1}}</td>
	          					</thead>
	          					<tr>
	          						<td>Product: </td>
	          						@if($product_service['product_id'] != null)
	          							<td>{{$product_service->product['name']}}</td>	
	          						@elseif($product_service['product_id'] == null)
	          							<td>{{$product_service['other_product']}}</td>
	          						@endif
	          					</tr>
	          					<tr>
	          						<td>Issues:</td>
	          						@php
	          							$issues = json_decode($product_service['issues']);
	          						@endphp
	          						<td>{{implode(",",$issues[0]->issues)}}</td>
	          					</tr>
	          					<tr>
	          						<td>Description: </td>
	          						<td>{{$issues[1]->desc}}</td>
	          					</tr>
	          					<tr>
	          						<td>Due Date:</td>
	          						<td>{{date("d/m/Y", strtotime($product_service['due_date']))}}</td>
	          					</tr>
	          					@if($product_service['sparepart'] != null)
		          					@php
		                            	$arr_sparepart = json_decode($product_service['sparepart']);
		                            	$count_sparepart = count($arr_sparepart);
		                            @endphp
		                            <thead>
		                            	<td colspan="5">Detail Sparepart</td>
		                            </thead>
		                            <tr>
		                            	<td>No.</td>
		                            	<td>Sparepart</td>
		                            	<td>Qty</td>
		                            	<td>Price (Rp)</td>
		                            	<td>Total (Rp)</td>
		                            </tr>
		                            <tr>
		                            	@foreach($arr_sparepart as $index => $item)
		                            		@php
		                            			$unit_price = $product_service->getSparepart($item->id)->id['price'];
		                            			$total_price = $item->qty * $unit_price;
		                            		@endphp
		                            		<td>{{$index+1}}</td>
		                            		<td>{{$product_service->getSparepart($item->id)->id['name']}}</td>
			                            	<td>{{$item->qty}}</td>
			                            	<td>{{number_format($unit_price)}}</td>
			                            	<td>{{number_format($total_price)}}</td>
			                            	@php break; @endphp
		                            	@endforeach
	          						</tr>
	          						@php $first = true; @endphp
			                        @for($i = 0; $i < $count_sparepart; $i++)
			                            @php
			                                if($first){
			                                    $first = false;
			                                    continue;
			                                }
			                                $unit_price = $product_service->getSparepart($arr_sparepart[$i]->id)->id['price'];
		                            		$total_price = $item->qty * $unit_price;
			                            @endphp
			                            <tr>
			                            	<td>{{$i+1}}</td>
			                            	<td>{{$product_service->getSparepart($arr_sparepart[$i]->id)->id['name']}}</td>
			                                <td>{{$arr_sparepart[$i]->qty}}</td>
			                                <td>{{number_format($unit_price)}}</td>
			                            	<td>{{number_format($total_price)}}</td>
			                            </tr>
			                        @endfor
	          					@endif
	          				</table>
	          				@endforeach
	          			</div>
	          		</div>
	        	</div>
	      	</div>
	    </div>

	    @if($services['status'] == 'Delivery' || $services['status'] == 'Take Away')
	    @php
	    	$arr_serviceoption = json_decode($services['service_option']);
	    @endphp
	    <div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">
	          			<div class="row justify-content-center">
	          				<table class="col-md-12">
	          					<thead>
	          						<td colspan="5">{{ $services['status'] }} Detail</td>
	          					</thead>
	          					<tr>
	          						<td>Recipient's Name: </td>
	          						<td>{{ $arr_serviceoption[0]->recipient_name }}</td>
	          					</tr>
	          					<tr>
	          						<td>Recipient's Phone: </td>
	          						<td>{{ $arr_serviceoption[0]->recipient_phone }}</td>
	          					</tr>
	          					<tr>
	          						<td>Address: </td>
	          						<td>{{ $arr_serviceoption[0]->address }}</td>
	          					</tr>
	          					<tr>
	          						<td>Branch: </td>
	          						<td>{{ $services->getDetailSales($arr_serviceoption[0]->branch_id, $arr_serviceoption[0]->cso_id)->branch_id['code'] }} - {{ $services->getDetailSales($arr_serviceoption[0]->branch_id, $arr_serviceoption[0]->cso_id)->branch_id['name'] }}</td>
	          					</tr>
	          					<tr>
	          						<td>CSO: </td>
	          						<td>{{ $services->getDetailSales($arr_serviceoption[0]->branch_id, $arr_serviceoption[0]->cso_id)->cso_id['code'] }} - {{ $services->getDetailSales($arr_serviceoption[0]->branch_id, $arr_serviceoption[0]->cso_id)->cso_id['name'] }}</td>
	          					</tr>
	          				</table>
	          			</div>
	          		</div>
	        	</div>
	      	</div>
	    </div>
	    @endif

	    @if($services['history_status'] != null)
	    <div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">
	          			<div class="row justify-content-center">
	          				<table class="col-md-12">
	          					<thead>
	          						<td colspan="3">History Status</td>
	          					</thead>
	          					<tr>
	          						<td class="center">Status</td>
	          						<td class="center">User</td>
	          						<td class="center">Date</td>
	          					</tr>
	          					@foreach(json_decode($services['history_status'], true) as $history_status)
	          					<tr>
	          						<td class="center">
	          							@if (strtolower($history_status['status']) == "process")
                                            <span class="badge badge-primary">
                                                Process
                                            </span>
                                        @elseif (strtolower($history_status['status']) == "repaired")
                                            <span class="badge badge-warning">
                                                Repaired
                                            </span>
                                        @elseif (strtolower($history_status['status']) == "quality_control")
                                            <span class="badge badge-warning">
                                                Quality Control
                                            </span>
                                        @elseif (strtolower($history_status['status']) == "delivery")
                                            <span class="badge badge-info">
                                                Delivery
                                            </span>
                                        @elseif (strtolower($history_status['status']) == "take_away")
                                            <span class="badge badge-info">
                                                Take Away
                                            </span>
                                        @elseif (strtolower($history_status['status']) == "completed")
                                            <span class="badge badge-success">
                                                Completed
                                            </span>
                                        @endif
	          						</td>
	          						<td class="center">{{ $services->statusBy("process")->user_id['name'] }}</td>
	          						<td class="center">{{ date("d/m/Y", strtotime($history_status['updated_at'])) }}</td>
	          					</tr>
	          					@endforeach
	          				</table>
	          			</div>
	          		</div>
	        	</div>
	      	</div>
	    </div>
	    @endif

	    <?php if (strtolower($services->status) === "repaired"): ?>
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <form id="actionAdd"
                                    class="forms-sample"
                                    method="POST"
                                    action="<?php echo route("update_service_status"); ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden"
                                        name="id"
                                        value="<?php echo $services->id; ?>" />
                                    @can('change-status-qc-service')
                                    <button id="upgradeProcess"
                                        type="submit"
                                        class="btn btn-gradient-primary btn-lg"
                                        name="status"
                                        value="Quality_Control">
                                        Quality Control
                                    </button>
                                    @endcan
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif (strtolower($services->status) === "quality control"): ?>
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                        	<div class="row justify-content-center">
                                <h3>Delivery / Take Away Detail</h3>
                            </div>
                            <div class="row justify-content-center">
                                <form id="actionAdd"
                                    class="forms-sample"
                                    method="POST"
                                    action="<?php echo route("update_service_status"); ?>">
                                    <?php echo csrf_field(); ?>


                                    <div class="form-group">
						                <label for="">Recipient's Name</label>
						                <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
						                <div class="validation"></div>
			              			</div>
			              			<div class="form-group">
						                <label for="exampleTextarea1">Address</label>
						                <textarea class="form-control" id="address" name="address" rows="4" placeholder="Address" required></textarea>
						                <div class="validation"></div>
			              			</div>
			              			<div class="form-group">
						                <label for="">Recipient's Phone Number</label>
						                <input type="number" class="form-control" id="phone" name="phone" placeholder="Phone Number" required>
						                <div class="validation"></div>
			              			</div>

			              			<div class="form-group">
										<label for=""><h2>Data Sales </h2></label><br/>
						                <label for="">Branch</label>
						                <select class="form-control" id="branch" name="branch_id" data-msg="Mohon Pilih Cabang" required>
						                  	<option selected disabled value="">Choose Branch</option>

					                        @foreach($branches as $branch)
					                            <option value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
					                        @endforeach
			                			</select>
			                			<div class="validation"></div>
			              			</div>
			              			<div class="form-group">
			                			<label for="">CSO Code</label>
			               			 	<input type="text" class="form-control" name="cso_id" id="cso" placeholder="CSO Code" required data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase" {{ Auth::user()->roles[0]['slug'] == 'cso' ? "value=".Auth::user()->cso['code'] : "" }}  {{ Auth::user()->roles[0]['slug'] == 'cso' ? "readonly=\"\"" : "" }} />
		                    			<div class="validation" id="validation_cso"></div>
			              			</div>

                                    <input type="hidden"
                                        name="id"
                                        value="<?php echo $services->id; ?>" />
                                    @can('change-status-delivery-service')
                                    <button id="upgradeProcess"
                                        type="submit"
                                        class="btn btn-gradient-primary btn-lg"
                                        name="status"
                                        value="Delivery">
                                        Delivery
                                    </button>
                                    <button id="upgradeProcess"
                                        type="submit"
                                        class="btn btn-gradient-success btn-lg"
                                        name="status"
                                        value="Take_Away">
                                        Take Away
                                    </button>
                                    @endcan
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif (strtolower($services->status) === "delivery" || strtolower($services->status) === "take_away"): ?>
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <form id="actionAdd"
                                    class="forms-sample"
                                    method="POST"
                                    action="<?php echo route("update_service_status"); ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden"
                                        name="id"
                                        value="<?php echo $services->id; ?>" />
                                    @can('change-status-complete-service')
                                    <button id="upgradeProcess"
                                        type="submit"
                                        class="btn btn-gradient-primary btn-lg"
                                        name="status"
                                        value="Completed">
                                        Completed
                                    </button>
                                    @endcan
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

	</div>
</div>
@endsection

@section('script')
<script type="text/javascript">
	$(document).ready(function(){
		$("#cso").on("input", function(){
            check_cso($("#cso").val());
		});

		function check_cso(code) {
        	$.get( '{{route("fetchCso")}}', { cso_code: code })
            .done(function( result ) {
                if (result['result'] == "true" && result['data'].length > 0) {
                    $('#validation_cso').html('Kode CSO Benar');
                    $('#validation_cso').css('color', 'green');
                    $('#submit').removeAttr('disabled');
                }
                else{
                    $('#validation_cso').html('Kode CSO Salah');
                    $('#validation_cso').css('color', 'red');
                    $('#submit').attr('disabled',"");
                }
            });
        };
	});
</script>
@endsection
