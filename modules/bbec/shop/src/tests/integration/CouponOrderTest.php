<?php

use bbec\Shop\Models\Cart;
use bbec\Shop\Models\Coupon;
use bbec\Shop\Models\CouponOrder;
use bbec\Shop\Models\Order;
use bbec\Shop\Models\OrderProduct;
use bbec\Shop\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Tests\TestCase;

class CouponOrderTest extends TestCase {

    use DatabaseTransactions;
    
    /** @test */
    public function a_coupon_can_be_added_to_an_order()
    {
        $coupon  = new Coupon();

        $coupon1 = factory(Coupon::class)->create(['code' => $coupon->generateCode()]);
        $coupon2 = factory(Coupon::class)->create(['code' => $coupon->generateCode()]);

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

        if(!$coupon->hasBeenRedeemed($coupon1->code)) {
            $validCoupon = Coupon::code($coupon1->code)->first();
            $cart->applyCoupon($validCoupon);
        }
        if(!$coupon->hasBeenRedeemed($coupon2->code)) {
            $validCoupon = Coupon::code($coupon2->code)->first();
            $cart->applyCoupon($validCoupon);
        }

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


        foreach($cart->coupons as $cartCoupon) {
            $co = new CouponOrder();
            $co->add($cartCoupon, $order->id);
        }

        $this->assertCount(2, CouponOrder::all());


    }
}