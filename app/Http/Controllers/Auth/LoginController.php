<?php

namespace App\Http\Controllers\Auth;

use Adldap\Adldap;
use Adldap\AdldapException;
use Adldap\Connections\Provider;
use App\Http\Controllers\Controller;
use App\TermDates;
use App\User;
use bbec\Shop\Models\Merit;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $currentUser;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Override login method from AuthenticateUsers
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        $this->currentUser = User::getActiveUser($request->username);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if($this->currentUser) {
            if($this->currentUser->auth == "ldap") {

                $adConfig = [
                    'default' => [
                        'domain_controllers' => ['192.168.0.11'],
                        'base_dn' => 'OU=Establishments,DC=bbec,DC=internal'
                    ]
                ];
                $ad = new Adldap($adConfig);
                try {
                    $ad->connect('default', $request->username . '@bbec.internal', $request->password);
                    \Auth::login($this->currentUser);
                    //Google::setCredentials($request->username);
                    return $this->sendLoginResponse($request);
                } catch (AdldapException $e) {

                }
            } else if($this->currentUser->auth == "manual") {
                if ($this->attemptLogin($request)) {
                    return $this->sendLoginResponse($request);
                }
            }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Override the hasTooManyLoginAttempts
     *
     * if the user is a student keep them the same
     *
     * @param Request $request
     * @return bool
     */
    protected function hasTooManyLoginAttempts(Request $request)
    {
        if($this->currentUser && $this->currentUser->role == 'student') {
            $maxLoginAttempts = 10;
            $lockoutTime = 1;
        } else {
            $maxLoginAttempts = 3;
            $lockoutTime = 2;
        }


        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), $maxLoginAttempts, $lockoutTime
        );
    }

    public function username()
    {
        return 'username';
    }

    public function authenticated(Request $request, $user)
    {
        if($user->role == "student") {
            $merit = new Merit();
            $merits = $merit->getCurrentMeritValue($user->id);

            // If no merits are present enqueue the request to SIMS
            if(count($merits) == 0) {
                $td = new TermDates();
                $merits = $merit->syncMeritPoints($td->getFirstDate(), $td->getEndDate(), $user->sims_id);
            }
        }
    }
}
