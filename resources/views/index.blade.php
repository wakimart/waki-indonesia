@extends('layouts.template')

@section('content')
<!--==========================Intro Section============================-->
<section id="intro" class="clearfix">
  <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
    @php
      $banners = json_decode($banners['image']);
      $defaultImg = asset('sources/banners/');

      $count_banner = sizeof($banners);
    @endphp
    <div class="carousel-inner">
      @for($i = 0; $i < $count_banner; $i++)
        <div class="carousel-item active">
          <img class="d-block w-100" src="{{$defaultImg.'/'.$banners[$i]->img}}">
        </div>
      @endfor
      <!-- <div class="carousel-item">
        <img class="d-block w-100" src="{{asset('sources/waki-carousel2.jpg')}}" alt="Second slide">
      </div>
      <div class="carousel-item">
        <img class="d-block w-100" src="{{asset('sources/waki-carousel3.jpg')}}" alt="Third slide">
      </div> -->
    </div>
    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>

  <div class="container d-flex h-100">
    <div class="col-lg-12 col-md-12">
      <div class="intro-content">
        <br>

        @if(Utils::$lang=='id')
          <h2>Selamat datang di WAKi International Group</h2>

          <p>Sejak 1995, Bapak Teo Choo Guan telah mendirikan "Metrowealth International Group". Sejak itu, Perusahaan telah dengan cepat memperluas jaringan bisnis internasional-nya, dan merambah ke negara-negara Asia Pasifik dan Eropa. Dimulai sejak tahun 1995, Tuan Teo Choo Guan telah menjabat sebagai Ketua Eksekutif Grup.</p>
          <p>Pada tahun 2008, untuk melakukan strategi ulang dalam pemasaran global, Perusahaan telah mengubah nama merek semua produk menjadi "WAKi". Oleh karena itu, Perusahaan berganti nama menjadi "WAKi International Group". Saat ini, "WAKi" telah menjadi merek populer untuk produk kesehatan. Untuk mengembangkan cetak biru bisnis, WAKi telah mendirikan cabang dan perusahaan patungannya di Malaysia, Indonesia, Filipina, Thailand, Singapura, Brunei, Vietnam, Kamboja, Myanmar, Hongkong, Jepang, Korea, dan Cina. WAKi International Headquarter berlokasi di Gedung WAKi, Kuala Lumpur, Malaysia.</p>
        @elseif(Utils::$lang=='eng')
          <h2>Welcome to WAKi International Group</h2>

          <p>Since 1995, Mr. Teo Choo Guan has established the “Metrowealth International Group”. Since then, the Company has rapidly expanded its international business network, and venturing into Asia Pacific countries and Europe countries. Started from year 1995, Mr. Teo Choo Guan has been serving as Group Executive Chairman.</p>
          <p>In year 2008, in order to restrategize in global marketing, the Company has changed all products’ brand name to “WAKi”. Therefore, the Company renamed as “WAKi International Group”. Nowadays, “WAKi” has become a popular brand for health products. For expanding business blueprint, WAKi has established it’s branches and joint-venture companies in Malaysia, Indonesia, Philippines, Thailand, Singapore, Brunei, Vietnam, Cambodia, Myanmar, Hongkong, Japan, Korea and China. WAKi International Headquarter is located in WAKi Building, Kuala Lumpur, Malaysia.</p>
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
  </div>
</section><!-- #intro -->

<main id="main">
  <!--==========================About Us Section============================-->
  <section id="about" class="section-bg" style="position: relative; z-index: -1;">
    <div class="container">
      <div class="col-lg-12 col-md-12">
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

                <p>Sejak 1995, Bapak Teo Choo Guan telah mendirikan "Metrowealth International Group". Sejak itu, Perusahaan telah dengan cepat memperluas jaringan bisnis internasional-nya, dan merambah ke negara-negara Asia Pasifik dan Eropa. Dimulai sejak tahun 1995 Tuan Teo Choo Guan telah menjabat sebagai Ketua Eksekutif Grup.
                  <br><br>
                  Pada tahun 2008, untuk melakukan strategi ulang dalam pemasaran global, Perusahaan telah mengubah nama merek semua produk menjadi "WAKi". Oleh karena itu, Perusahaan berganti nama menjadi "WAKi International Group". Saat ini, "WAKi" telah menjadi merek populer untuk produk kesehatan. Untuk mengembangkan cetak biru bisnis, WAKi telah mendirikan cabang dan perusahaan patungannya di Malaysia, Indonesia, Filipina, Thailand, Singapura, Brunei, Vietnam, Kamboja, Myanmar, Hongkong, Jepang, Korea, dan Cina. WAKi International Headquarter berlokasi di Gedung WAKi, Kuala Lumpur, Malaysia.</p>
                </p>
              @elseif(Utils::$lang=='eng')
                <h2>About Us</h2>

                <h3>WAKI INTERNATIONAL GROUP</h3>

                <p>Since 1995, Mr. Teo Choo Guan has established the “Metrowealth International Group”. Since then, the Company has rapidly expanded its international business network, and venturing into Asia Pacific countries and Europe countries. Started from year 1995, Mr. Teo Choo Guan has been serving as Group Executive Chairman.
                  <br><br>
                  In year 2008, in order to restrategize in global marketing, the Company has changed all products’ brand name to “WAKi”. Therefore, the Company renamed as “WAKi International Group”. Nowadays, “WAKi” has become a popular brand for health products. For expanding business blueprint, WAKi has established it’s branches and joint-venture companies in Malaysia, Indonesia, Philippines, Thailand, Singapore, Brunei, Vietnam, Cambodia, Myanmar, Hongkong, Japan, Korea and China. WAKi International Headquarter is located in WAKi Building, Kuala Lumpur, Malaysia.</p>
                </p>
              @endif
            </div>
          </div>
          <div class="col-lg-12 col-md-12">
            <div class="about-content" style="padding-bottom: 40px">
              @if(Utils::$lang=='id')
                <h3>SIFAT BISNIS WAKI</h3>

                <ul>
                  <li><i class="ion-android-checkmark-circle"></i> Di negara-negara ASEAN, pemasaran produk perawatan kesehatan merek WAKi melalui toko-toko WAKi dan pameran WAKi.</li>
                  <li><i class="ion-android-checkmark-circle"></i> Di Hong Kong, China, mengekspor perangkat listrik rumah tangga ke seluruh dunia melalui Waki Trading Company.</li>
                  <li><i class="ion-android-checkmark-circle"></i> Di negara-negara Asia Tenggara, investasi properti komersial melalui perusahaan WAKi Holdings.</li>
                  <li><i class="ion-android-checkmark-circle"></i> Di Malaysia, mengembangkan proyek perumahan melalui Perusahaan konstruksi WAKi.</li>
                  <li><i class="ion-android-checkmark-circle"></i> Di Malaysia dan China, berinvestasi dalam industri makanan & minuman melalui perusahaan WAKi F&B.</li>
                </ul>
              @elseif(Utils::$lang=='eng')
                <h3>WAKI’S NATURE OF BUSINESS</h3>

                <ul>
                  <li><i class="ion-android-checkmark-circle"></i> In ASEAN countries, marketing WAKi’s brand health care products through WAKi shops and WAKi exhibitions.</li>
                  <li><i class="ion-android-checkmark-circle"></i> In Hong Kong China, exporting household electrical devices to worldwide through Waki Trading Company.</li>
                  <li><i class="ion-android-checkmark-circle"></i> In South East Asia countries, investing commercial properties through WAKi Holdings company.</li>
                  <li><i class="ion-android-checkmark-circle"></i> In Malaysia, developing housing projects through WAKi construction Company.</li>
                  <li><i class="ion-android-checkmark-circle"></i> In Malaysia and China, investing in food & beverage industries through WAKi F&B company.</li>
                </ul>
              @endif
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

        </div><!--for row-->
      </div><!--for div-->
    </div>
  </section><!-- #about -->

  <!--==========================Why Us Section============================-->
  <section id="why-us" class="wow fadeIn">
    <div class="container">
      <div class="container-fluid">

        <header class="section-header">
          @if(Utils::$lang=='id')
            <h3 style="margin-bottom: 10px;">Nilai Inti WAKi</h3>

            <h4 style="font-size: 16px;color: #555186; text-align: center; margin-bottom: 20px;">Bukan Janji, tapi Pasti!</h4>
          @elseif(Utils::$lang=='eng')
            <h3 style="margin-bottom: 10px;">WAKi Value</h3>

            <h4 style="font-size: 16px;color: #555186; text-align: center; margin-bottom: 20px;">Not a promise, but sure!</h4>
          @endif
        </header>

        <div class="col-lg-12 col-md-12">
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
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row counters"></div>
    </div>
  </section>

  <!--==========================Portfolio Section============================-->
  <section id="portfolio" class="section-bg">
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
      </div>
     

      <div class="row portfolio-container">
        @php
          $photos = json_decode($galleries['photo']);
          $photoPath = asset('sources/portfolio/');
          $count_photo = sizeof($photos);

          $videos = json_decode($galleries['url_youtube']);
          $count_video = sizeof($videos);
        @endphp

        @for($x = 0; $x < $count_photo; $x++)
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

        @for($v = 0; $v < $count_video; $v++)
        <div class="col-lg-4 col-md-6 portfolio-item filter-video">
          <div class="portfolio-wrap2">
            <h5 class="portfolio-video-title">{{$videos[$v]->title}}</h5>
            <iframe width="100%" height="auto" position="relative" src="{{$videos[$v]->url}}" frameborder="0" allowfullscreen=""></iframe>
          </div>
        </div>
        @endfor
        
      </div>
    </div>
  </section><!-- #portfolio -->

  <!--==========================Team Section============================-->
  <section id="team" class="section-bg">
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
  </section><!-- #team -->

  <!--==========================Clients Section============================-->
  <section id="clients" class="wow fadeInUp">
    <div class="container">

      <header class="section-header">
        @if(Utils::$lang=='id')
        <h3>Penghargaan Kami</h3>
        @elseif(Utils::$lang=='eng')
        <h3>Our Awards</h3>
        @endif
        <img src="{{asset('sources/Awards_s.png')}}" alt="" class="img-fluid">
      </header>

    </div>
  </section><!-- #clients -->
</main>
@endsection