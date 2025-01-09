<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            if(Auth::user()->username == "admin") {
                return view('error.maintenance');
            }
            return redirect()->intended('/dashboard')->with('success', 'Login berhasil!');
        } else {
            return back()->withErrors([
                'login' => 'Username atau password salah.',
            ])->onlyInput('username');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Logout berhasil!');
    }
}
