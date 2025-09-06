<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JwtFromQueryString
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('token')) {
            $token = $request->input('token');
            $request->headers->set('Authorization', 'Bearer '.$token);
        }

        return $next($request);
    }
}
