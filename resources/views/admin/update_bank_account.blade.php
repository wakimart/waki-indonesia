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
            <h3 class="page-title">Edit Bank Account</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse" href="#bank-dd" aria-expanded="false" aria-controls="bank-dd">
                            Bank
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Bank Account
                    </li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form class="forms-sample" method="POST" action="{{ route('update_bank_account') }}">
                            @csrf
                            <input type="hidden" name="idBankAccount" value="{{$bankAccount->id}}">
                            <div class="form-group">
                                <label for="">Code</label>
                                <input type="text" class="form-control @if($errors->has('code')) is-invalid @endif" name="code" placeholder="bank account code" value="{{$bankAccount->code}}" required/>
                                @if($errors->has('code'))
                                    <span class="error">{{ $errors->first('code') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" name="name" placeholder="bank account name" value="{{$bankAccount->name}}" required/>
                                @if($errors->has('name'))
                                    <span class="error">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Account Number</label>
                                <input type="text" class="form-control @if($errors->has('account_number')) is-invalid @endif" name="account_number" placeholder="bank account number" value="{{$bankAccount->account_number}}" required/>
                                @if($errors->has('account_number'))
                                    <span class="error">{{ $errors->first('account_number') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Type</label>
                                <select class="form-control @if($errors->has('type')) is-invalid @endif" name="type" id="" required>
                                    <option value="" selected disabled>-- select type first --</option>
                                    <option value="debit" {{ $bankAccount->type == 'debit' ? 'selected' : '' }}>Debit</option>
                                    <option value="card" {{ $bankAccount->type == 'card' ? 'selected' : '' }}>Card</option>
                                </select>
                                @if($errors->has('type'))
                                    <span class="error">{{ $errors->first('type') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Charge Percentage</label>
                                <input type="number" min=0 step="any" class="form-control @if($errors->has('charge_percentage')) is-invalid @endif" name="charge_percentage" value="{{ $bankAccount->charge_percentage }}" required>
                                @if($errors->has('charge_percentage'))
                                    <span class="error">{{ $errors->first('charge_percentage') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Estimate Transfer</label>
                                <input type="number" min=0 class="form-control @if($errors->has('estimate_transfer')) is-invalid @endif" name="estimate_transfer" value="{{ $bankAccount->estimate_transfer }}" required>
                                @if($errors->has('estimate_transfer'))
                                    <span class="error">{{ $errors->first('estimate_transfer') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Bank</label>
                                <select class="form-control @if($errors->has('bank_id')) is-invalid @endif" name="bank_id" id="selectBank" required>
                                    <option value="" selected disabled>-- select bank first --</option>
                                    @foreach($banks as $bank)
                                        <option value="{{$bank->id}}">{{$bank->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Petty Cash Out Type</label>
                                <select class="form-control @if($errors->has('estimate_transfer')) is-invalid @endif" id="petty_cash_type" name="petty_cash_type" required>
                                    <option selected disabled value="">
                                        Pilihan Type
                                    </option>
                                    @foreach(\App\BankAccount::$PettyCashType as $pettyCashType)
                                    <option value="{{ $pettyCashType }}" 
										@if($pettyCashType == $bankAccount->petty_cash_type) selected @endif>
                                        {{ ucwords($pettyCashType) }}
                                    </option>
                                    @endforeach
                                </select>
                                @if($errors->has('petty_cash_type'))
                                    <span class="error">{{ $errors->first('petty_cash_type') }}</span>
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
        $("#selectBank").select2().select2('val', '{{$bankAccount->bank_id}}')
        $("#selectBank").select2({
            theme: "bootstrap4",
            placeholder: "-- select bank first --"
        })
    })
</script>
@endsection