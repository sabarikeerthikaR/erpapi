<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Driver
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->type != 2) {
            return response()->json('You are not a Driver', 400);
        }
        return $next($request);
    }
}
