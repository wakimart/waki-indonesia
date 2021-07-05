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
                <form class="col-12">
                    <div class="col-xs-6 col-sm-3"
                        style="padding: 0; display: inline-block;">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input class="form-control"
                                type="date"
                                id="date"
                                name="date"
                                placeholder="Date" />
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-3"
                        style="padding: 0; display: inline-block;">
                        <div class="form-group">
                            <label for="cso">CSO</label>
                            <select class="form-control"
                                id="cso"
                                name="cso_id">
                                <option value="">Choose CSO</option>
                                @foreach ($csos as $cso)
                                    <option value="{{ $cso->id }}"
                                        {!! isset($_GET["cso"]) && $_GET["cso"] == $cso->id ? "selected" : "" !!}>
                                        {{ $cso->code }} - {{ $cso->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6"
                        style="padding: 0; display: inline-block;">
                        <div class="form-group">
                            <button id="btn-filter"
                                type="button"
                                class="btn btn-gradient-primary m-1"
                                onclick="getGeolocation()">
                                <span class="mdi mdi-magnify"></span> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div id="map"></div>
                    </div>
                </div>
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
let coordinates = [];

document.addEventListener("DOMContentLoaded", function () {
    $("#cso").select2();
});

function getGeolocation() {
    const csoId = document.getElementById("cso").value;
    const date = document.getElementById("date").value;

    fetch(`{{ route("fetch_geolocation") }}?cso_id=${csoId}&date=${date}`, {
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
        coordinates = response;
        initMap();
    }).catch(function (error) {
        console.error(error);
    });
}

function initMap() {
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
            new google.maps.Marker({
                position: { lat: value.lat, lng: value.lng },
                label: labels.toString(),
                map: map,
            });

            labels++;
        }

        previousTimestamp = currentTimestamp;

        if (index === coordinates.length - 1) {
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
</script>
@endsection
