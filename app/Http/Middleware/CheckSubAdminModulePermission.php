<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckSubAdminModulePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $module)
    {
        if (!Auth::user()->hasPermission($module)) {
            return response()->json(apiResponseHandler([], 'Sorry! Your are not authorized to access this route', 400), 400);
        }

        return $next($request);
    }
}
