@extends('layouts.app')

@section('content')
<h1>Coupons</h1>
<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li class="active">Coupons</li>
</ol>
<nav class="navbar navbar-default">
    <div class="text-right">
        <div class="container-fluid">
            @if($coupons->count() > 0)
            <a class="btn btn-default" href="/coupons/print/pdf">
                <span class="glyphicon glyphicon-print"></span>
            </a>
            @endif
            <a class="btn btn-default navbar-btn" href="/coupons/create">
                <span class="glyphicon glyphicon-plus"></span>
                Create Coupon
            </a>
        </div>
    </div>
</nav>

    @include('shop::coupons._couponsList');


@endsection

@section('scripts')
<script type="text/javascript">
    $(function() {
        $(document).on('click', '.delete-coupon-btn', function() {
            deleteCoupon($(this).data('coupon-id'));
        })
    });

    function deleteCoupon(id) {
        var conf = confirm("Are you sure you wish to delete this coupon?");
        if(conf) {
            $.ajax({
                url: '/coupons/'+id,
                dataType: 'json',
                type: 'DELETE',
                success: function(data) {
                    if(data.result) {
                        $("#coupon_"+id).remove();
                    }
                }
            });
        }
    }
</script>
@endsection

