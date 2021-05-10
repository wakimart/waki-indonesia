
@extends('layouts.template')

@section('content')

<!--==========================Hero Section============================-->
<section id="hero" class="clearfix">
  <div class="container d-flex h-100">
    <div class="row justify-content-center align-self-center" data-aos="fade-up">
      <div class="col-md-6 hero-info order-md-first order-last" data-aos="zoom-in" data-aos-delay="100">
        <h2>Selamat datang di WAKi International Group</h2>
        <p>WAKi adalah perusahaan alat kesehatan yang mengunggulkan kualitas terjamin, untuk membantu keluarga menuju kehidupan yang lebih baik. </p>
        <div>
          <a href="#main" class="btn-get-started scrollto">Mulai</a>
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
</section><!-- End Hero -->
{{--
    <div class="carousel-inner ">
      @foreach ($banners as $banner )
      @php
        $img = json_decode($banner->image, true);
      @endphp

      @if (count($img) != 0)
      <div class="carousel-item active">
        <img class="d-block w-100" src="{{asset('sources/banners/').'/'.$img[0]['img']}}"  href ="{{$img[0]['url']}}" alt="Second slide">
      </div>
      @endif
    @endforeach

    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
 --}}
  {{-- <div class="container d-flex h-100"> --}}
   {{--  <div class="col-lg-12 col-md-12">
      <div class="intro-content">
        <br>

        @if(Utils::$lang=='id')
          <h2>Selamat datang di WAKi International Group</h2>

          <p>"Metrowealth International Group" telah didirikan pada tahun 1995. Sejak saat itu, perusahaan telah berkembang pesat di kawasan Asia Pasifik dan negara-negara Eropa. Pada tahun 2008, untuk menyusun strategi pemasaran global, perusahaan memutuskan untuk mengubah nama menjadi "WAKi". Oleh karena itu, perusahaan ini terdaftar sebagai "WAKi International Group".</p>
          <p>Saat ini, WAKi telah berkembang di Malaysia, Indonesia, Filipina, Thailand, Singapura, Vietnam, Kamboja, Myanmar, Hong Kong dan Cina. Kantor pusat WAKi terletak di Menara WAKi, Kuala Lumpur, Malaysia.</p>
          <p>Pada tahun 2019, Teo Choo Guan dianugerahi "Penghargaan Perdamaian Dunia Sweeden" dan dinominasikan sebagai Duta Perdamaian Dunia di Blue Hall of Stockholm City Hall, Swedia. Serta mendapat liputan luas oleh media sosial dan media video dari seluruh dunia yang menerbitkan berita ucapan selamat khususnya iklan di layar raksasa Nasdaq di New York Times Square. Ini menunjukkan kekuatan perdamaian di seluruh dunia.</p>
        @elseif(Utils::$lang=='eng')
          <h2>Welcome to WAKi International Group</h2>

          <p>"Metrowealth International Group" has been established in 1995. Since then, the company has been expanding rapidly in the Asia Pacific region and European countries. At year 2008, in order to strategize global marketing, the company decided to change its branding to "WAKi". Therefore, the company is renamed as "WAKi International Group".</p>
          <p>In the present, WAKi has developed its marked in Malaysia, Indonesia, Philippines, Thailand, Singapore, Vietnam, Cambodia, Myanmar, Hong Kong and China. WAKi headqarter is located at WAKi Tower, Kuala Lumpur, Malaysia.</p>
          <p>In year 2019, Mr Teo Choo Guan was awarded the "Sweeden World Peace Award" and nominated as te World Peace AMbassador in the Blue Hall of Stockholm City Hall, Sweden. And gets wide coverage by the social media and video media from around the world that publishes congratulation news especialy the advertising on the giant screen Nasdaq in New York Times Square. This shows the strength of peace all across the world.</p>
        @endif
      </div>
      <div class="row">
        <div class="col-sm-12 col-md-4 col-lg-4 wow fadeInUp" data-wow-duration="1.4s">
          <div class="box">
            <div class="img-fluid">
              <iframe class="responsive-iframe-media" position="relative" src="https://www.youtube.com/embed/YMVW9d8X86M" frameborder="0" allowfullscreen=""></iframe>
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-md-4 col-lg-4 wow fadeInUp" data-wow-duration="1.4s">
          <div class="box">
            <img src="https://waki.asia/wp-content/uploads/2017/07/Malaysia-Therapy-Therapeutic-Equipment.jpg" style="background: center top no-repeat; background-size: contain; width: 100%;">
          </div>
        </div>
        <div class="col-sm-12 col-md-4 col-lg-4 wow fadeInUp" data-wow-delay="0.1s" data-wow-duration="1.4s">
          <div class="box">
            <img src="https://waki.asia/wp-content/uploads/2017/07/Indonesia-Therapy-Therapeutic-Equipment.jpg" style="background: center top no-repeat; background-size: contain; width: 100%;">
          </div>
        </div>
      </div>
    </div> --}}
  {{-- </div> --}}
{{-- </section> --}}


  <!--==========================Clients Section============================-->
  <section id="clients" class="wow fadeInUp">
    <div class="container">

      <header class="section-header">
        {{-- @if(Utils::$lang=='id')
        <h3>Penghargaan Kami</h3>
        @elseif(Utils::$lang=='eng')
        <h3>Our Awards</h3>
        @endif --}}
        <img src="{{asset('sources/Awards_s.png')}}" alt="" class="img-fluid">
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
              <img src="{{asset('sources/waki-building2.jpg')}}" alt="">
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
          <h2>Produk Kami</h2>
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
                      @if(Utils::$lang=='id')
                      <p> WAKi Massager adalah alat pijat otomatis yang di desain untuk meningkatkan kesehatan dan kenyamanan pengguna.</p>
                      @elseif(Utils::$lang=='eng')
                      <p>WAKi Massager is an automatic massage equipment designed to improve user health and provide comfort for users. </p>
                      @endif
                      </div>
                </div>
                <div class="card-footer" style="background: initial;">
                  <div class="text-center pb-2"><hr>
                    {{-- <button class="btn-link"><span>Lihat Semua </span></button> --}}
                    <a href="/product_category/5" class="btn-link"><span>Lihat Semua </span></a>
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
                    <a href="/product_category/5" class="btn-link"><span>Lihat Semua </span></a>
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
                    <a href="/product_category/4" class="btn-link"><span>Lihat Semua </span></a>
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
                    <a href="/product_category/3" class="btn-link"><span>Lihat Semua </span></a>
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
                    <a href="/product_category/6" class="btn-link"><span>Lihat Semua </span></a>
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
                <img src="{{ asset ('sources/testi-icon.png') }}" class="img-fluid" alt="" style="width: 60px; height: 60px;" />

               </div>
            </header>

        <div class="row justify-content-center">
          <div class="col-lg-8">

            <div class="owl-carousel testimonials-carousel">
              <div class="testimonial-item">
                <h5>
                  "Sebelum menggunakan WAKi High Potential Therapy, sering insomnia, tekanan darah tinggi, kolesterol 260, dan sering kelelahan.
                  Setelah menggunakan, tensi sudah normal 120/80, kolesterol menurun dan stabil, badan lebih energik dan bugar."
                </h5><br>
                <h4>- Bapak Moh. Lontara</h4>
              </div>

              <div class="testimonial-item">
                <h5>
                  "Sebelum menggunakan WAKi High Potential Therapy, tangan kanan kesemutan, lutut sering sakit, gampang pusing, darah tinggi.
                  Setelah menggunakan, kesemutan sudah hilang, lutut sudah membaik, pusing sudah jarang, darah tinggi sudah menurun."
                </h5><br>
                <h4>- Ibu Wiwik Sulastri</h4>
              </div>

              <div class="testimonial-item">
                <h5>
                  "Sebelum menggunakan WAKi High Potential Therapy, tidak bisa tidur, badan pegal dan sering pusing, dan sering kesemutan di kaki.
                  Setelah menggunakan 3 hari bisa tidur nyenyak, setelah 10 hari badan terasa lebih enak, setelah melanjutkan terapi, kesemutan hilang, badan enak dan tidur nyenyak."

                </h5><br>
                <h4>- Bapak Samsul Arifin</h4>
              </div>

              <div class="testimonial-item">
                <h5>
                  "Sebelum menggunakan WAKi High Potential Therapy, gampang capek, berat badan berlebih, telapak kaki sakit, menstruasi tidak lancar.
                  Setelah menggunakan 3 hari bisa tidur nyenyak, badan sudah tidak gampang capek, berat badan turun setelah 2 bulan terapi, telapak sudah tidak sakit, menstruasi sudah normal."
                </h5><br>
                <h4>- Ibu Imas Dedeh</h4>
              </div>

              <div class="testimonial-item">
                <h5>
                  " Sebelum menggunakan WAKi High Potential Therapy, berdiri dari duduk saja mata berkunang - kunang, susah BAB, kolesterol tinggi, sakit pinggang dan pinggul.
                  Setelah menggunakan, berdiri sudah berkunang-kunang, BAB lancar, Kolesterol menurun, pinggang sudah dapat digerakkan ke segala penjuru."
                </h5><br>
                <h4>- Bapak Suprapto</h4>
              </div>

              <div class="testimonial-item">
                <h5>
                  "Sebelum menggunakan WAKi High Potential Therapy, sering insomnia, sakit lutut, jari tangan sering keram.
                  Setelah menggunakan, Insomnia sudah hilang dan bisa tidur seperti biasa, lutut sudah membaik, jari tangan sudah tidak keram karena lancar peredaran darah."
                </h5><br>
                <h4>- Ibu Meiske</h4>
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

      <div class="row">
        <div class="col-lg-12">
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

      <div class="row portfolio-container">
        @php
        foreach($galleries as $gallerie){
          $photos = json_decode($gallerie->photo, true);
          $photoPath = asset('sources/portfolio/');
          $videos = json_decode($gallerie->url_youtube, true);

          $photoPath = asset('sources/portfolio/');
        }
        @endphp

        @for($x = 0; $x < sizeof($photos); $x++)
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{$photoPath.'/'.$photos[$x]}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo {{$x+1}}</a></h4>
              <p>App</p>
              <div>
                <a href="#" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        @endfor

        @for($v = 0; $v < sizeof($videos) ; $v++)
        <div class="col-lg-4 col-md-6 portfolio-item filter-video">
          <div class="portfolio-wrap2">
            <h5 class="portfolio-video-title">{{$videos[$v]['title']}}</h5>
            <iframe width="100%" height="auto" position="relative" src="{{$videos[$v]['url']}}" frameborder="0" allowfullscreen=""></iframe>
          </div>
        </div>
        @endfor

      </div>
    </div>
  </section>

  <!--==========================Our Business Section============================-->
  <section id="business" class="business">
    <div class="container" data-aos="zoom-in">

      <header class="section-header text-center pb-3">
        <h2>Bisnis Kami</h2>
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
                      <a href="https://wakimart.co.id/" class="r-link link text-underlined" target="_blank" rel="noopener noreferrer">Lihat</a>
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
                      <a href="https://www.facebook.com/WAKiShopMalaysia/" class="r-link link text-underlined"  target="_blank" rel="noopener noreferrer">Lihat</a>
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
                      <a href="https://www.facebook.com/wakimalaysiandimsum/" class="r-link link text-underlined" target="_blank" rel="noopener noreferrer">Lihat</a>
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
                      <a href="https://www.facebook.com/waki3Dprintingindustries/" class="r-link link text-underlined"  target="_blank" rel="noopener noreferrer">Lihat</a>
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
