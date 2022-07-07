<?php
$menu_item_page = "upgrade";
$menu_item_second = "edit_upgrade_form";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    #intro {
        padding-top: 2em;
    }
    button {
        background: #1bb1dc;
        border: 0;
        border-radius: 3px;
        padding: 8px 30px;
        color: #fff;
        transition: 0.3s;
    }
    .validation {
        color: red;
        font-size: 9pt;
    }
    input, select, textarea {
        border-radius: 0 !important;
        box-shadow: none !important;
        border: 1px solid #dce1ec !important;
        font-size: 14px !important;
    }

	/* Mark input boxes that gets an error on validation: */
	input.invalid {background-color: #ffdddd;}
	/* Mark the steps that are finished and valid: */
	.step.finish {background-color: #4CAF50;}
	input[type='checkbox'], input[type='radio'] {
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
  .center {text-align: center;}
  .right {text-align: right;}
  .justify-content-center {
  	padding: 0em 1em;
  }
    /*-- mobile --*/
	@media (max-width: 768px){
        #desktop{display: none;}
        #mobile{display: block;}
        img{height: 150px;}
    }
        @media (min-width: 768px) {
        #desktop{display: block;}
        #mobile{display: none;}
        .table-responsive::-webkit-scrollbar {
            display: none;
        }
    }
</style>
@endsection

@section('content')
<div class="main-panel">
  	<div class="content-wrapper">
        <!-- header mobile -->
		<div id="mobile">
			<h3 class="text-center">Add Upgrade</h3>
			<div class="row">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a data-toggle="collapse" href="#" aria-expanded="false" aria-controls="upgrade-dd">Upgrade</a></li>
						<li class="breadcrumb-item active" aria-current="page">Add Upgrade</li>
					</ol>
				</nav>
		  	</div>
	  	</div>

		<!-- header desktop -->
		<div id="desktop">
            <div class="page-header">
                <h3 class="page-title">Add Upgrade</h3>
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
                        <li class="breadcrumb-item active" aria-current="page">
                            Add Upgrade
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

    	<div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <h2>Detail Upgrade</h2>
                        </div>
                        <div class="row justify-content-center">
                            <div class="table-responsive">
                                <table class="col-md-12">
                                    <thead>
                                        <td>Acc By</td>
                                        <td>Acc Code</td>
                                        <td>Upgrade Date</td>
                                    </thead>
                                    <tr>
                                        <td class="center">
                                            @if (strtolower($upgrade['status']) == "new")
                                                <span class="badge badge-secondary">
                                                    New
                                                </span>
                                            @elseif (strtolower($upgrade['status']) == "process")
                                                <span class="badge badge-primary">
                                                    Process by: {{ $upgrade->statusBy("process")['user_id']['name'] }}
                                                </span>
                                            @elseif (strtolower($upgrade['status']) == "repaired")
                                                <span class="badge badge-warning">
                                                    Repaired by: {{ $upgrade->statusBy("repaired")['user_id']['name'] }}
                                                </span>
                                            @elseif (strtolower($upgrade['status']) == "approved")
                                                <span class="badge badge-info">
                                                    Approved by: {{ $upgrade->statusBy("approved")['user_id']['name'] }}
                                                </span>
                                            @elseif (strtolower($upgrade['status']) == "completed")
                                                <span class="badge badge-success">
                                                    Completed by: {{ $upgrade->statusBy("completed")['user_id']['name'] }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="center">
                                            {{ $upgrade->acceptance['code'] }}
                                        </td>
                                        <td class="center">
                                            {{ date("d/m/Y", strtotime($upgrade->acceptance['upgrade_date'])) }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="table-responsive">
                                <table class="col-md-12">
                                    <thead>
                                        <td colspan="2">Data Upgrade</td>
                                    </thead>
                                    <tr>
                                        <td>Customer Name: </td>
                                        <td>{{ $upgrade->acceptance['name'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Customer Phone: </td>
                                        <td>{{ $upgrade->acceptance['phone'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>Branch: </td>
                                        <td>{{ $upgrade->acceptance->branch->code }} - {{ $upgrade->acceptance->branch->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>CSO: </td>
                                        <td>{{ $upgrade->acceptance->cso->code }} - {{ $upgrade->acceptance->cso->name }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="table-responsive">
                                <table class="col-md-12">
                                    <thead>
                                        <td colspan="2">Data Product</td>
                                    </thead>
                                    <tr>
                                        <td>New Product: </td>
                                        <td>
                                            {{ $upgrade->acceptance->newproduct['code'] }} - {{ $upgrade->acceptance->newproduct['name'] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Old Product: </td>
                                        @if ($upgrade->acceptance['oldproduct_id'] != null)
                                            <td>
                                                {{ $upgrade->acceptance->oldproduct['code'] }} - {{ $upgrade->acceptance->oldproduct['name'] }}
                                            </td>
                                        @else
                                            <td>
                                                {{ $upgrade->acceptance['other_product'] }}
                                            </td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>Purchase Date: </td>
                                        <td>{{ date("d/m/Y", strtotime($upgrade->acceptance['purchase_date'])) }}</td>
                                    </tr>
                                    <tr>
                                        <td rowspan="5">Kelengkapan: </td>
                                        <td>
                                            <i class="mdi {{ in_array("mesin", $upgrade->acceptance['arr_condition']['kelengkapan']) ? "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" style="font-size: 24px; color: #fed713;"></i> Mesin
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
                                            <i class="mdi {{ in_array("other", $upgrade->acceptance['arr_condition']['kelengkapan']) ? "mdi-check-box-outline" : "mdi-checkbox-blank-outline" }}" style="font-size: 24px; color: #fed713;"></i> Other: {{ isset($upgrade->acceptance['arr_condition']['kelengkapan']['other']) ? $upgrade->acceptance['arr_condition']['kelengkapan']['other'][0] : "-" }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Kondisi Mesin: </td>
                                        <td>
                                            {{ ucwords($upgrade->acceptance['arr_condition']['kondisi']) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tampilan: </td>
                                        <td>
                                            {{ ucwords($upgrade->acceptance['arr_condition']['tampilan']) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Description: </td>
                                        <td>
                                            {{ $upgrade->acceptance['description'] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Photo: </td>
                                        <td>
                                            @foreach ($upgrade->acceptance['image'] as $imgAcc)
                                                <img src="{{ asset('sources/acceptance/') . '/' . $imgAcc }}" height="300px" />
                                            @endforeach
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

	    <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form id="actionAdd"
                            method="POST"
                            action="{{ route('update_upgrade_form') }}">
                            {{ csrf_field() }}
                            <input type="hidden"
                                name="id"
                                value="<?php echo $upgrade->id; ?>" />
                            <div class="form-group">
                                <label for="">Due Date</label>
                                <?php
                                $dueDate = date("Y-m-d", strtotime($upgrade->due_date));
                                ?>
                                <input type="date"
                                    class="form-control"
                                    name="due_date"
                                    id="due_date"
                                    placeholder="Due Date"
                                    value="<?php echo $dueDate; ?>"
                                    data-msg="Mohon Isi Tanggal" />
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="">Task</label>
                                <textarea class="form-control"
                                    name="task"
                                    rows="10"
                                    data-msg="Mohon Isi Task"
                                    placeholder="Task"><?php echo $upgrade->task; ?></textarea>
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <button id="upgradeProcess"
                                    type="submit"
                                    class="btn btn-gradient-primary mr-2"
                                    name="upgrade_id"
                                    value="{{ $upgrade->id }}">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>
@endsection
