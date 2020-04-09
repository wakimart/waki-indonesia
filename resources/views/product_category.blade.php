@extends('layouts.template')

@section('content')
<!--==========================Intro Section============================-->
<section id="intro" class="clearfix">
	<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
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
	</div>

	<div class="container">
	  	<div class="col-lg-12 col-md-12" style="padding:0;">
	    	<div class="intro-content">
	      	<br>
	      	<h2>WAKi High Potential Therapy Range</h2>
	    	</div>
	  	</div>

	  	<div class="col-lg-12 col-md-12" style="margin-top: 2em; padding:0;">
	    	<div class="row">
	      		<div class="col-lg-4 col-md-4 col-sm-12">
	        		<div class="product_categoriesmenu">
	          			<h5 class="prodcat-title">Product categories</h5>
	          			<ul class="product-categories">
		            		<li class="cat-item cat-item-24 current-cat"><a href="single_product.htmlx">WAKi High Potential Therapy Range</a></li>
				            <li class="cat-item cat-item-29"><a href="#">WAKi Air Humidifier Range</a></li>
				            <li class="cat-item cat-item-38"><a href="#">WAKi Bio Energy Range</a></li>
				            <li class="cat-item cat-item-44"><a href="#">WAKi Massager Range</a></li>
				            <li class="cat-item cat-item-52"><a href="#">WAKi Household Range</a></li>
				            <li class="cat-item cat-item-55"><a href="#">WAKi Others Range</a></li>
	          			</ul>
	        		</div>
	      		</div>

	      		<div class="col-lg-8 col-md-8 col-sm-12">
	        		<div class="row">
	          			<div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp prodpd" data-wow-duration="1.4s">
	            			<div class="boxprd">
	              				<img src="https://waki.asia/wp-content/uploads/2019/05/wkt2080-1-300x300.jpg" style="background: center top no-repeat; background-size: contain; width: 100%;">
	            			</div>
	            			<div class="titleprd">
	             				<p class="titleprodcat">WKT2080 – WAKi Multi Functional Electro Massager Equipment</p>
	            			</div>
	            			<div class="buttonprd">
	              				<a href="{{route('single_product')}}" class="button">Read More</a>
	            			</div>
	          			</div>

	          			<div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp prodpd" data-wow-duration="1.4s">
	            			<div class="boxprd">
	              			<img src="https://waki.asia/wp-content/uploads/2017/08/WAKi-High-Potential-Therapeutic-Equipment-wk2079-edit-300x300.jpg" style="background: center top no-repeat; background-size: contain; width: 100%;">
	            			</div>
	            			<div class="titleprd">
	              				<p class="titleprodcat">WK2079 – WAKi High Potential Therapeutic Equipment</p>
	            			</div>
	            			<div class="buttonprd">
	              				<a href="#" class="button">Read More</a>
	            			</div>
	          			</div>

	          			<div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp prodpd" data-wow-duration="1.4s">
				            <div class="boxprd">
				              <img src="https://waki.asia/wp-content/uploads/2017/07/wk2076i-300x300.jpg" style="background: center top no-repeat; background-size: contain; width: 100%;">
				            </div>
				            <div class="titleprd">
				              <p class="titleprodcat">WK2076i – WAKi Multi Functional Ultra Potential Therapy Healthcare Device</p>
				            </div>
				            <div class="buttonprd">
				              <a href="single_product.html" class="button">Read More</a>
				            </div>
	          			</div>

	          			<div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp prodpd" data-wow-duration="1.4s">
				            <div class="boxprd">
				              <img src="https://waki.asia/wp-content/uploads/2017/07/WAKi-Multi-Functional-High-Potential-Therapeutic-Equipment-wk2076h-300x300.jpg" style="background: center top no-repeat; background-size: contain; width: 100%;">
				            </div>
				            <div class="titleprd">
				              <p class="titleprodcat">WK2076H – Waki Multi Functional High Potential Therapeutic Equipment</p>
				            </div>
				            <div class="buttonprd">
				              <a href="#" class="button">Read More</a>
				            </div>
	          			</div>

	          			<div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp prodpd" data-wow-duration="1.4s">
				            <div class="boxprd">
				              <img src="https://waki.asia/wp-content/uploads/2019/05/wkt2080-1-300x300.jpg" style="background: center top no-repeat; background-size: contain; width: 100%;">
				            </div>
				            <div class="titleprd">
				              <p class="titleprodcat">WKT2080 – WAKi Multi Functional Electro Massager Equipment</p>
				            </div>
				            <div class="buttonprd">
				              <a href="#" class="button">Read More</a>
				            </div>
	          			</div>

	          			<div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp prodpd" data-wow-duration="1.4s">
				            <div class="boxprd">
				              <img src="https://waki.asia/wp-content/uploads/2017/08/WAKi-High-Potential-Therapeutic-Equipment-wk2079-edit-300x300.jpg" style="background: center top no-repeat; background-size: contain; width: 100%;">
				            </div>
				            <div class="titleprd">
				              <p class="titleprodcat">WK2079 – WAKi High Potential Therapeutic Equipment</p>
				            </div>
				            <div class="buttonprd">
				              <a href="#" class="button">Read More</a>
				            </div>
	          			</div>

	          			<div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp prodpd" data-wow-duration="1.4s">
				            <div class="boxprd">
				              <img src="https://waki.asia/wp-content/uploads/2017/07/wk2076i-300x300.jpg" style="background: center top no-repeat; background-size: contain; width: 100%;">
				            </div>
				            <div class="titleprd">
				              <p class="titleprodcat">WK2076i – WAKi Multi Functional Ultra Potential Therapy Healthcare Device</p>
				            </div>
				            <div class="buttonprd">
				              <a href="#" class="button">Read More</a>
				            </div>
	          			</div>

	          			<div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp prodpd" data-wow-duration="1.4s">
				            <div class="boxprd">
				              <img src="https://waki.asia/wp-content/uploads/2017/07/WAKi-Multi-Functional-High-Potential-Therapeutic-Equipment-wk2076h-300x300.jpg" style="background: center top no-repeat; background-size: contain; width: 100%;">
				            </div>
				            <div class="titleprd">
				              <p class="titleprodcat">WK2076H – Waki Multi Functional High Potential Therapeutic Equipment</p>
				            </div>
				            <div class="buttonprd">
				              <a href="#" class="button">Read More</a>
				            </div>
	          			</div>
	        		</div><!-- row -->
	      		</div>
	    	</div><!-- row -->
	  	</div>
	</div><!-- container -->
</section><!-- #intro -->
@endsection
