<?php

namespace App\Http\Middleware;

use App\Acl;
use App\TermDates;
use bbec\Shop\Models\Merit;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, ...$params)
    {
        $actionNameParts = explode("@", $request->route()->getActionName());
        $action = $actionNameParts[1];

        // get the role of the user
        $role = 'guest';

        if(Auth::check())
        {
            $role = Auth::user()->role;
        }

        if(!Acl::isAllowed($role, $action)) {
            Auth::logout();
            return redirect('/login');
        }

        return $next($request);
    }
}
