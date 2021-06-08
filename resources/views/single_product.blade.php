@extends('layouts.template')

@section('content')


<!--==========================Hero Section============================-->
<section id="hero" class="clearfix">
  <div class="container h-100 d-flex">
    <div class="justify-content-center align-self-center" data-aos="fade-up">
      <div class="col-md-12 hero-info order-md-first order-last" data-aos="zoom-in" data-aos-delay="100">
        <h2>Print Your <br>Imagination With Us.</h2>
        <div>
          <a href="#main" class="btn-get-started scrollto">Get Started</a>
        </div>
      </div>
    </div>

  </div>
</section><!-- End Hero -->


<section id="intro" class="clearfix" >
	<div class="container">
  		<div class="col-lg-12 col-md-12" style="padding: 2em 0 0;">
		    <div class="intro-content">
		      <h2>3D Prototyping Service</h2>
		    </div>
  		</div>

  		<div class="col-lg-12 col-md-12" style="margin-top: 2em; padding:0;">
    		<div class="row">

      			<div class="col-lg-12 col-md-12 col-sm-12">
        			<div class="col-sm-12 col-md-12 col-lg-12">
          				<div class="row">
            				<div class="col-sm-12 col-md-6 col-lg-6 wow fadeInRight prodpd" data-wow-duration="1.4s">
              					<div class="image-zoom">
                					<img src="{{asset('sources/3dproto.png')}}" data-zoom="enabled" style="background: center top no-repeat; background-size: contain; width: 100%; padding:1em;">
              					</div>
            				</div>

            				<div class="col-sm-12 col-md-6 col-lg-6 wow fadeInRight prodpd" data-wow-duration="1.4s">
              					<div class="titleprd">
														<ul>
															<li>Fast fabrication of a physical part, model or assembly.</li>
															<li>Using 3D Computer Aided Design (CAD). The creation of the part, model or asembly is usually completed using 3D Printing.</li>
															<li>A process of repeating through a design by quickly manufacturing physical prototype and making small changes at each step to improve it.</li>
														<ul>

              					</div>
                        <div class="buttonorder">
                          <a href="single_product.html" class="button">Order Service</a>
                        </div>
            				</div>

        			</div>
      			</div>
    		</div><!-- row -->
  		</div>

  		<div class="col-lg-12 col-md-12" style="margin-top: 2em;padding:0;">
		    <div class="intro-content">
		      <br>
		      <h2>Other Services</h2>
		    </div>
    		<div class="col-lg-12 col-md-12">
    			<div class="row">
      				<div class="col-sm-4 col-md-4 col-lg-4 wow fadeInUp prodpd" data-wow-duration="1.4s">
				        <div class="boxprd">
				          <img src="{{asset('sources/3dproto300x.png')}}" style="background: center top no-repeat; background-size: contain; width: 100%;">
				        </div>
				        <div class="titleprd">
				          <p>3D Prototyping</p>
				        </div>
				        <div class="buttonprd">
				          <a href="single_product.html" class="button">Read More</a>
				        </div>
      				</div>
      			<div class="col-sm-4 col-md-4 col-lg-4 wow fadeInUp prodpd" data-wow-duration="1.4s">
			        <div class="boxprd">
			          <img src="{{asset('sources/3dprint300x.png')}}" style="background: center top no-repeat; background-size: contain; width: 100%;">
			        </div>
			        <div class="titleprd">
			          <p>3D Printing Service</p>
			        </div>
			        <div class="buttonprd">
			          <a href="#" class="button">Read More</a>
			        </div>
      			</div>
      			<div class="col-sm-4 col-md-4 col-lg-4 wow fadeInUp prodpd" data-wow-duration="1.4s">
			        <div class="boxprd">
			          <img src="{{asset('sources/3ddesain300x.png')}}" style="background: center top no-repeat; background-size: contain; width: 100%;">
			        </div>
			        <div class="titleprd">
			          <p>3D Desain and Modeling</p>
			        </div>
			        <div class="buttonprd">
			          <a href="single_product.html" class="button">Read More</a>
			        </div>
      			</div>
    		</div><!-- row -->
    	</div>
  	</div>
	</div><!-- container -->
</section>
@endsection
