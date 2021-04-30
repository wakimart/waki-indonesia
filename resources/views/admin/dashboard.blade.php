@extends('admin.layouts.template')

@section('content')
@if (Auth::user()->roles[0]['slug'] != 'head-admin')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Welcome, {{ Auth::user()->name }}
            </h3>
        </div>
    </div>
</div>
@endif
@can('show-dashboard')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white mr-2">
                    <i class="mdi mdi-home"></i>
                </span> Dashboard
            </h3>
        </div>
        <div class="row">
            <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-danger card-img-holder text-white">
                    <div class="card-body">
                        <img src="{{ asset('sources/dashboard/circle.svg') }}"
                            class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">
                            Month Sale
                            <i class="mdi mdi-chart-line mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">
                            <?php
                            echo "Rp. " . number_format($order->total_payment);
                            ?>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-info card-img-holder text-white">
                    <div class="card-body">
                        <img src="{{ asset('sources/dashboard/circle.svg') }}"
                            class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">
                            Today Home Service
                            <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">
                            <?php echo $homeServiceToday->count; ?>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 stretch-card grid-margin">
                <div class="card bg-gradient-success card-img-holder text-white">
                    <div class="card-body">
                        <img src="{{ asset('sources/dashboard/circle.svg') }}"
                            class="card-img-absolute"
                            alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">
                            Total Registration This Month
                            <i class="mdi mdi-diamond mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">
                            <?php echo $registration->count; ?>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="clearfix">
                            <h4 class="card-title float-left">
                                Home Service - <?php echo date("F Y"); ?>
                            </h4>
                        </div>
                        <canvas id="homeservice-chart" class="mt-4"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection

@section("script")
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.1.1/dist/chart.js"></script>
<script type="application/javascript">
document.addEventListener('DOMContentLoaded', function () {
    const URL = '<?php echo route("dashboard_hs"); ?>';

    fetch(
        URL,
        {
            method: "GET",
            headers: {
                "Accept": "application/json",
                "Content-type": "application/json",
            },
        }
    ).then(function (response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    }).then(function (response) {
        const data = response.data;
        const today = Date.now();
        const currentDate = new Date(today);
        const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 0).getDate();
        const arrayData = [];
        const arrayLabel = [];

        for (let i = 0; i < lastDay; i++) {
            arrayData.push(0);
            arrayLabel.push(`${i + 1}`);
        }

        data.forEach(function (currentValue) {
            arrayData[currentValue.appointment_date - 1] = currentValue.data_count;
        });

        new Chart(
            document.getElementById("homeservice-chart"),
            {
                type: "bar",
                data: {
                    labels: arrayLabel,
                    datasets: [
                        {
                            backgroundColor: "rgba(173, 216, 230, 0.9)",
                            borderColor: "rgba(173, 216, 230, 1)",
                            data: arrayData,
                            label: "Jumlah",
                        }
                    ],
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: "Date",
                            },
                        },
                        y: {
                            ticks: {
                                stepSize: 20,
                            },
                        },
                    },
                },
            }
        );
    }).catch(function (error) {
        console.error(error);
    });
}, false);
</script>
@endsection
