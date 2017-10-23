@extends('layouts.app')

@section('content')
<h1>Checkout</h1>
<ol class="breadcrumb">
    <li><a href="/">Home</a></li>
    <li><a href="/cart">Basket</a></li>
    <li class="active">Checkout</li>
</ol>
    @if(!isStudent() && $user_id == 0)
    <div class="alert alert-warning">
        <span class="glyphicon glyphicon-warning-sign"></span> You are not logged in as a student.  If you would like to purchase on behalf of a student, please select them for the list below
    </div>
    <div class="form-group">
        <select id="select-student" class="form-control">
            <option value="">Select Student</option>
            @foreach($students as $student)
            <option
                @if($student['id'] == $user_id)
                selected
                @endif
                value="{{ $student['id'] }}"
            >
                {{ strtoupper($student['surname']) }}, {{ $student['forename'] }}
            </option>
            @endforeach
        </select>
    </div>
    @if(isManager())
    <p>
        <a
                class="btn btn-primary"
                href="/cart/checkout/{{ auth()->user()->id }}"
        >
            Continue as Myself
        </a>
    </p>
    @endif
    @else

        @if($user->merits->balance() < $cart->totalPrice)
            <div class="alert alert-warning">
                <span class="glyphicon glyphicon-warning-sign"></span> You do not have enough merits to complete your order
            </div>
        @endif
        @if($locked)
            <div class="alert alert-warning">
                <span class="glyphicon glyphicon-warning-sign"></span> You have made too many purchases today.  Please try again tomorrow.
            </div>
        @endif
        <div class="row">
            <div class="col-md-9">
                <div class="checkout-row clearfix">
                    <div class="col-md-3 checkout-title">1 Your Details</div>
                    <div class="col-md-9">
                        {{ $user->forename }} {{ $user->surname }}
                        ({{ $user->student->year_group.$user->student->form }})
                    </div>
                </div>
                <div class="checkout-row clearfix">
                    <div class="col-md-3 checkout-title">2 Payment</div>
                    <div class="col-md-9">
                        <div>{{ $user->merits->balance() }} BM</div>
                        <div id="applied-coupons">
                            <h5>Coupons:</h5>
                            <table class="table">
                                @foreach($cart->coupons as $code=>$value)
                                <tr id="cc_{{ $code }}">
                                    <th>{{ $code }}</th>
                                    <td>{{ $value }} BM</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-xs remove-coupon-btn" data-coupon-code="{{ $code }}">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                        <form class="form-inline">
                            <div class="form-group">
                                <input type="text" class="form-control" name="claimCode" id="claimCode" placeholder="Enter Code">
                            </div>
                            <button type="button" class="btn btn-default" id="applyVoucherCodeBtn">Apply</button>
                        </form>
                    </div>
                </div>
                <div class="clearfix">
                    <div class="col-md-3 checkout-title">3 Review items</div>
                    <div class="col-md-9">
                        @foreach($cart->getItems() as $item)
                            <div class="media">
                                <div class="media-left">
                                    @if($item['item']->photos->count() > 0)
                                        <img
                                            src="/{{ $item['item']->photos->first()->thumbnail_path }}"
                                            alt="{{ $item['item']->photos->first()->filename }}"
                                            class="thumbnail preview-img"
                                        >
                                    @endif
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">{{ $item['item']->name }}</h4>
                                    <div>
                                        <div>Price: {{ $item['item']->price }}</div>
                                        <div>Quantity: {{ $item['qty'] }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form action="/orders" method="post">
                            <p class="text-center">
                                <button
                                    class="btn btn-warning btn-block"
                                    @if($user->merits->balance() < $cart->totalPrice || $locked)
                                    disabled
                                    @endif
                                >
                                    Buy Now
                                </button>
                            </p>
                            @if($user_id > 0)
                            <input type="hidden" name="user_id" id="user_id" value="{{ $user_id }}">
                            @endif
                            {{ csrf_field() }}
                        </form>
                        <hr>
                        <h4>Order Summary</h4>
                        <div>
                            <p class="clearfix">
                                <span class="pull-left">Total Quantity:</span>
                                <span class="pull-right">{{ $cart->totalQty }}</span>
                            </p>
                            <p class="clearfix">
                                <span class="pull-left">Discounts:</span>
                                <span class="pull-right" id="discount-value">{{ $cart->discounts() }} BM</span>
                            </p>
                            <p class="clearfix">
                                <span class="pull-left">Total Price:</span>
                                <span class="pull-right">{{ $cart->totalPrice }} BM</span>
                            </p>
                            <p class="clearfix">
                                <span class="pull-left">VAT:</span>
                                <span class="pull-right">0</span>
                            </p>
                        </div>
                        <hr>
                        <div class="order-summary-grand-total clearfix">
                            <span class="pull-left">Order Total:</span>
                            <span class="pull-right" id="grand-total">{{ ($cart->totalPrice - $cart->discounts()) }} BM</span>
                        </div>

                    </div>
                    <div class="panel-footer">
                        <span class="small">Please allow 24 hours for your order to be processed.</span>
                    </div>
                </div>

            </div>
        </div>
    @endif
@endsection

@section('scripts')
    @if(!isStudent())
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#select-student').select2({
                placeholder: 'Select an option'
            });

            $(document).on('change', '#select-student', function() {
                location.href = "/cart/checkout/"+$(this).val();
            })
        });
    </script>
    @endif
@endsection

@section('css')
    @if(!isStudent())
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
    @endif
@endsection
