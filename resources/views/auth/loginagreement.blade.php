@extends('layouts.app')

@section('content')
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
            <div class="row flex-grow">
                <div class="col-12 col-lg-11 mx-auto">
                    <div class="auth-form-light text-left p-1">
                      <div class="col-6 col-sm-3 img-fluid">
                        <div class="brand-logo mb-1 mt-1">
                            <img src="{{ asset('sources/logosince.svg') }}">
                        </div>
                      </div>

                      <div class="agreementContent p-3" style="max-height:60vh; overflow-y:auto;">
                        <div id="firstAgreementContent">
                          <p align="center" style="text-align:center"><span style="font-size:14.0pt">TATA TERTIB PERATURAN PERUSAHAAN</span></p>
                          <br />
                          <p style=""><b><span><span>1.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp; </span></span></span></b><b><u><span>HARUS BERDISIPLIN,
                          BERPAKAIAN RAPI DAN MENGERTI SOPAN SANTUN</span></u></b></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>1.1.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Berdisiplin&nbsp;<span></span>: Taat pada Peraturan Perusahaan,<span>&nbsp;</span>taat pada atasan dan bertanggung jawab atas pekerjaan pada bagiannya masing-masing.</span>
                          </p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>1.2.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Berpakaian : Diwajibkan berpakaian yang sopan dan
                          bersih, warna pakaian yang sesuai, berkemeja dan tidak boleh kaos tanpa kerah,
                          tidak boleh pakai jeans, rambut tersisir rapi dan bersepatu.</span></p>
                          <p class="MsoListParagraphM"><span>&nbsp;</span></p>
                          <p style="text-align:justify"><b><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bagi karywan bagian<span>&nbsp;
                          </span>marketing TIDAK diperkenankan memakai T-Shirt dan celana/rok jeans <span>&nbsp;</span>semasa bertugas.</span></b></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>1.3.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Sopan Santun<span> </span>:</span>
                          </p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Sesama karyawan/karyawati harus seperti saudara sendiri. Perusahaan
                          adalah keluarga besar, harus bisa menjaga rasa kekeluargaan yang baik .</span></p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Sewaktu jam kerja tidak diperbolehkan bercakap-cakap kepada karyawan
                          lain, ini akan mengganggu pekerjaan sendiri dan karyawan lainnya.</span></p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Sewaktu jam kerja semua karyawan harus berada di tempat kerja
                          masing-masing, tidak diperbolehkan ke tempat karyawan lain, kecuali ada urusan
                          dengan karyawan yang bersangkutan.</span></p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Dilarang berkata-kata kasar/tidak pantas/tidak sopan kepada sesame
                          karyawan ataupun kepada customer</span></p>
                          <p style="text-align:justify"><span>&nbsp;</span></p>
                          <p style="text-align:justify;text-indent:
                          -18.0pt"><b><span><span>2.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp; </span></span></span></b><b><u><span>PERATURAN CUTI</span></u></b></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>2.1.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Off Mingguan:</span></p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Selama masa Training, dalam minggu pertama, tidak diberikan off
                          mingguan.</span></p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Off mingguan akan diberikan pada minggu kedua masa training dan
                          seterusnya (akan diatur kemudian oleh Perusahaan), yaitu 1 hari per minggu</span></p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Seluruh karyawan masuk 6 hari kerja dalam seminggu dan perusahaan
                          berhak mengatur jadwal off day untuk setiap karyawan</span></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>2.2.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Cuti Tahunan</span></p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Masa Cuti Tahunan adalah 12 hari kerja</span></p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Pengambilan cuti tahunan adalah max 6 hari, kecuali dengan ijin
                          tertulis dari perusahaan</span></p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Cuti tahunan dihitung dari setiap 1 Januari sd 31 Desember tahun
                          berjalan.</span></p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Karyawan yang berhak mengambil Cuti Tahunan adalah karyawan yang
                          masa kerjanya sudah 1 tahun setelah melewati masa percoabaan/training.</span></p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Apabila yang bersangkutan akan mengambil Cuti Tahunan, harus
                          memberitahukan kepada bagian personalia 30 <span>&nbsp;</span>hari sebelumnya dan dengan sepengetahuan Supervisor atau Pimpinan yang bersangkutan.</span>
                          </p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Libur Lebaran yang diberikan Perusahaan sudah termasuk dalam masa
                          Cuti Tahunan (mengurangi masa cuti tahunan)</span></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>2.3.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Cuti Khusus</span></p>
                          <p style="margin-left: 36pt;text-align:justify;text-indent:
                          -18.0pt"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Cuti melahirkan.</span></p>
                          <p style="margin-left: 36pt;text-align:justify"><span>diberikan kepada karyawan wanita selama<span>&nbsp; </span>45 hari terhitung permohonan cuti yang disetujui. Selama masa Cuti Melahirkan maka gaji yang diterima adalah terhitung berdasarkan GAJI POKOK.</span>
                          </p>
                          <p style="margin-left: 36pt;text-align:justify;text-indent:
                          -18.0pt"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Cuti Khusus selama 15 hari</span></p>
                          <p style="margin-left: 36pt;text-align:justify"><span>Cuti ini hanya diberikan atas permintaan karyawan yang sangat
                          memerlukan dan cuti ini harus disetujui<span>&nbsp;
                          </span>oleh perusahaan. Karena atas permintaan karyawan maka hak GAJI, ALLOWANCE dan KOMISI adalah NIHIL.</span>
                          </p>
                          <p style="margin-left:36.0pt;text-align:justify;text-indent:
                          22.5pt"><span style="font-family:Symbol"><span>·<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp;
                          </span></span>
                              </span><span>Cuti Nikah </span></p>
                          <p style="margin-left: 36pt;text-align:justify"><span>Diberikan selama maksimal 7 (tujuh) hari dan hal ini mengurangi masa
                          cuti tahunan sebanyak 4 (empat) hari.</span></p>
                          <p style="margin-left:36.0pt;text-align:justify;text-indent:
                          22.5pt"><span style="font-family:Symbol"><span>·<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp;
                          </span></span>
                              </span><span>Cuti Kelahiran Anak</span></p>
                          <p style="margin-left: 36pt;text-align:justify"><span>Diberikan selama 3 (tiga) hari kepada karyawan pria apabila istri
                          melahirkan anak</span></p>
                          <p style="margin-left:36.0pt;text-align:justify;text-indent:
                          22.5pt"><span style="font-family:Symbol"><span>·<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp;
                          </span></span>
                              </span><span>Cuti Holiday bersama perusahaan</span></p>
                          <p style="margin-left: 36pt;text-align:justify"><span>Mengurangi masa cuti tahunan sebesar setengah dari jumlah hari
                          liburan bersama tersebut.</span></p>
                          <p style="margin-left: 36pt;text-align:justify"><span>&nbsp;</span></p>
                          <p style="text-align:justify;text-indent:
                          -18.0pt"><b><span><span>3.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp; </span></span></span></b><b><u><span>JAM KERJA DAN ISTIRAHAT</span></u></b></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>3.1.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Pada saat bekerja:</span></p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-size:12.0pt;font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span style="font-size:12.0pt;font-family:&quot;Times New Roman&quot;,serif">Setiap hari sebelum photo absensi, wajib post ulang
                          jadwal kerja/off bulanan</span></p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-size:12.0pt;font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span style="font-size:12.0pt;font-family:&quot;Times New Roman&quot;,serif">Photo pada saat absensi pagi hari harus lengkap
                          seluruh team, baru bisa photo secara bersamaan</span></p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;</span></span>
                              </span><span>Apabila karyawan yang bersangkutan berhalangan hadir/masuk kerja
                          atau sakit, harus ijin kepada Pimpinan Pusat dan harus menunjukan Surat Keterangan Dokter</span></p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Apabila karyawan yang bersangkutan berhalangan masuk kerja dan tidak
                          ada pemberitahuan sebelumnya, maka akan dikenakan sanksi penalty pemotongan
                          gaji yang akan dihitung pada akhir bulan</span></p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Bagi karyawan yang terlambat masuk kerja, akan dikenakan sanksi
                          denda keterlambatan, yang akan diatur lebih lanjut oleh perusahaan, sesuai
                          dengan divisi/bagian masing-masing.</span></p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Perusahaan tidak memberikan toleransi bagi karyawan yang tidak masuk
                          kerja karena alasan pribadi. Bagi karyawan yang melanggar ketentuan ini, maka
                          Perusahaan akan mengenakan sanksi seperti tersebut di atas.</span></p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Jadwal libur bulanan untuk bagian sales dan delivery harus dibuat
                          pada setiap akhir bulan sebelumnya</span></p>
                          <p style="text-align: left; margin-left: 36pt;"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Untuk bagian administrasi dan bagian IT, jadwal libur adalah setiap
                          hari minggu dan libur nasional yang telah ditetapkan oleh pemerintah.</span></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>3.2.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Jam Istirahat :</span></p>
                          <p style="margin-left: 36pt;text-align:justify;text-indent:
                          -18.0pt"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Bagian Administrasi di kantor : Jam 12.00 – 13.00 WIB</span></p>
                          <p style="margin-left: 36pt;text-align:justify;text-indent:
                          -18.0pt"><span style="font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span>Bagian Marketing dan deliveri : Ditetapkan berdasarkan pertimbangan
                          lokasi, kondisi dan personel dengan sepengetahuan Supervisor/Pimpinan</span></p>
                          <p style="margin-left: 36pt;text-align:justify"><span>&nbsp;</span></p>
                          <p style="text-align:justify;text-indent:
                          -18.0pt"><b><span><span>4.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp; </span></span></span></b><b><u><span>MASA PERCOBAAN</span></u></b></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>4.1.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Selama 3 (tiga) Bulan dan
                          Perusahaan berhak untuk memperpanjang masa percobaan tersebut tanpa ikatan.</span></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>4.2.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Jikalau prestasi baik dan
                          kelakuan baik akan dipercepat masa percobaannya.</span></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>4.3.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Dengan berakhirnya masa
                          percobaan, tidak diharuskan ada kenaikan gaji.</span></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>4.4.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Dalam masa percobaan,
                          Perusahaan tidak memberikan uang pesangon kepada karyawan yang bersangkutan bila
                          mengundurkan diri, termasuk jika terjadi pemutusan hubungan kerja.</span></p>
                          <p style="text-align:justify"><span>&nbsp;</span></p>
                          <p style="text-align:justify;text-indent:
                          -18.0pt"><b><span><span>5.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp; </span></span></span></b><b><u><span>BERTELEPON</span></u></b></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>5.1.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Telepon di kantor tidak dapat
                          digunakan untuk berkomunikasi dengan karyawan/karyawati yang tidak
                          berkepentingan atau digunakan untuk berpacaran.</span></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>5.2.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Telephon kantor tidak boleh digunakan
                          untuk menghubungi teman antar cabang ataupun via telepon lokal.</span></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>5.3.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Berbicara melalui telepon mohon
                          dengan singkat dan seperlunya (apa yang mau dibicarakan sudah terlebih dahulu
                          disiapkan)</span></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>5.4.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;</span></span>
                              </span><span>Jika terdapat bukti bahwa
                          karyawan menggunakan telepon untuk keperluan pribadi, maka karyawan/karyawati
                          tersebut harus membayar biaya rekening sejumlah pemakaian berikut biaya
                          administrasi sebesar Rp. 50.000 (lima puluh ribu rupiah +Biaya PPN 10%)</span></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>5.5.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Tidak menggunakan telp/HP
                          kantor atau perusahaan untuk keperluan pribadi dalam waktu kerja.</span></p>
                          <p style="text-align:justify"><span>&nbsp;</span></p>
                          <p style="text-align:justify;text-indent:
                          -18.0pt"><b><span><span>6.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp; </span></span></span></b><b><u><span>HUBUNGAN ANTAR KARYAWAN</span></u></b></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span>6.1.<span style="font-family: &quot;Times New Roman&quot;; font-size: 9.33333px;">&nbsp;</span></span><span>Hubungan karyawan<span>&nbsp; </span>lawan jenis hanya terbatas sebagai saudara dan tidak diperbolehkan pacaran, seandainya hal ini tidak
                              dapat dihindarkan maka Perusahaan akan meminta salah satu karyawan mengundurkan diri.</span>
                          </p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>6.2.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;</span></span>
                              </span><span>Bagi karyawan/ti yang telah
                          menikah tidak diperbolehkan untuk berhubungan atau pacaran dengan karyawan/ti WAKI
                          lainnya, kalau hal ini tidak dapat dihindarkan maka karyawan/ti tersebut harus
                          mengundurkan diri.</span></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>6.3.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Bagi karyawan WAKI yang tempat
                          kerjanya sama (seatap) tetapi berlainan divisi, boleh ada hubungan
                          khusus/pacaran. </span></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>6.4.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;</span></span>
                              </span><span>Apabila karyawan WAKI telah
                          menikah dengan teman sekerja di WAKI, maka keduanya boleh tetap bekerja di WAKI
                          asalkan mendapatkan ijin khusus dan tertulis dari Pimpinan Tertinggi WAKI.</span></p>
                          <p style="text-align:justify"><span>&nbsp;</span></p>
                          <p style="text-align:justify;text-indent:
                          -18.0pt"><b><span><span>7.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp; </span></span></span></b><b><u><span>RAHASIA PERUSAHAAN</span></u></b></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>7.1.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;</span></span>
                              </span><span>Setiap Karyawan/Karyawati
                          diwajibkan menjaga rahasia Perusahaan, tidak dapat dibocorkan kepada siapapun
                          termasuk pacar, suami/istri keluarga<span>&nbsp;
                          </span>atau dengan siapapun juga.</span>
                          </p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>7.2.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Jikalau hal ini sampai terjadi,
                          Perusahaan akan meminta dengan hormat karyawan yang bersangkutan segera
                          mengundurkan diri.</span></p>
                          <p style="text-align:justify"><span>&nbsp;</span></p>
                          <p style="text-align:justify;text-indent:
                          -18.0pt"><b><span><span>8.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp; </span></span></span></b><b><u><span>PUTUS HUBUNGAN KERJA</span></u></b></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>8.1.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;</span></span>
                              </span><span>Setiap karyawan harus
                          memberitahu kepada pihak Perusahaan 1 (satu) bulan sebelumnya bila ingin
                          mengundurkan diri. Bilamana karyawan berhenti tidak sesuai dengan waktu yang
                          ditentukan yaitu 1 (satu) bulan, maka surat referensi tidak akan diberikan.</span></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>8.2.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;</span></span>
                              </span><span>Pihak Perusahaan memutuskan
                          hubungan kerja kepada karyawan dan bilamana karyawan yang bersangkutan tidak
                          pernah melakukan pelanggaran peraturan Perusahaan, maka pihak Perusahaan akan
                          memberikan pesangon maximum 3 (tiga) bulan gaji pokok.</span></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>8.3.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;</span></span>
                              </span><span>Jika karyawan melanggar<span>&nbsp; </span>Peraturan Perusahaan atau melanggar<span>&nbsp; </span>UU<span>&nbsp;
                          </span>Negara (KUHP) Perusahaan berhak memutuskan hubungan kerja seketika tanpa pemberian uang pesangon/ganti rugi</span>
                          </p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt">8.4.<span style="font-family: &quot;Times New Roman&quot;; font-size: 9.33333px;">&nbsp;</span>Atas permohonan karyawan sendiri untuk mengundurkan diri, maka Perusahaan tidak memberikan uang pesangon/ganti rugi</p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span>8.5.<span style="font-family: &quot;Times New Roman&quot;; font-size: 9.33333px;">&nbsp;</span></span><span>Karyawan<span>&nbsp; </span>tidak disiplin (misailnya: sering tidak masuk, sering terlambat, membuat onar, berkelahi sesama karyawan, manghasut
                              karyawan lain, dsb). Setelah ada peneguran secara lisan atau tertulis, maka pihak Perusahaan berhak memberhentikan karyawan tersebut tanpa uang pesangon.</span>
                          </p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span>8.6.<span style="font-family: &quot;Times New Roman&quot;; font-size: 9.33333px;">&nbsp;</span></span><span>Karyawan yang menyalahgunakan <span>&nbsp;</span>uang Perusahaan, maka Perusahaan akan melaporakan kepada pihak yang berwajib dan karyawan
                              tersebut akan diberhentikan tanpa memberikan uang pesangon.</span>
                          </p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>8.7.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Karyawan tidak masuk kerja
                          selama 3 hari berturut-turut tanpa surat keterangan sakit/dokter maka dianggap
                          telah mengundurkan diri secara sukarela dan tidak akan mendapatkan uang pesangon/ganti
                          rugi apapun juga.</span></p>
                          <p style="margin-left:18.0pt;text-align:justify"><span>&nbsp;</span></p>
                          <p style="text-align:justify;text-indent:
                          -18.0pt"><b><span><span>9.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp; </span></span></span></b><b><u><span>KECURANGAN DALAM BEKERJA</span></u></b></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>9.1.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Apabila terjadi kecurangan
                          dalam proses transaksi produk-produk WAKI dan terbukti secara sah dan
                          menyakinkan, maka akan dilanjutkan proses hokum kepada yang berwajib.</span></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>9.2.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Apabila terbukti adan tindakan
                          curang dan tersangka tidak mengakui secara jujur, maka perusahaan akan
                          melaporkan hal tersebut kepada pihak berwajib.</span></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span>9.3.<span style="font-family: &quot;Times New Roman&quot;; font-size: 9.33333px;">&nbsp;</span></span><span>Apabila karyawan tersebut telah
                          mengundurkan diri selama proses investigasi kasus, maka perusahaan akan tetap
                          melanjutkan pelaporan kepada pihak berwajib.</span></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span>9.4.<span style="font-family: &quot;Times New Roman&quot;; font-size: 9.33333px;">&nbsp;</span></span><span>Apabila ada tindakan kecurangan
                          yang terjadi di tempat kerja dan merugikan perusahaan secara materi, maka
                          pimpinan setempat (spv dan asst spv) harus mengganti kerugian perusahaan sesuai
                          dengan nilai kerugian atau daftar harga produk yang ada.</span></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span>9.5.&nbsp;</span><span>Apabila terjadi kecurangan
                          didalam gudang/kantor yang mengakibatkan kerugian perusahaan, maka pimpinan
                          divisi terkait, dan pimpinan setempat, wajib mengganti kerugian perusahaan
                          sesuai dengan nilai kerugian/daftar harga produk yang ada. </span></p>
                          <p style="text-align:justify"><span>&nbsp;</span></p>
                          <p style="text-align:justify;text-indent:
                          -18.0pt"><b><span><span>10.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span></span></b><b><u><span>NON AKTIF</span></u></b></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span>10.1.<span style="font-family: &quot;Times New Roman&quot;; font-size: 9.33333px;">&nbsp;</span></span><span>Apabila karyawan membuat
                          kesalahan / pelanggaran Perusahaan tetapi oleh Perusahaan dinilai bukan
                          merupakan kesengajaan, maka Perusahaan dapat memberi sanksi Non Aktif Kegiatan
                          (yang lamanya 1-6 Bulan) dan dalam menjalankan sanksi ini karyawan tetap
                          mendapatkan GAJI POKOK.</span></p>
                          <p style="text-align:justify"><span>&nbsp;</span></p>
                          <p style="text-align:justify;text-indent:
                          -18.0pt"><b><span><span>11.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span></span></b><b><u><span>PERATURAN PERUSAHAAN</span></u></b></p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>11.1.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Pihak Managemen WAKI berhak
                          merubah atau menambah Peraturan Perusahaan ini<span>&nbsp;
                          </span>sewaktu-waktu tanpa pemberitahuan terlebih dahulu.</span>
                          </p>
                          <p style="margin-left:18.0pt;text-align:justify;text-indent:
                          -21.6pt"><span><span>11.2.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;
                          </span></span>
                              </span><span>Apabila ada kasus-kasus atau
                          masalah-masalah khusus, Pihak Managemen WAKI tertinggi, berhak mempertimbangkan
                          dengan melihat latar belakang permasalahannya.</span></p>
                          <p class="MsoNormalM"><span>&nbsp;</span></p>
                          <p class="MsoNormalM"><span>Demikian peraturan dan<span>&nbsp; </span>tata tertib ini dibuat, dan mulai berlaku sejak tanggal ditetapkan termasuk dengan perubahan-perubahan pada masa mendatang.
                              </span>
                          </p>
                        </div>

                        <div id="secondAgreementContent" hidden>
                          <p style="line-height:150%"><b><span style="font-size:12.0pt;line-height:150%;font-family:
                          &quot;Times New Roman&quot;,serif">Peraturan Absen WAKi Reborn</span></b></p>
                          <p style="margin-left:18.0pt;text-indent:-18.0pt"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif"><span>1.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp; </span></span>
                              </span><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif">Absen
                          <b>Pagi sebelum jam 10.00</b>,dengan
                          ketentuan sbb:</span></p>
                          <blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">
                              <p style="text-indent:-18.0pt"><span style="font-size:12.0pt;line-height:107%;font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                                  </span><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif">Harus
                          ada photo dan Voice Note dengan seluruh team lengkap</span></p>
                              <p style="text-indent:-18.0pt"><span style="font-size:12.0pt;line-height:107%;font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                                  </span><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif">Apabila
                          ada anggota team yang sudah dalam perjalanan tugas/kerja, maka mereka
                          photo+voice note sendiri dengan disertai keterangan</span></p>
                          </blockquote>
                          <p style="margin-left:18.0pt;text-indent:-18.0pt"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif"><span>2.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp; </span></span>
                              </span><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif">Absen
                          <b>Malam jam 21.00/sesuai dengan jam kerja
                          terkini</b>, dengan ketentuan sbb :</span></p>
                          <blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">
                              <p style="text-indent:-18.0pt"><span style="font-size:12.0pt;line-height:107%;font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                                  </span><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif">Harus
                          ada foto Team lengkap dan Voice Note</span></p>
                              <p style="text-indent:-18.0pt"><span style="font-size:12.0pt;line-height:107%;font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                                  </span><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif">Apabila
                          ada anggota team yang masih dirumah customer/dalam perjalanan pulang dari rumah
                          customer, maka mereka photo+voice note sendiri dengan disertai keterangan</span></p>
                          </blockquote>
                          <p style="margin-left:18.0pt;text-indent:-18.0pt"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif"><span>3.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp; </span></span>
                              </span><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif">Setiap
                          hari sebelum Photo Absensi, wajib post ulang jadwal kerja / Off bulanan</span></p>
                          <p style="margin-left:18.0pt;text-indent:-18.0pt"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif"><span>4.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp; </span></span>
                              </span><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif">Pakaian
                          kerja harus rapi, berkemeja/blazer dan tidak boleh pakai kaos tanpa kerah</span></p>
                          <p style="margin-left:18.0pt;text-indent:-18.0pt"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif"><span>5.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp; </span></span>
                              </span><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif">Terlambat
                          masuk kerja maka kena penalti Rp 100rb/hari (Spv/Ast/Senior) dan Rp 50rb/hari
                          untuk sales dibawah 3 bulan</span></p>
                          <p style="margin-left:18.0pt;text-indent:-18.0pt"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif"><span>6.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp; </span></span>
                              </span><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif">Ijin
                          sakit harus ada surat keterangan dokter, langsung pada hari itu juga, jika
                          tidak ada, maka dianggap tanpa keterangan / Absen dan dikenakan denda / penalti
                          Rp 100rb/hari (Spv/Ast/Senior) dan Rp 50rb/hari untuk sales dibawah 3 bulan</span></p>
                          <p style="margin-left:18.0pt;text-indent:-18.0pt"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif"><span>7.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp; </span></span>
                              </span><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif">Hari
                          Off tidak sesuai jadwal yang sudah disetor pada awal bulan, harus ada ACC dari
                          Pimpinan (bukti Acc harus dikirim ke Group). Untuk Spv/Asst.Spv harus dapat Acc
                          dari <span style="color:red">Pak Erik/Ibu Diva</span>, dan untuk anggota team harus ada ACC dari <span style="color:red">Pak Bayu/Pak Supri </span></span>
                          </p>
                          <p style="margin-left:18.0pt;text-indent:-18.0pt"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif"><span>8.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp;
                          </span></span>
                              </span><span style="font-size:12.0pt;
                          line-height:107%;font-family:&quot;Times New Roman&quot;,serif">SANKSI CUTI/OFF DAY TANPA
                          ACC DARI ( <span style="color:red">PERUSAHAAN </span>) : </span>
                          </p>
                          <blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">
                              <p style="text-indent:-18.0pt"><span style="font-size:12.0pt;line-height:107%;font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                                  </span><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif">KOORDINATOR
                          <b>(Rp 2juta perhari) .</b></span></p>
                              <p style="text-indent:-18.0pt"><span style="font-size:12.0pt;line-height:107%;font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                                  </span><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif">SUPERVISOR/WAKIL/senior
                          lebih 3 bulan <b>(Rp 1juta perhari)</b></span></p>
                              <p style="text-indent:-18.0pt"><span style="font-size:12.0pt;line-height:107%;font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                                  </span><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif">Junior
                          dibawah 3 bulan <b>(Rp 500ribu perhari).</b></span></p>
                              <p style="text-indent:-18.0pt"><span style="font-size:12.0pt;line-height:107%;font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                                  </span><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif">Pengajuan
                          cuti adalah 1 (satu) bulan<b> sebelumnya.</b></span></p>
                              <p style="text-indent:-18.0pt"><span style="font-size:12.0pt;line-height:107%;font-family:Wingdings"><span>§<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                                  </span><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif">Pergantian
                          <b>OFF DAY</b> harus diajukan <b>3 hari sebelumnya.</b></span></p>
                          </blockquote>
                          <p style="margin-left:18.0pt;text-indent:-18.0pt"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif"><span>9.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp; </span></span>
                              </span><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif">Surat cuti harus ada tanda tangan<span>&nbsp; </span>Spv dan Koordinator, dan di ajukan 1 bulan
                              <span>&nbsp;&nbsp; </span>sebelumnya </span><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif">.</span></p>
                          <p style="margin-left:18.0pt;text-indent:-18.0pt"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif"><span>10.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp; </span></span>
                              </span><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif">Pegawai yang sudah Resign, tidak bisa masuk kerja
                          kembali sebelum 1th. Pada saat Resign surat pengunduran diri harus di photo di
                          Group, dan saat masuk kembali harus ada laporan di karyawan Baru.</span></p>
                          <p style="margin-left:54.0pt"><span style="font-size:12.0pt;line-height:107%;font-family:&quot;Times New Roman&quot;,serif">&nbsp;</span></p>
                          <p class="MsoNoSpacingM"><span style="font-size:12.0pt;font-family:&quot;Times New Roman&quot;,serif">Catatan
                          <span>&nbsp;</span>tambahan:</span>
                          </p>
                          <p style="margin-left:18.0pt;text-indent:-18.0pt"><span style="font-size:12.0pt;font-family:
                          &quot;Times New Roman&quot;,serif"><span>1.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp;
                          </span></span>
                              </span><span style="font-size:12.0pt;font-family:&quot;Times New Roman&quot;,serif">Apabila
                          Absensi tanpa Photo dan Voice Note dianggap Tanpa Keterangan (Absen)</span></p>
                          <p style="margin-left:18.0pt;text-indent:-18.0pt"><span style="font-size:12.0pt;font-family:
                          &quot;Times New Roman&quot;,serif"><span>2.<span style="font:7.0pt &quot;Times New Roman&quot;">&nbsp;&nbsp;&nbsp;&nbsp;
                          </span></span>
                              </span><span style="font-size:12.0pt;font-family:&quot;Times New Roman&quot;,serif">Mulai
                          berlaku pada tanggal 10 september 2020</span></p>
                        </div>

                      </div>

                      <div class="my-3 agreeBtnSection col-12 row no-gutters justify-content-end">
                        <div class="col-6 col-sm-3 col-md-2 p-1">
                          <a href="{{ route('login') }}" class="btn btn-block btn-gradient-primary btn-sm font-weight-medium auth-form-btn">
                              Kembali
                          </a>
                        </div>
                        <div class="col-6 col-sm-3 col-md-2 p-1">
                          <button id="nextAgreement" class="btn btn-block btn-gradient-primary btn-sm font-weight-medium auth-form-btn">
                              Setuju
                          </button>
                          <button id="scndAgreement" type="submit" class="btn btn-block btn-gradient-primary btn-sm font-weight-medium auth-form-btn m-0" hidden>
                              {{ __('LOGIN') }}
                          </button>
                        </div>
                      </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.slim.min.js" integrity="sha512-6ORWJX/LrnSjBzwefdNUyLCMTIsGoNP6NftMy2UAm1JBm6PRZCO1d7OHBStWpVFZLO+RerTvqX/Z9mBFfCJZ4A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
  $(document).ready(function () {
    $('#nextAgreement').click(function(){
      $('#firstAgreementContent').attr("hidden",true);
      $('#secondAgreementContent').removeAttr('hidden');
        $('#nextAgreement').attr("hidden",true);
        $('#scndAgreement').removeAttr('hidden');
    });

  });

</script>
@endsection
