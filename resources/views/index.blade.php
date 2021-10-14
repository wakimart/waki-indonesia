
@extends('layouts.template')

@section('content')
<!--==========================Hero Section============================-->
<section id="hero" class="clearfix">
    <div class="container d-flex h-100">
        <div class="row justify-content-center align-self-center"
            data-aos="fade-up">
            <div class="col-md-6 hero-info order-md-first order-last"
                data-aos="zoom-in"
                data-aos-delay="100">
                <h2>Welcome to WAKi International Group</h2>
                <p>WAKi is a health product company that emphasizes guaranteed quality, to help families towards a better life. </p>
                <div>
                    <a href="#main" class="btn-get-started scrollto">
                        Explore Now
                    </a>
                    <a href="#myModal"
                        id="mobile"
                        class="btn-mobile"
                        data-toggle="modal"
                        data-target="#video2"
                        style="margin-left: 0.5em;">
                        Watch Video<span style="padding-left: 0.5em;"><i class="far fa-play-circle"></i></span>
                    </a>
                </div>
            </div>

            <div class="col-md-6" style="flex: none;">
                <div class="text-right"
                    style="position: relative; top: 50%; transform: translateY(-50%);"
                    data-toggle="modal"
                    data-target="#video2">
                    <img src="{{ asset('sources/play.png') }}"
                        alt="#"
                        class="icon-play img-fluid" />
                </div>
            </div>

            <div id="video2" class="modal fade videoModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button"
                                class="close"
                                data-dismiss="modal">
                                &times;
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="overlay-inner overlay-video embed-responsive embed-responsive-4by3">
                                <video id="video-2"
                                    muted
                                    onclick="this.paused?this.play():this.pause();"
                                    loop
                                    style="min-height: 100%;">
                                    <source src="{{ asset('sources/Introduction of WAKi short.mp4') }}" type="video/mp4">
                                </video>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Hero -->

<!--==========================Clients Section============================-->
<section id="clients" class="wow fadeInUp">
    <div class="container">
        <header class="section-header">
        <img src="{{ asset('sources/awards-black.png') }}"
            alt=""
            class="img-fluid" />
        </header>
    </div>
</section>

<main id="main">
  <!--==========================About Us Section============================-->
  <section id="about" style="position: relative; z-index: -1;">
    <div class="container">
        <div class="col-lg-12 col-md-12" style="padding-bottom: 1.5em; padding-top: 2em;">
            <h2>About Us</h2>
            <div class="row">
                <div class="col-lg-5 col-md-6">
                    <div class="about-img wow fadeInLeft">
                        <img src="{{ asset('sources/waki-building2.jpg') }}" />
                    </div>
                </div>

                <div class="col-lg-7 col-md-6">
                    <div class="about-content">
                        <h3>WAKi International Group</h3>
                        <p>"Metrowealth International Group" has been established in 1995. Since then, the company has been expanding rapidly in the Asia Pacific region and European countries. At year 2008, in order to strategize global marketing, the company decided to change its branding to "WAKi". Therefore, the company is renamed as "WAKi International Group".
                        <br>
                        Therefore, the Company renamed as “WAKi International Group”. Nowadays, “WAKi” has become a popular brand for health products. For expanding business blueprint, WAKi has established it’s branches and joint-venture companies in Malaysia, Indonesia, Philippines, Thailand, Singapore, Brunei, Vietnam, Cambodia, Myanmar, Hongkong, Japan, Korea and China. WAKi International Headquarter is located in WAKi Building, Kuala Lumpur, Malaysia.</p>
                        </p>
                        <p>"Our vision being healthy with WAKi towards a better life."</p>
                        <p>Not a Promise, but Sure!</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp"
                data-wow-duration="1.4s"
                style="margin: 0 0 30px 0;">
                <div class="box col-md-12">
                    <div class="icon" style="background: #eafde7;">
                        <h1 class="WAKI">W</h1>
                    </div>
                    <h4 class="title" style="color: #00844a;font-weight: bolder;">
                        Willingness
                    </h4>
                    <p class="description">
                        We believe that <i>Willingness</i> conquers everything.
                    </p>
                </div>
            </div>
        <div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp" data-wow-delay="0.1s" data-wow-duration="1.4s" style="margin: 0 0 30px 0;">
          <div class="box col-md-12">
            <div class="icon" style="background: #eafde7;"><h1 class="WAKI">A</h1></div>
            <h4 class="title" style="color: #00844a;font-weight: bolder;">Action & Agility</h4>
            <p class="description">We believe that <i>Action</i> is power.</p>
          </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp" data-wow-delay="0.2s" data-wow-duration="1.4s" style="margin: 0 0 30px 0;">
          <div class="box col-md-12">
            <div class="icon" style="background: #eafde7;"><h1 class="WAKI">K</h1></div>
            <h4 class="title" style="color: #00844a;font-weight: bolder;">Knowledge</h4>
            <p class="description">We believe that <i>Knowledge</i> is the essential part of success.</p>
          </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp" data-wow-delay="0.3s" data-wow-duration="1.4s" style="margin: 0 0 30px 0;">
          <div class="box col-md-12">
            <div class="icon" style="background: #eafde7;"><h1 class="WAKI" style="color: #fcb813;">i</h1></div>
            <h4 class="title" style="color: #fcb813;font-weight: bolder;">Innovation</h4>
            <p class="description">We believe that <i>Innovation</i> will bring about infinite opportunities to us.</p>
          </div>
        </div>
      </div><!--row-->
    </div>
  </section><!-- #about -->

  <!--==========================Product Section============================-->
  <section id="product">
    <div class="container">

        <header class="section-header">
            <h2>Our Product</h2>
        </header>
      <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12 col-sm-12">

          <div class="owl-carousel product-carousel">
              <div class="card white-bg mb-3 item">
                <div class="container product-img">
                  <img src="{{ asset ('sources/thumbnail-massager.png')}}" class="card-img-top img-fluid" />
                </div>
                <div class="card-body">
                  <div data-equal-height="card" class="caption">
                    <span class="category">Massager</span>
                    <p>WAKi Massager is an automatic massage equipment designed to improve user health and provide comfort for users. </p>
                  </div>
                </div>
                <div class="card-footer" style="background: initial;">
                  <div class="text-center pb-2"><hr>
                    <a href="/product_category/5" class="btn-link"><span>View All </span></a>
                  </div>
                </div>
              </div>
              <div class="card white-bg mb-3 item">
                <div class="container product-img">
                  <img src="{{ asset ('sources/thumbnail-hpt.png') }}" class="card-img-top img-fluid" alt="" />
                </div>
                <div class="card-body ">
                  <div data-equal-height="card" class="caption">
                  <span class="category">High Potential Therapy</span>
                  <p>WAKi High Potential Therapy is multifunctional therapy equipment based on electricity that can regenerate health in human body, can help to activate body cells, accelerate cellular metabolic processes, and remove free radicals. </p>
                  </div>
                </div>
                <div class="card-footer"  style="background: initial;">
                  <div class="text-center pb-2"><hr>
                      <a href="/product_category/2" class="btn-link"><span>View All </span></a>
                  </div>
                </div>
              </div>
              <div class="card white-bg mb-3 item">
                <div class="container product-img">
                  <img src="{{ asset ('sources/thumbnail-bio.png') }}" class="card-img-top img-fluid" alt="" />
                </div>
                <div class="card-body">
                  <div data-equal-height="card" class="caption">
                  <span class="category">Bio Energy</span>
                  <p>WAKi Bio Energy is water trearment equipment to improve the quality of water with very small molecules so it is easier to be absorbed by the human body and it can help increase the metabolism of cells in the body. </p>
                  </div>
                </div>
                <div class="card-footer"  style="background: initial;">
                  <div class="text-center pb-2"><hr>
                      <a href="/product_category/4" class="btn-link"><span>View All </span></a>
                  </div>
                </div>
              </div>
              <div class="card white-bg mb-3 item">
                <div class="container product-img">
                  <img src="{{ asset ('sources/thumbnail-humidifier.png') }}" class="card-img-top img-fluid" alt="" />
                </div>
                <div class="card-body ">
                  <div data-equal-height="card" class="caption">
                  <span class="category">Air Humidifier</span>
                    <p>WAKi Multi Functional Ion Air Humidifier is multi-functional air purification and humidifier that can improve indoor air quality so it can create healthy environment in our house and office. </p>
                  </div>
                </div>
                <div class="card-footer"  style="background: initial;">
                  <div class="text-center pb-2"><hr>
                      <a href="/product_category/3" class="btn-link"><span>View All </span></a>
                  </div>
                </div>
              </div>
              <div class="card white-bg mb-3 item">
                <div class="container product-img">
                  <img src="{{ asset ('sources/thumbnail-household.png') }}" class="card-img-top img-fluid" alt="" />
                </div>
                <div class="card-body">
                  <div data-equal-height="card" class="caption">
                  <span class="category">Household</span>
                      <p>
                       WAKi Household is a neccesary electrical appliance in every household to make your household chores easier.
                      </p>
                  </div>
                </div>
                <div class="card-footer"  style="background: initial;">
                  <div class="text-center pb-2"><hr>
                      <a href="/product_category/6" class="btn-link"><span>View All </span></a>
                  </div>
                </div>
              </div>
          </div>
          <div class="row card-group">

          </div>
      </div>
      </div>
    </div>
  </section>
  <!-- #product -->

    <!-- ======= Testimonials Section ======= -->
    <section id="testimonials" class="testimonials section-bg">
      <div class="container" data-aos="zoom-in">

            <header class="section-header">
                <h2 style="margin-bottom: 10px;">Wy Choose Us</h2>

                <h4 style="text-align: center;">"We bring WAKian and WAKi’s customers, towards a better life."<br>Not a Promise, but Sure!</h4>
               <div class="text-center mt-2 mb-2">
                <img src="{{ asset ('sources/testi-icon.png') }}" class="img-fluid" alt="" style="width: 60px; height: 60px;" />

               </div>
            </header>

        <div class="row justify-content-center">
          <div class="col-lg-8">

            <div class="owl-carousel testimonials-carousel">
              <div class="testimonial-item">
                  <h5>
                    "Before using WAKi High Potential Therapy, I had frequent insomnia, high blood pressure, 260 cholesterol, and frequent fatigue.
                     After using it, the blood pressure is normal to 120/80, cholesterol decreases and is stable, the body is more energetic and fit."
                  </h5><br>
                  <h4>- Moh. Lontara</h4>
              </div>

              <div class="testimonial-item">
                  <h5>
                    "Before using WAKi High Potential Therapy, my right hand was tingling, my knees often hurt, I was easily dizzy, had high blood pressure.
                     After using it, the tingling has disappeared, the knee has improved, the dizziness is rare, the high blood pressure has decreased."
                  </h5>
                  <br>
                  <h4>- Wiwik Sulastri</h4>
              </div>

              <div class="testimonial-item">
                  <h5>
                    "Before using WAKi High Potential Therapy, I couldn't sleep, my body ached and I often felt dizzy, and often had tingling in my legs.
                     After using it for 3 days I can sleep well, after 10 days the body feels better, after continuing the therapy, the tingling is gone, the body feels good and sleeps well."
                  </h5><br>
                  <h4>- Samsul Arifin</h4>
              </div>

              <div class="testimonial-item">
                  <h5>
                    "Before using WAKi High Potential Therapy, it was easy to get tired, overweight, sore feet, irregular menstruation.
                     After using it for 3 days, I can sleep well, the body is no longer tired, the weight has decreased after 2 months of therapy, the palms are no longer sore, menstruation is normal."
                  </h5><br>
                  <h4>- Imas Dedeh</h4>
              </div>

              <div class="testimonial-item">
                  <h5>
                    "Before using WAKi High Potential Therapy, when standing up from sitting my eyes would feel dizzy, difficult to defecate, high cholesterol, back and hip pain.
                     After using it, my eyes are no longer dizzy when standing up, defecation is smooth, cholesterol decreased, waist can move in all directions."
                  </h5><br>
                  <h4>- Suprapto</h4>
              </div>

              <div class="testimonial-item">
                  <h5>
                    "Before using WAKi High Potential Therapy, I often had insomnia, knee pain, and frequent finger cramps.
                     After using it, insomnia has disappeared and can sleep as usual, knees have improved, fingers are no longer cramping due to smooth blood circulation."
                  </h5><br>
                  <h4>- Meiske</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
      </section>

  <!--==========================Portfolio Section============================-->
  <section id="portfolio">
    <div class="container">

      <header class="section-header">
        <h2 class="section-title">Our Gallery</h2>
      </header>
      
      <section class="Grid">
        <div class="Grid-row">
          @foreach($albums as $key => $album)
            @php
              $string = str_replace(' ', '', $album->event['name']);
              $codePath = strtolower($string);

              $photoPath = asset('sources/album/' . $codePath);

              $index = $key + 1;
            @endphp
            <a class="Card" onClick="openGallery({{$index}})" id="card-{{$key+1}}">
                <div class="Card-thumb">
                    <div class="Card-shadow"></div>
                    <div class="Card-shadow"></div>
                    <div class="Card-shadow"></div>
                    <div class="Card-image" style="background-image: url({{$photoPath.'/'.$album['arr_photo'][0]}})"></div>
                </div>
                <div class="Card-title"><span>{{$album->event['name']}}</span></div>
                @if(Utils::$lang=='id')
                <div class="Card-explore"><span>Lihat Semua</span></div><button class="Card-button">Lihat Semua</button>
                @elseif(Utils::$lang=='eng')
                <div class="Card-explore"><span>See More</span></div><button class="Card-button">View More</button>
                @endif
            </a>
          @endforeach
        </div>
      </section>

      @foreach($albums as $key => $album)
        @php
          $string = str_replace(' ', '', $album->event['name']);
          $codePath = strtolower($string);

          $photoPath = asset('sources/album/' . $codePath);
        @endphp
        <section class="Gallery" id="gallery-{{$key+1}}">

          <div class="row my-5">
            <div class="tabs-container">
              <ul id="portfolio-flters">
                @if(Utils::$lang=='id')
                <li data-filter=".filter-photo" class="filter-active">Foto</li>
                <li data-filter=".filter-video" >Video</li>
                @elseif(Utils::$lang=='eng')
                <li data-filter=".filter-photo" class="filter-active">Photo</li>
                <li data-filter=".filter-video" >Video</li>
                @endif
              </ul>
            </div>
          </div>

          <div class="Gallery-header"><a class="Gallery-close" onclick="closeAll()">×</a></div>
          <div class="row portfolio-container">
            @for($x = 0; $x < sizeof($album['arr_photo']); $x++)
            <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
              <div class="portfolio-wrap">
                <img src="{{$photoPath.'/'.$album['arr_photo'][$x]}}" class="img-fluid" alt="">
                <div class="portfolio-info">
                  @if(Utils::$lang=='id')
                  <h4><a href="#">Foto {{$x+1}}</a></h4>
                  @elseif(Utils::$lang=='eng')
                  <h4><a href="#">Photo {{$x+1}}</a></h4>
                  @endif
                  <div>
                    <a href="{{$photoPath.'/'.$album['arr_photo'][$x]}}" data-lightbox="portfolio" data-title="Foto" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
                  </div>
                </div>
              </div>
            </div>
            @endfor

            @for($v = 0; $v < sizeof($album['url_video']) ; $v++)
            <div class="col-lg-4 col-md-6 portfolio-item filter-video">
              <div class="portfolio-wrap2">
                <h5 class="portfolio-video-title">{{$album['url_video'][$v]['title']}}</h5>
                <iframe width="100%" height="auto" position="relative" src="{{$album['url_video'][$v]['url']}}" frameborder="0" allowfullscreen=""></iframe>
              </div>
            </div>
            @endfor
          </div>
        </section>
      @endforeach
    </div>
  </section>

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
                        <a href="https://wakimart.com.ph/" class="r-link link text-underlined" target="_blank" rel="noopener noreferrer">View</a>
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
                        <a href="https://wakishop.asia/" class="r-link link text-underlined"  target="_blank" rel="noopener noreferrer">View</a>
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
                        <a href="https://wakimalaysiandimsum.com/" class="r-link link text-underlined" target="_blank" rel="noopener noreferrer">View</a>
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
                        <a href="https://waki3dprinting.com/" class="r-link link text-underlined"  target="_blank" rel="noopener noreferrer">View</a>
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

@section('script')
<script>

  function openGallery(id) {
    closeAll();
    const gallery = document.getElementById('gallery-'+id);
    const card = document.getElementById('card-'+id);
    gallery.classList.add('Gallery--active');
    card.classList.add('Card--active');
  }

function closeAll() {
  const galleryActv = document.querySelector('.Gallery--active');
  const cardActv = document.querySelector('.Card--active');
  if (galleryActv) {
    galleryActv.classList.remove('Gallery--active');
  }
  if (cardActv) {
    cardActv.classList.remove('Card--active');
  }
}

</script>
@endsection

