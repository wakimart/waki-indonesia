<?php
    $menu_item_page = "index_frontendcms";
?>
@extends('admin.layouts.template')

@section('style')
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
@endsection

@section('content')
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
                    	
                    	<form id="actionUpdate" class="forms-sample" method="POST" action="{{route('update_frontendcms')}}">
                          @php
                              $img = json_decode($banners['image']);
                              $defaultImg = asset('sources/banners/');
                          @endphp

                          @for($i=0;$i<6;$i++)
                      		<div class="col-xs-12 col-sm-6 col-md-4 form-group imgUp" style="padding: 15px; float: left;">
                          		<label>Banner Image {{$i+1}}</label>
                        		  @if(!empty($img[$i]->url))
                              @php ($imgUrl = asset('sources/banners/'.$img[$i]->img))
                              <div class="imagePreview" style="background-image: url();"></div>

                              <span>Link ke produk / promo banner</span>
                              <input type="text" name="url_banner{{$i}}" class="text-uppercase form-control" placeholder="URL BANNER" value="{{$img[$i]->url}}" style="margin: 5px 0px;">

                              <label class="file-upload-browse btn btn-gradient-primary" style="margin-top: 15px;">Upload
                                  <input name="arr_image{{$i}}" id="bannerimg-{{$i}}" type="file" accept=".jpg,.jpeg,.png" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                              </label>
                              <i class="mdi mdi-window-close del"></i>

                              @else
                              <div class="imagePreview" style="background-image: url({{asset('sources/dashboard/no-img-banner.jpg')}});"></div>

                              <span>Link ke produk / promo banner</span>
                              <input type="text" name="url_banner{{$i}}" class="text-uppercase form-control" placeholder="URL BANNER" value="" style="margin: 5px 0px;">

                              <label class="file-upload-browse btn btn-gradient-primary" style="margin-top: 15px;">Upload
                                  <input name="arr_image{{$i}}" id="bannerimg-{{$i}}" type="file" accept=".jpg,.jpeg,.png" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                              </label>
                              <i class="mdi mdi-window-close del"></i>
                              @endif
                      		</div>
                          @endfor

                      		<button id="updateBanner" type="submit" class="btn btn-gradient-primary mr-2">Simpan</button>
                      		<button class="btn btn-light">Batal</button>
                    	</form>

                  	</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
      var banner = JSON.parse(<?php echo json_encode($banners['image']); ?>);
      var urlNya = "<?php echo asset('sources/banners/'); ?>";

      for (var j = 0; j < 6; j++) {
          //console.log("test: " + banner[j]['url']);
          if(banner[j]['url'] != null){
              var namaGambar = banner[j]['img'].replace(new RegExp(' ', 'g'), "%20");
              $("#bannerimg-"+j).closest(".imgUp").find('.imagePreview').css("background-image", "url("+urlNya+"/"+namaGambar+")");
              console.log(namaGambar);
          }
      }
  });

  $(document).ready(function() {
        var frmUpdate;

      $("#actionUpdate").on("submit", function (e) {
          e.preventDefault();
          frmUpdate = _("actionUpdate");
          frmUpdate = new FormData(document.getElementById("actionUpdate"));
          frmUpdate.enctype = "multipart/form-data";

          for (var i = 0; i < 6; i++)
          {
              frmUpdate.append('arr_image'+i, $("#bannerimg-"+i)[0].files[0]);
              if($("#bannerimg-"+i)[0].files[0]!=null)
              {
                  for(var j=0; j<deleted_img.length;j++)
                  {
                      //console.log()
                      if(deleted_img[j]==i)
                      {
                          deleted_img.splice(j,1);
                      }
                  }
              }
              console.log("test" + i);
          }

          frmUpdate.append('total_images', 6);
          frmUpdate.append('dlt_img', deleted_img);

          var URLNya = $("#actionUpdate").attr('action');
          console.log(URLNya);

          var ajax = new XMLHttpRequest();
          ajax.upload.addEventListener("progress", progressHandler, false);
          ajax.addEventListener("load", completeHandler, false);
          ajax.addEventListener("error", errorHandler, false);
          ajax.open("POST", URLNya);
          ajax.setRequestHeader("X-CSRF-TOKEN",$('meta[name="csrf-token"]').attr('content'));
          ajax.send(frmUpdate);
      });
      function progressHandler(event){
          document.getElementById("updateBanner").innerHTML = "UPLOADING...";
      }
      function completeHandler(event){
          var hasil = JSON.parse(event.target.responseText);
          console.log(hasil);

          $("#actionAdd").find('input[name^="url_banner"]').each(function() {
              $(this).removeClass("is-invalid");
              $(this).next().find("strong").text("");
          });

          if(hasil['errors'] != null){
              var temp = hasil['errors'];
              for(var i=0; i<Object.keys(hasil['errors']).length; i++){
                  $('[name="'+Object.keys(temp)[i]+'"]').addClass("is-invalid");
                  $('[name="'+Object.keys(temp)[i]+'"]').next().find("strong").text(temp[Object.keys(temp)[i]]);
              }

              alert("Input Error !!!");
          }

          // for (var key of frmUpdate.keys()) {
          //     $("#actionUpdate").find("input[name="+key.name+"]").removeClass("is-invalid");
          //     $("#actionUpdate").find("select[name="+key.name+"]").removeClass("is-invalid");
          //     $("#actionUpdate").find("textarea[name="+key.name+"]").removeClass("is-invalid");

          //     $("#actionUpdate").find("input[name="+key.name+"]").next().find("strong").text("");
          //     $("#actionUpdate").find("select[name="+key.name+"]").next().find("strong").text("");
          //     $("#actionUpdate").find("textarea[name="+key.name+"]").next().find("strong").text("");
          // }

          // if(hasil['errors'] != null){
          //     for (var key of frmUpdate.keys()) {
          //         if(typeof hasil['errors'][key] === 'undefined') {
                      
          //         }
          //         else {
          //             $("#actionUpdate").find("input[name="+key+"]").addClass("is-invalid");
          //             $("#actionUpdate").find("select[name="+key+"]").addClass("is-invalid");
          //             $("#actionUpdate").find("textarea[name="+key+"]").addClass("is-invalid");

          //             $("#actionUpdate").find("input[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
          //             $("#actionUpdate").find("select[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
          //             $("#actionUpdate").find("textarea[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
          //         }
          //     }
          //     alert("Input Error !!!");
          // }
          else{
              // alert("Input Success !!!");
              // window.location.reload()
          }

          document.getElementById("updateBanner").innerHTML = "SAVE";
      }
      function errorHandler(event){
          document.getElementById("updateBanner").innerHTML = "SAVE";
      }
    });
</script>
<script type="text/javascript">
  var deleted_img=[];

  $(document).on("click", "i.del" , function() {
      $(this).closest(".imgUp").find('.imagePreview').css("background-image", "");
      $(this).closest(".imgUp").find('input[type=text]').removeAttr("required");
      $(this).closest(".imgUp").find('.btn').find('.img').val("");
      $(this).closest(".imgUp").find('.form-control').val("");
      deleted_img.push($(this).closest(".imgUp").find(".img").attr('id').substring(8));
      console.log($(this).closest(".imgUp").find('.btn').find('.img').val());
      console.log("yg dihapus: " + deleted_img);
  });

  $(function() {
      $(document).on("change",".uploadFile", function()
      {
          var uploadFile = $(this);
          var files = !!this.files ? this.files : [];
          if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
   
          if (/^image/.test( files[0].type)){ // only image file
              var reader = new FileReader(); // instance of the FileReader
              reader.readAsDataURL(files[0]); // read the local file
   
              reader.onloadend = function(){ // set image data as background of div
                  //alert(uploadFile.closest(".upimage").find('.imagePreview').length);
                  uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url("+this.result+")");
              }
          }
        
      });
  });
</script>
@endsection