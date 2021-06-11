<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	  <title>WAKi Indonesia</title>
	  <meta content="width=device-width, initial-scale=1.0" name="viewport">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="waki, waki indonesia, waki indo, waki-indo, waki-indonesia, waki asia, waki ecommerce, waki shop" />
		<meta name="description" content="Waki Indonesia WAKi International Group, Bukan Janji tapi Pasti!">


	  <!--justicon-->
	  <link rel="apple-touch-icon" sizes="57x57" href="{{asset('sources/icon/apple-icon-57x57.png')}}">
	  <link rel="apple-touch-icon" sizes="60x60" href="{{asset('sources/icon/apple-icon-60x60.png')}}">
	  <link rel="apple-touch-icon" sizes="72x72" href="{{asset('sources/icon/apple-icon-72x72.png')}}">
	  <link rel="apple-touch-icon" sizes="76x76" href="{{asset('sources/icon/apple-icon-76x76.png')}}">
	  <link rel="apple-touch-icon" sizes="114x114" href="{{asset('sources/icon/apple-icon-114x114.png')}}">
	  <link rel="apple-touch-icon" sizes="120x120" href="{{asset('sources/icon/apple-icon-120x120.png')}}">
	  <link rel="apple-touch-icon" sizes="144x144" href="{{asset('sources/icon/apple-icon-144x144.png')}}">
	  <link rel="apple-touch-icon" sizes="152x152" href="{{asset('sources/icon/apple-icon-152x152.png')}}">
	  <link rel="apple-touch-icon" sizes="180x180" href="{{asset('sources/icon/apple-icon-180x180.png')}}">
	  <link rel="icon" type="image/png" sizes="192x192"  href="{{asset('sources/icon/android-icon-192x192.png')}}">
	  <link rel="icon" type="image/png" sizes="32x32" href="{{asset('sources/icon/favicon-32x32.png')}}">
	  <link rel="icon" type="image/png" sizes="96x96" href="{{asset('sources/icon/favicon-96x96.png')}}">
	  <link rel="icon" type="image/png" sizes="16x16" href="{{asset('sources/icon/favicon-16x16.png')}}">
	  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
	  <link rel="manifest" href="{{asset('sources/icon/manifest.json')}}">
	  <meta name="msapplication-TileColor" content="#ffffff">
	  <meta name="msapplication-TileImage" content="{{asset('sources/icon/ms-icon-144x144.png')}}">
	  <meta name="theme-color" content="#ffffff">
	  <!--//justicon-->

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-T3DP915XJK"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'G-T3DP915XJK');
		</script>

		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-N3ZNCDJ');</script>
		<!-- End Google Tag Manager -->

	  <!-- Google Fonts -->
	  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,500,600,700,700i|Montserrat:300,400,500,600,700" rel="stylesheet">
	  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

	  <!-- Bootstrap CSS File -->
	  <link href="{{asset('css/lib/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

	  <!-- Libraries CSS Files -->
	  <link href="{{asset('css/lib/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
	  <link href="{{asset('css/lib/animate/animate.min.css')}}" rel="stylesheet">
	  <link href="{{asset('css/lib/ionicons/css/ionicons.min.css')}}" rel="stylesheet">
	  {{-- <link href="{{asset('css/lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet"> --}}
	  <link href="{{asset('css/lib/lightbox/css/lightbox.min.css')}}" rel="stylesheet">
	  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">

	  <!-- Main Stylesheet File -->
	  <link href="{{asset('css/style.css')}}" rel="stylesheet">
</head>
<body>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N3ZNCDJ"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<!--==========================Header============================-->
	<header id="header" class="fixed-top header-transparent">
	  <div class="container d-flex">

			<div class="d-flex align-items-center justify-content-start" style="flex: 1;">
				<div class="logo justify-content-center align-content-center">
	        <a href="#header" class="scrollto"><img src="{{asset('sources/wakishoplogo.png')}}" alt="" class="img-fluid"></a>
	      </div>
			</div>

			<div class="d-flex align-items-center justify-content-end" style="flex: 1;">
	      <nav class="main-nav d-none d-lg-block scrollto align-items-start justify-content-start">
	        <ul>

	          <li><a href="#about">About</a></li>
	          <li><a href="#product">Categories</a></li>
	          <li><a href="#portfolio">Gallery</a></li>
	          <li><a href="#footer">Location</a></li>
	          <li><a href="#footer">Contact</a></li>

	        </ul>
	      </nav><!-- .main-nav -->
			</div>

	    </div>
	</header>
	@yield('content')

	<!--==========================Footer============================-->
	<section id="footer">
  	<footer id="footer" class="section-bg">
      	<div class="footer-top">
        	<div class="container">
          		<div class="row">
            		<div class="col-lg-4 col-sm-12">
              			<div class="row">
                 			<div class="col-sm-12">
			                    <div>
														<div class="logo logo-footer justify-content-center align-content-center">
															<a href="#header" class="scrollto"><img src="http://localhost/waki-indonesia/public/sources/waki3dprinting.png" alt="" class="img-fluid"></a>
														</div>

			                      	<p>WAKi SHOP since 2021.<br>
															We provide product for your daily needs and other needs with total of the 8000 products variation.</p>
			                    </div>

			                    <div>
			                    	<ul class="fa-ul">
															<li class="footer-menu-list"><i class="fa-li fa fa-envelope"></i>wakishopmalaysia@gmail.com
															</li>
															<li class="footer-menu-list"><i class="fa-li fa fa-phone"></i>+60 16-688 8903
															</li>
															<li class="footer-menu-list"><i class="fa-li fas fa-globe"></i>www.wakishop.com
															</li>
														</ul>
			                    </div>


                  			</div>
              			</div>
              		</div>

								<div class="col-lg-3 d-none d-lg-block">

             			<div class="col-md-12">
                    <div class="footer-newsletter">
	                  		<h4>Menu</h4>
												<ul class="footer-menu">
													<li class="footer-menu-list">About</li>
													<li class="footer-menu-list">Categories</li>
													<li class="footer-menu-list">Gallery</li>
													<li class="footer-menu-list">Location</li>
												</ul>
                    </div>
										<div class="social-links text-center" style="padding-top: 4em;">
												<a href="https://web.facebook.com/wakimart.id" class="facebook"><i class="fa fa-facebook"></i></a>
												<a href="https://www.instagram.com/wakimart.id/" class="instagram"><i class="fa fa-phone"></i></a>
												<a href="https://www.youtube.com/channel/UCI2G97LQi4lHZ0yQtug1Wqw" class="youtube"><i class="fa fa-youtube"></i></a>
										</div>
              		</div>
              	</div>

		            <div class="col-lg-5">
		              	<div class="form">

		              		<h4>Send us a message</h4>
			                <form action="" method="post" role="form" class="contactForm">
			                  	<div class="form-group">
			                    	<input type="text" name="name" class="form-control" id="name" placeholder="Your Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
			                    	<div class="validation"></div>
			                  	</div>
			                  	<div class="form-group">
			                    	<input type="email" class="form-control" name="email" id="email" placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email" />
			                    	<div class="validation"></div>
			                  	</div>
			                  	<div class="form-group">
			                    	<input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" />
			                    	<div class="validation"></div>
			                  	</div>
			                  	<div class="form-group">
			                    	<textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="Please write something for us" placeholder="Message"></textarea>
			                    	<div class="validation"></div>
			                  	</div>

			                  	<div id="sendmessage">Your message has been sent. Thank you!</div>
			                  	<div id="errormessage"></div>

			                  	<div class="text-center"><button type="submit" title="Send Message">Send
														<span class="span-padding"></span><i class="fas fa-location-arrow"></i></button></div>
			                </form>
		              </div>
		            </div>
          		</div>
        	</div>
      	</div>

	    <div class="container">
	      <div class="copyright">
	        &copy; Copyright <strong>Waki SHOP</strong>. All Rights Reserved
	      </div>
	      <div class="credits">
	        <a href="https://waki.asia/">WAKi International Group</a>
	      </div>
	    </div>
  	</footer>
	</section>
  	<!-- #footer -->

  	<a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
  	<!-- Uncomment below i you want to use a preloader -->
  	<!-- <div id="preloader"></div> -->

  	<!-- JavaScript Libraries -->
  	<script src="{{asset('css/lib/jquery/jquery.min.js')}}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
	{{-- <script src="{{asset('css/lib/owlcarousel/owl.carousel.min.js')}}"></script> --}}
  	<script src="{{asset('css/lib/jquery/jquery-migrate.min.js')}}"></script>
  	<script src="{{asset('css/lib/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  	<script src="{{asset('css/lib/easing/easing.min.js')}}"></script>
  	<script src="{{asset('css/lib/mobile-nav/mobile-nav.js')}}"></script>
  	<script src="{{asset('css/lib/wow/wow.min.js')}}"></script>
  	<script src="{{asset('css/lib/waypoints/waypoints.min.js')}}"></script>
  	<script src="{{asset('css/lib/counterup/counterup.min.js')}}"></script>
  	<script src="{{asset('css/lib/isotope/isotope.pkgd.min.js')}}"></script>
  	<script src="{{asset('css/lib/lightbox/js/lightbox.min.js')}}"></script>
  	<!-- Contact Form JavaScript File -->
  	<script src="contactform/contactform.js"></script>

  	<!-- Template Main Javascript File -->
  	<script src="{{asset('js/main.js')}}"></script>
		<script>
		$(document).ready(function() {
			$("#product-slider").owlCarousel({
		    items: 3,
		    pagination: true,
		    autoplay: true,
		    loop: true,
		    nav: true,
		    dots: false,
				navText:["<div class='nav-btn prev-slide'></div>","<div class='nav-btn next-slide'></div>"],
	   		responsive:{
		      1000: {items: 3},
		      768: {items: 3},
		      640: {items: 2},
		      370: {items: 1}
		    }
		  });
		});
		</script>
</body>
</html>
