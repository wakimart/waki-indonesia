<?php
    $menu_item_page = "homeservice";
    $menu_item_second = "list_homeservice";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="{{ asset('css/admin/calendarorganizer.css?v='.filemtime('css/admin/calendarorganizer.css'))}}">
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
    padding: 0.2rem 0.8rem;
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
                  @if(Utils::$lang=='id')
                    <div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
                      <div class="form-group">
                        <label for="">Filter By City</label>
                          <select class="form-control" id="filter_province" name="filter_province">
                              <option value="" selected="">All Province</option>
                              @php
                                $result = RajaOngkir::FetchProvince();
                                $result = $result['rajaongkir']['results'];
                                $arrProvince = [];
                                if(sizeof($result) > 0){
                                    foreach ($result as $value) {
                                        echo "<option value=\"". $value['province_id']."\">".$value['province']."</option>";
                                    }
                                }
                              @endphp
                          </select>
                          <div class="validation"></div>
                      </div>
                      <div class="form-group">
                        <input class="form-control" id="search" name="search" placeholder="Search By Name, Phone, and Code Homeservice">
                        <div class="validation"></div>
                      </div>
                    </div>
                    <div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
                      <div class="form-group">
                        <label style="opacity: 0;" for=""> s</label>
                          <select class="form-control" id="filter_city" name="filter_city">
                            <option value="">All City</option>
                            @if(isset($_GET['filter_city']))
                              <option selected="" value="{{$_GET['filter_city']}}">{{$_GET['filter_city']}}</option>
                            @endif
                          </select>
                          <div class="validation"></div>
                      </div>
                    </div>
                  @endif

                  @if(Auth::user()->roles[0]['slug'] != 'branch' && Auth::user()->roles[0]['slug'] != 'cso')
                    <div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
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
                    <div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
                      <div class="form-group">
                        <label for="">Filter By CSO</label>
                          <select class="form-control" id="filter_cso" name="filter_cso">
                            <option value="">All CSO</option>
                            @php
                              if(isset($_GET['filter_branch'])){
                                $csos = App\Cso::Where('branch_id', $_GET['filter_branch'])->where('active', true)->get();

                                foreach ($csos as $cso) {
                                  if(isset($_GET['filter_cso'])){
                                    if($_GET['filter_cso'] == $cso['id']){
                                      echo "<option selected=\"\" value=\"".$cso['id']."\">".$cso['code']." - ".$cso['name']."</option>";
                                      continue;
                                    }
                                  }
                                  echo "<option value=\"".$cso['id']."\">".$cso['code']." - ".$cso['name']."</option>";
                                }
                              }
                            @endphp
                          </select>
                          <div class="validation"></div>
                      </div>
                    </div>
                  @endif
              </div>


              @if(Auth::user()->roles[0]['slug'] != 'branch' && Auth::user()->roles[0]['slug'] != 'cso' && Auth::user()->roles[0]['slug'] != 'area-manager')
                <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
                  <div class="col-xs-6 col-sm-6" style="padding: 0;display: inline-block;">
                    <div class="form-group">
                      <button id="btn-filter" type="button" class="btn btn-gradient-primary m-1" name="filter" value="-"><span class="mdi mdi-filter"></span> Apply Filter</button>
                      <button id="btn-export" type="button" class="btn btn-gradient-info m-1" name="export" value="-"><span class="mdi mdi-file-document"></span> Export XLS</button>
                      <button id="btn-exportDate" type="button" class="btn btn-gradient-info m-1" name="export" data-toggle="modal" data-target="#datePickerHomeServiceModal" value="-"><span class="mdi mdi-file-document"></span> Export XLS with Date</button>
                    </div>
                  </div>
                </div>
              @endif

        				<div class="col-sm-12 col-md-12" style="padding: 0; border: 1px solid #ebedf2;">
                  <div class="col-xs-12 col-sm-11 col-md-6 table-responsive" id="calendarContainer" style="padding: 0; float: left;"></div>
              		<div class="col-xs-12 col-sm-11 col-md-6" id="organizerContainer" style="padding: 0; float: left;"></div>
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
  <!-- End Modal Add -->

  <!-- Modal View -->
  <div class="modal fade" id="viewHomeServiceModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">View Appointment</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <h5 style="text-align:center;"></h5>
                    {{ csrf_field() }}
                      <h5>Data Pelanggan</h5>
                      <div class="form-group">
                          <input type="text" name="no_member" class="form-control input-view" id="view-no_member" value=""/>
                          <div class="validation"></div>
                      </div>
                      <div class="form-group">
                          <input type="text" class="form-control input-view" name="name" id="view-name" value=""/>
                          <div class="validation"></div>
                      </div>
                      <div class="form-group">
                          <input type="number" class="form-control input-view" name="phone" id="view-phone" value=""/>
                          <div class="validation"></div>
                      </div>
                      <div class="form-group">
                          <input type="text" class="form-control input-view" name="city" id="view-city" value=""/>
                          <div class="validation"></div>
                      </div>
                      <div class="form-group">
                          <textarea class="form-control input-view" name="address" id="view-address" rows="5" value=""></textarea>
                          <div class="validation"></div>
                      </div>
                      <br>
                      <h5>Data CSO</h5>
                      <div class="form-group">
                          <select class="form-control input-view" id="view-branch" name="branch_id" value="">
                              <option selected disabled value="">Pilihan Cabang</option>

                              @foreach($branches as $branch)
                                  <option value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
                              @endforeach
                          </select>
                          <div class="validation"></div>
                      </div>
                      <div class="form-group">
                          <input type="text" class="form-control input-view cso" name="cso_id" id="view-cso" value="" style="text-transform:uppercase"/>
                          <div class="validation" id="validation_cso"></div>
                      </div>
                      <div class="form-group">
                          <input type="number" class="form-control input-view" name="cso_phone" id="view-cso_phone" value=""/>
                          <div class="validation"></div>
                      </div>
                      <div class="form-group">
                          <input type="text" class="form-control input-view cso" name="cso2_id" id="view-cso2" value="" style="text-transform:uppercase"/>
                          <div class="validation" id="validation_cso2"></div>
                      </div>

                      <br>
                      <h5>Waktu Home Service</h5>
                      <div class="form-group">
                          <input type="date" class="form-control input-view" name="date" id="view-date" value="" />
                          <div class="validation"></div>
                      </div>
                      <div class="form-group">
                          <input type="time" class="form-control input-view" name="time" id="view-time" value="" />
                          <div class="validation"></div>
                      </div>
              </div>
              <div class="modal-footer">
                <a id="url_share" href="" data-action="share/whatsapp/share" target="_blank"><button id="btn-share" type="button" class="btn btn-gradient-primary mr-2"><span class="mdi mdi-whatsapp" style="font-size: 18px;"></span> Share</button></a>
                <button class="btn btn-light" data-dismiss="modal" aria-label="Close">Cancel</button>
              </div>
            </div>
        </div>
    </div>
    <!-- End Modal View -->

	<!-- Modal Edit -->
	<div class="modal fade" id="editHomeServiceModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          	<div class="modal-content">
              <form id="actionEdit" class="forms-sample" method="POST" action="{{ route('update_homeService') }}">
              	<div class="modal-header">
                  <h5 class="modal-title">Edit Appointment</h5>
                		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  		<span aria-hidden="true">&times;</span>
                		</button>
              	</div>
              	<div class="modal-body">
                		<h5 style="text-align:center;"></h5>
      					    	{{ csrf_field() }}
                        <h5>Data Pelanggan</h5>
                        <div class="form-group">
                            <input type="text" name="no_member" class="form-control" id="edit-no_member" placeholder="No. Member (optional)"/>
                            <div class="validation"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" id="edit-name" placeholder="Nama" required data-msg="Mohon Isi Nama" />
                            <div class="validation"></div>
                        </div>
                        <div class="form-group">
                            <input type="number" class="form-control" name="phone" id="edit-phone" placeholder="Nomor Telepon" required data-msg="Mohon Isi Nomor Telepon" />
                            <div class="validation"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="city" id="edit-city" placeholder="Kota" required data-msg="Mohon Isi Kota" />
                            <div class="validation"></div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="address" id="edit-address" rows="5" required data-msg="Mohon Isi Alamat" placeholder="Alamat"></textarea>
                            <div class="validation"></div>
                        </div>
                        <br>
                        <h5>Data CSO</h5>
                        <div class="form-group">
                            <select class="form-control" id="edit-branch" name="branch_id" data-msg="Mohon Pilih Cabang" required>
                                <option selected disabled value="">Pilihan Cabang</option>

                                @foreach($branches as $branch)
                                    <option value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
                                @endforeach
                            </select>
                            <div class="validation"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control cso" name="cso_id" id="edit-cso" placeholder="Kode CSO" required data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase"/>
                            <div class="validation" id="validation_cso"></div>
                        </div>
                        <div class="form-group">
                            <input type="number" class="form-control" name="cso_phone" id="edit-cso_phone" placeholder="No. Telepon CSO" required data-msg="Mohon Isi Nomor Telepon" />
                            <div class="validation"></div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control cso" name="cso2_id" id="edit-cso2" placeholder="Kode Partner CSO (opsional)" style="text-transform:uppercase"/>
                            <div class="validation" id="validation_cso2"></div>
                        </div>

                        <br>
                        <h5>Waktu Home Service</h5>
                        <div class="form-group">
                            <input type="date" class="form-control" name="date" id="edit-date" placeholder="Tanggal Janjian" required data-msg="Mohon Isi Tanggal" />
                            <div class="validation"></div>
                        </div>
                        <div class="form-group">
                            <input type="time" class="form-control" name="time" id="edit-time" placeholder="Jam Janjian" required data-msg="Mohon Isi Jam" />
                            <div class="validation"></div>
                        </div>
    				  	</div>
              	<div class="modal-footer">
                  <button id="btn-edit" type="submit" class="btn btn-gradient-primary mr-2" name="id" value="-">Save</button>
                  <button class="btn btn-light" data-dismiss="modal" aria-label="Close">Cancel</button>
              	</div>
              </form>
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
                		<h5 style="text-align:center;">Are You Sure to Cancel this Appointment ?</h5>
              	</div>
              	<div class="modal-footer">
              		<form id="frmCancel" method="post" action="{{ route('update_homeService') }}">
                      {{csrf_field()}}
                        <input type="hidden" id="hiddenInput" name="cancel" value="1">
                      	<button type="submit" id="btn-cancel" class="btn btn-gradient-danger mr-2" name="id" value="-">Yes</button>
                  	</form>
                		<button type="button" data-dismiss="modal" class="btn btn-light">No</button>
              	</div>
            	</div>
          </div>
    </div>
    <!-- End Modal Delete -->

    <!-- Modal Cash -->
    <div class="modal fade" id="cashHomeServiceModal" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <form id="frmCash" method="post" action="{{ route('update_homeService') }}">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <h5 style="text-align:center;">Did You Manage to Get Cash ?</h5>
                      <br>
                      <div class="form-group mb-1">
                        <label for="">Description :</label>
                        <textarea class="form-control" name="cash_description" id="edit-cash_description" rows="5" required placeholder="Cash Description"></textarea>
                        <div class="validation"></div>
                      </div>
                  </div>
                  <div class="modal-footer footer-cash">
                    {{csrf_field()}}
                      <input type="hidden" id="idEditCash" name="id" value="-">
                      <button type="submit" id="btn-Cash" class="btn btn-gradient-success mr-2" name="cash" value="1">Yes</button>
                      <button type="submit" id="btn-NoCash" class="btn btn-gradient-danger mr-2" name="cash" value="0">No</button>
                  </div>
                </form>
              </div>
          </div>
    </div>
    <!-- End Modal Cash -->
    <!-- Modal Date Picker export Xls -->
  <div class="modal fade" id="datePickerHomeServiceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <label for="">Pick a Date</label>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Tanggal Awal</label>
              <input type="date" class="form-control" name="date" id="filter_startDate" placeholder="Awal Tanggal" required data-msg="Mohon Isi Tanggal" onload="getDate()" />
              <div class="validation"></div>
          </div>
          <div class="form-group">
            <label>Tanggal Akhir</label>
            <input type="date" class="form-control" name="date" id="filter_endDate" placeholder="Akhir Tanggal" required data-msg="Mohon Isi Tanggal" onload="getDate()"/>
            <div class="validation"></div>
        </div>
              
          </div>
        <div class="modal-footer">
                {{csrf_field()}}
                  <input type="hidden" id="hiddenInput" name="cancel" value="1">
                  <button type="submit" data-dismiss="modal" id="btn-exportByDate" class="btn btn-gradient-danger mr-2" name="id" value="-">Export</button>
              <button type="button" data-dismiss="modal" class="btn btn-light">No</button>
          </div>
        </div>
    </div>
  </div>
  <!-- End Modal Date Picker export Xls -->
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

        $canEdit = false;
        $canDelete = false;
        $canCash = false;
        if(Gate::check('detail-home_service')){
          $canEdit = true;
        }
        if(Gate::check('delete-home_service')){
          $canDelete = true;
        }
        if(Gate::check('edit-home_service')){
          $canCash = true;
        }
      @endphp

      //try tahun
      try{
        var kosonngan = data[{{$tahun}}][{{$bulan}}];
      }
      catch(e){
        data[{{$tahun}}] = {};
      }

      //try bulan
      try{
        var kosonngan = data[{{$tahun}}][{{$bulan}}][{{ $hari }}];
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
                dataId : "{{ $dataNya['id'] }}",
                canEdit : "{{ $canEdit }}",
                canDelete : "{{ $canDelete }}",
                canCash : "{{ $canCash }}"
              });
      }
      catch (e){
        data[{{ $tahun }}][{{ $bulan }}][{{ $hari }}] = [];
        data[{{ $tahun }}][{{ $bulan }}][{{ $hari }}].push({
                startTime: "{{ $jam }}",
                endTime: "{{ $jam }}",
                title: "<a href=\"{{ Route('homeServices_success') }}?code={{ $dataNya['code'] }}\" target=\"_blank\">{{ $dataNya['code'] }}</a>",
                desc: "{{ $dataNya['name'] }} - {{ $dataNya['phone'] }}<br>Branch : {{ $dataNya->branch['code'] }}<br>CSO : {{ $dataNya->cso['name'] }}",
                dataId : "{{ $dataNya['id'] }}",
                canEdit : "{{ $canEdit }}",
                canDelete : "{{ $canDelete }}",
                canCash : "{{ $canCash }}"
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

        if(!(currentYear == maxDate.getFullYear() && currentYear == minDate.getFullYear())){
          if(currentYear == maxDate.getFullYear()){
            $('#calendarContainer-year-next').css('display', 'none');
            $('#calendarContainer-year-back').css('display', 'flex');
          }
          else if(currentYear == minDate.getFullYear()){
            $('#calendarContainer-year-next').css('display', 'flex');
            $('#calendarContainer-year-back').css('display', 'none');
          }
        }
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

        if(!(currentYear == maxDate.getFullYear() && currentYear == minDate.getFullYear())){
          if(currentYear == maxDate.getFullYear()){
            $('#calendarContainer-year-next').css('display', 'none');
            $('#calendarContainer-year-back').css('display', 'flex');
          }
          else if(currentYear == minDate.getFullYear()){
            $('#calendarContainer-year-next').css('display', 'flex');
            $('#calendarContainer-year-back').css('display', 'none');
          }
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
        }
      },
  );

  //cek ada tahun depan atau belakang
  $(document).ready(function(e){
    if(nowDate.getFullYear() == minDate.getFullYear()){
      $('#calendarContainer-year-back').css('display', 'none');
    }
    if(nowDate.getFullYear() == maxDate.getFullYear()){
      $('#calendarContainer-year-next').css('display', 'none');
    }

    //cek cso
    $(".cso").on("input", function(){
      var txtCso = $(this).val();
      console.log(txtCso);
      var temp = $(this);
      $.get( '{{route("fetchCso")}}', { txt: txtCso })
      .done(function( result ) {
          var bool = false;
          console.log(result);
          if (result == 'true'){
              $(temp).parent().children('.validation').html('Kode CSO Benar');
              $(temp).parent().children('.validation').css('color', 'green');
              bool = true;
          }
          else{
              $(temp).parent().children('.validation').html('Kode CSO Salah');
              $(temp).parent().children('.validation').css('color', 'red');
          }
      });
    });

    $("#filter_province").on("change", function(){
      var id = $(this).val();
      $( "#filter_city" ).html("");
      $.get( '{{ route("fetchCity", ['province' => ""]) }}/'+id )
      .done(function( result ) {
          result = result['rajaongkir']['results'];
          var arrCity = "<option selected value=\"\">All City</option>";
          if(result.length > 0){
              $.each( result, function( key, value ) {
                  if(value['type'] == "Kota"){                            
                      arrCity += "<option value=\"Kota "+value['city_name']+"\">Kota "+value['city_name']+"</option>";
                  }
              });
              $( "#filter_city" ).append(arrCity);
            }
        });
    });

    $("#filter_branch").on("change", function(){
      var id = $(this).val();
      $.get( '{{ route("fetchCsoByIdBranch", ['branch' => ""]) }}/'+id )
      .done(function( result ) {
          $( "#filter_cso" ).html("");
          var arrCSO = "<option selected value=\"\">All CSO</option>";
          if(result.length > 0){
              $.each( result, function( key, value ) {
                arrCSO += "<option value=\""+value['id']+"\">"+value['code']+" - "+value['name']+"</option>";
              });
              $( "#filter_cso" ).append(arrCSO);
            }
        });
      if(id == ""){
        $( "#filter_cso" ).html("<option selected value=\"\">All CSO</option>");
      }
    });
    $("#btn-exportByDate").on("click", function(){
      var urlParamArray = new Array();
      var urlParamStr = "";
      if($('#filter_city').val() != ""){
        urlParamArray.push("filter_city=" + $('#filter_city').val());
      }
      if($('#filter_branch').val() != ""){
        urlParamArray.push("filter_branch=" + $('#filter_branch').val());
      }
      if($('#filter_cso').val() != ""){
        urlParamArray.push("filter_cso=" + $('#filter_cso').val());
      }
      if($('#search').val() != ""){
        urlParamArray.push("filter_search=" + $('#search').val());
      }
      if($('#filter_startDate').val() != "" && $('#filter_endDate').val() != ""){
        urlParamArray.push("filter_startDate=" + $('#filter_startDate').val());
        urlParamArray.push("filter_endDate=" + $('#filter_endDate').val());
      }
      for (var i = 0; i < urlParamArray.length; i++) {
        if (i === 0) {
          urlParamStr += "?" + urlParamArray[i]
        } else {
          urlParamStr += "&" + urlParamArray[i]
        }
      }
      window.location.href = "{{route('homeservice_export-to-xls-by-date')}}" + urlParamStr;   
    });
    $("#btn-export").on("click", function(){
      var urlParamArray = new Array();
      var urlParamStr = "";
      if($('#filter_city').val() != ""){
        urlParamArray.push("filter_city=" + $('#filter_city').val());
      }
      if($('#filter_branch').val() != ""){
        urlParamArray.push("filter_branch=" + $('#filter_branch').val());
      }
      if($('#filter_cso').val() != ""){
        urlParamArray.push("filter_cso=" + $('#filter_cso').val());
      }
      if($('#search').val() != ""){
        urlParamArray.push("filter_search=" + $('#search').val());
      }
      for (var i = 0; i < urlParamArray.length; i++) {
        urlParamStr += "&" + urlParamArray[i]
      }

      var tgl = organizer.calendar.date;
      var tahun = tgl.getFullYear();
      var hari = tgl.getDate();if(hari < 9)  hari="0" +hari;
      var bulan = tgl.getMonth()+1;if(bulan < 9)  bulan="0" +bulan;
      tgl = tahun+"-"+bulan+"-"+hari;
      window.location.href = "{{route('homeservice_export-to-xls')}}?date=" + tgl + urlParamStr;   
    });

  });
};

//ajax ambil data detail home service
$(document).on("click", ".btn-homeservice-edit", function(e){
  $.ajax({
    headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    type: 'get',
    url: "{{ route('edit_homeService') }}",
    data: {
        'id': e.target.value,
    },
    success: function(result){
      result = result['result'];
      var tgl = new Date(result['appointment']);
      console.log(tgl.getHours());
      var tahun = tgl.getFullYear();
      var hari = tgl.getDate();if(hari < 9)  hari="0" +hari;
      var bulan = tgl.getMonth()+1;if(bulan < 9)  bulan="0" +bulan;
      var jam = tgl.getHours();if(jam < 9)  jam="0" +jam;
      var menit = tgl.getMinutes();if(menit < 9)  menit="0" +menit;

      tgl = tahun+"-"+bulan+"-"+hari;

      //fetching cso
      $.ajax({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        type: 'get',
        url: "{{ route('fetchCsoById') }}",
        data: {
            'id': result['cso_id'],
        },
        success: function(data1){
          $('#edit-cso').val(data1['code']);
        },
      });
      //fetching cso2
      $.ajax({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        type: 'get',
        url: "{{ route('fetchCsoById') }}",
        data: {
            'id': result['cso2_id'],
        },
        success: function(data1){
          $('#edit-cso2').val(data1['code']);
        },
      });
      //fetching branch
      $.ajax({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        type: 'get',
        url: "{{ route('fetchBranchById') }}",
        data: {
            'id': result['branch_id'],
        },
        success: function(data1){
          $('#edit-branch').val(data1['id']);
        },
      });

      $('#edit-no_member').val(result['no_member']);
      $('#edit-name').val(result['name']);
      $('#edit-phone').val(result['phone']);
      $('#edit-city').val(result['city']);
      $('#edit-address').val(result['address']);
      $('#edit-cso_phone').val(result['cso_phone']);
      $('#edit-date').val(tgl);
      $('#edit-time').val(jam+":"+menit);
      $('#btn-edit').val(result['id']);

      @if(Auth::user()->roles[0]['slug'] == 'cso')
        $('#edit-cso').attr('readonly','true');
      @endif
    },
  });
});

//ajax ambil data detail home service KHUSUS VIEW
$(document).on("click", ".btn-homeservice-view", function(e){
  $('.input-view').removeAttr('readonly');
  $('.input-view').removeAttr('disabled');
  $.ajax({
    headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    type: 'get',
    url: "{{ route('detail_homeService') }}",
    data: {
        'id': e.target.value,
    },
    success: function(result){
      result = result['result'];
      var tgl = new Date(result['appointment']);
      console.log(tgl.getHours());
      var tahun = tgl.getFullYear();
      var hari = tgl.getDate();if(hari < 9)  hari="0" +hari;
      var bulan = tgl.getMonth()+1;if(bulan < 9)  bulan="0" +bulan;
      var jam = tgl.getHours();if(jam < 9)  jam="0" +jam;
      var menit = tgl.getMinutes();if(menit < 9)  menit="0" +menit;

      tgl = tahun+"-"+bulan+"-"+hari;

      //fetching cso
      $.ajax({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        type: 'get',
        url: "{{ route('fetchCsoById') }}",
        data: {
            'id': result['cso_id'],
        },
        success: function(data1){
          $('#view-cso').val(data1['code']);
        },
      });
      //fetching cso2
      $.ajax({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        type: 'get',
        url: "{{ route('fetchCsoById') }}",
        data: {
            'id': result['cso2_id'],
        },
        success: function(data1){
          $('#view-cso2').val(data1['code']);
        },
      });
      //fetching branch
      $.ajax({
        headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        type: 'get',
        url: "{{ route('fetchBranchById') }}",
        data: {
            'id': result['branch_id'],
        },
        success: function(data1){
          $('#view-branch').val(data1['id']);
        },
      });

      $('#view-no_member').val(result['no_member']);
      $('#view-name').val(result['name']);
      $('#view-phone').val(result['phone']);
      $('#view-city').val(result['city']);
      $('#view-address').val(result['address']);
      $('#view-cso_phone').val(result['cso_phone']);
      $('#view-date').val(tgl);
      $('#view-time').val(jam+":"+menit);
      $('.input-view').attr('readonly', true);
      $('.input-view').attr('disabled', true);
      $('#url_share').attr('href', "whatsapp://send?text={{ Route('homeServices_success') }}?code="+result['code']);
    },
  });
});

//untuk cancel appointemtn
$(document).on("click", ".btn-homeservice-cancel", function(e){
  $('#btn-cancel').val(e.target.value);
});

//untuk Cash atau gk appointemtn
$(document).on("click", ".btn-homeservice-cash", function(e){
  $('#idEditCash').val(e.target.value);
  $('.footer-cash').removeClass('d-none');
  $('#edit-cash_description').val("");
  $('#edit-cash_description').removeAttr('readonly');

  $.ajax({
    headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    type: 'get',
    url: "{{ route('edit_homeService') }}",
    data: {
        'id': e.target.value,
    },
    success: function(result){
      result = result['result'];
      if(result['cash'] != null){
        $('.footer-cash').addClass('d-none');
        $('#edit-cash_description').val(result['cash_description']);
        $('#edit-cash_description').attr('readonly','true');
      }
    },
  });
});

$(document).on("click", "#btn-filter", function(e){
  var urlParamArray = new Array();
  var urlParamStr = "";
  if($('#filter_city').val() != ""){
    urlParamArray.push("filter_city=" + $('#filter_city').val());
  }
  if($('#filter_branch').val() != ""){
    urlParamArray.push("filter_branch=" + $('#filter_branch').val());
  }
  if($('#filter_cso').val() != ""){
    urlParamArray.push("filter_cso=" + $('#filter_cso').val());
  }
  if($('#search').val() != ""){
    urlParamArray.push("filter_search=" + $('#search').val());
  }
  for (var i = 0; i < urlParamArray.length; i++) {
    if (i === 0) {
      urlParamStr += "?" + urlParamArray[i]
    } else {
      urlParamStr += "&" + urlParamArray[i]
    }
  }

  window.location.href = "{{route('admin_list_homeService')}}" + urlParamStr;
});

</script>
@endsection
