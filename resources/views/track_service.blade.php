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

    .timeline .timeline-item:last-of-type {
			background: transparent;

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
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        padding: 0 0 0 1rem;
    }

    .accordion {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  border-radius: 5px;
  text-align: left;
  outline: none;
  font-size: 15px;
  transition: 0.4s;
}

.active, .accordion:hover {
  background-color: #ccc;
}

.accordion:after {
  content: '\02C5';
  color: #444;
  font-weight: bold;
  float: right;
  margin-left: 5px;
}



</style>

<section id="intro" class="clearfix">
    <div class="container">
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
                                                  <h6 class="card-title" style="font-weight: 600;">Delivery Detail</h6>
                                                  @foreach(json_decode($services['service_option'], true) as $service_option)
                                                  <h6 class="card-subtitle mb-2" style="font-weight: 500;">{{$service_option['recipient_name']}}</h6>
                                                  <p class="card-text">{{$service_option['address']}} </p>
                                                  @endforeach
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
                                            <p style="font-weight: 400;">Produk telah diambil</p>
                                        </div>
                                    </div>
                                    @elseif (strtolower($history_status['status']) == "completed")
                                    <div class="timeline-content">
                                        <div class="card-body">
                                            <h6 style="color: #048b32; font-weight: 700;">Completed</h6>
                                            <h6 style="font-weight: 500; padding-top: 0">{{ date('d M Y', strtotime($history_status['updated_at'])) }}</h6>
                                        </div>
                                    </div>

                                    @endif
                                </div>
                            </div>
                    @endforeach
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
