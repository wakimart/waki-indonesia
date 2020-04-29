@extends('admin.layouts.tempalte')

@section('content')
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
			<h3 class="page-title">Frontend Content Management System</h3>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a data-toggle="collapse" href="#kategori-dd" aria-expanded="false" aria-controls="kategori-dd">Kategori</a></li>
					<li class="breadcrumb-item active" aria-current="page">Tambah Kategori</li>
				</ol>
			</nav>
		</div>
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<form class="forms-sample">
						<div class="form-group">
							<label for="">Nama Kategori</label>
							<input type="text" class="form-control" id="exampleInputName1" placeholder="Nama Kategori">
						</div>
						<div class="form-group">
							<label for="">Kategori Produk</label>
							<select class="form-control" id="exampleSelectGender">
								<option>1</option>
								<option>2</option>
							</select>
						</div>

						<div class="form-group">
							<div class="col-xs-12">
								<label>Gambar Kategori (400x400 pixel)</label>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row productimg" style="border: 1px solid rgb(221, 221, 221, 0.5); border-radius: 4px; box-shadow: none; margin: 0;">
								<div class="col-xs-12 col-sm-4 imgUp" style="padding: 15px; float: left; text-align: center;">
									<div class="imagePreview" style="background-image: url(assets/images/dashboard/no-img.jpg);"></div>
									<label class="file-upload-browse btn btn-gradient-primary" style="margin: 15px 0 0; text-align: center;">Upload
									<input name="arr_image[]" data-name="arr_image" id="gambars-{{$i}}" type="file" accept=".jpg,.jpeg,.png" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
									</label>
									<i class="mdi mdi-window-close del"></i>
								</div>
							</div>
						</div>

						<button type="submit" class="btn btn-gradient-primary mr-2">Simpan</button>
						<button class="btn btn-light">Batal</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- partial -->
</div>
@endsection