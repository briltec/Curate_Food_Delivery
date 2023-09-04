<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\CentralLogics\Helpers;

class VendorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Helpers::check_subscription_validity();

        if (Auth::guard('vendor')->check()) {
            if(!auth('vendor')->user()->status)
            {
                auth()->guard('vendor')->logout();
                return redirect()->route('vendor.auth.login');
            }
            return $next($request);
        }
        else if (Auth::guard('vendor_employee')->check()) {
            if(!auth('vendor_employee')->user()->restaurant->status)
            {
                auth()->guard('vendor_employee')->logout();
                return redirect()->route('vendor.auth.login');
            }
            return $next($request);
        }
        return redirect()->route('vendor.auth.login');
    }
}
