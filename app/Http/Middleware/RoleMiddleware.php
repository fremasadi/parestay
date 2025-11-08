<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (strtolower(Auth::user()->role) !== strtolower($role)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }


        return $next($request);
    }
}
