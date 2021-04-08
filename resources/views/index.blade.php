
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
              <img src="{{asset('sources/waki-building.jpg')}}" alt="">
            </div>
          </div>

          <div class="col-lg-7 col-md-6">
            <div class="about-content">
              @if(Utils::$lang=='id')

                <h3>WAKi International Group</h3>

                <p>"Metrowealth International Group" telah didirikan pada tahun 1995. Sejak saat itu, perusahaan telah berkembang pesat di kawasan Asia Pasifik dan negara-negara Eropa.</p>
                <p>Pada tahun 2008, untuk menyusun strategi pemasaran global, perusahaan memutuskan untuk mengubah nama menjadi "WAKi". Oleh karena itu, perusahaan ini terdaftar sebagai "WAKi International Group".
                </p>
              @elseif(Utils::$lang=='eng')

                <h3>WAKi International Group</h3>

                <p>"Metrowealth International Group" has been established in 1995. Since then, the company has been expanding rapidly in the Asia Pacific region and European countries. At year 2008, in order to strategize global marketing, the company decided to change its branding to "WAKi". Therefore, the company is renamed as "WAKi International Group".
                  <br>
                  Therefore, the Company renamed as “WAKi International Group”. Nowadays, “WAKi” has become a popular brand for health products. For expanding business blueprint, WAKi has established it’s branches and joint-venture companies in Malaysia, Indonesia, Philippines, Thailand, Singapore, Brunei, Vietnam, Cambodia, Myanmar, Hongkong, Japan, Korea and China. WAKi International Headquarter is located in WAKi Building, Kuala Lumpur, Malaysia.</p>
                </p>
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
            <p class="description">Kami percaya bahwa <i>Willingness</i> menguasai segalanya.</p>
            @elseif(Utils::$lang=='eng')
            <p class="description">We believe that <i>Willingness</i> conquers everything.</p>
            @endif
          </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp" data-wow-delay="0.1s" data-wow-duration="1.4s" style="margin: 0 0 30px 0;">
          <div class="box col-md-12">
            <div class="icon" style="background: #eafde7;"><h1 class="WAKI">A</h1></div>
            <h4 class="title" style="color: #00844a;font-weight: bolder;">Action</h4>
            @if(Utils::$lang=='id')
            <p class="description">Kami percaya bahwa <i>Action</i> adalah kekuatan.</p>
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
            <p class="description">Kami percaya bahwa <i>Knowledge</i> adalah bagian penting dari kesuksesan.</p>
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
            <p class="description">Kami percaya bahwa <i>Innovation</i> akan membawa peluang tak
            terbatas kepada kita.</p>
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
                  <img src="{{ asset ('sources/massager.jpg')}}" class="card-img-top img-fluid" />
                </div>
                <div class="card-body">
                  <div data-equal-height="card" class="caption">
                      <span class="category">Massager</span>
                      @if(Utils::$lang=='id')
                      <p> WAKi Multi Functional Massage Belt adalah sabuk pelangsing pijat terkecil pertama di dunia yang dirancang dengan baik untuk pelanggan oleh Metrowealth WAKi International Group. Menerapkan teori pijat tradisional sebagai fondasi, menerapkan teknologi getaran inti terbaru untuk bagian dalam sistem operasi.</p>
                      @elseif(Utils::$lang=='eng')
                      <p>WAKi Multi Functional Massage Belt is the world's first smallest massage slimming belt that well designed for customers by Metrowealth WAKi International Group. Applies the traditional massage theory as foundation, applies the latest core vibration technology for inner operation system. </p>
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
                  <img src="{{ asset ('sources/theraphy.jpg') }}" class="card-img-top img-fluid" alt="" />
                </div>
                <div class="card-body ">
                  <div data-equal-height="card" class="caption">
                  <span class="category">High Potential Therapy</span>
                    @if(Utils::$lang=='id')
                      <p> WAKi Multi Functional ULTRA Potential Therapy Healthcare Device dapat membantu masyarakat modern memperbaiki masalah kesehatanya dalam kehidupan modern secara efektif. Berhasil menciptakan generasi baru dari peralatan terapi yang berpotensi tinggi.</p>
                      @elseif(Utils::$lang=='eng')
                      <p>
                        WAKi Multi Functional ULTRA Potential Therapy Healthcare Device is able to help modern people in improving their health problems in modern life effectively. Successfully invented the new generation of high potential therapeutic equipment. </p>
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
                  <img src="{{ asset ('sources/bioenergy.jpg') }}" class="card-img-top img-fluid" alt="" />
                </div>
                <div class="card-body">
                  <div data-equal-height="card" class="caption">
                  <span class="category">Bio Energy</span>
                      @if(Utils::$lang=='id')
                      <p> WAKi Bio Energy-π Air adalah bio energi air alkali rendah yang berenergi tinggi. Sangat cocok untuk tubuh manusia karena molekulnya yang sangat kecil sehingga lebih mudah diserap oleh tubuh manusia. </p>
                      @elseif(Utils::$lang=='eng')
                      <p>
                         WAKi Bio Energy-π Water is high energetic low alkaline water. It is very suitable for the human body because its molecule is very small. Therefore, it is easier to be absorbed by the human body. </p>
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
                  <img src="{{ asset ('sources/air.png') }}" class="card-img-top img-fluid" alt="" />
                </div>
                <div class="card-body ">
                  <div data-equal-height="card" class="caption">
                  <span class="category">Air Humidifier</span>
                      @if(Utils::$lang=='id')
                      <p>  WAKi Multi Functional Ion Air Humidifier adalah peralatan perawatan kesehatan pemurni udara dan pelembab udara multifungsi. Dapat membantu mengurangi polusi udara di dalam ruangan dan menyediakan lingkungan yang sehat di rumah dan kantor.</p>
                      @elseif(Utils::$lang=='eng')
                      <p>
                        WAKi Multi Functional Ion Air Humidifier is multi-functional air purification and humidifier health care equipment. It helps to reduce air pollution in room and provide healthy environment in house and office.  </p>
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
                  <img src="{{ asset ('sources/household.jpg') }}" class="card-img-top img-fluid" alt="" />
                </div>
                <div class="card-body">
                  <div data-equal-height="card" class="caption">
                  <span class="category">Household</span>
                     @if(Utils::$lang=='id')
                      <p>   WAKi Multi Functional Dishwasher adalah mesin pencuci piring otomatis multi fungsi yang super bersih dan higienis. Dengan teknologi terbaru dimana fungsi hemat energi disertakan. Peralatan listrik yang diperlukan di setiap rumah tangga. </p>
                      @elseif(Utils::$lang=='eng')
                      <p>
                       WAKi Multi Functional Dishwasher is a multi functional automatic super clean and hygienic dishwasher. With the latest technology where energy-save function is included. A neccesary electrical appliance in every household.  </p>
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

                <h4 style="text-align: center;">Misi kami membawa pelanggan WAKian dan WAKi, menuju kehidupan yang lebih baik.<br>Bukan Janji, tapi Pasti!</h4>
              @elseif(Utils::$lang=='eng')
                <h2 style="margin-bottom: 10px;">Wy Choose Us</h2>

                <h4 style="text-align: center;">We bring WAKian and WAKi’s customers, towards a better life.<br>Not a promise, but sure!</h4>
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

<<<<<<< HEAD
      <div class="col-lg-12 col-md-12">

        <div class="col-lg-12 col-md-12 wow fadeInUp" style="width: 60%; margin:auto;">
          <div class="member">
            <img src="{{asset('sources/Waki-Chairman.jpg')}}" class="img-fluid" alt="">
            <div class="member-info">
              <div class="member-info-content">
                <h4>Teo Choo Guan</h4>
                <span>Chairman of WAKi International Group</span>
=======
      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="row card-group align-items-center">
            <div class="col-lg-3 col-md-3 col-sm-3">
              <div class="card-business mb-3">
                <div class="card-img-top">
                  <img src="{{ asset ('sources/wakimartlogo.png')}}" class="img-fluid" style="padding-top: 30px;" />
                </div>
                <div class="card-body">
                  <div class="card-title text-center">
                    WAKimart
                  </div>
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
                  <div class="card-title text-center">
                    WAKiShop
                  </div>
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
                  <div class="card-title text-center">
                    WAKi F&B
                  </div>
                  <div class="row justify-content-center align-self-center">
                    <div>
                      <a href="#0" class="r-link link text-underlined">Lihat</a>
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
                  <div class="card-title text-center">
                    WAKi 3D Printing
                  </div>
                  <div class="row justify-content-center align-self-center">
                    <div>
                      <a href="https://www.facebook.com/waki3Dprintingindustries/" class="r-link link text-underlined"  target="_blank" rel="noopener noreferrer">Lihat</a>
                    </div>
                  </div>
                </div>
>>>>>>> eb0fa6e727f421bc79753cd97f8769352b463c3a
              </div>
            </div>

        </div>
      </div>

    </div>
  </section>
  <!-- #business -->
</main>
@endsection
