<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Cek apakah role user sesuai dengan yang diizinkan
        if (!in_array(Auth::user()->role, $roles)) {
            abort(403, 'Maaf, Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}