<?php
$menu_item_page = "homeservice";
$menu_item_second = "list_homeservice";
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

    .cjslib-day-indicator {
        olor: #ffa000; background-color: #ffa000;
    }

    .cjslib-indicator-type-numeric {
        color: #ffffff;
    }

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
  			<h3 class="page-title">Home Service</h3>
  			<nav aria-label="breadcrumb">
    			<ol class="breadcrumb">
      				<li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#cso-dd"
                            aria-expanded="false"
                            aria-controls="cso-dd">
                            Home Service
                        </a>
                    </li>
      				<li class="breadcrumb-item active" aria-current="page">
                        List Home Service
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
                            <?php
                            if (
                                Utils::$lang === 'id'
                                && Auth::user()->roles[0]["slug"] !== "admin-management"
                            ):
                            ?>
                                <div class="col-xs-6 col-sm-3"
                                    style="padding: 0; display: inline-block;">
                                    <div class="form-group">
                                        <label for="">Filter By City</label>
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
                                            placeholder="Search By Name, Phone, and Code Homeservice" />
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
                            <?php endif; ?>

                            <?php
                            if (
                                Auth::user()->roles[0]['slug'] !== 'branch'
                                && Auth::user()->roles[0]['slug'] !== 'cso'
                                && Auth::user()->roles[0]["slug"] !== "admin-management"
                            ):
                            ?>
                                <div class="col-xs-6 col-sm-3"
                                    style="padding: 0;display: inline-block;">
                                    <div class="form-group">
                                        <label for="">Filter By Team</label>
                                        <select class="form-control"
                                            id="filter_branch"
                                            name="filter_branch">
                                            <option value="" selected="">
                                                All Branch
                                            </option>
                                            @foreach($branches as $branch)
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

                                <div class="col-xs-6 col-sm-3"
                                    style="padding: 0;display: inline-block;">
                                    <div class="form-group">
                                        <label for="">Filter By CSO</label>
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
                                                @foreach($csos as $cso)
                                                    <option value="{{ $cso['code'] }}-{{ $cso['name'] }}"></option>
                                                @endforeach
                                            </select>
                                        </datalist>
                                        <div class="validation"></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php
                        if (
                            Auth::user()->roles[0]['slug'] !== 'branch'
                            && Auth::user()->roles[0]['slug'] !== 'cso'
                            && Auth::user()->roles[0]['slug'] !== 'area-manager'
                            && Auth::user()->roles[0]["slug"] !== "admin-management"
                        ):
                        ?>
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
                                        <button id="btn-exportDate"
                                            type="button"
                                            class="btn btn-gradient-info m-1"
                                            name="export"
                                            data-toggle="modal"
                                            data-target="#datePickerHomeServiceModal"
                                            value="-">
                                            <span class="mdi mdi-file-document"></span>
                                            Export XLS with Start-End Date
                                        </button>
                                        <button id="btn-exportByInput"
                                            type="button"
                                            class="btn btn-gradient-info m-1"
                                            name="export"
                                            data-toggle="modal"
                                            data-target="#datePickerByInput"
                                            value="-">
                                            <span class="mdi mdi-file-document"></span>
                                            Export XLS by Input Date
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
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

                        $arrBranches = [];
                        if (
                            Auth::user()->roles[0]['slug'] === 'branch'
                            || Auth::user()->roles[0]['slug'] === 'area-manager'
                        ) {
                            foreach (Auth::user()->listBranches() as $value) {
                                $arrBranches[] = $value["id"];
                            }
                        }

                        if (isset($_GET["filter_search"])) {
                            $search = $_GET["filter_search"];
                        }

                        if (
                            isset($_GET["filter_branch"])
                            && Auth::user()->roles[0]['slug'] !== 'branch'
                        ) {
                            $arrBranches = [];
                            $arrBranches[] = $_GET["filter_branch"];
                        }

                        $branchCode = implode(", ", $arrBranches);

                        if (isset($_GET["filter_cso"])) {
                            $CSOCode = $_GET["filter_cso"];
                        }

                        if (Auth::user()->roles[0]['slug'] === "cso") {
                            $getCSOCode = App\Cso::where("id", Auth::user()->cso["id"])->first();
                            $CSOCode = $getCSOCode->code;
                        }
                        ?>

                        <div class="col-xs-12 col-sm-12 col-md-12 table-responsive"
                            id="calendar-container">
                            <div class="cjslib-calendar cjslib-size-small">
                                <div class="cjslib-year"
                                    style="background-color: rgb(255, 160, 0); color: rgb(255, 236, 179);">
                                    <span id="calendar-container-year">
                                        <?php echo date("Y"); ?>
                                    </span>
                                </div>

                                <?php
                                    $currentMonth = date("n");
                                    $previousMonth = (int) $currentMonth - 1;
                                    $nextMonth = (int) $currentMonth + 1;
                                    $month = date("m");
                                    $year = date("Y");

                                    $currentMonth_text = date("F");

                                    if(isset($_GET['isSubmission'])){
                                        $get_appointment = strtotime($_GET['appointment']);

                                        $currentMonth = date("n", $get_appointment);
                                        $previousMonth = (int) $currentMonth - 1;
                                        $nextMonth = (int) $currentMonth + 1;
                                        $month = date("m", $get_appointment);
                                        $year = date("Y", $get_appointment);

                                        $currentMonth_text = date("F", $get_appointment);
                                    }

                                    if ($previousMonth < 10) {
                                        $previousMonth = "0" . $previousMonth;
                                    }
                                    if ($nextMonth < 10) {
                                        $nextMonth = "0". $nextMonth;
                                    }
                                ?>
                                <div class="cjslib-month"
                                    style="background-color: rgb(255, 193, 7); color: rgb(255, 255, 255);">
                                    <div id="calendarContainer-month-back">
                                        <svg style="width: 24px; height: 24px;"
                                            viewBox="0 0 24 24"
                                            id="previous-month"
                                            data-value="<?php echo $year . "-" . $previousMonth . "-01"; ?>"
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
                                            data-value="<?php echo $year . "-" . $nextMonth . "-01"; ?>"
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

                                    if(isset($_GET['isSubmission'])){
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
                                                    . 'data-branch="' . $branchCode . '" '
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
                                        <?php
                                        if (Auth::user()->roles[0]["slug"] === "admin-management") {
                                            $isAdminManagement = true;
                                        } else {
                                            $isAdminManagement = false;
                                        }
                                        ?>
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
                                                                <?php if (!$isAdminManagement): ?>
                                                                    <p class="titleAppoin">
                                                                        <?php
                                                                        echo '<a href="'
                                                                            . route('homeServices_success')
                                                                            . '?code='
                                                                            . $dayData->hs_code
                                                                            . '" target="_blank">';
                                                                        echo $dayData->hs_code;
                                                                        echo '</a>';
                                                                        ?>
                                                                    </p>
                                                                <?php endif; ?>
                                                                <p class="descAppoin">
                                                                    <?php
                                                                    if (!$isAdminManagement) {
                                                                        echo $dayData->customer_name
                                                                            . ' - '
                                                                            . $dayData->customer_phone;
                                                                    }
                                                                    ?>
                                                                    <br>
                                                                    <?php
                                                                    echo 'Branch: '
                                                                        . $dayData->branch_code
                                                                        . ' - '
                                                                        . $dayData->branch_name;
                                                                    ?>
                                                                    <br>
                                                                    <?php
                                                                    echo 'CSO: ' . $dayData->cso_name;
                                                                    ?>
                                                                    <br>
                                                                    <?php
                                                                    if (!$isAdminManagement) {
                                                                        echo 'Created at: ' . $dayData->created_at;
                                                                    }
                                                                    ?>
                                                                    <br>
                                                                    <?php
                                                                    if (!$isAdminManagement) {
                                                                        echo 'Last update: ' . $dayData->updated_at;
                                                                    }
                                                                    ?>
                                                                </p>
                                                            </td>
                                                            <?php if (!$isAdminManagement): ?>
                                                                <td style="text-align: center">
                                                                    <?php
                                                                        if(Gate::check('detail-home_service')){
                                                                            echo '<button '
                                                                            . 'class="btnappoint btn-gradient-primary mdi mdi-eye btn-homeservice-view" '
                                                                            . 'type="button" '
                                                                            . 'data-toggle="modal" '
                                                                            . 'data-target="#viewHomeServiceModal" '
                                                                            . 'onclick="clickView(this)" '
                                                                            . 'value="' . $dayData->hs_id . '">'
                                                                            . '</button>';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td style="text-align: center">
                                                                    <?php
                                                                        if(Gate::check('edit-home_service')){
                                                                            echo '<button '
                                                                                . 'class="btnappoint btn-gradient-success mdi mdi-cash-multiple btn-homeservice-cash" '
                                                                                . 'type="button" '
                                                                                . 'data-toggle="modal" '
                                                                                . 'data-target="#cashHomeServiceModal" '
                                                                                . 'onclick="clickCash(this)" '
                                                                                . 'value="' . $dayData->hs_id . '">'
                                                                                . '</button>';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td style="text-align: center">
                                                                    <?php
                                                                        if(Gate::check('edit-home_service')){
                                                                            echo '<button '
                                                                                . 'class="btnappoint btn-gradient-info mdi mdi-border-color btn-homeservice-edit" '
                                                                                . 'type="button" '
                                                                                . 'data-toggle="modal" '
                                                                                . 'data-target="#editHomeServiceModal" ';

                                                                            if (Auth::user()->roles[0]["slug"] === "cso") {
                                                                                echo 'data-cso="true" ';
                                                                            } else {
                                                                                echo 'data-cso="false" ';
                                                                            }

                                                                            echo 'onclick="clickEdit(this)" '
                                                                                . 'value="' . $dayData->hs_id . '">'
                                                                                . '</button>';
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td style="text-align: center">
                                                                    <?php
                                                                        if(Gate::check('delete-home_service')){
                                                                            echo '<button '
                                                                                . 'class="btnappoint btn-gradient-danger mdi mdi-calendar-remove btn-homeservice-cancel" '
                                                                                . 'type="button" '
                                                                                . 'data-toggle="modal" '
                                                                                . 'data-target="#deleteHomeServiceModal" '
                                                                                . 'onclick="clickCancel(this)" '
                                                                                . 'value="' . $dayData->hs_id . '">'
                                                                                . '</button>';
                                                                        }
                                                                    ?>
                                                                </td>
                                                            <?php else: ?>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            <?php endif; ?>
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
        id="viewHomeServiceModal"
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
                <div class="modal-body">
                    <table style="width: 90%; margin: auto;">
                        <tr>
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Type Customer: </td>
                            <td id="view_type_customer" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>
                        <tr>
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Type Home Service: </td>
                            <td id="view_type_homeservices" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>

                        <tr><td style="padding-top: 1em;"></td></tr>

                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">No. Member: </td>
                            <td id="view-no_member" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>
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

                        <tr><td style="padding-top: 1em;"></td></tr>

                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Cabang: </td>
                            <td id="view-branch" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>
                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Kode CSO: </td>
                            <td id="view-cso" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>
                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Kode Partner CSO: </td>
                            <td id="view-cso2" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
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
                </div>
                <div class="modal-footer">
                    <a id="url_share"
                        href=""
                        data-action="share/whatsapp/share"
                        target="_blank">
                        <button id="btn-share"
                            type="button"
                            class="btn btn-gradient-primary mr-2">
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

    <!-- Modal Edit -->
    <div class="modal fade"
        id="editHomeServiceModal"
        tabindex="-1"
        role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="actionEdit"
                    class="forms-sample"
                    method="POST"
                    action="{{ route('update_homeService') }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Appointment</h5>
                        <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 style="text-align:center;"></h5>
                        {{ csrf_field() }}
                        <input type="hidden" id="edit-id" name="id" value="" />
                        <div class="form-group">
                            <span>Type Customer</span>
                            <select id="type_customer"
                                style="margin-top: 0.5em;"
                                class="form-control"
                                style="height: auto;"
                                name="type_customer"
                                value=""
                                readonly
                                disabled>
                                <option value="VVIP (Type A)">
                                    VVIP (Type A)
                                </option>
                                <option value="WAKi Customer (Type B)">
                                    WAKi Customer (Type B)
                                </option>
                                <option value="New Customer (Type C)">
                                    New Customer (Type C)
                                </option>
                            </select>
                            <span class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <span>Type Home Service</span>
                            <select id="type_homeservices"
                                style="margin-top: 0.5em;"
                                class="form-control"
                                style="height: auto;"
                                name="type_homeservices"
                                value=""
                                readonly
                                disabled>
                                <option value="Home service">
                                    Home Service
                                </option>
                                <option value="Home Tele Voucher">
                                    Home Tele Voucher
                                </option>
                                <option value="Home Eksklusif Therapy">
                                    Home Eksklusif Therapy
                                </option>
                                <option value="Home Free Family Therapy">
                                    Home Free Family Therapy
                                </option>
                                <option value="Home Demo Health & Safety with WAKi">
                                    Home Demo Health & Safety with WAKi
                                </option>
                                <option value="Home Voucher">
                                    Home Voucher
                                </option>
                                <option value="Home Tele Free Gift">
                                    Home Tele Free Gift
                                </option>
                                <option value="Home Refrensi Product">
                                    Home Refrensi Product
                                </option>
                                <option value="Home Delivery">
                                    Home Delivery
                                </option>
                                <option value="Home Free Refrensi Therapy VIP">
                                    Home Free Refrensi Therapy VIP
                                </option>
                                <option value="Home WAKi di Rumah Aja">
                                    Home WAKi di Rumah Aja
                                </option>
                            </select>
                            <span class="invalid-feedback">
                                <strong></strong>
                            </span>
                        </div>
                        <h5>Data Pelanggan</h5>
                        <div class="form-group">
                            <input type="text"
                                name="no_member"
                                class="form-control"
                                id="edit-no_member"
                                placeholder="No. Member (optional)" />
                            <div class="validation"></div>
                        </div>
                        <div class="form-group">
                            <input type="text"
                                class="form-control"
                                name="name"
                                id="edit-name"
                                placeholder="Nama"
                                required
                                data-msg="Mohon Isi Nama" />
                            <div class="validation"></div>
                        </div>
                        <div class="form-group">
                            <input type="number"
                                class="form-control"
                                name="phone"
                                id="edit-phone"
                                placeholder="Nomor Telepon"
                                required
                                data-msg="Mohon Isi Nomor Telepon" />
                            <div class="validation"></div>
                        </div>
                        <div class="form-group">
                            <select class="form-control"
                                id="edit_province"
                                name="province"
                                required
                                onchange="setCity(this)"
                                data-msg="Mohon Isi Provinsi">
                                <option selected disabled value="">
                                    Pilihan Provinsi
                                </option>

                                @php
                                $result = RajaOngkir::FetchProvince();
                                $result = $result['rajaongkir']['results'];

                                if(sizeof($result) > 0){
                                    foreach ($result as $value) {
                                        echo "<option value=\""
                                            . $value['province_id']
                                            . "\">"
                                            . $value['province']
                                            . "</option>";
                                    }
                                }
                                @endphp
                            </select>
                            <div class="validation"></div>
                        </div>
                        <div class="form-group">
                            <select class="form-control"
                                name="city"
                                id="edit_city"
                                value=""
                                required
                                onchange="setDistrict(this)"
                                data-msg="Mohon Isi Kota">
                            </select>
                            <div class="validation"></div>
                        </div>
                        <div class="form-group">
                            <select class="form-control"
                                name="distric"
                                id="edit_district"
                                value=""
                                required
                                data-msg="Mohon Isi Kecamatan">
                            </select>
                            <div class="validation"></div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control"
                                name="address"
                                id="edit-address"
                                rows="5"
                                required
                                data-msg="Mohon Isi Alamat"
                                placeholder="Alamat"></textarea>
                            <div class="validation"></div>
                        </div>

                        <br>

                        <h5>Data CSO</h5>
                        <div class="form-group">
                            <select class="form-control"
                                id="edit-branch"
                                name="branch_id"
                                data-msg="Mohon Pilih Cabang"
                                required>
                                <option selected disabled value="">
                                    Pilihan Cabang
                                </option>

                                @foreach ($branches as $branch)
                                    <option value="{{ $branch['id'] }}">
                                        {{ $branch['code'] }} - {{ $branch['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="validation"></div>
                        </div>
                        <div class="form-group">
                            <input type="text"
                                class="form-control cso"
                                name="cso_id"
                                id="edit-cso"
                                placeholder="Kode CSO"
                                required
                                data-msg="Mohon Isi Kode CSO"
                                style="text-transform:uppercase" />
                            <div class="validation" id="validation_cso"></div>
                        </div>
                        <div class="form-group">
                            <input type="hidden"
                                class="form-control"
                                name="cso_phone"
                                id="edit-cso_phone"
                                placeholder="No. Telepon CSO"
                                required
                                data-msg="Mohon Isi Nomor Telepon" />
                            <div class="validation"></div>
                        </div>
                        <div class="form-group">
                            <input type="text"
                                class="form-control cso"
                                name="cso2_id"
                                id="edit-cso2"
                                placeholder="Kode Partner CSO (opsional)"
                                style="text-transform: uppercase;" />
                            <div class="validation" id="validation_cso2"></div>
                        </div>

                        <br>

                        <h5>Waktu Home Service</h5>
                        <div class="form-group">
                            <input type="date"
                                class="form-control"
                                name="date"
                                id="edit-date"
                                placeholder="Tanggal Janjian"
                                required
                                data-msg="Mohon Isi Tanggal" />
                            <div class="validation"></div>
                        </div>
                        <div class="form-group">
                            <input type="time"
                                class="form-control"
                                name="time"
                                id="edit-time"
                                placeholder="Jam Janjian"
                                required
                                data-msg="Mohon Isi Jam" />
                            <div class="validation"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btn-edit"
                            type="submit"
                            class="btn btn-gradient-primary mr-2">
                            Save
                        </button>
                        <button class="btn btn-light"
                            data-dismiss="modal"
                            aria-label="Close">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Edit -->

    <!-- Modal Delete -->
    <div class="modal fade"
        id="deleteHomeServiceModal"
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
                    <h5 style="text-align: center;">
                        Are you sure to cancel this appointment?
                    </h5>
                </div>
                <div class="modal-footer">
                    <form id="frmCancel"
                        method="post"
                        action="{{ route('update_homeService') }}">
                        {{ csrf_field() }}
                        <input type="hidden"
                            id="hiddenInput"
                            name="cancel"
                            value="1" />
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

    <!-- Modal Cash -->
    <div class="modal fade"
        id="cashHomeServiceModal"
        tabindex="-1"
        role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="frmCash"
                    method="post"
                    action="{{ route('update_homeService') }}"
                    enctype="multipart/form-data">
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
                            Did you manage to get cash? <span id="cash_or_not_txt" style="color: black;"></span>
                        </h5>
                        <br>
                        <div class="form-group mb-1">
                            <label for="">Description:</label>
                            <textarea class="form-control"
                                name="cash_description"
                                id="edit-cash_description"
                                rows="5"
                                required
                                placeholder="Cash Description"></textarea>
                            <label for="">Bukti Foto (WAJIB): </label>
                            <div id="divImageCash" style="padding: 0.5em;">
                                <img id="showImageCash" src="" height="300px" width="450px">
                            </div>
                            <input type="file" class="form-control" name="cash_image" id="cash_image" accept="image/*" placeholder="Bukti Foto" required data-msg="Mohon Sertakan Foto" style="text-transform:uppercase"/>
                            <div class="validation"></div>
                        </div>
                    </div>
                    <div class="modal-footer footer-cash" id="footer-cash">
                        {{ csrf_field() }}
                        <input type="hidden"
                            id="idEditCash"
                            name="id"
                            value="-" />
                        <button type="submit"
                            id="btn-CashNoCash"
                            class="btn btn-gradient-success mr-2"
                            name="cash"
                            value="1">
                            Yes
                        </button>
                        <button type="submit"
                            id="btn-CashNoCash"
                            class="btn btn-gradient-danger mr-2"
                            name="cash"
                            value="0">
                            No
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Cash -->

    <!-- Modal Date Picker export Xls -->
    <div class="modal fade"
        id="datePickerHomeServiceModal"
        tabindex="-1"
        role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <label for="">Pick a Date</label>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal Awal</label>
                        <input type="date"
                            class="form-control"
                            name="date"
                            id="filter_startDate"
                            placeholder="Awal Tanggal"
                            required
                            data-msg="Mohon Isi Tanggal"
                            onload="getDate()" />
                        <div class="validation"></div>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Akhir</label>
                        <input type="date"
                            class="form-control"
                            name="date"
                            id="filter_endDate"
                            placeholder="Akhir Tanggal"
                            required
                            data-msg="Mohon Isi Tanggal"
                            onload="getDate()" />
                        <div class="validation"></div>
                    </div>
                    <div class="form-group">
                        <label>Export Type</label>
                        <select class="form-control"
                            id="export_type"
                            name="export_type"
                            required>
                            <option value="detail">Detail Home Service</option>
                            <option value="compare">Comparison Home Service</option>
                        </select>
                        <div class="validation"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{ csrf_field() }}
                    <input type="hidden"
                        id="hiddenInput"
                        name="cancel"
                        value="1" />
                    <button type="submit"
                        data-dismiss="modal"
                        id="btn-exportByDate"
                        class="btn btn-gradient-danger mr-2"
                        name="id"
                        onclick="submitExportXLSWithDate()"
                        value="-">
                        Export
                    </button>
                    <button type="button"
                        data-dismiss="modal"
                        class="btn btn-light">
                        No
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Date Picker export Xls -->

    <!-- Modal Date Picker export By Input Xls -->
    <div class="modal fade"
        id="datePickerByInput"
        tabindex="-1"
        role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <label for="">Pick a Date</label>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal Input</label>
                        <input type="date"
                            class="form-control"
                            name="date"
                            id="filter_inputByDate"
                            placeholder="Awal Tanggal"
                            required
                            data-msg="Mohon Isi Tanggal"
                            onload="getDate()" />
                        <div class="validation"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{csrf_field()}}
                    <input type="hidden"
                        id="hiddenInput"
                        name="cancel"
                        value="1" />
                    <button type="submit"
                        data-dismiss="modal"
                        id="btn-exportByInp"
                        class="btn btn-gradient-danger mr-2"
                        name="id"
                        onclick="submitExportXLSByInputDate()"
                        value="-">
                        Export
                    </button>
                    <button type="button"
                        data-dismiss="modal"
                        class="btn btn-light">
                        No
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Date Picker export By Input Xls -->
</div>
@endsection

@section('script')
<script type="application/javascript">
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

    const filterBranch = document.getElementById("filter_branch").value.trim();
    if (filterBranch.length) {
        urlParamStr += "filter_branch=" + filterBranch + "&";
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

    window.location.href = "<?php echo route('admin_list_homeService'); ?>" + "?" + urlParamStr;
}

{{-- Export XLS --}}
function submitExportXLS() {
    let urlParamStr = buildParam();

    const selectedDate = new Date(Date.parse(document.getElementById("organizer-container-date").innerHTML));

    const year = selectedDate.getFullYear();
    const month = ("0" + (selectedDate.getMonth() + 1)).slice(-2);
    const date = ("0" + (selectedDate.getDate())).slice(-2);

    const fullDate = year + "-" + month + "-" + date;

    window.location.href = "<?php echo route('homeservice_export-to-xls'); ?>" + "?date=" + fullDate + "&" + urlParamStr;
}

{{-- Export XLS With Date --}}
function submitExportXLSWithDate() {
    let urlParamStr = buildParam();

    let startDate = document.getElementById("filter_startDate").value;
    let endDate = document.getElementById("filter_endDate").value;
    let typeExport = document.getElementById("export_type").value;

    urlParamStr += "filter_startDate=" + startDate + "&";
    urlParamStr += "filter_endDate=" + endDate;

    if(typeExport == "detail"){
        window.location.href = "<?php echo route('homeservice_export-to-xls-by-date'); ?>" + "?" + urlParamStr;
    }
    else{
        window.location.href = "<?php echo route('homeservice_export-to-xls-compare'); ?>" + "?" + urlParamStr;
    }

}

{{-- Export XLS By Input Date --}}
function submitExportXLSByInputDate() {
    const inputDate = document.getElementById("filter_inputByDate").value;

    window.location.href = "<?php echo route('homeservice_export-to-xls-by-date'); ?>" + "?inputDate=" + inputDate;
}

{{-- Ganti bulan --}}
function changeMonth(click) {
    const convertDate = new Date(Date.parse(click.dataset.value));
    const currentYear = convertDate.getFullYear();
    const currentMonth = convertDate.getMonth();

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
        "<?php echo route('homeservice_print_data_count'); ?>",
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
        "<?php echo route('homeservice_print_appointment'); ?>",
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

{{-- View detail home service --}}
function clickView(btn) {
    const URL = "<?php echo route('detail_homeService'); ?>";

    fetch(
        URL + "?id=" + btn.value,
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

        document.getElementById("view_type_customer").innerHTML = result.type_customer;
        document.getElementById("view_type_homeservices").innerHTML = result.type_homeservices;
        document.getElementById("view-no_member").innerHTML = result.no_member;
        document.getElementById("view-name").innerHTML = result.name;
        document.getElementById("view-phone").innerHTML = (result.phone).toString();
        document.getElementById("view-province").innerHTML = result.province_name;
        document.getElementById("view-city").innerHTML = result.city_name;
        document.getElementById("view-distric").innerHTML = result.district_name;
        document.getElementById("view-address").innerHTML = result.address;
        document.getElementById("view-branch").innerHTML = result.branch_code_name;
        document.getElementById("view-cso").innerHTML = result.cso_code_name;
        document.getElementById("view-cso2").innerHTML = result.cso2_code_name;
        document.getElementById("view-date").innerHTML = dateString;
        document.getElementById("view-time").innerHTML = timeString;
        document.getElementById("url_share").setAttribute(
            "href",
            "whatsapp://send?text=<?php echo route('homeServices_success'); ?>"
            + "?code="
            + result.code
        );
    }).catch(function (error) {
        console.error(error);
    });
}

{{-- Edit detail home service --}}
function clickEdit(btn) {
    const URL = "<?php echo route('edit_homeService'); ?>";

    fetch(
        URL + "?id=" + btn.value,
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

        document.getElementById("edit-id").value = btn.value;

        const editTypeCustomer = document.getElementById("type_customer");
        for (let i = 0; i < editTypeCustomer.options.length; i++) {
            if (editTypeCustomer.options[i].text === result.type_customer) {
                editTypeCustomer.selectedIndex = i;

                break;
            }
        }

        const editTypeHomeService = document.getElementById("type_homeservices");
        for (let i = 0; i < editTypeHomeService.options.length; i++) {
            if (editTypeHomeService.options[i].text === result.type_homeservices) {
                editTypeHomeService.selectedIndex = i;

                break;
            }
        }

        document.getElementById("edit-no_member").value = result.no_member;
        document.getElementById("edit-name").value = result.name;
        document.getElementById("edit-phone").value = (result.phone).toString();

        document.getElementById("edit_province").value = result.province;

        fetch(
            '<?php echo route("fetchCity", ["province" => ""]); ?>/' + result.province,
            {
                method: "GET",
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": getCSRF(),
                },
            }
        ).then(function (responseCity) {
            if (!responseCity.ok) {
                throw new Error(`HTTP error! status: ${responseCity.status}`);
            }

            return responseCity.json();
        }).then(function (responseCity) {
            const resultCity = responseCity["rajaongkir"]["results"];
            const arrCity = [];

            arrCity[0] = '<option disabled value="">Pilihan Kabupaten</option>';
            arrCity[1] = '<option disabled value="">Pilihan Kota</option>';

            if (resultCity.length > 0) {
                resultCity.forEach(function (currentValue) {
                    let isSelected = "";

                    if (currentValue["city_id"] === result.city) {
                        isSelected = "selected";
                    }

                    if (currentValue["type"] === "Kabupaten") {
                        arrCity[0] += '<option value="' + currentValue["city_id"] + '"'
                            + isSelected
                            + '>'
                            + currentValue["type"]
                            + ' '
                            + currentValue["city_name"]
                            + '</option>';
                    } else {
                        arrCity[1] += '<option value="' + currentValue["city_id"] + '"'
                            + isSelected
                            + '>'
                            + currentValue["type"]
                            + ' '
                            + currentValue["city_name"]
                            + '</option>';
                    }
                });

                document.getElementById("edit_city").innerHTML = arrCity[0] + arrCity[1];
            }
        }).catch(function(errorCity) {
            console.error(errorCity);
        });

        fetch(
            '<?php echo route("fetchDistrict", ["city" => ""]); ?>/' + result.city,
            {
                method: "GET",
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": getCSRF(),
                },
            }
        ).then(function (responseDistrict) {
            if (!responseDistrict.ok) {
                throw new Error(`HTTP error! status: ${responseDistrict.status}`);
            }

            return responseDistrict.json();
        }).then(function (responseDistrict) {
            resultDistrict = responseDistrict["rajaongkir"]["results"];

            let optionsDistrict = '<option disabled value="">Pilihan Kecamatan</option>';

            if (resultDistrict.length > 0) {
                resultDistrict.forEach(function (currentValue) {
                    let isSelected = "";

                    if (currentValue["subdistrict_id"] === result.distric) {
                        isSelected = "selected";
                    }

                    optionsDistrict += '<option value="'
                        + currentValue["subdistrict_id"]
                        + '"'
                        + isSelected
                        + '>'
                        + currentValue["subdistrict_name"]
                        + '</option>';
                });

                document.getElementById("edit_district").innerHTML = optionsDistrict;
            }
        }).catch(function (errorDistrict){
            console.error(errorDistrict);
        });

        document.getElementById("edit-address").innerHTML = result.address;

        const editBranch = document.getElementById("edit-branch");
        const modifyBranchCodeName = (result.branch_code_name).split(" ")
        modifyBranchCodeName.splice(1, 0, "-");
        joinBranchCodeName = modifyBranchCodeName.join(" ");

        for (let i = 0; i < editBranch.options.length; i++) {
            if (editBranch.options[i].text === joinBranchCodeName) {
                editBranch.selectedIndex = i;

                break;
            }
        }

        const getCSOCode = (result.cso_code_name).split(" - ");
        document.getElementById("edit-cso").value = getCSOCode[0];

        if (btn.dataset.cso === "true") {
            document.getElementById("edit-cso").setAttribute("readonly", "");
        } else if (btn.dataset.cso === "false" || btn.dataset.cso === undefined) {
            document.getElementById("edit-cso").removeAttribute("readonly");
        }

        document.getElementById("edit-cso_phone").value = result.cso_phone;

        const getCSO2Code = (result.cso2_code_name).split(" - ");
        document.getElementById("edit-cso2").value = getCSO2Code[0];

        const appointmentDate = new Date(result.appointment);

        const dateString = appointmentDate.getFullYear()
            + "-"
            + ("0" + (appointmentDate.getMonth() + 1)).slice(-2)
            + "-"
            + ("0" + (appointmentDate.getDate())).slice(-2);
        document.getElementById("edit-date").value = dateString;

        const timeString = ("0" + (appointmentDate.getHours())).slice(-2)
            + ":"
            + ("0" + (appointmentDate.getMinutes())).slice(-2);
        document.getElementById("edit-time").value = timeString;
    }).catch(function (error) {
        console.error(error);
    });
}

{{-- Cash --}}
function clickCash(e) {
    document.getElementById("idEditCash").value = e.value;
    document.getElementById("footer-cash").classList.remove("d-none");
    document.getElementById("edit-cash_description").innerHTML = "";
    document.getElementById("edit-cash_description").removeAttribute("readonly");
    document.getElementById("divImageCash").classList.add("d-none");

    const URL = "<?php echo route('edit_homeService'); ?>";

    fetch(
        URL + "?id=" + e.value,
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

        if (result.cash != null) {
            let desc_cash_or_not = "No";
            let color_cash_or_not = "red";
            if(result.cash == 1){
                desc_cash_or_not = "Yes";
                color_cash_or_not = "green";
            }

            document.getElementById("footer-cash").classList.add("d-none");
            document.getElementById("cash_or_not_txt").innerHTML = desc_cash_or_not;
            document.getElementById("cash_or_not_txt").style.color = color_cash_or_not;
            document.getElementById("edit-cash_description").innerHTML = result.cash_description;
            document.getElementById("edit-cash_description").setAttribute("readonly", "");

            document.getElementById("cash_image").classList.add("d-none");
            document.getElementById("divImageCash").classList.remove("d-none");
            document.getElementById("showImageCash").src = "{{asset('sources/homeservice/')}}" + '/' + result.image[0];

        }
    }).catch(function (error) {
        console.log(error);
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
