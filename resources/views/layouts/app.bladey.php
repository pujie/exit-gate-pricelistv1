<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Verifikasi Keamanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center">
                    <p class="mb-4 text-sm text-gray-600">
                        Kode OTP telah dikirim ke <strong>{{ auth()->user()->email }}</strong>
                    </p>

                    @if($errors->any())
                        <div class="mb-4 font-medium text-sm text-red-600">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verify.store') }}">
                        @csrf
                        <div class="mb-4">
                            <input type="text" name="two_factor_code" 
                                   class="form-control text-center text-2xl tracking-widest font-bold w-full md:w-1/2 mx-auto" 
                                   placeholder="000000" maxlength="6" inputmode="numeric" required autofocus>
                        </div>

                        <x-primary-button class="justify-center w-full md:w-1/2">
                            {{ __('Verifikasi Sekarang') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>