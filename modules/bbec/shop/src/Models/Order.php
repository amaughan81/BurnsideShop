<?php

namespace bbec\Shop\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['status'];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderProducts()
    {
        return $this->hasMany(\bbec\Shop\Models\OrderProduct::class);
    }

    /**
     * Query scope to return orders of a given status
     *
     * @param $query
     * @param $status
     * @return mixed
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Set the order status to complete
     */
    public function complete()
    {
        $this->status = 1;
        $this->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coupons()
    {
        return $this->hasMany(\bbec\Shop\Models\CouponOrder::class);
    }

    /**
     * Toggle the status of the order
     */
    public function toggleStatus() {
        $this->status = !$this->status;
        $this->save();
    }
}
