@foreach($coupons->chunk(2) as $chunk)
    <div class="row">
        @foreach($chunk as $coupon)
            <div class="col-md-6">
                <div class="coupon-template text-right">
                    <p class="coupon-value">{{ $coupon->value }} BM</p>
                    <p class="coupon-code">Code: {{ $coupon->code }}</p>
                </div>
            </div>
        @endforeach
    </div>
@endforeach