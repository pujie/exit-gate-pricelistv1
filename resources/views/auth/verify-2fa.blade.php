@extends('layouts.app') {{-- Asumsi Anda menggunakan layout utama Laravel --}}

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 text-center">
                    <div class="mb-4">
                        <i class="fas fa-envelope-open-text fa-3x text-primary"></i>
                    </div>

                    <h4 class="font-weight-bold">Verifikasi Keamanan</h4>
                    <p class="text-muted">
                        Kami telah mengirimkan kode OTP ke email: <br>
                        <strong>{{ auth()->user()->email }}</strong>
                    </p>

                    <form action="{{ route('2fa.verify') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="text" 
                                   name="otp" 
                                   class="form-control form-control-lg text-center @error('otp') is-invalid @enderror" 
                                   placeholder="Masukkan 6 Digit Kode"
                                   maxlength="6" 
                                   required 
                                   style="letter-spacing: 10px; font-size: 1.5rem; font-weight: bold;">
                            
                            @error('otp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg btn-block shadow-sm">
                            Verifikasi Sekarang
                        </button>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-1 text-muted">Belum menerima kode?</p>
                        <form action="{{ route('2fa.resend') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link text-decoration-none p-0">
                                Kirim Ulang Kode
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                        Batal dan Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection