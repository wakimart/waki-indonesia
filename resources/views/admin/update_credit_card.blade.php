<?php
$menu_item_page = "bank";
?>
@extends('admin.layouts.template')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    <link rel="stylesheet" href="{{ asset("css/lib/select2/select2-bootstrap4.min.css") }}" />
    <style>
        select {
            color: black !important
        }
    </style>
@endsection
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Edit Credit Card</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse" href="#bank-dd" aria-expanded="false" aria-controls="bank-dd">
                            Bank
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Credit Card
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form class="forms-sample" method="POST" action="{{ route('update_credit_card') }}">
                            @csrf
                            <input type="hidden" name="idCreditCard" value="{{$creditCard->id}}">
                            <div class="form-group">
                                <label for="">Code</label>
                                <input type="text" class="form-control @if($errors->has('code')) is-invalid @endif" name="code" placeholder="credit card code" value="{{$creditCard->code}}" required/>
                                @if($errors->has('code'))
                                    <span class="error">{{ $errors->first('code') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" name="name" placeholder="credit card name" value="{{$creditCard->name}}" required/>
                                @if($errors->has('name'))
                                    <span class="error">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Bank Account</label>
                                <select class="form-control @if($errors->has('bank_account_id')) is-invalid @endif" name="bank_account_id" id="selectBankAccount" required>
                                    <option value="" selected disabled>-- select bank account first --</option>
                                    @foreach($bankAccounts as $bankAccount)
                                        <option value="{{$bankAccount->id}}">{{$bankAccount->code}} - {{$bankAccount->name}} ( {{$bankAccount->account_number}} )</option>
                                    @endforeach
                                </select>
                                @if($errors->has('bank_account_id'))
                                    <span class="error">{{ $errors->first('bank_account_id') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Cicilan</label>
                                <input type="number" class="form-control @if($errors->has('cicilan')) is-invalid @endif" name="cicilan" min=0 value="{{$creditCard->cicilan}}" required/>
                                @if($errors->has('cicilan'))
                                    <span class="error">{{ $errors->first('cicilan') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Charge Percentage Sales</label>
                                <input type="number" min=0 step=".01" class="form-control @if($errors->has('charge_percentage_sales')) is-invalid @endif" name="charge_percentage_sales" value="{{ $creditCard->charge_percentage_sales }}" required>
                                @if($errors->has('charge_percentage_sales'))
                                    <span class="error">{{ $errors->first('charge_percentage_sales') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Charge Percentage Company</label>
                                <input type="number" min=0 step=".01" class="form-control @if($errors->has('charge_percentage_company')) is-invalid @endif" name="charge_percentage_company" value="{{ $creditCard->charge_percentage_company }}" required>
                                @if($errors->has('charge_percentage_company'))
                                    <span class="error">{{ $errors->first('charge_percentage_company') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Estimate Transfer</label>
                                <input type="number" min=0 class="form-control @if($errors->has('estimate_transfer')) is-invalid @endif" name="estimate_transfer" value="{{ $creditCard->estimate_transfer }}" required>
                                @if($errors->has('estimate_transfer'))
                                    <span class="error">{{ $errors->first('estimate_transfer') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea class="form-control" name="description" id="" cols="30" rows="10">{{$creditCard->description}}</textarea>
                                @if($errors->has('description'))
                                    <span class="error">{{ $errors->first('description') }}</span>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-gradient-primary mr-2">Update</button>
                            <button class="btn btn-light" type="button" onclick='location.href="{{ route('list_bank_account') }}"'>Cancel</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
<script>
    $(document).ready(function(){
        $("#selectBankAccount").select2().select2('val', '{{$creditCard->bank_account_id}}')
        $("#selectBankAccount").select2({
            theme: "bootstrap4",
            placeholder: "-- select bank account first --"
        });
    })
</script>
@endsection