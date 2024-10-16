<?php
$menu_item_page = "upgrade";
$menu_item_second = "list_upgrade_form";
?>
@extends('admin.layouts.template')
@section('style')
<style type="text/css">
.nav > li > a {
      position: relative;
      display: block;
      padding: 15px 20px;
    }
    .nav-tabs {
      border-bottom: 1px solid #ddd;
      background: #f2f3f2;
      border: 0;
      margin: 0 auto;
      padding: 0px 20px;
    }
    .nav-tabs > li {
      float: left;
      margin-bottom: -1px;
    }
    .nav-tabs > li > a {
      margin-right: 2px;
      line-height: 1.42857143;
      border: 1px solid transparent;
      border-radius: 4px 4px 0 0;
    }
    .nav-tabs > li > a:hover {
      border-color: #eee #eee #ddd;
    }
    .nav-tabs > li.active > a,
    .nav-tabs > li.active > a:hover,
    .nav-tabs > li.active > a:focus {
      color: #555;
      cursor: default;
      background-color: #fff;
      border-bottom-color: transparent;
      border: 0;
      padding: 15px 20px;
    }
    .nav-tabs.nav-justified {
      width: 100%;
      border-bottom: 0;
    }
    .nav-tabs.nav-justified > li {
      float: none;
    }
    .nav-tabs.nav-justified > li > a {
      margin-bottom: 5px;
      text-align: center;
    }
    .nav-tabs.nav-justified > .dropdown .dropdown-menu {
      top: auto;
      left: auto;
    }
    .nav-tabs li a:hover {background: #fff;}
    .nav-tabs li.active a {color: #30a5ff;}
    .nav-tabs li a {color: #999;}

    /*-- mobile --*/
		@media (max-width: 768px){
        #desktop{display: none;}
        #mobile{display: block;}
    }
    @media (min-width: 768px) {
        #desktop{display: block;}
        #mobile{display: none;}
    }
</style>
@endsection
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
         <!-- header mobile -->
		<div id="mobile">
			<h3 class="text-center">Upgrade List</h3>
			<div class="row">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a data-toggle="collapse" href="#" aria-expanded="false" aria-controls="upgrade-dd">Upgrade</a></li>
						<li class="breadcrumb-item active" aria-current="page">Upgrade List</li>
					</ol>
				</nav>
		  	</div>
	  	</div>

		<!-- header desktop -->
        <div id="desktop">
            <div class="page-header">
                <h3 class="page-title">Upgrade List</h3>
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
                            Upgrade List
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">

            <div class="col-12" style="margin-bottom: 0;">
                <div class="col-xs-6 col-sm-3"
                    style="padding: 0;display: inline-block;">
                    <div class="form-group">
                        <label for="filter_branch">
                            Filter By Team
                        </label>
                        <select class="form-control"
                            id="filter_branch"
                            name="filter_branch">
                            <option value="" selected="">
                                All Branch
                            </option>
                            @foreach ($branches as $branch)
                                @php
                                $selected = "";

                                if (isset($_GET['filter_branch'])) {
                                    if ((int) $_GET['filter_branch'] === (int) $branch['id']) {
                                        $selected = "selected";
                                    }
                                }
                                @endphp

                                <option {{ $selected }}
                                    value="{{ $branch['id'] }}">
                                    {{ $branch['code'] }} - {{ $branch['name'] }}
                                </option>
                            @endforeach
                        </select>
                        <div class="validation"></div>
                    </div>
                </div>

                <div class="col-xs-6 col-sm-4" style="margin-bottom: 0; padding: 0; display: inline-block">
                    <div class="form-group">
                        <label for="filter_cso">
                            Filter By CSO
                        </label>
                        <input name="filter_cso"
                            id="filter_cso"
                            list="data_cso"
                            class="text-uppercase form-control"
                            placeholder="Search CSO"
                            required />
                        <span class="invalid-feedback">
                            <strong></strong>
                        </span>

                        <datalist id="data_cso">
                            <select class="form-control">
                                <option value="All CSO"></option>
                                @foreach ($csos as $cso)
                                    <option value="{{ $cso['code'] }}-{{ $cso['name'] }}"></option>
                                @endforeach
                            </select>
                        </datalist>
                        <div class="validation"></div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6" style="padding: 0; display: inline-block">
                    <label for=""></label>
                    <div class="form-group">
                        <button id="btn-filter" type="button" class="btn btn-gradient-primary m-1" name="filter" value="-"><span class="mdi mdi-filter"></span> Apply Filter</button>
                    </div>
                </div>
            </div>

            <div class="form-group col-12">
                <ul class="nav nav-tabs" style="width: 100%;">
                    @php $tabActive = request()->query('tabActive') ?? App\Acceptance::$Area['0'] @endphp
                    @foreach ($upgradeAreas as $keyArea => $upgrades)
                    <li class="nav-pt 
                        @if ($tabActive == $keyArea) active @endif">
                        <a data-toggle="tab" href="#tab_{{ $keyArea }}">
                            {{ ($keyArea == 'null') ? "No Area" : ucwords($keyArea) }} ({{ $upgrades->total() }})
                        </a>
                    </li>
                    @endforeach
                </ul>

                <div class="tab-content" name="list_tab">
                    @foreach ($upgradeAreas as $keyArea => $upgrades)
                    <div id="tab_{{ $keyArea }}" class="tab-pane fade in @if ($tabActive == $keyArea) active show @endif" style="overflow-x:auto;">
                        <div class="col-12 grid-margin stretch-card" style="padding: 0;">
                            <div class="card">
                                <div class="card-body">
                                    <h5 style="margin-bottom: 0.5em;">
                                        Total: {{ $upgrades->total() }} data
                                    </h5>
                                    <div class="table-responsive"
                                        style="border: 1px solid #ebedf2;">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Upgrade Date</th>
                                                    <th>Member Name</th>
                                                    <th>Upgrade Product</th>
                                                    <th>Branch - CSO</th>
                                                    <th>Status</th>
                                                    {{-- @if(Gate::check('edit-deliveryorder') || Gate::check('delete-deliveryorder')) --}}
                                                        <th colspan="3">Detail/Edit/Delete</th>
                                                    {{-- @endif --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($upgrades as $key => $upgrade)
                                                    <tr>
                                                        <td>{{ $key+ $upgrades->firstItem() }}</td>
                                                        <td>
                                                            {{ date("d/m/Y", strtotime($upgrade->acceptance->upgrade_date)) }}
                                                        </td>
                                                        <td>
                                                            {{ $upgrade->acceptance->name }}
                                                        </td>
                                                        <td>
                                                            {{ $upgrade->acceptance['other_product'] == null ? $upgrade->acceptance->oldproduct['code'] : $upgrade->acceptance['other_product'] }} <i class="mdi mdi-arrow-right-bold" style="font-size: 18px; color: #fed713;"></i> {{ $upgrade->acceptance->newproduct['code'] }}
                                                        </td>
                                                        <td>
                                                            {{ $upgrade->acceptance->branch->code }} <br> {{ $upgrade->acceptance->cso->code }} - {{ $upgrade->acceptance->cso->name }}
                                                        </td>
                                                        <td>
                                                            @if(strtolower($upgrade['status']) == "new")
                                                                <span class="badge badge-secondary">New</span>
                                                            @elseif(strtolower($upgrade['status']) == "process")
                                                                <span class="badge badge-primary">Process by : {{ $upgrade->statusBy("process")['user_id']['name'] }}</span>
                                                            @elseif(strtolower($upgrade['status']) == "repaired")
                                                                <span class="badge badge-warning">Repaired by : {{ $upgrade->statusBy("repaired")['user_id']['name'] }}</span>
                                                            @elseif(strtolower($upgrade['status']) == "approved")
                                                                <span class="badge badge-info">Approved by : {{ $upgrade->statusBy("approved")['user_id']['name'] }}</span>
                                                            @elseif(strtolower($upgrade['status']) == "completed")
                                                                <span class="badge badge-Success">Completed by : {{ $upgrade->statusBy("completed")['user_id']['name'] }}</span>
                                                            @endif
                                                        </td>
                                                        {{-- @can('edit-deliveryorder') --}}
                                                            <td style="text-align: center;">
                                                                <a href="{{ route('detail_upgrade_form' ,['id' => $upgrade['id']]) }}">
                                                                    <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(76 172 245);"></i>
                                                                </a>
                                                            </td>
                                                        {{-- @endcan --}}
                                                        {{-- @can('edit-deliveryorder') --}}
                                                            <td style="text-align: center;">
                                                                <a href="{{ route('detail_upgrade_form' ,['id' => $upgrade['id']]) }}">
                                                                    <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                                                </a>
                                                            </td>
                                                        {{-- @endcan --}}
                                                        {{-- @can('edit-deliveryorder') --}}
                                                            <td style="text-align: center;">
                                                                @if(strtolower($upgrade['status']) == "new")
                                                                    <a class="btn-delete disabled" data-toggle="modal" href="#deleteDoModal" value="{{ route('delete_upgrade_form', ['id' => $upgrade->id]) }}">
                                                                        <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                                    </a>
                                                                @endif
                                                            </td>
                                                        {{-- @endcan --}}
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <br>
                                        {{ $upgrades->appends($url)->appends(['tabActive' => $keyArea])->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- partial -->

<!-- Modal Delete -->
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
                <form id="frmDelete" method="post" action="">
                    {{csrf_field()}}
                    <button type="submit" class="btn btn-gradient-danger mr-2">
                        Yes
                    </button>
                </form>
                <button class="btn btn-light">No</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Delete -->
@endsection

@section('script')
<script>
$(document).ready(function (e) {
    $(".btn-delete").click(function (e) {
        $("#frmDelete").attr("action",  $(this).attr('value'));
    });
});

$('.nav-tabs').on('click', 'li', function() {
	$('.nav-tabs li.active').removeClass('active');
	$(this).addClass('active');
});

$(document).ready(function (e) {
    $("#btn-filter").click(function (e) {
        var urlParamArray = new Array();
        var urlParamStr = "";
        if($('#filter_branch').val() != ""){
            urlParamArray.push("filter_branch=" + $('#filter_branch').val());
        }
        if($('#filter_cso').val() != ""){
            urlParamArray.push("filter_cso=" + $('#filter_cso').val());
        }
        for (var i = 0; i < urlParamArray.length; i++) {
            if (i === 0) {
                urlParamStr += "?" + urlParamArray[i]
            } else {
                urlParamStr += "&" + urlParamArray[i]
            }
        }
        window.location.href = "{{route('list_upgrade_form')}}" + urlParamStr;
    });
});
</script>
@endsection
