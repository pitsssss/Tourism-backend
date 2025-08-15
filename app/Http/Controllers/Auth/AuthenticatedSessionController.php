<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
        $request->session()->regenerate();

        switch (auth()->user()->role) {
            case 'super_admin':
                return redirect()->route('super_admin.dashboard');
            case 'admin_users':
                return redirect()->route('admin_users.dashboard');
            case 'admin_trips':
                return redirect()->route('dashboard');
            case 'admin_hotels':
                return redirect()->route('admin_hotels.dashboard');
            case 'admin_tour_guides':
                return redirect()->route('admin_tour_guides.index');
            
            default:
                return redirect('/'); 
        }
    }

    return back()->withErrors([
        'email' => 'المعلومات غير صحيحة.',
    ]);
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
