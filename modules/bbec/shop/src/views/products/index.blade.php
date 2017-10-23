@extends('layouts.app')

@section('content')
    <h1>{{ $title }}</h1>

    <ol class="breadcrumb">
        <li><a href="/">Home</a></li>
        <li class="active">Products</li>
    </ol>

    @include('flash::message')

    <div class="text-right">
        <a class="btn btn-default" href="/products/create">
            <span class="glyphicon glyphicon-plus"></span>
            New Product
        </a>
    </div>
    <h4>{{ $products->count() }} Products</h4>
    @if($products->count() > 0)
    <table class="table table-striped" id="product-list-tbl">
        <thead>
            <tr>
                <th></th>
                <th></th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
        <tr id="prod_{{ $product->id }}">
            <td>
                <form id="del_prod_{{ $product->id }}_form" class="form-inline">
                    <a class="btn btn-xs btn-warning" href="/products/{{ $product->id }}/edit">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </a>

                    <a class="btn btn-danger btn-xs del-prod-btn" data-prod-del="{{ $product->id }}">
                        <span class="glyphicon glyphicon-trash"></span>
                    </a>
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                </form>
            </td>
            <td>
                @if($product->photos->count() > 0)
                            <img
                                    src="/{{ $product->photos->first()->thumbnail_path }}"
                                    alt="{{ $product->photos->first()->filename }}"
                                    class="img-responsive thumbnail preview-img"
                            >
                @endif
            </td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->quantity }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    @endif
@endsection