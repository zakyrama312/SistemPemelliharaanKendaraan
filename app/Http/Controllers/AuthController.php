<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ],[
            'username.required' => 'Username harus diisi.',
            'password.required' => 'Password harus diisi.',
        ]);

        if (Auth::attempt($credentials)) {
            return Auth::user()->role == 'admin'
                ? redirect()->route('dashboard.index')
                : redirect()->route('kendaraan.index');
        }

        return back()->withErrors(['login' => 'Username atau password salah.'])
        ->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // Step 1: Form input username
    public function formUsername() {
        return view('auth.lupa-password');
    }

    // Step 1b: Cek username, redirect ke halaman buat password
    public function checkUsername(Request $request) {
        $request->validate([
            'username' => 'required|exists:users,username',
        ],[
            'username.required' => 'Username harus diisi.',
            'username.exists' => 'Username tidak ditemukan.',
        ]);

        return redirect()->route('reset.password.form', $request->username);
    }

    // Step 2: Form reset password
    public function formResetPassword($username) {
        return view('auth.reset-password', compact('username'));
    }

    // Step 2b: Submit password baru
    public function submitResetPassword(Request $request) {
        $request->validate([
            'username' => 'required|exists:users,username',
            'password' => 'required|confirmed',
        ],[
            'username.required' => 'Username harus diisi.',
            'username.exists' => 'Username tidak ditemukan.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::where('username', $request->username)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('status', 'Password berhasil diubah. Silakan login.');
    }

}
