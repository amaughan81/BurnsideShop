<?php

use bbec\Shop\Models\Category;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CategoryTest extends \Tests\TestCase {
    // use this to roll back db transactions
    //use DatabaseTransactions;

    /** @test */
    function a_category_can_be_created() {
        $category = factory(Category::class)->create();

        $categories = new Category();

        $this->assertEquals($category->id, $categories->latest()->first()->id);
    }
}