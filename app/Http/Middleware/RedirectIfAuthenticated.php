<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Redirect admin to dashboard
                if ($guard === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                
                // Redirect regular users to home (ganti sesuai kebutuhan)
                return redirect('/home'); // atau route('home')
            }
        }

        return $next($request);
    }
}