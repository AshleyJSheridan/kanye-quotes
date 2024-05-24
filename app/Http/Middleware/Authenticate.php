<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Factory as Auth;
use Closure;

class Authenticate extends Middleware
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next, ...$guard)
    {
        if ($this->auth->guard(reset($guard))->guest())
            return response('Unauthorised.', 401);

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    /*protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }*/
}
