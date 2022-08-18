<?php
    $menu_item_page = "homeservice";
    $menu_item_second = "list_area_homeservice";
?>
@extends('admin.layouts.template')

@section('style')
	<style>
		.cjslib-day-indicator {
			color: #ffc107 !important;
			background-color: #1bcfb4 !important;
		}
		.cjslib-calendar.cjslib-size-small .cjslib-day > .cjslib-day-indicator {
			width: 24px;
			height: 24px;
		}
		.cjslib-calendar.cjslib-size-small .cjslib-day > .cjslib-indicator-type-numeric {
			font-size: 12px;
			font-weight: bolder;
			color: #ffffff !important;
		}
		.btnappoint {
			display: inline-block;
			font-weight: 400;
			font-size: 1.4em;
			padding: 0.2rem 0.8rem;
			border-radius: 0.1875rem;
			text-align: center;
			vertical-align: middle;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
		}
		.titleAppoin {
			font-weight: bolder;
		}
		.timeContainerDiv{
			flex: 1 !important;
		}
		.paragrapContainerDiv{
			flex-direction: column;
			align-items: normal !important;
		}
		.iconContainerDiv{
			flex: 1 !important;
		}
		.cjslib-day-indicator {
			olor: #ffa000; background-color: #ffa000;
		}
		.cjslib-indicator-type-numeric {
			color: #ffffff;
		}
		.cjslib-day.cjslib-day-today > .cjslib-day-num {
			border-color: #ffa000 !important;
		}
		.table-bordered th, .table-bordered td {
			border: 1px solid darkgray !important;
		}
	</style>

    <!-- Leaflet -->
    <link rel="stylesheet" href="{{asset('assets_leaflet/leaflet.css')}}" type="text/css">
    <script src="{{asset('assets_leaflet/leaflet.js')}}" type="text/javascript"></script>

	<script src="{{asset('assets_leaflet/leaflet.ajax.js')}}" type="text/javascript"></script>

	<script src="{{asset('assets_leaflet/wicket/wicket.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets_leaflet/wicket/wicket-leaflet.js')}}" type="text/javascript"></script>

	<link rel="stylesheet" href="{{asset('assets_leaflet/easy-button.css')}}" type="text/css">
	<script src="{{asset('assets_leaflet/easy-button.js')}}" type="text/javascript"></script>
	<link rel="stylesheet" href="{{asset('assets_leaflet/Control.FullScreen.css')}}">
	<script src="{{asset('assets_leaflet/Control.FullScreen.js')}}" type="text/javascript"></script>
	<link rel="stylesheet" href="{{asset('assets_leaflet/leaflet.legend.css')}}">
	<script src="{{asset('assets_leaflet/leaflet.legend.js')}}" type="text/javascript"></script>

	<style>
		.leaflet-legend-column {
			margin-left: 0;
		}
		.leaflet-legend-item i {
			padding-left: 0;
		}
		.leaflet-legend-title {
			font-size: 14px;
		}
	</style>
@endsection

@section('content')
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
  			<h3 class="page-title">List Area Home Service</h3>
  			<nav aria-label="breadcrumb">
    			<ol class="breadcrumb">
      				<li class="breadcrumb-item"><a data-toggle="collapse" href="#homeservice-dd" aria-expanded="false" aria-controls="homeservice-dd">Branch</a></li>
      				<li class="breadcrumb-item active" aria-current="page">List Area Home Service</li>
    			</ol>
  			</nav>
		</div>

		<div class="row">
			<div class="col-12 grid-margin">
				<div class="col-xs-12 col-sm-12 row"
					style="margin: 0;padding: 0;">
					<div class="col-xs-6 col-sm-4" style="margin-bottom: 0; padding: 0; display: inline-block">
						<div class="form-group">
							<label for="">Start Date</label>
							<input type="date"
								class="form-control"
								id="filter_start_date"
								name="filter_start_date"
								value="{{ isset($_GET['filter_start_date']) ? $_GET['filter_start_date'] : '' }}">
							<div class="validation"></div>
						</div>
					</div>
					<div class="col-xs-6 col-sm-4" style="margin-bottom: 0; padding: 0; display: inline-block">
						<div class="form-group">
							<label for="">End Date</label>
							<input type="date"
								class="form-control"
								id="filter_end_date"
								name="filter_end_date"
								value="{{ isset($_GET['filter_end_date']) ? $_GET['filter_end_date'] : '' }}">
							<div class="validation"></div>
						</div>
					</div>
				</div>

				<div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
					<div class="form-group">
					  <label for="">Filter By Team</label>
					  <select class="form-control"
						  id="filter_branch"
						  name="filter_branch">
						  <option value="" selected="">
							  All Branch
						  </option>
						  @foreach ($branches as $branch)
							  @php
							  $selected = "";

							  if (isset($_GET['filter_branch'])) {
								  if ((int) $_GET['filter_branch'] === (int) $branch['id']) {
									  $selected = "selected";
								  }
							  }
							  @endphp

							  <option {{ $selected }}
								  value="{{ $branch['id'] }}">
								  {{ $branch['code'] }} - {{ $branch['name'] }}
							  </option>
						  @endforeach
					  </select>
					  <div class="validation"></div>
					</div>
				</div>
				<div class="col-xs-6 col-sm-3" style="padding: 0;display: inline-block;">
					<div class="form-group">
						<label for="filter_cso">
							Filter By CSO
						</label>
						<input name="filter_cso"
							id="filter_cso"
							list="data_cso"
							class="text-uppercase form-control"
							placeholder="Search CSO"
							@isset($currentCso)
								value="{{ $currentCso['code'] . "-" . $currentCso['name'] }}"
							@endisset
							required />
						<span class="invalid-feedback">
							<strong></strong>
						</span>

						<datalist id="data_cso">
							<select class="form-control">
								<option value="All CSO"></option>
								@foreach ($csos as $cso)
									<option value="{{ $cso['code'] }}-{{ $cso['name'] }}"></option>
								@endforeach
							</select>
						</datalist>
						<div class="validation"></div>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
				<div class="col-xs-6 col-sm-6" style="padding: 0;display: inline-block;">
					<div class="form-group">
						<button id="btn-filter" type="button" class="btn btn-gradient-primary m-1" name="filter" value="-"><span class="mdi mdi-filter"></span> Apply Filter</button>
						<a href="{{ route('list_area_homeservice') }}"
							class="btn btn-gradient-danger m-1"
							value="-">
							<span class="mdi mdi-filter"></span> Reset Filter
						</a>
					</div>
				</div>
				</div>

				<div class="col-sm-12 col-md-12" style="padding: 0; border: 1px solid #ebedf2;">
					<div class="col-xs-12 col-sm-11 col-md-6 table-responsive" id="calendarContainer" style="padding: 0; float: left;"></div>
					<div class="col-xs-12 col-sm-11 col-md-6" id="organizerContainer" style="padding: 0; float: left;"></div>
				</div>

			</div>
  			<div class="col-12 mb-3">
    			<div class="card">
      				<div class="card-body">
						<h5>{{ date('d F Y', strtotime($startDate)) }} - {{ date('d F Y', strtotime($endDate)) }}</h5>
      					<h5 style="margin-bottom: 0.5em;">Total : {{ $layers->sum('count_hs') }} data</h5>
						<div id="div_map" style="width: 100%; min-height: 600px; height:100%;" class="map"></div>
      				</div>
    			</div>
  			</div>

			<div class="col-12 mb-3">
				<div class="card">
					<div class="card-body">
						<div class="cjslib-events cjslib-size-small">
							<div class="cjslib-date" style="background-color: rgb(255, 160, 0); color: white;">
								<h3 id="organizer-container-date" style="text-align: center"></h3>
							</div>
							<div class="cjslib-rows" id="organizerContainer-list-container">
								<div class="table-responsive">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th style="text-align: center;">
													No.
												</th>
												<th style="text-align: center;">
													Appointment
												</th>
												<th style="text-align: center;">
													Detail
												</th>
												<th></th>
											</tr>
										</thead>
										<tbody id="appointment-data"></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- partial -->
<!-- Modal View -->
<div class="modal fade" id="viewHomeServiceModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
	  <div class="modal-content">
		<div class="modal-header">
		  <h5 class="modal-title">View Appointment</h5>
		  <button type="button"
			  class="close"
			  data-dismiss="modal"
			  aria-label="Close">
			  <span aria-hidden="true">&times;</span>
		  </button>
		</div>
		<div class="modal-body">
			<table style="margin: auto;">
				<tr>
					<td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Type Customer: </td>
					<td id="view_type_customer" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
				</tr>
				<tr>
					<td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Type Home Service: </td>
					<td id="view_type_homeservices" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
				</tr>

				<tr><td style="padding-top: 1em;"></td></tr>

				<tr style="margin-top: 0.5em">
					<td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">No. Member: </td>
					<td id="view-no_member" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
				</tr>
				<tr style="margin-top: 0.5em">
					<td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Nama: </td>
					<td id="view-name" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
				</tr>
				<tr style="margin-top: 0.5em">
					<td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">No. Telp: </td>
					<td id="view-phone" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
				</tr>
				<tr style="margin-top: 0.5em">
					<td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Provinsi: </td>
					<td id="view-province" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
				</tr>
				<tr style="margin-top: 0.5em">
					<td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Kota/Kabupaten: </td>
					<td id="view-city" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
				</tr>
				<tr style="margin-top: 0.5em">
					<td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Kecamatan: </td>
					<td id="view-distric" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
				</tr>
				<tr style="margin-top: 0.5em">
					<td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Alamat: </td>
					<td id="view-address" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
				</tr>

				<tr><td style="padding-top: 1em;"></td></tr>

				<tr style="margin-top: 0.5em">
					<td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Cabang: </td>
					<td id="view-branch" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
				</tr>
				<tr style="margin-top: 0.5em">
					<td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Kode CSO: </td>
					<td id="view-cso" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
				</tr>
				<tr style="margin-top: 0.5em">
					<td style="width: 40%; text-align: right; font-weight: 600; vertical-align: baseline;">Kode Partner CSO: </td>
					<td id="view-cso2" style="width: 60%; text-align: left; padding-left: 0.5em;">-</td>
				</tr>
			</table>
		</div>
		<div class="modal-footer">
			<a id="url_share"
				href=""
				data-action="share/whatsapp/share"
				target="_blank">
				<button id="btn-share"
					type="button"
					class="btn btn-gradient-primary mr-2">
					<span class="mdi mdi-whatsapp">
					</span>
					Share
				</button>
			</a>
			<button class="btn btn-light"
				data-dismiss="modal"
				aria-label="Close">
				Cancel
			</button>
		</div>
	  </div>
	</div>
  </div>
  <!-- End Modal View -->
</div>
@endsection

@section('script')
<script>
	$(document).on("click", "#btn-filter", function(e){
	  var urlParamArray = new Array();
	  var urlParamStr = "";
	  if($('#filter_branch').length && $('#filter_branch').val() != ""){
		urlParamArray.push("filter_branch=" + $('#filter_branch').val());
	  }
      var filterCSO = document.getElementById("filter_cso");
      if (filterCSO) {
        filterCSO = filterCSO.value.trim()
        if (filterCSO !== "All CSO" && filterCSO.length) {
          const getCSOCode = filterCSO.split("-");
          urlParamArray.push("filter_cso=" + getCSOCode[0]);
        }
      }
      if($('#filter_start_date').val() != ""){
		urlParamArray.push("filter_start_date=" + $('#filter_start_date').val());
	  }
      if($('#filter_end_date').val() != ""){
		urlParamArray.push("filter_end_date=" + $('#filter_end_date').val());
	  }
	  for (var i = 0; i < urlParamArray.length; i++) {
		if (i === 0) {
		  urlParamStr += "?" + urlParamArray[i]
		} else {
		  urlParamStr += "&" + urlParamArray[i]
		}
	  }

	  window.location.href = "{{route('list_area_homeservice')}}" + urlParamStr;
	});
</script>

<script>
	function printAreaListHs(distric) {
		$.ajax({
			method: "post",
			url: "<?php echo route('print_area_list_hs', request()->query()); ?>",
			data: {
				"_token": "{{ csrf_token() }}",
				"distric": distric,
			},
			success: function(data, textStatus, jqXHR) {
				if (data.status == "success") {
					$("#organizer-container-date").html(data.distric['subdistrict_name'])
					$("#appointment-data").html(data.msg)
				} else
					alert(data.msg)
			},
			error: function(data, textStatus, jqXHR) {
				alert("Error!")
			},

		})
	}

	function clickView(btn) {
		var id_hs = btn;
		if(isNaN(id_hs)){
			id_hs = btn.value;
			console.log(id_hs);
		}else{
			console.log(id_hs);
		}

		const URL = "<?php echo route('detail_homeService'); ?>";

		fetch(
			URL + "?id=" + id_hs,
			{
				method: "GET",
				headers: {
					"Accept": "application/json",
					"X-CSRF-TOKEN": "{{ csrf_token() }}",
				},
			}
		).then(function (response) {
			if (!response.ok) {
				throw new Error(`HTTP error! status: ${response.status}`);
			}

			return response.json();
		}).then(function (response) {
			const result = response.result;

			document.getElementById("view_type_customer").innerHTML = result.type_customer;
			document.getElementById("view_type_homeservices").innerHTML = result.type_homeservices;
			document.getElementById("view-no_member").innerHTML = result.no_member;
			document.getElementById("view-name").innerHTML = result.name;
			document.getElementById("view-phone").innerHTML = (result.phone).toString();
			document.getElementById("view-province").innerHTML = result.province_name;
			document.getElementById("view-city").innerHTML = result.city_name;
			document.getElementById("view-distric").innerHTML = result.district_name;
			document.getElementById("view-address").innerHTML = result.address;
			document.getElementById("view-branch").innerHTML = result.branch_code_name;
			document.getElementById("view-cso").innerHTML = result.cso_code_name;
			document.getElementById("view-cso2").innerHTML = result.cso2_code_name;

			document.getElementById("url_share").setAttribute(
				"href",
				"whatsapp://send?text=<?php echo route('homeServices_success'); ?>"
				+ "?code="
				+ result.code
			);
		}).catch(function (error) {
			console.error(error);
		});
	}
</script>

{{-- Map --}}
<script>
	const defaultView = {coor: [-7.2859717, 112.7278266], zoom: 11.8 }

	var map = L.map('div_map', {
		fullscreenControl: true,
		fullscreenControlOptions: {
			position: 'topleft'
		}
	}).setView(defaultView.coor, defaultView.zoom);
	var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {});
	var googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
		maxZoom: 20,
		subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
	});
	var googleHybrid = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
		maxZoom: 20,
		subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
	});
	var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
		maxZoom: 20,
		subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
	});

	googleStreets.addTo(map);

	var baseMaps = {
		'OSM': osm,
		'Google Street': googleStreets,
		'Google Hybrid': googleHybrid,
		'Google Satellite': googleSat
	};

	// var kecamatan= L.geoJson.ajax("{{asset('geojson/Kec_Surabaya_2017.geojson')}}",{color:'black', fillOpacity:0});

	var resetMapViewBtn = L.easyButton(' <span>&target;</span>', function() {
		map.setView(defaultView.coor, defaultView.zoom);
	});
	resetMapViewBtn.addTo(map);

	var isClicked = false;
	map.on ({
		click: function() {
			isClicked = false
		},
		popupclose: function () {
			isClicked = false
		}
	})

	// Region
	var legends = [];
	@foreach ($regions as $region)
		var layerGroupRegion_{{ $region->id }} = L.layerGroup([]);
		legends.push({
			label: "{{ $region->name }}",
			type: "polygon",
			sides: 5,
			color: "{{ $region->bg_color }}",
			fillColor: "{{ $region->bg_color }}",
			weight: 2,
			opacity: 1,
			fillOpacity: 0.7,
		})
	@endforeach

	var legend = L.control.Legend({
		position: "bottomleft",
		collapsed: false,
		column: 2,
		legends: legends
	}).addTo(map)


	var wkt = new Wkt.Wkt();
	<?php
        $nomer = 0;
        if (isset($layers)) {
            foreach ($layers as $key => $layer) {
        ?>
				var regionColor = '{{ $layer->rg_bg_color ?? "#000" }}'
        var colorBorder = "#63b2c5"
				var regionBgOpacity = "{{ ($layer->count_hs != 0) ? '0.6' : '0.2' }}"
				var regionBorderOpacity = "{{ ($layer->count_hs != 0) ? '0.3' : '0.3' }}"

                var geom = "{{ $layer->geom }}";
                wkt.read(geom);
				var layer_<?php echo $layer->id + $nomer; ?> = wkt.toObject({
					fillColor: regionColor,
					fillOpacity: regionBgOpacity,
					color: colorBorder,
					opacity: regionBorderOpacity
				});

				@if ($layer->count_hs >= 0)
					layer_<?php echo $layer->id + $nomer; ?>
						.bindPopup("Kecamatan: {{ $layer->subdistrict_name }} <br> Total HS: {{ $layer->count_hs }}");

					layer_<?php echo $layer->id + $nomer; ?>.on({
						mouseover: function() {
							if (!isClicked) {
								this.openPopup();
							}
						},
						mouseout: function() {
							if (!isClicked) {
								this.closePopup()
							}
						},
						click: function() {
							isClicked = true
							this.openPopup()
							printAreaListHs("{{ $layer->distric }}")
						}
					});
				@endif

				// layer_<?php echo $layer->id + $nomer; ?>.addTo(map)
				layerGroupRegion_<?php echo $layer->rg_id; ?>.addLayer(layer_<?php echo $layer->id + $nomer; ?>).addTo(map);
        <?php
            }
        }
	?>

	var overlayMaps = {};

	@foreach ($regions as $region)
		overlayMaps["{{ $region->name }}"] = layerGroupRegion_<?php echo $region->id; ?>,
	@endforeach
	L.control.layers(baseMaps, overlayMaps).addTo(map);
</script>
@endsection
