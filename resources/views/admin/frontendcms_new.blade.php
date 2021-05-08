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
                            <h4 class="card-title">Photo Gallery</h4>
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
                                    class="col-xs-12 col-sm-6 col-md-4"
                                    style="padding: 15px; float: left;">
                                    <label>Photo {{ $x + 1 }}</label>
                                    @if (!empty($photos[$x]))
                                        <div class="imagePreview"
                                            style="background-image: url({{ $defaultImg . '/' . $photos[$x] }});">
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
                                        data-target="#delete-modal">
                                        Delete
                                    </button>
                                </div>
                            @endfor
                            <div id="tambahan_photo"></div>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        <br>
                        <div class="form-group">
                            <?php
                            $urlvideo = json_decode($galleries['url_youtube']);
                            $count_url = sizeof($urlvideo);
                            ?>
                            <button class="btn btn-gradient-primary"
                                style="float: right;"
                                data-target="#add-video-modal"
                                data-toggle="modal">
                                Add Video
                            </button>
                            <h4 class="card-title">Video Gallery</h4>
                            <br>
                            @if (!empty($urlvideo))
                                @for ($v = 0; $v < $count_url; $v++)
                                    <div id="video_{{ $v }}"
                                        class="col-xs-12 col-sm-6 col-md-4"
                                        style="padding: 15px; float: left;">
                                        <label>Video {{ $v + 1 }}</label>
                                        <div class="imagePreview embed-responsive embed-responsive-16by9">
                                            <iframe class="embed-responsive-item"
                                                src="{{ $urlvideo[$v]->url }}"
                                                allowfullscreen></iframe>
                                        </div>
                                        <form method="POST"
                                            id="update-video-{{ $v }}"
                                            action="{{ route("update_frontendcms_video") }}">
                                            @csrf
                                            <input type="hidden"
                                                name="sequence"
                                                value="{{ $v }}" />
                                            <input type="text"
                                                id="video-title-{{ $v }}"
                                                class="form-control"
                                                placeholder="Video Title"
                                                value="{{ $urlvideo[$v]->title }}"
                                                name="title" />
                                            <input type="url"
                                                id="video-url-{{ $v }}"
                                                class="form-control"
                                                placeholder="Video URL"
                                                pattern="https://.*"
                                                value="{{ $urlvideo[$v]->url }}"
                                                name="url" />
                                        </form>
                                        <input type="submit"
                                            form="update-video-{{ $v }}"
                                            value="Update"
                                            class="btn btn-gradient-primary" />
                                        <button class="btn btn-gradient-danger"
                                            onclick="clickDeleteVideo(this)"
                                            data-sequence="{{ $v }}"
                                            data-toggle="modal"
                                            data-target="#delete-modal">
                                            Delete
                                        </button>
                                    </div>
                                @endfor
                            @endif
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
    id="delete-modal"
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
                <form id="delete-form"
                    method="POST"
                    action="">
                    @csrf
                    <input type="hidden" id="delete-sequence" name="sequence" value="" />
                </form>
            </div>
            <div class="modal-footer">
                <input type="submit"
                    form="delete-form"
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

<div class="modal fade"
    id="add-video-modal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Add Video</h5>
                <button type="button"
                    id="edit-close"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add-video"
                    method="POST"
                    enctype="multipart/form-data"
                    action="{{ route("store_frontendcms_video") }}">
                    @csrf
                    <div class="form-group">
                        <label for="video-title">Title</label>
                        <input type="text"
                            id="video-title"
                            class="form-control"
                            placeholder="Video Title"
                            name="title"
                            required />
                    </div>
                    <div class="form-group">
                        <label for="video-url">URL</label>
                        <input type="url"
                            id="video-url"
                            class="form-control"
                            pattern="https://.*"
                            placeholder="Video URL (e.g. https://www.youtube.com/embed/xfQWGp805O4)"
                            name="url"
                            required />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <input type="submit"
                    form="add-video"
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
@endsection

@section("script")
<script type="application/javascript">
function clickDeleteImage(e) {
    const DELETE_URL = '<?php echo route("delete_frontendcms_image"); ?>';
    document.getElementById("delete-form").setAttribute("action", DELETE_URL);
    document.getElementById("delete-sequence").setAttribute("value", e.dataset.sequence);
}

function clickDeleteVideo(e) {
    const DELETE_URL = '<?php echo route("delete_frontendcms_video"); ?>';
    document.getElementById("delete-form").setAttribute("action", DELETE_URL);
    document.getElementById("delete-sequence").setAttribute("value", e.dataset.sequence);
}
</script>
@endsection
