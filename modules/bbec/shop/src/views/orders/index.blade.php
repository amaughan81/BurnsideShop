@extends('layouts.app')

@section('content')
<h1>Orders</h1>
<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li class="active">Orders</li>
</ol>
    @if(isAdmin() || isManager())
        @include('flash::message')
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-heading">
                    <a class="navbar-brand">@if($status == 0) Pending @else Complete @endif Orders</a>
                </div>
                <div class="navbar navbar-nav navbar-right">
                    <div class="btn-group" role="group" id="order-toogle-btns">
                        <a class="btn btn-default navbar-btn @if($status == 0) active @endif" href="/orders">
                            <span class="glyphicon glyphicon-hourglass"></span>
                            Pending
                        </a>
                        <a class="btn btn-default navbar-btn @if($status == 1) active @endif" href="/orders/status/1">
                            <span class="glyphicon glyphicon-ok"></span>
                            Complete
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    @include("shop::orders._admin")
    @elseif(isStudent())
    @include("shop::orders._students")
    @endif
@endsection