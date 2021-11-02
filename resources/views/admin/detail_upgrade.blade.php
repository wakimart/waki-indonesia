<?php
$menu_item_page = "upgrade";
$menu_item_second = "detail_upgrade_form";
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

	/*-- mobile --*/
	@media (max-width: 768px){

		#desktop{
			display: none;
		}

		#mobile{
			display: block;
		}

		img{
			height: 150px;
		}
	}

	@media (min-width: 768px) { 
		#desktop{
			display: block;
		}

		#mobile{
			display: none;
		}
		.table-responsive::-webkit-scrollbar {
			display: none;
		}
	}
</style>
@endsection

@section('content')
<div class="main-panel">
  	<div class="content-wrapper">
		<!-- header mobile -->
		<div id="mobile">
			<h3 class="text-center">Add Upgrade</h3>
			<div class="row">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a data-toggle="collapse" href="#" aria-expanded="false" aria-controls="upgrade-dd">Upgrade</a></li>
						<li class="breadcrumb-item active" aria-current="page">Add Upgrade</li>
					</ol>
				</nav>
		  	</div>
	  	</div>

		<!-- header desktop -->
		<div id="desktop">   	
			<div class="page-header">
				<h3 class="page-title">Add Upgrade</h3>
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a data-toggle="collapse"
								href="#"
								aria-expanded="false"
								aria-controls="upgrade-dd">
								Upgrade
							</a>
						</li>
						<li class="breadcrumb-item active"
							aria-current="page">
							Add Upgrade
						</li>
					</ol>
				</nav>
			</div>
		</div> 

    	<div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">
	          			<div class="row justify-content-center">
	          				<h2>Detail Upgrade</h2>
	          			</div>
	          			<div class="row justify-content-center">
							<div class="table-responsive">
								<table class="col-md-12">
									<thead>
										<td>Status</td>
										<td>Acc Code</td>
										<td>Upgrade Date</td>
									</thead>
									<tr>
										<td class="center">
											@if (strtolower($upgrade['status']) == "new")
												<span class="badge badge-secondary">
													New
												</span>
											@elseif (strtolower($upgrade['status']) == "process")
												<span class="badge badge-primary">
													Process by: {{ $upgrade->statusBy("process")['user_id']['name'] }}
												</span>
											@elseif (strtolower($upgrade['status']) == "repaired")
												<span class="badge badge-warning">
													Repaired by: {{ $upgrade->statusBy("repaired")['user_id']['name'] }}
												</span>
											@elseif (strtolower($upgrade['status']) == "approved")
												<span class="badge badge-info">
													Approved by: {{ $upgrade->statusBy("approved")['user_id']['name'] }}
												</span>
											@elseif (strtolower($upgrade['status']) == "completed")
												<span class="badge badge-success">
													Completed by: {{ $upgrade->statusBy("completed")['user_id']['name'] }}
												</span>
											@endif
										</td>
										<td class="center">
											{{ $upgrade->acceptance['code'] }}
										</td>
										<td class="center">
											{{ date("d/m/Y", strtotime($upgrade->acceptance['upgrade_date'])) }}
										</td>
									</tr>
								</table>
							</div>
	          			</div>
	          			<div class="row justify-content-center">
							<div class="table-responsive">
								<table class="col-md-12">
									<thead>
										<td colspan="2">Data Upgrade</td>
									</thead>
									<tr>
										<td>Customer Name: </td>
										<td>{{ $upgrade->acceptance['name'] }}</td>
									</tr>
									<tr>
										<td>Customer Phone: </td>
										<td>{{ $upgrade->acceptance['phone'] }}</td>
									</tr>
									<tr>
										<td>Branch: </td>
										<td>{{ $upgrade->acceptance->branch->code }} - {{ $upgrade->acceptance->branch->name }}</td>
									</tr>
									<tr>
										<td>CSO: </td>
										<td>{{ $upgrade->acceptance->cso->code }} - {{ $upgrade->acceptance->cso->name }}</td>
									</tr>
								</table>
							</div>
	          			</div>
	          			<div class="row justify-content-center">
							<div class="table-responsive">
								<table class="col-md-12">
									<thead>
										<td colspan="2">Data Product</td>
									</thead>
									<tr>
										<td>New Product: </td>
										<td>{{ $upgrade->acceptance->newproduct['code'] }} - {{ $upgrade->acceptance->newproduct['name'] }}</td>
									</tr>
									<tr>
										<td>Old Product: </td>
										@if ($upgrade->acceptance['oldproduct_id'] != null)
											<td>{{ $upgrade->acceptance->oldproduct['code'] }} - {{ $upgrade->acceptance->oldproduct['name'] }}</td>
										@else
											<td>{{ $upgrade->acceptance['other_product'] }}</td>
										@endif
									</tr>
									<tr>
										<td>Purchase Date: </td>
										<td>{{ date("d/m/Y", strtotime($upgrade->acceptance['purchase_date'])) }}</td>
									</tr>

									<tr>
										<td rowspan="5">Completeness: </td>
										<td><i class="mdi {{ in_array("mesin", $upgrade->acceptance['arr_condition']['kelengkapan']) ? "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" style="font-size: 24px; color: #fed713;"></i> Mesin
										</td>
									</tr>
									<tr>
										<td>
											<i class="mdi {{ in_array("filter", $upgrade->acceptance['arr_condition']['kelengkapan']) ? "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" style="font-size: 24px; color: #fed713;"></i> Filter
										</td>
									</tr>
									<tr>
										<td>
											<i class="mdi {{ in_array("aksesoris", $upgrade->acceptance['arr_condition']['kelengkapan']) ? "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" style="font-size: 24px; color: #fed713;"></i> Aksesoris
										</td>
									</tr>
									<tr>
										<td>
											<i class="mdi {{ in_array("kabel", $upgrade->acceptance['arr_condition']['kelengkapan']) ? "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" style="font-size: 24px; color: #fed713;"></i> Kabel
										</td>
									</tr>
									<tr>
										<td>
											<i class="mdi {{ in_array("other", $upgrade->acceptance['arr_condition']['kelengkapan']) ? "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" style="font-size: 24px; color: #fed713;"></i> Other: {{ isset($upgrade->acceptance['arr_condition']['kelengkapan']['other']) ? $upgrade->acceptance['arr_condition']['kelengkapan']['other'][0] : "-" }}
										</td></tr>
									<tr>
										<td>Machine Condition: </td>
										<td>{{ ucwords($upgrade->acceptance['arr_condition']['kondisi']) }}</td>
									</tr>
									<tr>
										<td>Body Condition: </td>
										<td>{{ ucwords($upgrade->acceptance['arr_condition']['tampilan']) }}</td>
									</tr>
									<tr>
										<td>Description: </td>
										<td>{{ $upgrade->acceptance['description']}}</td>
									</tr>
									<tr>
										<td>Photo: </td>
										<td>
											@foreach($upgrade->acceptance['image'] as $imgAcc)
												<img src="{{asset('sources/acceptance/').'/'.$imgAcc}}" height="300px">
											@endforeach
										</td>
									</tr>
								</table>
							</div>
	          			</div>
	          		</div>
	        	</div>
	      	</div>
	    </div>

	    <div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">
	          			<div class="row justify-content-center">
	          				<h2>Detail Process</h2>
	          			</div>
	          			<div class="row justify-content-center">
							<div class="table-responsive">
								<table class="col-md-12">
									<thead>
										<td colspan="2">Process Detail</td>
									</thead>
									<tr>
										<td>Due Date: </td>
										<td>{{ date("d/m/Y", strtotime($upgrade->due_date)) }}</td>
									</tr>
									<tr>
										<td>Task: </td>
										<td>{{ $upgrade->task }}</td>
									</tr>
								</table>
							</div>
	          			</div>

	          			<div class="row justify-content-center">
	          			@foreach($upgrade->product_services as $key => $product_service)
	          				@if($product_service['sparepart'] != null)
							<div class="table-responsive">
								<table class="col-md-12">
									<thead>
										<td colspan="5">Data Product Service {{$key + 1}}</td>
									</thead>
									<tr>
										<td>Product: </td>
										@if ($upgrade->acceptance['oldproduct_id'] != null)
											<td>{{ $upgrade->acceptance->oldproduct['code'] }} - {{ $upgrade->acceptance->oldproduct['name'] }}</td>
										@else
											<td>{{ $upgrade->acceptance['other_product'] }}</td>
										@endif
									</tr>
									
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
												$total_price = (int)$item->qty * $unit_price;
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
											$unit_price_two = $product_service->getSparepart($arr_sparepart[$i]->id)->id['price'];
											$total_price_two = (int)$arr_sparepart[$i]->qty * $unit_price_two;
										@endphp
										<tr>
											<td>{{$i+1}}</td>
											<td>{{$product_service->getSparepart($arr_sparepart[$i]->id)->id['name']}}</td>
											<td>{{$arr_sparepart[$i]->qty}}</td>
											<td>{{number_format($unit_price_two)}}</td>
											<td>{{number_format($total_price_two)}}</td>
										</tr>
									@endfor
									
								</table>
							</div>
	          				@endif
	          			@endforeach
	          			</div>
	          		</div>
	        	</div>
	      	</div>
	    </div>

        <?php if (strtolower($upgrade->status) === "repaired"): ?>
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>Upgrade Status</h2>
                            </div>
                            <div class="row justify-content-center">
                                <form id="actionAdd"
                                    class="forms-sample"
                                    method="POST"
                                    action="<?php echo route("update_upgrade_status"); ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden"
                                        name="id"
                                        value="<?php echo $upgrade->id; ?>" />
                                    <button id="upgradeProcess"
                                        type="submit"
                                        class="btn btn-gradient-primary btn-lg"
                                        name="status"
                                        value="Approved">
                                        Approved
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php elseif (strtolower($upgrade->status) === "approved"): ?>
            <div class="row">
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <h2>Upgrade Status</h2>
                            </div>
                            <div class="row justify-content-center">
                                <form id="actionAdd"
                                    class="forms-sample"
                                    method="POST"
                                    action="<?php echo route("update_upgrade_status"); ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden"
                                        name="id"
                                        value="<?php echo $upgrade->id; ?>" />
                                    <button id="upgradeProcess"
                                        type="submit"
                                        class="btn btn-gradient-primary btn-lg"
                                        name="status"
                                        value="Display">
                                        Display
                                    </button>
                                    <button id="upgradeProcess"
                                        type="submit"
                                        class="btn btn-gradient-success btn-lg"
                                        name="status"
                                        value="Ready">
                                        Ready
                                    </button>
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
