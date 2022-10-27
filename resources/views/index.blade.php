
@extends('layouts.template')

@section('content')
<div id="video2" class="modal fade videoModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <div class="overlay-inner overlay-video embed-responsive embed-responsive-4by3">
          <video id="video-2" onclick="this.paused?this.play():this.pause();" loop style="min-height: 100%";>
            <source src="{{asset('sources/Introduction of WAKi short.mp4')}}" type="video/mp4">
          </video>
        </div>

      </div>

    </div>
  </div>
</div>

<!--==========================Hero Section============================-->
<section id="hero" class="clearfix">
  <div class="container d-flex h-100">
    <div class="row justify-content-center align-self-center" data-aos="fade-up">
      <div class="col-md-6 hero-info order-md-first order-last" data-aos="zoom-in" data-aos-delay="100">
        @if(Utils::$lang=='id')
          <h2>Selamat datang di WAKi International Group</h2>
          <p>WAKi adalah perusahaan alat kesehatan yang mengunggulkan kualitas terjamin, untuk membantu keluarga menuju kehidupan yang lebih baik. </p>
          <div>
            <a href="#main" class="btn-get-started scrollto">Mulai</a>
            <a href="#myModal" id="mobile" class="btn-mobile" data-toggle="modal" data-target="#video2" style="margin-left: 0.5em;">Lihat Video<span style="padding-left: 0.5em;"><i class="far fa-play-circle"></i></span></a>
          </div>
        @elseif(Utils::$lang=='eng')
          <h2>Welcome to WAKi International Group</h2>
          <p>WAKi is a health product company that emphasizes guaranteed quality, to help families towards a better life. </p>
          <div>
            <a href="#main" class="btn-get-started scrollto">Explore Now</a>
            <a href="#myModal" id="mobile" class="btn-mobile" data-toggle="modal" data-target="#video2" style="margin-left: 0.5em;">Watch Video<span style="padding-left: 0.5em;"><i class="far fa-play-circle"></i></span></a>
          </div>
        @endif
      </div>

      <div class="col-md-6" style="flex: none;">
        <div class="text-right" style="position: relative; top: 50%; transform: translateY(-50%);" data-toggle="modal" data-target="#video2">
          <img data-src="{{asset('sources/play.png')}}" alt="" class="icon-play img-fluid lozad">
        </div>

        {{-- <div class="overlay-inner overlay-video embed-responsive embed-responsive-4by3">
          <video muted onclick="this.paused?this.play():this.pause();" loop style="min-height: 100%";>
            <source src="{{asset('sources/Introduction of WAKi short.mp4')}}" type="video/mp4">
          </video>
        </div> --}}
      </div>
    </div>

  </div>
</section><!-- End Hero -->


  <!--==========================Clients Section============================-->
  <section id="clients" class="wow fadeInUp">
    <div class="container">

      <header class="section-header">
        {{-- @if(Utils::$lang=='id')
        <h3>Penghargaan Kami</h3>
        @elseif(Utils::$lang=='eng')
        <h3>Our Awards</h3>
        @endif --}}
        <img class="img-fluid lozad" data-src="{{asset('sources/awards-update.png')}}" alt="">
      </header>

    </div>
  </section><!-- #clients -->


<main id="main">
  <!--==========================About Us Section============================-->
  <section id="about" style="position: relative; z-index: -1;">
    <div class="container">
      <div class="col-lg-12 col-md-12" style="padding-bottom: 1.5em; padding-top: 2em;">
        @if(Utils::$lang=='id')
          <h2>Tentang Kami</h2>
        @elseif(Utils::$lang=='eng')
          <h2>About Us</h2>
        @endif
        <div class="row">

          <div class="col-lg-5 col-md-6">
            <div class="about-img wow fadeInLeft">
              <img class="lozad" data-src="{{asset('sources/waki-building2.jpg')}}" alt="">
            </div>
          </div>

          <div class="col-lg-7 col-md-6">
            <div class="about-content">
              @if(Utils::$lang=='id')

                <h3>WAKi International Group</h3>

                <p>"Metrowealth International Group" telah didirikan pada tahun 1995. Sejak saat itu, perusahaan telah berkembang pesat di kawasan Asia Pasifik dan negara-negara Eropa.</p>
                <p>Pada tahun 2008, untuk menyusun strategi pemasaran global, perusahaan memutuskan untuk mengubah nama menjadi "WAKi". Oleh karena itu, perusahaan ini terdaftar sebagai "WAKi International Group".
                </p>
                <p>"Visi kami Towards a Better Life. Sehat bersama WAKi menuju kehidupan yang lebih baik."</p>
                <p style="font-style: italic;">Bukan Janji, Tapi Pasti!</p>
              @elseif(Utils::$lang=='eng')

                <h3>WAKi International Group</h3>

                <p>"Metrowealth International Group" has been established in 1995. Since then, the company has been expanding rapidly in the Asia Pacific region and European countries. At year 2008, in order to strategize global marketing, the company decided to change its branding to "WAKi". Therefore, the company is renamed as "WAKi International Group".
                  <br>
                  Therefore, the Company renamed as “WAKi International Group”. Nowadays, “WAKi” has become a popular brand for health products. For expanding business blueprint, WAKi has established it’s branches and joint-venture companies in Malaysia, Indonesia, Philippines, Thailand, Singapore, Brunei, Vietnam, Cambodia, Myanmar, Hongkong, Japan, Korea and China. WAKi International Headquarter is located in WAKi Building, Kuala Lumpur, Malaysia.</p>
                </p>
                <p>"Our vision being healthy with WAKi towards a better life."</p>
                <p>Not a Promise, but Sure!</p>
              @endif
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp" data-wow-duration="1.4s" style="margin: 0 0 30px 0;">
          <div class="box col-md-12">
            <div class="icon" style="background: #eafde7;"><h1 class="WAKI">W</h1></div>
            <h4 class="title" style="color: #00844a;font-weight: bolder;">Willingness</h4>
            @if(Utils::$lang=='id')
            <p class="description">Kami percaya bahwa <i>Kemauan</i> dapat menaklukan segalanya.</p>
            @elseif(Utils::$lang=='eng')
            <p class="description">We believe that <i>Willingness</i> conquers everything.</p>
            @endif
          </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp" data-wow-delay="0.1s" data-wow-duration="1.4s" style="margin: 0 0 30px 0;">
          <div class="box col-md-12">
            <div class="icon" style="background: #eafde7;"><h1 class="WAKI">A</h1></div>
            <h4 class="title" style="color: #00844a;font-weight: bolder;">Action & Agility</h4>
            @if(Utils::$lang=='id')
            <p class="description">Kami percaya bahwa <i>Tindakan dan Kelincahan</i> adalah kekuatan.</p>
            @elseif(Utils::$lang=='eng')
            <p class="description">We believe that <i>Action</i> is power.</p>
            @endif
          </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp" data-wow-delay="0.2s" data-wow-duration="1.4s" style="margin: 0 0 30px 0;">
          <div class="box col-md-12">
            <div class="icon" style="background: #eafde7;"><h1 class="WAKI">K</h1></div>
            <h4 class="title" style="color: #00844a;font-weight: bolder;">Knowledge</h4>
            @if(Utils::$lang=='id')
            <p class="description">Kami percaya bahwa <i>Pengetahuan</i> adalah bagian penting dari keberhasilan.</p>
            @elseif(Utils::$lang=='eng')
            <p class="description">We believe that <i>Knowledge</i> is the essential part of success.</p>
            @endif
          </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp" data-wow-delay="0.3s" data-wow-duration="1.4s" style="margin: 0 0 30px 0;">
          <div class="box col-md-12">
            <div class="icon" style="background: #eafde7;"><h1 class="WAKI" style="color: #fcb813;">i</h1></div>
            <h4 class="title" style="color: #fcb813;font-weight: bolder;">Innovation</h4>
            @if(Utils::$lang=='id')
            <p class="description">Kami percaya bahwa <i>Inovasi</i> akan membawa peluang tak
            terbatas kepada kami.</p>
            @elseif(Utils::$lang=='eng')
            <p class="description">We believe that <i>Innovation</i> will bring about infinite opportunities to us.</p>
            @endif
          </div>
        </div>
      </div><!--row-->
    </div>
  </section><!-- #about -->

  <!--==========================Product Section============================-->
  <section id="product">
    <div class="container">

        <header class="section-header">
          @if(Utils::$lang=='id')
            <h2>Produk Kami</h2>
          @elseif(Utils::$lang=='eng')
            <h2>Our Product</h2>
          @endif
        </header>
      <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12 col-sm-12">

          <div class="owl-carousel product-carousel">
              <div class="card white-bg mb-3 item" style="cursor: pointer;" onclick="window.location=`{{route('product_category', 5)}}`">
                <div class="container product-img">
                  <img data-src="{{ asset ('sources/thumbnail-massager.png')}}" class="card-img-top img-fluid lozad" />
                </div>
                <div class="card-body">
                  <div data-equal-height="card" class="caption">
                      <span class="category">Massager</span>
                      @if(Utils::$lang=='id')
                      <p> WAKi Massager adalah alat pijat otomatis yang di desain untuk meningkatkan kesehatan dan kenyamanan pengguna.</p>
                      @elseif(Utils::$lang=='eng')
                      <p>WAKi Massager is an automatic massage equipment designed to improve user health and provide comfort for users. </p>
                      @endif
                      </div>
                </div>
                <div class="card-footer" style="background: initial;">
                  <div class="text-center pb-2"><hr>
                    @if(Utils::$lang=='id')
                      <a href="/product_category/5" class="btn-link"><span>Lihat Semua </span></a>
                    @elseif(Utils::$lang=='eng')
                      <a href="/product_category/5" class="btn-link"><span>View All </span></a>
                    @endif
                  </div>
                </div>
              </div>
              <div class="card white-bg mb-3 item" style="cursor: pointer;" onclick="window.location=`{{route('product_category', 2)}}`">
                <div class="container product-img">
                  <img data-src="{{ asset ('sources/thumbnail-hpt.png') }}" class="card-img-top img-fluid lozad" alt="" />
                </div>
                <div class="card-body ">
                  <div data-equal-height="card" class="caption">
                  <span class="category">High Potential Therapy</span>
                    @if(Utils::$lang=='id')
                      <p> WAKi  High Potential Theraphy merupakan alat terapi multifungsi berbasis listrik yang dapat meregenerasi kesehatan dalam tubuh manusia, membantu mengaktifkan sel-sel tubuh, melancarkan proses metabolisme sel, dan membuang radikal bebas.</p>
                      @elseif(Utils::$lang=='eng')
                      <p>
                        WAKi High Potential Therapy is multifunctional therapy equipment based on electricity that can regenerate health in human body, can help to activate body cells, accelerate cellular metabolic processes, and remove free radicals. </p>
                       @endif
                      </div>
                </div>
                <div class="card-footer"  style="background: initial;">
                  <div class="text-center pb-2"><hr>
                    @if(Utils::$lang=='id')
                      <a href="/product_category/2" class="btn-link"><span>Lihat Semua </span></a>
                    @elseif(Utils::$lang=='eng')
                      <a href="/product_category/2" class="btn-link"><span>View All </span></a>
                    @endif
                  </div>
                </div>
              </div>
              <div class="card white-bg mb-3 item" style="cursor: pointer;" onclick="window.location=`{{route('product_category', 4)}}`">
                <div class="container product-img">
                  <img data-src="{{ asset ('sources/air.jpg') }}" class="card-img-top img-fluid lozad" alt="" />
                </div>
                <div class="card-body">
                  <div data-equal-height="card" class="caption">
                  <span class="category">Bio Energy</span>
                      @if(Utils::$lang=='id')
                      <p> Waki Bio Energy adalah alat peningkat kualitas air minum dengan molekul yang sangat kecil sehingga dapat lebih mudah diserap oleh tubuh dan dapat membantu meningkatkan metabolisme sel dalam tubuh.</p>
                      @elseif(Utils::$lang=='eng')
                      <p>
                         WAKi Bio Energy is water trearment equipment to improve the quality of water with very small molecules so it is easier to be absorbed by the human body and it can help increase the metabolism of cells in the body. </p>
                       @endif
                      </div>
                </div>
                <div class="card-footer"  style="background: initial;">
                  <div class="text-center pb-2"><hr>
                    @if(Utils::$lang=='id')
                      <a href="/product_category/4" class="btn-link"><span>Lihat Semua </span></a>
                    @elseif(Utils::$lang=='eng')
                      <a href="/product_category/4" class="btn-link"><span>View All </span></a>
                    @endif
                  </div>
                </div>
              </div>
              <div class="card white-bg mb-3 item" style="cursor: pointer;" onclick="window.location=`{{route('product_category', 3)}}`">
                <div class="container product-img">
                  <img data-src="{{ asset ('sources/thumbnail-humidifier.png') }}" class="card-img-top img-fluid lozad" alt="" />
                </div>
                <div class="card-body ">
                  <div data-equal-height="card" class="caption">
                  <span class="category">Air Humidifier</span>
                      @if(Utils::$lang=='id')
                      <p> WAKi Air Humudifire adalah alat penyaring udara multifungsi yang dapat meningkatkan kualitas udara di dalam ruangan sehingga dapat menciptakan lingkungan yang sehat di rumah dan kantor kita.</p>
                      @elseif(Utils::$lang=='eng')
                      <p>
                        WAKi Multi Functional Ion Air Humidifier is multi-functional air purification and humidifier that can improve indoor air quality so it can create healthy environment in our house and office.  </p>
                       @endif
                  </div>
                </div>
                <div class="card-footer"  style="background: initial;">
                  <div class="text-center pb-2"><hr>
                    @if(Utils::$lang=='id')
                      <a href="/product_category/3" class="btn-link"><span>Lihat Semua </span></a>
                    @elseif(Utils::$lang=='eng')
                      <a href="/product_category/3" class="btn-link"><span>View All </span></a>
                    @endif
                  </div>
                </div>
              </div>
              <div class="card white-bg mb-3 item" style="cursor: pointer;" onclick="window.location=`{{route('product_category', 6)}}`">
                <div class="container product-img">
                  <img data-src="{{ asset ('sources/thumbnail-household.png') }}" class="card-img-top img-fluid lozad" alt="" />
                </div>
                <div class="card-body">
                  <div data-equal-height="card" class="caption">
                  <span class="category">Household</span>
                     @if(Utils::$lang=='id')
                       <p> Waki Household adalah peralatan listrik yang diperlukan di setiap rumah tangga untuk membuat pekerjaan rumah menjadi lebih mudah.</p>
                      @elseif(Utils::$lang=='eng')
                      <p>
                       WAKi Household is a neccesary electrical appliance in every household to make your household chores easier.</p>
                       @endif
                      </div>
                </div>
                <div class="card-footer"  style="background: initial;">
                  <div class="text-center pb-2"><hr>
                    @if(Utils::$lang=='id')
                      <a href="/product_category/6" class="btn-link"><span>Lihat Semua </span></a>
                    @elseif(Utils::$lang=='eng')
                      <a href="/product_category/6" class="btn-link"><span>View All </span></a>
                    @endif
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
              @if(Utils::$lang=='id')
                <h2 class="text-center" style="margin-bottom: 1em;">Kenapa Memilih Kami</h2>

                <h4 style="text-align: center;">"Misi kami membawa pelanggan WAKian dan WAKi, menuju kehidupan yang lebih baik."<br>Bukan Janji, tapi Pasti!</h4>
              @elseif(Utils::$lang=='eng')
                <h2 style="margin-bottom: 10px;">Wy Choose Us</h2>

                <h4 style="text-align: center;">"We bring WAKian and WAKi’s customers, towards a better life."<br>Not a Promise, but Sure!</h4>
              @endif
               <div class="text-center mt-2 mb-2">
                <img data-src="{{ asset ('sources/testi-icon.png') }}" class="img-fluid lozad" alt="" style="width: 60px; height: 60px;" />

               </div>
            </header>

        <div class="row justify-content-center">
          <div class="col-lg-8">

            <div class="owl-carousel testimonials-carousel">
              <div class="testimonial-item">
                @if(Utils::$lang=='id')
                  <h5>
                    "Sebelum menggunakan WAKi High Potential Therapy, sering insomnia, tekanan darah tinggi, kolesterol 260, dan sering kelelahan.
                    Setelah menggunakan, tensi sudah normal 120/80, kolesterol menurun dan stabil, badan lebih energik dan bugar."
                  </h5><br>
                  <h4>- Bapak Moh. Lontara</h4>
                @elseif(Utils::$lang=='eng')
                  <h5>
                    "Before using WAKi High Potential Therapy, I had frequent insomnia, high blood pressure, 260 cholesterol, and frequent fatigue.
                     After using it, the blood pressure is normal to 120/80, cholesterol decreases and is stable, the body is more energetic and fit."
                  </h5><br>
                  <h4>- Moh. Lontara</h4>
                @endif
              </div>

              <div class="testimonial-item">
                @if(Utils::$lang=='id')
                  <h5>
                    "Sebelum menggunakan WAKi High Potential Therapy, tangan kanan kesemutan, lutut sering sakit, gampang pusing, darah tinggi.
                    Setelah menggunakan, kesemutan sudah hilang, lutut sudah membaik, pusing sudah jarang, darah tinggi sudah menurun."
                  </h5><br>
                  <h4>- Ibu Wiwik Sulastri</h4>
                @elseif(Utils::$lang=='eng')
                  <h5>
                    "Before using WAKi High Potential Therapy, my right hand was tingling, my knees often hurt, I was easily dizzy, had high blood pressure.
                     After using it, the tingling has disappeared, the knee has improved, the dizziness is rare, the high blood pressure has decreased."
                  </h5><br>
                  <h4>- Wiwik Sulastri</h4>
                @endif
              </div>

              <div class="testimonial-item">
                @if(Utils::$lang=='id')
                  <h5>
                    "Sebelum menggunakan WAKi High Potential Therapy, tidak bisa tidur, badan pegal dan sering pusing, dan sering kesemutan di kaki.
                    Setelah menggunakan 3 hari bisa tidur nyenyak, setelah 10 hari badan terasa lebih enak, setelah melanjutkan terapi, kesemutan hilang, badan enak dan tidur nyenyak."

                  </h5><br>
                  <h4>- Bapak Samsul Arifin</h4>
                @elseif(Utils::$lang=='eng')
                  <h5>
                    "Before using WAKi High Potential Therapy, I couldn't sleep, my body ached and I often felt dizzy, and often had tingling in my legs.
                     After using it for 3 days I can sleep well, after 10 days the body feels better, after continuing the therapy, the tingling is gone, the body feels good and sleeps well."
                  </h5><br>
                  <h4>- Samsul Arifin</h4>
                @endif
              </div>

              <div class="testimonial-item">
                @if(Utils::$lang=='id')
                  <h5>
                    "Sebelum menggunakan WAKi High Potential Therapy, gampang capek, berat badan berlebih, telapak kaki sakit, menstruasi tidak lancar.
                    Setelah menggunakan 3 hari bisa tidur nyenyak, badan sudah tidak gampang capek, berat badan turun setelah 2 bulan terapi, telapak sudah tidak sakit, menstruasi sudah normal."
                  </h5><br>
                  <h4>- Ibu Imas Dedeh</h4>
                @elseif(Utils::$lang=='eng')
                  <h5>
                    "Before using WAKi High Potential Therapy, it was easy to get tired, overweight, sore feet, irregular menstruation.
                     After using it for 3 days, I can sleep well, the body is no longer tired, the weight has decreased after 2 months of therapy, the palms are no longer sore, menstruation is normal."
                  </h5><br>
                  <h4>- Imas Dedeh</h4>
                @endif
              </div>

              <div class="testimonial-item">
                @if(Utils::$lang=='id')
                  <h5>
                    "Sebelum menggunakan WAKi High Potential Therapy, berdiri dari duduk saja mata berkunang - kunang, susah BAB, kolesterol tinggi, sakit pinggang dan pinggul.
                    Setelah menggunakan, berdiri sudah tidak berkunang-kunang, BAB lancar, Kolesterol menurun, pinggang sudah dapat digerakkan ke segala penjuru."
                  </h5><br>
                  <h4>- Bapak Suprapto</h4>
                @elseif(Utils::$lang=='eng')
                  <h5>
                    "Before using WAKi High Potential Therapy, when standing up from sitting my eyes would feel dizzy, difficult to defecate, high cholesterol, back and hip pain.
                     After using it, my eyes are no longer dizzy when standing up, defecation is smooth, cholesterol decreased, waist can move in all directions."
                  </h5><br>
                  <h4>- Suprapto</h4>
                @endif
              </div>

              <div class="testimonial-item">
                @if(Utils::$lang=='id')
                  <h5>
                    "Sebelum menggunakan WAKi High Potential Therapy, sering insomnia, sakit lutut, jari tangan sering keram.
                    Setelah menggunakan, Insomnia sudah hilang dan bisa tidur seperti biasa, lutut sudah membaik, jari tangan sudah tidak keram karena lancar peredaran darah."
                  </h5><br>
                  <h4>- Ibu Meiske</h4>
                @elseif(Utils::$lang=='eng')
                  <h5>
                    "Before using WAKi High Potential Therapy, I often had insomnia, knee pain, and frequent finger cramps.
                     After using it, insomnia has disappeared and can sleep as usual, knees have improved, fingers are no longer cramping due to smooth blood circulation."
                  </h5><br>
                  <h4>- Meiske</h4>
                @endif
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
        @if(Utils::$lang=='id')
        <h2 class="section-title">Galeri Kami</h2>
        @elseif(Utils::$lang=='eng')
        <h2 class="section-title">Our Gallery</h2>
        @endif
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
                <img data-src="{{$photoPath.'/'.$album['arr_photo'][$x]}}" class="img-fluid lozad" alt="">
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
        @if(Utils::$lang=='id')
          <h2>Bisnis Kami</h2>
        @elseif(Utils::$lang=='eng')
          <h2>Our Business</h2>
        @endif
      </header>

      <div class="col-lg-12 col-md-12 col-sm-12 p-0 ">
        <div class="row card-group align-items-center justify-content-center">
            <div class="col-lg-4 col-md-4 col-sm-4">
              <div class="card-business mb-3">
                <div class="card-img-top">
                  <img data-src="{{ asset ('sources/wakimartlogo.png')}}" class="img-fluid lozad" style="padding-top: 30px;" />
                </div>
                <div class="card-body">

                  <div class="row justify-content-center align-self-center">
                    <div>
                      @if(Utils::$lang=='id')
                        <a href="https://wakimart.co.id/" class="r-link link text-underlined" target="_blank" rel="noopener noreferrer">Lihat</a>
                      @elseif(Utils::$lang=='eng')
                        <a href="https://wakimart.co.id/" class="r-link link text-underlined" target="_blank" rel="noopener noreferrer">View</a>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
              <div class="card-business mb-3">
                <div class="card-img-top">
                  <img data-src="{{ asset ('sources/wakishop.png')}}" class="img-fluid lozad" />
                </div>
                <div class="card-body">

                  <div class="row justify-content-center align-self-center">
                    <div>
                      @if(Utils::$lang=='id')
                        <a href="https://wakishop.id/" class="r-link link text-underlined"  target="_blank" rel="noopener noreferrer">Lihat</a>
                      @elseif(Utils::$lang=='eng')
                        <a href="https://wakishop.id/" class="r-link link text-underlined"  target="_blank" rel="noopener noreferrer">View</a>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
              <div class="card-business mb-3">
                <div class="card-img-top">
                  <img data-src="{{ asset ('sources/wakifnb.png')}}" class="img-fluid lozad" style="padding-top: 15px;"/>
                </div>
                <div class="card-body">

                  <div class="row justify-content-center align-self-center">
                    <div>
                      @if(Utils::$lang=='id')
                        <a href="https://wakimalaysiandimsum.com/" class="r-link link text-underlined" target="_blank" rel="noopener noreferrer">Lihat</a>
                      @elseif(Utils::$lang=='eng')
                        <a href="https://wakimalaysiandimsum.com/" class="r-link link text-underlined" target="_blank" rel="noopener noreferrer">View</a>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
              <div class="card-business mb-3">
                <div class="card-img-top">
                  <img data-src="{{ asset ('sources/waki3dprinting.png')}}" class="img-fluid lozad" />
                </div>
                <div class="card-body">

                  <div class="row justify-content-center align-self-center">
                    <div>
                      @if(Utils::$lang=='id')
                        <a href="https://waki3dprinting.com/" class="r-link link text-underlined"  target="_blank" rel="noopener noreferrer">Lihat</a>
                      @elseif(Utils::$lang=='eng')
                        <a href="https://waki3dprinting.com/" class="r-link link text-underlined"  target="_blank" rel="noopener noreferrer">View</a>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
              <div class="card-business mb-3">
                <div class="card-img-top">
                  <img data-src="{{ asset ('sources/wakirelic.png')}}" class="img-fluid lozad" />
                </div>
                <div class="card-body">

                  <div class="row justify-content-center align-self-center">
                    <div>
                      @if(Utils::$lang=='id')
                        <a href="https://wakirelicmuseum.com/" class="r-link link text-underlined"  target="_blank" rel="noopener noreferrer">Lihat</a>
                      @elseif(Utils::$lang=='eng')
                        <a href="https://wakirelicmuseum.com/" class="r-link link text-underlined"  target="_blank" rel="noopener noreferrer">View</a>
                      @endif
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
    if (typeof(Event) === 'function') {
      setTimeout(function(){window.dispatchEvent(new Event('resize'))},3000);
    } else {
      var evt = window.document.createEvent('UIEvents');
      evt.initUIEvent('resize', true, false, window, 0);
      setTimeout(function(){window.dispatchEvent(evt)},3000);
    }
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
