<?php

class UserTest extends \Tests\TestCase {
    
    /** @test */
    public function a_user_has_a_google_account()
    {
        $user = \Auth::loginUsingId(1);

        $user->google()->first()->username;
    }
    
    /** @test */
    public function check_if_a_user_has_a_google_account()
    {
        $user = \Auth::loginUsingId(1);

        $account = $user->googleAccount();

        $this->assertCount(0, $account);
    }
    
    /** @test */
    public function check_if_user_has_a_staff_profile()
    {
        $user = \Auth::loginUsingId(1);

        $account = $user->staffProfile();

        $this->assertCount(1, $account);
    }


}