<?php
    
?>
@extends('layouts.template')

@section('content')
<style type="text/css">
    #intro {
        padding-top: 2em;
    }

    .card-shadow {
        box-shadow: 0 0 10px 0 rgba(100, 100, 100, 0.26);
        padding:1em; 
    }

    #table-mob .table thead{
        background-color: #9eabe4;
        background-image: linear-gradient(315deg, #9eabe4 0%, #77eed8 74%);
        color: #1c1c1c;
        font-size: 1em;
        font-weight: 600; 
    }

    .table-responsive .sk td, .table-responsive .sk th{
        padding-bottom: 0.1rem;
    }

    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link {
        color: #737373; 
    }

    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
        color: #63a4ff;
    }

    /* Tabs Card */

    .tab-card {
        border:1px solid #eee;
    }

    .tab-card-header {
        background:none;
    }
    .tab-content .tab-pane{
        background: none;
    }
    /* Default mode */
    .tab-card-header > .nav-tabs {
        border: none;
        margin: 0px;
    }
    .tab-card-header > .nav-tabs > li {
        margin-right: 2px;
    }
    .tab-card-header > .nav-tabs > li > a {
        border: 0;
        border-bottom:2px solid transparent;
        margin-right: 0;
        color: #545454; 
        font-weight: 600;
        padding: 2px 10px;
    }

    .tab-card-header > .nav-tabs > li > a.show {
        border-bottom:2px solid #048b32;
        color: #048b32;
    }
    .tab-card-header > .nav-tabs > li > a:hover {
        color: #048b32;
    }

    .tab-card-header > .tab-content {
        padding-bottom: 0;
    }

    .responsive-table{
        list-style-type: none;
        padding-left: 0;
        margin-left: 0;
    }

    .responsive-table li {
        border-radius: 3px;
        padding: 25px 30px;
        display: flex;
        justify-content: space-between;
        margin-bottom: 25px;
    }

    .responsive-table .table-header {
        background-color: #9eabe4;
        background-image: linear-gradient(315deg, #9eabe4 0%, #77eed8 74%);
        color: #1c1c1c;
        font-size: 16px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .responsive-table .table-row {
        background-color: #ffffff;
        box-shadow: 0px 0px 9px 0px rgba(0,0,0,0.1);
    }

    #table-mob{
          display : none;
    }

      #table-desk{
          display : block;
    }
  
  @media (max-width: 767px) {
      #table-mob{
          display : block;
      }

      #table-desk{
          display : none;
      }
    
    }

    @media (max-width: 575px){
        .container{
            padding: 2em;
        }
        .table-responsive{
            margin: 1em;
        }
    }
</style>

<section id="intro" class="clearfix">
    <div class="container">
        <div class="row justify-content-center">
            <h2 class="text-center" style="margin-bottom: 0.2em; font-weight: 600; color: #002853;">Program Pinjamin Produk 5 Hari</h2>
        </div>
        <div class="row justify-content-center">
            <h4 class="text-center" style="font-weight: 400;">Coba Produk di Rumah Sendiri</h4>
        </div>
        <hr>
        
        <div class="row justify-content-center mt-5">
            <h4 class="text-center" style="font-size: 48px; font-family: 'Poppins', sans-serif; font-weight: 500; color: #f0bc5e;">
            <span><img class="img-fluid" style="margin-right: 15px; width: 60px; vertical-align: text-center;" src="{{asset('sources/confetti.png')}}"></span>
                Terima Kasih Bpk/Ibu {{ $publichomecare['name'] }}
                <span><img class="img-fluid" style="margin-right: 15px; width: 60px; vertical-align: text-center;" src="{{asset('sources/confetti.png')}}"></span>
            </h4>
        </div>
        <div class="row justify-content-center">
            <p class="text-center" style="font-size: 18px; color: #000; font-weight: 500;">
                telah mengikuti program
                <span style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #002853;"> Pinjamin Produk 5 Hari</span> 
                di WAKi
            </p> 
        </div>
        <div class="row justify-content-center mb-5">
            <img class="img-fluid" style="margin-right: 15px; width: 120px;" src="{{asset('sources/thankyou.png')}}">
        </div>
        <div class="row justify-content-center mb-5">
        <p class="text-center" style="font-size: 18px; color: #000; font-weight: 500;">
            Anda bisa mengikuti program
            <span style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #002853;"> Pinjamin Produk 5 Hari</span>
            lagi, setelah 2 pekan depan. 
        </p>
        </div>
        <hr>

        <br>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                <div class="d-flex" style="align-items: center;">
                    <img style="margin-right: 15px; width: 120px;" src="{{asset('sources/ph-desc.png')}}"> 
                    <span>
                        <p style="margin-top: 2em; margin-bottom: 0.7em; font-weight: bold;">
                            APA ITU PROGRAM PINJAMIN PRODUK 5 HARI?
                        </p>
                        <p>Bapak & Ibu bisa mencoba produk WAKi (Electro/Hepa/HPT) di rumah sendiri selama 5 hari, dimana petugas kami akan mendemokan produk tersebut sebelumnya.</p>
                    </span>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-6">
                <div class="d-flex" style="align-items: center;">
                    <img style="margin-right: 15px; width: 120px;" src="{{asset('sources/ph-form.png')}}"> 
                    <span>
                    <p style="margin-top: 2em; margin-bottom: 0.7em; font-weight: bold;">TUJUAN :</p>
                    <p>Visi WAKi adalah Happy With WAKi menuju kehidupan yang lebih baik. Dengan kondisi seperti ini, program ini dibuat khusus untuk meminimalisirkan adanya kontak fisik secara langsung. </p>
                    </span>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="table-responsive">
                <table class="table table-borderless sk">
                    <thead>
                        <th colspan="2">Syarat dan Ketentuan</th>
                    </thead>
                    <tbody class="sk">
                        <tr>
                            <td>1.</td>
                            <td>Program pinjamin produk 5 hari (PP5H) tidak dipungut biaya apapun.</td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td>Sudah menjadi member WAKimart dan minimal berusia 35 tahun.</td>
                        </tr>
                        <tr>
                            <td>3.</td>
                            <td>Apabila ada kekurangan, kehilangan ataupun kerusakan pada produk saat pengembalian, berarti membeli barang dengan harga normal sesuai ketentuan yang ada.</td>
                        </tr>
                        <tr>
                            <td>4.</td>
                            <td>Kekurangan aksesoris produk harap dikembalikan paling lambat 7 hari setelah program PP5H berakhir.</td>
                        </tr>
                        <tr>
                            <td>5.</td>
                            <td>Syarat dan Ketentuan dapat berubah tanpa pemeberitahuan sebelumnya.</td>
                        </tr>
                        <tr>
                            <td>6.</td>
                            <td>Kenyamanan dan keamanan konsumen kami adalah prioritas pertama.
                            Apabila ada sesuatu atau ada pertanyaan bisa menghubungi melalui facebook page 
                            WAKi Indonesia atau customer care kami di : +62 815-5467-3357</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>

<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
@endsection
