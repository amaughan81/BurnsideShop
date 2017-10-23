@extends('layouts.app')

@section('content')

    @include('flash::message')

    <div class="jumbotron text-center">
        <h2>Order Complete</h2>
        <h3>Order Number:</h3>
        <p><a class="btn btn-primary" href="/browse">Continue Shopping</a></p>
    </div>

@endsection