<?php
$menu_item_page = "product";
$menu_item_second = "list_product";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    .center {
        text-align: center;
    }

    .right {
        text-align: right;
    }

    .table th img, .table td img {
        border-radius: 0% !important;
    }

     /*-- mobile --*/
	@media (max-width: 768px){
		#desktop{
			display: none;
		}

		#mobile{
			display: block;
		}

		#mobile .filter{
			padding-top: 15px;
		}
	}

	@media (min-width: 768px){
		#desktop{
			display: block;
		}

		#mobile{
			display: none;
		}
	}

	@media (min-width: 410px){
		#desktop{
			display: none;
		}

		#mobile{
			display: block;
		}

		#mobile .filter{
			padding-top: 0;
		}
	}
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">List Product</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#order-dd"
                            aria-expanded="false"
                            aria-controls="order-dd">
                            Product
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        List Product
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            {{-- <div id="desktop">
                <div class="col-12 grid-margin stretch-card">
                    @if(Utils::$lang === 'id'&& Auth::user()->roles[0]["slug"] !== "admin-management")
                    <div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
                        <div class="form-group">
                            <label for="">Search By Name, and Code</label>
                            <input class="form-control" id="search" name="search" placeholder="Search By Name and Code">
                            <div class="validation"></div>
                        </div>
                    </div>
                    @endif
    
                    @if(Utils::$lang === 'id'&& Auth::user()->roles[0]["slug"] !== 'area-manager')
                    <div class="col-xs-6 col-sm-12 row" style="margin: 0;padding: 0;">
                        <div class="col-xs-6 col-sm-6" style="padding: 0;display: inline-block;">
                            <label for=""></label>
                            <div class="form-group">
                                <button id="btn-filter" type="button" class="btn btn-gradient-primary m-1" name="filter" onclick="submitApplyFilter()" value="-"><span class="mdi mdi-filter"></span> Apply Filter</button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div> --}}

            <div class="col-12 grid-margin" style="margin-bottom: 0;">
                    <div class="col-xs-6 col-sm-4" style="margin-bottom: 0; padding: 0;">
                        <div class="form-group">
                            <label for="">Search By Name, and Code</label>
                            <input class="form-control" id="search" name="search" placeholder="Search By Name and Code">
                            <div class="validation"></div>
                        </div>
                    </div>
            </div>
            <div class="col-12 grid-margin" style="margin-bottom: 15px;">
                <div class="col-xs-6 col-sm-6" style="padding: 0;">
                    <label for=""></label>
                    <div class="form-group">
                        <button id="btn-filter" type="button" class="btn btn-gradient-primary m-1" name="filter" value="-"><span class="mdi mdi-filter"></span> Apply Filter</button>
                    </div>
                </div>
            </div>

            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h5 style="margin-bottom: 0.5em;">
                            Total : {{ $countProduct }} data
                        </h5>
                        <div class="table-responsive"
                            style="border: 1px solid #ebedf2;">
                            <table class="table table-bordered" id="myTable">
                                <thead>
                                    <tr>
                                        <th class="center">No.</th>
                                        <th>Code</th>
                                        <th class="center">Image</th>
                                        <th>Name</th>
                                        <th class="center">Price</th>
                                        <th>Category</th>
                                        <th class="center">Edit</th>
                                        <th class="center">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $key => $product)
                                        <tr>
                                            <td class="right">
                                                <?php echo $i; ?>
                                            </td>
                                            <td>{{ $product['code'] }}</td>
                                            <td class="center">
                                                <?php
                                                $defaultImg = "";

                                                try {
                                                    $img = json_decode($product->image);
                                                    $defaultImg = asset('sources/product_images/')
                                                        . '/'
                                                        . strtolower($product['code'])
                                                        . '/'
                                                        . $img[0];
                                                } catch (\Exception $e) {
                                                    unset($e);
                                                }
                                                ?>
                                                <?php if (!empty($defaultImg)): ?>
                                                    <div class="product-thumbnail product__image center-block">
                                                        <div class="product-thumbnail__wrapper">
                                                        <img alt="#"
                                                            class="product-thumbnail__image"
                                                            src="{{ $defaultImg }}">
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>{{ $product['name'] }}</td>
                                            <td class="right">
                                                Rp. {{ number_format($product['price']) }}
                                            </td>
                                            <td>
                                                {{ $product->category['name'] }}
                                            </td>
                                            <td class="center">
                                                <a href="{{ route('edit_product', ['id' => $product['id']]) }}">
                                                    <i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i>
                                                </a>
                                            </td>
                                            <td class="center">
                                                <a class="btn-delete"
                                                    data-toggle="modal"
                                                    href="#deleteDoModal"
                                                    onclick="submitDelete(this)"
                                                    data-id="{{ $product["id"] }}">
                                                    <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            <?php echo $products->appends($url)->links(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- partial -->
    <!-- Modal Delete -->
    <div class="modal fade"
        id="deleteDoModal"
        tabindex="-1"
        role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 style="text-align:center;">
                        Are you sure to delete this product?
                    </h5>
                </div>
                <div class="modal-footer">
                    <form id="frmDelete"
                        method="post"
                        action="{{ route("delete_product") }}">
                        @csrf
                        <input type="hidden" name="id" id="id-delete" />
                        <button type="submit"
                            class="btn btn-gradient-danger mr-2">
                            Yes
                        </button>
                    </form>
                    <button class="btn btn-light">No</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Delete -->
</div>
@endsection

@section("script")
<script type="application/javascript">
function submitDelete(e) {
    document.getElementById("id-delete").value = e.dataset.id;
}
 
$(document).ready(function (e) {
    $("#btn-filter").click(function (e) {
      /*   var urlParamArray = new Array();
        var urlParamStr = "";
        if($('#search').val() != ""){
            urlParamArray.push("search=" + $('#search').val());
        }
        for (var i = 0; i < urlParamArray.length; i++) {
            if (i === 0) {
                urlParamStr += "?" + urlParamArray[i]
            } else {
                urlParamStr += "&" + urlParamArray[i]
            }
        }
        window.location.href = "{{route('list_product')}}" + urlParamStr; */

        $.each($("#table tbody tr"), function() {

            if($(this).text().toLowerCase().indexOf($('#search').val().toLowerCase()) === -1)
                $(this).hide();
            else
                $(this).show();                
        });

    });
}); 

/* {{-- Menyusun parameter untuk filter --}}
function buildParam() {
    let urlParamStr = "";

    const filterSearch = document.getElementById("search").value.trim();
    if (filterSearch.length) {
        urlParamStr += "filter_search=" + filterSearch + "&";
    }

    return urlParamStr;
}

{{-- Apply Filter --}}
function submitApplyFilter() {
    const urlParamStr = buildParam();

    window.location.href = "<?php echo route('list_product'); ?>" + "?" + urlParamStr;
} */
</script>
@endsection
