<?php
    use App\Order;
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    .wrapper {
        overflow: auto;
        width: auto;
        max-height: 600px;
        white-space: nowrap;
        padding-bottom: 10px;
        padding-top: 10px;
    }
</style>
@endsection

@section('content')
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

        @if(Gate::check('change-status-checkin-personalhomecare') == true || Gate::check('change-status-checkout-personalhomecare') == true || Gate::check('change-status-verified-personalhomecare') == true || Gate::check('acc-reschedule-personalhomecare') == true || Gate::check('acc-extend-personalhomecare') == true)
            <div class="col-12 grid-margin stretch-card px-0">
                <div class="card">
                    <div class="card-header" style="background: none;">
                        <h4 style="margin-bottom: 1em;">
                            Personal Homecare ACC
                        </h4>
                        <ul class="nav nav-tabs card-header-tabs">
                            @foreach($personalHomecares as $keyNya => $arrPP5H)
                                <li class="nav-item">
                                    <a class="nav-link {{ $keyNya == "new" ? 'active' : '' }}"
                                        style="font-weight: 500; font-size: 1em;"
                                        id="{{ $keyNya }}-tab"
                                        data-toggle="tab"
                                        href="#{{ $keyNya }}-table"
                                        role="tab"
                                        aria-controls="{{ $keyNya }}"
                                        aria-selected="true">
                                        {{ ucwords(str_replace("_", " ",$keyNya)) }} ({{ sizeof($arrPP5H) }})
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-body wrapper">
                        <div class="tab-content" id="myTabContent">
                            @foreach($personalHomecares as $keyNya => $arrPP5H)
                                <div class="tab-pane fade show {{ $keyNya == "new" ? 'active' : '' }} p-3" id="{{ $keyNya }}-table" role="tabpanel" aria-labelledby="{{ $keyNya }}-tab">
                                    <h5 style="margin-bottom: 0.5em;">
                                        Status {{ ucwords(str_replace("_", " ",$keyNya)) }} | Total: {{ sizeof($arrPP5H) }}
                                    </h5>
                                    <div class="table-responsive"
                                        style="border: 1px solid #ebedf2;">
                                        <table class="table table-bordered">
                                            <thead style="text-align: center; background-color: aliceblue;">
                                                <tr>
                                                    <td colspan="2">Personal Homecare Data</td>
                                                    <td rowspan="2">View</td>
                                                </tr>
                                                <tr>
                                                    <td>Customer <br class="break">(Branch - CSO)</td>
                                                    <td>Product</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($arrPP5H as $personalHomecare)
                                                    <tr>
                                                        <td>
                                                            {{ $personalHomecare['name'] }} - {{ $personalHomecare['phone'] }}
                                                            <br class="break">
                                                            ({{ $personalHomecare->branch['code'] }} - {{ $personalHomecare->cso['code'] }})
                                                        </td>
                                                        <td>{{ $personalHomecare->personalHomecareProduct['code'] }} - {{ $personalHomecare->personalHomecareProduct->product['code'] }}</td>
                                                        <td style="text-align: center;">
                                                            <a href="{{ route('detail_personal_homecare', ['id' => $personalHomecare['id']]) }}">
                                                                <i class="mdi mdi-eye" style="font-size: 24px;"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <br>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body wrapper">
                        <div class="clearfix">
                            <h4 class="card-title float-left">
                                Cancel Homeservice to Acc (total : {{ sizeof($accDeleteHS) }})
                            </h4>
                        </div>
                        {{-- <canvas id="homeservice-chart" class="mt-4"></canvas> --}}
                        <div class="table-responsive" style="border: 1px solid #ebedf2;">
                            <table class="table table-bordered">
                                <thead style="text-align: center; background-color: aliceblue;">
                                    <tr>
                                        <td colspan="2">Home Service Data</td>
                                        <td rowspan="2">Acc/Reject</td>
                                    </tr>
                                    <tr>
                                        <td>Schedule</td>
                                        <td>Detail & Cancel Reason Home Service</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($accDeleteHS as $perHomeservice)
                                        <tr>
                                            <td>{{ date_format(date_create($perHomeservice['appointment']), 'd/m/Y H:i') }}</td>
                                            <td>
                                              <div class="detailHs" style="border-bottom: 0.2px solid #2f2f2f">
                                                Type HS : {{ $perHomeservice['type_homeservices'] }}
                                                <br class="break">
                                                {{ $perHomeservice['name'] }} - {{ $perHomeservice['phone'] }}
                                                <br class="break">
                                                ({{ $perHomeservice->branch['code'] }} - {{ $perHomeservice->cso['code'] }})
                                                <br class="break">
                                              </div>

                                              <br class="break">
                                              <div class="cancelReason" style="font-weight:bold;">
                                                {{ $perHomeservice['cancel_desc'] }}
                                              </div>
                                            </td>

                                            <td style="text-align: center;">
                                              @if(Auth::user()->inRole("head-admin"))
                                              <form id="formUpdateStatusHS" method="POST" action="{{ route('update_homeService') }}" style="margin: auto;">
                                                  @csrf
                                                  <div class="form-group">

                                                      <input type="hidden" id="hiddenInput" name="cancel" value="1" />
                                                      <input type="hidden" id="input_id_hs_hidden" name="id" value="{{ $perHomeservice->id }}" />

                                                      <div style="text-align: center;">
                                                        <p>Do you approved it ?</p>
                                                      </div>

                                                      <div style="text-align: center;">
                                                          <button type="submit" class="btn btn-gradient-primary" name="status_acc" value="true">Yes</button>
                                                          <button type="submit" class="btn btn-gradient-danger" name="status_acc" value="false">No</button>
                                                      </div>
                                                  </div>
                                              </form>
                                              @else
                                                <a href="{{ route('admin_list_homeService', ["id_hs"=>$perHomeservice['id']]) }}">
                                                    <i class="mdi mdi-eye" style="font-size: 24px;"></i>
                                                </a>
                                              @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::id() == 1)
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body wrapper">
                        <div class="clearfix">
                            <h4 class="card-title float-left">
                                Reference Souvenir to Acc (total : {{ sizeof($refSouvenirs) }})
                            </h4>
                        </div>
                        {{-- <canvas id="homeservice-chart" class="mt-4"></canvas> --}}
                        <div class="table-responsive" style="border: 1px solid #ebedf2;">
                            <table class="table table-bordered">
                                <thead style="text-align: center; background-color: aliceblue;">
                                    <tr>
                                        <td rowspan="2">Submission Code</td>
                                        <td colspan="4">Reference Souvenir Data</td>
                                        <td rowspan="2">View</td>
                                    </tr>
                                    <tr>
                                        <td>Name - Phone</td>
                                        <td>Branch - CSO</td>
                                        <td>Order</td>
                                        <td>Status Prize</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($refSouvenirs as $refSouvenir)
                                        <tr>
                                            <td>{{ $refSouvenir->reference->submission['code'] }}</td>
                                            <td>{{ $refSouvenir->reference['name'] }} - {{ $refSouvenir->reference['phone'] }}</td>
                                            <td>{{ $refSouvenir->reference->submission->branch['code'] }} - {{ $refSouvenir->reference->submission->cso['name'] }}</td>
                                            <td>
                                                <?php
                                                if (!empty($refSouvenir['order_id'])) {
                                                    $order = Order::select("id", "code")
                                                        ->where("id", $refSouvenir['order_id'])
                                                        ->first();
                                                    echo '<a href="'
                                                        . route("detail_order", ["code" => $order->code])
                                                        . '">'
                                                        . $order->code
                                                        . '</a>';
                                                }
                                                ?>
                                            </td>
                                            <td>{{ $refSouvenir['status_prize'] }}</td>
                                            <td style="text-align: center;">
                                                <a href="{{ route("detail_submission_form", ['id'=>$refSouvenir->reference->submission['id'], 'type'=>"mgm", "id_ref"=>$refSouvenir->reference['id']]) }}">
                                                    <i class="mdi mdi-eye" style="font-size: 24px;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="clearfix">
                            <h4 class="card-title float-left">
                                New Reference to Review (total : {{ sizeof($references) }})
                            </h4>
                        </div>
                        {{-- <canvas id="homeservice-chart" class="mt-4"></canvas> --}}
                        <div class="table-responsive"
                            style="border: 1px solid #ebedf2;">
                            <table class="table table-bordered">
                                <thead style="text-align: center; background-color: aliceblue;">
                                    <tr>
                                        <td rowspan="2">Submission Code</td>
                                        <td colspan="3">Reference Data</td>
                                        <td rowspan="2">View</td>
                                    </tr>
                                    <tr>
                                        <td>Name</td>
                                        <td>Phone</td>
                                        <td>Link HS</td>
                                        {{-- <td>Order</td> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($references as $ref)
                                        @if($ref->reference['active'] == true && $ref->reference->submission['active'] == true )
                                            <tr>
                                                <td>{{ $ref->reference->submission['code'] }}</td>
                                                <td>{{ $ref->reference['name'] }}</td>
                                                <td>{{ $ref->reference['phone'] }}</td>

                                                @php
                                                    if (!empty($ref->link_hs)) {
                                                        $i = 1;
                                                        $link_hs = json_decode(
                                                            $ref->link_hs,
                                                            JSON_THROW_ON_ERROR
                                                        );
                                                    }
                                                @endphp
                                                <td>
                                                    @foreach ($link_hs as $value)
                                                        @if (is_numeric($value))
                                                            <?php
                                                            $hs = App\HomeService::select("code")->where("id", $value)->first();
                                                            ?>
                                                            <a href="{{ route("homeServices_success", ["code" => $hs->code]) }}"
                                                                target="_blank">
                                                                <i class="mdi mdi-numeric-{{ $i }}-box" style="font-size: 24px; color: #2daaff;"></i>
                                                            </a>
                                                        @else
                                                            <a href="{{ $value }}"
                                                                target="_blank">
                                                                <i class="mdi mdi-numeric-{{ $i }}-box" style="font-size: 24px; color: red;"></i>
                                                            </a>
                                                        @endif
                                                        <?php $i++; ?>
                                                    @endforeach
                                                </td>

                                                {{-- <td>
                                                    @if($ref['order_id'] != null)
                                                        <a href="{{ route('detail_order') }}?code={{ $ref->order['code'] }}" target="_blank">
                                                            {{ $ref->order['code'] }}
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </td> --}}
                                                <td style="text-align: center;">
                                                    <a href="{{ route("detail_submission_form", ["id" => $ref->reference->submission['id'], "type" => 'referensi']) }}">
                                                        <i class="mdi mdi-eye" style="font-size: 24px;"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
@else
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Welcome, {{ Auth::user()->name }}
            </h3>
        </div>

        @if(Gate::check('change-status-checkin-personalhomecare') == true || Gate::check('change-status-checkout-personalhomecare') == true || Gate::check('change-status-verified-personalhomecare') == true || Gate::check('acc-reschedule-personalhomecare') == true || Gate::check('acc-extend-personalhomecare') == true)
            <div class="col-12 grid-margin stretch-card px-0">
                <div class="card">
                    <div class="card-header" style="background: none;">
                        <h4 style="margin-bottom: 1em;">
                            Personal Homecare ACC
                        </h4>
                        <ul class="nav nav-tabs card-header-tabs">
                            @foreach($personalHomecares as $keyNya => $arrPP5H)
                                <li class="nav-item">
                                    <a class="nav-link {{ $keyNya == "new" ? 'active' : '' }}"
                                        style="font-weight: 500; font-size: 1em;"
                                        id="{{ $keyNya }}-tab"
                                        data-toggle="tab"
                                        href="#{{ $keyNya }}-table"
                                        role="tab"
                                        aria-controls="{{ $keyNya }}"
                                        aria-selected="true">
                                        {{ ucwords(str_replace("_", " ",$keyNya)) }} ({{ sizeof($arrPP5H) }})
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            @foreach($personalHomecares as $keyNya => $arrPP5H)
                                <div class="tab-pane fade show {{ $keyNya == "new" ? 'active' : '' }} p-3" id="{{ $keyNya }}-table" role="tabpanel" aria-labelledby="{{ $keyNya }}-tab">
                                    <h5 style="margin-bottom: 0.5em;">
                                        Status {{ ucwords(str_replace("_", " ",$keyNya)) }} | Total: {{ sizeof($arrPP5H) }}
                                    </h5>
                                    <div class="table-responsive"
                                        style="border: 1px solid #ebedf2;">
                                        <table class="table table-bordered">
                                            <thead style="text-align: center; background-color: aliceblue;">
                                                <tr>
                                                    <td colspan="2">Personal Homecare Data</td>
                                                    <td rowspan="2">View</td>
                                                </tr>
                                                <tr>
                                                    <td>Customer <br class="break">(Branch - CSO)</td>
                                                    <td>Product</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($arrPP5H as $personalHomecare)
                                                    <tr>
                                                        <td>
                                                            {{ $personalHomecare['name'] }} - {{ $personalHomecare['phone'] }}
                                                            <br class="break">
                                                            ({{ $personalHomecare->branch['code'] }} - {{ $personalHomecare->cso['code'] }})
                                                        </td>
                                                        <td>{{ $personalHomecare->personalHomecareProduct['code'] }} - {{ $personalHomecare->personalHomecareProduct->product['code'] }}</td>
                                                        <td style="text-align: center;">
                                                            <a href="{{ route('detail_personal_homecare', ['id' => $personalHomecare['id']]) }}">
                                                                <i class="mdi mdi-eye" style="font-size: 24px;"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <br>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
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
