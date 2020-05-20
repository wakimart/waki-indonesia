@extends('layouts.template')

@section('content')
<style type="text/css">
    #intro {
        padding-top: 2em;
    }
    button{
        background: #1bb1dc;
        border: 0;
        border-radius: 3px;
        padding: 8px 30px;
        color: #fff;
        transition: 0.3s;
    }
    .validation{
        color: red;
        font-size: 9pt;
    }
    input, select, textarea{
        border-radius: 0 !important;
        box-shadow: none !important;
        border: 1px solid #dce1ec !important;
        font-size: 14px !important;
    }
    table{
        margin: 1em;
        font-size: 14px;
    }
    table thead{
        background-color: #8080801a;
        text-align: center;
    }
    table td{
        border: 0.5px #8080801a solid;
        padding: 0.5em;
    }
    .right{
        text-align: right;
    }
    .pInTable{
        margin-bottom: 6pt !important;
        font-size: 14px;
    }
</style>


<section id="intro" class="clearfix">
    <div class="container">
        <div class="row justify-content-center">
            <h2 style="margin: 0 5px 0 5px;">REGISTRASI HOME</h2>
            <h2 style="margin: 0 5px 0 5px;">SERVICE BERHASIL</h2>
        </div>

        <div class="row justify-content-center">
          <table class="col-md-12">
              <tr>
                  <td>
                      <p class="pInTable">&emsp;Terima kasih kepada bapak/ibu yang terhormat telah memberi
                        dukungan dan dorongan kepada WAKi Indonesia selama ini untuk
                        lebih berkembang dan lebih baik di masa mendatang.</p>
                      <p class="pInTable">Tujuan Home Service adalah untuk merapatkan hubungan antara
                        bapak/ibu sama WAKi dan biar bapak/ibu lebih memahami cara guna
                        WAKi produk supaya capai kesan yang lebih baik.</p>
                      <p class="pInTable">Team WAKi Home Service akan menghubungi terlebih dahulu
                        sebelum berangkat ke tempat Bapak/Ibu. Untuk informasi lebih lanjut
                        atau perubahan jadwal home service, dapat menghubungi WAKi
                        Home Service Department kembali di nomor (+6281234511881) atau
                        {{ $homeService->cso['name'] }}, {{ $homeService['cso_phone'] }}, {{ $homeService->branch['code'] }} - {{ $homeService->branch['name'] }}.</p>
                  </td>
              </tr>
          </table>
        </div>

        <div class="row justify-content-center">
            <table class="col-md-12">
                <thead>
                    <td colspan="2">Informasi Kustomer </td>
                </thead>
                <tr>
                    <td>No. Member : </td>
                    <td>{{ $homeService['no_member'] }}</td>
                </tr>
                <tr>
                    <td>Nama : </td>
                    <td>{{ $homeService['name'] }}</td>
                </tr>
                <tr>
                    <td>No. Telp : </td>
                    <td>{{ $homeService['phone'] }}</td>
                </tr>
                <tr>
                    <td>Kota : </td>
                    <td>{{ $homeService['city'] }}</td>
                </tr>
                <tr>
                    <td>Alamat : </td>
                    <td>{{ $homeService['address'] }}</td>
                </tr>
            </table>
            <table class="col-md-12">
                <thead>
                    <td colspan="2">Tanggal dan Waktu Janjian </td>
                </thead>

                @php
                    $dt = new DateTime($homeService['appointment']);
                @endphp

                <tr>
                    <td>Tanggal : </td>
                    <td>{{ $dt->format('j/m/Y') }}</td>
                </tr>
                <tr>
                    <td>Waktu : </td>
                    <td>{{ $dt->format('H:i') }}</td>
                </tr>

            </table>

            <table class="col-md-12">
                <thead>
                    <td colspan="2">Kebijakan Home Service WAKi</td>
                </thead>
                <tr>
                    <td>
                      <p class="pInTable">1. Home Service dari WAKi tidak dipungut biaya apapun.</p>
                      <p class="pInTable">2. Biaya akan dikenakan kepada konsumen jika ada sparepat ataupun kerusakan
                        di luar persetujuan MPC / Warranty.</p>
                      <p class="pInTable">3. Kenyamanan dan keamanan konsumen kami adalah prioritas pertama. Jika
                        ada sesuatu bisa hubungi kami melalui Home Service department:
                        +6281234511881.</p>
                      <p class="pInTable">4. Form ini akan carbon copy (CC) kepada Customer, Petugas, Ketua Cabang,
                        Home Service Department.</p>
                    </td>
                </tr>
            </table>
            <a href="whatsapp://send?text={{ Route('homeServices_success') }}?code={{ $homeService['code'] }}" data-action="share/whatsapp/share">Share to Whatsapp</a>
        </div>
    </div>
</section>
@endsection
