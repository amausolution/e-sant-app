<?php

namespace Feggu\Core\Partner\Middleware;

use Illuminate\Http\Request;

class Session
{
    public function handle(Request $request, \Closure $next)
    {
        $path = '/' . trim(AU_PARTNER_PREFIX, '/');

        config(['session.path' => $path]);

        return $next($request);
    }
}
