<html>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<head>
    <style>
        body {
            font-size: 12px;
        }
    </style>
</head>

<body>
  <div style="margin: 0 80px;">
    <div style="margin-top: 0.5rem;">
      <div style="float: right; margin-top: 1rem;">
        <p style="margin: 0rem; font-weight: bolder; font-size:1.2rem;">{{$submission->branch->code}}</p>
      </div>
      <div style="text-align: center;">
        <img src="{{public_path('sources/Logo Since.png')}}" style="max-width: 300px; height: 80px; margin-bottom:0.5rem;">
      </div>
    </div>

    <div style="text-align: center;">
      <h3 style="margin-bottom: 5px; text-decoration: underline;">WAKI PROGRAM REFRENSI BIAYA IKLAN BELANJA</h3>
      <h5 style="margin-top: 0;">TEMA AKTIFITAS: PRODUK KEMBALI PROGRAM</h5>
      <p style="margin-top: 0; text-align: right">No. MPC: <span style="text-decoration: underline;">{{$submission->no_member}}</span></p>
    </div>

    <div style="margin-top: 1.2rem;">
      <p>Terhormat Bapak / Ibu yang terpilih
        <span style="text-decoration: underline; text-transform: uppercase; font-weight: bolder">{{$submission->name}}</span>
        <span style="text-decoration: underline; text-transform: uppercase; font-weight: bolder"><i>{{$submission->phone}}</i></span>
      </p>
    </div>

    <div>
      <small>Sebagai terima kasih kepada Bapak/Ibu, atas kepercayaan dan dukungannya selama ini, WAKI memberi satu aktiviti barang kembali dengan syarat dibawah ini.</small>
    </div>

    <div style="margin-top: 1rem;">
      <div style="margin: 0.5rem 0;">
        <p style="margin: 0rem; width: 150px; display: inline-block;">Tanggal Berlaku : </p>

        @php
          //divide the days of a month into two parts
          $numberOfDays = date('t');
          $divideByTwo = $numberOfDays/2;
          $currentDate = date('d');
          if($currentDate <= floor($divideByTwo)){
            $effectiveDate = "1 - " . floor($divideByTwo);
          }else{
            $effectiveDate = floor($divideByTwo)+1 . " - " . $numberOfDays;
          }
        @endphp
        <p style="margin: 0; text-decoration: underline; display: inline; position: relative; top: -0.2rem;">{{$effectiveDate}} {{date("F Y")}}</p>
      </div>

      <div style="margin: 0.5rem 0; height: 5em;">
        <p style="margin: 0rem; width: 150px; float: left;">
          Special Referensi Produk : 
        </p>
        <ol style="margin: 0; text-decoration: underline; float: left; padding-left: 18px;">
          <li>HPT 2079 benefit Hepa Air Purifier</li>
          <li>Hepa Air Filter benefit Hepa Air Purifier</li>
          <li>HPT 2079 benefit HPT 2079</li>
          <li>Electro Massage benefit Hepa Air Purifier</li>
        </ol>
      </div>

      <div style="margin: 0.5rem 0; clear: both;">
        <p style="margin: 0rem; width: 150px; display: inline-block;">
          Branch :
        </p>
        <p style="margin: 0; text-decoration: underline; display: inline; position: relative; top: -0.2rem;">{{$submission->branch->code}} - {{$submission->branch->name}}</p>
      </div>

      <div style="margin: 0.5rem 0;">
        <p style="margin: 0rem; width: 150px; display: inline-block;">
          Produk Kembali :
        </p>
        <p style="margin: 0; text-decoration: underline; display: inline; position: relative; top: -0.2rem;">{{$reference->prize_name}}</p>
      </div>
    </div>

    <div style="margin-top: 1rem;">
      <h4 style="margin-bottom: 5px;">Syarat dan Ketentuan :</h4>
      <div style="padding-left: 1rem;">
        <ol style="margin: 0; padding-left: 0.75rem;">
          <li>Program ini hanya berlaku untuk member MPC WAKi saja secara gratis.</li>
          <li>Program ini hanya memperkenalkan teman / saudara untuk memiliki produk promosi WAKI sebagai program keuntungan biaya iklan dari WAKi (nama referensi tidak boleh sama).</li>
          <li>Barang tidak dapat ditukarkan dalam bentuk tunai.</li>
          <li>Quantiti produk kembali yang diberikan akan mengikut quantity memperkenalkan teman / saudara untuk memiliki produk promosi WAKI.</li>
          <li>Hanya berlaku untuk member MPC WAKI yang baru.</li>
          <li>Tidak boleh untuk upgrade produk.</li>
          <li>Berlaku setelah mengikuti 5 hari coba produk di rumah.</li>
          <li>Syarat dan ketentuan dapat berubah tanpa pemberitahuan sebelumnya.</li>
        </ol>
      </div>
    </div>

    <div style="margin-top: 5rem;">
      <p style="margin: 0;">Jakarta, <span>{{date("d F Y")}}</span></p>
      <p style="margin: 0;">Salam Hangat,</p>
    </div>
    <div style="position: relative;">
      <img src="{{public_path('sources/ttd.png')}}" style="max-width: 140px; margin-bottom:0.5rem; position: absolute; top: -2rem;">
    </div>
    <div style="margin-top: 4rem;">
      <p style="margin: 0;">Customer Relationship Department</p>
      <p style="margin: 0;">WAKi International Group</p>
      <a href="waki.asia">www.waki.asia</a>
    </div>
  </div>

  <div style="width:100%; position: absolute; bottom: 0; color: #1734b0; font-size: 0.8rem;">
    <div style="width: 80%; margin: auto; text-align:center">
      <p style="margin: 0">
        Komplek Darmopark 1 Blok 2B no 1-6, Jl. Mayjend Sungkono, Surabaya, 60189
      </p>
      <p style="margin: 0">
        Telp. (62-31) 5661876 - 5662308 | Fax. (62-31) 5661995 | Hotline: 0899-31-99999
      </p>
    </div>
  </div>

</body>

</html>
