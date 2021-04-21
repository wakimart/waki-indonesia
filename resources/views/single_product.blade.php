@extends('layouts.template')

@section('content')
<!--==========================Intro Section============================-->
<section id="hero" class="clearfix">
	{{--<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
	  <div class="carousel-inner">
	    <div class="carousel-item active">
	      <img class="d-block w-100" src="{{asset('sources/waki-carousel1.jpg')}}" alt="First slide">
	    </div>
	    <div class="carousel-item">
	      <img class="d-block w-100" src="{{asset('sources/waki-carousel2.jpg')}}" alt="Second slide">
	    </div>
	    <div class="carousel-item">
	      <img class="d-block w-100" src="{{asset('sources/waki-carousel3.jpg')}}" alt="Third slide">
	    </div>
	  </div>

	  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
	    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
	    <span class="sr-only">Previous</span>
	  </a>

	  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
	    <span class="carousel-control-next-icon" aria-hidden="true"></span>
	    <span class="sr-only">Next</span>
	  </a>
	</div>--}}
	<div class="container d-flex h-100">
    <div class="row justify-content-center align-self-center" data-aos="fade-up">
      <div class="col-md-6 hero-info order-md-first order-last" data-aos="zoom-in" data-aos-delay="100">
        <h2>Selamat datang di WAKi International Group</h2>
        <p>WAKi adalah perusahaan alat kesehatan yang mengunggulkan kualitas terjamin, untuk membantu keluarga menuju kehidupan yang lebih baik. </p>
        <div>
          <!-- <a href="#main" class="btn-get-started scrollto">Mulai</a> -->
          <a href="#myModal" id="mobile" class="btn-mobile" data-toggle="modal" data-target="#video2" style="margin-left: 0.5em;">Lihat Video<span style="padding-left: 0.5em;"><i class="far fa-play-circle"></i></span></a>
        </div>
      </div>

      <div class="col-md-6" style="flex: none;">
        <div class="text-right" style="position: relative; top: 50%; transform: translateY(-50%);" data-toggle="modal" data-target="#video2">
          <img src="{{asset('sources/play.png')}}" alt="" class="icon-play img-fluid">
        </div>

        {{-- <div class="overlay-inner overlay-video embed-responsive embed-responsive-4by3">
          <video muted onclick="this.paused?this.play():this.pause();" loop style="min-height: 100%";>
            <source src="{{asset('sources/Introduction of WAKi short.mp4')}}" type="video/mp4">
          </video>
        </div> --}}
      </div>

      <div id="video2" class="modal fade videoModal" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
              <div class="overlay-inner overlay-video embed-responsive embed-responsive-4by3">
                <video id="video-2" muted onclick="this.paused?this.play():this.pause();" loop style="min-height: 100%";>
                  <source src="{{asset('sources/Introduction of WAKi short.mp4')}}" type="video/mp4">
                </video>
              </div>

            </div>

          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<section id="intro" class="clearfix">
	<div class="container">
  		<div class="col-lg-12 col-md-12" style="padding:0;">
		    <div class="intro-content">
		      <br>
		      <h2>WAKi High Potential Therapy Range</h2>
		    </div>
  		</div>

  		<div class="col-lg-12 col-md-12" style="margin-top: 2em; padding:0;">
    		<div class="row">
      			<div class="col-lg-4 col-md-4 col-sm-12">
        			<div class="product_categoriesmenu">
          				<h5 class="prodcat-title">Product categories</h5>
          				<ul class="product-categories">
							@foreach ($categoryProducts as $categoryProduct )
								<li class="cat-item cat-item-24 current-cat"><a href="{{route('product_category',['id' => $categoryProduct->id])}}" id=categoryId value={{$categoryProduct->id}} >{{$categoryProduct->name}}</a></li>
							@endforeach
						</ul>
        			</div>
      			</div>

      			<div class="col-lg-8 col-md-8 col-sm-12">
        			<div class="col-sm-12 col-md-12 col-lg-12">
          				<div class="row">
            				<div class="col-sm-12 col-md-6 col-lg-6 wow fadeInRight prodpd" data-wow-duration="1.4s">
              					<div class="image-zoom">
                            @php
                                $img = json_decode($product->image);
                                $defaultImg = asset('sources/product_images/').'/'.strtolower($product['code']).'/'.$img[0];
                            @endphp
                					<img src="{{ $defaultImg }}" data-zoom="enabled" style="background: center top no-repeat; background-size: contain; width: 100%; padding:1em;">
              					</div>
              <!-- <div>
                <img src="https://waki.asia/wp-content/uploads/2019/05/wkt2080-1-300x300.jpg" style="background: center top no-repeat; background-size: contain; width: 100%;">
              </div> -->
              					<div class="titleprd">
									<p class="titleprodcat">{{ $product->code }} – {{ $product->name }}</p>
              					</div>
            				</div>

            				<div class="col-sm-12 col-md-6 col-lg-6 wow fadeInRight prodpd" data-wow-duration="1.4s">
              					<div class="titleprd">
                					<h4>{{ $product->code }} – {{ $product->name }}</h4>
            						  <p class="titleprodcat">
                  					{!! $product->quick_desc !!}
                          </p>
                					<p class="titleprodcat">
                  					Category : <a href="#">{{ $product->category['name'] }}</a>
                          </p>
              					</div>
            				</div>

        					<div class="col-sm-12 col-md-12 col-lg-12 wow fadeInRight prodpd" data-wow-duration="1.4s" style="margin-top: 1em;">
              					<div class="titleprd">
                          <div>
                            {!! $product->description !!}
                          </div>

                					<div class="descvid">
                  					<iframe width="100%" height="250px" position="relative" src="{{ $product->video }}" frameborder="0" allowfullscreen=""></iframe>
                					</div>
              					</div>
            				</div>
          				</div><!-- row -->
        			</div>
      			</div>
    		</div><!-- row -->
  		</div>

  		<div class="col-lg-12 col-md-12" style="margin-top: 2em;padding:0;">
		    <div class="intro-content">
		      <br>
		      <h2>Related Product</h2>
		    </div>
    		<div class="col-lg-12 col-md-12">
    			<div class="row">
      				<div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp prodpd" data-wow-duration="1.4s">
				        <div class="boxprd">
				          <img src="https://waki.asia/wp-content/uploads/2019/05/wkt2080-1-300x300.jpg" style="background: center top no-repeat; background-size: contain; width: 100%;">
				        </div>
				        <div class="titleprd">
				          <p class="titleprodcat">WKT2080 – WAKi Multi Functional Electro Massager Equipment</p>
				        </div>
				        <div class="buttonprd">
				          <a href="single_product.html" class="button">Read More</a>
				        </div>
      				</div>
      			<div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp prodpd" data-wow-duration="1.4s">
			        <div class="boxprd">
			          <img src="https://waki.asia/wp-content/uploads/2017/08/WAKi-High-Potential-Therapeutic-Equipment-wk2079-edit-300x300.jpg" style="background: center top no-repeat; background-size: contain; width: 100%;">
			        </div>
			        <div class="titleprd">
			          <p class="titleprodcat">WK2079 – WAKi High Potential Therapeutic Equipment</p>
			        </div>
			        <div class="buttonprd">
			          <a href="#" class="button">Read More</a>
			        </div>
      			</div>
      			<div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp prodpd" data-wow-duration="1.4s">
			        <div class="boxprd">
			          <img src="https://waki.asia/wp-content/uploads/2017/07/wk2076i-300x300.jpg" style="background: center top no-repeat; background-size: contain; width: 100%;">
			        </div>
			        <div class="titleprd">
			          <p class="titleprodcat">WK2076i – WAKi Multi Functional Ultra Potential Therapy Healthcare Device</p>
			        </div>
			        <div class="buttonprd">
			          <a href="single_product.html" class="button">Read More</a>
			        </div>
      			</div>
      			<div class="col-sm-6 col-md-3 col-lg-3 wow fadeInUp prodpd" data-wow-duration="1.4s">
			        <div class="boxprd">
			          <img src="https://waki.asia/wp-content/uploads/2017/07/WAKi-Multi-Functional-High-Potential-Therapeutic-Equipment-wk2076h-300x300.jpg" style="background: center top no-repeat; background-size: contain; width: 100%;">
			        </div>
			        <div class="titleprd">
			          <p class="titleprodcat">WK2076H – Waki Multi Functional High Potential Therapeutic Equipment</p>
			        </div>
			        <div class="buttonprd">
			          <a href="#" class="button">Read More</a>
			        </div>
      			</div>
    		</div><!-- row -->
    	</div>
  	</div>
	</div><!-- container -->
</section>
@endsection
