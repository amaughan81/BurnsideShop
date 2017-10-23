@extends('layouts.app')

@section('content')
    <h1>Order </h1>
    <ol class="breadcrumb">
        <li><a href="/">Home</a></li>
        <li><a href="/orders">Orders</a></li>
        <li class="active">Order #{{ $order->id }}</li>
    </ol>

    <div class="row">
        <div class="col-md-8">
            <h3>Order Details</h3>
            <p>Order Placed: {{ $order->created_at->format("d F Y") }}</p>
            <p>Order Number: #{{ $order->id }}</p>
            <p>Order Total: {{ $order->amount }} BM</p>
            <h3>Customer Details</h3>
            <p>Name: {{ $order->user->forename.' '.$order->user->surname }}</p>
            <p>Form: {{ $order->user->student->year_group.$order->user->student->form }}</p>
            <p>Contact: {{ $order->user->google->username }}@burnsidecollege.org.uk</p>
        </div>
        <div class="col-md-4">
            @if(!isStudent())
            <div class="panel panel-default" id="order-status-panel">
                <div class="panel-heading">Order Status</div>
                <div class="panel-body">
                    <form action="/orders/{{ $order->id }}" method="post" class="form-inline complete-order-frm">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <button class="btn btn-success btn-block">
                            Complete Order
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">Items Ordered</div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th class="text-right">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderProducts as $orderProduct)
                <tr>
                    <td>{{ $orderProduct->product->name }}</td>
                    <td>{{ $orderProduct->quantity }}</td>
                    <td class="text-right">{{ ($orderProduct->quantity * $orderProduct->product->price) }} BM</td>
                </tr>
                @foreach($order->coupons as $coupon)
                <tr>
                    <td colspan="2" class="text-right">Coupon {{ $coupon->code }}</td>
                    <td colspan="2" class="text-right">{{ $coupon->coupon->value }} BM</td>
                </tr>
                @endforeach
                @endforeach
                <tr>
                    <th colspan="2" class="text-right">Total:</th>
                    <th class="text-right">{{ $order->amount }} BM</th>
                </tr>
            </tbody>
        </table>
    </div>

@endsection