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
                  <form id="actionEdit" class="forms-sample" method="POST" action="#">
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

  // untuk pertama kali data di buka
  function onLoadDate(){
    data = {};
    // data[new Date().getFullYear()] = {};
    // data[new Date().getFullYear()][new Date().getMonth()+1] = {};

    @foreach($homeServices as $dataNya)
      @php
        $AppointmentNya = new DateTime($dataNya['appointment']);
        $tahun = $AppointmentNya->format('Y');
        $bulan = $AppointmentNya->format('n');
        $hari = $AppointmentNya->format('j');
        $jam = $AppointmentNya->format('H:i');
      @endphp

      //try tahun
      try{
        console.log(data[{{$tahun}}][{{$bulan}}]);
      }
      catch(e){
        data[{{$tahun}}] = {};
      }

      //try bulan
      try{
        console.log(data[{{$tahun}}][{{$bulan}}][{{ $hari }}]);
      }
      catch(e){
        data[{{$tahun}}][{{$bulan}}] = {};
      }

      //try hari
      try{
        data[{{ $tahun }}][{{ $bulan }}][{{ $hari }}].push({
                startTime: "{{ $jam }}",
                endTime: "{{ $jam }}",
                title: "<a href=\"{{ Route('homeServices_success') }}?code={{ $dataNya['code'] }}\" target=\"_blank\">{{ $dataNya['code'] }}</a>",
                desc: "{{ $dataNya['name'] }} - {{ $dataNya['phone'] }}<br>Branch : {{ $dataNya->branch['code'] }}<br>CSO : {{ $dataNya->cso['name'] }}",
                dataId : "{{ $dataNya['id'] }}"
              });
      } 
      catch (e){
        data[{{ $tahun }}][{{ $bulan }}][{{ $hari }}] = [];
        data[{{ $tahun }}][{{ $bulan }}][{{ $hari }}].push({
                startTime: "{{ $jam }}",
                endTime: "{{ $jam }}",
                title: "<a href=\"{{ Route('homeServices_success') }}?code={{ $dataNya['code'] }}\" target=\"_blank\">{{ $dataNya['code'] }}</a>",
                desc: "{{ $dataNya['name'] }} - {{ $dataNya['phone'] }}<br>Branch : {{ $dataNya->branch['code'] }}<br>CSO : {{ $dataNya->cso['name'] }}",
                dataId : "{{ $dataNya['id'] }}"
              });
      }
    @endforeach

    return data;
  }

  // creating the dummy static data
  var data = onLoadDate();

  // stating variables in order for them to be global
  var calendar, organizer;
  var minDate = new Date('{{ $awalBulan }}');
  var maxDate = new Date('{{ $akhirBulan }}');
  var nowDate = new Date();

  // initializing a new calendar object, that will use an html container to create itself
  calendar = new Calendar("calendarContainer", // id of html container for calendar
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
  organizer = new Organizer("organizerContainer", // id of html container for calendar
    calendar, // defining the calendar that the organizer is related to
    data // giving the organizer the static data that should be displayed
  );

  // Month Slider (Left and Right Arrow) Click Listeners
  organizer.setOnClickListener('month-slider',
      // Called when the month left arrow is clicked
      function () {
        // calendarContainer-month-back
        var currentMonth = organizer.calendar.date.getMonth()+1;
        var currentYear = organizer.calendar.date.getFullYear();

        if(currentMonth > minDate.getMonth()+1 || currentYear > minDate.getFullYear()){
          $('#calendarContainer-month-back').css('display', 'flex');
        }
        else{
          $('#calendarContainer-month-back').css('display', 'none');
        }
        if(currentMonth < maxDate.getMonth() || currentYear < maxDate.getFullYear()){
          $('#calendarContainer-month-next').css('display', 'flex');
        }
        else{
          $('#calendarContainer-month-next').css('display', 'none');
        }
        // while(organizer.calendar.date.getFullYear() != minDate.getFullYear()){
        //   if(organizer.calendar.date.getFullYear() < minDate.getFullYear()){
        //     calendar.next();
        //   }
        //   else if(organizer.calendar.date.getFullYear() > minDate.getFullYear()){
        //     calendar.back('year');
        //   }
        //   console.log(organizer.calendar.date.getFullYear());
        // }
        
          $('#calendarContainer-year-next').css('display', 'flex');
          $('#calendarContainer-year-back').css('display', 'none');
      },
      // Called when the month right arrow is clicked
      function () {
        var currentMonth = organizer.calendar.date.getMonth()+1;
        var currentYear = organizer.calendar.date.getFullYear();

        if(currentMonth > minDate.getMonth()+1 || currentYear > minDate.getFullYear()){
          $('#calendarContainer-month-back').css('display', 'flex');
        }
        else{
          $('#calendarContainer-month-back').css('display', 'none');
        }
        if(currentMonth < maxDate.getMonth() || currentYear < maxDate.getFullYear()){
          $('#calendarContainer-month-next').css('display', 'flex');
        }
        else{
          $('#calendarContainer-month-next').css('display', 'none');
        }
      }
  );

  // Year Slider (Left and Right Arrow) Click Listeners
  organizer.setOnClickListener('year-slider',
      // Called when the year left arrow is clicked
      function () {  
        var currentYear = organizer.calendar.date.getFullYear();
        if(currentYear > minDate.getFullYear()){
          $('#calendarContainer-year-back').css('display', 'flex');
        }
        else{
          $('#calendarContainer-year-back').css('display', 'none');
        }
        if(currentYear < maxDate.getFullYear()){
          $('#calendarContainer-year-next').css('display', 'flex');
        }
        else{
          $('#calendarContainer-year-next').css('display', 'none');
        }
        while(organizer.calendar.date.getMonth()+1 != minDate.getMonth()+1){
          if(organizer.calendar.date.getMonth()+1 < minDate.getMonth()+1){
            calendar.next('month');
          }
          else if(organizer.calendar.date.getMonth()+1 > minDate.getMonth()+1){
            calendar.back('month');
          }
          $('#calendarContainer-month-next').css('display', 'flex');
          $('#calendarContainer-month-back').css('display', 'none');
        }
      },
      // Called when the year right arrow is clicked
      function () {
        var currentYear = organizer.calendar.date.getFullYear();
        if(currentYear > minDate.getFullYear()){
          $('#calendarContainer-year-back').css('display', 'flex');
        }
        else{
          $('#calendarContainer-year-back').css('display', 'none');
        }
        if(currentYear < maxDate.getFullYear()){
          $('#calendarContainer-year-next').css('display', 'flex');
        }
        else{
          $('#calendarContainer-year-next').css('display', 'none');
        }
        while(organizer.calendar.date.getMonth()+1 != maxDate.getMonth()){
          if(organizer.calendar.date.getMonth()+1 < maxDate.getMonth()){
            calendar.next('month');
          }
          else if(organizer.calendar.date.getMonth()+1 > maxDate.getMonth()){
            calendar.back('month');
          }
          $('#calendarContainer-month-next').css('display', 'none');
          $('#calendarContainer-month-back').css('display', 'flex');
          console.log("masuk tahun");
        }
      }, 
  );

  //cek ada tahun depan atau belakang
  $(document).ready(function(e){
    if(nowDate.getFullYear() == minDate.getFullYear()){
      $('#calendarContainer-year-back').css('display', 'none');
      console.log("masuk");
    }
    if(nowDate.getFullYear() == maxDate.getFullYear()){
      $('#calendarContainer-year-next').css('display', 'none');
      console.log("masuk");
    }
  });
   
//   //ajax ambil data di bulan atau tahun baru
//   function fetchingCalenderData(month, year){
//     $.ajax({
//         headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             },
//         type: 'post',
//         url: "",
//         data: {
//             'month': month,
//             'year': year
//         },
//         success: function(data){
//           if(data.length > 0){
//             var calenderFill = {};
//             calenderFill[year] = {};
//             calenderFill[year][month] = {};

//             $.each(data, function( index, value ) {
//               //rubah ke satuan hari, bulan, tahun dan jam
//               var rawDate = new Date(value['appointment']);
//               var date = addZero(rawDate.getDate());
//               var hour = addZero(rawDate.getHours())+":"+addZero(rawDate.getMinutes());

//               try{
//                 calenderFill[year][month][date].push({
//                         startTime: hour,
//                         endTime: hour,
//                         title: value['code'],
//                         desc: value['name'] +"-"+ value['phone'] +"\n"+ value['address'] ,
//                         dataId : value['id']
//                       });
//               } 
//               catch (e){
//                 calenderFill[year][month][date] = [];
//                 calenderFill[year][month][date].push({
//                         startTime: hour,
//                         endTime: hour,
//                         title: value['code'],
//                         desc: value['name'] +"-"+ value['phone'] +"\n"+ value['address'] ,
//                         dataId : value['id']
//                       });
//               }
//             });

//             organizer.data = onLoadDate();
//             console.log(organizer.data);
//           }
//         },
//     });
//   };
// };

// function addZero(i) {
//   if (i < 10) {
//     i = "0" + i;
//   }
//   return i;
// }
};

</script>
@endsection
