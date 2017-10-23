@extends('layouts.app')

@section('content')
    <h1>{{ $title }}</h1>

    <ol class="breadcrumb">
        <li><a href="/">Home</a></li>
        <li class="active">Basket</li>
    </ol>

    @include('flash::message')

    @if(count($cart->getItems()) > 0)
    <table class="table" id="shopping-cart-tbl">
        <thead>
            <tr>
                <th></th>
                <th>Product</th>
                <th>Quantity</th>
                <th class="text-right">Price</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($cart->getItems() as $item)
            <tr>
                <td class="table-image">
                    @if($item['item']->photos->count() > 0)
                        <img
                            src="/{{ $item['item']->photos->first()->thumbnail_path }}"
                            alt="{{ $item['item']->photos->first()->filename }}"
                            class="img-responsive thumbnail preview-img"
                        >
                    @endif
                </td>
                <td>{{ $item['item']->name }}</td>
                <td>
                    <select
                        type="text"
                        name="quantity"
                        class="adjust-quantity form-control form-inline"
                        data-product-id="{{ $item['item']->id }}"
                    >
                        @for($i=1;$i<4;$i++)
                        <option value="{{ $i }}"@if($i == $item['qty']) selected @endif>{{ $i }}</option>
                        @endfor
                    </select>
                </td>
                <td class="text-right">{{ $item['price'] }} BM</td>
                <td>
                    <form action="/cart/{{ $item['item']->id }}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button class="btn btn-xs btn-danger">
                            <span class="glyphicon glyphicon-trash"></span> Remove
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
            <tr>
                <td colspan="3"></td>
                <td class="text-right">Total: {{ $cart->totalPrice }} BM</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @endif
    <div class="row">
        <div class="col-md-6">
            <a class="btn btn-primary" href="/browse">Continue Shopping</a>
            @if(count($cart->getItems()) > 0)
            <a class="btn btn-success" href="/cart/checkout">Proceed to Checkout</a>
            @endif
        </div>
        <div class="col-md-6 text-right">
            @if(count($cart->getItems()) > 0)
            <form action="/cart/empty" method="post">
                {{ csrf_field() }}
                {{ method_field('delete') }}
                <button class="btn btn-danger">Empty Basket</button>
            </form>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function() {
            $(document).on('change', '.adjust-quantity', function() {
                var qtyField = $(this);
                $.ajax({
                    url: '/cart/'+$(this).data('product-id'),
                    type: 'PATCH',
                    dataType: 'json',
                    data: {
                        'quantity': $(this).val(),
                    },
                    success: function(data) {
                        if(data.result) {
                            window.location.href = "/cart";
                        } else {
                            qtyField.val(data.originalQty);
                            alert(data.message);
                        }
                    }
                });
            });
        });
    </script>
@endsection