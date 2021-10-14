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
            <h3 class="page-title">Album</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#"
                            aria-expanded="false"
                            aria-controls="deliveryorder-dd">
                            Add Album
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" action="{{ route("store_frontendcms_album") }}">
                            @csrf
                            <h4 class="card-title">Add Album</h4>

                            <div class="form-group">
                                <label for="">Event</label>
                                <button class="btn btn-gradient-primary"
                                    style="float: right; margin-bottom: 0px;"
                                    data-target="#add-event-modal"
                                    data-toggle="modal">
                                    Add New Event
                                </button>
                                <select class="form-control mt-5"
                                    id="event"
                                    name="event_id"
                                    data-msg="Please Choose Event"
                                    required>
                                    <option selected disabled value="">
                                        Choose Event
                                    </option>
                                    @foreach($events as $event)
                                        <option value="{{$event['id']}}">{{$event['name']}}</option>
                                    @endforeach
                                </select>
                                <div class="validation"></div>
                            </div>

                            <div class="form-group">
                                <label for="">Choose Photo</label>
                                    <input multiple type="file" class="form-control" name="arr_photo[]" id="arr_photo" accept="image/*" placeholder="Choose Photo" required data-msg="Mohon Sertakan Foto" style="text-transform:uppercase" onchange="loadFile();" />
                                <div class="validation"></div>
                            </div>

                            <div id="image_preview"></div>

                            <br>
                            <br>

                            <div class="form-group">
                                <label for="">Link Video</label>
                                <br>
                                @for($x = 0; $x < 3; $x++)
                                <div id="video_{{ $x }}"
                                    class="col-xs-12 col-sm-6 col-md-4"
                                    style="padding: 15px; float: left;">
                                    <label>Video {{ $x + 1 }}</label>

                                    <div class="imagePreview embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item"
                                            src=""
                                            allowfullscreen></iframe>
                                    </div>

                                    <input type="hidden"
                                            name="sequence"
                                            value="{{ $x }}" />
                                    <input type="text"
                                        id="video-title-{{ $x }}"
                                        class="form-control"
                                        placeholder="Video Title"
                                        value=""
                                        name="title_{{ $x }}" />
                                    <input type="url"
                                        id="video-url-{{ $x }}"
                                        class="form-control mt-3"
                                        placeholder="Video URL"
                                        pattern="https://.*"
                                        value=""
                                        name="url_{{ $x }}" />
                                </div>
                                @endfor
                                <div class="validation"></div>
                            </div>

                            <button type="submit" class="btn btn-gradient-primary">Save</button>
                        </form>                       

                        <br>
                        <br>

                        @if($albums != null)
                            <h4 class="card-title">List Album</h4>

                            <div class="table-responsive" style="border: 1px solid #ebedf2;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th> No </th>
                                        <th> Album Name </th>
                                        <th> Active </th>
                                        <th> Edit </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($albums as $key => $album)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{$album->event['name']}}</td>
                                        <td>{{$album['active']}}</td>
                                        <td style="text-align: center;">
                                            <a href="{{route('edit_album', ['id' => $album['id']])}}">
                                                <i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade"
    id="add-event-modal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Add New Event</h5>
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
                    action="{{ route("store_event") }}">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" id="add-event" placeholder="Event Name" required/>
                        <div class="validation"></div>
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
@endsection

@section("script")
<script type="application/javascript">
function loadFile() 
{
    var total_file=document.getElementById("arr_photo").files.length;
    for(var i=0;i<total_file;i++)
    {
        $('#image_preview').append("<img style='width:400px;height:300px;' src='"+URL.createObjectURL(event.target.files[i])+"'><br>");
    }
}
</script>
@endsection
