<?php

namespace Feggu\Core\Api\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiConnection
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!au_config('api_connection_required')) {
            return $next($request);
        }
        $apiconnection = $request->header('apiconnection');
        $apikey = $request->header('apikey');
        if (!$apiconnection || !$apikey) {
            return  response()->json(['error' => 1, 'msg' => 'apiconnection or apikey not found']);
        }
        $check = ApiConnection::check($apiconnection, $apikey);
        if ($check) {
            $check->update(['last_active' => date('Y-m-d H:i:s')]);
            return $next($request);
        } else {
            return  response()->json(['error' => 1, 'msg' => 'Connection not correct']);
        }
        return $next($request);
    }
}
