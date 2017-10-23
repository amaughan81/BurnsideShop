<?php

use bbec\Shop\Models\Product;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductTest extends \Tests\TestCase
{
    // use this to roll back db transactions
    use DatabaseTransactions;

    /** @test */
    function a_product_can_be_create() {
        $product = factory(Product::class)->create();

        $products = new Product();

        $this->assertEquals($product->id, $products->latest()->first()->id);
    }

    /** @test */
    function create_unique_slug() {

        $product = new Product();
        $product->category_id = 2;
        $product->name = "First Product";
        $product->slug = str_slug($product->name);
        $product->description = "Some Text";
        $product->price = 100;
        $product->save();

        $this->assertEquals("first-product", $product->slug);

    }

    /** @test */
    public function a_product_automatically_sets_the_slug_on_create()
    {

        $product = factory(Product::class)->create([
            'name' => 'My Second Product'
        ]);

        $this->assertEquals('my-second-product', $product->slug);
    }
    
    /** @test */
    public function a_post_will_always_increment_a_slug_if_its_a_duplicate()
    {
        factory(Product::class)->create([
            'name' => 'My Third Product'
        ]);
        factory(Product::class)->create([
            'name' => 'My Third Product'
        ]);
        $latestProduct = factory(Product::class)->create([
            'name' => 'My Third Product'
        ]);

        $this->assertEquals('my-third-product-2', $latestProduct->slug);
    }
    
    /** @test */
    public function a_product_can_be_viewed_by_its_slug()
    {
        $product = factory(Product::class)->create([
            'name' => 'My Product'
        ]);

        $myProduct = Product::where('slug',$product->slug)->firstOrFail();

        $this->assertEquals($product->id, $myProduct->id);
    }

    /** @test */
    public function a_product_quantity_can_be_adjusted()
    {
        $product = factory(Product::class)->create([
            'name' => 'My Product',
            'quantity' => 10
        ]);

        $product->quantity(10);

        $this->assertEquals(20, $product->quantity);
    }

    public function check_that_a_product_is_in_stock()
    {
        
    }
}