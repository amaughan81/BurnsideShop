@if($orders->count() > 0)
    <table class="table table-striped" id="orders-tbl">
        <thead>
        <tr>
            <th>Order No.</th>
            <th>Total Products</th>
            <th>Total Price</th>
            <th>Order Date</th>
            <th>Status</th>
            <th></th>
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
                <td>
                    <form action="/orders/{{ $order->id }}" method="post" class="form-inline complete-order-frm">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                            <button
                                class="btn btn-success complete-order-btn btn-xs"
                                data-toggle="tooltip"
                                title="Complete Order"
                            >
                                <span class="glyphicon glyphicon-ok"></span>
                            </button>
                        <a
                            class="btn btn-danger delete-order-btn btn-xs"
                            type="button"
                            data-order-id="{{ $order->id }}"
                            data-toggle="tooltip"
                            title="Delete Order"
                        >
                            <span class="glyphicon glyphicon-trash"></span>
                        </a>
                    </form>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif

@section('scripts')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            $(document).on('click', '.delete-order-btn', function() { deleteOrder($(this).data('order-id')); });
            $('#orders-tbl').DataTable();
        });

        function deleteOrder(id) {
            var conf = confirm("Are you sure you wish to delete this order?\n\nAll products will be refunded.\n\nDo you wish to continue?")
            if(conf) {
                $.ajax({
                    url: '/orders/'+id,
                    dataType: 'json',
                    type: 'DELETE',
                    success: function(data) {
                        if(data.result) {
                            $("#order_"+id).remove();
                        }
                    }
                });
            }
        }
    </script>
@endsection
@section('css')
    <link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet">
@endsection