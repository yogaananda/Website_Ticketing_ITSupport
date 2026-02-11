<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Menampilkan halaman Manajemen User
     */
    public function index()
    {
        // Proteksi tambahan: Pastikan hanya admin yang bisa masuk
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses tidak diizinkan.');
        }

        // Mengambil semua user untuk ditampilkan di tabel
        $users = User::latest()->get();

        return view('log_user', compact('users'));
    }

    /**
     * Menyimpan User Baru ke Database
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'username'  => 'required|string|unique:users,username|max:255',
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'division'  => 'required|string|max:255',
            'role'      => 'required|in:admin,it_support,user',
            'password'  => 'required|string|min:8',
        ]);

        // 2. Simpan Data
        User::create([
            'username'  => $request->username,
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'division'  => $request->division,
            'role'      => $request->role,
            'password'  => Hash::make($request->password), // Enkripsi password
        ]);

        // 3. Kembali dengan pesan sukses
        return redirect()->route('admin.log_user')->with('success', 'User baru berhasil ditambahkan!');
    }
}