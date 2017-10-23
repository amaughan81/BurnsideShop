<?php

namespace bbec\Shop\Models;

use bbec\Shop\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * Protect against mass assignment
     * @var array
     */
    protected $fillable = ['name'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($category) {
            $category->slug = str_slug($category->name);

            $latestSlug =
                static::whereRaw("slug RLIKE '^{$category->slug}(-[0-9]*)?$'")
                    ->latest('id')
                    ->pluck('slug')
                    ->first();

            if($latestSlug) {
                $pieces = explode('-', $latestSlug);
                $number = intval(end($pieces));
                $category->slug = $category->slug . "-" . ($number + 1);
            }
        });
    }

    /**
     * A category has many products
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * @param $slug
     * @return mixed
     */
    public static function slug($slug) {
        return static::where('slug', $slug)->firstOrFail();
    }
}
