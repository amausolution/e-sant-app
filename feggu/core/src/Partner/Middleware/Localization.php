<?php

namespace Feggu\Core\Partner\Middleware;

use Feggu\Core\Partner\Models\FegguLanguage;
use Closure;
use Session;

class Localization
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
        //Set language
        $languages = FegguLanguage::getListActive();
        if (!Session::has('locale')) {
            $detectLocale = au_partner('language') ?? config('app.locale');
        } else {
            $detectLocale = session('locale');
        }
        $currentLocale = array_key_exists($detectLocale, $languages->toArray()) ? $detectLocale : $languages->first()->code;
        session(['locale' => $currentLocale, 'locale_id' => $languages[$currentLocale]['id']]);
        app()->setLocale($currentLocale);
        //End language
        return $next($request);
    }
}
