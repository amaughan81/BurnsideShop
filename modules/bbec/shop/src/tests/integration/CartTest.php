<?php

use bbec\Shop\Models\Coupon;
use bbec\Shop\Models\Product;
use bbec\Shop\Models\Cart;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CartTest extends \Tests\TestCase {

    use DatabaseTransactions;

    /** @test */
    public function a_product_can_be_added_to_the_cart()
    {
        $session = new Session(new MockArraySessionStorage());
        $session->items = [];
        $session->totalQty = 0;
        $session->coupons = [];
        $session->totalPrice = 0;

        $cart = new Cart($session);

        $product = factory(Product::class)->create();
        $cart->add($product);

        $product2 = factory(Product::class)->create();
        $cart->add($product2);

        $this->assertEquals(2, count($cart->getItems()));
    }
    
    /** @test */
    public function product_quantity_is_updated_when_an_existing_product_is_added()
    {
        $session = new Session(new MockArraySessionStorage());
        $session->items = [];
        $session->totalQty = 0;
        $session->totalPrice = 0;

        $product = factory(Product::class)->create();
        $product2 = factory(Product::class)->create();

        $cart = new Cart($session);
        $cart->add($product);
        $cart->add($product);
        $cart->add($product);
        $cart->add($product2);

        $items = $cart->getItems();
        $item = $items[$product->id];

        // Product qty count should equal 3
        $this->assertEquals(3, count($item));
        // Total Product count should equal 2
        $this->assertEquals(2, count($items));
        // Total Quantity should equal 4
        $this->assertEquals(4, $cart->totalQty);
    }
    
    /** @test */
    public function a_product_can_be_removed_from_the_cart()
    {
        $session = new Session(new MockArraySessionStorage());
        $session->items = [];
        $session->totalQty = 0;
        $session->totalPrice = 0;

        $product = factory(Product::class)->create();
        $product2 = factory(Product::class)->create();

        $cart = new Cart($session);
        $cart->add($product);
        $cart->add($product2);

        $cart->removeItem($product2);

        $items = $cart->getItems();

        $this->assertEquals(1, count($items));
    }
    
    /** @test */
    public function total_quantity_and_price_are_updated_when_a_product_is_removed()
    {
        $session = new Session(new MockArraySessionStorage());
        $session->items = [];
        $session->totalQty = 0;
        $session->totalPrice = 0;

        $product = factory(Product::class)->create();
        $product2 = factory(Product::class)->create();

        $cart = new Cart($session);
        $cart->add($product);
        $cart->add($product2);

        $cart->removeItem($product2);

        $items = $cart->getItems();

        $this->assertEquals(1, count($items));
        $this->assertEquals($product->price, $cart->totalPrice);
        $this->assertEquals(1, $cart->totalQty);
    }

    /** @test */
    public function reduce_product_quantity()
    {
        $session = new Session(new MockArraySessionStorage());
        $session->items = [];
        $session->totalQty = 0;
        $session->totalPrice = 0;

        $product = factory(Product::class)->create();
        $product2 = factory(Product::class)->create();

        $cart = new Cart($session);
        $cart->add($product);
        $cart->add($product);
        $cart->add($product);

        $cart->reduceQuantity($product, 2);

        $this->assertEquals(1, $cart->totalQty);
    }

    /** @test */
    public function reduce_total_price_when_product_quanitity_is_changed()
    {
        $session = new Session(new MockArraySessionStorage());
        $session->items = [];
        $session->totalQty = 0;
        $session->totalPrice = 0;

        $product = factory(Product::class)->create();

        $cart = new Cart($session);
        $cart->add($product);
        $cart->add($product);
        $cart->add($product);

        $cart->reduceQuantity($product);

        $this->assertEquals(($product->price * 2), $cart->totalPrice);
    }

    /** @test */
    public function get_total_price()
    {
        $session = new Session(new MockArraySessionStorage());
        $session->items = [];
        $session->totalQty = 0;
        $session->totalPrice = 0;

        $product = factory(Product::class)->create();

        $cart = new Cart($session);
        $cart->add($product);
        $cart->add($product);

        $this->assertEquals(($product->price) * 2, $cart->totalPrice);
    }

    /** @test */
    public function a_coupon_can_be_applied_to_the_balance()
    {
        $session = new Session(new MockArraySessionStorage());
        $session->items = [];
        $session->totalQty = 0;
        $session->totalPrice = 0;

        $coupon = new Coupon();

        $coupon1 = factory(Coupon::class)->create(['code' => $coupon->generateCode()]);

        $product = factory(Product::class)->create();

        // Add two products to the cart
        $cart = new Cart($session);
        $cart->add($product);
        $cart->add($product);

        if(!$coupon->hasBeenRedeemed($coupon1->code)) {

            $validCoupon = Coupon::code($coupon1->code)->first();

            $cart->applyCoupon($validCoupon);
        }

        // Assert Coupon has been applied
        $this->assertEquals(1, count($cart->coupons));
        // Assert price has been updated
        $total = ($product->price * 2) - $coupon1->value;
        $this->assertEquals($total, $cart->totalPrice);
    }

}