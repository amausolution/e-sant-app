<?php

namespace Feggu\Core\Partner\Middleware;

use Closure;
use Feggu\Core\Partner\Partner;
use Feggu\Core\Partner\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use InvalidArgumentException;

class PermissionMiddleware
{
    /**
     * @var string
     * Example midleware roles partner.permission:allow,administrator,editor
     */
    protected $middlewarePrefix = 'partner.permission:';

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure                 $next
     * @param array                    $args
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$args)
    {
        if (!empty($args) || $this->shouldPassThrough($request) || Partner::user()->isAdministrator()) {
            return $next($request);
        }

        // Allow access route
        if ($this->routeDefaultPass($request)) {
            return $next($request);
        }

        //Check middware in route
        if ($this->checkRoutePermission($request)) {
            return $next($request);
        }

        //Group view all
        // this group can view all path, but cannot change data
        if (Partner::user()->isViewAll()) {
            if ($request->method() == 'GET'
                && !collect($this->viewWithoutToMessage())->contains($request->path())
                && !collect($this->viewWithout())->contains($request->path())
            ) {
                return $next($request);
            } else {
                if (!request()->ajax()) {
                    if (collect($this->viewWithoutToMessage())->contains($request->path())) {
                        return redirect()->route('partner.deny_single')->with(['url' => $request->url(), 'method' => $request->method()]);
                    }
                    return redirect()->route('partner.deny')->with(['url' => $request->url(), 'method' => $request->method()]);
                } else {
                    if (collect($this->viewWithoutToMessage())->contains($request->path())) {
                        return redirect()->route('partner.deny_single')->with(['url' => $request->url(), 'method' => $request->method()]);
                    }
                    return Permission::error();
                }
            }
        }

        if (!Partner::user()->allPermissions()->first(function ($modelPermission) use ($request) {
            return $modelPermission->passRequest($request);
        })) {
            if (!request()->ajax()) {
                return redirect()->route('partner.deny')->with(['url' => $request->url(), 'method' => $request->method()]);
            } else {
                return Permission::error();
            }
        }
        return $next($request);
    }

    /**
     * If the route of current request contains a middleware prefixed with 'partner.permission:',
     * then it has a manually set permission middleware, we need to handle it first.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function checkRoutePermission(Request $request)
    {
        if (!$middleware = collect($request->route()->middleware())->first(function ($middleware) {
            return Str::startsWith($middleware, $this->middlewarePrefix);
        })) {
            return false;
        }
        $args = explode(',', str_replace($this->middlewarePrefix, '', $middleware));

        $method = array_shift($args);

        if (!method_exists(Permission::class, $method)) {
            throw new InvalidArgumentException("Invalid permission method [$method].");
        }

        call_user_func_array([Permission::class, $method], [$args]);

        return true;
    }

    /**
     * Determine if the request has a URI that should pass through verification.
     *
     * @param Request $request
     *
     * @return bool
     */
    protected function shouldPassThrough($request)
    {
        $routePath = $request->path();
        $exceptsPAth = [
            AU_PARTNER_PREFIX . '/auth_partner/login',
            AU_PARTNER_PREFIX . '/auth_partner/logout',
        ];
        return in_array($routePath, $exceptsPAth);
    }

    /*
    Check route defualt allow access
    */
    public function routeDefaultPass($request)
    {
        $routeName = $request->route()->getName();
        $allowRoute = ['partner.deny', 'partner.deny_single', 'partner.locale', 'partner.home', 'partner.theme','partner.data_not_found'];
        return in_array($routeName, $allowRoute);
    }

    public function viewWithout()
    {
        return [
            // Array item in here
        ];
    }

    /**
     * Send page deny as meeasge
     *
     * @return  [type]  [return description]
     */
    public function viewWithoutToMessage()
    {
        return [
            AU_PARTNER_PREFIX . '/uploads/delete',
            AU_PARTNER_PREFIX . '/uploads/newfolder',
            AU_PARTNER_PREFIX . '/uploads/domove',
            AU_PARTNER_PREFIX . '/uploads/rename',
            AU_PARTNER_PREFIX . '/uploads/resize',
            AU_PARTNER_PREFIX . '/uploads/doresize',
            AU_PARTNER_PREFIX . '/uploads/cropimage',
            AU_PARTNER_PREFIX . '/uploads/crop',
            AU_PARTNER_PREFIX . '/uploads/move',
        ];
    }
}
