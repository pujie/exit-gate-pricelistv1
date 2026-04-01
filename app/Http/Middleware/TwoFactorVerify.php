<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class TwoFactorVerify
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // 1. Jika user sudah login
        if (auth()->check()) {
            
            // 2. Cek apakah user memiliki 'two_factor_code' yang belum diverifikasi
            // Kita asumsikan jika field ini tidak null, berarti user harus verifikasi dulu
            if ($user->two_factor_code) {
                
                // Cek apakah kode sudah kadaluarsa (misal 1 jam)
                if ($user->two_factor_expires_at && now()->gt($user->two_factor_expires_at)) {
                    auth()->logout();
                    return redirect()->route('login')->with('error', 'Kode OTP kadaluarsa. Silakan login kembali.');
                }

                // Jika user mencoba mengakses selain halaman verifikasi OTP, lempar balik ke halaman verifikasi
                if (!$request->is('verify-2fa*') && !$request->is('logout')) {
                    return redirect()->route('2fa.index');
                }
            }
        }

        return $next($request);
    }
}