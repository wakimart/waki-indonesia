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
<link href="{{ asset('sources/landing/favicon/favicon.ico') }}" rel="icon">
<link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">
<!-- Fonts
============================================= -->
<link href="https://fonts.googleapis.com/css?family=Poppins:300i,400,400i,500,600,700,800%7CLora:400,400i,700,700i%7COpen+Sans:800" rel="stylesheet">

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-MTXL2H0TEK"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-MTXL2H0TEK');
</script>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MSXJGPP');</script>
<!-- End Google Tag Manager -->

<!-- Stylesheets
============================================= -->
<link href="{{ asset('css/landing/external.css') }}?ver=1.0" rel="stylesheet">
<link href="{{ asset('css/landing/cons.css') }}" rel="stylesheet">
<link href="{{ asset('css/landing/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/landing/style_landingwakimart.css') }}?ver=1.0" rel="stylesheet">
<style>
    .WAKI{
        color: #00844a;
        font-size: 68px;
        line-height: 1;
        font-weight: bolder;
        -webkit-text-stroke: 1.5px black;
    }

    .testimonial {
        background-color: white;
        text-align: center;
        padding: 20px 10px 40px;
        margin: 0 15px 10px;
        position: relative;
        border-radius: 1em;
    }

    .testimonial .icon {
        display: inline-block;
        font-size: 80px;
        color: #016d9b;
        margin-bottom: 20px;
        opacity: 0.6;
    }

    .testimonial .description {
        font-size: 14px;
        color: #777;
        text-align: justify;
        margin-bottom: 30px;
    }

    .testimonial .testimonial-content {
        width: 100%;
        left: 0;
        position: absolute;
    }

    .testimonial .pic {
        display: inline-block;
        border: 4px solid white;
        overflow: hidden;
        z-index: 1;
        position: relative;
    }

    .testimonial .pic img {
        width: 100%;
        height: auto;
    }

    .testimonial .name {
        font-size: 15px;
        font-weight: bold;
        color: #2d2d2d;
        text-transform: capitalize;
        margin: 10px 0 5px 0;
    }

    .testimonial .title {
        display: block;
        font-size: 14px;
        color: #ffd9b8;
    }

    .owl-controls {
        margin-top: 20px;
    }

    .owl-pagination {
        display: flex;
        justify-content: center;
    }

    .owl-page {
        height: 10px;
        width: 40px;
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 10%;
    }

    .owl-page:hover,
    .owl-page.active {
        background-color: rgba(255, 255, 255, 0.3);
    }

    .owl-page:not(first-item) {
        margin-left: 10px;
    }

    .owl-carousel {
        -ms-touch-action: none;
        touch-action: none;
    }

    .modal-window {
        position: fixed;
        background-color: rgba(255, 255, 255, 0.25);
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 999;
        visibility: hidden;
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s;
    }

    .modal-window:target {
        visibility: visible;
        opacity: 1;
        pointer-events: auto;
    }

    .modal-window > div {
        width: 400px;
        position: absolute;
        bottom: 2em;
        left: 2em;
        padding: 2em;
        background: white;
        border-radius: 1em;
        background-color: #ccffeb;
    }

    .modal-window header {
        font-weight: bold;
    }

    .modal-window h5 {
        font-size: 16px;
        margin: 0;
        font-weight: 600;
    }

    .modal-close {
        color: #aaa;
        line-height: 50px;
        position: absolute;
        right: 0;
        text-align: center;
        top: 0;
        width: 70px;
        text-decoration: none;
        font-weight: bold;
        font-family: "Open Sans", sans-serif;
    }

    .modal-close:hover {
        color: black;
    }

    .displaynone {
        display: none;
    }

    @media (max-width: 460px) {
        .modal-window > div {
            width: 90%;
            margin: 0 1em;
            left: 0;
        }
    }

    @media (min-width: 768px) {
        .mobadjust {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    }
</style>

<!-- Document Title
============================================= -->
<title>WAKimart DI RUMAH AJA</title>
</head>
<body class="body-scroll">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MSXJGPP"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<!-- Document Wrapper
============================================= -->
<div id="wrapper" class="wrapper clearfix">

<!-- Hero #12
============================================= -->
<section id="hero" class="section hero hero-12 text-center bg-overlay bg-overlay-light bg-parallax fullscreen">
    <div class="bg-section">
        <img src="{{ asset('sources/landing/hero/bgtopwakimart.jpg') }}" alt="Background" />
    </div>
    <div class="pos-vertical-center">
        <div class="container">

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="hero--content col-xs-12 col-sm-8 col-md-8">
                        <img src="https://www.wakimart.co.id/sources/wakimartlogo.png" alt="" class="img-fluid logoWK">
                        <div class="clearfix"></div>
                        <h1 class="hero--headline">Dapatkan Voucher Senilai Rp 270.000 Dengan Menjadi Member Wakimart!</h1>
                        <p class="hero--bio">PASTI UNTUNG PASTI HAPPY!</p>
                        <div class="col-xs-12 col-sm-12 col-md-12" style="text-align: left;">
                            {{-- <form action="{{ route('single_register', ['cdd' => 'WKT001']) }}"> 
                                <button id="addRegistrationPromo" type="submit" class="btn btn--customwk btn--rounded">Daftar Sekarang</button>
                            </form> --}}
                            <a id="addRegistrationPromo" class="btn btn--customwk btn--rounded" href="https://wakimart.co.id/register">Daftar Sekarang</a>
                        </div>
                    </div>

                    {{--<div class="form--top col-xs-12 col-sm-4 col-md-4">
                        <h5 class="form--title">
                            Isi Form Kami
                        </h5>
                        <p class="form--para">Dapatkan ekstra bonus voucher wakimart senilai 280.000 untuk 100 orang pendaftar pertama. <br>Promo berakhir dalam</p>
                        <div id="countdown-timer" class="countdown py-4 form--title" style="margin: 0;"></div>
                        <form id="actionAdd" action="{{ route('store_registraton') }}" method="POST">
                            @csrf
                            <div class="col-xs-12 col-sm-12 col-md-12" style="padding: 0;">
                                <div class="input-group">
                                    <span class="usericon">
                                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Nama Depan" required />
                                    </span>
                                </div>
                                <div class="input-group">
                                    <span class="usericon">
                                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Nama Akhir" required />
                                    </span>
                                </div>
                                <div class="input-group">
                                    <span class="usericon2">
                                        <input type="text" class="form-control" id="address" name="address" placeholder="Alamat" required />
                                    </span>
                                </div>
                                <div class="input-group">
                                    <span class="usericon3">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required />
                                    </span>
                                </div>
                                <div class="input-group">
                                    <span class="usericon4">
                                        <input type="phone" class="form-control" id="phone" name="phone" placeholder="No Telp" required />
                                    </span>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12" style="margin: auto;">
                                <button id="addRegistrationPromo" type="submit" class="btn btn--customwk btn--rounded">Daftar</button>
                            </div>
                        </form>
                    </div>--}}
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
<section id="feature" class="section feature feature-1 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-7">
                <div class="heading">
                    <h1 class="hero--headline">Apa itu WAKimart?</h2>
                    <p class="heading--subtitle">WAKimart adalah supermarket bergerak yang menyediakan 5000 produk untuk kebutuhan keluarga anda dalam menuju kehidupan yang lebih baik.</p>
                    <img class="img-responsive" style="margin-bottom:20px" src="{{ asset('sources/landing/icon4.jpg') }}" />
                    <h4 class="heading--medium text-center">Alat Dapur | Dekorasi | Elektronik<br>
                      Mainan & Hobi | Peralatan | Lainnya</h4>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-5">
                <div class="heading" style="max-width: 80%; margin: auto;">
                    <img class="img-responsive" src="{{ asset('sources/landing/mascotwkm.png') }}" />
                </div>
            </div>
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
                    <h2 class="hero--headline" style="margin: 10px 0;">SEHAT DI RUMAH AJA!</h2>
                </div>
                <div class="col-xs-12 col-sm-5 col-md-5" style="text-align: center;">
                    <a class="btn btn--customwk2 btn--rounded" href="#">Daftar Sekarang</a>
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
        <img src="{{ asset('sources/landing/hero/bgmidwakimart.jpg') }}" alt="Background" />
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3">
                <div class="heading mb-70">
                    <h1 class="heading--title">Keuntungan Menjadi Member WAKimart</h2>
                </div>
            </div>
        </div>
        <!-- .row end -->
        <div class="row">
            <!-- Panel #1 -->
            <div class="col-xs-6 col-sm-6 col-md-3">
                <div class="feature-panel custom-panel">
                    <div class="feature--icon" style="position: relative;">
                        <div class="custom--icon">
                            <img src="{{ asset('sources/landing/Asset 1.png') }}" />
                        </div>
                    </div>
                    <div class="feature--content">
                        <p>Harga menarik khusus member</p>
                    </div>
                    <div class="feature--content2">
                        <p>Harga spesial untuk semua produk bagi member WAKimart</p>
                        <!-- <p class="moredetail">View detail ></p> -->
                    </div>
                </div>
                <!-- .feature-panel end -->
            </div>
            <!-- .col-md-4 end -->

            <!-- Panel #2 -->
            <div class="col-xs-6 col-sm-6 col-md-3">
                <div class="feature-panel custom-panel">
                    <div class="feature--icon" style="position: relative;">
                        <div class="custom--icon">
                            <img src="{{ asset('sources/landing/Asset 2.png') }}" />
                        </div>
                    </div>
                    <div class="feature--content">
                        <p>Keanggotaan gratis dan seumur hidup</p>
                    </div>
                    <div class="feature--content2">
                        <p>Menjadi member mudah, sekali daftar dan berjagka seumur hidup</p>
                        <!-- <p class="moredetail">View detail ></p> -->
                    </div>
                </div>
                <!-- .feature-panel end -->
            </div>
            <!-- .col-md-4 end -->

            <!-- Panel #3 -->
            <div class="col-xs-6 col-sm-6 col-md-3">
                <div class="feature-panel custom-panel">
                    <div class="feature--icon" style="position: relative;">
                        <div class="custom--icon">
                            <img src="{{ asset('sources/landing/Asset 3.png') }}" />
                        </div>
                    </div>
                    <div class="feature--content">
                        <p>Bisa mengikuti program PASTI UNTUNG</p>
                    </div>
                    <div class="feature--content2">
                        <p>Dapatkan produk-produk berkualitas yang pasti BEST DEAL</p>
                        <!-- <p class="moredetail">View detail ></p> -->
                    </div>
                </div>
                <!-- .feature-panel end -->
            </div>
            <!-- .col-md-4 end -->

            <!-- Panel #4 -->
            <div class="col-xs-6 col-sm-6 col-md-3">
                <div class="feature-panel custom-panel">
                    <div class="feature--icon" style="position: relative;">
                        <div class="custom--icon">
                            <img src="{{ asset('sources/landing/Asset 4.png') }}" />
                        </div>
                    </div>
                    <div class="feature--content">
                        <p>Bisa mengikuti program WAKi Indonesia</p>
                    </div>
                    <div class="feature--content2">
                        <p>Program WAKi tebaru saat ini yaitu WAKi Stay at Home untuk sehati di rumah setiap saat</p>
                        <!-- <p class="moredetail">View detail ></p> -->
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
            <div class="col-xs-12 col-sm-7 col-md-7 heading">
                <h2 class="hero--headline" style="margin: 10px 0;">Daftar sekarang untuk<br> sehat di rumah aja!</h2>
            </div>
            <div class="col-xs-12 col-sm-5 col-md-5">
                <a class="btn btn--customwk2 btn--rounded" href="#">Daftar Sekarang</a>
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
                        <img src="https://www.wakimart.co.id/sources/wakimartlogo.png" alt="" class="img-fluid logoWKbawah">
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
            <div class="modal-header heading" style="display: flex; flex-direction: row;">
                <h4 class="modal-title heading--title" style="width: 90%; margin: 0;">Terima Kasih</h4>
                <button type="button" style="width: 10%; float: right" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body heading">
                <p id="txt-success" class="heading--subtitle">Pendaftaran anda telah berhasil. Terima kasih telah mendaftar, petugas kami akan segera menghubungi Anda.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn--customwk2 btn--rounded" type="button" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<div class="container displaynone">
    <div class="interior">
        <a class="btn regisnotif" href="#open-modal"></a>
    </div>
</div>
<div id="open-modal" class="modal-window">
  <div>
    <a href="#" title="Close" class="modal-close">X</a>
    <h5 id="notif-name">Albert - Surabaya</h5>
    <h5 style="font-weight: 100;" id="notif-time">Telah mendaftar 5 menit yang lalu</h5>
    <!-- <div>A CSS-only modal based on the :target pseudo-class. Hope you find it helpful.</div> -->
    </div>
</div>

</div>
<!-- #wrapper end -->

<!-- Footer Scripts
============================================= -->
<script src="{{ asset('js/landing/jquery-2.2.4.min.js') }}"></script>
<script src="{{ asset('js/landing/plugins.js') }}"></script>
<script src="{{ asset('js/landing/functions.js') }}"></script>
<script type="application/javascript">
{{--const countDownDate = new Date("Apr 30, 2021 00:00:00").getTime();
const intervalId = setInterval(function () {
    const now = new Date().getTime();
    const distance = countDownDate - now;
    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    let countDownHtml = "";
    if (days > 0) {
        countDownHtml += '<span class="h1 font-weight-bold">'
            + days
            + ' </span><span class="h1 font-weight-bold">hari </span>';
    }

    if (hours > 0) {
        countDownHtml += '<span class="h1 font-weight-bold">'
            + ("0" + hours).slice(-2)
            + ' </span><span class="h1 font-weight-bold">jam </span>';
    }

    if (minutes > 0) {
        countDownHtml += '<span class="h1 font-weight-bold">'
            + ("0" + minutes).slice(-2)
            + ' </span><span class="h1 font-weight-bold">menit </span>';
    }

    countDownHtml += '<span class="h1 font-weight-bold">'
        + ("0" + seconds).slice(-2)
        + ' </span><span class="h1 font-weight-bold">detik</span>';

    if (days <= 0 && hours <= 0 && minutes < 0) {
        clearInterval(intervalId);
        countDownHtml = '<span class="h1 font-weight-bold">Promo telah berakhir</span>';
    }
    document.getElementById("countdown-timer").innerHTML = countDownHtml;
}, 1000);--}}

$(document).ready(function() {
    $("#testimonial-slider").owlCarousel({
        items: 4,
        pagination: true,
        autoplay: true,
        loop: true,
        navigation: true,
        dots: false,
        responsive:{
            1000: { items: 4 },
            768: { items: 3 },
            640: { items: 2 },
            370: { items: 1 }
        }
    });

    $("#success-alert").show();
    // $("#myWish").click(function showAlert() {
    //   $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
    //     $("#success-alert").slideUp(500);
    //   });
    // });
});
</script>

@if(Session::has('success_registration'))
    <script type="application/javascript">
        $("#modal-Success").modal("show");
    </script>
    @php
        Session::forget('success_registration');
    @endphp
@endif
</body>
</html>
