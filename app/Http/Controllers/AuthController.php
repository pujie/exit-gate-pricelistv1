<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCode;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Generate dan Kirim OTP (Dipanggil setelah login sukses)
     */
    public function generateCode($user)
    {
        \Log::info('1. Masuk fungsi generateCode untuk: ' . $user->email);
        $code = rand(100000, 999999); // Generate 6 digit angka

        $user->update([
            'two_factor_code' => $code,
            'two_factor_expires_at' => now()->addMinutes(15), // Berlaku 15 menit
        ]);
        \Log::info('2. Database terupdate dengan kode: ' . $code);
        Mail::to($user->email)->send(new TwoFactorCode($code));
        \Log::info('3. Email berhasil dikirim');
        return redirect()->route('verify.index');
    }

    /**
     * Menampilkan Form Verifikasi
     */
    public function showVerifyForm()
    {
        return view('auth.verify-2fa');
    }

    /**
     * Proses Verifikasi Input User
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        $user = auth()->user();

        // Cek jika kode cocok dan belum expired
        if ((int)$request->otp == (int)$user->two_factor_code && now()->lt($user->two_factor_expires_at)) {
            
            // Reset kode agar Middleware 2fa_verified mengizinkan akses
            $user->update([
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
            ]);

            return redirect()->route('dashboard');
        }

        return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kadaluarsa.']);
    }

    /**
     * Kirim Ulang Kode (Resend)
     */
    public function resendOtp()
    {
        $this->generateCode(auth()->user());
        return back()->with('success', 'Kode baru telah dikirim ke email Anda.');
    }

    /**
 * Menampilkan Form Verifikasi
 */
public function indexVerify()
{
    // Pastikan user sedang login
    \Log::info('4. Index Verify dicapai');
    if (!auth()->check()) {
        \Log::info('5. Otentikasi tidak berhasil, login kembali');
        return redirect()->route('login');
    }
    \Log::info('6. Otentikasi berhasil, verifikasi OTP');
    return view('auth.verify-otp'); 
}

/**
 * Proses Verifikasi OTP
 */
/*public function storeVerify(Request $request)
{
    $request->validate([
        'two_factor_code' => 'required|integer',
    ]);

    $user = auth()->user();

    // Cek apakah kode cocok dan belum expired
    if ($request->two_factor_code == $user->two_factor_code && 
        now()->lessThan($user->two_factor_expires_at)) {
        
        // Reset kode agar tidak bisa dipakai lagi
        $user->update([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ]);

        return redirect()->route('dashboard'); // Redirect ke halaman utama sales
    }

    return back()->withErrors(['two_factor_code' => 'Kode OTP salah atau sudah kadaluarsa.']);
}*/
public function storeVerify(Request $request)
{
    \Log::info('7. Store Verify berhasil dicapai');
    $user = \App\Models\User::find(auth()->id()); // Ambil ulang data user terbaru dari DB

    if (!$user) {
        \Log::info('8. User tidak dikenal, login lagi');
        return redirect()->route('login')->withErrors(['email' => 'Sesi berakhir, silakan login ulang.']);
    }

    if ($request->two_factor_code == $user->two_factor_code ) {
        \Log::info('9. 2fa sudah sama');
        // Reset kode agar tidak bisa dipakai lagi
        $user->update([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ]);
        \Log::info('10. 2fa di db sudah terupdate');
        return redirect()->route('dashboard'); // Redirect ke halaman utama sales
    }
    \Log::info('11. Error iki cak, embuh opo-o');
    return back()->withErrors(['two_factor_code' => 'Kode OTP salah atau sudah kadaluarsa.']);
}
}