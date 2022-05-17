<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckDepart
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (\Partner::user() && !session('departmentIds')){
            if (count(employeeDepartments()) > 0){
                return redirect()->route('department');
            }else{
                session(['departmentIds'=> employeeDepartments()]) ;
            }
        }
        return $next($request);
    }
}
