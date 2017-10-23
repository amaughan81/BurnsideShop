@extends('layouts.app')

@section('content')
<h1>Edit Category</h1>
<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li><a href="/categories">Categories</a></li>
    <li class="active">Update Category</li>
</ol>
<form action="/categories/{{ $category->id }}" method="post">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
    @include('shop::categories._form')

    <div class="form-group">
        <button class="btn btn-primary">Save</button>
    </div>
</form>
@endsection