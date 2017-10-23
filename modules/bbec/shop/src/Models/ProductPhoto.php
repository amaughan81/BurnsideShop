<?php

namespace bbec\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductPhoto extends Model
{

    /**
     * protect against mass assignment
     *
     * @var array
     */
    protected $fillable = ['product_id', 'path','thumbnail_path','filename'];

    /**
     * A photo belongs to a product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(\bbec\Shop\Models\Product::class);
    }

    /**
     * Get the base directory of products
     *
     * @return string
     */
    public function baseDir()
    {
        return "images/products/";
    }

    /**
     * @param $name
     */
    public function setFilenameAttribute($filename)
    {
        $this->attributes['filename'] = $filename;
        $this->path = $this->baseDir().'/'.$filename;
        $this->thumbnail_path = $this->baseDir().'/tn-'.$filename;
    }

    public function delete() {

        \File::delete(
            $this->path,
            $this->thumbnail_path
        );
        return parent::delete();
    }

    
}
