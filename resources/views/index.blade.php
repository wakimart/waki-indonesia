@extends('layouts.template')

@section('content')
<!--==========================Intro Section============================-->
<section id="intro" class="clearfix">
  <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img class="d-block w-100" src="{{asset('sources/waki-carousel1.jpg')}}" alt="First slide">
      </div>
      <div class="carousel-item">
        <img class="d-block w-100" src="{{asset('sources/waki-carousel2.jpg')}}" alt="Second slide">
      </div>
      <div class="carousel-item">
        <img class="d-block w-100" src="{{asset('sources/waki-carousel3.jpg')}}" alt="Third slide">
      </div>
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
        <h2>Selamat datang di WAKi International Group</h2>
        <p>Sejak 1995, Bapak Teo Choo Guan telah mendirikan "Metrowealth International Group". Sejak itu, Perusahaan telah dengan cepat memperluas jaringan bisnis internasional-nya, dan merambah ke negara-negara Asia Pasifik dan Eropa. Dimulai sejak tahun 1995, Tuan Teo Choo Guan telah menjabat sebagai Ketua Eksekutif Grup.</p>
        <p>Pada tahun 2008, untuk melakukan strategi ulang dalam pemasaran global, Perusahaan telah mengubah nama merek semua produk menjadi "WAKi". Oleh karena itu, Perusahaan berganti nama menjadi "WAKi International Group". Saat ini, "WAKi" telah menjadi merek populer untuk produk kesehatan. Untuk mengembangkan cetak biru bisnis, WAKi telah mendirikan cabang dan perusahaan patungannya di Malaysia, Indonesia, Filipina, Thailand, Singapura, Brunei, Vietnam, Kamboja, Myanmar, Hongkong, Jepang, Korea, dan Cina. WAKi International Headquarter berlokasi di Gedung WAKi, Kuala Lumpur, Malaysia.</p>
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
              <h2>Tentang Kami</h2>
              <h3>WAKI INTERNATIONAL GROUP</h3>
              <p>Sejak 1995, Bapak Teo Choo Guan telah mendirikan "Metrowealth International Group". Sejak itu, Perusahaan telah dengan cepat memperluas jaringan bisnis internasional-nya, dan merambah ke negara-negara Asia Pasifik dan Eropa. Dimulai sejak tahun 1995 Tuan Teo Choo Guan telah menjabat sebagai Ketua Eksekutif Grup.
                <br><br>
                Pada tahun 2008, untuk melakukan strategi ulang dalam pemasaran global, Perusahaan telah mengubah nama merek semua produk menjadi "WAKi". Oleh karena itu, Perusahaan berganti nama menjadi "WAKi International Group". Saat ini, "WAKi" telah menjadi merek populer untuk produk kesehatan. Untuk mengembangkan cetak biru bisnis, WAKi telah mendirikan cabang dan perusahaan patungannya di Malaysia, Indonesia, Filipina, Thailand, Singapura, Brunei, Vietnam, Kamboja, Myanmar, Hongkong, Jepang, Korea, dan Cina. WAKi International Headquarter berlokasi di Gedung WAKi, Kuala Lumpur, Malaysia.</p>
              </p>
            </div>
          </div>
          <div class="col-lg-12 col-md-12">
            <div class="about-content" style="padding-bottom: 40px">
              <h3>SIFAT BISNIS WAKI</h3>
              <ul>
                <li><i class="ion-android-checkmark-circle"></i> Di negara-negara ASEAN, pemasaran produk perawatan kesehatan merek WAKi melalui toko-toko WAKi dan pameran WAKi.</li>
                <li><i class="ion-android-checkmark-circle"></i> Di Hong Kong, China, mengekspor perangkat listrik rumah tangga ke seluruh dunia melalui Waki Trading Company.</li>
                <li><i class="ion-android-checkmark-circle"></i> Di negara-negara Asia Tenggara, investasi properti komersial melalui perusahaan WAKi Holdings.</li>
                <li><i class="ion-android-checkmark-circle"></i> Di Malaysia, mengembangkan proyek perumahan melalui Perusahaan konstruksi WAKi.</li>
                <li><i class="ion-android-checkmark-circle"></i> Di Malaysia dan China, berinvestasi dalam industri makanan & minuman melalui perusahaan WAKi F&B.</li>
              </ul>
            </div>
            <div class="row">
              <div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp" data-wow-duration="1.4s" style="margin: 0 0 30px 0;">
                <div class="box col-md-12">
                  <div class="icon" style="background: #eafde7;"><h1 class="WAKI">W</h1></div>
                  <h4 class="title" style="color: #00844a;font-weight: bolder;">Willingness</h4>
                  <p class="description">Kami percaya bahwa <i>Willingness</i> menguasai segalanya.</p>
                </div>
              </div>
              <div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp" data-wow-delay="0.1s" data-wow-duration="1.4s" style="margin: 0 0 30px 0;">
                <div class="box col-md-12">
                  <div class="icon" style="background: #eafde7;"><h1 class="WAKI">A</h1></div>
                  <h4 class="title" style="color: #00844a;font-weight: bolder;">Action</h4>
                  <p class="description">Kami percaya bahwa <i>Action</i> adalah kekuatan.</p>
                </div>
              </div>
              <div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp" data-wow-delay="0.2s" data-wow-duration="1.4s" style="margin: 0 0 30px 0;">
                <div class="box col-md-12">
                  <div class="icon" style="background: #eafde7;"><h1 class="WAKI">K</h1></div>
                  <h4 class="title" style="color: #00844a;font-weight: bolder;">Knowledge</h4>
                  <p class="description">Kami percaya bahwa <i>Knowledge</i> adalah bagian penting dari kesuksesan.</p>
                </div>
              </div>
              <div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp" data-wow-delay="0.3s" data-wow-duration="1.4s" style="margin: 0 0 30px 0;">
                <div class="box col-md-12">
                  <div class="icon" style="background: #eafde7;"><h1 class="WAKI" style="color: #fcb813;">i</h1></div>
                  <h4 class="title" style="color: #fcb813;font-weight: bolder;">Innovation</h4>
                  <p class="description">Kami percaya bahwa <i>Innovation</i> akan membawa peluang tak terbatas kepada kita.</p>
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
          <h3 style="margin-bottom: 10px;">Nilai Inti WAKi</h3>
          <h4 style="font-size: 16px;color: #555186; text-align: center; margin-bottom: 20px;">Bukan Janji, tapi Pasti!</h4>
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
                  <h4>Visi WAKi</h4>
                  <p>Taklukkan Asia Tenggara, Pikirkan Secara Global. Mempromosikan perangkat sehat WAKi yang sangat baik untuk setiap keluarga, menuju kehidupan yang lebih sehat.</p>
                </div>

                <div class="features wow bounceInUp clearfix">
                  <i class="fa fa-object-group" style="color: #ffb774;"></i>
                  <h4>Misi WAKi</h4>
                  <p>Membawa pelanggan WAKian dan WAKi, menuju kehidupan yang lebih baik.</p>
                </div>

                <div class="features wow bounceInUp clearfix">
                  <i class="fa fa-pie-chart" style="color: #589af1;"></i>
                  <h4>Objektif WAKi</h4>
                  <p>Secara terus-menerus menciptakan nilai-nilai bagi para pemangku kepentingan.</p>
                </div>

                <div class="features wow bounceInUp clearfix">
                  <i class="fa fa-arrows" style="color: #58f19c;"></i>
                  <h4>Pemosisian WAKi</h4>
                  <p>WAKi = Merek perangkat sehat yang sangat baik (termasuk perangkat potensial yang sehat, perangkat udara sehat, perangkat air sehat, perangkat pijat sehat, dan peralatan listrik rumah tangga yang sehat).</p>
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
        <h3 class="section-title">Galeri Kami</h3>
      </header>

      <div class="row">
        <div class="col-lg-12">
          <ul id="portfolio-flters">
            <li data-filter=".filter-photo" class="filter-active">Foto</li>
            <li data-filter=".filter-video" >Video</li>
          </ul>
        </div>
      </div>

      <div class="row portfolio-container">

        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo1.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 1</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo1.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo2.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 2</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo2.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo3.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 3</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo3.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo4.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 4</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo4.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo5.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 5</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo5.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo6.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 6</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo6.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo7.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 7</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo7.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo8.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 8</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo8.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo9.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 9</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo9.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo10.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 10</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo10.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo11.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 11</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo11.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo12.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 12</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo12.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo13.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 13</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo13.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo14.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 14</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo14.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo15.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 15</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo15.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo16.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 16</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo16.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo17.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 17</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo17.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo18.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 18</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo18.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo19.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 19</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo19.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo20.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 20</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo20.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo21.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 21</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo21.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo22.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 22</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo22.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo23.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 23</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo23.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo24.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 24</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo24.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo25.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 25</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo25.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo26.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 26</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo26.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo27.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 27</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo27.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo28.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 28</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo28.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-photo">
          <div class="portfolio-wrap">
            <img src="{{asset('sources/portfolio/photo29.jpg')}}" class="img-fluid" alt="">
            <div class="portfolio-info">
              <h4><a href="#">Photo 29</a></h4>
              <p>App</p>
              <div>
                <a href="{{asset('sources/portfolio/photo29.jpg')}}" data-lightbox="portfolio" data-title="App 1" class="link-preview" title="Preview"><i class="ion ion-eye"></i></a>
              </div>
            </div>
          </div>
        </div>


        <div class="col-lg-4 col-md-6 portfolio-item filter-video">
          <div class="portfolio-wrap2">
            <h5 class="portfolio-video-title">The Opening of WAKi Relic Museum</h5>
            <iframe width="100%" height="auto" position="relative" src="https://www.youtube.com/embed/AlodzGToyG4" frameborder="0" allowfullscreen=""></iframe>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-video">
          <div class="portfolio-wrap2">
            <h5 class="portfolio-video-title">World Peace Awards 2019, Stockholm City Hall, Sweden</h5>
            <iframe width="100%" height="auto" position="relative" src="https://www.youtube.com/embed/dDCFtdrinbk" frameborder="0" allowfullscreen=""></iframe>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-video">
          <div class="portfolio-wrap2">
            <h5 class="portfolio-video-title">WAKi Indonesia – Ramadhan Diary 2019</h5>
            <iframe width="100%" height="auto" position="relative" src="https://www.youtube.com/embed/4w8Z1boMAKo" frameborder="0" allowfullscreen=""></iframe>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-video">
          <div class="portfolio-wrap2">
            <h5 class="portfolio-video-title">WAKi Indonesia Present – Mahakarya 2019</h5>
            <iframe width="100%" height="auto" position="relative" src="https://www.youtube.com/embed/hoAchdTvpY8" frameborder="0" allowfullscreen=""></iframe>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-video">
          <div class="portfolio-wrap2">
            <h5 class="portfolio-video-title">Iftar with Anak Rumah Penyayang Ulin Nuha & Al Munirah</h5>
            <iframe width="100%" height="auto" position="relative" src="https://www.youtube.com/embed/W-pbLd9KZtk" frameborder="0" allowfullscreen=""></iframe>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-video">
          <div class="portfolio-wrap2">
            <h5 class="portfolio-video-title">Iftar with Anak Rumah Amal Al Firdaus</h5>
            <iframe width="100%" height="auto" position="relative" src="https://www.youtube.com/embed/hoeYyGmWnNc" frameborder="0" allowfullscreen=""></iframe>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-video">
          <div class="portfolio-wrap2">
            <h5 class="portfolio-video-title">Iftar & Teraweeh Prayers with Anak Madrasah Tahfiz Sg Buluh</h5>
            <iframe width="100%" height="auto" position="relative" src="https://www.youtube.com/embed/BHNm9dl4f0Q" frameborder="0" allowfullscreen=""></iframe>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-video">
          <div class="portfolio-wrap2">
            <h5 class="portfolio-video-title">WAKi FOOT MASSAGE MASTER II</h5>
            <iframe width="100%" height="auto" position="relative" src="https://www.youtube.com/embed/RwjWCVyT9-U" frameborder="0" allowfullscreen=""></iframe>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 portfolio-item filter-video">
          <div class="portfolio-wrap2">
            <h5 class="portfolio-video-title">WAKi International Group Corporate Song</h5>
            <iframe width="100%" height="auto" position="relative" src="https://www.youtube.com/embed/ZzKgr4_-BKw" frameborder="0" allowfullscreen=""></iframe>
          </div>
        </div>
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
          </div>
        </div>


      </div>

    </div>
  </section><!-- #team -->

  <!--==========================Clients Section============================-->
  <section id="clients" class="wow fadeInUp">
    <div class="container">

      <header class="section-header">
        <h3>Penghargaan Kami</h3>
        <img src="{{asset('sources/Awards_s.png')}}" alt="" class="img-fluid">
      </header>

    </div>
  </section><!-- #clients -->
</main>
@endsection