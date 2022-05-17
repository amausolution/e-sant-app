<?php

namespace Feggu\Core\Partner\Middleware;

use Feggu\Core\Partner\Partner;
use Feggu\Core\Partner\Models\PartnerLog;
use Illuminate\Http\Request;

class LogOperation
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if ($this->shouldLogOperation($request)) {
            $log = [
                'user_id' => Partner::user()->id,
                'path' => substr($request->path(), 0, 255),
                'method' => $request->method(),
                'ip' => $request->getClientIp(),
                'user_agent' => $request->header('User-Agent'),
                'input' => json_encode($request->input()),
            ];

            try {
                PartnerLog::create($log);
            } catch (\Exception $exception) {
                // pass
            }
        }

        return $next($request);
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    protected function shouldLogOperation(Request $request)
    {
        return au_config_global('ADMIN_LOG')
        && !$this->inExceptArray($request)
        && Partner::user();
    }

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach (explode(',', au_config_global('PARTNER_LOG_EXP')) as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }
            if ($request->path() == $except) {
                return true;
            }
        }

        return false;
    }
}
