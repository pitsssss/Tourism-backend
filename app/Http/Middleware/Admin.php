<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
{
    public function handle(Request $request, Closure $next)
    {
          if (!auth()->check()) {
        return redirect()->route('login');
    }
        $user = auth()->user();

            if ($user && in_array($user->role, ['super_admin', 'admin'])) {

            return $next($request);
        }

        auth()->logout(); // حتى ما يضل بالجلسة
    return redirect()->route('login')->with('error', 'لا تملك صلاحية الدخول.');

    }
}
