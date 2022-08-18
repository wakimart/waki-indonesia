<?php
$menu_item_page = "bank";
?>
@extends('admin.layouts.template')

@section('script')
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
                                    <option value="ewallet" {{ $bankAccount->type == 'ewallet' ? 'selected' : '' }}>E-wallet</option>
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
                                <select class="form-control @if($errors->has('bank_id')) is-invalid @endif" name="bank_id" id="" required>
                                    <option value="" selected disabled>-- select bank first --</option>
                                    @foreach($banks as $bank)
                                        <option value="{{$bank->id}}" {{ $bankAccount->bank_id == $bank->id ? 'selected' : '' }}>{{$bank->name}}</option>
                                    @endforeach
                                </select>
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