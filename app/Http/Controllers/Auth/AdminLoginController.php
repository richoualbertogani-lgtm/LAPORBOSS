<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminLoginController extends Controller
{
    /**
     * Show the administrator sign-in form.
     */
    public function create()
    {
        return view('auth.admin-login');
    }

    /**
     * Verify administrator credentials and start an authenticated session.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'email_admin' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $admin = Admin::where('email_admin', $data['email_admin'])->first();

        if (! $admin || ! Hash::check($data['password'], $admin->password_admin)) {
            return back()
                ->withErrors(['email_admin' => 'Email atau password admin tidak sesuai.'])
                ->onlyInput('email_admin');
        }

        $request->session()->regenerate();
        $request->session()->put('admin_id', $admin->id_admin);

        return redirect()->route('admin.dashboard')->with('success', 'Login admin berhasil.');
    }

    /**
     * Clear the administrator session and return to the home page.
     */
    public function destroy(Request $request)
    {
        $request->session()->forget('admin_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Admin telah logout.');
    }
}
