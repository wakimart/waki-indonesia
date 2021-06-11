
@extends('layouts.template')

@section('content')

<!--==========================Hero Section============================-->
<section id="hero" class="clearfix">
  <div class="container d-flex">
    <div class="justify-content-center align-self-center herobg" data-aos="fade-up" style="margin: 0 15px;">
      <div class="row">
        <div class="col-md-7" style="padding:0;">
            <div class="about-img" style="visibility: visible; animation-name: fadeInLeft;">
              <img src="{{asset('sources/wakishophero.png')}}" alt="">
            </div>
          </div>
        <div class="col-md-5 order-last herodesc">
          <div class="p-4">
              <h2>Welcome to WAKi Shop.</h2>
              <p>Find and shop all your daily needs. Let’s shopping with us!</p>
              <div class="text-center">
                <a href="#main" class="btn-get-started scrollto">Get Started</a>
              </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</section><!-- End Hero -->

  <!--==========================About Us Section============================-->
  <section id="about" style="position: relative;">
    <div class="container">
      <div class="col-lg-12 col-md-12" style="padding: 1em 0;">
        <h4>ABOUT________</h4>
        <h2>WAKi Shop Overview</h2>
      </div>

        <div class="col-lg-12 col-md-12" data-aos="zoom-in" data-aos-delay="100" style="padding:0;">
          <div class="row">
            <div class="col-md-6">
              <div class="about-img wow fadeInLeft img-responsive" style="visibility: visible; animation-name: fadeInLeft;">
                <img src="{{asset('sources/overview.png')}}" alt="">
              </div>
            </div>
            <div class="col-md-6 align-self-center">
              <p>WAKi has become a popular brand for health product expanded internationally venturing into Asia Pacific and Europe countries. Expanding the business, WAKi Malaysia ventures into shop business.
              <br><br>
              Waki SHOP have the complete product for your daily needs and other needs with total of the 8000 product variation.
              The mission of WAKishop is to provide and serve customer with “High Value, Worth Having” product and service.
              </p>
            </div>
          </div>
        </div>

        </div>
      </div>

    </div>
  </section><!-- #about -->

  <!--==========================Product Section============================-->
  <section id="product">
    <div class="container">

      <div class="col-lg-12 col-md-12" style="padding: 1em 0;">
        <h4>CATEGORIES________</h4>
        <h2>Variety of Choices</h2>
      </div>

      <div class="row" style="padding:0 40px;">
          <div id="product-slider" class="owl-carousel">
            <div class="product">
              <div class="pic">
                <img src="{{asset('sources/testcat1.png')}}" alt="">
              </div>
              <div class="description">
                <h4 class="titledesc">Foods____</h4>
              </div>
            </div>
            <div class="product">
              <div class="pic">
                <img src="{{asset('sources/testcat2.png')}}" alt="">
              </div>
              <div class="description">
                <h4 class="titledesc">Kitchenatte____</h4>
              </div>
            </div>
            <div class="product">
              <div class="pic">
                <img src="{{asset('sources/testcat3.png')}}" alt="">
              </div>
              <div class="description">
                <h4 class="titledesc">Stylish____</h4>
              </div>
            </div>
            <div class="product">
              <div class="pic">
                <img src="{{asset('sources/testcat1.png')}}" alt="">
              </div>
              <div class="description">
                <h4 class="titledesc">Foods____</h4>
              </div>
            </div>
            <div class="product">
              <div class="pic">
                <img src="{{asset('sources/testcat2.png')}}" alt="">
              </div>
              <div class="description">
                <h4 class="titledesc">Kitchenatte____</h4>
              </div>
            </div>
            <div class="product">
              <div class="pic">
                <img src="{{asset('sources/testcat3.png')}}" alt="">
              </div>
              <div class="description">
                <h4 class="titledesc">Stylish____</h4>
              </div>

            </div>
          </div>
      </div>

    </div>
  </section>
<!-- #product -->


<!--==========================Portfolio Section============================-->
<section id="portfolio">
  <div class="container">

    <div class="col-lg-12 col-md-12" style="padding: 1em 0;">
      <h4>GALLERY________</h4>
      <h2>Some Photos from Our Store</h2>
    </div>

    <div class="row portfolio-container">

        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/our1.png')}}" class="img-fluid" alt="">
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/our2.png')}}" class="img-fluid" alt="">
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/our3.png')}}" class="img-fluid" alt="">
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/our6.png')}}" class="img-fluid" alt="">
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/our4.png')}}" class="img-fluid" alt="">
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/our5.png')}}" class="img-fluid" alt="">
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/our7.png')}}" class="img-fluid" alt="">
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/our9.png')}}" class="img-fluid" alt="">
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/our8.png')}}" class="img-fluid" alt="">
          </div>
        </div>

      </div>


  </div>
</section>

<!--==========================Our Business Section============================-->
<section id="location" class="location">
  <div class="container" data-aos="zoom-in">

    <div class="col-lg-12 col-md-12" style="padding: 1em 0;">
      <h4>LOCATION________</h4>
      <h2>Our Shops Location</h2>
    </div>

    <div class="col-lg-12 col-md-12" style="padding:0;">
      <ul id="location-flters">
          <li data-filter=".filter-shop1" class="filter-active">WAKiShop Kajang</li>
          <li data-filter=".filter-shop2">WAKiShop Bangi</li>
      </ul>
    </div>

    <div class="row col-lg-12 col-md-12 justify-content-center align-self-center herobg" style="padding:0;margin: 0 15px;">
      <div class="col-md-3" style="background-color:#f1faf2;display:flex;align-items:center;font-size:0.9em;font-weight:600;">
        WAKI Shop, Kajang<br><br>
        D-G, 30, Jalan Prima Saujana<br>
        2/A, Taman Prima Saujana <br>
        Seksyen 2, 43000 <br>
        Kajang, Selangor<br><br>
        Opening Hours<br><br>
        Monday - Sunday<br>
        10:00 a.m - 10:00 p.m<br>
      </div>
      <div class="col-md-9 order-last" style="padding: 0;">
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDvgWknP6hbVFgnvWzAgI-aoLQ89IySh4I"></script>
        <div style="overflow:hidden;height:370px;width:100%;">
            <div id="gmap_canvas" style="height:370px;width:100%;"></div>
            <style>#gmap_canvas img{max-width:none!important;background:none!important}</style>
        </div>
        <script type="text/javascript">function init_map(){var myOptions = {zoom:16, center:new google.maps.LatLng( 3.0583106, 101.5978456), mapTypeId: google.maps.MapTypeId.ROADMAP}; map = new google.maps.Map(document.getElementById('gmap_canvas'), myOptions); marker = new google.maps.Marker({map: map, position: new google.maps.LatLng(  3.0583106, 101.5978456)}); infowindow = new google.maps.InfoWindow({content:'<strong> WAKiMart Malaysia</strong><br> WAKi Tower Block C, S01 01 USJ ONE, Persiaran Subang Permai. <br>'}); google.maps.event.addListener(marker, 'click', function(){infowindow.open(map, marker); }); infowindow.open(map, marker); }google.maps.event.addDomListener(window, 'load', init_map);</script>
      </div>
    </div>

  </div>
</section>

  <!--==========================Our Business Section============================-->
  <!-- <section id="business" class="business">
    <div class="container" data-aos="zoom-in">

      <header class="section-header text-center pb-3">
        <h2>Our Business</h2>
      </header>

      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="row card-group align-items-center">
            <div class="col-lg-3 col-md-3 col-sm-6">
              <div class="card-business mb-3">
                <div class="card-img-top">
                  <img src="{{ asset ('sources/wakimartlogo.png')}}" class="img-fluid" style="padding-top: 30px;" />
                </div>
                <div class="card-body">

                  <div class="row justify-content-center align-self-center">
                    <div>
                      <a href="https://wakimart.co.id/" class="r-link link text-underlined" target="_blank" rel="noopener noreferrer">See More</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
              <div class="card-business mb-3">
                <div class="card-img-top">
                  <img src="{{ asset ('sources/wakishop.png')}}" class="img-fluid" />
                </div>
                <div class="card-body">

                  <div class="row justify-content-center align-self-center">
                    <div>
                      <a href="https://www.facebook.com/WAKiShopMalaysia/" class="r-link link text-underlined"  target="_blank" rel="noopener noreferrer">See More</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
              <div class="card-business mb-3">
                <div class="card-img-top">
                  <img src="{{ asset ('sources/wakifnb.png')}}" class="img-fluid" style="padding-top: 15px;"/>
                </div>
                <div class="card-body">

                  <div class="row justify-content-center align-self-center">
                    <div>
                      <a href="https://www.facebook.com/wakimalaysiandimsum/" class="r-link link text-underlined" target="_blank" rel="noopener noreferrer">See More</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6">
              <div class="card-business mb-3">
                <div class="card-img-top">
                  <img src="{{ asset ('sources/waki3dprinting.png')}}" class="img-fluid" />
                </div>
                <div class="card-body">

                  <div class="row justify-content-center align-self-center">
                    <div>
                      <a href="https://www.facebook.com/waki3Dprintingindustries/" class="r-link link text-underlined"  target="_blank" rel="noopener noreferrer">See More</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

        </div>
      </div>

    </div>
  </section> -->
  <!-- #business -->
@endsection
