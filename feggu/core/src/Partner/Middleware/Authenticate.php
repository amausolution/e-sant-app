<?php

namespace Feggu\Core\Partner\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $redirectTo = au_route_partner('partner.login');
        if (Auth::guard('partner')->guest() && !$this->shouldPassThrough($request)) {
            return redirect()->guest($redirectTo);
        }
        return $next($request);
    }

    /**
     * Determine if the request has a URI that should pass through verification.
     *
     * @param Request $request
     *
     * @return bool
     */
    protected function shouldPassThrough($request)
    {
        $routeName = $request->path();
        $excepts = [
            AU_PARTNER_PREFIX . '/auth_partner/login',
            AU_PARTNER_PREFIX . '/auth_partner/logout',
        ];
        return in_array($routeName, $excepts);
    }
}
