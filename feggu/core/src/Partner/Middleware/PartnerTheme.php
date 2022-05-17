<?php

namespace Feggu\Core\Partner\Middleware;

use Closure;
use Session;

class PartnerTheme
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
        if (!Session::has('partnerTheme')) {
            $partnerTheme = (\Partner::user())?\Partner::user()->theme : '';
        } else {
            $partnerTheme = session('partnerTheme');
        }
        $currentTheme = in_array($partnerTheme, config('partner.theme')) ? $partnerTheme : config('partner.theme_default');
        session(['partnerTheme' => $currentTheme]);
        config(['partner.theme_default' => $currentTheme]);
        return $next($request);
    }
}
