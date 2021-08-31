<?php
$menu_item_page = "personal_homecare";
$menu_item_second = "list_approved";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    .center {
        text-align: center;
    }

    .right {
        text-align: right;
    }

    .table th img, .table td img {
        border-radius: 0% !important;
    }

	#calendar {
/* 		float: right; */
        margin: 0 auto;
		background-color: #FFFFFF;
		border-radius: 6px;
	}

    #myTable{
        display: none;
    }

    @media (min-width: 768px){
        #calendar .fc-scroller {
            overflow-y: hidden !important;
        }
    }

    @media (max-width: 767.98px) {
    .fc .fc-toolbar.fc-header-toolbar {
        display: block;
        text-align: center;
        float: none !important;
    }

    .fc-header-toolbar .fc-toolbar-chunk {
        display: block;
        float: none !important;
    }
}

</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">List Personal Homecare</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#order-dd"
                            aria-expanded="false"
                            aria-controls="order-dd">
                            Personal Homecare
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        List Approved
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">

            <div class="col-12" style="margin-bottom: 0;">
                <div class="col-xs-6 col-sm-4" style="padding: 0;display: inline-block;">
					<div class="form-group">
						<label for="">Filter By Team</label>
						<select class="form-control" id="filter_branch" name="filter_branch">
							<option value="" selected="">All Branch</option>
							@foreach($branches as $branch)
							@php
								$selected = "";
								if(isset($_GET['filter_branch'])){
								if($_GET['filter_branch'] == $branch['id']){
									$selected = "selected=\"\"";
								}
								}
							@endphp

							<option {{$selected}} value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
							@endforeach
						</select>
						<div class="validation"></div>
					</div>
				</div>
                @if(Auth::user()->roles[0]['slug'] != 'branch' && Auth::user()->roles[0]['slug'] != 'cso' && Auth::user()->roles[0]['slug'] != 'area-manager')
                <div class="col-xs-6 col-sm-6" style="padding: 0; display: inline-block">
                    <label for=""></label>
                    <div class="form-group">
                        <button id="btn-filter" type="button" class="btn btn-gradient-primary m-1" name="filter" value="-"><span class="mdi mdi-filter"></span> Apply Filter</button>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div id='calendar'></div>
                        
                        
                        <div class="card"
                            id="myTable">
                            <div class="card-body">
                                <div class="table-responsive"
                                    style="border: 1px solid #ebedf2;">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th colspan="10" 
                                                    id="theader" 
                                                    class="text-center" 
                                                    style="background-color: #61e2a0; color: #fff;">
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>Schedule Start</th>
                                                <th>Schedule End</th>
                                                <th>Customer Name</th>
                                                <th>Product Code</th>
                                                <th>Branch</th>
                                                <th class="text-center">CSO</th>
                                                <th>Status</th>
                                                <th colspan="3" class="center">View/Edit/Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="start"></td>
                                                <td id="end"></td>
                                                <td id="name"></td>
                                                <td id="product"></td>
                                                <td id="branch"></td>
                                                <td id="cso"></td>
                                                <td id="status"></td>
                                                <td class="center" id="#view">
                                                    <a>
                                                        <i class="mdi mdi-eye" style="font-size: 24px; color: rgb(76 172 245);"></i>
                                                    </a>
                                                </td>
                                                <td class="center">
                                                    <a id="#edit">
                                                        <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                                    </a>
                                                </td>
                                                <td class="center">
                                                    <a class="btn-delete disabled"
                                                        data-toggle="modal"
                                                        href="#deleteDoModal"
                                                        onclick="submitDelete(this)"
                                                        data-id="">
                                                        <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                </div>
                            </div>
                        </div>


                        <div style='clear:both'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- partial -->
    <!-- Modal Event Click -->
    <div class="modal fade"
        id="dialog"
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
                    <form id="eventClick" method="post" action="">
                        @csrf
                        <div class="form-group">
                            <label>Kode Produk</label>
                            <input type="text" id="title" class="form-control" name="title" placeholder="Kode Produk">
                        </div>
                        <div class="form-group">
                            <label>Nama Customer</label>
                            <input type="text" id="description" class="form-control" name="title" placeholder="Nama Customer">
                        </div>
                        <div class="form-group">
                            <label>Start Date/Time</label>
                            <input type="text" id="start" class="form-control" name="start" placeholder="Start date & time">
                        </div>
                        <div class="form-group">
                            <label>Background Color</label>
                            <input type="color" id="color" class="form-control" name="color">
                        </div>
                        <div class="form-group">
                            <label>Text Color</label>
                            <input type="color" id="textcolor" class="form-control" name="textColor">
                        </div>
                        <input type="hidden" id="eventId" name="event_id">
                        <div class="form-group">
                            <button type="submit" id="textcolor" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("script")

<script type="application/javascript">
    function submitDelete(e) {
        document.getElementById("id-delete").value = e.dataset.id;
    }
    
    $(document).ready(function (e) {
        $("#btn-filter").click(function (e) {
            var urlParamArray = new Array();
            var urlParamStr = "";
            if($('#search').val() != ""){
                urlParamArray.push("search=" + $('#search').val());
            }
            for (var i = 0; i < urlParamArray.length; i++) {
                if (i === 0) {
                    urlParamStr += "?" + urlParamArray[i]
                } else {
                    urlParamStr += "&" + urlParamArray[i]
                }
            }
            window.location.href = "{{route('list_all_phc')}}" + urlParamStr;
        });
    }); 

    $(document).ready(function () {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        initialDate: new Date(),
        navLinks: true, 
        businessHours: true, 
        editable: false,
        selectable: true,
        allDay: true,
        nextDayThreshold: '00:00:01',
        dayMaxEventRows: true, 
        views: {
            timeGrid: {
                dayMaxEventRows: 3
            }
        },
        events: [
            @foreach($personalhomecares as $personalhomecare)
            {
                title : '{{ $personalhomecare->personalHomecareProduct->code }}', 
                description : '{{ $personalhomecare['name'] }}',
                branch : '{{ $personalhomecare->branch->code }}',
                cso : '{{ $personalhomecare->cso->code }}',
                status : '{{ strtoupper($personalhomecare->status) }}',
                start : '{{ $personalhomecare['schedule'] }}',
                end : '{{ date("Y-m-d", strtotime($personalhomecare->schedule . "T23.59.00" . "+5 days")) }}',
                img : '{{ asset('sources/phc.png')}}',
                view : '{{ route('detail_personal_homecare', ['id' => $personalhomecare['id']]) }}',
                edit : '{{ route('edit_personal_homecare', ['id' => $personalhomecare['id']]) }}'
            },
            @endforeach
        ],
        eventDidMount: function(info, view, el) {
            const end_date = moment(info.event.start).add(6, 'days').format('M/DD/YYYY');

            $(info.el).tooltip({
                placement: "right",
                html: true,
                title: "<p>Kode Produk : " 
                        + info.event.title 
                        + "</p>" 
                        + "<p>Nama Customer : " 
                        + info.event.extendedProps.description 
                        + "</p>"
                        + "<p>Schedule Date : "
                        + moment(info.event.start).format('M/DD/YYYY')
                        + "</p>"
                        + "<p>End Date : "
                        + moment(info.event.end).format('M/DD/YYYY')
                        + "</p>",
            });
        },
        eventContent: function(info) {
            const img = info.event._def.extendedProps.img;
            const text = "<div class='d-flex' style='align-items: center; padding-left: 10px;'>"
                    + "<img style='margin-right: 10px;' src='" 
                    + img 
                    + "' width='18' height='18'> "
                    + "<span style='line-height: 20px;'>" 
                    + info.event._def.title 
                    + "<br>" 
                    + info.event.extendedProps.description
                    + "</span>"
                    + "</div>";

            return {
                html: text
            };
        },
        eventClick: function(info) {
            $("#myTable").show(500);
            $('#theader').html(moment(info.event.start).format('MMMM Do YYYY'));
            $('#start').html(moment(info.event.start).format('M/DD/YYYY'));
            $('#end').html(moment(info.event.end).format('M/DD/YYYY'));
            $('#product').html(info.event.title);
            $('#name').html(info.event.extendedProps.description);
            $('#branch').html(info.event.extendedProps.branch);
            $('#cso').html(info.event.extendedProps.cso);
            $('#status').html(info.event.extendedProps.status);
            $('#edit').setAttribute("href", info.event.extendedProps.edit);
        } 
        });

        calendar.render();
  });
</script>
@endsection
