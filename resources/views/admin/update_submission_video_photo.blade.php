<?php
$menu_item_page = "submission_video_photo";
?>
@extends('admin.layouts.template')

@section('style')
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
                Edit Submission Video & Photo
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
                        Edit Submission Video & Photo
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
                            action="{{ route("update_submission_video_photo") }}">
                            @csrf
                            <input type="hidden"
                                name="id"
                                value="{{ $submissionVP->id }}" />
                            <div class="form-group">
                                <label>Type</label>
                                <input type="text"
                                    readonly
                                    disabled
                                    value="Testimonial Video & Photo" />
                            </div>
                            <div class="form-group">
                                <label id="submission_date" for="submission_date">
                                    Submission Date
                                </label>
                                <input id="submission_date"
                                    type="date"
                                    class="form-control"
                                    name="submission_date"
                                    required
                                    value="{{ $submissionVP->submission_date }}"/>
                                <div class="validation"></div>
                            </div
                            ><div class="form-group">
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
                                        <option value="{{ $branch['id'] }}"
                                            @If($branch['id'] == $submissionVP->branch_id) selected @endif>
                                            {{ $branch['code'] }} - {{ $branch['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="validation"></div>
                                <small id="submission_video_photo_error" style="color: red"></small>
                                <small id="submission_video_photo_success" style="color: green"></small>
                            </div>

                            <div id="refrensiForm">
                                <br>
                                <br>
                                <div class="form-group">
                                    <button id="addDeliveryOrder"
                                        type="submit"
                                        class="btn btn-gradient-primary mr-2">
                                        Save
                                    </button>
                                    <button class="btn btn-light">Cancel</button>
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
<script>
    $(document).on('change', '#branch', function() {
        var id = "{{ $submissionVP->id }}";
        var branch = $("#branch").val();
        if (branch) {
            $("#submission_video_photo_error").html("");
            $("#submission_video_photo_success").html("");
            $("#refrensiForm").hide();
            $.get( '{{ route("check_submission_video_photo_branch") }}', { branch, id })
            .done(function( result ) {
                if (result['status'] == 'success'){      
                    $("#submission_video_photo_success").html("New Submission Video & Photo");
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
