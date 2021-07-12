<?php
$menu_item_page = "service";
$menu_item_second = "list_service";
?>

@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    /*-- mobile --*/
	@media (max-width: 768px){
		#desktop{
			display: none;
		}

		#mobile{
			display: block;
		}

		#mobile .filter{
			padding-top: 15px;
		}
	}

	@media (min-width: 768px){
		#desktop{
			display: block;
		}

		#mobile{
			display: none;
		}
	}

	@media (min-width: 410px){
		#desktop{
			display: none;
		}

		#mobile{
			display: block;
		}

		#mobile .filter{
			padding-top: 0;
		}
	}
</style>
@endsection

@section('content')
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
  			<h3 class="page-title">List Service</h3>
  			<nav aria-label="breadcrumb">
    			<ol class="breadcrumb">
      				<li class="breadcrumb-item"><a data-toggle="collapse" href="#service-dd" aria-expanded="false" aria-controls="service-dd">Service</a></li>
      				<li class="breadcrumb-item active" aria-current="page">List Service</li>
    			</ol>
  			</nav>
		</div>

		<div id="desktop" class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
                    <div class="form-group">
						<label for="">Search By Name, Code, and Phone</label>
                        <input class="form-control" id="search" name="search" placeholder="Search By Name and Code">
                        <div class="validation"></div>
                    </div>
				</div>

				<div class="col-xs-6 col-sm-12 row" style="margin: 0;padding: 0;">
					<div class="col-xs-6 col-sm-6" style="padding: 0;display: inline-block;">
						<label for=""></label>
						<div class="form-group">
							<button id="btn-filter" type="button" class="btn btn-gradient-primary m-1" name="filter" value="-"><span class="mdi mdi-filter"></span> Apply Filter</button>
					  	</div>
					</div>
				</div>
			</div>
		</div>

		<div id="mobile" class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="col-xs-12">
                    <div class="form-group">
						<label for="">Search By Name, Code, and Phone</label>
                        <input class="form-control" id="search" name="search" placeholder="Search By Name and Code">
                        <div class="validation"></div>
                    </div>
				</div>
				<div class="col-xs-12 filter">
					<label for=""></label>
						<div class="form-group">
							<button id="btn-filter" type="button" class="btn btn-gradient-primary m-1" style="font-size: 0.8em; padding: 1.1em;" name="filter" value="-"><span class="mdi mdi-filter"></span> Apply Filter</button>
						</div>
				</div>
			</div>
		</div>

		<div class="col-12 grid-margin stretch-card" style="padding: 0;">
			<div class="card">
	  			<div class="card-body">
					<h5 style="margin-bottom: 0.5em;">Total : {{$countServices}} data</h5>
	    			<div class="table-responsive" style="border: 1px solid #ebedf2;">
	    				<table class="table table-bordered">
				     		<thead>
				            	<tr>
                                    <th> Service Date </th>
					              	<th> Name </th>
					              	<th> Address </th>
					              	<th> Phone </th>
					              	<th> Status </th>
					              	@if(Gate::check('edit-order') || Gate::check('delete-order'))
						              	<th colspan="3"> Detail/Edit/Delete </th>
						            @endif
				            	</tr>
							</thead>
							<tbody>
								@foreach($services as $key => $service)
								<tr>
									<td>{{$service['service_date']}}</td>
									<td>{{$service['name']}}</td>
									<td>{{$service['address']}}</td>
									<td>{{$service['phone']}}</td>
									<td>{{$service['status']}}</td>

									@can('detail-service')
									<td style="text-align: center;">
                                        <a href="{{ route('detail_service' ,['id' => $service['id']]) }}">
                                            <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(76 172 245);"></i>
                                        </a>
                                    </td>
									@endcan
									@can('edit-service')
		                            <td style="text-align: center;">
		                            	<a href="{{route('edit_service', ['id' => $service['id']])}}">
		                            		<i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
		                            	</a>
		                            </td>
		                            @endcan
		                            @can('delete-service')
                      				<td style="text-align: center;">
                      					<a class="btn-delete"
                                            data-toggle="modal"
                                            href="#deleteDoModal"
                                            onclick="submitDelete(this)"
                                            data-id="<?php echo $service['id']; ?>">
                                            <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                        </a>
                      				</td>
                      				@endcan
								</tr>
								@endforeach
							</tbody>
						</table>
						<br>
						{{ $services->appends($url)->links() }}
	    			</div>
	  			</div>
			 </div>
		</div>

	</div>
</div>

<div class="modal fade"
    id="deleteDoModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 style="text-align:center;">
                    Are you sure you want to delete this?
                </h5>
            </div>
            <div class="modal-footer">
                <form id="frmDelete"
                    method="POST"
                    action="<?php echo route("delete_service"); ?>">
                    @csrf
                    <input type="hidden" name="id" id="id-delete" />
                    <button type="submit" class="btn btn-gradient-danger mr-2">
                        Yes
                    </button>
                </form>
                <button class="btn btn-light">No</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
function submitDelete(e) {
    document.getElementById("id-delete").value = e.dataset.id;
}
</script>
@endsection
