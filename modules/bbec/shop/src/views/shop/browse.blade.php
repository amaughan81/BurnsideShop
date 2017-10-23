@extends('layouts.app')

@section('content')
<h1>Shop</h1>

<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li class="active">Products</li>
</ol>

<div class="row">
    <div class="col-md-9">
        @foreach($products->chunk(3) as $chunk)
            <div class="row">
                @foreach($chunk as $product)
                    <div class="col-md-4">
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
    </div>
    <div class="col-md-3">
        <div class="list-group">
            <a href="/browse" class="list-group-item">All Categories</a>
            @foreach($categories as $category)
                <a
                    class="list-group-item @if($category->slug == $slug) active @endif"
                    href="/browse/{{ $category->slug }}"
                >
                    {{$category->name}}
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection