<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
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

        if( $request->user() && $request->user()->user_role==2)
        {
            return $next($request);
        }

        return response()->json('You are not Admin',400);
    }

  }
