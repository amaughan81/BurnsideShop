<?php

namespace bbec\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(\bbec\Shop\Models\Product::class);
    }

    /**
     * Add a product to the order
     *
     * @param $orderId
     * @param $productId
     * @param $quantity
     * @return bool
     */
    public function add($orderId, $productId, $quantity)
    {
        $this->order_id = $orderId;
        $this->product_id = $productId;
        $this->quantity = $quantity;
        return $this->save();
    }

}
