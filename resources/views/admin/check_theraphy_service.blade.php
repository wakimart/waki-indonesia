<?php
    $menu_item_page = "theraphy_service";
    $menu_item_second = "check_theraphy_service";
?>
@extends('admin.layouts.template')

@section('style')
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
	.check label{ width: 25em;	}
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
</style>
@endsection

@section('content')
<div class="main-panel">
  	<div class="content-wrapper">
    	<div class="page-header">
      		<h3 class="page-title">Check Therapy</h3>
      		<nav aria-label="breadcrumb">
	        	<ol class="breadcrumb">
	          		<li class="breadcrumb-item"><a data-toggle="collapse" href="#service-dd" aria-expanded="false" aria-controls="service-dd">Therapy</a></li>
	          		<li class="breadcrumb-item active" aria-current="page">Check Therapy</li>
	        	</ol>
      		</nav>
    	</div>
	    <div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">
	            		<form id="actionAdd" class="forms-sample" method="GET" action="{{ route('check_theraphy_service') }}">
                            <div class="form-group">
                                <label for="">Check Code</label>
                                <div class="row text-center">
                                    <div class="col-12 col-md-10 mt-2">
                                        <input type="text" class="form-control" id="code" name="code" placeholder="Check Code" required value="{{ $_GET['code'] ?? '' }}">
                                    </div>
                                    <div class="col-12 col-md-2 mt-2">
                                        <button id="addService" type="submit" class="btn btn-gradient-primary mr-2">Check Code</button>
                                    </div>
                                </div>
                            </div>
	            		</form>
	          		</div>
	        	</div>
	      	</div>

            @if(isset($_GET['code']))
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            @if($custTherapy)
                                <h3 class="text-center" style="color: green">Data Free Therapy Found ({{ strtoupper($custTherapy->status) }})</h3>
                                <div class="table-responsive">
                                    <table class="col-md-12">
                                        <thead>
                                            <td>Code</td>
                                            <td>Name</td>
                                            <td>Phone</td>
                                            <td>Status</td>
                                        </thead>
                                        <tr>
                                            <td>{{ $custTherapy['code'] }}</td>
                                            <td>{{ $custTherapy['name'] }}</td>
                                            <td>{{ $custTherapy['phone'] }}</td>
                                            <td>{{ strtoupper($custTherapy['status']) }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="table-responsive">
                                    <table class="col-md-12">
                                        <thead>
                                            <td colspan="3"><h5 class="my-auto">{{ count($custTherapy->theraphySignIn) }}/30 total days</h5></td>
                                        </thead>
                                        @foreach($custTherapy->theraphySignIn as $idxNya => $perSignIn)
                                            <tr>
                                                <td>{{ $idxNya+1 }}</td>
                                                <td>{{ $perSignIn['therapy_date'] }}</td>
                                                <td>{{ $perSignIn->user['name'] }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                                @if($custTherapy->status == 'process')
                                    <form id="actionAdd" class="forms-sample" method="POST" action="{{ route('store_check_in_theraphy_service') }}">
                                        {{ csrf_field() }}
                                        <input type="text" name="id" hidden="" value="{{ $custTherapy['id'] }}">

                                        @if(Auth::user()->roles[0]->slug != "branch" && Auth::user()->roles[0]->slug != "cso")
                                            <br>
                                            <div class="form-group text-center row">
                                                <input class="form-control col-6 col-md-6 mx-auto" type="date" name="signInDate" value="{{ date('Y-m-d', strtotime('now')) }}">
                                            </div>
                                        @endif
                                        <div class="form-group text-center">                                    
                                            <button id="addService" type="submit" class="btn btn-gradient-primary mr-2">Check In Therapy</button>
                                        </div>
                                    </form>
                                @endif
                            @else
                                <h3 class="text-center" style="color: red">Data Free Therapy Not Found</h3>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

	    </div>
	</div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script type="text/javascript">
	$(document).ready(function(){

	});
</script>
@endsection
