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

    button {
        background: #1bb1dc;
        border: 0;
        border-radius: 3px;
        padding: 8px 30px;
        color: #fff;
        transition: 0.3s;
    }

    .validation {
        color: red;
        font-size: 9pt;
    }

    input, select, textarea {
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
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#"
                            aria-expanded="false"
                            aria-controls="deliveryorder-dd">
                            Frontend CMS
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <button class="btn btn-gradient-primary"
                                style="float: right; margin-bottom: 0px;"
                                data-target="#add-image-modal"
                                data-toggle="modal">
                                Add Photo
                            </button>
                            <h4 class="card-title">GALLERY PHOTO</h4>
                            <p class="card-description">
                                Image Size (1280x500 pixel)
                            </p>

                            <?php
                            $photos = json_decode($galleries['photo']);
                            $defaultImg = asset('sources/portfolio/');

                            $count_photo = sizeof($photos);
                            if ($count_photo === 0) {
                                $count_photo++;
                            }
                            ?>

                            @for ($x = 0; $x < $count_photo; $x++)
                                <div id="photo_{{ $x }}"
                                    class="col-xs-12 col-sm-6 col-md-4 form-group"
                                    style="padding: 15px; float: left;">
                                    <label>Photo {{ $x + 1 }}</label>
                                    @if (!empty($photos[$x]))
                                        <div class="imagePreview"
                                            style="background-image: url({{ $defaultImg . '/' . $photos[$x] }});">
                                        </div>
                                    @else
                                        <div class="imagePreview"
                                            style="background-image: url({{ asset('sources/dashboard/no-img-banner.jpg') }});">
                                        </div>
                                    @endif
                                    <form method="POST"
                                        id="update-photo-{{ $x }}"
                                        enctype="multipart/form-data"
                                        action="{{ route("update_frontendcms_image") }}">
                                        @csrf
                                        <input type="hidden"
                                            name="sequence"
                                            value="{{ $x }}" />
                                        <div class="custom-file">
                                            <input type="file"
                                                class="custom-file-input"
                                                accept=".jpg,.jpeg,.png"
                                                name="photo"
                                                id="photos-{{ $x }}"
                                                required />
                                            <label class="custom-file-label"
                                                for="photos-{{ $x }}">
                                                Choose file
                                            </label>
                                        </div>
                                    </form>
                                    <input type="submit"
                                        form="update-photo-{{ $x }}"
                                        value="Update"
                                        class="btn btn-gradient-primary" />
                                    <button class="btn btn-gradient-danger"
                                        onclick="clickDeleteImage(this)"
                                        data-sequence="{{ $x }}"
                                        data-toggle="modal"
                                        data-target="#delete-image-modal">
                                        Delete
                                    </button>
                                </div>
                            @endfor
                            <div id="tambahan_photo"></div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <?php
                            $urlvideo = json_decode($galleries['url_youtube']);
                            $count_url = sizeof($urlvideo);
                            ?>
                            <h4 class="card-title">GALLERY VIDEO</h4>
                            @if (!empty($urlvideo))
                                @for ($v = 0; $v < $count_url; $v++)
                                    <div id="video_{{ $v }}"
                                        style="padding: 15px;">
                                        <div class="form-group"
                                            style="width: 72%; display: inline-block;">
                                            <label for="title-{{ $v }}">
                                                Video Title {{ $v + 1 }}
                                            </label>
                                            <input type="text"
                                                id="title-{{ $v }}"
                                                name="title_{{ $v }}"
                                                class="text-uppercase form-control"
                                                value="{{ $urlvideo[$v]->title }}"
                                                style="margin: 5px 0px;" />
                                            <div class="validation"></div>

                                            <label for="video-{{ $v }}">
                                                URL Video {{ $v + 1 }}
                                            </label>
                                            <input type="text"
                                                id="video-{{ $v }}"
                                                name="video_{{ $v }}"
                                                class="text-uppercase form-control"
                                                value="{{ $urlvideo[$v]->url }}"
                                                style="margin: 5px 0px;" />
                                            <div class="validation"></div>
                                        </div>

                                        @if ($v === 0)
                                            <span>
                                                <label id="btnAddUrl"
                                                    class="btn btn-gradient-primary"
                                                    style="float: right; display: inline-block; margin-top: 1.8em;">
                                                    Add URL
                                                </label>
                                            </span>
                                        @else
                                            <span>
                                                <label class="btn btn-gradient-danger delete_url"
                                                    style="float: right; display: inline-block; margin-top: 1.8em;"
                                                    value="{{ $v }}">
                                                    Delete URL
                                                </label>
                                            </span>
                                        @endif
                                    </div>
                                @endfor
                            @else
                                <div id="video_0" style="padding: 15px;">
                                    <div class="form-group"
                                        style="width: 72%; display: inline-block;">
                                        <span>Video Title 1</span>
                                        <input type="text"
                                            name="title_0"
                                            class="text-uppercase form-control"
                                            placeholder="Video Title"
                                            style="margin: 5px 0px;" />
                                        <div class="validation"></div>
                                        <span>URL Video 1</span>
                                        <input type="text"
                                            name="video_0"
                                            class="text-uppercase form-control"
                                            placeholder="URL Video"
                                            style="margin: 5px 0px;" />
                                        <div class="validation"></div>
                                    </div>

                                    <span>
                                        <label id="btnAddUrl"
                                            class="btn btn-gradient-primary"
                                            style="float: right; display: inline-block; margin-top: 2.5%; width: 16%;">
                                            Add URL
                                        </label>
                                    </span>
                                </div>
                            @endif
                            <div id="tambahan_video"></div>
                            <input type="hidden"
                                id="totalVideo"
                                value="{{ $count_url }}" />
                            <input type="hidden"
                                id="totalPhoto"
                                value="{{ $count_photo }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade"
    id="add-image-modal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Add Image</h5>
                <button type="button"
                    id="edit-close"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add-photo"
                    method="POST"
                    enctype="multipart/form-data"
                    action="{{ route("store_frontendcms_image") }}">
                    @csrf
                    <div class="custom-file">
                        <input type="file"
                            class="custom-file-input"
                            accept=".jpg,.jpeg,.png"
                            name="photo"
                            form="add-photo"
                            required />
                        <label class="custom-file-label"
                            for="add-photo">
                            Choose file
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <input type="submit"
                    form="add-photo"
                    value="Add"
                    class="btn btn-gradient-primary" />
                <button class="btn btn-light"
                    data-dismiss="modal"
                    aria-label="Close">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade"
    id="delete-image-modal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Confirmation</h5>
                <button type="button"
                    id="edit-close"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="delete-image-body"></div>
                <form id="delete-image"
                    method="POST"
                    action="{{ route("delete_frontendcms_image") }}">
                    @csrf
                    <input type="hidden" id="delete-sequence" name="sequence" value="" />
                </form>
            </div>
            <div class="modal-footer">
                <input type="submit"
                    form="delete-image"
                    value="Confirm Delete"
                    class="btn btn-gradient-danger" />
                <button class="btn btn-light"
                    data-dismiss="modal"
                    aria-label="Close">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section("script")
<script type="application/javascript">
function clickDeleteImage(e) {
    document.getElementById("delete-sequence").setAttribute("value", e.dataset.sequence);
}
</script>
@endsection
