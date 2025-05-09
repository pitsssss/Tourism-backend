<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user() && auth()->user()->role === 'admin') {
        return $next($request);
    }

        return response()->json(['message' => 'Unauthorized. Admins only.'], 403);
    }
}
