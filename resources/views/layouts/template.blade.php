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

	  <!-- Bootstrap CSS File -->
	  <link href="{{asset('css/lib/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

	  <!-- Libraries CSS Files -->
	  <link href="{{asset('css/lib/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
	  <link href="{{asset('css/lib/animate/animate.min.css')}}" rel="stylesheet">
	  <link href="{{asset('css/lib/ionicons/css/ionicons.min.css')}}" rel="stylesheet">
	  <link href="{{asset('css/lib/owlcarousel/asset/owl.carousel.min.css')}}" rel="stylesheet">
	  <link href="{{asset('css/lib/lightbox/css/lightbox.min.css')}}" rel="stylesheet">

	  <!-- Main Stylesheet File -->
	  <link href="{{asset('css/style.css')}}" rel="stylesheet">
</head>
<body>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N3ZNCDJ"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<!--==========================Header============================-->
	<header id="header" style="background-color: #fff;">
	  <!-- <div id="topbar">
	    <div class="container">
	      <div class="social-links">
	        <a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
	        <a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
	        <a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a>
	        <a href="#" class="instagram"><i class="fa fa-instagram"></i></a>
	      </div>
	    </div>
	  </div> -->
	  <div class="container">

	      <div class="logo float-left">
	        <!-- Uncomment below if you prefer to use an image logo -->
	        <!-- <h1 class="text-light"><a href="#intro" class="scrollto"><span>WAKi</span></a></h1> -->
	        <a href="#header" class="scrollto"><img src="{{asset('sources/Logo Since.png')}}" alt="" class="img-fluid"></a>
	      </div>
	      <nav class="main-nav float-right d-none d-lg-block">
	        <ul>
	          @if(Utils::$lang=='id')
	          <li><a href="{{route('index')}}#intro">Beranda</a></li>
	          @elseif(Utils::$lang=='eng')
	          <li><a href="{{route('index')}}#intro">Home</a></li>
	          @endif
						@if(Utils::$lang=='id' || Utils::$lang=='eng')
	          <li class="drop-down"><a href="#product">Produk</a>
	            <ul>
					@foreach (App\CategoryProduct::all() as $categoryProduct)
					<li class="drop-down"><a href="{{route('product_category',['id'=> $categoryProduct->id])}}" value="{{$categoryProduct->id}}">{{$categoryProduct->name}}</a>
						@if( ! $categoryProduct->product->isEmpty())
							<ul>
								@foreach($categoryProduct->product as $product)
									<li><a href="{{route('single_product',['id'=>$product->id])}}">{{ $product->code }}</a></li>
								@endforeach
							</ul>
						@endif
					  </li>
					@endforeach
	            </ul>
	          </li>
						@endif

	          @if(Utils::$lang=='id')
	          <li><a href="{{route('index')}}#about">Tentang</a></li>
	          @elseif(Utils::$lang=='eng')
	          <li><a href="{{route('index')}}#about">About Us</a></li>
	          @endif

	          @if(Utils::$lang=='id')
	          <li><a href="{{route('index')}}#portfolio">Galeri</a></li>
	          @elseif(Utils::$lang=='eng')
	          <li><a href="{{route('index')}}#portfolio">Gallery</a></li>
	          @endif


						{{-- @if(Utils::$lang=='id')
						<li class="drop-down {{isset($menu_item_page) && $menu_item_page == 'form'? 'active': '' }}"><a href="#product">Form</a>
	            <ul>
	              <li class="{{isset($menu_item_second) && $menu_item_second == 'formregistrasi'? 'active': '' }}"><a href="{{ route('delivery_order') }}">REGISTRASI</a></li>
								<li class="{{isset($menu_item_second) && $menu_item_second == 'formorder'? 'active': '' }}"><a href="{{ route('add_order') }}">ORDER</a></li>
								<li class="{{isset($menu_item_second) && $menu_item_second == 'formhomeservice'? 'active': '' }}"><a href="{{ route('add_homeServices') }}">HOME SERVICE</a></li>
							</ul>
						</li>
						@endif --}}

						@if(Utils::$lang=='id')
	          <li><a href="{{route('index')}}#team">World Peace</a></li>
	          @elseif(Utils::$lang=='eng')
	          <li><a href="{{route('index')}}#team">World Peace</a></li>
	          @endif

	          @if(Utils::$lang=='id')
	          <li><a href="{{route('index')}}#footer">Kontak</a></li>
	          @elseif(Utils::$lang=='eng')
	          <li><a href="{{route('index')}}#footer">Contact Us</a></li>
	          @endif

	          <li><a href="{{route('login')}}">Login</a></li>

	          <li><a href="#" class="searchtxtmob"> </a>
	            <div class="searchicon">
	                <i id="searchicn" class="fa fa-search hidden-sm"></i>
	              </div>
	              <div class="searchbox" style="display: none;" >
	                <form role="search" method="get" class="searchform" action="">
	                    <input type="search" class="search-field" placeholder="Search …" value="" name="s">
	                  <button id="searchbtn" type="submit" class="search-submit btn search-btn"><i class="fa fa-search"></i></button>
	                </form>
	              </div>
	              <div class="searchboxmob" style="display: none;" >
	                <form role="search" method="get" class="searchform" action="">
	                    <input type="search" class="search-field" placeholder="Search …" value="" name="s">
	                  <button id="searchbtn" type="submit" class="search-submit btn search-btn"><i class="fa fa-search"></i></button>
	                </form>
	              </div>
	          </li>
	        </ul>
	      </nav><!-- .main-nav -->

	    </div>
	</header>
	@yield('content')

	<!--==========================Footer============================-->
  	<footer id="footer" class="section-bg">
      	<div class="footer-top">
        	<div class="container">
          		<div class="row">
            		<div class="col-lg-6">
              			<div class="row">
                 			<div class="col-sm-12">
			                    <div class="footer-newsletter">
				                  	@if(Utils::$lang=='id')
				                  		<h4>Kantor Pusat WAKI</h4>
				                  	@elseif(Utils::$lang=='eng')
				                  		<h4>WAKi Headquarter</h4>
				                  	@endif


			                      	<p style="margin-bottom: 5px;"><strong>WAKi International Group</strong></p>
			                      	<p>WAKi Tower,
			                      	S-01-01, Block C, USJ ONE,
			                      	Persiaran Subang Permai, USJ 1
			                      	47500 Subang Jaya, Selangor,
			                      	Malaysia.</p>
			                    </div>

			                    <div class="footer-links">
			                    	@if(Utils::$lang=='id')
				                  		<h4>Kontak Kami</h4>
				                      	<p>
				                        	<strong>Telepon:</strong>
				                        	<br>Malaysia : +60 10 239 3899
				                        	<br>Indonesia : +62 811 8683 899
				                        	<br>Philippines: +63 998 988 8899
				                        	<br>Cambodia: +855 11 762 719
				                        	<br>Myanmar: +95 9 79653 2299
				                        	<br>Vietnam: +84 90 143 31 99
				                        	<br>Thailand: +66 6 1965 9646
				                      	</p>
				                  	@elseif(Utils::$lang=='eng')
				                  		<h4>Contact Us</h4>
				                      	<p>
				                        	<strong>Phone:</strong>
				                        	<br>Malaysia : +60 10 239 3899
				                        	<br>Indonesia : +62 811 8683 899
				                        	<br>Philippines: +63 998 988 8899
				                        	<br>Cambodia: +855 11 762 719
				                        	<br>Myanmar: +95 9 79653 2299
				                        	<br>Vietnam: +84 90 143 31 99
				                        	<br>Thailand: +66 6 1965 9646
				                      	</p>

				                      	<a href="{{ route('term_cond') }}"><p><strong>Term and Condition</strong></p></a>
				                  	@endif
			                    </div>

			                    <div class="social-links">
			                    	@if(Utils::$lang=='id')
				                  		<h4>Ikuti Kami</h4>
				                  	@elseif(Utils::$lang=='eng')
				                  		<h4>Follow Us</h4>
				                  	@endif

			                      	<a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
			                      	<a href="#" class="instagram"><i class="fa fa-instagram"></i></a>
			                      	<a href="#" class="youtube"><i class="fa fa-youtube"></i></a>
			                    </div>
                  			</div>
              			</div>
              		</div>

		            <div class="col-lg-6">
		              	<div class="form">
		              	@if(Utils::$lang=='id')
		              		<h4>Kirim Pesan</h4>
			                <form action="" method="post" role="form" class="contactForm">
			                  	<div class="form-group">
			                    	<input type="text" name="name" class="form-control" id="name" placeholder="Nama Lengkap" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
			                    	<div class="validation"></div>
			                  	</div>
			                  	<div class="form-group">
			                    	<input type="email" class="form-control" name="email" id="email" placeholder="Email" data-rule="email" data-msg="Please enter a valid email" />
			                    	<div class="validation"></div>
			                  	</div>
			                  	<div class="form-group">
			                    	<input type="text" class="form-control" name="subject" id="subject" placeholder="Subyek" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" />
			                    	<div class="validation"></div>
			                  	</div>
			                  	<div class="form-group">
			                    	<textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="Please write something for us" placeholder="Pesan"></textarea>
			                    	<div class="validation"></div>
			                  	</div>

			                  	<div id="sendmessage">Pesan Anda telah terkirim. Thank you!</div>
			                  	<div id="errormessage"></div>

			                  	<div class="text-center"><button type="submit" title="Send Message">Kirim Pesan</button></div>
			                </form>
		              	@elseif(Utils::$lang=='eng')
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

			                  	<div class="text-center"><button type="submit" title="Send Message">Send Message</button></div>
			                </form>
		              	@endif
		              </div>
		            </div>
          		</div>
        	</div>
      	</div>

	    <div class="container">
	      <div class="copyright">
	        &copy; Copyright <strong>WAKi Indonesia</strong>. All Rights Reserved
	      </div>
	      <div class="credits">
	        <a href="https://waki.asia/">WAKi International Group</a>
	      </div>
	    </div>
  	</footer>
  	<!-- #footer -->

  	<a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
  	<!-- Uncomment below i you want to use a preloader -->
  	<!-- <div id="preloader"></div> -->

  	<!-- JavaScript Libraries -->
  	<script src="{{asset('css/lib/jquery/jquery.min.js')}}"></script>
  	<script src="{{asset('css/lib/jquery/jquery-migrate.min.js')}}"></script>
  	<script src="{{asset('css/lib/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  	<script src="{{asset('css/lib/easing/easing.min.js')}}"></script>
  	<script src="{{asset('css/lib/mobile-nav/mobile-nav.js')}}"></script>
  	<script src="{{asset('css/lib/wow/wow.min.js')}}"></script>
  	<script src="{{asset('css/lib/waypoints/waypoints.min.js')}}"></script>
  	<script src="{{asset('css/lib/counterup/counterup.min.js')}}"></script>
  	<script src="{{asset('css/lib/owlcarousel/owl.carousel.min.js')}}"></script>
  	<script src="{{asset('css/lib/isotope/isotope.pkgd.min.js')}}"></script>
  	<script src="{{asset('css/lib/lightbox/js/lightbox.min.js')}}"></script>
  	<!-- Contact Form JavaScript File -->
  	<script src="contactform/contactform.js"></script>

  	<!-- Template Main Javascript File -->
  	<script src="{{asset('js/main.js')}}"></script>
</body>
</html>
