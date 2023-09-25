<?php
$menu_item_page = "user";
$menu_item_second = "list_user";
?>
@extends('admin.layouts.template')
@section('style')
<style>
    .btn-outline-danger.disabled, .btn-outline-danger:disabled:hover {
        color: #fe7c96 !important;
        background: transparent !important;
    }
</style>
@endsection

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
                        <div class="d-flex flex-wrap" style="justify-content: right;">
                            <button type="button" id="deleteUserAll" class="btn btn-md btn-outline-danger" disabled>Delete All</button>
                        </div>
                        <div class="table-responsive" style="border: 1px solid #ebedf2;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            <input type="checkbox" value="true"
                                                class="checkboxUserAll"
                                                autocomplete="off"
                                                style="position: relative; width: 16px; margin: auto;"/>
                                        </th>
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
                                            <td class="text-center">
                                                <input type="checkbox" name=""
                                                    class="checkboxUser"
                                                    value="{{$user['id']}}"
                                                    autocomplete="off"
                                                    style="position: relative; width: 16px; margin: auto;"/>
                                            </td>
                                            <td>{{ $i }}</td>
                                            <td id="user_code_{{$user['id']}}">{{ $user['code'] }}</td>
                                            <td id="user_name_{{$user['id']}}">{{ $user['name'] }}</td>
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
	<div class="modal fade" id="deleteMultipleModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          	<div class="modal-content">
            	<div class="modal-header">
              		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                		<span aria-hidden="true">&times;</span>
              		</button>
            	</div>
                <form id="frmDeleteMultiple" method="post" action="{{ route("delete_multiple") }}">
                    @csrf
                    <div class="modal-body">
                        <h5 style="text-align:center;">Are You Sure to Delete this Admin ?</h5>
                        <div id="deleteMultipleAdmin"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-gradient-danger mr-2">Yes</button>
                        <button class="btn btn-light" data-dismiss="modal">No</button>
                    </div>
                </form>
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

    $(".checkboxUserAll:checkbox").click(function() {
        $(".checkboxUser:checkbox").prop("checked", this.checked);
        $("#deleteUserAll").prop("disabled", !this.checked);
    });
    $(".checkboxUser:checkbox").click(function() {
        const lengthChecked = $(".checkboxUser:checkbox:checked").length;
        $(".checkboxUserAll:checkbox").prop("checked", lengthChecked == $(".checkboxUser:checkbox").length);
        $("#deleteUserAll").prop("disabled", lengthChecked < 2);
    });
    $("#deleteUserAll").click(function() {
        $("#deleteMultipleAdmin").html("");
        $(".checkboxUser:checkbox:checked").each(function() {
            const user_id = $(this).val();
            $("#deleteMultipleAdmin").append("<div>"+$("#user_code_"+user_id).html()+" - "+$("#user_name_"+user_id).html()+"</div>");
            $("#deleteMultipleAdmin").append(`<input type="hidden" name="user_ids[]" value="${user_id}">`);
        });
        $("#deleteMultipleModal").modal("show");
    })
});
</script>
@endsection
