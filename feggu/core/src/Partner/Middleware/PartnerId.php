<?php

namespace Feggu\Core\Partner\Middleware;

use Closure;
use Session;

class PartnerId
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
        if (\Partner::user()) {
            session(['partnerId' => \Partner::user()->partner_id]);
        } else {
            session()->forget('partnerId');
        }

        return $next($request);
    }
}
