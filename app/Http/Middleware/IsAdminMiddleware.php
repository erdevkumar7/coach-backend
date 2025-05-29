<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->user_type == 1) {
            return $next($request);
        }

        return $next($request);
    }
}


?>