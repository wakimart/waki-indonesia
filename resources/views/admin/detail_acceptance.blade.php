<?php
	$menu_item_page = "acceptance";
	$menu_item_second = "list_acceptance_form";
?>
@extends('admin.layouts.template')

@section('style')
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

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

    #regForm {
	  background-color: #ffffff;
	  margin: 100px auto;
	  padding: 40px;
	  width: 70%;
	  min-width: 300px;
	}

	/* Style the input fields */
	/*input {
	  padding: 10px;
	  width: 100%;
	  font-size: 17px;
	  font-family: Raleway;
	  border: 1px solid #aaaaaa;
	}*/

	/* Mark input boxes that gets an error on validation: */
	input.invalid {
	  background-color: #ffdddd;
	}

	/* Hide all steps by default: */
	.tab {
	  display: none;
	}

	/* Make circles that indicate the steps of the form: */
	.step {
	  height: 15px;
	  width: 15px;
	  margin: 0 2px;
	  background-color: #bbbbbb;
	  border: none;
	  border-radius: 50%;
	  display: inline-block;
	  opacity: 0.5;
	}

	/* Mark the active step: */
	.step.active {
	  opacity: 1;
	}

	/* Mark the steps that are finished and valid: */
	.step.finish {
	  background-color: #4CAF50;
	}

   .div-CheckboxGroup {
	  border:solid 1px rgba(128, 128, 128, 0.32941);
	  padding:0px 10px ;
	  border-radius:3px;
	}

	input[type='checkbox'], input[type='radio']{
		margin-left: 0px !important;
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
      		<h3 class="page-title">Detail Acceptance</h3>
      		<nav aria-label="breadcrumb">
	        	<ol class="breadcrumb">
	          		<li class="breadcrumb-item"><a data-toggle="collapse" href="#" aria-expanded="false" aria-controls="acceptance-dd">Acceptance</a></li>
	          		<li class="breadcrumb-item active" aria-current="page">Detail Acceptance</li>
	        	</ol>
      		</nav>
    	</div>

    	<div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">
	          			<div class="row justify-content-center">
	          				<h2>Detail Acceptance (Upgrade)</h2>
	          			</div>
	          			<div class="row justify-content-center">
	          				<table class="col-md-12">
	          					<thead>
	          						<td>Status</td>
	          						<td>Acceptance Code</td>
	          						<td>Acceptance Date</td>
	          					</thead>
	          					<tr>
	          						<td style="text-align: center;">
	          							@if(strtolower($acceptance['status']) == "new")
	                                        <span class="badge badge-primary">New</span>
	                                    @elseif(strtolower($acceptance['status']) == "approved")
	                                        <span class="badge badge-success">Approved by : {{ $acceptance->acceptanceLog[sizeof($acceptance->acceptanceLog)-1]->user['name'] }}</span>
	                                    @elseif(strtolower($acceptance['status']) == "rejected")
	                                        <span class="badge badge-danger">Rejected by : {{ $acceptance->acceptanceLog[sizeof($acceptance->acceptanceLog)-1]->user['name'] }}</span>
	                                    @endif
	          						</td>	          						
	          						<td>{{ $acceptance['code'] }}</td>
	          						<td>
	          							{{ date("d/m/Y", strtotime($acceptance['created_at'])) }}
	          						</td>
	          					</tr>
	          				</table>
	          			</div>
	          			<div class="row justify-content-center">
	          				<table class="col-md-12">
	          					<thead>
	          						<td colspan="2">Data Acceptance</td>
	          					</thead>
	          					<tr>
	          						<td>Upgrade Date : </td>
	          						<td>{{ date("d/m/Y", strtotime($acceptance['upgrade_date'])) }}</td>
	          					</tr>
	          					<tr>
	          						<td>Customer Name : </td>
	          						<td>{{ $acceptance['name'] }}</td>
	          					</tr>
	          					<tr>
	          						<td>Customer Phone : </td>
	          						<td>{{ $acceptance['phone'] }}</td>
	          					</tr>
	          					<tr>
	          						<td>Customer Address : </td>
	          						<td>
		          						{{ $acceptance['address'] }}
		          						<br>
		          						{{$acceptance->provinceObj['province']}}, {{$acceptance->cityObj['city_name']}}, {{$acceptance->districObj['subdistrict_name']}}
		          					</td>
	          					</tr>
	          					<tr>
	          						<td>Branch - CSO : </td>
	          						<td>{{ $acceptance->branch->code }} - {{ $acceptance->cso->code }} ({{ $acceptance->cso->name }})</td>
	          					</tr>
	          				</table>
	          			</div>
	          			<div class="row justify-content-center">
	          				<table class="col-md-12">
	          					<thead>
	          						<td colspan="2">Data Product</td>
	          					</thead>
	          					<tr>
	          						<td>New Product : </td>
	          						<td>{{ $acceptance->newproduct['code'] }} - {{ $acceptance->newproduct['name'] }}</td>
	          					</tr>
	          					<tr>
	          						<td>Old Product : </td>
	          						@if($acceptance['oldproduct_id'] != null)
		          						<td>{{ $acceptance->oldproduct['code'] }} - {{ $acceptance->oldproduct['name'] }}</td>
		          					@else
		          						<td>{{ $acceptance['other_product'] }}</td>
		          					@endif
	          					</tr>
	          					<tr>
	          						<td>Purchase Date : </td>
	          						<td>{{ date("d/m/Y", strtotime($acceptance['purchase_date'])) }}</td>
	          					</tr>
	          					<tr>
	          						<td>Request Price : </td>
	          						<td>{{ number_format($acceptance->request_price) }}</td>
	          					</tr>

	          					<tr>
	          						<td rowspan="5">Kelengkapan : </td>
	          						<td><i class="mdi {{ in_array("mesin", $acceptance['arr_condition']['kelengkapan']) ? "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" style="font-size: 24px; color: #fed713;"></i> Mesin
	          						</td>
	          					</tr>
	          					<tr>
	          						<td>
	          							<i class="mdi {{ in_array("filter", $acceptance['arr_condition']['kelengkapan']) ? "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" style="font-size: 24px; color: #fed713;"></i> Filter
	          						</td>
	          					</tr>
	          					<tr>
	          						<td>
	          							<i class="mdi {{ in_array("aksesoris", $acceptance['arr_condition']['kelengkapan']) ? "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" style="font-size: 24px; color: #fed713;"></i> Aksesoris
	          						</td>
	          					</tr>
	          					<tr>
	          						<td>
	          							<i class="mdi {{ in_array("kabel", $acceptance['arr_condition']['kelengkapan']) ? "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" style="font-size: 24px; color: #fed713;"></i> Kabel
	          						</td>
	          					</tr>
	          					<tr>
	          						<td>
	          							<i class="mdi {{ in_array("other", $acceptance['arr_condition']['kelengkapan']) ? "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" style="font-size: 24px; color: #fed713;"></i> Other : {{ isset($acceptance['arr_condition']['kelengkapan']['other']) ? $acceptance['arr_condition']['kelengkapan']['other'][0] : "-" }}
	          						</td></tr>
	          					<tr>
	          						<td>Kondisi Mesin : </td>
	          						<td>{{ ucwords($acceptance['arr_condition']['kondisi']) }}</td>
	          					</tr>
	          					<tr>
	          						<td>Tampilan : </td>
	          						<td>{{ ucwords($acceptance['arr_condition']['tampilan']) }}</td>
	          					</tr>
	          					<tr>
	          						<td>Description : </td>
	          						<td>{{ $acceptance['description']}}</td>
	          					</tr>
	          					<tr>
	          						<td>Photo : </td>
	          						<td>
	          							@foreach($acceptance['image'] as $imgAcc)
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

		@if(strtolower($acceptance['status']) == "new" && Gate::check('change-status-approval-acceptance') && Gate::check('change-status-reject-acceptance'))
		    <div class="row">
		      	<div class="col-12 grid-margin stretch-card">
		        	<div class="card">
		          		<div class="card-body">	          			
		          			<div class="row justify-content-center">
		          				<h2>Status Acceptance</h2>
		          			</div>
		            		<form id="actionAdd" class="forms-sample" method="POST" action="{{ route('update_acceptance_form') }}">
		            			{{ csrf_field() }}
		            			<input type="text" name="id" value="{{ $acceptance['id'] }}" hidden="">
		              			<div class="form-group row justify-content-center">
		              				<button id="upgradeProcess" type="submit" class="btn btn-gradient-primary mr-2 btn-lg" name="status" value="approved">Approved</button>
		              				<button id="upgradeProcess" type="submit" class="btn btn-gradient-danger mr-2 btn-lg" name="status" value="rejected">Reject</button>
		              			</div>
		            		</form>
		          		</div>
		        	</div>
		      	</div>
		    </div>
		@else
			<div class="row">
		      	<div class="col-12 grid-margin stretch-card">
		        	<div class="card">
		          		<div class="card-body">	          			
		          			<div class="row justify-content-center">
		          				<h2>Share Acceptance</h2>
		          			</div>
		            		<form class="forms-sample" method="GET" action="whatsapp://send">
		              			<div class="form-group row justify-content-center">
		              				<button id="upgradeProcess" type="submit" class="btn btn-gradient-primary mr-2 btn-lg" name="text" value="Request Acc {{ route('detail_acceptance_form' ,['id' => $acceptance['id']]) }}">Share Whatsapp</button>
		              			</div>
		            		</form>
		          		</div>
		        	</div>
		      	</div>
		    </div>
		@endif

		@if(sizeof($historyUpdate) > 0)
			<div class="row">
		      	<div class="col-12 grid-margin stretch-card">
		        	<div class="card">
		          		<div class="card-body">	          			
		          			<div class="row justify-content-center">
		          				<h2>Acceptance History Log</h2>
		          			</div>
		            		<div class="row justify-content-center">
		          				<table class="col-md-12">
		          					<thead>
		          						<td>No.</td>
		          						<td>Action</td>
		          						<td>User</td>
		          						<td>Change</td>
		          						<td>Time</td>
		          					</thead>
		          					@foreach($historyUpdate as $key => $historyNya)
			          					<tr>
			          						<td>{{$key+1}}</td>
			          						<td>{{$historyNya->method}}</td>
			          						<td>{{$historyNya->user['name']}}</td>
			          						<?php $dataChange = json_decode($historyNya->meta, true);?>
			          						<td>
			          							@foreach ($dataChange['dataChange'] as $key=>$value)
				          							<b>{{$key}}</b>: {{$value}}<br/>
			          							@endforeach
			          						</td>
			          						<td>{{ date("d/m/Y H:i:s", strtotime($historyNya->created_at)) }}</td>
			          					</tr>
		          					@endforeach
		          				</table>
		          			</div>
		          		</div>
		        	</div>
		      	</div>
		    </div>
	    @endif
	</div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script type="text/javascript" src="{{ asset('js/tags-input.js') }}"></script>
@endsection
