<style>
    /* Merapikan Box DataTables */
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1.5rem;
    }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.4rem 1rem;
        outline: none;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        ring: 2px;
        ring-color: #4f46e5;
        border-color: #4f46e5;
    }
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.3rem 2rem 0.3rem 0.5rem;
    }
    /* Tombol Navigasi */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #4f46e5 !important;
        color: white !important;
        border-radius: 0.4rem;
        border: none;
    }
</style>
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen User Sales') }}
            </h2>
            <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                + Tambah User
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg-lg">
                
                @if(session('success'))
                    <div class="mb-4 text-sm font-medium text-green-600">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Daftar Akun Sales</h3>
                    <p class="text-sm text-gray-500">Kelola akses login dan status keamanan OTP tim Anda.</p>
                </div>
                <table id="userTable" class="min-w-full divide-y divide-gray-200 shadow-sm border rounded-lg overflow-hidden">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50/50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status OTP</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($user->two_factor_code)
                                    <span class="text-yellow-600">Menunggu Verifikasi</span>
                                @else
                                    <span class="text-green-600">Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
                                <a href="{{ route('users.edit', $user->id) }}" class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                responsive: true,
                language: {
                    search: "Cari Sales:",
                    lengthMenu: "Tampilkan _MENU_ user per halaman",
                }
            });
        });
    </script>
    @endpush
</x-app-layout>