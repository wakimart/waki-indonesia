<?php
$menu_item_page = "personal_homecare";
$menu_item_second = "list_approved";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
    integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
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

    .select2-selection__rendered {
        line-height: 45px !important;
    }

    .select2-container .select2-selection--single {
        height: 45px !important;
    }

    .select2-container--default
    .select2-selection--single
    .select2-selection__arrow {
        top: 10px;
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
                @if (Auth::user()->roles[0]->slug !== "branch" && Auth::user()->roles[0]->slug !== "cso")
                    <div class="col-xs-6 col-sm-4"
                        style="padding: 0; display: inline-block;">
                        <div class="form-group">
                            <label for="filter-branch">Search by Branch</label>
                            <select class="form-control"
                                id="filter_branch"
                                name="filter_branch">
                                <option value="">Choose Branch</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {!! isset($_GET["filter_branch"]) && $_GET["filter_branch"] == $branch->id ? "selected" : "" !!}>
                                        {{ $branch->code }} - {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6" style="padding: 0; display: inline-block">
                        <div class="form-group">
                            <button id="btn-filter" 
                                type="button" 
                                class="btn btn-gradient-primary m-1" 
                                name="filter" 
                                value="-">
                                <span class="mdi mdi-filter"></span> 
                                Apply Filter
                            </button>
                            <button id="btn-filter_reset" 
                                type="button" 
                                class="btn btn-gradient-danger m-1" 
                                name="filter_reset" 
                                value="-">
                                <span class="mdi mdi-refresh"></span> 
                                Reset Filter
                            </button>
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
                                                <th colspan="3" 
                                                    class="center">
                                                    View/Edit/Delete
                                                </th>
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
                                                <td class="center view">
                                                    <a href="#"
                                                        target="_blank">
                                                        <i class="mdi mdi-eye" 
                                                            style="font-size: 24px; 
                                                                color: rgb(76 172 245);">
                                                        </i>
                                                    </a>
                                                </td>
                                                <td class="center edit">
                                                    <a href="#"
                                                        target="_blank">
                                                        <i class="mdi mdi-border-color" 
                                                            style="font-size: 24px; 
                                                                color: #fed713;">
                                                        </i>
                                                    </a>
                                                </td>
                                                <td class="center">
                                                    <a class="btn-delete disabled"
                                                        data-toggle="modal"
                                                        href="#deleteDoModal"
                                                        onclick="submitDelete(this)"
                                                        data-id="">
                                                        <i class="mdi mdi-delete" 
                                                            style="font-size: 24px; 
                                                            color: #fe7c96;">
                                                        </i>
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
                        Are you sure to delete this product?
                    </h5>
                </div>
                <div class="modal-footer">
                    <form id="frmDelete"
                        method="post"
                        action="">
                        @csrf
                        <input type="hidden" name="id" id="id-delete" />
                        <button type="submit"
                            class="btn btn-gradient-danger mr-2">
                            Yes
                        </button>
                    </form>
                    <button class="btn btn-light">No</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Delete -->

</div>
@endsection

@section("script")
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
    integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
    defer></script>
<script type="application/javascript">
    document.addEventListener("DOMContentLoaded", function () {
        $("#filter_branch").select2();
    });
        function submitDelete(e) {
        document.getElementById("id-delete").value = e.dataset.id;
    }
    
    $(document).ready(function (e) {
        $("#btn-filter").click(function (e) {
            var urlParamArray = new Array();
            var urlParamStr = "";
            if($('#filter_branch').val() != ""){
                urlParamArray.push("filter_branch=" + $('#filter_branch').val());
            }
            for (var i = 0; i < urlParamArray.length; i++) {
                if (i === 0) {
                    urlParamStr += "?" + urlParamArray[i]
                } else {
                    urlParamStr += "&" + urlParamArray[i]
                }
            }
            window.location.href = "{{route('list_approved_phc')}}" + urlParamStr;
        });

        $("#btn-filter_reset").click(function (e) {
            window.location.href = "{{route('list_approved_phc')}}";
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
        dayMaxEventRows: 2, 
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
                cso : '{{ $personalhomecare->cso->code }} - {{ $personalhomecare->cso->name }}',
                status : '{{ strtoupper($personalhomecare->status) }}',
                start : '{{ $personalhomecare['schedule'] }}',
                end : '{{ $personalhomecare->status == "process_extend" ? date("Y-m-d", strtotime($personalhomecare->schedule . "T23.59.00" . "+8 days")) : date("Y-m-d", strtotime($personalhomecare->schedule . "T23.59.00" . "+5 days")) }}',
                img : '{{ asset('sources/phc.png')}}',
                view : '{{ route('detail_personal_homecare', ['id' => $personalhomecare['id']]) }}',
                edit : '{{ route('edit_personal_homecare', ['id' => $personalhomecare['id']]) }}',
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
            console.log(info.event.extendedProps.view);
            $("#myTable").show(500);
            $('#theader').html(moment(info.event.start).format('MMMM Do YYYY'));
            $('#start').html(moment(info.event.start).format('M/DD/YYYY'));
            $('#end').html(moment(info.event.end).format('M/DD/YYYY'));
            $('#product').html(info.event.title);
            $('#name').html(info.event.extendedProps.description);
            $('#branch').html(info.event.extendedProps.branch);
            $('#cso').html(info.event.extendedProps.cso);
            $('#status').html(info.event.extendedProps.status);
            $(".view a").prop('href', info.event.extendedProps.view);
            $(".edit a").prop('href', info.event.extendedProps.edit);
        }
        });

        calendar.render();
  });
</script>
@endsection
