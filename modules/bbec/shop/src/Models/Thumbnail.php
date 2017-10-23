<?php

namespace bbec\Shop\Models;

use Intervention\Image\Facades\Image;

class Thumbnail {
    public function make($src, $dest)
    {
        Image::make($src)
            ->fit(200)
            ->save($dest);
    }
}