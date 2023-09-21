<?php
$menu_item_page = "commstype";
$menu_item_second = "add_commstype";
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
            <h3 class="page-title">Add Commission Type</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#commstype-dd"
                            aria-expanded="false"
                            aria-controls="commstype-dd">
                            Commision Type
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Add Commision Type
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{route('store_commission_type')}}">
                            {{ csrf_field() }}
                              <div>
                                <div class="row no-gutters">
                                  <div class="form-group w-25">
                                    <label for="orderUpgrade" class="w-100">Upgrade ?</label>
                                    <div class="form-check-inline">
                                      <input class="form-check-input" type="radio" name="upgrade" id="orderUpgradeYes" value="1" required>
                                      <label class="form-check-label mb-0" for="orderUpgradeYes">Yes</label>
                                    </div>
                                    <div class="form-check-inline">
                                      <input class="form-check-input" type="radio" name="upgrade" id="orderUpgradeNo" value="0" required>
                                      <label class="form-check-label mb-0" for="orderUpgradeNo">No</label>
                                    </div>
                                  </div>

                                  <div class="form-group w-25">
                                    <label for="orderHadiah" class="w-100">Hadiah ?</label>
                                    <div class="form-check-inline">
                                      <input class="form-check-input" type="radio" name="prize" id="orderHadiahYes" value="1" required>
                                      <label class="form-check-label mb-0" for="orderHadiahYes">Yes</label>
                                    </div>
                                    <div class="form-check-inline">
                                      <input class="form-check-input" type="radio" name="prize" id="orderHadiahNo" value="0" required>
                                      <label class="form-check-label mb-0" for="orderHadiahNo">No</label>
                                    </div>
                                  </div>

                                  <div class="form-group w-25">
                                    <label for="orderTakeaway" class="w-100">Takeaway ?</label>
                                    <div class="form-check-inline">
                                      <input class="form-check-input" type="radio" name="takeaway" id="orderTakeawayYes" value="1" required>
                                      <label class="form-check-label mb-0" for="orderTakeawayYes">Yes</label>
                                    </div>
                                    <div class="form-check-inline">
                                      <input class="form-check-input" type="radio" name="takeaway" id="orderTakeawayNo" value="0" required>
                                      <label class="form-check-label mb-0" for="orderTakeawayNo">No</label>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Commision Type Name</label>
                                    <div class="form-group">
                                        <input type="text"
                                            class="form-control"
                                            name="name"
                                            placeholder="Commision Type Name" required/>
                                        <div class="validation"></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control"
                                        name="description"
                                        rows="5"
                                        data-msg="Mohon Isi Description"
                                        placeholder="Description" required></textarea>
                                    <div class="validation"></div>
                                </div>

                                <div class="form-group">
                                    <label for="">Nominal</label>
                                    <input type="text"
                                        class="form-control"
                                        name="nominal"
                                        autocomplete="off"
                                        data-type="currency"
                                        placeholder="Nominal" required/>
                                    <div class="validation"></div>
                                </div>

                                <div class="form-group">
                                    <label for="">Nominal Semangat</label>
                                    <input type="text"
                                        class="form-control"
                                        name="smgt_nominal"
                                        autocomplete="off"
                                        data-type="currency"
                                        placeholder="Semangat Nominal" required/>
                                    <div class="validation"></div>
                                </div>

                              </div>
                              <div class="row justify-content-center">
                                <button type="submit" class="btn btn-success mr-2">
                                    Submit
                                </button>
                              </div>
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
<script>
    $(document).ready(function() {
        $(document).on("input", 'input[data-type="currency"]', function() {
            $(this).val(numberWithCommas($(this).val()));
        });

        function numberWithCommas(x) {
            var parts = x.toString().split(".");
            parts[0] = parts[0].replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
        }
    })
</script>
@endsection
