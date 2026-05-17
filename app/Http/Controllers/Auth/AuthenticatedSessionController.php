<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
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
    public function store(LoginRequest $request): RedirectResponse
    {
        // Strict Role Check: Block super_admin accounts from citizen node
        $user = \App\Models\User::where('email', $request->email)->first();
        if ($user && $user->role === 'super_admin') {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => 'Access denied. Administrative accounts must login through the secure system portal.',
            ]);
        }

        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Display the super admin secret login view.
     */
    public function createSuperAdmin(): View
    {
        return view('auth.superadmin');
    }

    /**
     * Handle super admin login request.
     */
    public function storeSuperAdmin(LoginRequest $request): RedirectResponse
    {
        // Strict Role Check: Allow ONLY super_admin roles to use this portal
        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user || $user->role !== 'super_admin') {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => 'Access denied. Unauthorized system credentials.',
            ]);
        }

        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
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
