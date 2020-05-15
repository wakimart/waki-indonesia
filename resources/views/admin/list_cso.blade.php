<?php
    $menu_item_page = "cso";
    $menu_item_second = "list_cso";
?>
@extends('admin.layouts.template')


@section('content')

<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
  			<h3 class="page-title">List CSO</h3>
  			<nav aria-label="breadcrumb">
    			<ol class="breadcrumb">
      				<li class="breadcrumb-item"><a data-toggle="collapse" href="#cso-dd" aria-expanded="false" aria-controls="cso-dd">CSO</a></li>
      				<li class="breadcrumb-item active" aria-current="page">List CSO</li>
    			</ol>
  			</nav>
		</div>

		<div class="row">
  			<div class="col-12 grid-margin stretch-card">
    			<div class="card">
      				<div class="card-body">
      					<h5 style="margin-bottom: 0.5em;">Total : {{ sizeof($csos) }} data</h5>
        				<div class="table-responsive" style="border: 1px solid #ebedf2;">
        					<table class="table table-bordered">
          						<thead>
						            <tr>
						              	<th> No. </th>
						              	<th> Code </th>
						              	<th> Name </th>
						              	<th> Branch </th>
						              	<th colspan="2"> Edit / Delete </th>
						            </tr>
          						</thead>
          						<tbody>
          							@foreach($csos as $key => $cso)
				                        <tr>
				                        	<td>{{$key+1}}</td>
				                            <td>{{$cso['code']}}</td>
				                            <td>{{$cso['name']}}</td>
				                            <td>{{$cso->branch['name']}}</td>
				                            <td style="text-align: center;"><a href="{{ route('edit_cso', ['id' => $cso['id']])}}"><i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i></a></td>
                          					<td style="text-align: center;"><a href="{{ route('delete_cso', ['id' => $cso['id']])}}" data-toggle="modal" data-target="#deleteDoModal" class="btnDelete"><i class="mdi mdi-delete" style="font-size: 24px; color:#fe7c96;"></i></a></td>
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

@section('script')
@endsection
