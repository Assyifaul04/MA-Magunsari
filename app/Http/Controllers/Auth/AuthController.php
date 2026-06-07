<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticated(Request $request)
    {
        // 1. Ubah validasi 'email' menjadi 'string' agar bisa menerima NIP (angka) atau Email
        $credentials = $request->validate([
            'email'    => ['required', 'string'], 
            'password' => ['required'],
        ]);
    
        // Auth::attempt otomatis akan mencocokkan input dengan kolom 'email' di database
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
    
            $user = Auth::user();
    
            if ($user->role === 'superAdmin') {
                return redirect()->intended('/superAdmin/dashboard');
            } elseif ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role === 'guru') {
                return redirect()->intended('/guru/dashboard');
            }
    
            return redirect()->route('login')->withErrors([
                'role' => 'Role tidak dikenali.'
            ]);
        }
    
        // 2. Sesuaikan pesan error agar pengguna tahu bisa pakai NIP
        return back()->withErrors([
            'email' => 'Email atau NIP / Password salah.',
        ])->onlyInput('email');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}