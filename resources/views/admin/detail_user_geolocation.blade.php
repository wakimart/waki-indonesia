<?php
$menu_item_page = "homeservice";
$menu_item_second = "track_homeservice";
?>
@extends("admin.layouts.template")

@section("style")
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
    integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
<style type="text/css">
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

    #map {
        height: 800px;
    }
</style>
@endsection

@section("content")
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Track Home Service</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#admin-dd"
                            aria-expanded="false"
                            aria-controls="admin-dd">
                            Home Service
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Track Home Service
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="col-md-12 col-sm-12 col-xs-12 row">
                    <div class="col-md-6 col-sm-12 col-xs-12"
                        style="padding-left: 0;">
                        <form id="form-search">
                            <div class="col-xs-6 col-sm-6 col-md-6"
                                style="padding: 0; display: inline-block;">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input class="form-control"
                                        type="date"
                                        id="date"
                                        name="date"
                                        placeholder="Date"
                                        value="{{ $_GET["date"] ?? "" }}"
                                        required />
                                </div>
                            </div>
                            <div style="padding: 0; display: inline-block;">
                                <div class="form-group">
                                    <button type="submit"
                                        class="btn btn-gradient-primary">
                                        <span class="mdi mdi-filter"></span> Apply
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12"
                        style="padding-left: 0;">
                        @if (!empty($branches) && $branches->isNotEmpty())
                            <div class="col-xs-6 col-sm-6 col-md-6"
                                style="padding: 0; display: inline-block;">
                                <div class="form-group">
                                    <label class="col-sm-12 col-xs-12"
                                        for="branch"
                                        style="padding-left: 0;">
                                        Branch
                                    </label>
                                    <select class="form-control"
                                        style="width: 100%"
                                        form="form-search"
                                        id="branch"
                                        name="branch_id">
                                        <option value="">Choose Branch</option>
                                        @foreach ($branches as $branch)
                                            <?php
                                            $selected = false;
                                            if(isset($_GET["branch_id"])){
                                                if ($branch->id == $_GET["branch_id"]) {
                                                    $selected = true;
                                                }
                                            }
                                            
                                            ?>
                                            <option value="{{ $branch->id }}"
                                                {!! $selected ? "selected" : "" !!}>
                                                {{ $branch->code }} - {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div style="padding: 0; display: inline-block;">
                                <div class="form-group">
                                    <button id="btn-filter"
                                        type="submit"
                                        class="btn btn-gradient-primary"
                                        form="form-search">
                                        <span class="mdi mdi-magnify"></span> Search
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if (!empty($userGeolocations) && $userGeolocations->isNotEmpty())
                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive"
                                style="border: 1px solid #ebedf2;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>CSO Code</th>
                                            <th>Name</th>
                                            <th class="text-center">
                                                Check In
                                            </th>
                                            <th class="text-center">
                                                Check Out
                                            </th>
                                            <th class="text-center">View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($userGeolocations as $userGeolocations)
                                            <tr>
                                                <td>
                                                    {{ $userGeolocations->cso_code }}
                                                </td>
                                                <td>
                                                    {{ $userGeolocations->name }}
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ asset("sources/geolocation/" . $_GET["date"] . "/img/" . $userGeolocations->presence_image[0]) }}"
                                                        target="_blank">
                                                        <i class="mdi mdi-image" style="font-size: 24px; color: #0d6efd;"></i>
                                                    </a>
                                                    @php
                                                        if(isset($userGeolocations->presence_image[0])){
                                                            $imgName = explode("_", $userGeolocations->presence_image[0]);
                                                            echo "<p>".date("H:i:s", $imgName[1])."</p>";
                                                        }
                                                    @endphp
                                                </td>
                                                <td class="text-center">
                                                    @if (isset($userGeolocations->presence_image[1]))
                                                        <a href="{{ asset("sources/geolocation/" . $_GET["date"] . "/img/" . $userGeolocations->presence_image[1]) }}"
                                                            target="_blank">
                                                            <i class="mdi mdi-image" style="font-size: 24px; color: #0d6efd;"></i>
                                                        </a>
                                                        @php
                                                            $imgName = explode("_", $userGeolocations->presence_image[1]);
                                                            echo "<p>".date("H:i:s", $imgName[1])."</p>";
                                                        @endphp
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button type="button"
                                                        class="btn"
                                                        data-user="{{ $userGeolocations->user_id }}"
                                                        onclick="getGeolocation(this)">
                                                        <i class="mdi mdi-eye" style="font-size: 24px; color: #3dd5f3;"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div id="map"></div>
                    </div>
                </div>
            </div>

            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive"
                            style="border: 1px solid #ebedf2;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-right">#</th>
                                        <th>Time</th>
                                        <th>Location</th>
                                    </tr>
                                </thead>
                                <tbody id="timetable-body">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade"
    id="error-modal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Error</h5>
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p id="error-body">
                    Failed to load geolocation data.
                    <br>
                    Reason: There's something wrong with the device GPS.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button"
                    class="btn btn-gradient-primary"
                    data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section("script")
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
    integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer"
    defer></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASaeppCIyKr3FpfxVSl-KpiTb8xMXCF6Y"
    defer></script>
<script type="application/javascript">
let tableRowCounter = 0;

document.addEventListener("DOMContentLoaded", function () {
    $("#branch").select2();
});

function getGeolocation(e) {
    const userId = e.dataset.user;
    const date = document.getElementById("date").value;

    fetch(`{{ route("fetch_geolocation") }}?user_id=${userId}&date=${date}`, {
        method: "GET",
        headers: {
            "Accept": "application/json",
        },
        mode: "same-origin",
        referrerPolicy: "no-referrer",
    }).then(function (response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.text();
    }).then(function (response) {
        try {
            const json = JSON.parse(response);
            initMap(json);
            // presenceImage(userId, date);
        } catch (error) {
            $("#error-modal").modal("show");
            console.error(error);
        }
    }).catch(function (error) {
        console.error(error);
    });
}

function initMap(coordinates) {
    document.getElementById("timetable-body").innerHTML = "";

    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 15,
        mapTypeId: "roadmap",
    });

    const path = new google.maps.Polyline({
        path: coordinates,
        geodesic: true,
        strokeColor: "#059452",
        strokeOpacity: 1.0,
        strokeWeight: 5,
    });

    let labels = 1;
    let previousTimestamp = 0;
    let currentTimestamp = 0;
    const bounds = new google.maps.LatLngBounds();
    coordinates.forEach(function (value, index) {
        bounds.extend({ lat: value.lat, lng: value.lng});

        if (index === 0) {
            addRowToTable(labels.toString(), value.timestamp);
            requestGeocoding(value.lat, value.lng, tableRowCounter - 1);

            new google.maps.Marker({
                position: { lat: value.lat, lng: value.lng },
                label: labels.toString(),
                map: map,
            });

            labels++;
        }

        if (previousTimestamp === 0) {
            previousTimestamp = value.timestamp;
        }

        currentTimestamp = value.timestamp;
        const timestampDifference = currentTimestamp - previousTimestamp;

        if (timestampDifference >= 900000) {
            addRowToTable(labels.toString(), previousTimestamp, currentTimestamp);
            requestGeocoding(value.lat, value.lng, tableRowCounter - 1);

            new google.maps.Marker({
                position: { lat: value.lat, lng: value.lng },
                label: labels.toString(),
                map: map,
            });

            labels++;
        }

        previousTimestamp = currentTimestamp;

        if (index === coordinates.length - 1) {
            addRowToTable(labels.toString(), value.timestamp);
            requestGeocoding(value.lat, value.lng, tableRowCounter - 1);

            new google.maps.Marker({
                position: { lat: value.lat, lng: value.lng },
                label: labels.toString(),
                map: map,
            });
        }
    });

    path.setMap(map);
    map.fitBounds(bounds);
}

function addRowToTable(label, unixTimestamp1, unixTimestamp2 = null, location = "-") {
    const tableRow = document.createElement("tr");
    const tableDataNumber = document.createElement("td");
    const tableDataTime = document.createElement("td");
    const tableDataLocation = document.createElement("td");

    tableDataNumber.className = "text-right";
    tableDataNumber.innerHTML = label;

    const time1 = new Date(unixTimestamp1);
    tableDataTime.innerHTML = `${("0" + (time1.getHours())).slice(-2)}:${("0" + (time1.getMinutes())).slice(-2)}`;
    if (unixTimestamp2 !== null) {
        const time2 = new Date(unixTimestamp2);
        tableDataTime.innerHTML += ` - ${("0" + (time2.getHours())).slice(-2)}:${("0" + (time2.getMinutes())).slice(-2)}`;
    }

    tableDataLocation.id = `location-${tableRowCounter}`;
    tableDataLocation.innerHTML = location;

    tableRow.appendChild(tableDataNumber);
    tableRow.appendChild(tableDataTime);
    tableRow.appendChild(tableDataLocation);
    document.getElementById("timetable-body").appendChild(tableRow);

    tableRowCounter++;
}

function presenceImage(userId, date) {
    const directory = '{{ url("/sources/geolocation") }}';

    fetch(`{{ route("fetch_geolocation_presence") }}?user_id=${userId}&date=${date}`, {
        method: "GET",
        headers: {
            "Accept": "application/json",
        },
        mode: "same-origin",
        referrerPolicy: "no-referrer",
    }).then(function (response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    }).then(function (response) {
        const data = response.data;
        document.getElementById("presence-start").setAttribute("src", directory + "/" + date + "/img/" + data[0]);
        document.getElementById("presence-end").setAttribute("src", directory + "/" + date + "/img/" + data[1]);
    }).catch(function (error) {
        console.error(error);
    });
}

function requestGeocoding(lat, lng, tableRow) {
    const geocoder = new google.maps.Geocoder();
    geocoder.geocode(
        {
            location: {
                lat: lat,
                lng: lng,
            },
        },
        function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    document.getElementById(`location-${tableRow}`).innerHTML = results[0].formatted_address;
                } else {
                    document.getElementById(`location-${tableRow}`).innerHTML = "-";
                }
            }
        }
    );
}
</script>
@endsection
