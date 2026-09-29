<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nis' => ['required', 'integer', 'min:1', 'max:4294967295', 'unique:users,nis'],
            'nama' => ['required', 'string', 'max:255'],
            'rombel' => ['required', 'string', 'max:50'],
        ]);

        $user = User::create([
            'nis' => $data['nis'],
            'nama' => $data['nama'],
            'rombel' => $data['rombel'],
        ]);

        $request->session()->regenerate();
        $request->session()->put('user_id', $user->nis);

        return redirect()->route('home')->with('success', 'Registrasi berhasil. Selamat datang di LaporBoss!');
    }
}
