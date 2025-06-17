<?php

namespace App\Http\Controllers;

use alert;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    // LOGIN
    public function authenticating(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ], [
            'username.required' => 'Username harus diisi!',
            'username.username' => 'Format username harus benar',
            'password.required' => 'Password harus diisi!',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            toast('Username tidak ditemukan', 'error')->position('top-end')->autoClose(3000)->width('fit-content');
            return back()->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            toast('Password salah', 'error')->position('top-end')->autoClose(3000)->width('fit-content');
            return back()->withInput();
        }

        Auth::login($user);
        $request->session()->regenerate();

        alert()->success('Berhasil Login', 'Selamat datang di Sistem Keuangan Erlina Konveksi');

        return redirect()->intended('dashboard');
    }

    // LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
