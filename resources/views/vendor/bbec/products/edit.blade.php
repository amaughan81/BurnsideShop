@extends('layouts.app')

@section('content')
<h1>Update Product</h1>
<form action="/products/{{ $product->id }}" method="post" enctype="multipart/form-data">

    {{ method_field('PATCH') }}

    @include('shop::products._form')

    <hr>
    <div class="form-group">
        <button class="btn btn-primary">Save</button>
    </div>
</form>
@endsection