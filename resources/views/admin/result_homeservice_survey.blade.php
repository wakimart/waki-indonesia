<?php
    use App\Order;
?>
@extends('admin.layouts.template')

@section('style')

@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Survey Result
            </h3>
        </div>


        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="clearfix">
                            <h4 class="card-title float-left mb-0">
                                Last 6 Month Margin
                            </h4>
                        </div>
                        <canvas id="marginbarChart" class="mt-4"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="col-xs-6 col-sm-3"
                            style="padding: 0;display: inline-block;">
                            <div class="form-group">
                                <label for="filter_month">
                                    Filter By Month
                                </label>
                                <select class="form-control"
                                    id="filter_month"
                                    name="filter_month">
                                    <option value="" selected="">
                                        All
                                    </option>
                                    <option value="">
                                        January
                                    </option>
                                </select>
                                <div class="validation"></div>
                            </div>
                        </div>
                        <div class="clearfix">
                            <h4 class="card-title float-left mb-0">
                                Rank Per Branch
                            </h4>
                        </div>
                        <canvas id="branchrankChart" class="mt-4"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
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

                                </select>
                                <div class="validation"></div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("script")
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.1.1/dist/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0-rc"></script>
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
            document.getElementById("margin-chart"),
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

<!-- Chart data -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    var dataMargin = {
      labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun"],
      datasets: [{
        data: [10, 19, 3, 5, 2, 3],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(255, 159, 64, 0.2)'
        ],
        borderColor: [
          'rgba(255,99,132,1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)'
        ],
        borderWidth: 1,
        fill: false
      }]
    };
    var optionsMargin = {
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
                  beginAtZero: true,
                  stepSize: 10,
              },
          },
      },
      plugins: {
         legend: {
            display: false
         }
      },
    };

    var optionsRank = {
      responsive: true,
      indexAxis: 'y',
      scales: {
          x: {
              ticks: {
                  min: 0,
                  max: 100,
                  beginAtZero: true,
                  stepSize: 25,
              },
          },
      },
      plugins: {
        legend: {
           display: false
        },
        datalabels: {
          formatter: (val, context) => (`${val}%`),
          anchor: 'end',
          align: 'end',
          labels: {
            value: {
              color: 'black',
              padding: 5,
            }
          }
        },
      },
    };

    if ($("#marginbarChart").length) {
      var marginbarChartCanvas = $("#marginbarChart").get(0).getContext("2d");
      // This will get the first returned node in the jQuery collection.
      var marginbarChart = new Chart(marginbarChartCanvas, {
        type: 'bar',
        data: dataMargin,
        options: optionsMargin
      });
    }

    if ($("#branchrankChart").length) {
      var rankChartCanvas = $("#branchrankChart").get(0).getContext("2d");
      // This will get the first returned node in the jQuery collection.
      var rankChart = new Chart(rankChartCanvas, {
        type: 'bar',
        plugins: [ChartDataLabels],
        data: dataMargin,
        options: optionsRank
      });
    }
}, false);
</script>
@endsection
