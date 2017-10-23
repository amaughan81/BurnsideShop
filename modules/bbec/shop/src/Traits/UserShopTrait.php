<?php

namespace bbec\Shop\Traits;

use bbec\Shop\Models\Merit;
use bbec\Shop\Models\Order;

trait UserShopTrait {

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function merits()
    {
        return $this->hasOne(Merit::class);
    }
}