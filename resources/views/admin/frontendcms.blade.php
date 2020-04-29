@extends('admin.layouts.template')
@section('content')
<style type="text/css">
	.imagePreview {
	    width: 100%;
	    height: 150px;
	    background-position: center center;
	    background-color: #fff;
	    background-size: cover;
	    background-repeat: no-repeat;
	    display: inline-block;
	 }
	 
	.del {
	    position: absolute;
	    top: 0px;
	    right: 10px;
	    width: 30px;
	    height: 30px;
	    text-align: center;
	    line-height: 30px;
	    background-color: rgba(255,255,255,0.6);
	    cursor: pointer;
	}
</style>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Frontend Content Management System</h3>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                  	<div class="card-body">
                    	<h4 class="card-title">BANNER IMAGES</h4>
                    	<p class="card-description"> Image Size (1280x500 pixel) </p>
                    	
                    	@php
                    		$i = 0;
                    	@endphp
                    	<form class="forms-sample">
                      		<div class="col-xs-12 col-sm-6 col-md-4 form-group imgUp" style="padding: 15px; float: left;">
                          		<label>Banner Image 1</label>
                          		<div class="imagePreview" style="background-image: url({{asset('sources/dashboard/no-img-banner.jpg')}});"></div>
                          		<span>Link ke produk / promo banner</span>
                          		<input type="text" name="url_banner{{$i}}" class="text-uppercase form-control" placeholder="URL BANNER" value="" style="margin: 5px 0px;" readonly="">

                          		<label class="file-upload-browse btn btn-gradient-primary" style="margin-top: 15px;">Upload
                              		<input name="arr_image{{$i}}" id="bannerimg-{{$i}}" type="file" accept=".jpg,.jpeg,.png" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                          		</label>
                          		<i class="mdi mdi-window-close del"></i>
                      		</div>

                      		<div class="col-xs-12 col-sm-6 col-md-4 form-group imgUp" style="padding: 15px; float: left;">
                          		<label>Banner Image 2</label>
                          		<div class="imagePreview" style="background-image: url({{asset('sources/dashboard/no-img-banner.jpg')}});"></div>
                          		<span>Link ke produk / promo banner</span>
                          		<input type="text" name="url_banner{{$i}}" class="text-uppercase form-control" placeholder="URL BANNER" value="" style="margin: 5px 0px;" readonly="">

                          		<label class="file-upload-browse btn btn-gradient-primary" style="margin-top: 15px;">Upload
                              		<input name="arr_image{{$i}}" id="bannerimg-{{$i}}" type="file" accept=".jpg,.jpeg,.png" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                          		</label>
                          		<i class="mdi mdi-window-close del"></i>
                      		</div>

                      		<div class="col-xs-12 col-sm-6 col-md-4 form-group imgUp" style="padding: 15px; float: left;">
                          		<label>Banner Image 3</label>
                          		<div class="imagePreview" style="background-image: url({{asset('sources/dashboard/no-img-banner.jpg')}});"></div>
                          		<span>Link ke produk / promo banner</span>
                          		<input type="text" name="url_banner{{$i}}" class="text-uppercase form-control" placeholder="URL BANNER" value="" style="margin: 5px 0px;" readonly="">

                          		<label class="file-upload-browse btn btn-gradient-primary" style="margin-top: 15px;">Upload
                              		<input name="arr_image{{$i}}" id="bannerimg-{{$i}}" type="file" accept=".jpg,.jpeg,.png" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                          		</label>
                          		<i class="mdi mdi-window-close del"></i>
                      		</div>

                      		<div class="col-xs-12 col-sm-6 col-md-4 form-group imgUp" style="padding: 15px; float: left;">
                          		<label>Banner Image 4</label>
                          		<div class="imagePreview" style="background-image: url({{asset('sources/dashboard/no-img-banner.jpg')}});"></div>
                          		<span>Link ke produk / promo banner</span>
                          		<input type="text" name="url_banner{{$i}}" class="text-uppercase form-control" placeholder="URL BANNER" value="" style="margin: 5px 0px;" readonly="">

                          		<label class="file-upload-browse btn btn-gradient-primary" style="margin-top: 15px;">Upload
                              		<input name="arr_image{{$i}}" id="bannerimg-{{$i}}" type="file" accept=".jpg,.jpeg,.png" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                          		</label>
                          		<i class="mdi mdi-window-close del"></i>
                      		</div>

                      		<div class="col-xs-12 col-sm-6 col-md-4 form-group imgUp" style="padding: 15px; float: left;">
                          		<label>Banner Image 5</label>
                          		<div class="imagePreview" style="background-image: url({{asset('sources/dashboard/no-img-banner.jpg')}});"></div>
                          		<span>Link ke produk / promo banner</span>
                          		<input type="text" name="url_banner{{$i}}" class="text-uppercase form-control" placeholder="URL BANNER" value="" style="margin: 5px 0px;" readonly="">

                          		<label class="file-upload-browse btn btn-gradient-primary" style="margin-top: 15px;">Upload
                              		<input name="arr_image{{$i}}" id="bannerimg-{{$i}}" type="file" accept=".jpg,.jpeg,.png" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                          		</label>
                          		<i class="mdi mdi-window-closes del"></i>
                      		</div>

                      		<div class="col-xs-12 col-sm-6 col-md-4 form-group imgUp" style="padding: 15px; float: left;">
                          		<label>Banner Image 6</label>
                          		<div class="imagePreview" style="background-image: url({{asset('sources/dashboard/no-img-banner.jpg')}});"></div>
                          		<span>Link ke produk / promo banner</span>
                          		<input type="text" name="url_banner{{$i}}" class="text-uppercase form-control" placeholder="URL BANNER" value="" style="margin: 5px 0px;" readonly="">

                          		<label class="file-upload-browse btn btn-gradient-primary" style="margin-top: 15px;">Upload
                              		<input name="arr_image{{$i}}" id="bannerimg-{{$i}}" type="file" accept=".jpg,.jpeg,.png" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                          		</label>
                          		<i class="mdi mdi-window-close del"></i>
                      		</div>

                      		<button type="submit" class="btn btn-gradient-primary mr-2">Simpan</button>
                      		<button class="btn btn-light">Batal</button>
                    	</form>

                  	</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection