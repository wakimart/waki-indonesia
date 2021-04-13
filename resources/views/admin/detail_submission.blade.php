<?php
$menu_item_page = "submission";
$menu_item_second = "detail_submission_form";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
    #intro {
        padding-top: 2em;
    }

    table {
        margin: 1em;
        font-size: 14px;
    }

    .table-responsive table{
        
        display: inline-table;
        table-layout:fixed;
        overflow:scroll;
    }

    .table-responsive table td{
        word-wrap:break-word;
    }

    .table-responsive table .form-control {
        height: 32px;
        line-height: 32px;
        box-shadow: none;
        border-radius: 2px;
        margin-bottom: 0;
    }
	.table-responsive table .form-control.error {
		border-color: #f50000;
	}
	.table-responsive table td .save {
		display: none;
	}

    table thead {
        background-color: #8080801a;
        text-align: center;
    }

    table td {
        border: 0.5px #8080801a solid;
        padding: 0.5em;

    }

    table, td{
        background-color: #fff !important;
    }

    .center {
        text-align: center;
    }

    .right {
        text-align: right;
    }

    .pInTable {
        margin-bottom: 6pt !important;
        font-size: 10pt;
    }

    select.form-control {
        color: black !important;
    }

</style>
@endsection

@section('content')
@if ($deliveryOrder['code'] !== null)
    <section id="intro" class="clearfix">
        <div class="container">
            <div class="row justify-content-center">
                <h2>SUBMISSION SUCCESS</h2>
            </div>
            <div class="row justify-content-center">
                <table class="col-md-12">
                    <thead>
                        <td class="right">Submission Date</td>
                    </thead>
                    <tr>
                        <td class="right">
                            {{ date("d/m/Y H:i:s", strtotime($deliveryOrder['created_at'])) }}
                        </td>
                    </tr>
                </table>
                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Customer Data</td>
                    </thead>
                    <tr>
                        <td>Member Code: </td>
                        <td>{{ $deliveryOrder['no_member'] }}</td>
                    </tr>
                    <tr>
                        <td>Name: </td>
                        <td>{{ $deliveryOrder['name'] }}</td>
                    </tr>
                    <tr>
                        <td>Phone Number: </td>
                        <td>{{ $deliveryOrder['phone'] }}</td>
                    </tr>
                    <tr>
                        <td rowspan="2">Address: </td>
                        <td>{{ $deliveryOrder['address'] }}</td>
                    </tr>
                    <tr>
                        <td>
                            <?php
                            echo $deliveryOrder['district'][0]['province'];
                            echo ", ";
                            echo $deliveryOrder['district'][0]['kota_kab'];
                            echo ", ";
                            echo $deliveryOrder['district'][0]['subdistrict_name'];
                            ?>
                        </td>
                    </tr>
                </table>

                <table class="col-md-12">
                    <thead>
                        <td colspan="2">Detail Order</td>
                    </thead>
                    <thead style="background-color: #80808012 !important">
                        <td>Promo Name</td>
                        <td>Quantity</td>
                    </thead>

                    @foreach (json_decode($deliveryOrder['arr_product'], true) as $promo)
                        <tr>
                            @if (is_numeric($promo['id']) && $promo['id'] < 8)
                                <td>
                                    <?php
                                    echo App\DeliveryOrder::$Promo[$promo['id']]['code'];
                                    echo " - ";
                                    echo App\DeliveryOrder::$Promo[$promo['id']]['name'];
                                    echo " (";
                                    echo App\DeliveryOrder::$Promo[$promo['id']]['harga'];
                                    echo ")";
                                    ?>
                                </td>
                            @else
                                <td>{{ $promo['id'] }}</td>
                            @endif

                            <td>{{ $promo['qty'] }}</td>
                        </tr>
                    @endforeach
                </table>

                <table class="col-md-12">
                    <thead>
                        <td>Sales Branch</td>
                        <td>Sales Code</td>
                    </thead>
                    <tr>
                        <td style="width:50%; text-align: center">
                            <?php
                            echo $deliveryOrder->branch['code'];
                            echo " - ";
                            echo $deliveryOrder->branch['name'];
                            ?>
                        </td>
                        <td style="width:50%; text-align: center">
                            {{ $deliveryOrder->cso['code'] }}
                        </td>
                    </tr>
                </table>

                    <table class="col-md-12">
                        <thead>
                            <td colspan="8">Reference</td>
                        </thead>
                        <thead style="background-color: #80808012 !important">
                            <td>Name</td>
                            <td>Age</td>
                            <td>Phone</td>
                            <td>City</td>
                            <td>Souvenir</td>
                            <td>Link HS</td>
                            <td>Status</td>
                            <td>Edit</td>
                        </thead>
                        <?php foreach ($references as $key => $reference): ?>
                            <form id="ref_{{ $key }}">
                                <input type="hidden"
                                    id="id_{{ $key }}"
                                    class="d-none"
                                    {{-- name="id" --}}
                                    value="{{ $reference->id }}" />
                                <tr>
                                    <td id="name_{{ $key }}">
                                        <?php echo $reference->name; ?>
                                    </td>
                                    <td class="center" id="age_{{ $key }}">
                                        <?php echo $reference->age; ?>
                                    </td>
                                    <td class="center" id="phone_{{ $key }}">
                                        <?php echo $reference->phone; ?>
                                    </td>
                                    <td id="province_{{ $key }}"
                                        data-province="{{ $reference->province }}"
                                        data-city="{{ $reference->city }}">
                                        <?php echo $referencesCityAndProvince[$key]; ?>
                                    </td>
                                    <td id="souvenir_{{ $key }}"
                                        data-souvenir="{{ $reference->souvenir_id }}">
                                        <?php echo $referenceSouvenir[$key]; ?>
                                    </td>
                                    <td class="center" id="link-hs_{{ $key }}">
                                        <?php if (!empty($reference->link_hs)): ?>
                                            <a id="link-hs-href_{{ $key }}"
                                                href="<?php echo $reference->link_hs; ?>"
                                                target="_blank">
                                                <i class="mdi mdi-home" style="font-size: 24px; color: #2daaff;"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="center" id="status_{{ $key }}">
                                    {{--  <label for="edit-status"><label>
                                        <select class="form-control"
                                            id="edit-status"
                                            name="status" onchange="this.nextElementSibling.value=this.value">
                                            <option value="pending">pending</option>
                                            <option value="success">success</option>
                                        </select> --}}
                                        <?php echo $reference->status; ?>
                                    </td>
                                    <td class="center">
                                        <button class="btn"
                                            style="padding: 0;"
                                            data-toggle="modal"
                                            data-target="#edit-reference"
                                            data-edit="edit_{{ $key }}"
                                            onclick="clickEdit(this)"
                                            value="<?php echo $reference->id; ?>">
                                            <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                        </button>
                                    </td>
                                </tr>
                            </form>
                        <?php endforeach; ?>
                    </table>

                <div class="table-responsive">
                    <table class="col-md-12">
                        <thead>
                            <td colspan="12">Reference</td>
                        </thead>
                        <thead style="background-color: #80808012 !important">
                            <td>Name</td>
                            <td>Age</td>
                            <td>Phone</td>
                            <td colspan="2">Province</td>
                            <td colspan="3">City</td>
                            <td colspan="2">Souvenir</td>
                            <td>Link HS</td>
                            <td>Status</td>
                            <td>Edit</td>
                        </thead>
                            <form id="ref_1">
                                <tr>
                                    <td contenteditable="true" id="name">
                                        Amel
                                    </td>
                                    <td contenteditable="true" class="center" id="age">
                                        21
                                    </td>
                                    <td contenteditable="true" class="center" id="phone">
                                        0811111111
                                    </td>
                                    <td colspan="2" id="province" style="background-color: #fff;">
                                            <select contenteditable="true" class="form-control" style="min-width: 100px"
                                                id="edit-province"
                                                name="province" onchange="this.nextElementSibling.value=this.value">
                                                <option value="Maluku">Maluku</option>
                                                <option value="Jawa Barat">Jawa Barat</option>
                                            </select>
                                    </td>
                                    <td colspan="3" id="city" style="background-color: #fff;">
                                        <select contenteditable="true" class="form-control" style="min-width: 100px"
                                            id="edit-city"
                                            name="city" onchange="this.nextElementSibling.value=this.value">
                                            <option value="Kabupaten Maluku Barat Daya">Kabupaten Maluku Barat Daya</option>
                                            <option value="Kota Cirebon">Kota Cirebon</option>
                                        </select>
                                    </td>
                                    <td colspan="2" id="souvenir">
                                        <select contenteditable="true" class="form-control"
                                            id="edit-souvenir"
                                            name="souvenir" onchange="this.nextElementSibling.value=this.value">
                                            <option value="success">Jeep Hardtop 4x4</option>
                                            <option value="pending">Driver & BBM & Parkir</option>
                                            <option value="success">Tiket Masuk Bromo</option>
                                            <option value="success">Air Mineral</option>
                                        </select>
                                    </td>
                                    <td contenteditable="true" class="center" id="link-hs">
                                       https://dzsdfdfcsadfcsdfcsdsdvszdv
                                    </td>
                                    <td class="center scroll-code" id="status">
                                        <select contenteditable="true" class="form-control"
                                            id="edit-status"
                                            name="status" onchange="this.nextElementSibling.value=this.value">
                                            <option value="pending">pending</option>
                                            <option value="success">success</option>
                                        </select>
                                    </td>
                                    <td class="center">
                                        <button id="edit_button" value="edit" class="btn" onclick="edit_row(this)">
                                            <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                        </button>
                                        <button id="save_button" value="save" class="btn" onclick="save_row(this)">
                                            <i class="mdi mdi-content-save" style="font-size: 24px; color: #fed713;"></i>
                                        </button>
                                    </td>
                                </tr>
                            </form>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="col-md-12">
                        <thead>
                            <td colspan="9">Reference</td>
                        </thead>
                        <thead style="background-color: #80808012 !important">
                            <tr>
                                <td>Name</td>
                                <td>Age</td>
                                <td>Phone</td>
                                <td>Province</td>
                                <td>City</td>
                                <td>Souvenir</td>
                                <td>Link HS</td>
                                <td>Status</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="name" value="Amel">
                                    Amel
                                </td>
                                <td class="center" id="age" value="21">
                                    21
                                </td>
                                <td class="center" id="phone" value="0811111111">
                                    0811111111
                                </td>
                                <td id="province">
                                        <select class="form-control"
                                            id="edit-province"
                                            name="province">
                                            <option value="Maluku">Maluku</option>
                                            <option value="Jawa Barat">Jawa Barat</option>
                                        </select>
                                </td>
                                <td id="city">
                                    <select class="form-control"
                                        id="edit-city"
                                        name="city">
                                        <option value="Kabupaten Maluku Barat Daya">Kabupaten Maluku Barat Daya</option>
                                        <option value="Kota Cirebon">Kota Cirebon</option>
                                    </select>
                                </td>
                                <td class="souvenir">
                                    <select class="form-control"
                                        id="edit-souvenir"
                                        name="souvenir">
                                        <option value="success">Jeep Hardtop 4x4</option>
                                        <option value="pending">Driver & BBM & Parkir</option>
                                        <option value="success">Tiket Masuk Bromo</option>
                                        <option value="success">Air Mineral</option>
                                    </select>
                                </td>
                                <td>
                                   https://dzsdfdfcsadfcsdfcsdsdvszdv
                                </td>
                                <td id="status">
                                    <select class="form-control"
                                        id="edit-status"
                                        name="status">
                                        <option value="pending">pending</option>
                                        <option value="success">success</option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <a class="save" title="Save" data-toggle="tooltip"><i class="mdi mdi-content-save" style="font-size: 24px; color: blue;"></i></a>
                                    <a class="edit" title="Edit" data-toggle="tooltip"><i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i></a>
                                </td>
                            </tr>    
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row justify-content-center" style="margin-top: 2em;">
                <h2>SUBMISSION HISTORY LOG</h2>
            </div>
            <div class="row justify-content-center">
                <table class="col-md-12">
                    <thead>
                        <td>No.</td>
                        <td>Action</td>
                        <td>User</td>
                        <td>Change</td>
                        <td>Time</td>
                    </thead>
                    @if ($historyUpdateDeliveryOrder !== null)
                        @foreach ($historyUpdateDeliveryOrder as $key => $historyUpdateDeliveryOrder)
                            <tr>
                                <td class="center">{{ $key + 1 }}</td>
                                <td class="center">
                                    {{ $historyUpdateDeliveryOrder->method }}
                                </td>
                                <td class="center">
                                    {{ $historyUpdateDeliveryOrder->name }}
                                </td>
                                <?php
                                $dataChange = json_decode($historyUpdateDeliveryOrder->meta, true);
                                ?>
                                <td>
                                    @foreach ($dataChange['dataChange'] as $key => $value)
                                        <b>{{ $key }}</b>: {{ $value}}
                                        <br>
                                    @endforeach
                                </td>
                                <td class="center">
                                    {{ date("d/m/Y H:i:s", strtotime($historyUpdateDeliveryOrder->created_at)) }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
        <div id="response"></div>
    </section>
@else
    <div class="row justify-content-center">
        <h2>CANNOT FIND SUBMISSION</h2>
    </div>
@endif
<div class="modal fade"
    id="edit-reference"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Reference</h5>
                <button type="button"
                    id="edit-close"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-form" method="POST">
                    @csrf
                    <input type="hidden" id="edit-id" name="id" value="" />
                    <div class="form-group">
                        <label for="edit-name">Name</label>
                        <input type="text"
                            class="form-control"
                            id="edit-name"
                            name="name"
                            maxlength="191"
                            value=""
                            placeholder="Name"
                            required />
                    </div>
                    <div class="form-group">
                        <label for="edit-age">Age</label>
                        <input type="number"
                            class="form-control"
                            id="edit-age"
                            name="age"
                            value=""
                            placeholder="Age"
                            required />
                    </div>
                    <div class="form-group">
                        <label for="edit-phone">Phone</label>
                        <input type="tel"
                            class="form-control"
                            id="edit-phone"
                            name="phone"
                            value=""
                            placeholder="Phone"
                            required />
                    </div>
                    <div class="form-group">
                        <label for="edit-province">Province</label>
                        <select class="form-control"
                            id="edit-province"
                            onchange="setCity(this)"
                            name="province"
                            required>
                            <option selected disabled>
                                Pilih Provinsi
                            </option>
                            <?php
                            $result = RajaOngkir::FetchProvince();
                            $result = $result['rajaongkir']['results'];

                            if (sizeof($result) > 0) {
                                foreach ($result as $value) {
                                    echo '<option value="'
                                        . $value['province_id']
                                        . '">'
                                        . $value['province']
                                        . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-city">City</label>
                        <select class="form-control"
                            id="edit-city"
                            name="city"
                            required>
                            <option selected disabled>
                                Pilih Kota
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-souvenir">Souvenir</label>
                        <select class="form-control"
                            id="edit-souvenir"
                            name="souvenir_id">
                            <option selected disabled>
                                Pilih Souvenir
                            </option>
                            <?php foreach ($souvenirs as $souvenir): ?>
                                <option value="<?php echo $souvenir->id; ?>">
                                    <?php echo $souvenir->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-link-hs">Link Home Service</label>
                        <input type="url"
                            class="form-control"
                            id="edit-link-hs"
                            name="link_hs"
                            pattern="https://.*"
                            maxlength="191"
                            value=""
                            placeholder="Link Home Service" />
                    </div>
                    <div class="form-group">
                        <label for="edit-status">Status</label>
                        <select class="form-control"
                            id="edit-status"
                            name="status">
                            <option value="pending">pending</option>
                            <option value="success">success</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="btn-edit"
                    class="btn btn-gradient-primary mr-2"
                    data-sequence=""
                    onclick="submitEdit(this)">
                    Save
                </button>
                <button class="btn btn-light"
                    data-dismiss="modal"
                    aria-label="Close">
                    Back
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="application/javascript">
    $(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
	var actions = $("table td:last-child").html();
	// Edit row on edit button click
	$(document).on("click", ".edit", function(){	
        $(this).parents("tr").find("td:not(:last-child)").each(function(){
			$(this).html('<input type="text" class="form-control" value="' + $(this).text() + '">');
		});		
		$(this).parents("tr").find(".save, .edit").toggle();
    });
	// Delete row on delete button click
	$(document).on("click", ".delete", function(){
        $(this).parents("tr").remove();
		$(".add-new").removeAttr("disabled");
    });
});
</script>

<script type="application/javascript">

function edit_row(e){
    document.getElementById("edit_button").style.display="none";
    document.getElementById("save_button").style.display="block";

    var name=document.getElementById("name");
    var age=document.getElementById("age");
    var phone=document.getElementById("phone");
    var province=document.getElementById("province");
    var souvenir=document.getElementById("souvenir");
    var linkHS=document.getElementById("link-hs-href");
    var status=document.getElementById("status");
        
    var name_data=name.innerHTML;
    var age_data=age.innerHTML;
    var phone_data=phone.innerHTML;
    var province_data=province.innerHTML;
    var souvenir_data=souvenir.innerHTML;
    var linkHS_data=linkHS.innerHTML;
    var status_data=status.innerHTML;
        
    name.innerHTML="<input type='text' class='form-control' id='name_text' value='"+name_data+"'>";
    age.innerHTML="<input type='text' id='age_text' value='"+age_data+"'>";
    phone.innerHTML="<input type='text' id='phone_text' value='"+phone_data+"'>";
    province.innerHTML="<input type='text' id='province_text' value='"+province_data+"'>";
    souvenir.innerHTML="<input type='text' id='souvenir_text' value='"+souvenir_data+"'>";
    linkHS.innerHTML="<input type='text' id='linkHS_text' value='"+linkHS_data+"'>";
    status.innerHTML="<input type='text' id='status_text' value='"+status_data+"'>";
}

function save_row(){
    /*  var name_val=document.getElementById("name_text"+no).value;
    var country_val=document.getElementById("country_text"+no).value;
    var age_val=document.getElementById("age_text"+no).value;

    document.getElementById("name_row"+no).innerHTML=name_val;
    document.getElementById("country_row"+no).innerHTML=country_val;
    document.getElementById("age_row"+no).innerHTML=age_val; */

    document.getElementById("edit_button").style.display="block";
    document.getElementById("save_button").style.display="none";
}
</script>

<script type="application/javascript">
function getCSRF() {
    const getMeta = document.getElementsByTagName("meta");
    let metaCSRF = "";

    for (let i = 0; i < getMeta.length; i++) {
        if (getMeta[i].getAttribute("name") === "csrf-token") {
            metaCSRF = getMeta[i].getAttribute("content");
            break;
        }
    }

    return metaCSRF;
}

function clickEdit(e) {
    const getRefSeq = e.dataset.edit.split("_")[1];
    const id = document.getElementById("id_" + getRefSeq).value;
    const name = document.getElementById("name_" + getRefSeq).innerHTML.trim();
    const age = document.getElementById("age_" + getRefSeq).innerHTML.trim();
    const phone = document.getElementById("phone_" + getRefSeq).innerHTML.trim();
    const province = document.getElementById("province_" + getRefSeq).getAttribute("data-province");
    const city = document.getElementById("province_" + getRefSeq).getAttribute("data-city");
    const souvenir = document.getElementById("souvenir_" + getRefSeq).getAttribute("data-souvenir");
    const linkHS = document.getElementById("link-hs-href_" + getRefSeq).getAttribute("href");
    const status = document.getElementById("status_" + getRefSeq).innerHTML.trim();

    document.getElementById("edit-id").value = id;
    document.getElementById("edit-name").value = name;
    document.getElementById("edit-age").value = age;
    document.getElementById("edit-phone").value = phone;
    document.getElementById("edit-province").value = province;
    document.getElementById("edit-province").setAttribute("data-city", city);
    setCity(document.getElementById("edit-province"));
    document.getElementById("edit-souvenir").value = souvenir;
    document.getElementById("edit-link-hs").value = linkHS;
    document.getElementById("edit-status").value = status || "pending";
    document.getElementById("btn-edit").setAttribute("data-sequence", getRefSeq);
}

function setCity(e) {
    fetch(
        '<?php echo route("fetchCity", ["province" => ""]); ?>/' + e.value,
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
        const result = response["rajaongkir"]["results"];

        const arrCity = [];
        arrCity[0] = '<option disabled value="">Pilihan Kabupaten</option>';
        arrCity[1] = '<option disabled value="">Pilihan Kota</option>';

        if (result.length > 0) {
            result.forEach(function (currentValue) {
                if (currentValue["type"] === "Kabupaten") {
                    arrCity[0] += `<option value="${currentValue["city_id"]}">`
                        + `${currentValue['type']} ${currentValue['city_name']}`
                        + `</option>`;
                } else {
                    arrCity[1] += `<option value="${currentValue['city_id']}">`
                        + `${currentValue['type']} ${currentValue['city_name']}`
                        + `</option>`;
                }
            });

            document.getElementById("edit-city").innerHTML = '<option value="">All City</option>'
                + arrCity[0]
                + arrCity[1];

            document.getElementById("edit-city").value = e.dataset.city;
        }
    }).catch(function(error) {
        console.error(error);
    });
}

function submitEdit(e) {
    const URL = '<?php echo route("update_reference"); ?>';
    const data = new URLSearchParams();

    for (const pair of new FormData(document.getElementById("edit-form"))) {
        data.append(pair[0], pair[1]);
    }

    fetch(
        URL,
        {
            method: "POST",
            headers: {
                "Accept": "application/json",
                "X-CSRF-TOKEN": getCSRF(),
            },
            body: data,
        }
    ).then(function (response) {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    }).then(function (response) {
        const refSeq = e.dataset.sequence;
        const data = response.data;

        document.getElementById("name_" + refSeq).innerHTML = data.name;
        document.getElementById("age_" + refSeq).innerHTML = data.age;
        document.getElementById("phone_" + refSeq).innerHTML = data.phone;
        document.getElementById("province_" + refSeq).setAttribute("data-province", data.province);
        document.getElementById("province_" + refSeq).setAttribute("data-city", data.city);
        document.getElementById("province_" + refSeq).innerHTML = response.city + ", " + response.province;
        document.getElementById("souvenir_" + refSeq).setAttribute("data-souvenir", data.souvenir_id);
        document.getElementById("souvenir_" + refSeq).innerHTML = response.souvenir;

        if (data.link_hs) {
            document.getElementById("link-hs_" + refSeq).innerHTML = `<a href="${data.link_hs}" id="link-hs-href_${refSeq}" target="blank">`
                + `<i class="mdi mdi-home" style="font-size: 24px; color: #2daaff;"></i>`
                + `</a>`;
        } else {
            document.getElementById("link-hs_" + refSeq).innerHTML = "";
        }

        document.getElementById("status_" + refSeq).innerHTML = data.status;

        document.getElementById("edit-close").click();
    }).catch(function (error) {
        console.log(error);
    });
}
</script>
@endsection
