<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'nis' => ['required', 'integer', 'min:1', 'max:4294967295'],
        ]);

        $user = User::find($credentials['nis']);

        if (! $user) {
            return back()->withErrors(['nis' => 'NIS tidak terdaftar.'])->onlyInput('nis');
        }

        $request->session()->regenerate();
        $request->session()->put('user_id', $user->nis);

        return redirect()->intended(route('home'))->with('success', 'Login berhasil.');
    }

    public function destroy(Request $request)
    {
        $request->session()->forget('user_id');
        $request->session()->regenerateToken();
        $request->session()->invalidate();

        return redirect()->route('home')->with('success', 'Anda telah logout.');
    }
}
