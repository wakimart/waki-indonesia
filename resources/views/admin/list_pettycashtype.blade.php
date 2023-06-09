<?php
$menu_item_page = "petty_cash_type";
$menu_item_second = "list_petty_cash_type";
?>
@extends("admin.layouts.template")

@section("content")
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Petty Cash Type List</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#"
                            aria-expanded="false">
                            Petty Cash Type
                        </a>
                    </li>
                    <li class="breadcrumb-item active"
                        aria-current="page">
                        Petty Cash Type List
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
                    <div class="form-group">
                        <label for="filter_string">
                            Filter by Code, Name
                        </label>
                        <input type="text"
                            class="form-control"
                            placeholder="Code, Name or Account Numebr"
                            value="{{ $_GET["filter_string"] ?? "" }}"
                            id="filter_string"
                            name="filter_string">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
                    <div class="col-xs-6 col-sm-6" style="display: inline-block;">
                        <div class="form-group">
                            <button id="btn-filter" type="button" class="btn btn-gradient-primary m-1"><span class="mdi mdi-filter"></span> Apply Filter</button>
                            <a href="{{ route('list_petty_cash_type') }}"
                                class="btn btn-gradient-danger m-1"
                                value="-">
                                <span class="mdi mdi-filter"></span> Reset Filter
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-12 grid-margin stretch-card" style="padding: 0;">
            <div class="card">
                <div class="card-body">
                    <h5 style="margin-bottom: 0.5em;">
                        Total: {{ $pettyCashTypes->total() }} data
                    </h5>
                    <div class="table-responsive"
                        style="border: 1px solid #ebedf2;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-right">No.</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Max Nominal</th>
                                    <th class="text-center" colspan="3">Edit/Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pettyCashTypes as $pettyCashType)
                                    <tr>
                                        <td class="text-right">{{ $loop->iteration + $pettyCashTypes->firstItem() - 1 }}</td>
                                        <td>{{ $pettyCashType->code }}</td>
                                        <td>{{ $pettyCashType->name }}</td>
                                        <td>{{ $pettyCashType->max ? number_format($pettyCashType->max) : '-' }}</td>
                                        @can('edit-petty_cash_type')
                                        <td class="text-center">
                                            <a href="{{ route("edit_petty_cash_type", ["id" => $pettyCashType->id]) }}">
                                                <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                            </a>
                                        </td>
                                        @endcan
                                        @can('delete-petty_cash_type')
                                        <td class="text-center">
                                            <a class="btn-delete"
                                                data-toggle="modal"
                                                href="#delete-modal"
                                                onclick="submitDelete(this)"
                                                data-id="{{ $pettyCashType->id }}">
                                                <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                            </a>
                                        </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{ $pettyCashTypes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade"
    id="delete-modal"
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
                    Are you sure you want to delete this petty cash type?
                </h5>
            </div>
            <div class="modal-footer">
                <form method="POST"
                    action="{{ route("delete_petty_cash_type") }}">
                    @csrf
                    <input type="hidden" name="id" id="id-delete" />
                    <button type="submit" class="btn btn-gradient-danger">
                        Yes
                    </button>
                </form>
                <button class="btn btn-light" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).on("click", "#btn-filter", function(e){
	  var urlParamArray = new Array();
	  var urlParamStr = "";
	  if($('#filter_string').length && $('#filter_string').val() != ""){
		urlParamArray.push("filter_string=" + $('#filter_string').val());
	  }
	  for (var i = 0; i < urlParamArray.length; i++) {
		if (i === 0) {
		  urlParamStr += "?" + urlParamArray[i]
		} else {
		  urlParamStr += "&" + urlParamArray[i]
		}
	  }

	  window.location.href = "{{route('list_petty_cash_type')}}" + urlParamStr;
	});
    function submitDelete(e) {
        document.getElementById("id-delete").value = e.dataset.id;
    }
</script>
@endsection
