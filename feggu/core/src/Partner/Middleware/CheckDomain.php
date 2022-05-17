<?php

namespace Feggu\Core\Partner\Middleware;

use Closure;
use Feggu\Core\Partner\Models\FegguPartner;

class CheckDomain
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
        //Only apply for when plugin multi-vendor or multi-store active
        if (au_config_global('MultiPartnerPro') && au_config_global('domain_strict')) {
            //Check domain exist
            $domain = au_process_domain_partner(url('/')); //domain currently
            $domainRoot = au_process_domain_partner(config('app.url')); //Domain root config in .env
            $arrDomainPartner = FegguPartner::getDomainPartner(); // List domain is partner active
           // $arrDomainActive = FegguPartner::getDomain(); // List domain is unlock domain

            if (au_config_global('MultiPartnerPro')) {
                if (!in_array($domain, $arrDomainPartner) && $domain != $domainRoot) {
                    echo view('deny_domain')->render();
                    exit();
                }
            }

        }
        return $next($request);
    }
}
