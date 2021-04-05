<?php
    
?>
@extends('layouts.template')

@section('content')
<style type="text/css">
    #intro {
        padding-top: 2em;
    }

    .card-shadow {
        box-shadow: 0 0 10px 0 rgba(100, 100, 100, 0.26);
        padding:1em; 
    }


    .timeline .timeline-item {
        display: flex;
        position: relative;
    }

    .timeline .timeline-item::before {
        background: #dadee4;
        content: "";
        height: 100%;
        left: 19px;
        position: absolute;
        top: 20px;
        width: 2px;
        z-index: -1;
    }

    .timeline .timeline-item .timeline-icon{
        margin-top: 20px;
        width: 40px;
        height: 40px;
        background: #048b32;
        border-radius: 50%;
        display: flex;
        align-items: center;
    }

    .timeline .timeline-item .timeline-content {
        -ms-flex: 1; /* IE 10 */
        flex: 1;
        padding: 0 0 0 1rem;
    }

    .timeline .timeline-item-last {
        display: flex;
        position: relative;
    }

    .timeline .timeline-item-last::before {
        background: #fff;
        content: "";
        height: 100%;
        left: 19px;
        position: absolute;
        top: 20px;
        width: 2px;
        z-index: -1;
    }

    .timeline .timeline-item-last .timeline-icon-last{
        margin-top: 20px;
        width: 40px;
        height: 40px;
        background: #737373;
        border-radius: 50%;
        display: flex;
        align-items: center;
    }
/*-- mobile --*/
@media (min-width: 768px){
    #desktop {
        display: block;
    }
    #mobile {
        display: none;
    }
}


@media (max-width: 768px){
    #desktop {
        display: none;
    }
    #mobile {
        display: block;
        font-size: 0.9em;
    }

    #mobile h6{
        font-size: 1em;
    }

    #mobile .timeline .timeline-item .timeline-content {
        -ms-flex: 1; /* IE 10 */
        flex: 1;
        padding: 0 0 0 0;
    }

    #mobile .table-responsive{
        display: block;
        width: 100%;
        overflow-x: auto;
        -ms-overflow-style: -ms-autohiding-scrollbar; 
    }

}

.td-product-name{
    width: 60em;
}


/* Tabs Card */

.tab-card {
  border:1px solid #eee;
}

.tab-card-header {
  background:none;
}
.tab-content .tab-pane{
  background: none;
}
/* Default mode */
.tab-card-header > .nav-tabs {
  border: none;
  margin: 0px;
}
.tab-card-header > .nav-tabs > li {
  margin-right: 2px;
}
.tab-card-header > .nav-tabs > li > a {
  border: 0;
  border-bottom:2px solid transparent;
  margin-right: 0;
  color: #545454; 
  font-weight: 600;
  padding: 2px 10px;
}

.tab-card-header > .nav-tabs > li > a.show {
    border-bottom:2px solid #048b32;
    color: #048b32;
}
.tab-card-header > .nav-tabs > li > a:hover {
    color: #048b32;
}

.tab-card-header > .tab-content {
  padding-bottom: 0;
}


</style>

<section id="intro" class="clearfix">
    <div class="container">
<!--MOBILE-->
<div id="mobile">
        <div class="row justify-content-center">
            <h4>{{ $services['code'] }}</h4>
        </div>
            <div class="col-xs-12">
              <div class="card card-shadow mt-3 tab-card">
                <div class="card-header tab-card-header">
                  <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" id="one-tab" data-toggle="tab" href="#one" role="tab" aria-controls="One" aria-selected="true">Data Service</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="two-tab" data-toggle="tab" href="#two" role="tab" aria-controls="Two" aria-selected="false">Data Product</a>
                    </li>
                  </ul>
                </div>
        
                <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show active p-3" id="one" role="tabpanel" aria-labelledby="one-tab">
                        <div class="row">
                            <div class="col-6">
                                <label style="color: #737373; font-weight: 600; font-size: 0.9em; ">
                                    No. MPC
                                </label>

                            </div>
                            <div class="col-6">
                                <label style="color: #737373; font-weight: 600; font-size: 0.9em; ">
                                    @if($services['no_mpc'] != null)
                                        {{ $services['no_mpc'] }}
                                    @else
                                        : -
                                    @endif
                                </label>
                            </div>
                        </div>
                    <div class="row">
                        <div class="col-6">
                            <label style="color: #737373; font-weight: 600; font-size: 0.9em; ">
                                Customer Name
                            </label>

                        </div>
                        <div class="col-6">
                            <label style="color: #737373; font-weight: 600; font-size: 0.9em; ">
                                {{ $services['name'] }}
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label style="color: #737373; font-weight: 600; font-size: 0.9em; ">
                                Customer Phone
                            </label>

                        </div>
                        <div class="col-6">
                            <label style="color: #737373; font-weight: 600; font-size: 0.9em; ">
                               {{ $services['phone'] }}
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label style="color: #737373; font-weight: 600; font-size: 0.9em;  ">
                                Customer Address
                            </label>

                        </div>
                        <div class="col-6">
                            <label style="color: #737373; font-weight: 600; font-size: 0.9em; ">
                               {{ $services['address'] }}
                            </label>
                        </div>
                    </div>
                </div>      
                  <div class="tab-pane fade p-3" id="two" role="tabpanel" aria-labelledby="two-tab" style="font-size: 0.9em;">
                    @foreach($services->product_services as $key => $product_service)
                    <h6 style="color: #545454; font-weight: 600; margin-bottom: 0.5m;">
                        Product {{$key + 1}}
                    </h6>
                    <div class="row">
                        <div class="col-12">
                            <label style="color: #737373; font-weight: 600; ">
                                @if($product_service['product_id'] != null)
                                      {{$product_service->product['name']}}	
                                  @elseif($product_service['product_id'] == null)
                                      {{$product_service['other_product']}}
                                  @endif
                            </label>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label style="color: #545454; font-weight: 600; ">
                                Issues</label>

                        </div>
                        <div class="col-6">
                            <label style="color: #737373; font-weight: 600; ">
                                @php
                                      $issues = json_decode($product_service['issues']);
                                  @endphp
                                  {{implode(", ",$issues[0]->issues)}}

                            </label>

                        </div>
                    </div>
                    <hr style="padding-top: 0; padding-bottom: 1em;">
                    @endforeach             
                  </div>
                </div>
              </div>
            </div><br>
            <div class="col-xs-12">
                <div class="container">
                    <h6 class="text-center" style="color: #737373; font-weight: 700; text-transform: uppercase; padding-top: 1em;">Proses Service</h6>
                    <div class="timeline wow bounceInUp clearfix">
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                    <i class="fas fa-check" style="color: #fff; text-align: center; width: 100%;"></i>
                            </div>
                            <div class="timeline-content">
                                    <div class="card-body">
                                        <h6 style="color: #048b32; font-weight: 700;">New</h6>
                                        <h6 style="font-weight: 500; padding-top: 0">{{ date('d M Y', strtotime($services['service_date'])) }}</h6>
                                        <hr>
                                        <p style="font-weight: 400;">Pengajuan Servis telah diterima</p>
                                    </div>
                            </div>
                        </div>
                    </div>
                    @foreach(json_decode($services['history_status'], true) as $history_status)
                            <div class="timeline wow bounceInUp clearfix">
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                            <i class="fas fa-check" style="color: #fff; text-align: center; width: 100%;"></i>
                                    </div>
                                    @if (strtolower($history_status['status']) == "process")
                                    <div class="timeline-content">
                                        <div class="card-body">
                                            <h6 style="color: #048b32; font-weight: 700;">Processed</h6>
                                            <h6 style="font-weight: 500; padding-top: 0">{{ date('d M Y', strtotime($history_status['updated_at'])) }}</h6>
                                            <hr>
                                            <div class="card">
                                                
                                                <div class="card-body">
                                                    <h6 class="text-center" style="font-weight: 600;">Detail Sparepart</h6>
                                                    @foreach($services->product_services as $key => $product_service)
                                                    <p class="card-title" style="font-weight: 600;">Product {{$key + 1}}</p>
                                                    @php
                                                        $arr_sparepart = json_decode($product_service['sparepart']);
                                                    @endphp
                                                    @foreach($arr_sparepart as $index => $item)
                                                    <p class="card-subtitle mb-2" style="font-weight: 500;">{{$product_service->getSparepart($item->id)->id['name']}}</p>
                                                    <p class="card-text" font-size: 0.9em>Qty: {{$item->qty}}</p>
                                                    @endforeach

                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @elseif (strtolower($history_status['status']) == "repaired")
                                    <div class="timeline-content">
                                        <div class="card-body">
                                            <h6 style="color: #048b32; font-weight: 700;">Repaired</h6>
                                            <h6 style="font-weight: 500; padding-top: 0">{{ date('d M Y', strtotime($history_status['updated_at'])) }}</h6>
                                            <hr>
                                            <p style="font-weight: 400;">Produk dalam proses Repaired</p>
                                        </div>
                                    </div>
                                    @elseif (strtolower($history_status['status']) == "quality_control")
                                    <div class="timeline-content">
                                        <div class="card-body">
                                            <h6 style="color: #048b32; font-weight: 700;">Quality Control</h6>
                                            <h6 style="font-weight: 500; padding-top: 0">{{ date('d M Y', strtotime($history_status['updated_at'])) }}</h6>
                                            <hr>
                                            <p style="font-weight: 400;">Produk dalam proses Quality Control</p>
                                        </div>
                                    </div>
                                    @elseif (strtolower($history_status['status']) == "delivery")
                                    <div class="timeline-content">
                                        <div class="card-body">
                                            <h6 style="color: #048b32; font-weight: 700;">Delivered</h6>
                                            <h6 style="font-weight: 500; padding-top: 0">{{ date('d M Y', strtotime($history_status['updated_at'])) }}</h6>
                                            <hr>
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="card-title" style="font-weight: 600; padding-bottom: 1em;">Delivery Detail</h6>
                                                    @php
                                                        $arr_serviceoption = $services->ServiceOptionDelivery();
                                                    @endphp
                                                    <h6 class="card-subtitle mb-2" style="font-weight: 500;">{{ $arr_serviceoption->recipient_name }}</h6>
                                                    <p class="card-text">{{ $arr_serviceoption->address }}<br>{{ $arr_serviceoption->recipient_phone }} </p>
                                                    <p>Branch: {{ $services->getDetailSales($arr_serviceoption->branch_id, $arr_serviceoption->cso_id)->branch['code'] }} - {{ $services->getDetailSales($arr_serviceoption->branch_id, $arr_serviceoption->cso_id)->branch['name'] }}
                                                        <br> CSO Code: {{ $services->getDetailSales($arr_serviceoption->branch_id, $arr_serviceoption->cso_id)->cso['code'] }} - {{ $services->getDetailSales($arr_serviceoption->branch_id, $arr_serviceoption->cso_id)->cso['name'] }}
                                                    </p>
                                                    @if(isset($arr_serviceoption->appointment))
                                                    <h6 style="font-weight: 600;">Delivery Schedule</h6>
                                                    <p class="card-text">{{ date("d/m/Y", strtotime($arr_serviceoption->appointment)) }}<br>{{ date("H:i", strtotime($arr_serviceoption->appointment)) }}</p>
                                                    @endif
                                                </div>

                                            </div>{{-- 
                                            <p style="font-weight: 400;">Produk telah dikirim pada</p>
                                                <p>{{$service_option['recipient_name']}}<br>{{$service_option['address']}}</p> --}}
                                        </div>
                                    </div>
                                    @elseif (strtolower($history_status['status']) == "take_away")
                                    <div class="timeline-content">
                                        <div class="card-body">
                                            <h6 style="color: #048b32; font-weight: 700;">Take Away</h6>
                                            <h6 style="font-weight: 500; padding-top: 0">{{ date('d M Y', strtotime($history_status['updated_at'])) }}</h6>
                                            <hr>
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="card-title" style="font-weight: 600; padding-bottom: 1em;">Pickup Detail</h6>
                                                    @php
                                                        $arr_serviceoption = $services->ServiceOptionDelivery();
                                                    @endphp
                                                    <h6 class="card-subtitle mb-2" style="font-weight: 500;">{{ $arr_serviceoption->recipient_name }}</h6>
                                                    <p class="card-text">{{ $arr_serviceoption->recipient_phone }} </p>
                                                    <p>Branch: {{ $services->getDetailSales($arr_serviceoption->branch_id, $arr_serviceoption->cso_id)->branch['code'] }} - {{ $services->getDetailSales($arr_serviceoption->branch_id, $arr_serviceoption->cso_id)->branch['name'] }}
                                                        <br> CSO Code: {{ $services->getDetailSales($arr_serviceoption->branch_id, $arr_serviceoption->cso_id)->cso['code'] }} - {{ $services->getDetailSales($arr_serviceoption->branch_id, $arr_serviceoption->cso_id)->cso['name'] }}
                                                    </p>
                                                    @if(isset($arr_serviceoption->appointment))
                                                    <h6 style="font-weight: 500;">Pickup Schedule</h6>
                                                    <p class="card-text">{{ date("d/m/Y", strtotime($arr_serviceoption->appointment)) }}<br>{{ date("H:i", strtotime($arr_serviceoption->appointment)) }}</p>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    @elseif (strtolower($history_status['status']) == "completed")
                                    <div class="timeline-content">
                                        <div class="card-body">
                                            <h6 style="color: #048b32; font-weight: 700;">Completed</h6>
                                            <h6 style="font-weight: 500; padding-top: 0">{{ date('d M Y', strtotime($history_status['updated_at'])) }}</h6>
                                            <hr>
                                                        
                                                       
                                            {{-- <div class="table-responsive"> --}}
                                                            {{-- <table class="table table-responsive table-borderless">
                                                                <thead>
                                                                    <h6 class="text-center" style="font-weight: 600;">Biaya Service</h6>
                                                                </thead>
                                                                @foreach($services->product_services as $key => $product_service)
                                                                <tbody>
                                                                    <tr> 
                                                                        <td colspan="3">
                                                                            <p class="card-title" style="font-weight: 600;">Product {{$key + 1}}</p>
                                                                        </td>
                                                                    </tr>
                                                                    @php
                                                                    $arr_sparepart = json_decode($product_service['sparepart']);
                                                                    $count_sparepart = count($arr_sparepart);
                                                                    @endphp
                                                                    <tr>
                                                                        @foreach($arr_sparepart as $index => $item)
                                                                        @php
                                                                            $unit_price = $product_service->getSparepart($item->id)->id['price'];
                                                                            $total_price = $item->qty * $unit_price;
                                                                        @endphp --}}
                                                                       {{--  <td>
                                                                            {{$product_service->getSparepart($item->id)->id['name']}}
                                                                        </td> --}}
                                                                        {{-- <td>
                                                                            {{$item->qty}}x
                                                                        </td>
                                                                        <td class="text-right">{{number_format($unit_price)}}</td>
                                                                        <td class="text-right">{{number_format($total_price)}}</td>
                                                                        @php break; @endphp
                                                                        @endforeach
                                                                    </tr>
                                                                    @php $first = true; @endphp
                                                                        @for($i = 0; $i < $count_sparepart; $i++)
                                                                        @php
                                                                            if($first){
                                                                                $first = false;
                                                                                continue;
                                                                            }
                                                                            $unit_price = $product_service->getSparepart($arr_sparepart[$i]->id)->id['price'];
                                                                            $total_price = $item->qty * $unit_price;
                                                                        @endphp
                                                                        <tr>
                                                                            {{-- <td>{{$product_service->getSparepart($arr_sparepart[$i]->id)->id['name']}}</td> --}}
                                                                           {{--  <td>{{$arr_sparepart[$i]->qty}}x</td>
                                                                            <td class="text-right">{{number_format($unit_price)}}</td>
                                                                            <td class="text-right">{{number_format($total_price)}}</td>
                                                                        </tr>
                                                                    @endfor

                                                                </tbody>
                                                                @endforeach
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="3">
                                                                            <p style="font-weight: 600;">Total</p>
                                                                        </td>
                                                                        <td>

                                                                        </td>
                                                                    </tr>

                                                                </tfoot>
                                                            </table> --}}
    
                                                        {{-- </div> --}}
                                                        
                                        </div>
                                    </div>

                                    @endif
                                </div>
                            </div>
                    @endforeach
                    <div class="timeline wow bounceInUp clearfix">
                        <div class="timeline-item-last">
                            <div class="timeline-icon-last">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>


<!--DESKTOP-->
    <div id="desktop">
            
        <div class="row justify-content-center">
            <h2>{{ $services['code'] }}</h2>
        </div>
        <div class="row" style="padding-top: 1em;">
            <div class="col-md-4 col-sm-4 col-xs-4">
                <div class="card card-shadow">
                    <div class="card-body">
                        <div class="row justify-content-center" style="padding-bottom: 0;">
                            <h5 style="font-weight: 600;">
                                Data Service
                            </h5>
                        </div>
                        <hr style="height:2px;border:none;color:#ebebeb; background-color:#ebebeb; margin-bottom: 2em;">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <label style="color: #545454; font-weight: 600; ">
                                    No. MPC
                                </label>
    
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <label style="color: #545454; font-weight: 600; ">
                                    @if($services['no_mpc'] != null)
	          							{{ $services['no_mpc'] }}
	          						@else
	          							: -
	          						@endif
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <label style="color: #545454; font-weight: 600; ">
                                    Customer Name
                                </label>
    
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <label style="color: #545454; font-weight: 600; ">
                                    {{ $services['name'] }}
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <label style="color: #545454; font-weight: 600; ">
                                    Customer Phone
                                </label>
    
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <label style="color: #545454; font-weight: 600; ">
                                   {{ $services['phone'] }}
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <label style="color: #545454; font-weight: 600; ">
                                    Customer Address
                                </label>
    
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <label style="color: #545454; font-weight: 600; ">
                                   {{ $services['address'] }}
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
                <br>
                <div class="card card-shadow ">
                    <div class="card-body">
                        <div class="row justify-content-center" style="padding-bottom: 0;">
                            <h5 style="font-weight: 600;">
                                Data Product
                            </h5>
                        </div>
                        <hr style="height:2px;border:none;color:#ebebeb; background-color:#ebebeb; margin-bottom: 2em;">
                        @foreach($services->product_services as $key => $product_service)
                        <h6 style="color: #545454; font-weight: 700; ">
                            Product {{$key + 1}}
                        </h6>
                            <table class="table table-responsive table-borderless">
                                <tr>
                                    <td>
                                        <label style="color: #545454; font-weight: 600; ">
                                            @if($product_service['product_id'] != null)
                                                  {{$product_service->product['name']}}	
                                              @elseif($product_service['product_id'] == null)
                                                  {{$product_service['other_product']}}
                                              @endif
                                        </label>
                                    </td>

                                </tr>
                                <tr>
                                    <td style="padding-bottom: 0;"><label style="color: #545454; font-weight: 700; ">
                                        Issues</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 0;">
                                        <label style="color: #545454; font-weight: 600; ">
                                            @php
                                                  $issues = json_decode($product_service['issues']);
                                              @endphp
                                              {{implode(", ",$issues[0]->issues)}}
        
                                        </label>
                                    </td>

                                </tr>
                            </table>
                        <hr style="padding-top: 0; padding-bottom: 1em;">
                        @endforeach
                    </div>
                </div>

            </div>
            <div class="col-md-8 col-sm-8 col-xs-8">
                <div class="container">
                    <div class="timeline wow bounceInUp clearfix">
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                    <i class="fas fa-check" style="color: #fff; text-align: center; width: 100%;"></i>
                            </div>
                            <div class="timeline-content">
                                    <div class="card-body">
                                        <h6 style="color: #048b32; font-weight: 700;">New</h6>
                                        <h6 style="font-weight: 500; padding-top: 0">{{ date('d M Y', strtotime($services['service_date'])) }}</h6>
                                        <hr>
                                        <p style="font-weight: 400;">Pengajuan Servis telah diterima</p>
                                    </div>
                            </div>
                        </div>
                    </div>
                    @foreach(json_decode($services['history_status'], true) as $history_status)
                            <div class="timeline wow bounceInUp clearfix">
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                            <i class="fas fa-check" style="color: #fff; text-align: center; width: 100%;"></i>
                                    </div>
                                    @if (strtolower($history_status['status']) == "process")
                                    <div class="timeline-content">
                                        <div class="card-body">
                                            <h6 style="color: #048b32; font-weight: 700;">Processed</h6>
                                            <h6 style="font-weight: 500; padding-top: 0">{{ date('d M Y', strtotime($history_status['updated_at'])) }}</h6>
                                            <hr>
                                            <div class="card">
                                                
                                                <div class="card-body">
                                                    <h6 class="text-center" style="font-weight: 600;">Detail Sparepart</h6>
                                                    @foreach($services->product_services as $key => $product_service)
                                                    <h6 class="card-title" style="font-weight: 600;">Product {{$key + 1}}</h6>
                                                    @php
                                                        $arr_sparepart = json_decode($product_service['sparepart']);
                                                    @endphp
                                                    @foreach($arr_sparepart as $index => $item)
                                                    <h6 class="card-subtitle mb-2" style="font-weight: 500;">{{$product_service->getSparepart($item->id)->id['name']}}</h6>
                                                    <p class="card-text">Qty: {{$item->qty}}</p>
                                                    @endforeach

                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @elseif (strtolower($history_status['status']) == "repaired")
                                    <div class="timeline-content">
                                        <div class="card-body">
                                            <h6 style="color: #048b32; font-weight: 700;">Repaired</h6>
                                            <h6 style="font-weight: 500; padding-top: 0">{{ date('d M Y', strtotime($history_status['updated_at'])) }}</h6>
                                            <hr>
                                            <p style="font-weight: 400;">Produk dalam proses Repaired</p>
                                        </div>
                                    </div>
                                    @elseif (strtolower($history_status['status']) == "quality_control")
                                    <div class="timeline-content">
                                        <div class="card-body">
                                            <h6 style="color: #048b32; font-weight: 700;">Quality Control</h6>
                                            <h6 style="font-weight: 500; padding-top: 0">{{ date('d M Y', strtotime($history_status['updated_at'])) }}</h6>
                                            <hr>
                                            <p style="font-weight: 400;">Produk dalam proses Quality Control</p>
                                        </div>
                                    </div>
                                    @elseif (strtolower($history_status['status']) == "delivery")
                                    <div class="timeline-content">
                                        <div class="card-body">
                                            <h6 style="color: #048b32; font-weight: 700;">Delivered</h6>
                                            <h6 style="font-weight: 500; padding-top: 0">{{ date('d M Y', strtotime($history_status['updated_at'])) }}</h6>
                                            <hr>
                                            <div class="card">
                                                <div class="card-body">
                                                  <h6 class="card-title" style="font-weight: 600; padding-bottom: 1em;">Delivery Detail</h6>
                                                  <div class="row">
                                                    @php
                                                  $arr_serviceoption = $services->ServiceOptionDelivery();
                                                  @endphp
                                                  <div class="col-lg-7 col-md-7 col-sm-7 border-right">
                                                    <h6 class="card-subtitle mb-2" style="font-weight: 500;">{{ $arr_serviceoption->recipient_name }}</h6>
                                                    <p class="card-text" style="font-size: 1em; ">{{ $arr_serviceoption->address }}<br>{{ $arr_serviceoption->recipient_phone }} </p>
                                                    <p style="font-size: 1em; text-transform: capitalize;">Branch: {{ $services->getDetailSales($arr_serviceoption->branch_id, $arr_serviceoption->cso_id)->branch['code'] }} - {{ $services->getDetailSales($arr_serviceoption->branch_id, $arr_serviceoption->cso_id)->branch['name'] }}
                                                        <br> CSO Code: {{ $services->getDetailSales($arr_serviceoption->branch_id, $arr_serviceoption->cso_id)->cso['code'] }} - {{ $services->getDetailSales($arr_serviceoption->branch_id, $arr_serviceoption->cso_id)->cso['name'] }}
                                                    </p>

                                                  </div>
                                                  <div class="col-lg-5 col-md-5 col-sm-5">
                                                    <h6 style="font-size: 1em; font-weight: 600;">Delivery Schedule</h6>
                                                    @if(isset($arr_serviceoption->appointment))
                                                    <p class="card-text">{{ date("d/m/Y", strtotime($arr_serviceoption->appointment)) }}<br>{{ date("H:i", strtotime($arr_serviceoption->appointment)) }}</p>
                                                    @endif
                                                  </div>
                                                </div>
                                                </div>
                                            </div>{{-- 
                                            <p style="font-weight: 400;">Produk telah dikirim pada</p>
                                                <p>{{$service_option['recipient_name']}}<br>{{$service_option['address']}}</p> --}}
                                        </div>
                                    </div>
                                    @elseif (strtolower($history_status['status']) == "take_away")
                                    <div class="timeline-content">
                                        <div class="card-body">
                                            <h6 style="color: #048b32; font-weight: 700;">Take Away</h6>
                                            <h6 style="font-weight: 500; padding-top: 0">{{ date('d M Y', strtotime($history_status['updated_at'])) }}</h6>
                                            <hr>
                                            <div class="card">
                                                <div class="card-body">
                                                  <h6 class="card-title" style="font-weight: 600; padding-bottom: 1em;">Pickup Detail</h6>
                                                  <div class="row">
                                                    @php
                                                  $arr_serviceoption = $services->ServiceOptionDelivery();
                                                  @endphp
                                                  <div class="col-lg-7 col-md-7 col-sm-7 border-right">
                                                    <h6 class="card-subtitle mb-2" style="font-weight: 500;">{{ $arr_serviceoption->recipient_name }}</h6>
                                                    <p class="card-text" style="font-size: 1em; ">{{ $arr_serviceoption->recipient_phone }} </p>
                                                    <p style="font-size: 1em; text-transform: capitalize;">Branch: {{ $services->getDetailSales($arr_serviceoption->branch_id, $arr_serviceoption->cso_id)->branch['code'] }} - {{ $services->getDetailSales($arr_serviceoption->branch_id, $arr_serviceoption->cso_id)->branch['name'] }}
                                                        <br> CSO Code: {{ $services->getDetailSales($arr_serviceoption->branch_id, $arr_serviceoption->cso_id)->cso['code'] }} - {{ $services->getDetailSales($arr_serviceoption->branch_id, $arr_serviceoption->cso_id)->cso['name'] }}
                                                    </p>

                                                  </div>
                                                  
                                                  <div class="col-lg-5 col-md-5 col-sm-5">
                                                    <h6 style="font-size: 1em; font-weight: 600;">Delivery Schedule</h6>
                                                    @if(isset($arr_serviceoption->appointment))
                                                    <p class="card-text">{{ date("d/m/Y", strtotime($arr_serviceoption->appointment)) }}<br>{{ date("H:i", strtotime($arr_serviceoption->appointment)) }}</p>
                                                    @endif
                                                  </div>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @elseif (strtolower($history_status['status']) == "completed")
                                    <div class="timeline-content">
                                        <div class="card-body">
                                            <h6 style="color: #048b32; font-weight: 700;">Completed</h6>
                                            <h6 style="font-weight: 500; padding-top: 0">{{ date('d M Y', strtotime($history_status['updated_at'])) }}</h6>
                                            <hr>
                                            <div class="card">
                                                
                                                <div class="card-body">
                                                    <h6 class="text-center" style="font-weight: 600;">Biaya Service</h6>
                                                    @foreach($services->product_services as $key => $product_service)
                                                    <h6 class="card-title" style="font-weight: 600;">Product {{$key + 1}}</h6>
                                                    <table class="table table-borderless table-responsive">
                                                        @php
                                                        $arr_sparepart = json_decode($product_service['sparepart']);
                                                        $count_sparepart = count($arr_sparepart);
                                                        @endphp
                                                        <tr>
                                                            @foreach($arr_sparepart as $index => $item)
                                                            @php
                                                                $unit_price = $product_service->getSparepart($item->id)->id['price'];
                                                                $total_price = $item->qty * $unit_price;
                                                            @endphp
                                                            <td class="td-product-name">
                                                                {{$product_service->getSparepart($item->id)->id['name']}}
                                                            </td>
                                                            <td>
                                                                {{$item->qty}}x
                                                            </td>
                                                            <td>{{number_format($unit_price)}}</td>
                                                            <td>{{number_format($total_price)}}</td>
                                                            @php break; @endphp
                                                            @endforeach
                                                        </tr>
                                                        @php $first = true; @endphp
                                                            @for($i = 0; $i < $count_sparepart; $i++)
                                                            @php
                                                                if($first){
                                                                    $first = false;
                                                                    continue;
                                                                }
                                                                $unit_price = $product_service->getSparepart($arr_sparepart[$i]->id)->id['price'];
                                                                $total_price = $item->qty * $unit_price;
                                                            @endphp
                                                            <tr>
                                                                <td class="td-product-name">{{$product_service->getSparepart($arr_sparepart[$i]->id)->id['name']}}</td>
                                                                <td>{{$arr_sparepart[$i]->qty}}x</td>
                                                                <td>{{number_format($unit_price)}}</td>
                                                                <td>{{number_format($total_price)}}</td>
                                                            </tr>
                                                        @endfor
                                                    </table>
                                                    @endforeach
                                                    <h6 style="font-weight: 600;">Total</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @endif
                                </div>
                            </div>
                    @endforeach
                    <div class="timeline wow bounceInUp clearfix">
                        <div class="timeline-item-last">
                            <div class="timeline-icon-last">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        </div>
    </div>

</section>

<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<!--accordion -->
<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    }
  });
}
</script>
<!--accordion -->
@endsection
