@extends('layouts.app')

@section('content')
<div class="jumbotron text-center">
    <h2>Welcome to the Burnside Merit Shop</h2>
    <p>This is your one stop shop for purchasing stationery and accessing rewards.</p>
    <p>
        @if(!isLoggedIn())
        <a class="btn btn-lg btn-primary" href="/login">Login</a>
        @else
        <a class="btn btn-lg btn-primary" href="/my-account">My Account</a>
        @endif
        <a class="btn btn-lg btn-success" href="/browse">Shop</a>
    </p>
</div>
@foreach($products->chunk(4) as $chunk)
    <div class="row">
        @foreach($chunk as $product)
        <div class="col-md-3">
            <div class="thumbnail text-center">
                <div class="caption">
                    <a href="/products/{{ $product->slug }}">
                        @if($product->photos->count() > 0)
                        <img
                            src="/{{ $product->photos->first()->thumbnail_path }}"
                            alt="/{{ $product->photos->first()->filename }}"
                        >
                        @else
                        <img src="/images/placeholder.jpg" alt="{{ $product->name }}">
                        @endif
                    </a>
                    <a href="/products/{{ $product->slug }}">
                        <h4>{{ $product->name }}</h4>
                        <p>{{ $product->price }}BM</p>
                    </a>
                </div>
            </div>

        </div>
        @endforeach
    </div>
@endforeach
@endsection