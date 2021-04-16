<?php
$menu_item_page = "product";
$menu_item_second = "add_product";
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
            <h3 class="page-title">Add Product</h3>
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
                        Add Product
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
                            method="POST"
                            action="{{ route('store_product') }}">
                            @csrf
                            <div class="form-group">
                                <label for="">Show/Hide</label>
                                <select class="form-control"
                                    id="show-hide"
                                    name="active"
                                    onchange="isImageRequired(this)"
                                    required>
                                    <option value="1">Show</option>
                                    <option value="0">Hide</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Code</label>
                                <input type="text"
                                    class="form-control"
                                    name="code"
                                    id="code"
                                    placeholder="Code"
                                    required />
                            </div>
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text"
                                    class="form-control"
                                    name="name"
                                    id="name"
                                    placeholder="Name"
                                    required />
                            </div>
                            <div class="form-group">
                                <label for="category_id">
                                    Product Category
                                </label>
                                <select class="form-control"
                                    id="category_id"
                                    name="category_id"
                                    required />
                                    <option selected disabled value="">
                                        Choose Category
                                    </option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category['id'] }}">
                                            {{ $category['name'] }}
                                        </option>
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
                                    required />
                            </div>


                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label>Product Image (720x720 pixel)</label>
                                    <span style="float: right;">min. 1 picture</span>
                                </div>
                                @for ($i = 0; $i < 3; $i++)
                                    <div class="col-xs-12 col-sm-6 col-md-4 form-group imgUp"
                                        style="padding: 15px; float: left;">
                                        <label>Image {{ $i + 1 }}</label>
                                        <div class="imagePreview"
                                            style="background-image: url({{ asset('sources/dashboard/no-img-banner.jpg') }});">
                                        </div>
                                        <label class="file-upload-browse btn btn-gradient-primary"
                                            style="margin-top: 15px;">
                                            Upload
                                            <input name="images{{ $i }}"
                                                id="productimg-{{ $i }}"
                                                type="file"
                                                accept=".jpg,.jpeg,.png"
                                                class="uploadFile img"
                                                value="Upload Photo"
                                                style="width: 0px; height: 0px; overflow: hidden;"
                                                {{ $i === 0 ? "required" : "" }} />
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
                                    placeholder="URL"
                                    name="video"
                                    required />
                            </div>

                            <div class="form-group">
                                <label for="edit_quickdescription">
                                    Quick Description
                                </label>
                                <textarea id="edit_quickdescription"
                                    name="quick_desc"
                                    class="form-control form-control-sm"
                                    rows="4"
                                    placeholder="Deskripsi Produk"
                                    required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="edit_description">
                                    Description
                                </label>
                                <textarea id="edit_description"
                                    name="description"
                                    class="form-control form-control-sm"
                                    rows="6"
                                    placeholder="Description"
                                    required></textarea>
                            </div>

                            <button id="addProduct"
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
    function isImageRequired(e) {
        if (e.value === "0") {
            document.getElementById("productimg-0").removeAttribute("required");
        } else if (e.value === "1") {
            document.getElementById("productimg-0").setAttribute("required", "");
        }
    }
</script>
<script type="application/javascript">
    $(document).ready(function () {
        CKEDITOR.replace('edit_quickdescription');
        CKEDITOR.replace('edit_description');

        let frmAdd;

        $("#actionAdd").on("submit", function (e) {
            e.preventDefault();
            frmAdd = new FormData(document.getElementById("actionAdd"));
            frmAdd.enctype = "multipart/form-data";
            frmAdd.append('quick_desc', CKEDITOR.instances.edit_quickdescription.getData());
            frmAdd.append('description', CKEDITOR.instances.edit_description.getData());

            const URLNya = $("#actionAdd").attr('action');

            const ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.addEventListener("load", completeHandler, false);
            ajax.addEventListener("error", errorHandler, false);
            ajax.open("POST", URLNya);
            ajax.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
            ajax.send(frmAdd);
        });

        function progressHandler(event) {
            document.getElementById("addProduct").innerHTML = "UPLOADING...";
        }

        function completeHandler(event) {
            const hasil = JSON.parse(event.target.responseText);

            for (const key of frmAdd.keys()) {
                $("#actionAdd").find("input[name=" + key + "]").removeClass("is-invalid");
                $("#actionAdd").find("select[name=" + key + "]").removeClass("is-invalid");
                $("#actionAdd").find("textarea[name=" + key + "]").removeClass("is-invalid");

                $("#actionAdd").find("input[name=" + key + "]").next().find("strong").text("");
                $("#actionAdd").find("select[name=" + key + "]").next().find("strong").text("");
                $("#actionAdd").find("textarea[name=" + key + "]").next().find("strong").text("");
            }

            if (hasil['errors']) {
                for (const key of frmAdd.keys()) {
                    if (typeof hasil['errors'] !== 'undefined') {
                        $("#actionAdd").find("input[name=" + key + "]").addClass("is-invalid");
                        $("#actionAdd").find("select[name=" + key + "]").addClass("is-invalid");
                        $("#actionAdd").find("textarea[name=" + key + "]").addClass("is-invalid");

                        $("#actionAdd").find("input[name=" + key + "]").next().find("strong").text(hasil['errors'][key]);
                        $("#actionAdd").find("select[name=" + key + "]").next().find("strong").text(hasil['errors'][key]);
                        $("#actionAdd").find("textarea[name=" + key + "]").next().find("strong").text(hasil['errors'][key]);
                    }
                }

                alert("Input error!");
            } else {
                alert("Input success!");
                window.location.reload();
            }

            document.getElementById("addProduct").innerHTML = "SAVE";
        }

        function errorHandler(event) {
            document.getElementById("addProduct").innerHTML = "SAVE";
        }
    });
</script>
<script type="application/javascript">
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
</script>
@endsection
