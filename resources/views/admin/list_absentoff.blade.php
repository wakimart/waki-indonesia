<?php
$menu_item_page = "absent_off";
$menu_item_second = "list_absent_off";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="{{ asset('css/admin/calendarorganizer.css?v=' . filemtime('css/admin/calendarorganizer.css')) }}">
<style>
    .hs-filter a {
      font-weight: 600;
      font-size: 1.1em;
    }
    .hs-filter a.active {
      background-color: #ffc107 !important;
      color: white !important;
    }
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
  			<h3 class="page-title">Cuti</h3>
  			<nav aria-label="breadcrumb">
    			<ol class="breadcrumb">
      				<li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#absent_off-dd"
                            aria-expanded="false"
                            aria-controls="absent_off-dd">
                            Cuti
                        </a>
                    </li>
      				<li class="breadcrumb-item active" aria-current="page">
                        List Cuti
                    </li>
    			</ol>
  			</nav>
		</div>

        <div class="row">
  			<div class="col-12 grid-margin stretch-card">
    			<div class="card">
      				<div class="card-body">
      					<h5 style="margin-bottom: 0.5em;">Approved Cuti</h5>
                        <div class="col-xs-12 col-sm-12 row"
                            style="margin: 0; padding: 0;">
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
                                        <label for="filter_branch">
                                            Filter By Team
                                        </label>
                                        <select class="form-control"
                                            id="filter_branch"
                                            name="filter_branch">
                                            <option value="" selected="">
                                                All Branch
                                            </option>
                                            @foreach ($branches as $branch)
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
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php
                        $branchCode = null;
                        $CSOCode = null;

                        $arrBranches = [];
                        if (
                            Auth::user()->roles[0]['slug'] === 'branch'
                            || Auth::user()->roles[0]['slug'] === 'area-manager'
                        ) {
                            foreach (Auth::user()->listBranches() as $value) {
                                $arrBranches[] = $value["id"];
                            }
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
                                                    $existDataCount = 0;
                                                    $fullDayWithZero = $temp_search . $dayWithZero;
                                                    foreach ($currentMonthDataCount as $key => $value) {
                                                        if ($fullDayWithZero >= $value->start_date  &&  $fullDayWithZero <= $value->end_date) {
                                                            $existDataCount += $value->data_count;
                                                            
                                                        }
                                                        if ($value->end_date == $fullDayWithZero) {
                                                            unset($currentMonthDataCount[$key]);
                                                        }
                                                    }
                                                    if ($existDataCount > 0) {
                                                        echo '<span class="cjslib-day-indicator cjslib-indicator-pos-bottom cjslib-indicator-type-numeric" '
                                                            . 'id="calendarContainer-day-indicator-' . $dayCounter . '">';
                                                        echo $existDataCount;
                                                        echo '</span>';
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
                                                    <th
                                                        style="text-align: center;">
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="appointment-data"><?php echo $currentDayData ?></tbody>
                                        </table>
                                    </div>
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
        id="viewCutiModal"
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
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Name: </td>
                            <td id="view-name" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>
                        <tr>
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Cso: </td>
                            <td id="view-cso" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>

                        <tr><td style="padding-top: 1em;"></td></tr>

                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Branch: </td>
                            <td id="view-branch" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>
                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Division: </td>
                            <td id="view-division" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>
                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Work Since: </td>
                            <td id="view-duration_work" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>
                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Duration: </td>
                            <td id="view-duration_off" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>
                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Date: </td>
                            <td id="view-date" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>
                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Work On: </td>
                            <td id="view-work_on" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>

                        <tr><td style="padding-top: 1em;"></td></tr>

                        <tr style="margin-top: 0.5em">
                            <td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Reason: </td>
                            <td id="view-desc" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
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

    const filterBranch = document.getElementById("filter_branch").value.trim();
    if (filterBranch.length) {
        urlParamStr += "filter_branch=" + filterBranch + "&";
    }

    const filterCSO = document.getElementById("filter_cso").value.trim();
    if (filterCSO !== "All CSO" && filterCSO.length) {
        const getCSOCode = filterCSO.split("-");
        urlParamStr += "filter_cso=" + getCSOCode[0] + "&";
    }

    return urlParamStr;
}

{{-- Apply Filter --}}
function submitApplyFilter() {
    const urlParamStr = buildParam();

    window.location.href = "<?php echo route('list_absent_off'); ?>" + "?" + urlParamStr;
}

{{-- Ganti bulan --}}
function changeMonth(click) {
    const convertDate = new Date(Date.parse(click.dataset.value));
    const currentYear = convertDate.getFullYear();
    const currentMonth = convertDate.getMonth();
    console.log([click, convertDate, currentYear, currentMonth]);

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
        "<?php echo route('absentoff_print_data_count'); ?>",
        {
            method: "POST",
            headers: {
                "Accept": "text/plain",
                "Content-type": "application/json",
                "X-CSRF-TOKEN": getCSRF(),
            },
            body: JSON.stringify({
                date: click.dataset.value,
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
        "<?php echo route('absentoff_print_appointment'); ?>",
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
                filter_branch: click.dataset.branch,
                filter_cso: click.dataset.cso,
            }),
        }
    ).then(function (response) {
        if (!response.ok) {
            console.log(response);
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    }).then(function (response) {
        document.getElementById("appointment-data").innerHTML = response['msg'];

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
    var id_ao = btn;
    if(isNaN(id_ao)){
        id_ao = btn.value;
        console.log(id_ao);
    }else{
        console.log(id_ao);
    }

    const URL = "<?php echo route('detail_response_absent_off'); ?>";

    fetch(
        URL + "?id=" + id_ao,
        {
            method: "POST",
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

        document.getElementById("view-name").innerHTML = result.cso_name;
        document.getElementById("view-cso").innerHTML = result.cso_code;
        document.getElementById("view-branch").innerHTML = result.branch_code + " - " + result.branch_name;
        document.getElementById("view-division").innerHTML = result.division;
        document.getElementById("view-duration_work").innerHTML = result.duration_work;
        document.getElementById("view-duration_off").innerHTML = result.duration_off + " days";
        document.getElementById("view-date").innerHTML = result.date;
        document.getElementById("view-work_on").innerHTML = result.work_on;
        document.getElementById("view-desc").innerHTML = result.desc;

    }).catch(function (error) {
        console.error(error);
    });
}
</script>
@endsection
