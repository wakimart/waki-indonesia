<?php
    $menu_item_page = "upgrade";
    $menu_item_second = "new_upgrade_form";
?>
@extends('admin.layouts.template')

@section('style')
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

<style type="text/css">
    #intro {padding-top: 2em;}
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
  	input.invalid { background-color: #ffdddd; }
  	.tab { display: none; }
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
  	.step.active { opacity: 1; }
  	.step.finish { background-color: #4CAF50; }
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
    .center { text-align: center; }
    .right { text-align: right; }
    .justify-content-center{ padding: 0em 1em; }
</style>
@endsection

@section('content')
<div class="main-panel">
  	<div class="content-wrapper">
    	<div class="page-header">
      		<h3 class="page-title">Add Upgrade</h3>
      		<nav aria-label="breadcrumb">
	        	<ol class="breadcrumb">
	          		<li class="breadcrumb-item"><a data-toggle="collapse" href="#" aria-expanded="false" aria-controls="upgrade-dd">Upgrade</a></li>
	          		<li class="breadcrumb-item active" aria-current="page">Add Upgrade</li>
	        	</ol>
      		</nav>
    	</div>

    	<div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">
	          			<div class="row justify-content-center">
	          				<h2>Detail Upgrade</h2>
	          			</div>
	          			<div class="row justify-content-center">
	          				<table class="col-md-12">
	          					<thead>
	          						<td>Acc By</td>
	          						<td>Acc Code</td>
	          						<td>Upgrade Date</td>
									<td>Bill DO</td>
	          					</thead>
	          					<tr>
	          						<td style="text-align: center;"><span class="badge badge-success">Approved by : {{ $upgrade->acceptance->acceptanceLog[sizeof($upgrade->acceptance->acceptanceLog)-1]->user['name'] }}</span></td>
	          						<td class="center">{{ $upgrade->acceptance['code'] }}</td>
	          						<td class="center">
	          							{{ date("d/m/Y", strtotime($upgrade->acceptance['upgrade_date'])) }}
	          						</td>
									<td class="center">
										{{ $upgrade->acceptance->bill_do }}
									</td>
	          					</tr>
	          				</table>
	          			</div>
	          			<div class="row justify-content-center">
	          				<table class="col-md-12">
	          					<thead>
	          						<td colspan="2">Data Upgrade</td>
	          					</thead>
	          					<tr>
	          						<td>Customer Name : </td>
	          						<td>{{ $upgrade->acceptance['name'] }}</td>
	          					</tr>
	          					<tr>
	          						<td>Customer Phone : </td>
	          						<td>{{ $upgrade->acceptance['phone'] }}</td>
	          					</tr>
	          					<tr>
	          						<td>Branch : </td>
	          						<td>{{ $upgrade->acceptance->branch->code }} - {{ $upgrade->acceptance->branch->name }}</td>
	          					</tr>
	          					<tr>
	          						<td>CSO : </td>
	          						<td>{{ $upgrade->acceptance->cso->code }} - {{ $upgrade->acceptance->cso->name }}</td>
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
	          						<td>{{ $upgrade->acceptance->newproduct['code'] }} - {{ $upgrade->acceptance->newproduct['name'] }}</td>
	          					</tr>
	          					<tr>
	          						<td>Old Product : </td>
	          						@if($upgrade->acceptance['oldproduct_id'] != null)
		          						<td>{{ $upgrade->acceptance->oldproduct['code'] }} - {{ $upgrade->acceptance->oldproduct['name'] }}</td>
		          					@else
		          						<td>{{ $upgrade->acceptance['other_product'] }}</td>
		          					@endif
	          					</tr>
	          					<tr>
	          						<td>Purchase Date : </td>
	          						<td>{{ date("d/m/Y", strtotime($upgrade->acceptance['purchase_date'])) }}</td>
	          					</tr>

	          					<tr>
	          						<td rowspan="5">Kelengkapan : </td>
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
	          							<i class="mdi {{ in_array("other", $upgrade->acceptance['arr_condition']['kelengkapan']) ? "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" style="font-size: 24px; color: #fed713;"></i> Other : {{ isset($upgrade->acceptance['arr_condition']['kelengkapan']['other']) ? $upgrade->acceptance['arr_condition']['kelengkapan']['other'][0] : "-" }}
	          						</td></tr>
	          					<tr>
	          						<td>Kondisi Mesin : </td>
	          						<td>{{ ucwords($upgrade->acceptance['arr_condition']['kondisi']) }}</td>
	          					</tr>
	          					<tr>
	          						<td>Tampilan : </td>
	          						<td>{{ ucwords($upgrade->acceptance['arr_condition']['tampilan']) }}</td>
	          					</tr>
	          					<tr>
	          						<td>Description : </td>
	          						<td>{{ $upgrade->acceptance['description']}}</td>
	          					</tr>
	          					<tr>
	          						<td>Photo : </td>
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

	    <div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">
	            		<form id="actionAdd" class="forms-sample" method="POST" action="{{ route('store_upgrade_form') }}">
	            			{{ csrf_field() }}
	              			<div class="form-group">
	              				<label for="">Due Date</label>
	              				<input required="" type="date" class="form-control" name="due_date" id="due_date" placeholder="Due Date" value="<?php echo date('Y-m-j'); ?>" data-msg="Mohon Isi Tanggal"/>
	              				<div class="validation"></div>
	              			</div>
							<div class="form-group">
								<label for="">Area</label>
								<select required="" name="area" id="area" class="form-control">
									<option value="">Choose Area</option>
									@foreach (App\Acceptance::$Area as $area)
									@if($area != 'null')
									<option value="{{ $area }}">{{ ucwords($area) }}</option>
									@endif
									@endforeach
								</select>
							</div>
			                <div class="form-group">
			                	<label for="">Task</label>
			                    <textarea required="" class="form-control" id="task" name="task" rows="10" data-msg="Mohon Isi Task" placeholder="Task"></textarea>
			                    <div class="validation"></div>
			                </div>

	              			<div class="form-group">
								<input type="hidden" name="upgrade_id" value="{{ $upgrade->id }}">
								<input type="hidden" name="is_it_direct_upgrade" id="is_it_direct_upgrade" value="no">
	              				<button id="upgradeProcess" type="submit" class="btn btn-gradient-primary mr-2">Process</button>
								<button class="btn btn-gradient-info" type="button" id="directUpgradeButton">Direct Upgrade</button>
	              			</div>
	            		</form>

	          		</div>
	        	</div>
	      	</div>
	    </div>
	</div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script type="text/javascript" src="{{ asset('js/tags-input.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#directUpgradeButton").click(function(){
			$("#task").prop('required',false);
			$("#is_it_direct_upgrade").val('yes');
			if($("#area").val() !== ''){
				$('#actionAdd').submit();
			}else{
				alert('Please choose area first!')
			}
		}); 
	});
</script>
@endsection
