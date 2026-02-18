<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses tidak diizinkan.');
        }

        $users = User::latest()->get();

        return view('log_user', compact('users'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'username'  => 'required|string|unique:users,username|max:255',
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'division'  => 'required|string|max:255',
            'role'      => 'required|in:admin,it_support,user',
            'password'  => 'required|string|min:8',
        ]);

        User::create([
            'username'  => $request->username,
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'division'  => $request->division,
            'role'      => $request->role,
            'password'  => Hash::make($request->password), 
        ]);
        return redirect()->route('admin.log_user')->with('success', 'User baru berhasil ditambahkan!');
    }
}