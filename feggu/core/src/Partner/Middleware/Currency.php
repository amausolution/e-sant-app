<?php

namespace Feggu\Core\Partner\Middleware;

use Feggu\Core\Front\Models\FegguCurrency;
use Closure;
use Session;

class Currency
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
        $currency = session('currency') ?? au_partner('currency');
        if (!array_key_exists($currency, au_currency_all_active())) {
            $currency = array_key_first(au_currency_all_active());
        }
        FegguCurrency::setCode($currency);
        return $next($request);
    }
}
