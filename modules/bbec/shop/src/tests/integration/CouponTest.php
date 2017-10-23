<?php

use bbec\Shop\Models\Coupon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Session\Session;

class CouponTest extends \Tests\TestCase {

    use DatabaseTransactions;

    /** @test */
    public function can_generate_a_token()
    {
        $coupon = new Coupon();
        $code = $coupon->getToken(10, time());

        $this->assertEquals(strlen($code), 10);
    }

    /** @test */
    public function can_generate_a_unqie_code()
    {
        $coupon = new Coupon();
        $coupon->generateCode();
    }

    /** @test */
    public function can_create_multiple_coupons()
    {
        $numCoupons = 10;

        $coupon = new Coupon();
        $coupon->massCreate(100, $numCoupons);

        $this->assertEquals($numCoupons, count(Coupon::all()));
    }

    /** @test */
    public function can_retrieve_active_coupons()
    {
        $coupon  = new Coupon();

        $coupon1 = factory(Coupon::class)->create(['code' => $coupon->generateCode()]);
        $coupon2 = factory(Coupon::class)->create(['code' => $coupon->generateCode(), 'redeemed' => 1]);

        $this->assertTrue($coupon->hasBeenRedeemed($coupon1->code));
    }

    /** @test */
    public function a_coupon_can_be_redeemed()
    {
        $coupon = new Coupon();

        $coupon1 = factory(Coupon::class)->create(['code' => $coupon->generateCode()]);

        $coupon->redeem($coupon1->code);

        $this->assertEquals(1, Coupon::all()->first()->redeemed);
    }


}