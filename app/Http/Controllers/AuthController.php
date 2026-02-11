<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Tampilkan Halaman Login
    public function showLoginForm()
    {
        return view('login');// Pastikan file blade loginmu ada di folder resources/views/auth/login.blade.php
    }

    // 2. Proses Login (Saat tombol ditekan)
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek ke Database
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Cek Role dan Redirect ke Dashboard yang sesuai
            $role = Auth::user()->role;

            if ($role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($role === 'it_support') {
                return redirect()->intended('/it/dashboard');
            } else {
                return redirect()->intended('/user/dashboard');
            }
        }

        // Kalau gagal, balik lagi dengan error
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // 3. Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}