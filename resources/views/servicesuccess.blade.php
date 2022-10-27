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
            <h2 style="margin: 0 5px 0 5px;">
                REGISTRASI JADWAL SERVICE BERHASIL
            </h2>
        </div>

        <div class="row justify-content-center">
          <table class="col-md-12">
              <tr>
                  <td>
                      <p class="pInTable">
                        Terima kasih kepada Bapak/Ibu yang terhormat atas dukungan dan dorongan yang telah diberikan kepada WAKi Indonesia agar dapat berkembang dan menjadi lebih baik di masa mendatang.
                      </p>
                      <p class="pInTable">
                        Tujuan Servis adalah untuk mempererat hubungan antara bapak/ibu dengan WAKi dan memberikan cara penggunaan produk Waki untuk mencapai efek yang lebih baik dan maksimal.
                      </p>
                      <p class="pInTable">
                        Team Servis WAKi akan menghubungi terlebih dahulu sebelum berangkat ke tempat Bapak/Ibu. Untuk informasi lebih lanjut atau perubahan jadwal service, dapat menghubungi WAKi Service Department kembali di nomor (+628993199999).
                      </p>
                  </td>
              </tr>
          </table>
        </div>

        <div class="row justify-content-center">
            <table class="col-md-12">
                <thead>
                    <td colspan="2">Informasi Kustomer </td>
                </thead>
                {{-- <tr>
                    <td>No. Member : </td>
                    <td>{{ $technician_schedule['no_member'] }}</td>
                </tr> --}}
                <tr>
                    <td>Nama : </td>
                    <td>{{ $technician_schedule['name'] }}</td>
                </tr>
                <tr>
                    <td>No. Telp : </td>
                    <td>{{ $technician_schedule['phone'] }}</td>
                </tr>
                <tr>
                    <td>Alamat : </td>
                    <td>{{ $technician_schedule['address'] }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>{{
                        $technician_schedule->provinceObj['province'] }},
                        {{ $technician_schedule->cityObj['type'] }}
                        {{ $technician_schedule->cityObj['city_name'] }},
                        {{ $technician_schedule->districObj['subdistrict_name'] }}
                    </td>
                </tr>
            </table>
            <table class="col-md-12">
                <thead>
                    <td colspan="2">Tanggal dan Waktu Janjian</td>
                </thead>
                @php
                    $dt = new DateTime($technician_schedule['appointment']);
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
            @foreach ($technician_schedule->product_technician_schedule_withProduct as $key => $product_ts)
            <table class="col-md-12">
                <colgroup>
                    <col class="col-4">
                    <col class="col-10">
                </colgroup>
                <thead>
                    <td colspan="2">Detail Service {{ $key+1 }}</td>
                </thead>
                <tr>
                    <td>Produk : </td>
                    <td>{{ ($product_ts->product_id != null)
                        ? $product_ts->product['name']
                        : $product_ts->other_product }}
                    </td>
                </tr>
                @php $issues = json_decode($product_ts->issues); @endphp
                <tr>
                    <td>Issues : </td>
                    <td>{{ implode(", ", $issues[0]->issues) }}</td>
                </tr>
                <tr>
                    <td>Description : </td>
                    <td>{{ $issues[1]->desc }}</td>
                </tr>
            </table>
            @endforeach
            <table class="col-md-12">
                <thead>
                    <td colspan="2">Kebijakan Home Service WAKi</td>
                </thead>
                <tr>
                    <td>
                        <p class="pInTable">
                        Sehubungan dengan kenaikan biaya operasional bagian maintenance/service, maka kami dengan berat hati memutuskan untuk mengenakan biaya kunjungan service/pengecheckan produk ke rumah Bapak/Ibu, dengan ketentuan sbb :
                        </p>
                        <p class="pInTable">
                        • Kunjungan service dilakukan oleh team teknisi untuk melakukan service/pengecheckan produk-produk WAKi di rumah Bapak/Ibu.
                        </p>
                        <p class="pInTable">
                        • Apabila perbaikan tidak bisa dikerjakan di rumah Bapak/Ibu, maka pengerjaan akan dilakukan di tempat kami.
                        </p>
                        <p class="pInTable">
                        • Biaya untuk 1(satu) kali kunjungan adalah Rp 200.000,- (dua ratus ribu rupiah) dan akan dibuatkan tanda terima resmi dari WAKI.
                        </p>
                        <p class="pInTable">
                        • Apabila pada saat kunjungan service tersebut, perbaikan harus dilakukan di kantor kami, maka untuk pengembalian produk ke rumah Bapak/Ibu adalah free.
                        </p>
                        <p class="pInTable">
                        • Apabila Bapak/Ibu menghantarkan produk ke tempat kami, dan setelah selesai dilakukan perbaikan, produk tersebut wajib diambil sendiri. Apabila kami yang menghantarkan tetap akan dikenakan biaya sebesar Rp 200.000,-
                        </p>
                        <p class="pInTable">
                        • Apabila ternyata ada sparepart yang perlu dilakukan penggantian, maka harga sparepart tersebut adalah berbeda dg biaya kunjungan service.
                        </p>
                    </td>
                </tr>
            </table>
            <a href="whatsapp://send?text={{ Route('services_success') }}?code={{ base64_encode($technician_schedule['id']) }}" data-action="share/whatsapp/share">Share to Whatsapp</a>
        </div>
    </div>
</section>
@endsection
