@foreach($coupons->chunk(2) as $chunk)
    <div class="row clearfix">
        @foreach($chunk as $coupon)
            <div class="col-md-6">
                <div class="coupon-template text-right" id="coupon_{{ $coupon->id }}">
                    <button type="button" class="btn btn-danger btn-xs delete-coupon-btn" data-coupon-id="{{ $coupon->id }}">
                        <span class="glyphicon glyphicon-remove"></span>
                    </button>
                    <p class="coupon-value">{{ $coupon->value }} BM</p>
                    <p class="coupon-code">Code: {{ $coupon->code }}</p>
                </div>
            </div>
        @endforeach
    </div>
@endforeach