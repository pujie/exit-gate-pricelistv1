<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Cek apakah user sudah login dan apakah rolenya ada di daftar yang diizinkan
        if (auth()->check() && in_array(auth()->user()->role, $roles)) {
            return $next($request);
        }
    
        return redirect('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
    }
}
