<?php

namespace bbec\Shop\Http\Controllers;

use App\User;
use bbec\Shop\Models\Merit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MeritsController extends Controller
{
    /**
     * Persist the student merit points
     *
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        if($request->has('user_id')) {
            $user = User::findOrFail($request->user_id);
        } else {
            $user = auth()->user();
        }
        $merits = $user->merits();

        $today = Carbon::now()->format("Y-m-d");
        $data = ['points' => $request->points, 'sync_date' => $today];

        if($merits->count() == 0) {
            $merits->save(new Merit($data));
        } else {
            $merits->update($data);
        }

        // return the user's balance
        return ['balance' => $user->merits->balance()];
    }
}
