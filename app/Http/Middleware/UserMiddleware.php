<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param $roles
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        $roles = explode('|', $roles);
        foreach ($roles as $role) {

            // if user has given role, continue processing the request
            if (Auth::user()->roles->pluck("id", "name")->has($role)) {
                return $next($request);
            }
        }

        // user didn't have any of required roles, return Forbidden error
        abort(404);
    }
}
