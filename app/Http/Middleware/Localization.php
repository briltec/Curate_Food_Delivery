<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if ($request->is('restaurant-panel/*')) {
            if (session()->has('vendor_local')) {
                App::setLocale(session()->get('vendor_local'));
            }
        // }else{
        //     if (session()->has('local')) {
        //         App::setLocale(session()->get('local'));
        //     }
        // }

    }elseif($request->is('admin/*')){
        if (session()->has('local')) {
            App::setLocale(session()->get('local'));
        }
    }else{
        if (session()->has('landing_local')) {
            App::setLocale(session()->get('landing_local'));
        }
    }
        return $next($request);
    }
}
