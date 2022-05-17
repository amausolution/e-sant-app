<?php

namespace Feggu\Core\Admin\Middleware;

use Closure;
use Session;

class AdminPartnerId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\Admin::user()) {
            session(['adminPartnerId' => 1]);
        } else {
            session()->forget('adminPartnerId');
        }
        return $next($request);
    }
}
