
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



<main id="main">
  <!--==========================About Us Section============================-->
  <section id="about" style="position: relative; z-index: -1;">
    <div class="container">
      <div class="col-lg-12 col-md-12" style="padding: 2em 0;">
        <div class="row">
          <div class="col-md-6">
            <h2>We Print Your Creation.</h2>
          </div>
          <div class="col-md-6">
            <p>WAKi 3D Printing Industries believe that 3D printing is the next huge thing in the upcomig years and following decades.</p>
          </div>
        </div>

        <div class="col-lg-12 col-md-12" data-aos="zoom-in" data-aos-delay="100" style="padding:0;">
          <div class="about-img wow fadeInLeft" style="visibility: visible; animation-name: fadeInLeft;margin-bottom: 3.5em;">
            <img src="{{asset('sources/coverimage.png')}}" alt="">
          </div>
        </div>

        <div class="col-md-12" style="padding:0;">
          <h2>We Choose the Best Option.</h2>
        </div>

        <div class="row">

          <div class="col-md-3">
            <div class="about-img wow fadeInLeft">
              <img src="{{asset('sources/about1.png')}}" alt="">
            </div>
            <div>
              <p>High quality detail model output.</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="about-img wow fadeInLeft">
              <img src="{{asset('sources/about2.png')}}" alt="">
            </div>
            <div>
              <p>High performance 3D printer equipment.</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="about-img wow fadeInLeft">
              <img src="{{asset('sources/about3.png')}}" alt="">
            </div>
            <div>
              <p>High quality filament selected.</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="about-img wow fadeInLeft">
              <img src="{{asset('sources/about4.png')}}" alt="">
            </div>
            <div>
              <p>Detailing and finishing the model.</p>
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

        <header class="section-header">
          <h2>Our Service</h2>
        </header>
      <div class="row justify-content-center">
        <div class="row" style="padding:0; margin: 2em 0">

          <div class="col-lg-6 col-md-6">
            <div class="about-img wow fadeInLeft img-responsive" style="visibility: visible; animation-name: fadeInLeft;">
              <img src="{{asset('sources/ourservice.png')}}" alt="">
            </div>
          </div>

          <div class="col-lg-6 col-md-6 d-flex">
            <div class="about-content align-self-center">
              <div class="product-service">
                <h3>3D Prototyping</h3>
                <p>We can reverse engineering to improve or remodel the 3D model from the existed product to your desire.</p>
              </div>
              <div class="product-service">
                <h3>3D Printing Service</h3>
                <p>We can print your 3d model like souvenir, speciial gift, statue, car accessories, and many more with selected filament.</p>
              </div>
              <div class="product-service">
                <h3>3D Design & Modeling</h3>
                <p>We can specialized 3d model for car part like exterior body kit, plastic mechanism part and interior car parts that based on plastic material.</p>
              </div>
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

    <header class="section-header">
      <h2 class="section-title">Recent Project</h2>
    </header>


    <div class="grid-container">
      <div class="long-left">
        <div class="pic">
          <img src="{{asset('sources/portfolio/longleft.png')}}" alt="">
        </div>
      </div>
      <div class="wide-right">
        <div class="pic">
          <img src="{{asset('sources/portfolio/wideright.png')}}" alt="">
        </div>
      </div>
      <div class="long-right">
        <div class="pic">
          <img src="{{asset('sources/portfolio/longright.png')}}" alt="">
        </div>
      </div>
      <div class="normal-bottom">
        <div class="pic">
          <img src="{{asset('sources/portfolio/notmalbottom.png')}}" alt="">
        </div>
      </div>
      <div class="big-left">
        <div class="pic">
          <img src="{{asset('sources/portfolio/bigleft.png')}}" alt="">
        </div>
      </div>
      <div class="center">
        <div class="pic">
          <img src="{{asset('sources/portfolio/center.png')}}" alt="">
        </div>
      </div>
    </div>
  </div>
</section>


<!-- Testimoni #1
============================================= -->
<section id="testimonial" class="section portfolio portfolio-grid portfolio-2 pb-70">
    <div class="container">
      <header class="section-header" style="text-align:center;">
        <h2>Customer Review</h2>
      </header>

        <div class="row">
            <div id="testimonial-slider" class="owl-carousel">
              <div class="testimonial">
                <div class="description">
                  <h4 class="titledesc">Detailed Model</h4>
                  <p>Excellent job for detailing and finishing my 3d model sculpture, smooth surface like i want.
                  </p>
                </div>
                <div class="pic">
                  <img src="{{asset('sources/testface1.jpg')}}" alt="">
                </div>
                <div class="testimonial-content">
                  <h3 class="name">Bapak Suprapto</h3>
                </div>
              </div>
              <div class="testimonial">
                <div class="description">
                  <h4 class="titledesc">Detailed Model</h4>
                  <p>Excellent job for detailing and finishing my 3d model sculpture, smooth surface like i want.
                  </p>
                </div>
                <div class="pic">
                  <img src="{{asset('sources/testface1.jpg')}}" alt="">
                </div>
                <div class="testimonial-content">
                  <h3 class="name">Ibu Meiske</h3>
                </div>
              </div>
              <div class="testimonial">
                <div class="description">
                  <h4 class="titledesc">Detailed Model</h4>
                  <p>Excellent job for detailing and finishing my 3d model sculpture, smooth surface like i want.
                  </p>
                </div>
                <div class="pic">
                  <img src="{{asset('sources/testface1.jpg')}}" alt="">
                </div>
                <div class="testimonial-content">
                  <h3 class="name">Bapak Moh. Lontara</h3>
                </div>
              </div>
              <div class="testimonial">
                <div class="description">
                  <h4 class="titledesc">Detailed Model</h4>
                  <p>Excellent job for detailing and finishing my 3d model sculpture, smooth surface like i want.
                  </p>
                </div>
                <div class="pic">
                  <img src="{{asset('sources/testface1.jpg')}}" alt="">
                </div>
                <div class="testimonial-content">
                  <h3 class="name">Ibu Wiwik Sulastri</h3>
                </div>
              </div>
              <div class="testimonial">
                <div class="description">
                  <h4 class="titledesc">Detailed Model</h4>
                  <p>Excellent job for detailing and finishing my 3d model sculpture, smooth surface like i want.
                  </p>
                </div>
                <div class="pic">
                  <img src="{{asset('sources/testface1.jpg')}}" alt="">
                </div>
                <div class="testimonial-content">
                  <h3 class="name">Bapak Samsul Arifin</h3>
                </div>
              </div>
              <div class="testimonial">
                <div class="description">
                  <h4 class="titledesc">Detailed Model</h4>
                  <p>Excellent job for detailing and finishing my 3d model sculpture, smooth surface like i want.
                  </p>
                </div>
                <div class="pic">
                  <img src="{{asset('sources/testface1.jpg')}}" alt="">
                </div>
                <div class="testimonial-content">
                  <h3 class="name">Ibu Imas Dedeh</h3>
                </div>
              </div>
            </div>
        </div>


        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>
<!-- #Testimoni end -->


  <!--==========================Our Business Section============================-->
  <section id="business" class="business">
    <div class="container" data-aos="zoom-in">

      <header class="section-header text-center pb-3">
        <h2>Our Business</h2>
      </header>

      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="row card-group align-items-center">
            <div class="col-lg-3 col-md-3 col-sm-3">
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
            <div class="col-lg-3 col-md-3 col-sm-3">
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
            <div class="col-lg-3 col-md-3 col-sm-3">
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
            <div class="col-lg-3 col-md-3 col-sm-3">
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
  </section>
  <!-- #business -->
</main>
@endsection
