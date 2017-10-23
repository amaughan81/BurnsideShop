<?php

use bbec\Shop\Models\Cart;
use bbec\Shop\Models\Order;
use bbec\Shop\Models\OrderProduct;
use bbec\Shop\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class OrderTest extends \Tests\TestCase {
    //use DatabaseTransactions;
    
    /** @test */
    public function an_order_can_be_created_from_a_cart()
    {
        $session = new Session(new MockArraySessionStorage());
        $session->items = [];
        $session->totalQty = 0;
        $session->coupons = [];
        $session->totalPrice = 0;

        $cart = new Cart($session);

        $product = factory(Product::class)->create();
        $cart->add($product);
        $cart->add($product);
        $cart->add($product);

        // log in a user
        \Auth::loginUsingId(1);

        $order = new Order();
        $order->user_id = \Auth::user()->id;
        $order->amount = $cart->totalPrice;
        $order->save();

        $this->assertEquals($cart->totalPrice, Order::all()->first()->amount);
    }

    /** @test */
    public function products_can_be_added_to_an_order()
    {
        // Initialise the cart sessions
        $session = new Session(new MockArraySessionStorage());
        $session->items = [];
        $session->totalQty = 0;
        $session->coupons = [];
        $session->totalPrice = 0;

        // Initialise the cart
        $cart = new Cart($session);

        // Add two products to the cart
        $product = factory(Product::class)->create();
        $product2 = factory(Product::class)->create();
        $cart->add($product);
        $cart->add($product);
        $cart->add($product2);

        // log in a user
        \Auth::loginUsingId(1);

        // Create a new order
        $order = new Order();
        $order->user_id = \Auth::user()->id;
        $order->amount = $cart->totalPrice;
        $order->save();

        // Add cart items to the order
        foreach($cart->items as $item) {
            $op = new OrderProduct();
            $op->add($order->id, $item['item']->id, $item['qty']);
        }

        // Assert their are two products in the order
        $this->assertCount(2, OrderProduct::all());
    }

    /** @test */
    public function an_order_can_be_completed()
    {
        // log in a user
        \Auth::loginUsingId(1);

        // create order
        $order = new Order();
        $order->user_id = \Auth::user()->id;
        $order->amount = 100;
        $order->save();

        $order->toggleStatus();
        $order->toggleStatus();

        $this->assertEquals(0, $order->status);
    }


}