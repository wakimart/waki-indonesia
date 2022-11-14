<?php
$menu_item_page = "submission_video_photo";
$menu_item_second = "add_submission_video_photo";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="{{ asset("css/lib/select2/select2-bootstrap4.min.css") }}" />
<style type="text/css">
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

    #regForm {
        background-color: #ffffff;
        margin: 100px auto;
        padding: 40px;
        width: 70%;
        min-width: 300px;
    }

    /* Style the input fields */
    input {
        padding: 10px;
        width: 100%;
        font-size: 17px;
        font-family: Raleway;
        border: 1px solid #aaaaaa;
    }

    /* Mark input boxes that gets an error on validation: */
    input.invalid {
        background-color: #ffdddd;
    }

    .invalid {
        border: 1px solid red !important;
        border-color: #f50000 !important;
    }

    /* Hide all steps by default: */
    .tab {
        display: none;
    }

    /* Make circles that indicate the steps of the form: */
    .step {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbbbbb;
        border: none;
        border-radius: 50%;
        display: inline-block;
        opacity: 0.5;
    }

    /* Mark the active step: */
    .step.active {
        opacity: 1;
    }

    /* Mark the steps that are finished and valid: */
    .step.finish {
        background-color: #4CAF50;
    }

    select {
        color: black !important;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                Add Submission Video & Photo
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#"
                            aria-expanded="false"
                            aria-controls="deliveryorder-dd">
                            Submission Video & Photo
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Add Submission Video & Photo
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
                            enctype="multipart/form-data"
                            action="{{ route("store_submission_video_photo") }}">
                            @csrf
                            <div class="form-group">
                                <label>Type</label>
                                <input type="text"
                                    readonly
                                    disabled
                                    value="Testimonial Video Photo" />
                            </div>
                            <div class="form-group">
                                <label id="submission_date" for="submission_date">
                                    Submission Date
                                </label>
                                <input id="submission_date"
                                    type="date"
                                    class="form-control"
                                    name="submission_date"
                                    required/>
                                <div class="validation"></div>
                            </div>
                            <div class="form-group">
                                <label for="branch">Branch</label>
                                <select class="form-control"
                                    id="branch"
                                    name="branch_id"
                                    data-msg="Mohon Pilih Cabang"
                                    required>
                                    <option selected disabled value="">
                                        Choose Branch
                                    </option>

                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch['id'] }}">
                                            {{ $branch['code'] }} - {{ $branch['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="validation"></div>
                                <small id="submission_video_photo_error" style="color: red"></small>
                                <small id="submission_video_photo_success" style="color: green"></small>
                            </div>

                            <br>
                            <div id="refrensiForm" class="form-group" style="display: none;">
                                <h3>Testimonial Data</h3>
                                <br>
                                @for ($x = 0; $x < 10; $x++)
                                    <div class="tab">
                                        <input type="hidden" name="member_details[]" value="{{ $x }}">
                                        <label for="member-name-{{ $x }}">
                                            Testimonial {{ $x + 1 }}
                                        </label>
                                        <div class="form-group">
                                            <label for="member-detail_date-{{ $x }}">
                                                Detail Date
                                            </label>
                                            <input type="date"
                                                id="member-detail_date-{{ $x }}"
                                                class="form-control"
                                                name="detail_date_ref_{{ $x }}"
                                                placeholder="Name"
                                                {{ $x > 0 ? "" : "required" }} />
                                        </div>

                                        <div class="form-group">
                                            <label for="member-cso-{{ $x }}">
                                                Cso
                                            </label>
                                            <select class="form-control select2"
                                                id="member-cso-{{ $x }}"
                                                name="cso_ref_{{ $x }}"
                                                data-msg="Mohon Pilih Cso"
                                                {{ $x > 0 ? "" : "required" }}>
                                                <option selected disabled value="">
                                                    Choose Cso
                                                </option>

                                                @foreach ($csos as $cso)
                                                    <option value="{{ $cso['id'] }}">
                                                        {{ $cso['code'] }} - {{ $cso['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        

                                        <div class="form-group">
                                            <label for="member-mpc_wakimart-{{ $x }}">
                                                MPC/Wakimart (Optional)
                                            </label>
                                            <input type="text"
                                                id="member-mpc_wakimart-{{ $x }}"
                                                class="form-control"
                                                name="mpc_wakimart_ref_{{ $x }}"
                                                placeholder="MPC/Wakimart" />
                                        </div>

                                        <div class="form-group">
                                            <label for="member-name-{{ $x }}">
                                                Name
                                            </label>
                                            <input type="text"
                                                id="member-name-{{ $x }}"
                                                class="form-control"
                                                name="name_ref_{{ $x }}"
                                                placeholder="Name"
                                                {{ $x > 0 ? "" : "required" }} />
                                        </div>

                                        <div class="form-group">
                                            <label for="member-phone-{{ $x }}">
                                                Phone Number
                                            </label>
                                            <input type="number"
                                                id="member-phone-{{ $x }}"
                                                class="form-control"
                                                name="phone_ref_{{ $x }}"
                                                placeholder="Phone Number"
                                                {{ $x > 0 ? "" : "required" }} />
                                        </div>

                                        <div class="form-group">
                                            <label for="member-address-{{ $x }}">Address</label>
                                            <textarea class="form-control"
                                                id="member-address-{{ $x }}"
                                                name="address_ref_{{ $x }}"
                                                rows="4"
                                                placeholder="Full address"
                                                {{ $x > 0 ? "" : "required" }} ></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="member-url_drive-{{ $x }}">Url Drive Video & Photo</label>
                                            <input type="text" 
                                                class="form-control"
                                                id="member-url_drive-{{ $x }}"
                                                name="url_drive_ref_{{ $x }}"
                                                placeholder="https://deive.google.com/drive/folders/"
                                                {{ $x > 0 ? "" : "required" }} />
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="member-souvenir-{{ $x }}">
                                                Souvenir
                                            </label>
                                            <select class="form-control"
                                                id="member-souvenir-{{ $x }}"
                                                name="souvenir_{{ $x }}"
                                                {{ $x > 0 ? "" : "required" }}>
                                                <option selected
                                                    disabled
                                                    hidden
                                                    value="">
                                                    Choose Souvenir
                                                </option>
                                                @foreach ($souvenirs as $souvenir)
                                                    @if ($souvenir->id === 7)
                                                        <option value="{{ $souvenir->name }}" hidden>
                                                            {{ $souvenir->name }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $souvenir->name }}">
                                                            {{ $souvenir->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endfor

                                <div style="text-align: center; margin-top: 40px;">
                                    @for ($x = 0; $x < 10; $x++)
                                        <span class="step"></span>
                                    @endfor
                                </div>

                                <div style="overflow: auto;">
                                    <div style="float: right;">
                                        <button type="button"
                                            id="prevBtn"
                                            onclick="nextPrev(-1)">
                                            Previous
                                        </button>
                                        <button type="button"
                                            id="nextBtn"
                                            onclick="nextPrev(1)">
                                            Next
                                        </button>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="form-group">
                                    <button id="addDeliveryOrder"
                                        type="submit"
                                        class="btn btn-gradient-primary">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("script")
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
<script type="application/javascript">
let currentTab = 0;
showTab(currentTab);

function showTab(n) {
    // This function will display the specified tab of the form ...
    const x = document.getElementsByClassName("tab");
    x[n].style.display = "block";

    // ... and fix the Previous/Next buttons:
    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }

    if (n == (x.length - 1)) {
        document.getElementById("nextBtn").style.display = "none";
    } else {
        document.getElementById("nextBtn").style.display = "inline";
    }

    // ... and run a function that displays the correct step indicator:
    fixStepIndicator(n)
}

function nextPrev(n) {
    // This function will figure out which tab to display
    const x = document.getElementsByClassName("tab");

    // Exit the function if any field in the current tab is invalid:
    if (n == 1 && !validateForm()) {
        return false;
    }

    // Hide the current tab:
    x[currentTab].style.display = "none";
    // Increase or decrease the current tab by 1:
    currentTab = currentTab + n;

    // Otherwise, display the correct tab:
    showTab(currentTab);
}

function fixStepIndicator(n) {
    // This function removes the "active" class of all steps...
    const x = document.getElementsByClassName("step");

    for (let i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }

    //... and adds the "active" class to the current step:
    x[n].className += " active";
}

const souvenirArray = []
for (let i = 0; i < 10; i++) {
    souvenirArray.push(-1);
};

function validateForm() {
    // This function deals with validation of the form fields
    let valid = true;

    const inputArray = [
        "member-detail_date-",
        "member-cso-",
        "member-name-",
        "member-phone-",
        "member-address-",
        "member-url_drive-",
        "member-souvenir-",
    ];

    inputArray.forEach(function (currentValue) {
        const inputBeingChecked = document.getElementById(currentValue + currentTab);

        if (!inputBeingChecked.checkValidity()) {
            addOrRemoveInvalid(inputBeingChecked, "add");
            valid = false;
        } else {
            addOrRemoveInvalid(inputBeingChecked, "remove");
        }
    });

    const souvenirInput = document.getElementById("member-souvenir-" + currentTab);
    const souvenirValue = parseInt(souvenirInput.value, 10);
    if (souvenirValue) {
        souvenirArray[currentTab] = souvenirValue;
        const findDuplicate = souvenirArray.filter(function (currentValue) {
            return currentValue === souvenirValue;
        });

        // if (findDuplicate.length > 2) {
        //     addOrRemoveInvalid(souvenirInput, "add");
        //     valid = false;
        // } else {
        //     addOrRemoveInvalid(souvenirInput, "remove");
        // }
    }

    // return the valid status
    return valid;
}

function addOrRemoveInvalid(element, command) {
    if (command === "add" && !element.className.includes("invalid")) {
        element.classList.add("invalid");
    } else if (command === "remove" && element.className.includes("invalid")) {
        element.classList.remove("invalid");
    }
}
</script>
<script>
    $(document).ready(function() {
        $(".select2").select2({
            theme: "bootstrap4",
        });
    });
    $(document).on('change', '#branch', function() {
        var branch = $("#branch").val();
        if (branch) {
            $("#submission_video_photo_error").html("");
            $("#submission_video_photo_success").html("");
            $.get( '{{ route("check_submission_video_photo_branch") }}', { branch })
            .done(function( result ) {
                if (result['status'] == 'success'){      
                    $("#submission_video_photo_success").html("New Submission Video Photo");
                    $("#refrensiForm").show();
                } else if(result['exists']) {
                    $("#submission_video_photo_error").html(result['exists']);   
                    $("#refrensiForm").hide();
                } else {
                    console.log(result);
                    alert('Error!');
                }
            })
            .fail(function( result ) {
                var err = JSON.parse(result.responseText)
                alert(err.message);
                $("#fr_data").hide();
            });
        } 
    });
</script>
@endsection
