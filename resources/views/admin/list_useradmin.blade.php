<?php
    $menu_item_page = "user";
    $menu_item_second = "list_user";
?>
@extends('admin.layouts.template')

@section('content')
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
			<h3 class="page-title">List Admin</h3>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a data-toggle="collapse" href="#admin-dd" aria-expanded="false" aria-controls="admin-dd">Admin</a></li>
					<li class="breadcrumb-item active" aria-current="page">List Admin</li>
				</ol>
			</nav>
		</div>

		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
					<div class="card-body">

						<div class="table-responsive" style="border: 1px solid #ebedf2;">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th> # </th>
										<th> Code </th>
										<th> Name </th>
										<th> Username </th>
										<th> Registered Date </th>
										<th colspan="2"> Edit / Delete </th>
									</tr>
								</thead>
								<tbody>
									@foreach($users as $key => $user)
									<tr>
										<td> {{$key+1}} </td>
										<td> {{$user['code']}} </td>
										<td> {{$user['name']}} </td>
										<td> {{$user['username']}} </td>
										<td> {{$user['created_at']}} </td>
										<td style="text-align: center;"><a href="{{ route('edit_useradmin', ['id' => $user['id']])}}"><i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i></a></td>
                      					<td style="text-align: center;"><a href="{{ route('delete_useradmin', ['id' => $user['id']])}}" data-toggle="modal" data-target="#deleteDoModal" class="btnDelete"><i class="mdi mdi-delete" style="font-size: 24px; color:#fe7c96;"></i></a></td>
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
</div>
@endsection
