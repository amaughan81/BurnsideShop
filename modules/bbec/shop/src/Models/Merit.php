<?php

namespace bbec\Shop\Models;

use App\BSG;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Merit extends Model
{
    protected $fillable = ['points', 'sync_date'];

    /**
     * A merit belongs to a user
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the users current merit score
     *
     * @param $user_id
     * @return mixed
     */
    public function getCurrentMeritValue($user_id)
    {
        $today = Carbon::now()->format("Y-m-d");

        return $this->where([
            ['sync_date',$today],
            ['user_id', $user_id]
        ])->first();
    }

    /**
     * Send a request to the queue
     *
     * @param $fromDate
     * @param $toDate
     * @param $student
     * @param int $personId
     */
    public function syncMeritPoints($fromDate, $toDate, $student, $personId=0)
    {
        $user = \Auth::user();
        if($personId == 0) {
            $personId = $user->sims_id;
        }
        $message = [
           'personId' => $personId,
           'behaviourType' => 0,
           'location' => 0,
           'status' => 0,
           'studentId' => $student,
           'fromDate' => $fromDate,
           'toDate' => $toDate,
           'tier' => '',
           'year' => '',
           'reg' => '',
           'house' => '',
           'class' => 0,
           'recordedBy' => 0,
           'activityType' => 0
        ];

        $bsg = new BSG();
        $bsg->buildQuery(
            "BrowseAchievements",
            $user->sims_id,
            json_encode($message),
            75
        );
        $bsg->addToQueue();
    }

    /**
     * Return the user's current balance
     *
     * @return mixed
     */
    public function balance()
    {
        return ($this->points - $this->calcTotalMerits());
    }

    public function calcTotalMerits()
    {
        $orders = auth()->user()->orders()->get();

        return $total = $orders->sum(function ($order) {
            return $order['amount'];
        });
    }
}
