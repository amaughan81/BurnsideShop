<?php

namespace bbec\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class CouponOrder extends Model
{
    protected $fillable = ['code', 'order_id'];
    /**
     * Add a coupon to the order
     *
     * @param $code
     * @param $orderId
     * @return bool
     */
    public function add($code, $orderId)
    {
        $this->code = $code;
        $this->order_id = $orderId;
        return $this->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(\bbec\Shop\Models\Order::class);
    }

    public function coupon()
    {
        return $this->belongsTo(\bbec\Shop\Models\Coupon::class, 'code', 'code');
    }
}
