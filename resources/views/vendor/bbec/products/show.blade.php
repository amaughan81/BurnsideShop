@extends('layouts.app')

@section('content')
<h1>{{ $product->name }}</h1>
<div class="row">
    <div class="col-md-6">
        <img src="/images/placeholder.jpg" alt="{{ $product->name }}">
    </div>
    <div class="col-md-6">
        <h3>{{ $product->price }} BM</h3>
        <p>
            <form cart="">
                <button class="btn btn-success">Add to Cart</button>
            </form>
            <button class="btn btn-primary">Add to Wishlist</button>
        </p>
        <p>{{ $product->description }}</p>
    </div>
</div>
@endsection