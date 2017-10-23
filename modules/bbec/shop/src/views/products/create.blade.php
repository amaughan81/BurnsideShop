@extends('layouts.app')

@section('content')
<h1>New Product</h1>



    <form action="/products" method="post" enctype="multipart/form-data">

        <div class="col-md-6">
        @include('shop::products._form')

        <hr>
        <div class="form-group">
            <button class="btn btn-primary">Create</button>
        </div>
        </div>
    </form>

</div>

@endsection