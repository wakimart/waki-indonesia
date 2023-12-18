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
    <div style="display: block; margin-top: 0.5rem;">
      <div style="display:flex; flex-direction: row; justify-content: space-between;">

        <div style="display:flex; flex-grow: 1; margin-top: 1rem; justify-content: center;">
        </div>
        <div style="display:flex; flex-grow: 1;">
          <img src="{{asset('sources/Logosince.svg')}}" style="max-width: 300px; height: 80px; margin-bottom:0.5rem;">
        </div>
        <div style="display:flex; margin-top: 1rem; justify-content: flex-end;">
          <div style="font-weight: bolder; font-size:1.2rem;">{{$submission->branch->code}}</div>
        </div>
      </div>
    </div>

    <div style="text-align: center;">
      <h3 style="margin-bottom: 5px; text-decoration: underline;">WAKI PROGRAM REFRENSI BIAYA IKLAN BELANJA</h3>
      <h5 style="margin-top: 0;">TEMA AKTIFITAS: PRODUK KEMBALI PROGRAM</h5>
      <p style="margin-top: 0; text-align: right">No. MPC: {{$submission->no_member}}</p>
    </div>

    <div style="display:flex; flex-direction: row;">
      <p>Terhormat Bapak / Ibu yang terpilih</p>
      <p style="margin-left: 0.25rem; text-decoration: underline; text-transform: uppercase; font-weight: bolder">
        {{$submission->name}}
      </p>
      <p style="margin-left: 0.25rem; text-decoration: underline; text-transform: uppercase; font-weight: bolder">
        <i>{{$submission->phone}}</i>
      </p>
    </div>

    <div>
      <small>Sebagai terima kasih kepada Bapak/Ibu, atas kepercayaan dan dukungannya selama ini, WAKI memberi satu aktiviti barang kembali dengan syarat dibawah ini.</small>
    </div>

    <div style="margin-top: 1rem;">
      <div style="display:flex; flex-direction: row; margin: 0.5rem 0;">
        <div style="width:200px;">
          Tanggal Berlaku :
        </div>
        <div style="text-decoration: underline;">
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
          {{$effectiveDate}} {{date("F Y")}}
        </div>
      </div>

      <div style="display:flex; flex-direction: row; margin: 0.5rem 0;">
        <div style="width:200px;">
          Special Referensi Produk :
        </div>
        <div style="text-decoration: underline;">
          <ol style="margin: 0; padding-left: 0.75rem;">
            <li>HPT 2079 benefit Hepa Air Purifier</li>
            <li>Hepa Air Filter benefit Hepa Air Purifier</li>
            <li>HPT 2079 benefit HPT 2079</li>
            <li>Electro Massage benefit Hepa Air Purifier</li>
          </ol>
        </div>
      </div>

      <div style="display:flex; flex-direction: row; margin: 0.5rem 0;">
        <div style="width:200px;">
          Branch :
        </div>
        <div style="text-decoration: underline;">
          {{$submission->branch->code}} - {{$submission->branch->name}}
        </div>
      </div>

      <div style="display:flex; flex-direction: row; margin: 0.5rem 0;">
        <div style="width:200px;">
          Produk Kembali :
        </div>
        <div style="text-decoration: underline;">
          {{$reference->prize_name}}
        </div>
      </div>
    </div>

    <div style="margin-top: 1rem;">
      <h4 style="margin-bottom: 5px;">Syarat dan Ketentuan :</h4>
      <div style="text-decoration: underline;">
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
    <div style="margin-top: 5rem;">
      <p style="margin: 0;">Customer Relationship Department</p>
      <p style="margin: 0;">WAKi International Group</p>
      <a href="waki.asia">www.waki.asia</a>
    </div>
  </div>

  <div style="width:100%; position: absolute; bottom: 0;">
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
