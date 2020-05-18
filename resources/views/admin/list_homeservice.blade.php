<?php
    $menu_item_page = "homeservice";
    $menu_item_second = "list_homeservice";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="{{ asset('css/admin/calendarorganizer.css')}}">
<style>
/* manual override */
.cjslib-day-indicator {
  color: #ffc107 !important;
  background-color: #1bcfb4 !important;
  }
.cjslib-indicator-type-numeric {
  color: #ffa000 !important;
  }
.cjslib-day.cjslib-day-today > .cjslib-day-num {
  border-color: #1bcfb4 !important;
  }
.cjslib-calendar.cjslib-size-small .cjslib-day > .cjslib-day-indicator {
    width: 24px;
    height: 24px;
}
.cjslib-calendar.cjslib-size-small .cjslib-day > .cjslib-indicator-type-numeric {
    font-size: 12px;
    font-weight: bolder;
    color: #ffffff !important;
}
.btnappoint {
    display: inline-block;
    font-weight: 400;
    font-size: 1.4em;
    padding: 0.2rem 1rem;
    border-radius: 0.1875rem;
    text-align: center;
    vertical-align: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }
.titleAppoin {
    font-weight: bolder;
}
.timeContainerDiv{
    flex: 1 !important;
}
.paragrapContainerDiv{
    flex-direction: column;
    align-items: normal !important;
}
.iconContainerDiv{
    flex: 1 !important;
}
</style>

@endsection

@section('content')

<div class="main-panel">
	<div class="content-wrapper">
    <div class="page-header">
  			<h3 class="page-title">Home Service</h3>
  			<nav aria-label="breadcrumb">
    			<ol class="breadcrumb">
      				<li class="breadcrumb-item"><a data-toggle="collapse" href="#cso-dd" aria-expanded="false" aria-controls="cso-dd">Home Service</a></li>
      				<li class="breadcrumb-item active" aria-current="page">List Home Service</li>
    			</ol>
  			</nav>
		</div>

    <div class="row">
  			<div class="col-12 grid-margin stretch-card">
    			<div class="card">
      				<div class="card-body">
      					<h5 style="margin-bottom: 0.5em;">Appointment</h5>
                <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
                  <div class="col-xs-6 col-sm-4" style="padding: 0;display: inline-block;">
                    <div class="form-group">
                      <label for="">Filter By Province</label>
                        <select class="form-control" id="filter_type" name="filter_type" data-msg="Mohon Pilih Tipe" required>
                            <option selected disabled value="">Choose Filter</option>
                                <option value="">All</option>
                                <option value="">Province</option>
                        </select>
                        <div class="validation"></div>
                    </div>
                  </div>
                  <div class="col-xs-6 col-sm-4" style="padding: 0;display: inline-block;">
                    <div class="form-group">
                      <label for="">Filter By City</label>
                        <select class="form-control" id="filter_type" name="filter_type" data-msg="Mohon Pilih Tipe" required>
                            <option selected disabled value="">Choose Filter</option>
                                <option value="">All</option>
                                <option value="">City</option>
                        </select>
                        <div class="validation"></div>
                    </div>
                  </div>
                  <div class="col-xs-6 col-sm-4" style="padding: 0;display: inline-block;">
                    <div class="form-group">
                      <label for="">Filter By Team</label>
                        <select class="form-control" id="filter_type" name="filter_type" data-msg="Mohon Pilih Tipe" required>
                            <option selected disabled value="">Choose Filter</option>
                                <option value="">All</option>
                                <option value="">Team</option>
                        </select>
                        <div class="validation"></div>
                    </div>
                  </div>
              </div>


        				<div class="table-responsive" style="border: 1px solid #ebedf2;">
                  <div id="calendarContainer" style="float: left;"></div>
              		<div id="organizerContainer" style="float: left;"></div>
        				</div>
              </div>
    			</div>
  			</div>
		</div>
	</div>
<!-- partial -->

  <!-- Modal Add -->
  <div class="modal fade" id="addHomeServiceModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Add Appointment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 style="text-align:center;"></h5>
                <form id="actionAdd" class="forms-sample" method="POST" action="#">
                  {{ csrf_field() }}
                    <div class="form-group">
                      <label for="">Start Date</label>
                      <input type="time" class="form-control" name="code" placeholder="Branch Code" required="">
                    </div>
                    <div class="form-group">
                      <label for="">End Date</label>
                      <input type="time" class="form-control" name="name" placeholder="Branch Name" required="">
                    </div>
                    <div class="form-group">
                      <label for="">Appointment Title</label>
                      <input type="text" class="form-control" name="name" placeholder="Appointment" required="">
                    </div>
                    <div class="form-group">
                      <label for="">Appointment Description</label>
                      <input type="text" class="form-control" name="name" placeholder="Description" required="">
                    </div>

                </form>
              </div>
            <div class="modal-footer">
              <button id="addBranch" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
              <button class="btn btn-light" data-dismiss="modal" aria-label="Close">Cancel</button>
            </div>
          </div>
      </div>
  </div>
  <!-- End Modal Edit -->

	<!-- Modal Edit -->
	<div class="modal fade" id="editHomeServiceModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          	<div class="modal-content">
            	<div class="modal-header">
                <h5 class="modal-title">Edit Appointment</h5>
              		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                		<span aria-hidden="true">&times;</span>
              		</button>
            	</div>
            	<div class="modal-body">
              		<h5 style="text-align:center;"></h5>
                  <form id="actionAdd" class="forms-sample" method="POST" action="#">
    					    	{{ csrf_field() }}
    					      	<div class="form-group">
    					        	<label for="">Start Date</label>
    					        	<input type="time" class="form-control" name="code" placeholder="Branch Code" required="">
    					      	</div>
    					      	<div class="form-group">
    					        	<label for="">End Date</label>
    					        	<input type="time" class="form-control" name="name" placeholder="Branch Name" required="">
    					      	</div>
                      <div class="form-group">
    					        	<label for="">Appointment Title</label>
    					        	<input type="text" class="form-control" name="name" placeholder="Appointment" required="">
    					      	</div>
                      <div class="form-group">
    					        	<label for="">Appointment Description</label>
    					        	<input type="text" class="form-control" name="name" placeholder="Description" required="">
    					      	</div>

    					    </form>
    				  	</div>
            	<div class="modal-footer">
                <button id="addBranch" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
                <button class="btn btn-light" data-dismiss="modal" aria-label="Close">Cancel</button>
            	</div>
          	</div>
        </div>
    </div>
    <!-- End Modal Edit -->

    <!-- Modal Delete -->
  	<div class="modal fade" id="deleteHomeServiceModal" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            	<div class="modal-content">
              	<div class="modal-header">
                		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  		<span aria-hidden="true">&times;</span>
                		</button>
              	</div>
              	<div class="modal-body">
                		<h5 style="text-align:center;">Are You Sure to Delete this Appointment ?</h5>
              	</div>
              	<div class="modal-footer">
              		<form id="frmDelete" method="post" action="">
                      {{csrf_field()}}
                      	<button type="submit" class="btn btn-gradient-danger mr-2">Yes</button>
                  	</form>
                		<button type="button" data-dismiss="modal" class="btn btn-light">No</button>
              	</div>
            	</div>
          </div>
    </div>
    <!-- End Modal Delete -->
</div>
@endsection

@section('script')
<script src="{{ asset('js/admin/calendarorganizer.js') }}"></script>

<script>
  window.onload = function() {
  "use strict";

  // function that creates dummy data for demonstration
  function createDummyData() {
    var date = new Date();
    var data = {
    // Cuma dummy buat 15,16,17,19.
        2020: {
            5: {
                15: [
                    // 1
                    {
                        startTime: "00:00",
                        endTime: "24:00",
                        title: "Judul Appointment YAY",
                        desc: "disini nanti desc dengan tambahan kejelasan",
                        link: "#"
                    },
                    // 2
                    {
                        startTime: "10:00", //bisa am pm soale string tp msh g tau carane sorting ini time
                        endTime: "11:00",
                        title: "Judul Appointment YAY",
                        desc: "disini nanti desc dengan tambahan kejelasan",
                        link: "#"
                    },
                    // 3
                    {
                        startTime: "13:00",
                        endTime: "15:00",
                        text: "Some Event Here",
                        link: "#" //link kalo mungkin edit modal kali yaa
                    },
                ],
                16: [
                    // 1
                    {
                        startTime: "00:00",
                        endTime: "24:00",
                        title: "Judul Appointment YAY",
                        desc: "disini nanti desc dengan tambahan kejelasan",
                    },
                    // 2
                    {
                        startTime: "5:00pm", //bisa am pm soale string
                        endTime: "11:00pm",
                        title: "Judul Appointment YAY",
                        desc: "disini nanti desc dengan tambahan kejelasan",
                    }
                ],
                17: [
                    // 1
                    {
                        startTime: "00:00",
                        endTime: "24:00",
                        title: "Judul Appointment YAY",
                        desc: "disini nanti desc dengan tambahan kejelasan",
                    },
                ]
            }
        }
    }



    // function dummy bawaan buat random atau for db
    /*for (var i = 0; i < 10; i++) {
      data[date.getFullYear() + i] = {};

      for (var j = 0; j < 12; j++) {
        data[date.getFullYear() + i][j + 1] = {};

        for (var k = 0; k < Math.ceil(Math.random() * 10); k++) {
          var l = Math.ceil(Math.random() * 28);

          try {
            data[date.getFullYear() + i][j + 1][l].push({
              startTime: "10:00",
              endTime: "12:00",
              text: "Some Event Here",
              link: "#"
            });
          } catch (e) {
            data[date.getFullYear() + i][j + 1][l] = [];
            data[date.getFullYear() + i][j + 1][l].push({
              startTime: "10:00",
              endTime: "12:00",
              text: "Some Event Here",
              link: "#"
            });
          }
        }
      }
    }*/

    return data;
  }

  // creating the dummy static data
  var data = createDummyData();

  // stating variables in order for them to be global
  // var calendar, organizer;

  // initializing a new calendar object, that will use an html container to create itself
  var calendar = new Calendar("calendarContainer", // id of html container for calendar
    "small", // size of calendar, can be small | medium | large
    [
      "Sunday", // left most day of calendar labels
      3 // maximum length of the calendar labels
    ], [
      "#ffc107", // primary color
      "#ffa000", // primary dark color
      "#ffffff", // text color
      "#ffecb3", // text dark color
    ],
    {
      // placeholder: "" // Removes Organizer's Placeholder
      placeholder: "<li style='text-align:center; margin-top: 1em;'>No appointments on this day.</li><br><button type='button' data-toggle='modal' data-target='#addHomeServiceModal' style='width: calc(100% - 16px); background-color: #E6E6E6; border-radius: 6px; margin: 8px; border: none; padding: 12px 0px; cursor: pointer;'>Add New Appointment</button>",
      indicator: true,
      indicator_type: 1, // indicator type, can be 0 (not numeric) | 1 (numeric)
      indicator_pos: "bottom" // indicator position, can be top | bottom
    }
  );

  // initializing a new organizer object, that will use an html container to create itself
  var organizer = new Organizer("organizerContainer", // id of html container for calendar
    calendar, // defining the calendar that the organizer is related to
    data // giving the organizer the static data that should be displayed
  );

  /*// This is gonna be similar to an ajax function that would grab
  // data from the server; then when the data for a this current month
  // is grabbed, you just add it to the data object of the form
  // { year num: { month num: { day num: [ array of events ] } } }
  function dataWithAjax(date, callback) {
    var data = {};

    try {
      data[date.getFullYear()] = {};
      data[date.getFullYear()][date.getMonth() + 1] = serverData[date.getFullYear()][date.getMonth() + 1];
    } catch (e) {}

    callback(data);
  };

  window.onload = function() {
    dataWithAjax(new Date(), function(data) {
      // initializing a new organizer object, that will use an html container to create itself
      organizer = new Organizer("organizerContainer", // id of html container for calendar
        calendar, // defining the calendar that the organizer is related
        data // small part of the data of type object
      );

      // after initializing the organizer, we need to initialize the onMonthChange
      // there needs to be a callback parameter, this is what updates the organizer
      organizer.onMonthChange = function(callback) {
        dataWithAjax(organizer.calendar.date, function(data) {
          organizer.data = data;
          callback();
        });
      };
    });
  };*/


    // Days Block Click Listener
    organizer.setOnClickListener('days-blocks',
        // Called when a day block is clicked
        function () {
            console.log("Day block clicked");
        }
    );

    // Days Block Long Click Listener
    organizer.setOnLongClickListener('days-blocks',
        // Called when a day block is long clicked
        function () {
            console.log("Day block long clicked");
        }
    );

    // Month Slider (Left and Right Arrow) Click Listeners
    organizer.setOnClickListener('month-slider',
        // Called when the month left arrow is clicked
        function () {
            console.log("Month back slider clicked");
        },
        // Called when the month right arrow is clicked
        function () {
            console.log("Month next slider clicked");
        }
    );

    // Year Slider (Left and Right Arrow) Click Listeners
    organizer.setOnClickListener('year-slider',
        // Called when the year left arrow is clicked
        function () {
            console.log("Year back slider clicked");
        },
        // Called when the year right arrow is clicked
        function () {
            console.log("Year next slider clicked");
        }
    );


};
</script>


@endsection
