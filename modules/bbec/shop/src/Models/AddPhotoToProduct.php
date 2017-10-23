<?php

namespace bbec\Shop\Models;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class AddPhotoToProduct {

    /**
     * @var Product
     */
    public $product;

    /**
     * @var UploadedFile
     */
    protected $file;

    protected $thumbnail;

    /**
     * AddPhotoToProduct constructor.
     * @param Product $product
     * @param UploadedFile $file
     * @param Thumbnail|null $thumbnail
     */
    public function __construct(Product $product, UploadedFile $file, Thumbnail $thumbnail = null)
    {
        $this->product = $product;
        $this->file = $file;
        $this->thumbnail = $thumbnail ? : new Thumbnail;
    }

    public function save()
    {
        $photo = $this->product->addPhoto($this->makePhoto());

        $this->file->move($photo->baseDir(), $photo->filename);

        $this->thumbnail->make($photo->path, $photo->thumbnail_path);

        return $photo;
    }

    protected function makePhoto()
    {
        return new ProductPhoto(['filename' => $this->makeFileName()]);
    }

    protected function makeFileName()
    {
        $name = sha1(
            time() . $this->file->getClientOriginalName()
        );
        $extension = $this->file->getClientOriginalExtension();
        return "{$name}.{$extension}";
    }
}