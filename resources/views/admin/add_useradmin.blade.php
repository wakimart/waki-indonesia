<?php
$menu_item_page = "user";
$menu_item_second = "add_user";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
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

    .select2-selection__rendered {
        line-height: 40px !important;
    }

    .select2-container .select2-selection--single {
        height: 40px !important;
    }

    .select2-selection__arrow {
        height: 40px !important;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Add Admin</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#admin-dd"
                            aria-expanded="false"
                            aria-controls="admin-dd">
                            Admin
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Add Admin
                    </li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form id="actionAdd"
                            class="forms-sample"
                            action="{{ route('store_useradmin') }}"
                            method="POST">
                            @csrf
                            <div class="form-group">
                                <span>ADMIN ROLE</span>
                                <select id="dropdown-role"
                                    style="margin-top: 0.5em;"
                                    class="form-control"
                                    style="height: auto;"
                                    name="role"
                                    required>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
                            </div>

                            <!-- CSO -->
                            <div id="form-cso"
                                class="form-group"
                                style="display: none;">
                                <span>CSO</span>
                                <select id="dropdown-cso"
                                    style="margin-top: 0.5em;"
                                    class="form-control"
                                    style="height: auto;"
                                    name="cso_id">
                                    <option value="">Choose CSO</option>
                                    @foreach ($csos as $cso)
                                        <option value="{{ $cso->id }}">
                                            {{ $cso->code }} - {{ $cso->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
                            </div>
                            <!-- End CSO -->

                            <!-- Branch -->
                            <div id="form-branch"
                                class="container-branch"
                                style="display: none;">
                                <div id="branch_0"
                                    class="form-group"
                                    style="width: 90%; display: inline-block;">
                                    <span>BRANCH</span>
                                    <select class="form-control"
                                        name="branch_0"
                                        data-msg="Please choose the Branch">
                                        <option selected disabled value="">
                                            Choose Branch
                                        </option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">
                                                {{ $branch['code'] }} - {{ $branch['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="validation"></div>
                                    <span class="invalid-feedback">
                                        <strong></strong>
                                    </span>
                                </div>
                                <div class="text-center"
                                    style="display: inline-block; float: right;">
                                    <button id="tambah_branch"
                                        title="Add branch"
                                        style="padding: 0.4em 0.7em;">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <div id="tambahan_branch"></div>
                            </div>
                            <!-- End Branch -->

                            <div class="form-group">
                                <label for="username">USERNAME ADMIN</label>
                                <input type="text"
                                    class="form-control"
                                    id="username"
                                    name="username"
                                    placeholder="Username Admin"
                                    required />
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="name">ADMIN'S NAME</label>
                                <input type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    placeholder="Nama Admin"
                                    required />
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="password">PASSWORD</label>
                                <input type="password"
                                    id="password"
                                    name="password"
                                    class="form-control"
                                    required />
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="password-confirmation">
                                    RE-ENTER PASSWORD
                                </label>
                                <input type="password"
                                    class="form-control"
                                    id="password-confirmation"
                                    name="password_confirmation"
                                    required />
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="birth-date">BIRTH DATE</label>
                                <input type="date"
                                    id="birth-date"
                                    name="birth_date"
                                    class="form-control"
                                    required />
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label>PROFILE IMAGE (750x750 pixel)</label>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row productimg"
                                    style="border: 1px solid rgb(221, 221, 221, 0.5); border-radius: 4px; box-shadow: none; margin: 0;">
                                    <div class="col-xs-12 col-sm-6 imgUp"
                                        style="padding: 15px; float: left; text-align: center;">
                                        <div class="imagePreview"
                                            style="background-image: url({{ asset('sources/dashboard/no-img-banner.jpg') }});">
                                        </div>
                                        <label class="file-upload-browse btn btn-gradient-primary"
                                            style="margin: 15px 0 0; text-align: center;">
                                            Upload
                                            <input name="user_image"
                                                type="file"
                                                accept=".jpg,.jpeg,.png"
                                                class="uploadFile img"
                                                value="Upload Photo"
                                                style="width: 0px; height: 0px; overflow: hidden;" />
                                        </label>
                                        <i class="mdi mdi-window-close del"></i>
                                    </div>
                                </div>
                            </div>

                            <button id="addUserAdmin"
                                type="submit"
                                class="btn btn-gradient-primary mr-2">
                                Save
                            </button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
<script type="application/javascript">
    var total_branch = 0;
    var branch = 1;

    $(document).ready(function () {
        var val = 0;
        $('#dropdown-role').change(function () {
            val = this.value;
            console.log("tes " + val);

            if (val == 1) {
                $('#form-cso').hide();
                $('#form-branch').hide();
            } else if(val == 2) {
                $('#form-cso').hide();
                $('#form-branch').hide();
            } else if(val == 3) {
                $('#form-cso').show();
                $("#dropdown-cso").select2();
                $('#form-branch').hide();
            } else if(val == 4) {
                $('#form-cso').hide();
                $('#form-branch').show();
            } else if(val == 5) {
                $('#form-cso').hide();
                $('#form-branch').show();
            } else if(val == 6) {
                $('#form-cso').hide();
                $('#form-branch').hide();
            } else if(val == 7) {
                $('#form-cso').hide();
                $('#form-branch').hide();
            }
        });

        $('#tambah_branch').click(function(e){
            e.preventDefault();
            total_branch++;
            branch++;

            if (val == 4) {
                if (total_branch <= 1) {
                    strIsi = "<div id=\"branch_" + total_branch + "\" class=\"form-group\" style=\"width: 90%; display: inline-block;\"><select class=\"form-control\" name=\"branch_" + total_branch + "\" data-msg=\"Please choose the Branch\"><option selected disabled value=\"\">Choose Branch</option>@foreach($branches as $branch)<option value=\"{{ $branch->id }}\">{{ $branch['code'] }} - {{ $branch['name'] }}</option>@endforeach</select><div class=\"validation\"></div></div><div class=\"text-center\" style=\"display: inline-block; float: right;\"><button class=\"hapus_branch\" value=\"" + total_branch + "\" title=\"Hapus Branch\" style=\"padding: 0.4em 0.7em; background-color: red;\"><i class=\"fas fa-minus\"></i></button></div>";
                    $('#tambahan_branch').html($('#tambahan_branch').html() + strIsi);
                } else {
                    alert("Maksimum choice of Branch is 2");
                }
            } else if (val == 5) {
                if (total_branch <= 4) {
                    strIsi = "<div id=\"branch_" + total_branch + "\" class=\"form-group\" style=\"width: 90%; display: inline-block;\"><select class=\"form-control\" name=\"branch_" + total_branch + "\" data-msg=\"Please choose the Branch\"><option selected disabled value=\"\">Choose Branch</option>@foreach($branches as $branch)<option value=\"{{ $branch->id }}\">{{ $branch['code'] }} - {{ $branch['name'] }}</option>@endforeach</select><div class=\"validation\"></div></div><div class=\"text-center\" style=\"display: inline-block; float: right;\"><button class=\"hapus_branch\" value=\"" + total_branch + "\" title=\"Hapus Branch\" style=\"padding: 0.4em 0.7em; background-color: red;\"><i class=\"fas fa-minus\"></i></button></div>";
                    $('#tambahan_branch').html($('#tambahan_branch').html() + strIsi);
                } else {
                    alert("Maksimum choice of Branch is 5");
                }
            }
        });

        $(document).on("click", ".hapus_branch", function (e) {
            e.preventDefault();
            total_branch--;
            branch--;
            $('#branch_'+$(this).val()).remove();
            $(this).remove();
        });

        var frmAdd;
        $("#actionAdd").on("submit", function (e) {
            e.preventDefault();
            frmAdd = _("actionAdd");
            frmAdd = new FormData(document.getElementById("actionAdd"));
            frmAdd.enctype = "multipart/form-data";

            frmAdd.append('total_branch', branch);

            var URLNya = $("#actionAdd").attr('action');
            console.log(URLNya);

            var ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.addEventListener("load", completeHandler, false);
            ajax.addEventListener("error", errorHandler, false);
            ajax.open("POST", URLNya);
            ajax.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
            ajax.send(frmAdd);
        });

        function progressHandler(event) {
            document.getElementById("addUserAdmin").innerHTML = "UPLOADING...";
        }

        function completeHandler(event) {
            var hasil = JSON.parse(event.target.responseText);
            console.log(hasil);

            for (var key of frmAdd.keys()) {
                $("#actionAdd").find("input[name=" + key + "]").removeClass("is-invalid");
                $("#actionAdd").find("select[name=" + key + "]").removeClass("is-invalid");
                $("#actionAdd").find("textarea[name=" + key + "]").removeClass("is-invalid");

                $("#actionAdd").find("input[name=" + key + "]").next().find("strong").text("");
                $("#actionAdd").find("select[name=" + key + "]").siblings().find("strong").text("");
                $("#actionAdd").find("textarea[name=" + key + "]").next().find("strong").text("");
            }

            if (hasil['errors'] != null) {
                for (var key of frmAdd.keys()) {
                    if (typeof hasil['errors'][key] === 'undefined') {

                    } else {
                        $("#actionAdd").find("input[name=" + key + "]").addClass("is-invalid");
                        $("#actionAdd").find("select[name=" + key + "]").addClass("is-invalid");
                        $("#actionAdd").find("textarea[name=" + key + "]").addClass("is-invalid");

                        $("#actionAdd").find("input[name=" + key + "]").next().find("strong").text(hasil['errors'][key]);
                        $("#actionAdd").find("select[name=" + key + "]").siblings().find("strong").text(hasil['errors'][key]);
                        $("#actionAdd").find("textarea[name=" + key + "]").next().find("strong").text(hasil['errors'][key]);
                    }
                }

                alert("Input Error !!!");
            } else {
                alert("Input Success !!!");
                window.location.reload();
            }

            document.getElementById("addUserAdmin").innerHTML = "SAVE";
        }

        function errorHandler(event) {
            document.getElementById("addUserAdmin").innerHTML = "SAVE";
        }
    });

    $(function() {
        $(document).on("change", ".uploadFile", function () {
            var uploadFile = $(this);
            var files = this.files ? this.files : [];
            if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

            if (/^image/.test( files[0].type)) { // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[0]); // read the local file

                reader.onloadend = function() { // set image data as background of div
                    uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url("+this.result+")");
                }
            }

        });
    });
</script>
@endsection
