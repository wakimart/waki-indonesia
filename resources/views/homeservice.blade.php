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
</style>

<section id="intro" class="clearfix">
    <div class="container">
        <div class="row justify-content-center">
            <h2>FORM HOME SERVICE</h2>
        </div>
        <div class="row justify-content-center">
            <form action="{{ Route('store_order') }}" method="post" role="form" class="contactForm col-md-9">
                @csrf
                <br>
                <h5>Data Pelanggan</h5>
                <div class="form-group">
                    <input type="text" name="no_member" class="form-control" id="no_member" placeholder="No. Member (optional)"/>
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Nama" required data-msg="Mohon Isi Nama" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Nomor Telepon" required data-msg="Mohon Isi Nomor Telepon" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="city" id="city" placeholder="Kota" required data-msg="Mohon Isi Kota" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <textarea class="form-control" name="address" rows="5" required data-msg="Mohon Isi Alamat" placeholder="Alamat"></textarea>
                    <div class="validation"></div>
                </div>
                <br>
                <h5>Data CSO</h5>
                <div class="form-group">
                    <select class="form-control" id="branch" name="branch_id" data-msg="Mohon Pilih Cabang" required>
                        <option selected disabled value="">Pilihan Cabang</option>

                        @foreach($branches as $branch)
                            <option value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
                        @endforeach
                    </select>
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="cso_id" id="cso" placeholder="Kode CSO" required data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase"/>
                    <div class="validation" id="validation_cso"></div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="phone" id="phone" placeholder="No. Telepon CSO" required data-msg="Mohon Isi Nomor Telepon" />
                    <div class="validation"></div>
                </div>
                
                <br>
                <h5>Waktu Home Service</h5>
                <div class="form-group">
                    <input type="date" class="form-control" name="date" id="date" placeholder="Tanggal Janjian" value="<?php echo date('Y-m-j'); ?>" required data-msg="Mohon Isi Tanggal" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <input type="time" class="form-control" name="time" id="time" placeholder="Jam Janjian" value="<?php echo date('H:m'); ?>" required data-msg="Mohon Isi Jam" />
                    <div class="validation"></div>
                </div>

                <div id="errormessage"></div>
                <div class="text-center"><button id="submit" type="submit" title="Send Message" disabled="">Simpan Form Home Service</button></div>
            </form>
        </div>
    </div>
</section>
@endsection
