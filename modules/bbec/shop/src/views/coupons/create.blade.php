@extends('layouts.app')

@section('content')
<h1>Create Coupons</h1>
<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li><a href="/coupons">Coupons</a></li>
    <li class="active">Create</li>
</ol>
<div class="row">
    <div class="col-md-6">
        <form method="post" action="/coupons">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="value">Coupon Value:</label>
                <input type="text" name="value" id="value" value=""  class="form-control" required>
            </div>
            <div class="form-group">
                <label for="quantity">Bulk Create:</label>
                <input type="text" name="quantity" id="quantity" value="" class="form-control" required>
            </div>
            <button class="btn btn-primary">Create</button>
        </form>
    </div>
</div>
@endsection