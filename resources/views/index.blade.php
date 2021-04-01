
@extends('layouts.template')

@section('content')
<!--==========================Hero Section============================-->
<section id="hero" class="clearfix">
  {{-- <img class="video-bg" src="{{ asset ('sources/video-bg.png') }}"/> --}}
    <div class="container d-flex h-100">
      <div class="row justify-content-center align-self-center" data-aos="fade-up">
        <div class="col-md-6 hero-info order-md-first order-last" data-aos="zoom-in" data-aos-delay="100">
          <h2>Selamat datang di WAKi International Group</h2>
          <p>WAKi adalah perusahaan alat kesehatan yang mengunggulkan kualitas terjamin, untuk membantu keluarga menuju kehidupan yang lebih baik. </p>
          <div>
            <a href="#main" class="btn-get-started scrollto">Mulai</a>
          </div>
        </div>

        <div class="col-md-6 hero-img">
          <img src="" alt="" class="img-fluid">
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
</section><!-- #intro -->


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
        <div class="row">

          <div class="col-lg-5 col-md-6">
            <div class="about-img wow fadeInLeft">
              <img src="{{asset('sources/waki-building.jpg')}}" alt="">
            </div>
          </div>

          <div class="col-lg-7 col-md-6">
            <div class="about-content">
              @if(Utils::$lang=='id')
                <h2>Tentang Kami</h2>

                <h3>WAKI INTERNATIONAL GROUP</h3>

                <p>"Metrowealth International Group" telah didirikan pada tahun 1995. Sejak saat itu, perusahaan telah berkembang pesat di kawasan Asia Pasifik dan negara-negara Eropa.</p>
                  
                  <p>Pada tahun 2008, untuk menyusun strategi pemasaran global, perusahaan memutuskan untuk mengubah nama menjadi "WAKi". Oleh karena itu, perusahaan ini terdaftar sebagai "WAKi International Group".
                </p>
              @elseif(Utils::$lang=='eng')
                <h2>About Us</h2>

                <h3>WAKI INTERNATIONAL GROUP</h3>

                <p>"Metrowealth International Group" has been established in 1995. Since then, the company has been expanding rapidly in the Asia Pacific region and European countries. At year 2008, in order to strategize global marketing, the company decided to change its branding to "WAKi". Therefore, the company is renamed as "WAKi International Group".
                  <br>
                  Therefore, the Company renamed as “WAKi International Group”. Nowadays, “WAKi” has become a popular brand for health products. For expanding business blueprint, WAKi has established it’s branches and joint-venture companies in Malaysia, Indonesia, Philippines, Thailand, Singapore, Brunei, Vietnam, Cambodia, Myanmar, Hongkong, Japan, Korea and China. WAKi International Headquarter is located in WAKi Building, Kuala Lumpur, Malaysia.</p>
                </p>
              @endif
            </div>
          </div>
        </div>
      </div>
      <div class="row" style="padding-top: 2em;">
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
  <section id="product" class="section-bg">
    <div class="col-lg-12 col-md-12 col-sm-12" style="padding-bottom: 1.5em; padding-top: 2em;">
      
      <h2>Produk Kami</h2>
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3 d-flex align-items-stretch"">
          <div class="card white-bg shadow  mb-3 ">
            <div class="container product-img">
              <img src="{{ asset ('sources/hpt.jpg') }}" class="" alt="" />
            </div>
            <div class="card-body">
              <div data-equal-height="card">
                  <span class="category">Massager</span>
                  <p>
                    WAKi Multi-Functional Electro Massager is a modern technology health care equipment that applying “Bio-Electro Energy” and “Magnetic Heat Energy” on foot, palms, buttocks and waist. It results in “Internal Body Massage” effect.
                  </p>
              </div><hr>
              <div class="row justify-content-center align-self-center">
                <div>
                  <a href="" class="btn-link">Lihat Semua</a>
                </div>
            </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 d-flex align-items-stretch">
          <div class="card white-bg shadow  mb-3 ">
            <div class="container product-img">
              <img src="{{ asset ('sources/hpthome.jpg') }}" class="" alt="" />
            </div>
            <div class="card-body">
              <div data-equal-height="card">
                  <span class="category">Massager</span>
                  <p>
                    WAKi Multi-Functional Electro Massager is a modern technology health care equipment that applying “Bio-Electro Energy” and “Magnetic Heat Energy” on foot, palms, buttocks and waist. It results in “Internal Body Massage” effect.
                  </p>
              </div><hr>
              <div class="row justify-content-center align-self-center">
                <div>
                  <a href="" class="btn-link">Lihat Semua</a>
                </div>
            </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 d-flex align-items-stretch">
          <div class="card white-bg shadow  mb-3 ">
            <div class="container product-img">
              <img src="{{ asset ('sources/hpt2079.jpg') }}" class="" alt="" />
            </div>
            <div class="card-body">
              <div data-equal-height="card">
                  <span class="category">Massager</span>
                  <p>
                    WAKi Multi-Functional Electro Massager is a modern technology health care equipment that applying “Bio-Electro Energy” and “Magnetic Heat Energy” on foot, palms, buttocks and waist. It results in “Internal Body Massage” effect.
                  </p>
              </div><hr>
              <div class="row justify-content-center align-self-center">
                <div>
                  <a href="" class="btn-link">Lihat Semua</a>
                </div>
            </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3">
          <div class="card white-bg shadow  mb-3 ">
            <div class="container product-img">
              <img src="{{ asset ('sources/hpt2076i.jpg') }}" class="" alt="" />
            </div>
            <div class="card-body">
              <div data-equal-height="card">
                  <span class="category">Massager</span>
                  <p>
                    WAKi Multi-Functional Electro Massager is a modern technology health care equipment that applying “Bio-Electro Energy” and “Magnetic Heat Energy” on foot, palms, buttocks and waist. It results in “Internal Body Massage” effect.
                  </p>
              </div><hr>
              <div class="row justify-content-center align-self-center">
                <div>
                  <a href="" class="btn-link">Lihat Semua</a>
                </div>
            </div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </section>
  <!-- #product -->

  <!--==========================Why Us Section============================-->
  <section id="why-us" class="wow fadeIn">
    <div class="container">
      <div class="container-fluid">

        <header class="section-header">
          @if(Utils::$lang=='id')
            <h2 class="text-center" style="margin-bottom: 1em;">Kenapa Memilih Kami</h2>

            <h4 style="text-align: center;">Misi kami membawa pelanggan WAKian dan WAKi, menuju kehidupan yang lebih baik.<br>Bukan Janji, tapi Pasti!</h4>
          @elseif(Utils::$lang=='eng')
            <h3 style="margin-bottom: 10px;">Wy Choose Us</h3>

            <h4 style="text-align: center;">We bring WAKian and WAKi’s customers, towards a better life.<br>Not a promise, but sure!</h4>
          @endif
           <div class="text-center mt-2 mb-2">
            <img src="{{ asset ('sources/testi-icon.png') }}" class="img-fluid" alt="" style="width: 60px; height: 60px;" />

           </div>
        </header>

         <!-- ======= Testimonials Section ======= -->
         <section id="testimonials" class="testimonials">
          <div class="container" data-aos="zoom-in">

        <div class="row justify-content-center">
          <div class="col-lg-8">

            <div class="owl-carousel testimonials-carousel">

              <div class="testimonial-item">
                <p>
                  "Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam, risus at semper."
                </p><br>
                <h4>- Saul Goodman, Ceo &amp; Founder</h4>
              </div>

              <div class="testimonial-item">
                <p>
                  "Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa."
                </p><br>
                <h4>- Sara Wilsson, Designer</h4>
              </div>

              <div class="testimonial-item">
                <p>
                  "Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim."
                </p><br> 
                <h4>- Jena Karlis, Store Owner</h4>
              </div>

              <div class="testimonial-item">
                <p>
                  "Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam."
                </p><br>
                <h4>- Matt Brandon, Freelancer</h4>
              </div>

            </div>

          </div>
        </div>
      </div>
      </section>


        {{-- <div class="col-lg-12 col-md-12">
          <div class="row">

            <div class="col-lg-5 col-md-6">
              <div class="about-img wow fadeInLeft">
                <img src="{{asset('sources/waki-tower.jpg')}}" alt="" class="img-fluid">
              </div>
            </div>

            <div class="col-lg-7 col-md-6">
              <div class="why-us-content">

                <div class="features wow bounceInUp clearfix" style="margin-top:30px;">
                  <i class="fa fa-diamond" style="color: #f058dc;"></i>
                  @if(Utils::$lang=='id')
                    <h4>Visi WAKi</h4>

                    <p>Taklukkan Asia Tenggara, Pikirkan Secara Global. Mempromosikan perangkat sehat WAKi yang sangat baik untuk setiap keluarga, menuju kehidupan yang lebih sehat.</p>
                  @elseif(Utils::$lang=='eng')
                    <h4>WAKi's Vision</h4>

                    <p>Conquer South East Asia, Think Globally; Promoting the WAKi’s excellent healthy devices to every family, towards a healthier life.</p>
                  @endif
                </div>

                <div class="features wow bounceInUp clearfix">
                  <i class="fa fa-object-group" style="color: #ffb774;"></i>
                  @if(Utils::$lang=='id')
                    <h4>Misi WAKi</h4>

                    <p>Membawa pelanggan WAKian dan WAKi, menuju kehidupan yang lebih baik.</p>
                  @elseif(Utils::$lang=='eng')
                    <h4>WAKi's Mission</h4>

                    <p>Bringing WAKian and WAKi’s customers, towards a better life!</p>
                  @endif
                </div>

                <div class="features wow bounceInUp clearfix">
                  <i class="fa fa-pie-chart" style="color: #589af1;"></i>
                  @if(Utils::$lang=='id')
                    <h4>Objektif WAKi</h4>

                    <p>Secara terus-menerus menciptakan nilai-nilai bagi para pemangku kepentingan.</p>
                  @elseif(Utils::$lang=='eng')
                    <h4>WAKi's Objective</h4>

                    <p>Continuously creating values for stakeholders.</p>
                  @endif
                </div>

                <div class="features wow bounceInUp clearfix">
                  <i class="fa fa-arrows" style="color: #58f19c;"></i>
                  @if(Utils::$lang=='id')
                    <h4>Pemosisian WAKi</h4>

                    <p>WAKi = Merek perangkat sehat yang sangat baik (termasuk perangkat potensial yang sehat, perangkat udara sehat, perangkat air sehat, perangkat pijat sehat, dan peralatan listrik rumah tangga yang sehat).</p>
                  @elseif(Utils::$lang=='eng')
                    <h4>WAKi's Positioning</h4>

                    <p>WAKi = Brand of excellent healthy devices (including healthy potential devices, healthy air devices, healthy water devices, healthy massage devices and healthy electrical home appliances)</p>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div> --}}
      </div>
    </div>

    <div class="container">
      <div class="row counters"></div>
    </div>
  </section>


  <!--==========================Our Business Section============================-->
  <section id="business" class="business">
    <div class="container" data-aos="zoom-in">

      <header class="section-header text-center">
        <h2>Bisnis Kami</h2>
      </header>

      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="row card-group">
            <div class="col-lg-3 col-md-3 col-sm-3">

              <div class="card-business text-center">
                <img class="card-img-top" src="" />
                <div class="card-body">
                  <div class="card-title">
                    WAKimart
                  </div>
                  <a href="" class="link-business">Lihat</a>
                </div>
  
              </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
              <div class="card-business text-center">
                <img class="card-img-top" src="" />
                <div class="card-body">
                  <div class="card-title">
                    WAKishop
                  </div>
                  <a href="" class="link-business">Lihat</a>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
              <div class="card-business text-center">
                <img class="card-img-top" src="" />
                <div class="card-body">
                  <div class="card-title">
                    WAKi F&B
                  </div>
                  <a href="" class="link-business">Lihat</a>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
              <div class="card-business text-center">
                <img class="card-img-top" src="" />
                <div class="card-body">
                  <div class="card-title">
                    WAKi 3D Printing
                  </div>
                  <a href="" class="link-business">Lihat</a>
                </div>
              </div>
            </div>
            
            
            

        </div>
      </div>

    </div>
  </section>

  <!-- #business -->

  <!--==========================Portfolio Section============================-->
{{--   <section id="portfolio" class="section-bg">
    <div class="container">

      <header class="section-header">
        @if(Utils::$lang=='id')
        <h3 class="section-title">Galeri Kami</h3>
        @elseif(Utils::$lang=='eng')
        <h3 class="section-title">Our Gallery</h3>
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
      </div> --}}

   {{--    <div class="row portfolio-container">
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
            <!--  -->
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
        @endfor --}}

     {{--    @for($v = 0; $v < sizeof($videos) ; $v++)
        <div class="col-lg-4 col-md-6 portfolio-item filter-video">
          <div class="portfolio-wrap2">
            <h5 class="portfolio-video-title">{{$videos[$v]['title']}}</h5>
            <iframe width="100%" height="auto" position="relative" src="{{$videos[$v]['url']}}" frameborder="0" allowfullscreen=""></iframe>
          </div>
        </div>
        @endfor

      </div>
    </div>
  </section> --}}<!-- #portfolio -->

  <!--==========================Team Section============================-->
 {{--  <section id="team" class="section-bg">
    <div class="container">
      <div class="section-header">
        <h3>World Peace</h3>
      </div>

      <div class="col-lg-12 col-md-12">

        <div class="col-lg-12 col-md-12 wow fadeInUp" style="width: 60%; margin:auto;">
          <div class="member">
            <img src="{{asset('sources/Waki-Chairman.jpg')}}" class="img-fluid" alt="">
            <div class="member-info">
              <div class="member-info-content">
                <h4>Teo Choo Guan</h4>
                <span>Chairman of WAKi International Group</span>
                <!-- <div class="social">
                  <a href=""><i class="fa fa-twitter"></i></a>
                  <a href=""><i class="fa fa-facebook"></i></a>
                  <a href=""><i class="fa fa-google-plus"></i></a>
                  <a href=""><i class="fa fa-linkedin"></i></a>
                </div> -->
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-12 col-md-12">
          <div class="section-header">
            @if(Utils::$lang=='id')
              <h3>Terima Kasih</h3>
              <p style="text-align: justify; font-weight: normal; width: 80%;">Pertama-tama, saya merasa sangat bersyukur & berterima kasih karena dianugerahi "Sweden World Peace Award 2019" di Blue Hall of Balai Kota Stockholm, Swedia. Saya hanya ingin mengucapkan terima kasih sekali lagi untuk semua ucapan selamat dan harapan baik dari anggota keluarga, kerabat, teman, serta mitra bisnis, dewan direksi, klien, dan semua staf saya Grup Internasional Waki.
              <br>
              <br>
              Selanjutnya, saya ingin mengucapkan terima kasih kepada media Malaysia Sin Chew Daily, China Daily, New Straits Times, Berita Harian, dan total 2.745 laporan surat kabar dan media sosial di seluruh dunia meliput berita gembira ini. Selain itu, terima kasih total 1.051 media sosial dan media video dari seluruh dunia menerbitkan berita selamat terutama di layar lebar Nasdaq di New York Times Square. Ini menunjukkan kekuatan perdamaian di persimpangan dunia.
              <br>
              <br>
              Terakhir tapi bukan yang akhir, saya memang merasa terhormat untuk menjadi penerima "Swedia World Peace Prize 2019", ini menandai sebuah tonggak baru dalam hidup saya. Saya siap menghadapi lebih banyak tantangan dan saya akan selalu bekerja keras untuk membuat sisa hidup saya lebih bermakna. Terima kasih.
              <br>
              <br>
              Semoga Perdamaian dan Cinta Dunia menang di Seluruh Dunia!
              </p>
            @elseif(Utils::$lang=='eng')
              <h3>Thank You</h3>
              <p style="text-align: justify; font-weight: normal; width: 80%;">First of all, I feel so thankful & grateful to be awarded the "Sweden World Peace Award 2019” in the Blue Hall of
              Stockholm City Hall, Sweden. I just want to say thank you again for all the congratulations wishes and well-wishes
              from my family members, relatives, friends as well as business partners, boards of directors, clients and all staffs
              Waki International Group.
              <br>
              <br>
              Next, I would like to thank the Malaysian media Sin Chew Daily, China Daily, New Straits Times, Berita Harian,
              and a total of 2,745 newspapers reports and social medias worldwide covering this happy news. In addition, thanks to
              a total of 1,051 social medias and video medias from worldwide publishing the news of congratulations especially on
              the Nasdaq big screen in New York's Times Square. It shows the power of peace at the crossroads of the world.
              <br>
              <br>
              Last but not least, I am indeed honoured to be a recipient of the "Sweden World Peace Prize 2019”, this marks a
              new milestone of my life. I am ready to face more challenges and I will always work hard to make the rest of my life
              more meaningful. Thank You.
              <br>
              <br>
              May World Peace and Love prevail Around the World!
              </p>
            @endif
          </div>
        </div>


      </div>

    </div>
  </section> --}}<!-- #team -->
</main>

@endsection

@section("script")
<script>
  // Testimonials carousel (uses the Owl Carousel library)
  $(".testimonials-carousel").owlCarousel({
    autoplay: true,
    dots: true,
    loop: true,
    items: 1
  });
</script>
@endsection
