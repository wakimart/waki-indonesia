@extends('layouts.template')

@section('content')
<!--==========================Intro Section============================-->

<section id="hero" class="clearfix">
		{{--<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
		  <div class="carousel-inner">
		    <div class="carousel-item active">
		      <img class="d-block w-100" src="{{asset('sources/waki-carousel1.jpg')}}" alt="First slide">
		    </div>
		    <div class="carousel-item">
		      <img class="d-block w-100" src="{{asset('sources/waki-carousel2.jpg')}}" alt="Second slide">
		    </div>
		    <div class="carousel-item">
		      <img class="d-block w-100" src="{{asset('sources/waki-carousel3.jpg')}}" alt="Third slide">
		    </div>
		  </div>

		  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
		    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
		    <span class="sr-only">Previous</span>
		  </a>

		  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
		    <span class="carousel-control-next-icon" aria-hidden="true"></span>
		    <span class="sr-only">Next</span>
		  </a>
		</div>--}}
		<div class="container d-flex h-100">
	    <div class="row justify-content-center align-self-center" data-aos="fade-up">
	      <div class="col-md-6 hero-info order-md-first order-last" data-aos="zoom-in" data-aos-delay="100">
	        <h2>Selamat datang di WAKi International Group</h2>
	        <p>WAKi adalah perusahaan alat kesehatan yang mengunggulkan kualitas terjamin, untuk membantu keluarga menuju kehidupan yang lebih baik. </p>
	        <div>
	          <!-- <a href="#main" class="btn-get-started scrollto">Mulai</a> -->
	          <a href="#myModal" id="mobile" class="btn-mobile" data-toggle="modal" data-target="#video2" style="margin-left: 0.5em;">Lihat Video<span style="padding-left: 0.5em;"><i class="far fa-play-circle"></i></span></a>
	        </div>
	      </div>

	      <div class="col-md-6" style="flex: none;">
	        <div class="text-right" style="position: relative; top: 50%; transform: translateY(-50%);" data-toggle="modal" data-target="#video2">
	          <img src="{{asset('sources/play.png')}}" alt="" class="icon-play img-fluid">
	        </div>

	        {{-- <div class="overlay-inner overlay-video embed-responsive embed-responsive-4by3">
	          <video muted onclick="this.paused?this.play():this.pause();" loop style="min-height: 100%";>
	            <source src="{{asset('sources/Introduction of WAKi short.mp4')}}" type="video/mp4">
	          </video>
	        </div> --}}
	      </div>

	      <div id="video2" class="modal fade videoModal" role="dialog">
	        <div class="modal-dialog">
	          <div class="modal-content">

	            <div class="modal-header">
	              <button type="button" class="close" data-dismiss="modal">&times;</button>
	            </div>

	            <div class="modal-body">
	              <div class="overlay-inner overlay-video embed-responsive embed-responsive-4by3">
	                <video id="video-2" muted onclick="this.paused?this.play():this.pause();" loop style="min-height: 100%";>
	                  <source src="{{asset('sources/Introduction of WAKi short.mp4')}}" type="video/mp4">
	                </video>
	              </div>

	            </div>

	          </div>
	        </div>
	      </div>
	    </div>

	  </div>
</section>

<section id="product" class="clearfix">
	<div class="container">
	  	<div class="col-lg-12 col-md-12" style="padding:0;">
	    	<div class="intro-content">
	      	<br>
	      	<h2>{{ $product[0]->category['name'] }}</h2>
	    	</div>
	  	</div>

	  	<div class="col-lg-12 col-md-12" style="margin-top: 2em; padding:0;">
	    	<div class="row">
	      		<div class="col-lg-4 col-md-4 col-sm-12">
	        		<div class="product_categoriesmenu">
	          			<h5 class="prodcat-title">Product categories</h5>
	          			<ul class="product-categories">
							  @foreach ($categoryProducts as $categoryProduct )
						  <li class="cat-item cat-item-24 current-cat"><a href="{{route('product_category',['id' => $categoryProduct->id])}}" id=categoryId value={{$categoryProduct->id}} >{{$categoryProduct->name}}</a></li>
							  @endforeach

	          			</ul>
	        		</div>
	      		</div>

	      		<div class="col-lg-8 col-md-8 col-sm-12">
	        		<div class="row">
							@foreach ($product as $product )
							<div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp prodpd" data-wow-duration="1.4s" style="padding: 0 5px;">
								<div class="card white-bg">
									<div class="container product-img">
										@php
											$img = json_decode($product->image);
										  	$defaulting = asset('sources/product_images/').'/'.strtolower($product->code).'/'.$img[0];
										@endphp
										<img src="{{$defaulting}}" style="background: center top no-repeat; background-size: contain; width: 100%; padding-top: 1em;">
									</div>
									<div class="card-body" style="min-height: 10vw;">
										<div class="titleprd">
											<p class="titleprodcat">{{$product->code.' - '.$product->name}}</p>
										</div>
									</div>
									<div class="card-footer" style="background: initial;">
										<div class="buttonprd">
											<a href="{{route('single_product',['id' => $product->id])}}" class="button">Read More</a>
										</div>
									</div>
								</div>
							</div>
							@endforeach
	        		</div><!-- row -->
	      		</div>
	    	</div><!-- row -->
	  	</div>
	</div><!-- container -->
</section><!-- #intro -->
@endsection
@section('script')
<script>

</script>
@endsection
