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
</style>


<section id="intro" class="clearfix">
    <div class="container">
        <div class="row justify-content-center">
            <h2>FORM PEMESANAN</h2>
            <form action="{{ Route('store_delivery_order') }}" method="post" role="form" class="contactForm col-md-9">
                @csrf
                <div class="form-group">
                    <input type="text" name="no_member" class="form-control" id="no_member" placeholder="No. Member" required data-msg="Mohon Isi Nomor Member" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Nama" required data-msg="Mohon Isi Nama" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="phone" id="phone" placeholder="No. Telepon" required data-msg="Mohon Isi Nomor Telepon" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <textarea class="form-control" name="address" rows="5" required data-msg="Mohon Isi Alamat" placeholder="Alamat"></textarea>
                    <div class="validation"></div>
                </div>

                @for($j=0;$j<4;$j++)
                    <div class="form-group" style="width: 80%; display: inline-block;">
                        <select class="form-control" name="product_{{ $j }}" data-msg="Mohon Pilih Promo" {{ $j>0 ? "":"required"}}>
                            <option selected disabled value="">Pilihan Promo{{ $j>0 ? " (optional)":""}}</option>

                            @foreach($promos as $key=>$promo)
                                <option value="{{ $key }}">{{ $promo['code'] }} - {{ $promo['name'] }}</option>
                            @endforeach
                        </select>
                        <div class="validation"></div>
                    </div>
                    <div class="form-group" style="width: 16%; display: inline-block; margin-left: 2%">
                        <select class="form-control" name="qty_{{ $j }}" data-msg="Mohon Pilih Jumlah" {{ $j>0 ? "":"required"}}>
                            <option selected value="1">1</option>

                            @for($i=2; $i<=10;$i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        <div class="validation"></div>
                    </div>
                @endfor

                <div class="form-group">
                    <input type="text" class="form-control" name="cso_code" id="cso_code" placeholder="Kode CSO (optional)"/>
                    <div class="validation"></div>
                </div>

                <div id="errormessage"></div>
                <div class="text-center"><button type="submit" title="Send Message">Simpan Form Pemesanan</button></div>
            </form>
        </div>
    </div>
</section>
@endsection
