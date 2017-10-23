<?php

use bbec\Shop\Models\Product;
use bbec\Shop\Models\ProductPhoto;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Mockery as m;

class ProductPhotoTest extends \Tests\TestCase {

    use DatabaseTransactions;

    /** @test */
    public function a_photo_can_be_added_to_a_product()
    {
        $product = factory(Product::class)->create();

        $pp = new ProductPhoto();
        $photo = $pp->create([
            'product_id' => $product->id,
            'filename' => 'pic.jpg',
            'path' => 'assets/images/pic.jpg',
            'thumbnail_path' => 'assets/images/tn_pic.jpg'
        ]);

        $this->assertEquals($product->id, $photo->product_id);
    }

    /** @test */
    public function a_product_can_list_photos()
    {
        $product = factory(Product::class)->create();

        $pp = new ProductPhoto();
        $photo = $pp->create([
            'product_id' => $product->id,
            'filename' => 'pic.jpg',
            'path' => 'assets/images/',
            'thumbnail_path' => 'tn_pic.jpg',
        ]);
        $photo2 = $pp->create([
            'product_id' => $product->id,
            'filename' => 'pic.jpg',
            'path' => 'assets/images/',
            'thumbnail_path' => 'tn_pic.jpg',
        ]);

        $this->assertEquals(count($product->photos), 2);
    }

    /** @test */
    public function an_image_can_be_deleted()
    {
        $product = factory(Product::class)->create();

        $pp = new ProductPhoto();
        $photo = $pp->create([
            'product_id' => $product->id,
            'filename' => 'pic.jpg',
            'path' => 'assets/images/',
            'thumbnail_path' => 'tn_pic.jpg',
        ]);
        $photo2 = $pp->create([
            'product_id' => $product->id,
            'filename' => 'pic.jpg',
            'path' => 'assets/images/',
            'thumbnail_path' => 'tn_pic.jpg',
        ]);

        $photo2->delete();

        $this->assertEquals(count($product->photos), 1);
    }

}



