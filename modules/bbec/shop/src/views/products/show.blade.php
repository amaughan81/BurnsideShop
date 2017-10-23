@extends('layouts.app')

@section('content')
<h1>{{ $product->name }}</h1>

<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li><a href="/browse">Products</a></li>
    <li class="active">{{ $product->name }}</li>
</ol>

@if($product->quantity <= 0)
<div class="alert alert-warning">
    <span class="glyphicon glyphicon-warning-sign"></span> This product is out of stock.  Please check back soon.
</div>
@endif

<div class="row">
    <div class="col-md-4">
        @if($product->photos->count() > 0)
        <div class="thumbnail">
            <a href="/{{ $product->photos->first()->path }}" data-lity>
                <img
                    src="/{{ $product->photos->first()->path }}"
                    alt="{{ $product->photos->first()->filename }}"
                    class="img-responsive"
                >
            </a>
        </div>
        @if($product->photos->count() > 1)
        @foreach($product->photos->chunk(4) as $chunk)
        <div class="row">
            @foreach($chunk as $photo)
            <div class="col-md-3 col-xs-3">
                <div class="thumbnail photo-thumbnail" id="photo_{{ $photo->id }}">
                    <a href="/images/products/{{ $photo->filename }}" data-lity>
                        <img src="/{{ $photo->thumbnail_path }}" alt="{{ $photo->filename }}">
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
        @endif
        @else
        <img src="/images/placeholder.jpg" alt="{{ $product->name }}">
        @endif
    </div>
    <div class="col-md-8" id="product-description">
        <h3>{{ $product->price }} BM</h3>
        <p>
            @if($product->quantity > 0)
            <form action="/cart" method="post" class="form-inline">
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $product->id }}">
                <button class="btn btn-success">Add to Cart</button>
            </form>
            @endif
        </p>
        <p>{!! nl2br($product->description) !!}</p>
    </div>
</div>
<hr>
<h4>You may also like:</h4>
<div class="row">
    @foreach($similar as $similarProduct)
        <div class="col-md-3">
            <div class="thumbnail text-center">
                <div class="caption">
                    <a href="/products/{{ $similarProduct->slug }}">
                        @if($similarProduct->photos->count() > 0)
                            <img
                                src="/{{ $similarProduct->photos->first()->thumbnail_path }}"
                                alt="/{{ $similarProduct->photos->first()->filename }}"
                            >
                        @else
                            <img src="/images/placeholder.jpg" alt="{{ $similarProduct->name }}">
                        @endif
                    </a>
                    <a href="/products/{{ $similarProduct->slug }}">
                        <h4>{{ $similarProduct->name }}</h4>
                        <p>{{ $similarProduct->price }}BM</p>
                    </a>
                </div>
            </div>

        </div>
    @endforeach
</div>
@endsection

@section('css')
<link href="/css/lity.min.css" rel="stylesheet">
@endsection
@section('scripts')
<script src="/js/lity.min.js" type="text/javascript"></script>
@endsection