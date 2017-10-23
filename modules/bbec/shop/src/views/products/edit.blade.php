@extends('layouts.app')

@section('content')
<h1>Update Product</h1>

<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li><a href="/products">Products</a></li>
    <li class="active">Update Product</li>
</ol>

@include('flash::message')

<div class="row">
    <div class="col-md-6">
        <form action="/products/{{ $product->id }}" method="post" enctype="multipart/form-data">

            {{ method_field('PATCH') }}

            @include('shop::products._form')

            <hr>
            <div class="form-group">
                <button class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
    <div class="col-md-6">
        <h4>Photos</h4>
        <div id="product-photo-container">
        @foreach($product->photos->chunk(3) as $chunk)
            <div class="row">
                @foreach($chunk as $photo)
                    <div class="col-md-4 col-xs-4">
                        <div class="thumbnail photo-thumbnail" id="photo_{{ $photo->id }}">
                            <a
                                class="btn btn-danger btn-xs del-photo-btn"
                                data-photo-id="{{ $photo->id }}"
                                data-toggle="tooltip"
                                title="Delete Photo"
                            >
                                <span class="glyphicon-trash glyphicon"></span>
                            </a>
                            <a href="/images/products/{{ $photo->filename }}" data-lity>
                                <img src="/{{ $photo->thumbnail_path }}" alt="{{ $photo->filename }}">
                            </a>

                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
        </div>
        <form action="/products/{{ $product->slug }}/photos"
              class="dropzone"
              method="post"
              id="productPhotoDropzone">
            {{ csrf_field() }}
            <div class="fallback">
                <input name="photo" type="file" multiple />
            </div>
        </form>
    </div>
</div>
@endsection

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.1.1/min/dropzone.min.css" rel="stylesheet">
<link href="/css/lity.min.css" rel="stylesheet">
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.1.1/min/dropzone.min.js" type="text/javascript">
</script>
<script>
    Dropzone.options.productPhotoDropzone = {
        paramName: 'photo',
        maxFileSize: 3,
        acceptedFiles: '.jpg, .jpeg, .png, .bmp',
        success: function(file, data) {
            var html = '<div class="col-md-4 col-xs-4">';
            html += '<div class="thumbnail photo-thumbnail" id="photo_'+data.id+'">';
            html += '<a href="/'+data.path+'" data-lity><img src="/'+data.thumbnail_path+'" alt="'+data.filename+'"></a>';
            html += '<a class="btn btn-danger btn-xs del-photo-btn" data-photo-id="'+data.id+'">';
            html += '<span class="glyphicon glyphicon-trash"></span>';
            html += '</a>';
            html += '</div>';
            html += '</div>';

            var container = $("#product-photo-container");

            if($("#product-photo-container .row").length == 0) {
                html = '<div class="row">'+html+'</div>';
            } else {
                container = $("#product-photo-container .row").last();
                if(container.children().length == 3 ) {
                    $("#product-photo-container").append('<div class="row"></div>');
                    container = $("#product-photo-container .row").last();
                }
            }

            container.append(html);
        }
    }
</script>
<script src="/js/lity.min.js" type="text/javascript"></script>
@endsection