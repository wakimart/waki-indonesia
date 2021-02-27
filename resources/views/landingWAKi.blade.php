<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
<!-- Document Meta
============================================= -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!--IE Compatibility Meta-->
<meta name="author" content="zytheme" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="description" content="Html5 Construction Landing Page">
<link href="{{asset('sources/landing/favicon/favicon.ico')}}" rel="icon">

<!-- Fonts
============================================= -->
<link href="https://fonts.googleapis.com/css?family=Poppins:300i,400,400i,500,600,700,800%7CLora:400,400i,700,700i%7COpen+Sans:800" rel="stylesheet">

<!-- Stylesheets
============================================= -->
<link href="{{asset('css/landing/external.css')}}?ver=1.0" rel="stylesheet">
<link href="{{asset('css/landing/cons.css')}}" rel="stylesheet">
<link href="{{asset('css/landing/bootstrap.min.css')}}" rel="stylesheet">
<link href="{{asset('css/landing/style.css')}}?ver=1.0" rel="stylesheet">

<!-- Document Title
============================================= -->
<title>WAKI DIRUMAH AJA</title>
</head>
<style>
.WAKI{
  color: #00844a;
    font-size: 68px;
    line-height: 1;
    font-weight: bolder;
    -webkit-text-stroke: 1.5px black;
}
@media (min-width: 768px) {
  .mobadjust {
    display: flex;
    justify-content: center;
    align-items: center;
  }
}
</style>
<body class="body-scroll">
<!-- Document Wrapper
============================================= -->
<div id="wrapper" class="wrapper clearfix">

<!-- Hero #12
============================================= -->
<section id="hero" class="section hero hero-12 text-center bg-overlay bg-overlay-light bg-parallax fullscreen">
    <div class="bg-section">
        <img src="{{asset('sources/landing/hero/bgtop.jpg')}}" alt="Background" />
    </div>
    <div class="pos-vertical-center">
        <div class="container">

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="hero--content col-xs-12 col-sm-8 col-md-8">
                        <img src="https://waki-indonesia.co.id/sources/Logo Since.png" alt="" class="img-fluid logoWK">
                        <div class="clearfix"></div>
                        <h1 class="hero--headline">WAKi semakin dekat dengan anda, langsung ke RUMAH!</h1>
                        <p class="hero--bio">Dapatkan fisioterapi gratis<br> selama <strong>5 hari</strong> di rumah anda!</p>
                        <!-- <a href="#" class="btn btn--primary btn--rounded">Get A Quote</a> -->
                    </div>

                    <div class="form--top col-xs-12 col-sm-4 col-md-4">
                      <h5 class="form--title">
                        Isi Form Kami
                      </h5>
                      <p class="form--para">Dapatkan ekstra bonus voucher wakimart senilai 280.000 untuk 100 orang pendaftar pertama. <br>Promo berakhir dalam</p>
                      <div id="countdown" class="countdown py-4 form--title" style="margin: 0;"></div>
                      <form id="actionAdd" action="{{ route('store_registrationPromotion') }}" method="POST">
                          @csrf
                          <div class="col-xs-12 col-sm-12 col-md-12" style="padding: 0;">
                            <div class="input-group">
                              <span class="usericon">
                                  <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Nama Depan" required />
                              </span>
                            </div>
                            <div class="input-group">
                              <span class="usericon">
                                  <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Nama Akhir" required/>
                              </span>
                            </div>
                            <div class="input-group">
                              <span class="usericon2">
                                  <input type="text" class="form-control" id="address" name="address" placeholder="Alamat" required/>
                              </span>
                            </div>
                            <div class="input-group">
                              <span class="usericon3">
                                  <input type="email" class="form-control" id="email" name="email" placeholder="Email" required/>
                              </span>
                            </div>
                            <div class="input-group">
                              <span class="usericon4">
                                  <input type="phone" class="form-control" id="phone" name="phone" placeholder="No Telp" required/>
                              </span>
                            </div>
                          </div>

                          <div class="col-xs-12 col-sm-12 col-md-12" style="margin: auto;">
                            <button id="addRegistrationPromo" type="submit" class="btn btn--customwk btn--rounded">Daftar</button>
                          </div>
                      </form>
                    </div>
                </div>
                <!-- .col-md-10 end -->
            </div>
            <!-- .row end -->
        </div>
    </div>
    <!-- .container end -->
</section>
<!-- #hero12 end -->

<!-- Feature #1
============================================= -->
<section id="feature" class="section feature feature-1 bg-white text-center">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3">
                <div class="heading mb-70">
                    <h1 class="heading--title">Apa itu WAKi?</h2>
                    <p class="heading--subtitle">WAKi adalah perusahaan alat kesehatan yang mengunggulkan kualitas terjamin, untuk membantu keluarga menuju kehidupan yang lebih baik.
                      <br><span class="diff" style="font-family: 'Brush Script Std'; font-size: 1.5em;">Bukan Janji Tapi Pasti !</span></p>
                </div>
            </div>
        </div>
        <!-- .row end -->
        <div class="row">
            <!-- Panel #1 -->
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="feature-panel">
                    <div class="feature--icon">
                        <h1 class="WAKI">W</h1>
                    </div>
                    <div class="feature--content">
                        <h3>(Willingness)</h3>
                        <p>Kami percaya kemauan menaklukkan segalanya</p>
                    </div>
                </div>
                <!-- .feature-panel end -->
            </div>
            <!-- .col-md-4 end -->

            <!-- Panel #2 -->
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="feature-panel">
                    <div class="feature--icon">
                        <h1 class="WAKI">A</h1>
                    </div>
                    <div class="feature--content">
                        <h3>(Action)</h3>
                        <p>Kami percaya bahwa tindakan adalah kekuatan</p>
                    </div>
                </div>
                <!-- .feature-panel end -->
            </div>
            <!-- .col-md-4 end -->

            <!-- Panel #3 -->
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="feature-panel">
                    <div class="feature--icon">
                        <h1 class="WAKI">K</h1>
                    </div>
                    <div class="feature--content">
                        <h3>(Knowledge)</h3>
                        <p>Kami percaya bahwa pengetahuan adalah bagian penting dari keberhasilan</p>
                    </div>
                </div>
                <!-- .feature-panel end -->
            </div>
            <!-- .col-md-4 end -->

            <!-- Panel #4 -->
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="feature-panel">
                    <div class="feature--icon">
                        <h1 class="WAKI" style="color: #fcb813;">i</h1>
                    </div>
                    <div class="feature--content">
                        <h3>(Innovation)</h3>
                        <p>Kami percaya bahwa inovasi akan membawa peluang yang tak terbatas kepada kami</p>
                    </div>
                </div>
                <!-- .feature-panel end -->
            </div>
            <!-- .col-md-4 end -->
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>
<!-- #feature  end -->

<!-- cta2
============================================= -->
<section id="cta2" class="section cta cta-2 bg-theme">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mobadjust">
                <div class="col-xs-12 col-sm-7 col-md-7 heading" style="text-align: center;">
                    <h2 class="heading--title" style="margin: 10px 0;">SEHAT DI RUMAH AJA!</h2>
                </div>
                <div class="col-xs-12 col-sm-5 col-md-5" style="text-align: center;">
                    <a class="btn btn--customwk btn--rounded" href="#">Daftar Sekarang</a>
                </div>
            </div>
            <!-- .col-md-8 end -->
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>

<!-- Feature #2
============================================= -->
<section id="feature" class="section feature feature-1 bg-white text-center">
  <div class="bg-section">
      <img src="{{asset('sources/landing/hero/bgmid.jpg')}}" alt="Background" />
  </div>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3">
                <div class="heading mb-70">
                    <h1 class="heading--title">Apa yang anda dapatkan selama 5 hari?</h2>
                    <p class="heading--subtitle">Kami membantu anda untuk merawat kesehatan keluarga anda dengan berbagai jagkauan mulai dari pemurni udara, air minum, hingga pemijatan tubuh teknologi modern.</p>
                </div>
            </div>
        </div>
        <!-- .row end -->
        <div class="row">
            <!-- Panel #1 -->
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="feature-panel">
                    <div class="feature--icon">
                      <img src="{{asset('sources/landing/advantage/Asset1.png')}}" />
                    </div>
                    <div class="feature--content">
                        <p>Bisa konsultasi kesehatan bersama tim</p>
                    </div>
                </div>
                <!-- .feature-panel end -->
            </div>
            <!-- .col-md-4 end -->

            <!-- Panel #2 -->
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="feature-panel">
                    <div class="feature--icon">
                        <img src="{{asset('sources/landing/advantage/Asset2.png')}}" />
                    </div>
                    <div class="feature--content">
                        <p>Mendapatkan fasilitas kesehatan sesuai kebutuhan</p>
                    </div>
                </div>
                <!-- .feature-panel end -->
            </div>
            <!-- .col-md-4 end -->

            <!-- Panel #3 -->
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="feature-panel">
                    <div class="feature--icon">
                        <img src="{{asset('sources/landing/advantage/Asset3.png')}}" />
                    </div>
                    <div class="feature--content">
                        <p>Mendapatkan informasi beserta fasilitas WAKi dan WAKimart</p>
                    </div>
                </div>
                <!-- .feature-panel end -->
            </div>
            <!-- .col-md-4 end -->

            <!-- Panel #4 -->
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="feature-panel">
                    <div class="feature--icon">
                        <img src="{{asset('sources/landing/advantage/Asset4.png')}}" />
                    </div>
                    <div class="feature--content">
                        <p>Bisa mendapatkan voucher gratis di WAKimart</p>
                    </div>
                </div>
                <!-- .feature-panel end -->
            </div>
            <!-- .col-md-4 end -->
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>
<!-- #feature  end -->

<!-- cta3
============================================= -->
<section id="cta2" class="section cta cta-2 bg-theme">
    <div class="container">
        <div class="col-xs-12 col-sm-12 col-md-12 mobadjust" style="text-align: center">
            <div class="col-xs-12 col-sm-12 col-md-12 heading">
                <h2 class="heading--title" style="margin: 10px 0;">Mulai Hidup Sehat<br> Sekarang Juga!</h2>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <a class="btn btn--customwk btn--rounded" href="#">Daftar Sekarang</a>
            </div>
        </div>
    </div>
    <!-- .container end -->
</section>

<!-- Footer #9
============================================= -->
<footer id="footer9" class="footer footer-5 footer-8 footer-9 bg-white text-center-xs pt-0 pb-0">

    <!-- .footer-widget end -->
    <div class="footer-bar">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-4">
                  <div class="logoWKbawahcon">
                    <img src="https://waki-indonesia.co.id/sources/Logo Since.png" alt="" class="img-fluid logoWKbawah">
                  </div>
                </div>
                <!-- .col-md-6 end -->
                <div class="col-xs-12 col-sm-8 col-md-8">

                    <div class="footer--content">
                        <p>WAKi Darmo Park 1, Blok 2B no 1-6, Jalan Mayjend Sungkono, Surabaya 60225.</p>
                    </div>
                    <div class="widget--social">
                        <a class="website" href="#">
                          <i class="fa fa-globe"></i>
                          <p>waki-indonesia.co.id</p>
                        </a>
                        <a class="whatsApp" href="#">
                          <i class="fa fa-whatsapp"></i>
                          <p>081234511881</p>
                         </a>
                        <a class="email" href="#">
                          <i class="fa fa-envelope-o"></i>
                          <p>wakimart.id@gmail.com</p>
                        </a>
                        <!-- <a class="instagram" href="#">
                            <i class="fa fa-instagram"></i>
                        </a> -->
                    </div>
                </div>
                <!-- .col-md-6 end -->
            </div>
            <!-- .row end -->
        </div>
        <!-- .container end -->
    </div>
    <!-- .footer bar end -->
</footer>
<!-- #footer9 end -->

<!-- modal success -->
<div class="modal fade" role="dialog" tabindex="-1" id="modal-Success">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Input Success</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="txt-success">Appointment telah berhasil dibuat. Terima kasih telah menggunakan layanan kami, petugas kami akan segera menghubungi Anda.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-gradient-primary" type="button" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

</div>
<!-- #wrapper end -->



<!-- Footer Scripts
============================================= -->
<script src="{{asset('js/landing/jquery-2.2.4.min.js')}}"></script>
<script src="{{asset('js/landing/plugins.js')}}"></script>
<script src="{{asset('js/landing/functions.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.countdown/2.2.0/jquery.countdown.min.js"></script>
<script>
// set the date we're counting down to
var target_date = new Date('Feb 28, 2021 00:00:00').getTime();

// variables for time units
var days, hours, minutes, seconds;

// get tag element
var countdown = document.getElementById('countdown');

// update the tag with id "countdown" every 1 second
setInterval(function () {

    // find the amount of "seconds" between now and target
    var current_date = new Date().getTime();
    var seconds_left = (target_date - current_date) / 1000;

    // do some time calculations
    days = parseInt(seconds_left / 86400);
    seconds_left = seconds_left % 86400;

    hours = parseInt(seconds_left / 3600);
    seconds_left = seconds_left % 3600;

    minutes = parseInt(seconds_left / 60);
    seconds = parseInt(seconds_left % 60);

    // format countdown string + set tag value
    countdown.innerHTML = ''
      + '<span class="h1 font-weight-bold">' + days +  ' </span><span class="separator">: </span>'
      + '<span class="h1 font-weight-bold">' + (hours<10 ? "0" + hours : hours) + ' </span><span class="separator">: </span>'
      + '<span class="h1 font-weight-bold">' + (minutes<10 ? "0" + minutes : minutes) + ' </span><span class="separator">: </span>'
      + '<span class="h1 font-weight-bold">' + (seconds<10 ? "0" + seconds : seconds) + ' </span>';
}, 1000);
</script>

@if(Session::has('success_registration'))
    <script type="text/javascript">
        $("#modal-Success").modal("show");
    </script>
    @php
        Session::forget('success_registration');
    @endphp
@endif

</body>

</html>
