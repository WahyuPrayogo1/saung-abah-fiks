<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminController extends Controller
{
    // Menampilkan halaman login admin
    public function showLogin()
    {
        return view('admin.login'); // Ganti dengan path view login yang sesuai
    }

    // Proses login admin
    public function login(Request $request)
    {
        // Validasi email dan password
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek apakah user yang login adalah admin
        $admin = User::where('email', $credentials['email'])->first();

        if ($admin && $admin->is_admin && Auth::attempt($credentials)) {
            // Jika login berhasil dan user adalah admin
            return redirect()->route('dashboard')->with('success', 'Login berhasil sebagai Admin!');
        }

        // Jika login gagal (salah email atau password)
        return back()->withErrors(['email' => 'Email atau Password salah'])->withInput();
    }

    // Logout Admin
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }
}
