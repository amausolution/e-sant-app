<?php

namespace Feggu\Core\Partner\Middleware;

use Illuminate\Http\Request;

class Session
{
    public function handle(Request $request, \Closure $next)
    {
        $path = '/' . trim(AU_PARTNER_PREFIX, '/');

        config(['session.path' => $path]);

        if ($domain = config('partner.route.domain')) {
            config(['session.domain' => $domain]);
        }

        return $next($request);
    }
}
