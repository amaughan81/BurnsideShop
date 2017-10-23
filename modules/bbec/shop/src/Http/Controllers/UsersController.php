<?php

namespace bbec\Shop\Http\Controllers;

use App\Acl;
use App\TermDates;
use App\User;
use bbec\Shop\Models\Merit;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        Acl::init();
        Acl::deny('guest');
        Acl::allow('student', ['account']);
        Acl::allow('manager', ['account']);
        Acl::deny('staff');
        Acl::deny('ls_admin');
        Acl::deny('priv_user');
        Acl::deny('slt');
        Acl::allow('admin');
        Acl::allow('super_admin');

        $this->middleware('amacl');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = User::activeStudents();
        return view('shop::users.index', compact('users'));
    }

    /**
     * Give a break down of how student merits are calculated
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function account()
    {
        if(auth()->user()->role != "student" && auth()->user()->role != "manager") {
            return redirect('/');
        }
        $td = new TermDates();
        $merit = new Merit();
        $merits = $merit->syncMeritPoints($td->getFirstDate(), $td->getEndDate(), auth()->user()->sims_id);

        $orderSum = $merit->calcTotalMerits();

        return view('shop::users.account', compact('orderSum'));
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('shop::users.edit', compact('user'));
    }

    /**
     * @param Request $request
     * @param User $user
     */
    public function update(Request $request, User $user)
    {
        $user->auth = $request->auth;
        $user->forename = $request->forename;
        $user->surname = $request->surname;
        $user->role = $request->role;
        $user->save();

        flash('Success - User details have been updated')->success()->important();

        return redirect('/users/'.$user->id.'/edit');
    }
}
