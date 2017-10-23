<?php

use bbec\Shop\Models\Merit;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MeritTest extends \Tests\TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function a_user_can_add_merits()
    {
        $points = 150;

        $user = \Auth::loginUsingId(1);

        //$merits = $user->merits()->save(new Merit(['points' => $points]));
        $merits = $user->merits()->save(new Merit(['points' => $points]));

        $this->assertEquals($points, $merits->points);
    }

    /** @test */
    public function merit_points_are_updated_if_record_exists()
    {
        $user = \Auth::loginUsingId(1);

        $points = 3000;

        $merits = $user->merits();

        if($merits->count() == 0) {
            $merits->save(new Merit(['points' => $points]));
        } else {
            $merits->update(['points' => $points]);
        }

        $this->assertEquals($points, $merits->first()->points);
    }

    /** @test */
    public function a_user_has_a_merit_balance()
    {
        $user = \Auth::loginUsingId(1);

        $merit = new Merit();
        $balance = $merit->balance($user);

        $this->assertEquals(721, $balance);
    }

    /** @test */
    public function can_retrieve_todays_merit_points()
    {
        $user = \Auth::loginUsingId(825);

        $merit = new Merit();
        $merits = $user->merits();

        $merits->save(new Merit(['points' => 314, 'sync_date'=>Carbon::now()->format("Y-m-d")]));

        $todaysMerits = $merit->getCurrentMeritValue($user);

        $this->assertEquals($todaysMerits->points, 314);
    }
}