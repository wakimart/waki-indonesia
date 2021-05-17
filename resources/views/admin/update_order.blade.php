<?php
$menu_item_page = "order";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="{{ asset("css/lib/select2/select2-bootstrap4.min.css") }}" />
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

    .validation {
        color: red;
        font-size: 9pt;
    }

    button {
        background: #1bb1dc;
        border: 0;
        border-radius: 3px;
        padding: 8px 30px;
        color: #fff;
        transition: 0.3s;
    }

    input, select, textarea {
        border-radius: 0 !important;
        box-shadow: none !important;
        border: 1px solid #dce1ec !important;
        font-size: 14px !important;
    }

    .select2-selection__rendered {
        line-height: 45px !important;
    }

    .select2-container .select2-selection--single {
        height: 45px !important;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
  	<div class="content-wrapper">
    	<div class="page-header">
      		<h3 class="page-title">Edit Order</h3>
      		<nav aria-label="breadcrumb">
	        	<ol class="breadcrumb">
	          		<li class="breadcrumb-item"><a data-toggle="collapse" href="#deliveryorder-dd" aria-expanded="false" aria-controls="deliveryorder-dd">Order</a></li>
	          		<li class="breadcrumb-item active" aria-current="page">Edit Order</li>
	        	</ol>
      		</nav>
    	</div>
	    <div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">
	            		<form id="actionUpdate" class="forms-sample" method="POST" action="{{ route('update_order') }}">
	            			{{ csrf_field() }}
	            			<div class="form-group">
				                <label for="order-code">Order Code</label>
				                <input type="text" class="form-control" id="order-code" name="order_code" value="{{ $orders['code'] }}" readonly="">
				                <div class="validation"></div>
	              			</div>
	              			<div class="form-group">
	                			<label for="">No. Member (optional)</label>
	                			<input type="number" class="form-control" id="no_member" name="no_member" value="{{ $orders['no_member'] }}">
	                			<div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">Name</label>
				                <input type="text" class="form-control" id="name" name="name" value="{{ $orders['name'] }}">
				                <div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">Phone Number</label>
				                <input type="number" class="form-control" id="phone" name="phone" value="{{ $orders['phone'] }}">
				                <div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">Province</label>
								<select class="form-control" id="province" name="province_id" data-msg="Mohon Pilih Provinsi" required>
									<option selected value="{{ $orders['district']['province_id'] }}">{{ $orders['district']['province'] }}</option>

									@php
										$result = RajaOngkir::FetchProvince();
										$result = $result['rajaongkir']['results'];
										$arrProvince = [];
										if(sizeof($result) > 0){
											foreach ($result as $value) {
												echo "<option value=\"" . $value['province_id'] . "\">" . $value['province'] . "</option>";
											}
										}
									@endphp
								</select>
								<div class="validation"></div>
							  </div>
							<div class="form-group">
				                <label for="">City</label>
								<select class="form-control" id="city" name="city" data-msg="Mohon Pilih Kota" required>
									@php
		                                if (isset($orders['district']['province_id'])) {
                                            $result = RajaOngkir::FetchCity($orders['district']['province_id']);
                                            $result = $result['rajaongkir']['results'];
                                            $arrCity = [];
                                            $arrCity[0] = "<option disabled value=\"\">Pilihan Kabupaten</option>";
                                            $arrCity[1] = "<option disabled value=\"\">Pilihan Kota</option>";
                                            if (sizeof($result) > 0) {
                                                foreach ($result as $value) {
                                                    $terpilihNya = "";
                                                    if (isset($orders['district']['city_id'])) {
                                                        if ($orders['district']['city_id'] == $value['city_id']) {
                                                            $terpilihNya = "selected";
                                                        }
                                                    }

                                                    if ($value['type'] == "Kabupaten") {
                                                        $arrCity[0] .= "<option value=\"".$value['city_id']."\"".$terpilihNya.">".$value['type']." ".$value['city_name']."</option>";
                                                    } else {
                                                        $arrCity[1] .= "<option value=\"".$value['city_id']."\"".$terpilihNya.">".$value['type']." ".$value['city_name']."</option>";
                                                    }
                                                }
                                                echo $arrCity[0];
                                                echo $arrCity[1];
                                            }
		                                }
		                            @endphp
								</select>
								<div class="validation"></div>
							</div>
							<div class="form-group">
				                <label for="">Sub District</label>
								<select class="form-control" id="subDistrict" name="distric" data-msg="Mohon Pilih Kecamatan" required>
									<option selected value="{{$orders['district']['subdistrict_id']}}">{{$orders['district']['subdistrict_name']}}</option>
									@php
		                              if(isset($orders['district']['city_id'])){
		                                $result = RajaOngkir::FetchDistrict($orders['district']['city_id']);
		                                $result = $result['rajaongkir']['results'];
		                                if(sizeof($result) > 0){
		                                  foreach ($result as $value) {
		                                    $terpilihNya = "";
		                                    if(isset($orders['district']['subdistrict_id'])){
		                                      if($orders['district']['subdistrict_id'] == $value['subdistrict_id']){
		                                        $terpilihNya = "selected";
		                                      }
		                                    }

		                                    echo "<option value=\"".$value['subdistrict_id']."\"".$terpilihNya.">".$value['subdistrict_name']."</option>";
		                                  }
		                                }
		                              }
		                            @endphp
								</select>
								<div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="exampleTextarea1">Address</label>
				                <textarea class="form-control" id="address" name="address" rows="4">{{$orders['address']}}</textarea>
				                <div class="validation"></div>
							</div>
							<div class="form-group">
				                <label for="">Know From</label>
								<select class="form-control" id="know_from" name="know_from" data-msg="Mohon Pilih Kecamatan" required>
									<option selected disabled value="">{{$orders['know_from']}}</option>
									@foreach($from_know as $key=>$value)
										<option value="{{ $value }}">{{ $value }}</option>
									@endforeach
								</select>
								<div class="validation"></div>
	              			</div>
	              			<br>

	              			<div class="form-group">
	              				<label for="">CASH/UPGRADE</label>
			                    <select class="form-control" id="cash_upgarde" name="cash_upgrade" data-msg="Mohon Pilih Tipe" required>
			                        <option selected disabled value="">Choose CASH/UPGRADE</option>

			                        @foreach ($cashUpgrades as $key => $cashUpgrade)
			                        	@if ($orders['cash_upgrade'] == $key)
			                                <option value="{{ $key }}" selected>
                                                {{ strtoupper($cashUpgrade) }}
                                            </option>
			                            @else
			                                <option value="{{ $key }}">
                                                {{ strtoupper($cashUpgrade) }}
                                            </option>
			                            @endif
			                        @endforeach
			                    </select>
			                    <div class="validation"></div>
			                </div>

			                @if ($orders['cash_upgrade'] == 1 || $orders['cash_upgrade'] == 2)
			                <div id="container-cashupgrade">
			                	<?php
                                $ProductPromos = json_decode($orders['product'], true);
                                $totalProduct = count($ProductPromos);

                                $total_product = -1;
		                        ?>

			                	@foreach ($ProductPromos as $ProductPromo)
			                		<?php
                                    $total_product++;
									?>

				                    {{-- ++++++++++++++ Product ++++++++++++++ --}}
				                    <div class="row">
                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <select class="form-control pilihan-product"
                                                    id="product_{{ $total_product }}"
                                                    name="product_{{ $total_product }}"
                                                    data-msg="Mohon Pilih Product"
                                                    required>
                                                    <option selected disabled value="">
                                                        Choose Product
                                                    </option>
                                                    <?php
                                                    $isPromoIdNumeric = false;
                                                    if (is_numeric($ProductPromo['id'])) {
                                                        $isPromoIdNumeric = true;
                                                    }
                                                    ?>

                                                    <?php foreach ($promos as $key => $promo): ?>
                                                        <option value="<?php echo $promo["id"]; ?>"
                                                            <?php
                                                            if (
                                                                $isPromoIdNumeric
                                                                && $promo["id"] === $ProductPromo["id"]
                                                            ) {
                                                                echo "selected";
                                                            }
                                                            ?>
                                                            >
                                                            <?php
                                                            echo $promo->code
                                                                . " - ("
                                                                . implode(", ", $promo->productName())
                                                                . ") - Rp. "
                                                                . number_format($promo->price);
                                                            ?>
                                                        </option>
                                                    <?php endforeach; ?>

                                                    <option value="other" <?php echo $isPromoIdNumeric ? "" : "selected";?>>
                                                        OTHER
                                                    </option>
                                                </select>
                                                <div class="validation"></div>
				                            </div>
                                        </div>
                                        <div class="col-md-1"></div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select class="form-control"
                                                    name="qty_{{ $total_product }}"
                                                    data-msg="Mohon Pilih Jumlah"
                                                    required>
                                                    <option selected value="1">
                                                        1
                                                    </option>

                                                    @for ($i = 2; $i <= 10; $i++)
                                                        @if ($ProductPromo['qty'] == $i)
                                                            <option value="{{ $i }}" selected="true">
                                                                {{ $i }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $i }}">{{ $i }}</option>
                                                        @endif
                                                    @endfor
                                                </select>
                                                <div class="validation"></div>
                                            </div>
                                        </div>
                                    </div>

				                    @if($total_product == 0)
                                        <div class="row">
                                            <div class="col-md-12 text-right"
                                                style="margin-bottom: 1em;">
                                                <button id="tambah_product"
                                                    title="Tambah Product"
                                                    style="padding: 0.4em 0.7em;">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
				                    @else
                                        <div class="row">
                                            <div class="col-md-12 text-right"
                                                style="margin-bottom: 1em;">
                                                <button class="hapus_product"
                                                    value="{{ $total_product }}"
                                                    title="Hapus Product"
                                                    style="padding: 0.4em 0.7em; background-color: red;">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
				                    @endif

				                    {{-- KHUSUS Philiphin --}}
                                    @if(!is_numeric($ProductPromo['id']))
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="product_other_{{ $total_product }}" placeholder="Product Name" data-msg="Please fill in the product" value="{{ $ProductPromo['id'] }}" />
                                            <div class="validation"></div>
                                        </div>
                                    @endif

			                    @endforeach
			                    <div id="tambahan_product"></div>
			                    {{-- ++++++++++++++ ======== ++++++++++++++ --}}

			                    @if($orders['cash_upgrade'] == 2)
			                    <div class="form-group">
			                        <input type="text" class="form-control" name="old_product" id="old_product" placeholder="Old Product" value="{{$orders['old_product']}}" data-msg="Mohon Isi Produk Lama" style="text-transform:uppercase"/>
			                        <div class="validation"></div>
			                    </div>
			                    @endif

			                    <div class="form-group">
			                        <input type="text" class="form-control" name="prize" id="prize" placeholder="Prize Product" value="{{$orders['prize']}}" data-msg="Mohon Isi Hadiah" style="text-transform:uppercase"/>
			                        <div class="validation"></div>
			                    </div>
			                </div>
			                @endif
			                <br>

			                <div class="form-group">
			                	<label for="">Payment Method</label>
			                    <select class="form-control" id="payment_type" name="payment_type" data-msg="Mohon Pilih Tipe" required>
			                        <option selected disabled value="">Choose Payment Method</option>

			                        @foreach($paymentTypes as $key=>$paymentType)
			                        	@if($orders['payment_type'] == $key)
			                            	<option value="{{ $key }}" selected="true">{{ strtoupper($paymentType) }}</option>
			                            @else
			                            	<option value="{{ $key }}">{{ strtoupper($paymentType) }}</option>
			                            @endif
			                        @endforeach
			                    </select>
			                    <div class="validation"></div>
			                </div>

			                @if($orders['payment_type'] == 1 || $orders['payment_type'] == 2)
			                @php
	                            $payments = json_decode($orders['bank'], true);
	                        @endphp
			                <div id="container-jenispembayaran">
			                    {{-- ++++++++ BANK ++++++++ --}}
			                    @foreach($payments as $payment)
			                    <div class="form-group bank_select" style="width: 62%; display: inline-block;">
			                        <select class="form-control bank_name" name="bank_0" data-msg="Mohon Pilih Bank">
			                            <option selected disabled value="">Choose Bank</option>

			                            @foreach($banks as $key=>$bank)
			                            	@if($payment['id'] == $key)
			                                	<option value="{{ $key }}" selected="true">{{ $bank }}</option>
			                                @else
			                                	<option value="{{ $key }}">{{ $bank }}</option>
			                                @endif
			                            @endforeach
			                        </select>
			                        <div class="validation"></div>
			                    </div>
			                    <div class="form-group bank_select" style="width: 26%; display: inline-block;">
			                        <select class="form-control bank_cicilan" name="cicilan_0" data-msg="Mohon Pilih Jumlah Cicilan">
			                            <option selected value="1">1X</option>
			                            @for($i=2; $i<=12;$i+=2)
			                            	@if($payment['cicilan'] == $i)
			                                	<option class="other_valCicilan" value="{{ $i }}" selected="true">{{ $i }}X</option>
			                                @else
			                                	<option class="other_valCicilan" value="{{ $i }}">{{ $i }}X</option>
			                                @endif
			                            @endfor
			                        </select>
			                        <div class="validation"></div>
			                    </div>
			                    @endforeach
			                    <div class="text-center" style="display: inline-block; float: right;"><button id="tambah_bank" title="Tambah Bank" style="padding: 0.4em 0.7em;"><i class="fas fa-plus"></i></button></div>

			                    <div id="tambahan_bank"></div>
			                    {{-- ++++++++ ==== ++++++++ --}}
			                    <div class="form-group">
			                        <input type="number" class="form-control" name="total_payment" id="total_payment" value="{{$orders['total_payment']}}" required data-msg="Mohon Isi Total Harga" style="text-transform:uppercase"/>
			                        <div class="validation"></div>
			                    </div>
			                    <div class="form-group">
			                        <input type="number" class="form-control" name="down_payment" id="down_payment" value="{{$orders['down_payment']}}" required data-msg="Mohon Isi Down Payment(DP)" style="text-transform:uppercase"/>
			                        <div class="validation"></div>
			                    </div>
			                    <div class="form-group">
			                        <input type="number" class="form-control" name="remaining_payment" id="remaining_payment" value="{{$orders['remaining_payment']}}" required data-msg="Mohon Isi Sisa Pembayaran" style="text-transform:uppercase"/>
			                        <div class="validation"></div>
			                    </div>
			                </div>
			                @endif
			                <br>

			                <div class="form-group">
			                	<label for="">Branch</label>
			                    <select class="form-control" id="branch" name="branch_id" data-msg="Mohon Pilih Cabang" required>
			                        <option selected disabled value="">Choose Branch</option>

			                        @foreach($branches as $branch)
			                        	@if($orders['branch_id'] == $branch['id'])
			                            	<option value="{{ $branch['id'] }}" selected="true">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
			                            @else
			                            	<option value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
			                            @endif
			                        @endforeach
			                    </select>
			                    <div class="validation"></div>
			                </div>

			                @if($orders['branch_id'] != null)
			                <div id="container-Cabang">
			                    <div class="form-group">
			                    	<label for="">CSO Code</label>
			                        <input type="text" class="form-control cso" name="cso_id" id="cso" value="{{$orders->cso['code']}}" required data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase" {{ Auth::user()->roles[0]['slug'] == 'cso' ? "readonly=\"\"" : "" }} />
									<input type="hidden" class="csoId" name="idCSO" value="">
									<div class="validation"></div>
			                    </div>
			                    <div class="form-group">
			                    	<label for="">CSO Code 30%</label>
			                        <input type="text" class="form-control cso" name="30_cso_id" id="30_cso" value="{{$orders->cso['code']}}" required data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase"/>
									<input type="hidden" class="csoId" name="idCSO30" value="">
									<div class="validation"></div>
			                    </div>
			                    <div class="form-group">
			                    	<label for="">CSO Code 70%</label>
			                        <input type="text" class="form-control cso" name="70_cso_id" id="70_cso" value="{{$orders->cso['code']}}" required data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase"/>
									<input type="hidden" class="csoId" name="idCSO70" value="">
									<div class="validation"></div>
			                    </div>
			                </div>
			                @endif
			                <br>


			                <div class="form-group">
			                	<label for="">Customer Type</label>
			                    <input type="text" class="form-control" name="customer_type" id="customer_type" value="{{$orders['customer_type']}}" required data-msg="Mohon Isi Tipe Customer" />
			                    <div class="validation"></div>
			                </div>
			                <div class="form-group">
			                	<label for="">Description</label>
			                    <textarea class="form-control" name="description" rows="5" data-msg="Mohon Isi Keterangan" value="{{$orders['description']}}">{{$orders['description']}}</textarea>
			                    <div class="validation"></div>
			                </div>

	              			<div id="errormessage"></div>

	              			<div class="form-group">
	              				<input type="hidden" name="idOrder" value="{{$orders['id']}}">
	              				<input type="hidden" id="lastTotalProduct" value="{{$total_product}}">
	              				<button id="updateOrder" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
	              				<button class="btn btn-light">Cancel</button>
	              			</div>
	            		</form>
	          		</div>
	        	</div>
	      	</div>
	    </div>
	</div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
<script type="application/javascript">
let promoOption = "";
let quantityOption = "";

document.addEventListener("DOMContentLoaded", function () {
    const URL = '<?php echo route("fetch_promo_dropdown"); ?>';

    fetch(
        URL,
        {
            method: "GET",
			headers: {
				"Accept": "application/json",
			},
        }
    ).then(function (response) {
		if (!response.ok) {
			throw new Error(`HTTP error! status: ${response.status}`);
		}

		return response.json();
	}).then(function (response) {
		const dataPromo = response.data;

        for (const promo in dataPromo) {
            promoOption += `<option value="${promo}">${dataPromo[promo].product}</option>`;
        }
	}).catch(function (error) {
		console.error(error);
	});

    for (let i = 1; i <= 10; i++) {
        quantityOption += `<option value="${i}">${i}</option>`;
    }
}, false);
</script>
<script type="application/javascript">
	$(document).ready(function() {
        var frmUpdate;

	    $("#actionUpdate").on("submit", function (e) {
	        e.preventDefault();
	        frmUpdate = _("actionUpdate");
	        frmUpdate = new FormData(document.getElementById("actionUpdate"));
	        frmUpdate.enctype = "multipart/form-data";
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
	        document.getElementById("updateOrder").innerHTML = "UPLOADING...";
	    }

	    function completeHandler(event){
	        var hasil = JSON.parse(event.target.responseText);
	        console.log(hasil);

	        for (var key of frmUpdate.keys()) {
	            $("#actionUpdate").find("input[name="+key+"]").removeClass("is-invalid");
	            $("#actionUpdate").find("select[name="+key+"]").removeClass("is-invalid");
	            $("#actionUpdate").find("textarea[name="+key+"]").removeClass("is-invalid");

	            $("#actionUpdate").find("input[name="+key+"]").next().find("strong").text("");
	            $("#actionUpdate").find("select[name="+key+"]").next().find("strong").text("");
	            $("#actionUpdate").find("textarea[name="+key+"]").next().find("strong").text("");
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

	        document.getElementById("updateOrder").innerHTML = "SAVE";
	    }

	    function errorHandler(event){
	        document.getElementById("updateOrder").innerHTML = "SAVE";
	    }

		$("#province").on("change", function(){
            var id = $(this).val();
            $( "#city" ).html("");
			$( "#subDistrict" ).html("");
			$( "#subDistrict" ).html("<option selected disabled value=\"\">Pilihan Kecamatan</option>");
            $.get( '{{ route("fetchCity", ['province' => ""]) }}/'+id )
            .done(function( result ) {
                result = result['rajaongkir']['results'];
                var arrCity = "<option selected disabled value=\"\">Pilihan Kota</option>";
                if(result.length > 0){
                    $.each( result, function( key, value ) {
                    	if(value['type'] == "Kabupaten"){
                        	arrCity += "<option value=\""+value['city_id']+"\">Kabupaten "+value['city_name']+"</option>";
                        }

                        if(value['type'] == "Kota"){
                            arrCity += "<option value=\""+value['city_id']+"\">Kota "+value['city_name']+"</option>";
                        }


                    });
                    $( "#city" ).append(arrCity);
                }
            });
		});

		$("#city").on("change", function(){
            var id = $(this).val();
			$( "#subDistrict" ).html("");
            $.get( '{{ route("fetchDistrict", ['city' => ""]) }}/'+id )
            .done(function( result ) {
				result = result['rajaongkir']['results'];
				console.log(result);
                var arrSubDistsrict = "<option selected disabled value=\"\">Pilihan Kecamatan</option>";
                if(result.length > 0){
                    $.each( result, function( key, value ) {
                        arrSubDistsrict += "<option value=\""+value['subdistrict_id']+"\">"+value['subdistrict_name']+"</option>";
                    });
                    $( "#subDistrict" ).append(arrSubDistsrict);
                }
            });
        });
	});
</script>
<script type="application/javascript">
    var total_bank = 0;
    var total_product = $('#lastTotalProduct').val();
    var count = 0;
    var arrBooleanCso = ['false', 'false', 'false'];

    $(document).ready(function () {
        for (let i = 0; i <= total_product; i++) {
            $("#product_" + i).select2({
                theme: "bootstrap4",
            });
        }

        $(".cso").on("input", function () {
            var txtCso = $(this).val();
            var temp = $(this);
            $.get( '{{route("fetchCso")}}', { cso_code: txtCso })
            .done(function( result ) {
                var bool = false;
				console.log(result)
                if (result.result == 'true'){
                    $(temp).parent().children('.validation').html('Kode CSO Benar');
                    $(temp).parent().children('.validation').css('color', 'green');
                    bool = true;
					$(temp).parent().children('.csoId').val(result.data[0].id);
                }
                else{
                    $(temp).parent().children('.validation').html('Kode CSO Salah');
                    $(temp).parent().children('.validation').css('color', 'red');
                }
                if(temp.attr("id") == 'cso'){
                    arrBooleanCso[0] = bool;
                }
                else if(temp.attr("id") == '30_cso'){
                    arrBooleanCso[1] = bool;
                }
                else if(temp.attr("id") == '70_cso'){
                    arrBooleanCso[2] = bool;
                }
                console.log(arrBooleanCso[0]+" "+arrBooleanCso[1]+" "+arrBooleanCso[2]);
                if(arrBooleanCso[0] == true && arrBooleanCso[1] == true && arrBooleanCso[2] == true){
                    $('#submit').removeAttr('disabled');
                    console.log("masuk");
                }
                else{
                    $('#submit').attr('disabled',"");
                }
            });
        });

        $("#tambah_bank").click(function(e){
            e.preventDefault();
            total_bank++;
            strIsi = "<div class=\"form-group bank_select\" style=\"width: 62%; display: inline-block;\" id=\"bank_"+total_bank+"\"><select class=\"form-control bank_name\" name=\"bank_"+total_bank+"\" data-msg=\"Mohon Pilih Bank\"><option selected disabled value=\"\">Choose Bank</option> @foreach($banks as $key=>$bank) <option value=\"{{ $key }}\">{{ $bank }}</option> @endforeach </select><div class=\"validation\"></div></div><div class=\"form-group bank_select\" style=\"width: 26%; display: inline-block;\" id=\"cicilan_"+total_bank+"\"><select class=\"form-control bank_cicilan\" name=\"cicilan_"+total_bank+"\" data-msg=\"Mohon Pilih Jumlah Cicilan\"><option selected value=\"1\">1X</option> @for($i=2; $i<=12;$i+=2) <option class=\"other_valCicilan\" value=\"{{ $i }}\">{{ $i }}X</option> @endfor </select><div class=\"validation\"></div></div><div class=\"text-center\" style=\"display: inline-block; float: right;\"><button class=\"hapus_bank\" value=\""+total_bank+"\" title=\"Hapus Bank\" style=\"padding: 0.4em 0.7em; background-color: red\"><i class=\"fas fa-minus\"></i></button></div>";
            $('#tambahan_bank').html($('#tambahan_bank').html()+strIsi);


            if($("#payment_type").val() == 1){
                $(".other_valCicilan").attr('disabled', "");
                $(".other_valCicilan").hide();
            }
            else{
                $(".other_valCicilan").removeAttr('disabled');
                $(".other_valCicilan").show();
            }
        });

        $(document).on("click",".hapus_bank", function(e){
            e.preventDefault();
            total_bank--;
            $('#bank_'+$(this).val()).remove();
            $('#cicilan_'+$(this).val()).remove();
            $(this).remove();
        });

        $("#tambah_product").click(function (e) {
            e.preventDefault();
            total_product++;
            count = total_product + 1;

            const newDivProduct = document.createElement("div");
            newDivProduct.className = "form-group";
            newDivProduct.style = "width: 74%; float: left; display: inline-block;";

            const newSelectProduct = document.createElement("select");
            newSelectProduct.id = `product_${total_product}`;
            newSelectProduct.className = "form-control pilihan-product";
            newSelectProduct.name = `product_${total_product}`;
            newSelectProduct.required = true;
            newSelectProduct.innerHTML = promoOption;

            const newDivQty = document.createElement("div");
            newDivQty.id = `qty_${total_product}`;
            newDivQty.className = "form-group";
            newDivQty.style = "width: 14%; float: right; display: inline-block;";

            const newSelectQty = document.createElement("select");
            newSelectQty.className = "form-control";
            newSelectQty.name = `qty_${total_product}`;
            newSelectQty.innerHTML = quantityOption;

            const newDivRemove = document.createElement("div");
            newDivRemove.className = "col-md-12";
            newDivRemove.style = "margin-bottom: 1em; display:flex; justify-content: flex-end; padding: 0;";

            const newButtonRemove = document.createElement("button");
            newButtonRemove.className = "hapus_product";
            newButtonRemove.value = total_product;
            newButtonRemove.title = "Kurangi Produk";
            newButtonRemove.style = "padding: 0.4em 0.7em; background-color: red;";
            newButtonRemove.innerHTML = '<i class="fas fa-minus"></i>';

            const newDivOther = document.createElement("div");
            newDivOther.className = "form-group d-none";

            const newInputOther = document.createElement("input");
            newInputOther.type = "text";
            newInputOther.className = "form-control";
            newInputOther.name = `product_other_${total_product}`;
            newInputOther.placeholder = "Product Name";

            newDivProduct.appendChild(newSelectProduct);
            newDivQty.appendChild(newSelectQty);
            newDivRemove.appendChild(newButtonRemove);
            newDivOther.appendChild(newInputOther);

            document.getElementById("tambahan_product").appendChild(newDivProduct);
            document.getElementById("tambahan_product").appendChild(newDivQty);
            document.getElementById("tambahan_product").appendChild(newDivRemove);
            document.getElementById("tambahan_product").appendChild(newDivOther);

            $("#product_" + total_product).select2({
                theme: "bootstrap4",
            });
        });

        $(document).on("click",".hapus_product", function(e){
            e.preventDefault();
            // total_product--;
            $('#product_'+$(this).val()).remove();
            $('#qty_'+$(this).val()).remove();
            $(this).remove();
        });

        $("#cash_upgarde").change( function(e){
            $("#container-cashupgrade").show();
            if($(this).val() == 2){
                $("#old_product").parent().show();
                $("#old_product").attr('required', "");
            }
            else{
                $("#old_product").parent().hide();
                $("#old_product").removeAttr('required');
            }
        });

        $(document).on("change", "#payment_type", function(e){
            $("#container-jenispembayaran").show();
            $(".other_valCicilan").parent().val('1');
            $('#tambahan_bank').html("");
            if($(this).val() == 1){
                $(".other_valCicilan").attr('disabled', "");
                $(".other_valCicilan").hide();
            }
            else{
                $(".other_valCicilan").removeAttr('disabled');
                $(".other_valCicilan").show();
            }
        });

         $("#branch").change( function(e){
            $("#container-Cabang").show();
        });

        $(".pilihan-product").change( function(e){
            if($(this).val() == 'other'){
                $(this).parent().next().next().removeClass("d-none");
                $(this).parent().next().next().children().attr('required', '');
            }
            else{
                $(this).parent().next().next().addClass("d-none");
                $(this).parent().next().next().children().removeAttr('required', '');
            }
        });
    });
</script>
@endsection
