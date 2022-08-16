<?php
$menu_item_page = "product";
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
        background-color: rgba(255, 255, 255, 0.6);
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Edit Product
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#produk-dd"
                            aria-expanded="false"
                            aria-controls="produk-dd">
                            Product
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Product
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form id="actionUpdate"
                            class="forms-sample"
                            method="POST"
                            action="{{ route('update_product') }}">
                            <div class="form-group">
                                <label for="">Show/Hide</label>
                                <select class="form-control"
                                    id="show-hide"
                                    name="show"
                                    required>
                                    <option value="1"
                                        {{ (int) $products["show"] === 1 ? "selected" : "" }}>
                                        Show
                                    </option>
                                    <option value="0"
                                        {{ (int) $products["show"] === 0 ? "selected" : "" }}>
                                        Hide
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text"
                                    class="form-control"
                                    name="code"
                                    id="code"
                                    value="{{ $products['code'] }}"
                                    readonly />
                            </div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text"
                                    class="form-control"
                                    name="name"
                                    id="name"
                                    value="{{ $products['name'] }}"
                                    required />
                            </div>
                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select class="form-control"
                                    id="category_id"
                                    name="category_id"
                                    required>
                                    <option selected disabled value="">
                                        Choose Category
                                    </option>
                                    @foreach ($categories as $category)
                                        @if ($products['category_id'] == $category['id'])
                                            <option value="{{ $category['id'] }}"
                                                selected>
                                                {{ $category['name'] }}
                                            </option>
                                        @else
                                            <option value="{{ $category['id'] }}">
                                                {{ $category['name'] }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group d-none">
                                <label for="weight">Berat Produk (KG)</label>
                                <input type="text"
                                    class="form-control"
                                    id="weight"
                                    placeholder="Berat (Kg)" />
                            </div>
                            <div class="form-group d-none">
                                <label for="amount">Jumlah Produk</label>
                                <input type="text"
                                    class="form-control"
                                    id="amount"
                                    placeholder="Jumlah" />
                            </div>
                            <div class="form-group">
                                <label for="price">Price (Rp.)</label>
                                <input type="number"
                                    class="form-control"
                                    id="price"
                                    placeholder="Product Price (Rp)"
                                    name="price"
                                    value="{{ (int) $products["price"] }}"
                                    required />
                            </div>

                            <div class="form-group">
                                <?php
                                $img = json_decode($products['image']);
                                $defaultImg = asset('sources/product_images/')
                                    . '/'
                                    . strtolower($products['code']);
                                ?>
                                <div class="col-xs-12">
                                    <label>Product Image (720x720 pixel)</label>
                                </div>
                                @for ($i = 0; $i < 3; $i++)
                                    <div class="col-xs-12 col-sm-6 col-md-4 form-group imgUp"
                                        style="padding: 15px; float: left;">
                                        <label>Image {{ $i + 1 }}</label>
                                        @if (!empty($img[$i]))
                                            <div class="imagePreview"
                                                style="background-image: url({{ $defaultImg . '/' . $img[$i] }});"></div>
                                        @else
                                            <div class="imagePreview"
                                                style="background-image: url({{ asset('sources/dashboard/no-img-banner.jpg') }});"></div>
                                        @endif
                                        <label class="file-upload-browse btn btn-gradient-primary"
                                            style="margin-top: 15px;">
                                            Upload
                                            <input name="arr_image[]"
                                                data-name="arr_image"
                                                id="gambars-{{ $i }}"
                                                type="file"
                                                accept=".jpg,.jpeg,.png"
                                                class="uploadFile img"
                                                value="Upload Photo"
                                                style="width: 0px; height: 0px; overflow: hidden;" />
                                        </label>
                                        <i class="mdi mdi-window-close del"></i>
                                    </div>
                                @endfor
                            </div>

                            <div class="form-group">
                                <label for="url-video">URL Video</label>
                                <input type="text"
                                    class="form-control"
                                    id="url-video"
                                    value="{{ $products['video'] }}"
                                    name="video" />
                            </div>
                            <div class="form-group">
                                <label for="flipbook_url">Flipbook URL</label>
                                <input type="text"
                                    class="form-control"
                                    id="flipbook_url"
                                    value="{{ $products['flipbook_url'] }}"
                                    name="flipbook_url" />
                            </div>

                            <div class="form-group">
                                <label for="edit_quickdescription">
                                    Quick Description
                                </label>
                                <textarea id="edit_quickdescription"
                                    name="quick_desc"
                                    class="form-control form-control-sm"
                                    rows="4"
                                    value="{{ $products['quick_desc'] }}"
                                    required>{{ $products['quick_desc'] }}</textarea>
                            </div>

                            {{-- <div class="form-group">
                                <label for="edit_description">
                                    Description
                                </label>
                                <textarea id="edit_description"
                                    name="description"
                                    class="form-control form-control-sm"
                                    rows="4"
                                    value="{{ $products['description'] }}"
                                    required>{{ $products['description'] }}</textarea>
                            </div> --}}

                            <input type="hidden"
                                name="idProduct"
                                value="{{ $products['id'] }}" />
                            <button id="updateProduct"
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
    <!-- partial -->
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.ckeditor.com/4.5.6/standard/ckeditor.js"></script>
<script type="application/javascript">
    // $(document).ready(function () {
    //     CKEDITOR.replace('edit_description');
    // });

    $(document).ready(function () {
        CKEDITOR.replace('edit_quickdescription');
    });

    function _(el) {
        return document.getElementById(el);
    }
    const deleted_img = [];
    $(document).ready(function () {
        let frmUpdate;

        $("#actionUpdate").on("submit", function (e) {
            e.preventDefault();
            frmUpdate = _("actionUpdate");
            frmUpdate = new FormData(document.getElementById("actionUpdate"));
            frmUpdate.enctype = "multipart/form-data";
            // frmUpdate.append('description', CKEDITOR.instances.edit_description.getData());
            frmUpdate.append('quick_description', CKEDITOR.instances.edit_quickdescription.getData());

            for (let i = 0; i < 3; i++) {
                frmUpdate.append('images' + i, $("#gambars-" + i)[0].files[0]);

                if ($("#gambars-" + i)[0].files[0] != null) {
                    for (let j = 0; j < deleted_img.length; j++) {
                        if (deleted_img[j] == i) {
                            deleted_img.splice(j, 1);
                        }
                    }
                }
            }

            frmUpdate.append('total_images', 3);
            frmUpdate.append('dlt_img', deleted_img);

            const URLNya = $("#actionUpdate").attr('action');

            const ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.addEventListener("load", completeHandler, false);
            ajax.addEventListener("error", errorHandler, false);
            ajax.open("POST", URLNya);
            ajax.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
            ajax.send(frmUpdate);
        });

        function progressHandler(event) {
            document.getElementById("updateProduct").innerHTML = "UPLOADING...";
        }

        function completeHandler(event) {
            const hasil = JSON.parse(event.target.responseText);

            for (const key of frmUpdate.keys()) {
                $("#actionUpdate").find("input[name=" + key.name + "]").removeClass("is-invalid");
                $("#actionUpdate").find("select[name=" + key.name + "]").removeClass("is-invalid");
                $("#actionUpdate").find("textarea[name=" + key.name + "]").removeClass("is-invalid");

                $("#actionUpdate").find("input[name=" + key.name + "]").next().find("strong").text("");
                $("#actionUpdate").find("select[name=" + key.name + "]").next().find("strong").text("");
                $("#actionUpdate").find("textarea[name=" + key.name + "]").next().find("strong").text("");
            }

            if (hasil['errors']) {
                for (const key of frmUpdate.keys()) {
                    if (typeof hasil['errors'][key] !== 'undefined') {
                        $("#actionUpdate").find("input[name=" + key + "]").addClass("is-invalid");
                        $("#actionUpdate").find("select[name=" + key + "]").addClass("is-invalid");
                        $("#actionUpdate").find("textarea[name=" + key + "]").addClass("is-invalid");

                        $("#actionUpdate").find("input[name=" + key + "]").next().find("strong").text(hasil['errors'][key]);
                        $("#actionUpdate").find("select[name=" + key + "]").next().find("strong").text(hasil['errors'][key]);
                        $("#actionUpdate").find("textarea[name=" + key + "]").next().find("strong").text(hasil['errors'][key]);
                    }
                }

                alert("Input error!");
            } else {
                alert("Input success!");
                window.location.reload();
            }

            document.getElementById("updateProduct").innerHTML = "SAVE";
        }

        function errorHandler(event) {
            document.getElementById("updateProduct").innerHTML = "SAVE";
        }
    });

    $(document).on("click", "i.del", function () {
        $(this).closest(".imgUp").find('.imagePreview').css("background-image", "");
        $(this).closest(".imgUp").find('input[type=text]').removeAttr("required");
        $(this).closest(".imgUp").find('.btn').find('.img').val("");
        $(this).closest(".imgUp").find('.form-control').val("");
        deleted_img.push($(this).closest(".imgUp").find(".img").attr('id').substring(8));
    });

    $(function () {
        $(document).on("change", ".uploadFile", function () {
            const uploadFile = $(this);
            const files = this.files ? this.files : [];

            // no file selected, or no FileReader support
            if (!files.length || !window.FileReader) {
                return;
            }

            // only image file
            if (/^image/.test(files[0].type)) {
                // instance of the FileReader
                const reader = new FileReader();
                // read the local file
                reader.readAsDataURL(files[0]);

                // set image data as background of div
                reader.onloadend = function () {
                    uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url(" + this.result + ")");
                };
            }

        });
    });
</script>
@endsection
