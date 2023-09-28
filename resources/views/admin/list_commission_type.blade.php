<?php
$menu_item_page = "commstype";
$menu_item_second = "list_commstype";
?>
@extends('admin.layouts.template')


@section('content')

<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
  			<h3 class="page-title">List Commision Type</h3>
  			<nav aria-label="breadcrumb">
    			<ol class="breadcrumb">
      				<li class="breadcrumb-item"><a data-toggle="collapse" href="#cso-dd" aria-expanded="false" aria-controls="cso-dd">CSO</a></li>
      				<li class="breadcrumb-item active" aria-current="page">List CSO</li>
    			</ol>
  			</nav>
		</div>

		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
					<div class="form-group">
						<label for="">Filter By Type</label>
						<select class="form-control" id="filter_commision_type" name="filter_commision_type">
							<option value="" selected="">All Type</option>
							<option value="upgrade" {{ isset($_GET['filter_commision_type']) ? $_GET['filter_commision_type'] == 'upgrade' ? 'selected=""' : '' : '' }} >Upgrade</option>
							<option value="takeaway" {{ isset($_GET['filter_commision_type']) ? $_GET['filter_commision_type'] == 'takeaway' ? 'selected=""' : '' : '' }} >Takeaway</option>
						</select>
						<div class="validation"></div>
					</div>
				</div>

				  <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
  					<div class="col-xs-6 col-sm-6" style="padding: 0;display: inline-block;">
  						<label for=""></label>
  						<div class="form-group">
  						<button id="btn-filter" type="button" class="btn btn-gradient-primary m-1" name="filter" value="-"><span class="mdi mdi-filter"></span> Apply Filter</button>
  					  </div>
  					</div>
				  </div>
			</div>

  			<div class="col-12 grid-margin stretch-card">
    			<div class="card">
      				<div class="card-body">
      					<h5 style="margin-bottom: 0.5em;">Total :  data</h5>
        				<div class="table-responsive" style="border: 1px solid #ebedf2;">
        					<table class="table table-bordered">
          						<thead>
						            <tr>
						              	<th> No. </th>
						              	<th> Name </th>
						              	<th> Description </th>
						              	<th colspan="2"> Edit / Delete </th>
						            </tr>
          						</thead>
          						<tbody>
									@foreach($datas as $index => $commtype)
										<tr>
											<td>{{$index+1}}</td>
											<td>{{$commtype->name}}</td>
											<td>
												<div>
													<div class="mb-1"><b>Hadiah :</b> {{$commtype->prize ? 'Yes' : 'No'}}</div>
													<div class="mb-1"><b>Takeaway :</b> {{$commtype->takeaway ? 'Yes' : 'No'}}</div>
													<div class="mb-1"><b>Upgrade :</b> {{$commtype->upgrade ? 'Yes' : 'No'}}</div>
													<div class="mb-1"><b>Nominal Bonus :</b> Rp {{number_format($commtype->nominal)}}</div><br>
													{{-- <div class="mb-1"><b>Semangat Nominal :</b> Rp {{number_format($commtype->smgt_nominal)}}</div><br> --}}
													<div class="mb-1"><b>Description :</b> {{$commtype->description}}</div>
												</div>
											</td>
          									@if(Gate::check('detail-commission_type') || Gate::check('edit-commission_type'))
											<td style="text-align: center;"><a href="{{route('edit_commission_type', $commtype->id)}}"><i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i></a></td>
											@endif
											@if(Gate::check('delete-commission_type'))
											<td style="text-align: center;"><a href="{{route('delete_commission_type', $commtype->id)}}" data-toggle="modal" data-target="#deleteDoModal" class="btnDelete"><i class="mdi mdi-delete" style="font-size: 24px; color:#fe7c96;"></i></a></td>
											@endif
										</tr>
									@endforeach
          						</tbody>
							</table>
							<br>
	                        {!! $datas->appends(\Request::except('page'))->render() !!}

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
              		<h5 style="text-align:center;">Are You Sure to Delete this Commision Type ?</h5>
            	</div>
            	<div class="modal-footer">
            		<form id="frmDelete" method="post" action="">
						{{ method_field('delete') }}
						{{csrf_field()}}
						@if(Gate::check('delete-commission_type'))
                    	<button type="submit" class="btn btn-gradient-danger mr-2">Yes</button>
						@endif
                	</form>
              		<button class="btn btn-light" data-dismiss="modal">No</button>
            	</div>
          	</div>
        </div>
    </div>
    <!-- End Modal Delete -->
</div>
@endsection

@section('script')

<script>
	$(document).on("click", "#btn-filter", function(e){
	  var urlParamArray = new Array();
	  var urlParamStr = "";
	  if($('#filter_commision_type').val() != ""){
		urlParamArray.push("filter_commision_type=" + $('#filter_commision_type').val());
	  }
	  for (var i = 0; i < urlParamArray.length; i++) {
		if (i === 0) {
		  urlParamStr += "?" + urlParamArray[i]
		} else {
		  urlParamStr += "&" + urlParamArray[i]
		}
	  }

	  window.location.href = "{{route('list_commission_type')}}" + urlParamStr;
	});
	$(document).on("click", ".btnDelete", function(e){
		$("#frmDelete").attr("action", $(this).attr('href'));
	});
</script>
@endsection
