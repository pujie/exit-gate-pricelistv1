<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Tampilkan Daftar User
    public function index() {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    // Form Tambah User
    public function create() {
        return view('users.create');
    }

    // Simpan User Baru
    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambah');
    }

    // Form Edit User
    public function edit(User $user) {
        return view('users.edit', compact('user'));
    }

    // Update Data User
    public function update(Request $request, User $user) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        
        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return redirect()->route('users.index')->with('success', 'User berhasil diupdate');
    }

    // Hapus User
    public function destroy(User $user) {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus');
    }
}