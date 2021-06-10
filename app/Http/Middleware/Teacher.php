<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Teacher
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
        if ($request->user()->user_role != 3) {
            return response()->json('You are not a Teacher', 400);
        }
        return $next($request);
    }
}
