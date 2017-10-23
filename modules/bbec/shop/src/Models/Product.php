<?php

namespace bbec\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * Set fillable fields to guard against mass assignment
     *
     * @var array
     */
    protected $fillable = ['category_id', 'name', 'description', 'price', 'quantity'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($product) {
            $product->slug = str_slug($product->name);

            $latestSlug =
                static::whereRaw("slug RLIKE '^{$product->slug}(-[0-9]*)?$'")
                    ->latest('id')
                    ->pluck('slug')
                    ->first();

            if($latestSlug) {
                $pieces = explode('-', $latestSlug);
                $number = intval(end($pieces));
                $product->slug = $product->slug . "-" . ($number + 1);
            }
        });
    }

    public function scopeSimilar($query, $slug, $category_id)
    {
       return  $query->where('slug', '!=', $slug)->where('category_id',$category_id);

    }

    /**
     * Create a safe random method
     *
     * @param int $limit
     * @return mixed
     */
    public  function scopeRandom($query, $limit=8)
    {
        $collection = $query->inRandomOrder()->get();

        if($collection->count() < $limit) {
            $limit = $collection->count();
        }
        return $collection->take($limit);
    }

    public function addPhoto(ProductPhoto $photo)
    {
        return $this->photos()->save($photo);
    }

    /**
     * A product has many photos
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany(\bbec\Shop\Models\ProductPhoto::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(\bbec\Shop\Models\Category::class);
    }

    /**
     * Adjust the stock level of a product
     * @param $quantity
     */
    public function quantity($quantity)
    {
        $quantity = (int)$quantity;

        $this->quantity += $quantity;
        $this->save();
    }

    /**
     * @param $slug
     * @return mixed
     */
    public static function slug($slug) {
        return static::where('slug', $slug)->firstOrFail();
    }
}
