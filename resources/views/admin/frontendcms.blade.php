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

                    	<form id="actionUpdate" class="forms-sample" method="POST" action="{{route('update_frontendcms')}}">
                        <div class="form-group">
                          <h4 class="card-title">BANNER IMAGES</h4>
                          <p class="card-description"> Image Size (1280x500 pixel) </p>
                          @php
                              $img = json_decode($banners['image']);
                              $defaultImg = asset('sources/banners/');
                          @endphp

                          @for($i=0;$i<6;$i++)

                      		<div class="col-xs-12 col-sm-6 col-md-4 form-group imgUp" style="padding: 15px; float: left;">
                          		<label>Banner Image {{$i+1}}</label>
                        		  @if(!empty($img[$i]->url))
                              @php ($imgUrl = asset('sources/banners/'.$img[$i]->img)) @endphp
                              <div class="imagePreview" style="background-image: url();"></div>

                              <span>Link ke produk / promo banner</span>
                              <input type="text" name="url_banner{{$i}}" class="text-uppercase form-control" placeholder="URL BANNER" value="{{$img[$i]->url}}" style="margin: 5px 0px;">

                              <label class="file-upload-browse btn btn-gradient-primary" style="margin-top: 15px;">Upload
                                  <input name="image_{{$i}}" id="gambars-{{$i}}" type="file" accept=".jpg,.jpeg,.png" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                              </label>
                              <i class="mdi mdi-window-close del delete_img"></i>

                              @else
                              <div class="imagePreview" style="background-image: url({{asset('sources/dashboard/no-img-banner.jpg')}});"></div>

                              <span>Link ke produk / promo banner</span>
                              <input type="text" name="url_banner{{$i}}" class="text-uppercase form-control" placeholder="URL BANNER" value="" style="margin: 5px 0px;">

                              <label class="file-upload-browse btn btn-gradient-primary" style="margin-top: 15px;">Upload
                                  <input name="image_{{$i}}" id="gambars-{{$i}}" type="file" accept=".jpg,.jpeg,.png" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                              </label>
                              <i class="mdi mdi-window-close del delete_img"></i>
                              @endif
                      		</div>
                          @endfor
                        </div>
                        <div class="clearfix"></div>

                        <div class="form-group">
                          <label id="btnAddPhoto" class="btn btn-gradient-primary" style="float: right;margin-bottom: 0px;">Add Photo</label>
                          <h4 class="card-title">GALLERY PHOTO</h4>
                          <p class="card-description"> Image Size (1280x500 pixel) </p>


                          @php
                              <!-- $photos = json_decode($galleries['photo']); -->
                              $defaultImg = asset('sources/portfolio/');

                              $count_photo = sizeof($photos);
                              if($count_photo == 0){
                                $count_photo++;
                              }
                          @endphp

                          @for($x = 0; $x < $count_photo; $x++)
                            <div id="photo_{{$x}}" class="col-xs-12 col-sm-6 col-md-4 form-group imgUp" style="padding: 15px; float: left;">
                                <label>Photo {{$x+1}}</label>
                                @if(!empty($photos[$x]))
                                <div class="imagePreview" style="background-image: url({{$defaultImg.'/'. $photos[$x]}});"></div>
                                @else
                                <div class="imagePreview" style="background-image: url({{asset('sources/dashboard/no-img-banner.jpg')}});"></div>
                                @endif
                                <label class="file-upload-browse btn btn-gradient-primary" style="margin-top: 15px;">Upload
                                    <input id="photos-{{$x}}" name="photo_{{$x}}" type="file" accept=".jpg,.jpeg,.png" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
                                </label>
                                <i class="mdi mdi-window-close del"></i>
                            </div>
                          @endfor

                          <div id="tambahan_photo"></div>

                        </div>
                        <div class="clearfix"></div>


                        <div class="form-group">
                          @php
                              $urlvideo = json_decode($galleries['url_youtube']);
                              $count_url = sizeof($urlvideo);
                          @endphp

                          <h4 class="card-title">GALLERY VIDEO</h4>
                          @if(!empty($urlvideo))
                            @for($v = 0; $v < $count_url; $v++)
                              <div id="video_{{$v}}" style="padding: 15px;">
                                <div class="form-group" style="width: 72%; display: inline-block;">
                                    <span>Video Title {{$v+1}}</span>
                                    <input type="text" name="title_{{$v}}" class="text-uppercase form-control" value="{{$urlvideo[$v]->title}}" style="margin: 5px 0px;">
                                    <div class="validation"></div>

                                    <span>URL Video {{$v+1}}</span>
                                    <input type="text" name="video_{{$v}}" class="text-uppercase form-control" value="{{$urlvideo[$v]->url}}" style="margin: 5px 0px;">
                                    <div class="validation"></div>
                                </div>

                                @if($v == 0)
                                  <span><label id="btnAddUrl" class="btn btn-gradient-primary" style="float: right;display: inline-block;margin-top: 1.8em;">Add URL</label></span>
                                @else
                                  <span><label class="btn btn-gradient-danger delete_url" style="float: right;display: inline-block;margin-top: 1.8em;" value="{{$v}}">Delete URL</label></span>
                                @endif
                              </div>
                            @endfor
                          @else
                            <div id="video_0" style="padding: 15px;">
                              <div class="form-group" style="width: 72%; display: inline-block;">
                                  <span>Video Title 1</span>
                                  <input type="text" name="title_0" class="text-uppercase form-control" placeholder="Video Title" style="margin: 5px 0px;">
                                  <div class="validation"></div>

                                  <span>URL Video 1</span>
                                  <input type="text" name="video_0" class="text-uppercase form-control" placeholder="URL Video" style="margin: 5px 0px;">
                                  <div class="validation"></div>
                              </div>

                              <span><label id="btnAddUrl" class="btn btn-gradient-primary" style="float: right;display: inline-block;margin-top: 2.5%;width: 16%;">Add URL</label></span>
                            </div>
                          @endif

                          <div id="tambahan_video"></div>


                          <input type="hidden" id="totalVideo" value="{{$count_url}}">
                          <input type="hidden" id="totalPhoto" value="{{$count_photo}}">
                        </div>
                      		<button id="updateBanner" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
                      		<button class="btn btn-light">Cancel</button>
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
  var total_photo = 0;
  var photo = 0;

  var total_video = 0;
  var video = 0;
  var counter = 0;

  var deleted_photo=[];

  $(document).ready(function () {
      var getTotalPhoto = $('#totalPhoto').val();
      console.log(getTotalPhoto);

      if(getTotalPhoto == 0){
        total_photo = 1;
        photo = 0;

        $('#btnAddPhoto').click(function(e){
          e.preventDefault();
          total_photo++;
          photo++;

          if(total_photo <= 30){
            strisi = "<div id=\"photo_"+photo+"\" class=\"col-xs-12 col-sm-6 col-md-4 form-group imgUp\" style=\"padding: 15px; float: left;\"><label>Photo "+total_photo+"</label><div class=\"imagePreview\" style=\"background-image: url({{asset('sources/dashboard/no-img-banner.jpg')}});\"></div><label class=\"file-upload-browse btn btn-gradient-primary\" style=\"margin-top: 15px;\">Upload<input name=\"photo_"+photo+"\" id=\"photos-"+photo+"\" type=\"file\" accept=\".jpg,.jpeg,.png\" class=\"uploadFile img\" value=\"Upload Photo\" style=\"width: 0px;height: 0px;overflow: hidden;\"></label><i class=\"mdi mdi-window-close del delete_photo\"></i></div>";

            $('#tambahan_photo').html($('#tambahan_photo').html()+strisi);
          }else{
            alert("Maksimum of photo is 30 photos");
          }

        });
      }else{
        photo = parseFloat(getTotalPhoto) - 1;
        total_photo = getTotalPhoto;

        $('#btnAddPhoto').click(function(e){
          e.preventDefault();
          total_photo++;
          photo++;

          if(total_photo <= 30){
            strisi = "<div id=\"photo_"+photo+"\" class=\"col-xs-12 col-sm-6 col-md-4 form-group imgUp\" style=\"padding: 15px; float: left;\"><label>Photo "+total_photo+"</label><div class=\"imagePreview\" style=\"background-image: url({{asset('sources/dashboard/no-img-banner.jpg')}});\"></div><label class=\"file-upload-browse btn btn-gradient-primary\" style=\"margin-top: 15px;\">Upload<input name=\"photo_"+photo+"\" id=\"photos-"+photo+"\" type=\"file\" accept=\".jpg,.jpeg,.png\" class=\"uploadFile img\" value=\"Upload Photo\" style=\"width: 0px;height: 0px;overflow: hidden;\"></label><i class=\"mdi mdi-window-close del delete_photo\"></i></div>";

            $('#tambahan_photo').html($('#tambahan_photo').html()+strisi);
          }else{
            alert("Maksimum of photo is 30 photos");
          }

        });
      }


      $(document).on("click", ".delete_photo" , function() {
          $(this).closest(".imgUp").find('.imagePreview').css("background-image", "");
          $(this).closest(".imgUp").find('input[type=text]').removeAttr("required");
          $(this).closest(".imgUp").find('.btn').find('.img').val("");
          $(this).closest(".imgUp").find('.form-control').val("");
          deleted_photo.push($(this).closest(".imgUp").find(".img").attr('id').substring(7));
          console.log($(this).closest(".imgUp").find('.btn').find('.img').val());
          console.log("photo yg dihapus: " + deleted_photo);
      });

      var getTotalUrl = $('#totalVideo').val();

      if(getTotalUrl == 0){
        total_video = 1;
        video = 0;

        $('#btnAddUrl').click(function(e){
          e.preventDefault();
          total_video++;
          video++;
          counter++;

          if(total_video <= 10){
            strisi = "<div id=\"video_"+video+"\" style=\"padding: 15px;\"><div class=\"form-group\" style=\"width: 72%; display: inline-block;\"><span>Video Title "+total_video+"</span><input type=\"text\" name=\"title_"+video+"\" class=\"text-uppercase form-control\" placeholder=\"Video Title\" style=\"margin: 5px 0px;\"><div class=\"validation\"></div><span>URL Video "+total_video+"</span><input type=\"text\" name=\"video_"+video+"\" class=\"text-uppercase form-control\" placeholder=\"URL Video\" style=\"margin: 5px 0px;\"><div class=\"validation\"></div></div><span><label class=\"btn btn-gradient-danger delete_url\" style=\"float: right;display: inline-block;margin-top: 1.8em;\" value=\""+video+"\">Delete URL</label></span></div>";

            $('#tambahan_video').html($('#tambahan_video').html()+strisi);
          }else{
            alert("Maksimum of video is 10 videos");
          }
        });
      }else{
        video = parseFloat(getTotalUrl) - 1;
        total_video = getTotalUrl;

        console.log("weeee" + total_video);

        $('#btnAddUrl').click(function(e){
          e.preventDefault();
          total_video++;
          video++;
          counter++;

          if(total_video <= 10){
            strisi = "<div id=\"video_"+video+"\" style=\"padding: 15px;\"><div class=\"form-group\" style=\"width: 72%; display: inline-block;\"><span>Video Title "+total_video+"</span><input type=\"text\" name=\"title_"+video+"\" class=\"text-uppercase form-control\" placeholder=\"Video Title\" style=\"margin: 5px 0px;\"><div class=\"validation\"></div><span>URL Video "+total_video+"</span><input type=\"text\" name=\"video_"+video+"\" class=\"text-uppercase form-control\" placeholder=\"URL Video\" style=\"margin: 5px 0px;\"><div class=\"validation\"></div></div><span><label class=\"btn btn-gradient-danger delete_url\" style=\"float: right;display: inline-block;margin-top: 1.8em;\" value=\""+video+"\">Delete URL</label></span></div>";

            $('#tambahan_video').html($('#tambahan_video').html()+strisi);
          }else{
            alert("Maksimum of video is 10 videos");
          }
        });
      }

      $(document).on("click",".delete_url", function(e){
          e.preventDefault();
          counter--;
          console.log("url yg dihapus: " + $(this).attr('value'));
          $('#video_'+$(this).attr('value')).remove();
          $('#title_'+$(this).attr('value')).remove();
          $(this).remove();
      });

      var banner = JSON.parse(<?php echo json_encode($banners['image']); ?>);
      var urlNya = "<?php echo asset('sources/banners/'); ?>";

      for (var j = 0; j < 6; j++) {
          //console.log("test: " + banner[j]['url']);
          if(banner[j]['url'] != null){
              var namaGambar = banner[j]['img'].replace(new RegExp(' ', 'g'), "%20");
              $("#gambars-"+j).closest(".imgUp").find('.imagePreview').css("background-image", "url("+urlNya+"/"+namaGambar+")");
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
              frmUpdate.append('images'+i, $("#gambars-"+i)[0].files[0]);
              if($("#gambars-"+i)[0].files[0]!=null)
              {
                  for(var j=0; j<deleted_img.length;j++)
                  {
                      console.log()
                      if(deleted_img[j]==i)
                      {
                          deleted_img.splice(j,1);
                      }
                  }
              }
              console.log("test" + i);
          }

          for(var x =0; x < total_photo; x++){
              frmUpdate.append('photos'+x, $("#photos-"+x)[0].files[0]);

              if($("#photos-"+x)[0].files[0]!=null)
              {
                  for(var j=0; j<deleted_photo.length;j++)
                  {
                      console.log()
                      if(deleted_photo[j]==x)
                      {
                          deleted_photo.splice(j,1);
                      }
                  }
              }
              console.log("photos" + x);
          }

          frmUpdate.append('total_images', 6);
          frmUpdate.append('dlt_img', deleted_img);
          frmUpdate.append('total_photos', total_photo);
          frmUpdate.append('dlt_photos', deleted_photo);
          frmUpdate.append('total_videos', total_video);

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

          for (var key of frmUpdate.keys()) {
              $("#actionUpdate").find("input[name="+key.name+"]").removeClass("is-invalid");
              $("#actionUpdate").find("select[name="+key.name+"]").removeClass("is-invalid");
              $("#actionUpdate").find("textarea[name="+key.name+"]").removeClass("is-invalid");

              $("#actionUpdate").find("input[name="+key.name+"]").next().find("strong").text("");
              $("#actionUpdate").find("select[name="+key.name+"]").next().find("strong").text("");
              $("#actionUpdate").find("textarea[name="+key.name+"]").next().find("strong").text("");
          }

          if(hasil['errors'] != null){
              for (var key of frmUpdate.keys()) {
                  if(typeof hasil['errors'][key] === 'undefined') {

                  }
                  else {
                      $("#actionUpdate").find("input[name="+key+"]").addClass("is-invalid");
                      $("#actionUpdate").find("select[name="+key+"]").addClass("is-invalid");
                      $("#actionUpdate").find("textarea[name="+key+"]").addClass("is-invalid");

                      $("#actionUpdate").find("input[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
                      $("#actionUpdate").find("select[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
                      $("#actionUpdate").find("textarea[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
                  }
              }
              alert("Input Error !!!");
          }
          else{
              alert("Input Success !!!");
              window.location.reload()
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

  $(document).on("click", ".delete_img" , function() {
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
