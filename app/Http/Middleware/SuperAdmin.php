<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
     public function handle(Request $request, Closure $next): Response
    {dd(auth()->user());

        if (auth()->check() && auth()->user()->role === 'super_admin') {
            return $next($request);
        }

       // abort(403, 'You are not authorized to access the admin panel.');
    }
}
