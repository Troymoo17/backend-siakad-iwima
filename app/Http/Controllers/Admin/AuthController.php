<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Session::has('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if (!$admin || !$admin->verifyPassword($request->password)) {
            return back()->withErrors(['login' => 'Username atau password salah.'])->withInput();
        }

        Session::put('admin_logged_in', true);
        Session::put('admin_id', $admin->id);
        Session::put('admin_nama', $admin->nama);
        Session::put('admin_role', $admin->role);
        Session::put('admin_username', $admin->username);

        return redirect()->route('admin.dashboard')->with('success', 'Selamat datang, ' . $admin->nama);
    }

    public function logout(Request $request)
    {
        Session::forget(['admin_logged_in', 'admin_id', 'admin_nama', 'admin_role', 'admin_username']);
        return redirect()->route('admin.login')->with('success', 'Berhasil logout.');
    }
}
