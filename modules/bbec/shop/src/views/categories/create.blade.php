@extends('layouts.app')

@section('content')
    <h1>Create Category</h1>
    <form action="/categories" method="post">
        {{ csrf_field() }}

        @include('vendor.bbec.categories._form')

        <div class="form-group">
            <button class="btn btn-primary">Create</button>
        </div>
    </form>
@endsection
