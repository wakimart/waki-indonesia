<?php
    $menu_item_page = "homeservice";
    $menu_item_second = "list_homeservice";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="{{ asset('css/admin/calendarorganizer.css')}}">
<style>
.cjslib-day-indicator {
  color: #ffc107 !important;
  background-color: #ffc107 !important;
  }
.cjslib-indicator-type-numeric {
  color: #ffa000 !important;
  }
.cjslib-day.cjslib-day-today > .cjslib-day-num {
  border-color: #ffc107 !important;
  }
</style>

@endsection

@section('content')

<div class="main-panel">
	<div class="content-wrapper">
    <div class="row">
  			<div class="col-12 grid-margin stretch-card">
    			<div class="card">
      				<div class="card-body">
      					<h5 style="margin-bottom: 0.5em;">Appointment</h5>
        				<div class="table-responsive" style="border: 1px solid #ebedf2;">
                  <div id="calendarContainer"></div>
              		<div id="organizerContainer"></div>
        				</div>
      				</div>
    			</div>
  			</div>
		</div>
	</div>
<!-- partial -->
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
              		<h5 style="text-align:center;">Are You Sure to Delete this Delivery Order ?</h5>
            	</div>
            	<div class="modal-footer">
            		<form id="frmDelete" method="post" action="">
                    {{csrf_field()}}
                    	<button type="submit" class="btn btn-gradient-danger mr-2">Yes</button>
                	</form>
              		<button class="btn btn-light">No</button>
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
                        text: "Christmas Day"
                    },
                    // 2
                    {
                        startTime: "5:00pm", //bisa am pm soale string tp msh g tau carane sorting ini time
                        endTime: "11:00pm",
                        text: "Christmas Dinner"
                    },
                    // 3
                    {
                        startTime: "10:00",
                        endTime: "12:00",
                        text: "Some Event Here",
                        link: "#" //link kalo mungkin edit modal kali yaa
                    },
                ],
                16: [
                    // 1
                    {
                        startTime: "00:00",
                        endTime: "24:00",
                        text: "Christmas Day"
                    },
                    // 2
                    {
                        startTime: "5:00pm", //bisa am pm soale string
                        endTime: "11:00pm",
                        text: "Christmas Dinner"
                    }
                ],
                17: [
                    // 1
                    {
                        startTime: "00:00",
                        endTime: "24:00",
                        text: "Christmas Day"
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
      placeholder: "<button style='width: calc(100% - 16px); background-color: #E6E6E6; border-radius: 6px; margin: 8px; border: none; padding: 12px 0px; cursor: pointer;'>Add New Event</button>",
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

</script>

@endsection
