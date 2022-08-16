<?php
$menu_item_page = "technician";
$menu_item_second = "list_technician";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="{{ asset('css/admin/calendarorganizer.css?v=' . filemtime('css/admin/calendarorganizer.css')) }}">
<style>
    .cjslib-day-indicator {
        color: #ffc107 !important;
        background-color: #1bcfb4 !important;
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

    .titleAppoin {font-weight: bolder;}
    .timeContainerDiv{flex: 1 !important;}
    .paragrapContainerDiv{
        flex-direction: column;
        align-items: normal !important;
    }
    .iconContainerDiv{flex: 1 !important;}
    .cjslib-day-indicator {
        color: #ffa000; background-color: #ffa000;
    }
    .cjslib-indicator-type-numeric {color: #ffffff;}
    .cjslib-day.cjslib-day-today > .cjslib-day-num {
        border-color: #ffa000 !important;
    }
    .table-bordered th, .table-bordered td {
        border: 1px solid darkgray !important;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
	<div class="content-wrapper">
        <div class="page-header">
  			<h3 class="page-title">Technician Schedule</h3>
  			<nav aria-label="breadcrumb">
    			<ol class="breadcrumb">
      				<li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#cso-dd"
                            aria-expanded="false"
                            aria-controls="cso-dd">
                            Technician Schedule
                        </a>
                    </li>
      				<li class="breadcrumb-item active" aria-current="page">
                        List Technician Schedule
                    </li>
    			</ol>
  			</nav>
		</div>

        <div class="row">
  			<div class="col-12 grid-margin stretch-card">
    			<div class="card">
      				<div class="card-body">
      					<h5 style="margin-bottom: 0.5em;">Appointment</h5>
                        <div class="col-xs-12 col-sm-12 row"
                            style="margin: 0; padding: 0;">
                            <div class="col-xs-6 col-sm-3"
                                style="padding: 0; display: inline-block;">
                                <div class="form-group">
                                    <label for="filter_province">
                                        Filter By City
                                    </label>
                                    <select class="form-control"
                                        id="filter_province"
                                        onchange="setCity(this)"
                                        name="filter_province">
                                        <option value="" selected="">
                                            All Province
                                        </option>
                                        @php
                                        $result = RajaOngkir::FetchProvince();
                                        $result = $result['rajaongkir']['results'];

                                        if (sizeof($result) > 0) {
                                            foreach ($result as $value) {
                                                $terpilihNya = "";

                                                if (isset($_GET['filter_province'])) {
                                                    if ($_GET['filter_province'] == $value['province_id']) {
                                                        $terpilihNya = "selected";
                                                    }
                                                }

                                                echo "<option value=\""
                                                    . $value['province_id']
                                                    . "\""
                                                    . $terpilihNya
                                                    . ">"
                                                    . $value['province']
                                                    . "</option>";
                                            }
                                        }
                                        @endphp
                                    </select>
                                    <div class="validation"></div>
                                </div>

                                <div class="form-group">
                                    <input class="form-control"
                                        id="search"
                                        name="filter_search"
                                        {!! isset($_GET['filter_search']) ? 'value="' . $_GET['filter_search'] . '"' : "" !!}
                                        placeholder="Search By Name, Phone" />
                                    <div class="validation"></div>
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-3"
                                style="padding: 0;display: inline-block;">
                                <div class="form-group">
                                    <label style="opacity: 0;" for=""> s</label>
                                    <select class="form-control"
                                        id="filter_city"
                                        onchange="setDistrict(this)"
                                        name="filter_city">
                                        <option value="">All City</option>
                                        @php
                                        if (isset($_GET['filter_province'])) {
                                            $result = RajaOngkir::FetchCity($_GET['filter_province']);
                                            $result = $result['rajaongkir']['results'];
                                            $arrCity = [];
                                            $arrCity[0] = "<option disabled value=\"\">Pilihan Kabupaten</option>";
                                            $arrCity[1] = "<option disabled value=\"\">Pilihan Kota</option>";

                                            if (sizeof($result) > 0) {
                                                foreach ($result as $value) {
                                                    $terpilihNya = "";
                                                    if (isset($_GET['filter_city'])) {
                                                        if ($_GET['filter_city'] == $value['city_id']) {
                                                            $terpilihNya = "selected";
                                                        }
                                                    }

                                                    if ($value['type'] == "Kabupaten") {
                                                        $arrCity[0] .= "<option value=\""
                                                            . $value['city_id']
                                                            . "\""
                                                            . $terpilihNya
                                                            . ">"
                                                            . $value['type']
                                                            . " "
                                                            . $value['city_name']
                                                            . "</option>";
                                                    } else {
                                                        $arrCity[1] .= "<option value=\""
                                                            . $value['city_id']
                                                            . "\""
                                                            . $terpilihNya
                                                            . ">"
                                                            . $value['type']
                                                            . " "
                                                            . $value['city_name']
                                                            . "</option>";
                                                    }
                                                }

                                                echo $arrCity[0];
                                                echo $arrCity[1];
                                            }
                                        }
                                        @endphp
                                    </select>
                                    <div class="validation"></div>
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-3"
                                style="padding: 0;display: inline-block;">
                                <div class="form-group">
                                    <label style="opacity: 0;" for=""> s</label>
                                    <select class="form-control"
                                        id="filter_district"
                                        name="filter_district">
                                        <option value="">
                                            All District
                                        </option>
                                        @php
                                        if (isset($_GET['filter_city'])) {
                                            $result = RajaOngkir::FetchDistrict($_GET['filter_city']);
                                            $result = $result['rajaongkir']['results'];

                                            if (sizeof($result) > 0) {
                                                foreach ($result as $value) {
                                                    $terpilihNya = "";

                                                    if (isset($_GET['filter_district'])) {
                                                        if ($_GET['filter_district'] == $value['subdistrict_id']) {
                                                            $terpilihNya = "selected";
                                                        }
                                                    }

                                                    echo "<option value=\""
                                                        . $value['subdistrict_id']
                                                        . "\""
                                                        . $terpilihNya
                                                        . ">"
                                                        . $value['subdistrict_name']
                                                        . "</option>";
                                                }
                                            }
                                        }
                                        @endphp
                                    </select>
                                    <div class="validation"></div>
                                </div>
                            </div>

                            <div class="col-xs-6 col-sm-3"
                                style="padding: 0;display: inline-block;">
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
                        </div>

                        <div class="col-xs-12 col-sm-12 row"
                            style="margin: 0;padding: 0;">
                            <div class="col-xs-6 col-sm-6"
                                style="padding: 0; display: inline-block;">
                                <div class="form-group">
                                    <button id="btn-filter"
                                        type="button"
                                        class="btn btn-gradient-primary m-1"
                                        name="filter"
                                        onclick="submitApplyFilter()"
                                        value="-">
                                        <span class="mdi mdi-filter"></span>
                                        Apply Filter
                                    </button>
                                    <button id="btn-export"
                                        type="button"
                                        class="btn btn-gradient-info m-1"
                                        name="export"
                                        onclick="submitExportXLS()"
                                        value="-">
                                        <span class="mdi mdi-file-document"></span>
                                        Export XLS
                                    </button>
                                </div>
                            </div>
                        </div>

                        <?php
                        $province = null;
                        $city = null;
                        $district = null;
                        $search = null;
                        $branchCode = null;
                        $CSOCode = null;

                        if (isset($_GET["filter_province"])) {
                            $province = $_GET["filter_province"];
                        }

                        if (isset($_GET["filter_city"])) {
                            $city = $_GET["filter_city"];
                        }

                        if (isset($_GET["filter_district"])) {
                            $district = $_GET["filter_district"];
                        }

                        if (isset($_GET["filter_search"])) {
                            $search = $_GET["filter_search"];
                        }

                        if (isset($_GET["filter_cso"])) {
                            $CSOCode = $_GET["filter_cso"];
                        }
                        ?>

                        <div class="col-xs-12 col-sm-12 col-md-12 table-responsive p-0" id="calendar-container">
                            <div class="cjslib-calendar cjslib-size-small">
                                <div class="cjslib-year"
                                    style="background-color: rgb(255, 160, 0); color: rgb(255, 236, 179);">
                                    <span id="calendar-container-year">
                                        <?php echo date("Y"); ?>
                                    </span>
                                </div>

                                <?php
                                $currentMonth = date("n");
                                $currentYear = date("Y");
                                $previousMonth = (int) $currentMonth - 1;
                                $nextMonth = (int) $currentMonth + 1;
                                $month = date("m");
                                $nextYear = date("Y");
                                $previousYear = date("Y");

                                $currentMonth_text = date("F");

                                if(isset($_GET['isSubmission'])){
                                    $get_appointment = strtotime($_GET['appointment']);

                                    $currentMonth = date("n", $get_appointment);
                                    $previousMonth = (int) $currentMonth - 1;
                                    $nextMonth = (int) $currentMonth + 1;
                                    $month = date("m", $get_appointment);
                                    $nextYear = date("Y", $get_appointment);
                                    $previousYear = date("Y", $get_appointment);

                                    $currentMonth_text = date("F", $get_appointment);
                                }

                                if ($previousMonth < 10) {
                                    $previousMonth = "0" . $previousMonth;
                                }
                                if ($nextMonth < 10) {
                                    $nextMonth = "0". $nextMonth;
                                }

                                if ($previousMonth < 1) {
                                    $previousMonth = "12";
                                    $previousYear--;
                                }
                                if ($nextMonth > 12) {
                                    $nextMonth = "01";
                                    $nextYear++;
                                }

                                ?>
                                <div class="cjslib-month"
                                    style="background-color: rgb(255, 193, 7); color: rgb(255, 255, 255);">
                                    <div id="calendarContainer-month-back">
                                        <svg style="width: 24px; height: 24px;"
                                            viewBox="0 0 24 24"
                                            id="previous-month"
                                            data-value="<?php echo $previousYear . "-" . $previousMonth . "-01"; ?>"
                                            data-province="<?php echo $province; ?>"
                                            data-city="<?php echo $city; ?>"
                                            data-district="<?php echo $district ?>"
                                            data-search="<?php echo $search; ?>"
                                            data-branch="<?php echo $branchCode; ?>"
                                            data-cso="<?php echo $CSOCode; ?>"
                                            onclick="changeMonth(this)">
                                            <path fill="#ffecb3" d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z"></path>
                                        </svg>
                                    </div>
                                    <span id="calendar-container-month">
                                        <?php echo $currentMonth_text; ?>
                                    </span>
                                    <div id="calendarContainer-month-next">
                                        <svg style="width: 24px; height: 24px;"
                                            viewBox="0 0 24 24"
                                            id="next-month"
                                            data-value="<?php echo $nextYear . "-" . $nextMonth . "-01"; ?>"
                                            data-province="<?php echo $province; ?>"
                                            data-city="<?php echo $city; ?>"
                                            data-district="<?php echo $district ?>"
                                            data-search="<?php echo $search; ?>"
                                            data-branch="<?php echo $branchCode; ?>"
                                            data-cso="<?php echo $CSOCode; ?>"
                                            onclick="changeMonth(this)">
                                            <path fill="#ffecb3" d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z"></path>
                                        </svg>
                                    </div>
                                </div>

                                <div class="cjslib-labels"
                                    style="background-color: rgb(255, 160, 0); color: rgb(255, 236, 179);">
                                    <span id="calendarContainer-label-0">
                                        Sun
                                    </span>
                                    <span id="calendarContainer-label-1">
                                        Mon
                                    </span>
                                    <span id="calendarContainer-label-2">
                                        Tue
                                    </span>
                                    <span id="calendarContainer-label-3">
                                        Wed
                                    </span>
                                    <span id="calendarContainer-label-4">
                                        Thu
                                    </span>
                                    <span id="calendarContainer-label-5">
                                        Fri
                                    </span>
                                    <span id="calendarContainer-label-6">
                                        Sat
                                    </span>
                                </div>

                                <div class="cjslib-days" id="appointment-per-day">
                                    <?php
                                    $dayCounter = 1;
                                    $echoSwitch = false;
                                    $todayDate = (int) date("j");
                                    $month = date("m");
                                    $year = date("Y");
                                    $maxDate = (int) date("t");
                                    $startDay = (int) date("w", strtotime(date("Y-m-01")));
                                    $rowCount = 0;
                                    $iMax = 28;
                                    $temp_search = date("Y-m-");

                                    if (isset($_GET['isSubmission'])) {
                                        $get_appointment = strtotime($_GET['appointment']);

                                        $todayDate = (int) date("j", $get_appointment);
                                        $month = date("m", $get_appointment);
                                        $year = date("Y", $get_appointment);
                                        $maxDate = (int) date("t", $get_appointment);
                                        $startDay = (int) date("w", strtotime(date("Y-m-01", $get_appointment)));

                                        $temp_search = date("Y-m-", $get_appointment);
                                    }

                                    $startMonthStartDay = $startDay + $maxDate;

                                    if ($startMonthStartDay > 28) {
                                        $iMax = 35;
                                    }
                                    if ($startMonthStartDay > 35) {
                                        $iMax = 42;
                                    }
                                    for ($i = 0; $i < $iMax; $i++) {
                                        if ($rowCount === 0) {
                                            echo '<div class="cjslib-row">';
                                        }

                                        if ($i === $startDay) {
                                            $echoSwitch = true;
                                        }

                                        if (!$echoSwitch) {
                                            echo '<label class="calendarContainer cjslib-day cjslib-day-diluted" data-i-value="' . $i . '">'
                                                . '</label>';
                                        }

                                        if ($echoSwitch) {
                                            if ($dayCounter <= $maxDate) {
                                                echo '<input class="cjslib-day-radios" '
                                                    . 'type="radio" '
                                                    . 'name="calendarContainer-day-radios" '
                                                    . 'id="calendarContainer-day-radio-' . $dayCounter . '" '
                                                    . 'data-date="' . $dayCounter . '" '
                                                    . 'data-month="' . $month . '"'
                                                    . 'data-year="' . $year . '" '
                                                    . 'data-province="' . $province . '" '
                                                    . 'data-city="' . $city . '" '
                                                    . 'data-district="' . $district . '" '
                                                    . 'data-search="' . $search . '" '
                                                    . 'data-cso="' . $CSOCode . '" ';

                                                echo 'onclick="changeDate(this)"'
                                                    . '/>';

                                                echo '<label class="calendarContainer cjslib-day cjslib-day-listed';

                                                if ($dayCounter === $todayDate) {
                                                    echo ' cjslib-day-today';
                                                }

                                                echo '" for="calendarContainer-day-radio-' . $dayCounter . '" '
                                                    . 'id="calendarContainer-day-' . $dayCounter . '">';

                                                echo '<span class="cjslib-day-num" '
                                                    . 'id="calendarContainer-day-num-' . $dayCounter . '">'
                                                    . $dayCounter
                                                    . '</span>';

                                                $dayWithZero = $dayCounter;
                                                if ($dayCounter < 10) {
                                                    $dayWithZero = "0" . $dayCounter;
                                                }


                                                if (!empty($currentMonthDataCount)) {
                                                    foreach ($currentMonthDataCount as $key => $value) {
                                                        if ($value->appointment_date === $temp_search . $dayWithZero) {
                                                            echo '<span class="cjslib-day-indicator cjslib-indicator-pos-bottom cjslib-indicator-type-numeric" '
                                                                . 'id="calendarContainer-day-indicator-' . $dayCounter . '">';
                                                            echo $value->data_count;
                                                            echo '</span>';

                                                            unset($currentMonthDataCount[$key]);

                                                            break 1;
                                                        }
                                                    }
                                                }

                                                echo "</label>";

                                                $dayCounter++;
                                            }

                                            if ($dayCounter > $maxDate) {
                                                $echoSwitch = false;
                                            }
                                        }

                                        $rowCount++;

                                        if ($rowCount === 7) {
                                            echo '</div>';
                                            $rowCount = 0;
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <br>

                        <div class="col-xs-12 col-sm-12 col-md-12"
                            id="organizerContainer"
                            style="padding: 0; float: left;">
                            <div class="cjslib-events cjslib-size-small">
                                <div class="cjslib-date" style="background-color: rgb(255, 160, 0); color: white;">
                                    <span id="organizer-container-date">
                                        <?php echo date("F j, Y"); ?>
                                    </span>
                                </div>
                                <div class="cjslib-rows" id="organizerContainer-list-container">
                                    <?php if (!$currentMonthDataCount): ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center;">
                                                            No.
                                                        </th>
                                                        <th style="text-align: center;">
                                                            Appointment
                                                        </th>
                                                        <th style="text-align: center;">
                                                            Detail
                                                        </th>
                                                        <th colspan="4" style="text-align: center;">
                                                            Action
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="appointment-data">
                                                    <ol class="cjslib-list"
                                                        id="organizerContainer-list">
                                                        <div class="cjslib-list-placeholder">
                                                            <li style="text-align:center; margin-top: 1em;">
                                                                No appointments on this day.
                                                            </li>
                                                        </div>
                                                    </ol>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center;">
                                                            No.
                                                        </th>
                                                        <th style="text-align: center;">
                                                            Appointment
                                                        </th>
                                                        <th style="text-align: center;">
                                                            Detail
                                                        </th>
                                                        <th colspan="4"
                                                            style="text-align: center;">
                                                            Action
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="appointment-data">
                                                    <?php $i = 1; ?>
                                                    <?php foreach ($currentDayData as $dayData): ?>
                                                        <tr>
                                                            <td style="text-align: center">
                                                                <?php echo $i; ?>
                                                            </td>
                                                            <td style="text-align: center">
                                                                <?php
                                                                $time = new DateTime($dayData->appointment);
                                                                echo $time->format("H:i");
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <p class="titleAppoin">
                                                                    <?php
                                                                    if ($dayData->hs_code) {
                                                                        echo '<a href="'
                                                                        . route('homeServices_success')
                                                                        . '?code='
                                                                        . $dayData->hs_code
                                                                        . '" target="_blank">';
                                                                        echo $dayData->hs_code;
                                                                        echo '</a><br>';
                                                                    }
                                                                    if ($dayData->s_code) {
                                                                        echo '<a href="'
                                                                        . route('detail_service', $dayData->s_id)
                                                                        . '" target="_blank">'
                                                                        . $dayData->s_code
                                                                        . '</a>';
                                                                    }
                                                                    ?>
                                                                </p>
                                                                <p class="descAppoin">
                                                                    <?php
                                                                        echo $dayData->customer_name
                                                                            . ' - '
                                                                            . $dayData->customer_phone;
                                                                    ?>
                                                                    <br>
                                                                    <?php
                                                                    echo 'D/O: ' . $dayData->d_o;
                                                                    ?>
                                                                    <br>
                                                                    <?php
                                                                    echo 'CSO Teknisi: ' . $dayData->cso_name;
                                                                    ?>
                                                                    <br>
                                                                    <?php
                                                                        echo '<strong>Data Product Service: </strong><br>';
                                                                        foreach ($dayData->product_technician_schedule_withProduct as $key => $product_ts) {
                                                                            echo $product_ts->product['name'] ?? $product_ts->other_product . '<br>';
                                                                        }
                                                                    ?>
                                                                </p>
                                                            </td>
                                                            <td style="text-align: center">
                                                                <?php
                                                                if (Gate::check('detail-technician_schedule')) {
                                                                    echo '<button '
                                                                    . 'class="btnappoint btn-gradient-primary mdi mdi-eye btn-homeservice-view" '
                                                                    . 'type="button" '
                                                                    . 'data-toggle="modal" '
                                                                    . 'data-target="#viewTechnicianScheduleModal" '
                                                                    . 'onclick="clickView(this)" '
                                                                    . 'value="' . $dayData->id . '">'
                                                                    . '</button>';
                                                                }
                                                                ?>
                                                            </td>
                                                            <td style="text-align: center">
                                                                <?php
                                                                if (Gate::check('edit-technician_schedule')) {
                                                                    echo "<a "
                                                                        . 'class="btnappoint btn-gradient-info mdi mdi-border-color btn-homeservice-edit"'
                                                                        . "href='" . route('edit_technician_schedule', ['id' => $dayData->id])
                                                                        . "'></a>";
                                                                }
                                                                ?>
                                                            </td>
                                                            <td style="text-align: center">
                                                                <?php
                                                                if (
                                                                    Gate::check('delete-technician_schedule')
                                                                ) {
                                                                    echo '<button '
                                                                        . 'class="btnappoint btn-gradient-danger mdi mdi-delete btn-homeservice-cancel" '
                                                                        . 'type="button" '
                                                                        . 'data-toggle="modal" '
                                                                        . 'data-target="#deleteTechnicianScheduleModal" '
                                                                        . 'onclick="clickCancel(this)" '
                                                                        . 'value="' . $dayData->id . '">'
                                                                        . '</button>';
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <?php $i++; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- partial -->

    <!-- Modal View -->
    <div class="modal fade"
        id="viewTechnicianScheduleModal"
        tabindex="-1"
        role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">View Appointment</h5>
                    <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 450px; overflow-y:scroll;">
                    <table style="width: 90%; margin: auto;">
                        <tr>
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Home Service Code: </td>
                            <td id="view_code_homeservice" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>
                        <tr>
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Service Code: </td>
                            <td id="view_code_service" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>

                        <tr><td style="padding-top: 1em;"></td></tr>

                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Nama: </td>
                            <td id="view-name" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>
                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">No. Telp: </td>
                            <td id="view-phone" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>
                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Provinsi: </td>
                            <td id="view-province" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>
                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Kota/Kabupaten: </td>
                            <td id="view-city" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>
                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Kecamatan: </td>
                            <td id="view-distric" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>
                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Alamat: </td>
                            <td id="view-address" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>
                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">D/O: </td>
                            <td id="view-d_o" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>

                        <tr><td style="padding-top: 1em;"></td></tr>

                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Kode CSO Teknisi: </td>
                            <td id="view-cso" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>

                        <tr><td style="padding-top: 1em;"></td></tr>

                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Tanggal: </td>
                            <td id="view-date" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>
                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Jam: </td>
                            <td id="view-time" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>
                    </table>
                    <div class="mt-3" id="view-technician-schedule-product">
                    </div>
                </div>
                <div class="modal-footer">
                    <a id="create_service"
                        href="">
                        <button id="btn-share"
                            type="button"
                            class="btn btn-gradient-primary mr-2">
                            Add Service
                        </button>
                    </a>
                    <a id="url_share"
                        href=""
                        data-action="share/whatsapp/share"
                        target="_blank">
                        <button id="btn-share"
                            type="button"
                            class="btn btn-gradient-primary mr-2 d-none">
                            <span class="mdi mdi-whatsapp"
                                style="font-size: 18px;">
                            </span>
                            Share
                        </button>
                    </a>
                    <button class="btn btn-light"
                        data-dismiss="modal"
                        aria-label="Close">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal View -->

    <!-- Modal Delete -->
    <div class="modal fade"
        id="deleteTechnicianScheduleModal"
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
                    <form id="frmCancel"
                        method="post"
                        action="{{ route("delete_technician_schedule") }}">
                        @csrf
                        <button type="submit"
                            id="btn-cancel"
                            class="btn btn-gradient-danger mr-2"
                            name="id"
                            value="-">
                            Yes
                        </button>
                    </form>
                    <button type="button"
                        data-dismiss="modal"
                        class="btn btn-light">
                        No
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Delete -->
</div>
@endsection

@section('script')
<script type="application/javascript">

$(document).ready(function(){
    //load modal Acc Cancel HS
    @if(isset($_GET['id_ts']))
        clickView({{$_GET['id_ts']}});
    @elseif(isset($_GET['id_detail_ts']))
        clickView({{$_GET['id_detail_ts']}});
    @endif
    //end load modal Acc Cancel HS
});

{{-- Mendapatkan CSRF Token --}}
function getCSRF() {
    {{-- Mendapatkan semua elemen dengan tag meta --}}
    const getMeta = document.getElementsByTagName("meta");
    let metaCSRF = "";

    {{-- Looping meta --}}
    for (let i = 0; i < getMeta.length; i++) {
        {{-- Jikanamametaadalah"csrf-token" --}}
        if (getMeta[i].getAttribute("name") === "csrf-token") {
            {{-- Mengisi metaCSRF dengan token csrf --}}
            metaCSRF = getMeta[i].getAttribute("content");

            {{-- Keluar dari loop karena "csrf-token" sudah ditemukan --}}
            break;
        }
    }

    return metaCSRF;
}

{{-- Menyusun parameter untuk filter --}}
function buildParam() {
    let urlParamStr = "";

    {{-- Mendapatkan value dari "filter_province" --}}
    const filterProvince = document.getElementById("filter_province").value.trim();
    {{-- Jika filter province memiliki isi --}}
    if (filterProvince.length) {
        urlParamStr += "filter_province=" + filterProvince + "&";
    }

    const filterCity = document.getElementById("filter_city").value.trim();
    if (filterCity.length) {
        urlParamStr += "filter_city=" + filterCity + "&";
    }

    const filterDistrict = document.getElementById("filter_district").value.trim();
    if (filterDistrict.length) {
        urlParamStr += "filter_district=" + filterDistrict + "&";
    }

    const filterCSO = document.getElementById("filter_cso").value.trim();
    if (filterCSO !== "All CSO" && filterCSO.length) {
        const getCSOCode = filterCSO.split("-");
        urlParamStr += "filter_cso=" + getCSOCode[0] + "&";
    }

    const filterSearch = document.getElementById("search").value.trim();
    if (filterSearch.length) {
        urlParamStr += "filter_search=" + filterSearch + "&";
    }

    return urlParamStr;
}

{{-- Apply Filter --}}
function submitApplyFilter() {
    const urlParamStr = buildParam();

    window.location.href = "<?php echo route('list_technician_schedule'); ?>" + "?" + urlParamStr;
}

var currentDate = "<?php echo $currentYear . "-" . $currentMonth . "-01"?>";

{{-- Export XLS --}}
function submitExportXLS() {
    let urlParamStr = buildParam();
    window.location.href = "<?php echo route('technician_schedule_export-to-xls'); ?>" + "?date=" + currentDate + "&" + urlParamStr;
}

{{-- Ganti bulan --}}
function changeMonth(click) {
    const convertDate = new Date(Date.parse(click.dataset.value));
    const currentYear = convertDate.getFullYear();
    const currentMonth = convertDate.getMonth();
    console.log([click, convertDate, currentYear, currentMonth]);

    currentDate = currentYear + "-" + (currentMonth + 1) + "-01";

    const monthWithZero = ("0" + (currentMonth + 1)).slice(-2);
    const dateWithZero = ("0" + (convertDate.getDate())).slice(-2);
    const fullDate = currentYear + "-" + monthWithZero + "-01";

    document.getElementById("calendar-container-year").innerHTML = currentYear;
    document.getElementById("calendar-container-month").innerHTML = convertDate
        .toLocaleString(
            "en-US",
            {
                month: "long",
            }
        );

    let setPreviousYear = currentYear;
    let setPreviousMonth = currentMonth;
    if (setPreviousMonth === 0) {
        setPreviousMonth = 12;
        setPreviousYear--;
    }
    setPreviousMonth = ("0" + (setPreviousMonth)).slice(-2);

    let setNextYear = currentYear;
    let setNextMonth = currentMonth + 2;
    if (setNextMonth === 13) {
        setNextMonth = 1;
        setNextYear++;
    }
    setNextMonth = ("0" + (setNextMonth)).slice(-2);

    fetch(
        "<?php echo route('technicianschedule_print_data_count'); ?>",
        {
            method: "POST",
            headers: {
                "Accept": "text/plain",
                "Content-type": "application/json",
                "X-CSRF-TOKEN": getCSRF(),
            },
            body: JSON.stringify({
                date: click.dataset.value,
                filter_province: click.dataset.province,
                filter_city: click.dataset.city,
                filter_district: click.dataset.district,
                filter_search: click.dataset.search,
                filter_branch: click.dataset.branch,
                filter_cso: click.dataset.cso,
            }),
        }
    ).then(function (response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.text();
    }).then(function (response) {
        document.getElementById("appointment-per-day").innerHTML = response;

        document.getElementById("previous-month").setAttribute(
            "data-value",
            setPreviousYear + "-" + setPreviousMonth + "-01"
        );

        document.getElementById("next-month").setAttribute(
            "data-value",
            setNextYear + "-" + setNextMonth + "-01"
        );
    }).catch(function (error) {
        console.log(error);
    })
}

{{-- Ganti hari --}}
function changeDate(click) {
    fetch(
        "<?php echo route('technicianschedule_print_appointment'); ?>",
        {
            method: "POST",
            headers: {
                "Accept": "text/plain",
                "Content-type": "application/json",
                "X-CSRF-TOKEN": getCSRF(),
            },
            body: JSON.stringify({
                date: click.dataset.year
                    + "-"
                    + click.dataset.month
                    + "-"
                    + click.dataset.date,
                filter_province: click.dataset.province,
                filter_city: click.dataset.city,
                filter_district: click.dataset.district,
                filter_search: click.dataset.search,
                filter_branch: click.dataset.branch,
                filter_cso: click.dataset.cso,
            }),
        }
    ).then(function (response) {
        if (!response.ok) {
            console.log(response);
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.text();
    }).then(function (response) {
        document.getElementById("appointment-data").innerHTML = response;

        const getDate = new Date(
            Date.parse(
                click.dataset.year
                + "-"
                + click.dataset.month
                + "-"
                + click.dataset.date
            )
        );

        document.getElementById("organizer-container-date").innerHTML = getDate
            .toLocaleString(
                "en-US",
                {
                    day:"numeric",
                    month: "long",
                    year: "numeric",
                }
            );
    }).catch(function (error) {
        console.error(error);
    });
}

{{-- View detail Technician Schedule --}}
function clickView(btn) {
    var id_ts = btn;
    if(isNaN(id_ts)){
        id_ts = btn.value;
        console.log(id_ts);
    }else{
        console.log(id_ts);
    }

    const URL = "<?php echo route('detail_technician_schedule'); ?>";

    fetch(
        URL + "?id=" + id_ts,
        {
            method: "GET",
            headers: {
                "Accept": "application/json",
                "X-CSRF-TOKEN": getCSRF(),
            },
        }
    ).then(function (response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    }).then(function (response) {
        const result = response.result;

        const appointmentDate = new Date(result.appointment);
        const dateString = appointmentDate.getFullYear()
            + "-"
            + ("0" + (appointmentDate.getMonth() + 1)).slice(-2)
            + "-"
            + ("0" + (appointmentDate.getDate())).slice(-2);
        const timeString = ("0" + (appointmentDate.getHours())).slice(-2)
            + ":"
            + ("0" + (appointmentDate.getMinutes())).slice(-2);

        if (result.code_homeservice) {
            document.getElementById("view_code_homeservice").innerHTML = "<a href='"
            + "{{route('homeServices_success')}}" + "?code="
            + result.code_homeservice + "'"
            + "target='_blank'>" + result.code_homeservice
            + "</a>";
        } else {
            document.getElementById("view_code_homeservice").innerHTML = '-';
        }

        if (result.code_service) {
            document.getElementById("view_code_service").innerHTML = "<a href='"
            + "{{url('cms-admin/service/detail')}}" + "/"
            + result.id_service + "'"
            + "target='_blank'>" + result.code_service
            + "</a>";
            document.getElementById("create_service").style.display = 'none';
        } else {
            document.getElementById("view_code_service").innerHTML = "-";
            document.getElementById("create_service").style.display = 'inline-block';
            document.getElementById("create_service").setAttribute('href', "{{route('add_service')}}?ts_id=" + result.id)
        }
        document.getElementById("view-name").innerHTML = result.name;
        document.getElementById("view-phone").innerHTML = (result.phone).toString();
        document.getElementById("view-province").innerHTML = result.province_name;
        document.getElementById("view-city").innerHTML = result.city_name;
        document.getElementById("view-distric").innerHTML = result.district_name;
        document.getElementById("view-address").innerHTML = result.address;
        document.getElementById("view-d_o").innerHTML = result.d_o;
        document.getElementById("view-cso").innerHTML = result.cso_code_name;
        document.getElementById("view-date").innerHTML = dateString;
        document.getElementById("view-time").innerHTML = timeString;
        @if(isset($_GET['id_ts']))
            $("#input_id_ts_hidden").val(id_ts);
            document.getElementById("cancel_desc_view").innerHTML = result.cancel_desc;
            $("#viewTechnicianScheduleModal").modal("show");
        @elseif(isset($_GET['id_detail_hs']))
            $("#viewTechnicianScheduleModal").modal("show");
        @else
            document.getElementById("url_share").setAttribute(
                "href",
                "whatsapp://send?text=<?php echo route('homeServices_success'); ?>"
                + "?code="
                + result.code
            );
        @endif

        var temp_product_ts = '';
        $.each(result.products_technician_schedule, function(i, product_ts) {
            temp_product_ts += `
            <table style="width: 90%; margin: auto; margin-bottom: 15px;" >
                <thead>
                    <td colspan="2" style="font-weight: 600;">Data Product Service ` +  (i + 1) + `</td>
                </thead>
                <tr>
                    <td class="col-4">Product:</td>
                    <td class="col-8">`;
            if (product_ts.product_id != null) {
                temp_product_ts += product_ts.product.name;
            } else {
                temp_product_ts += product_ts.other_product;
            }
            var issues = JSON.parse(product_ts.issues);
            temp_product_ts += `
                    </td>
                </tr>
                <tr>
                    <td class="col-4">Issues:</td>
                    <td class="col-8">` +
                        issues[0].issues.join(',') + `
                    </td>
                </tr>
                <tr>
                    <td class="col-4">Description:</td>
                    <td class="col-8">` +
                        issues[1].desc +`
                    </td>
                </tr>
            </table>`;
        });
        $('#view-technician-schedule-product').html(temp_product_ts);
    }).catch(function (error) {
        console.error(error);
    });
}

{{-- Cancel appointment --}}
function clickCancel(e) {
    document.getElementById("btn-cancel").value = e.value;
}

{{-- Set city ketika nilai province diubah --}}
function setCity(e) {
    fetch(
        '<?php echo route("fetchCity", ["province" => ""]); ?>/' + e.value,
        {
            method: "GET",
            headers: {
                "Accept": "application/json",
                "X-CSRF-TOKEN": getCSRF(),
            },
        }
    ).then(function (response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    }).then(function (response) {
        const result = response["rajaongkir"]["results"];

        const arrCity = [];
        arrCity[0] = '<option disabled value="">Pilihan Kabupaten</option>';
        arrCity[1] = '<option disabled value="">Pilihan Kota</option>';

        if (result.length > 0) {
            result.forEach(function (currentValue) {
                if (currentValue["type"] === "Kabupaten") {
                    arrCity[0] += '<option value="'
                        + currentValue["city_id"]
                        + '">'
                        + currentValue['type']
                        + ' '
                        + currentValue['city_name']
                        + '</option>';
                } else {
                    arrCity[1] += '<option value="'
                        + currentValue['city_id']
                        + '">'
                        + currentValue['type']
                        + ' '
                        + currentValue['city_name']
                        + '</option>';
                }
            });

            const getTargetId = (e.id).split("_");

            document.getElementById(getTargetId[0] + "_city").innerHTML = '<option selected value="">All City</option>'
                + arrCity[0]
                + arrCity[1];
        }
    }).catch(function(error) {
        console.error(error);
    });
}

{{-- Set district ketika nilai city diubah --}}
function setDistrict(e) {
    fetch(
        '<?php echo route("fetchDistrict", ['city' => ""]); ?>/' + e.value,
        {
            method: "GET",
            headers: {
                "Accept": "application/json",
                "X-CSRF-TOKEN": getCSRF(),
            },
        }
    ).then(function (response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    }).then(function (response) {
        const result = response["rajaongkir"]["results"];

        let optionsDistrict = '<option selected value="">All District</option>';

        if (result.length > 0) {
            result.forEach(function (currentValue) {
                optionsDistrict += '<option value="'
                    + currentValue["subdistrict_id"]
                    + '">'
                    + currentValue['subdistrict_name']
                    + '</option>';
            });

            const getTargetId = (e.id).split("_");

            document.getElementById(getTargetId[0] + "_district").innerHTML = optionsDistrict;
        }
    }).catch(function (error) {
        consol.error(error);
    })
}
</script>
@endsection
