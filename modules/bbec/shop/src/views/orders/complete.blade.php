@extends('layouts.app')

@section('content')

    @include('flash::message')

    <div class="jumbotron text-center">
        <h2>Order Complete</h2>
        <h3>Order Number: #{{ $id }}</h3>
        <p>Your order has been completed.  An email has been sent to you as receipt of this order.  Please allow 24 hours before collecting your order.</p>
        <p><a class="btn btn-primary" href="/browse">Continue Shopping</a></p>
    </div>

@endsection