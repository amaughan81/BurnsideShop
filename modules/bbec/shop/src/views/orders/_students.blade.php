@if($orders->count() > 0)
<table class="table table-striped">
    <thead>
        <tr>
            <th>Order No.</th>
            <th>Total Products</th>
            <th>Total Price</th>
            <th>Order Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr id="order_{{ $order->id }}">
            <td><a href="/orders/{{ $order->id }}">#{{ $order->id }}</a></td>
            <td>{{ $order->orderProducts->sum('quantity') }}</td>
            <td>{{ $order->amount }} BM</td>
            <td>{{ $order->created_at->format("d/m/Y H:i:s") }}</td>
            <td>@if($order->status == 0) Pending @else Complete @endif</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endif