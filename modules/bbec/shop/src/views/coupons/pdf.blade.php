@extends('layouts.pdf')

@section('content')
@include('shop::coupons._couponsList')
@endsection

@section('css')
    <style>
        @page { margin: 100px 30px; }
        body { padding: 0; color: #636b6f; font-family: Arial, Helvetica, sans-serif; margin:0 }
        .coupon-template { width: 330px; height: 210px; background: url('{{ asset("/images/discount_coupon_final.jpg") }}') no-repeat; border: 1px solid #DDD; margin-bottom:20px; padding:15px; font-weight:bold; }
        .coupon-value { font-size: 48px; margin-top:60px }
        .coupon-code { font-size: 18px; }
        .col-md-6 { float:left; width:50%; position: relative; }
        .text-right { text-align: right }
        .row { width: 100%; overflow: auto; }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .delete-coupon-btn { display: none; }
    </style>
@endsection