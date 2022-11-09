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
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#admin-dd"
                            aria-expanded="false"
                            aria-controls="admin-dd">
                            Admin
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        List Admin
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <form method="GET"
                    class="col-12"
                    action="{{ route("list_useradmin") }}">
                    <div class="col-xs-6 col-sm-3"
                        style="padding: 0; display: inline-block;">
                        <div class="form-group">
                            <label for="search-name">Search by name</label>
                            <input class="form-control"
                                id="search-name"
                                name="name"
                                <?php
                                if (isset($_GET["name"])) {
                                    echo 'value="' . $_GET["name"] . '"';
                                }
                                ?>
                                placeholder="Name" />
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-3"
                        style="padding: 0; display: inline-block;">
                        <div class="form-group">
                            <label for="search-username">
                                Search by username
                            </label>
                            <input class="form-control"
                                id="search-username"
                                name="username"
                                <?php
                                if (isset($_GET["username"])) {
                                    echo 'value="' . $_GET["username"] . '"';
                                }
                                ?>
                                placeholder="Username" />
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6"
                        style="padding: 0; display: inline-block;">
                        <div class="form-group">
                            <button id="btn-filter"
                                type="submit"
                                class="btn btn-gradient-primary m-1">
                                <span class="mdi mdi-magnify"></span> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive" style="border: 1px solid #ebedf2;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Registered Date</th>
                                        <th colspan="2">Edit / Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $key => $user)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $user['code'] }}</td>
                                            <td>{{ $user['name'] }}</td>
                                            <td>{{ $user['username'] }}</td>
                                            <td>{{ $user['created_at'] }}</td>
                                            <td style="text-align: center;">
                                                <a href="{{ route('edit_useradmin', ['id' => $user['id']]) }}">
                                                    <i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
                                                </a>
                                            </td>
                                            <td style="text-align: center;">
                                                <a class="btn-delete disabled"
                                                    data-toggle="modal"
                                                    href="#deleteDoModal"
                                                    value="{{ route('delete_useradmin', ['id' => $user->id]) }}">
                                                    <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                    @endforeach
                                </tbody>
                            </table>
                            <br/>
                            {{ $users->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
              		<h5 style="text-align:center;">Are You Sure to Delete this Admin ?</h5>
            	</div>
            	<div class="modal-footer">
            		<form id="frmDelete" method="post" action="">
                        @csrf
                    	<button type="submit" class="btn btn-gradient-danger mr-2">Yes</button>
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
$(document).ready(function() {
    $(".btn-delete").click(function (e) {
        $("#frmDelete").attr("action",  $(this).attr('value'));
    });
});
</script>
@endsection
