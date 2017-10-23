@extends('layouts.app')

@section('content')
<h1>Categories</h1>
<div class="text-right">
    <a class="btn btn-default" href="/categories/create">
        <span class="glyphicon glyphicon-plus"></span>
        New Category
    </a>
</div>

    @if($categories->count() > 0)
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th>Category Name</th>
            </tr>
        </thead>
        <tbody>
        @foreach($categories as $category)
            <tr id="cat_{{ $category->id }}">
                <td>
                    <form id="del_cat_{{ $category->id }}_form" class="form-inline">
                        <a class="btn btn-xs btn-warning" href="/categories/{{ $category->id }}/edit">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </a>

                        <a class="btn btn-danger btn-xs del-cat-btn" data-cat-del="{{ $category->id }}">
                            <span class="glyphicon glyphicon-trash"></span>
                        </a>
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                    </form>
                </td>
                <td>{{ $category->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif
@endsection