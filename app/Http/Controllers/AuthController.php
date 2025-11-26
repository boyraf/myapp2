<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ------------------------
    // MEMBER SIGNUP
    // ------------------------
    public function showSignup()
    {
        return view('auth.signup'); // <- uses resources/views/auth/signup.blade.php
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'password' => 'required|string|min:6|confirmed', // password + password_confirmation
            'phone' => 'required|string',
            'id_number' => 'required|string',
        ]);

        Member::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'id_number' => $request->id_number,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'membership_date' => now(),
            'status' => 'active',
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Account created! Please login.');
    }

    // ------------------------
    // MEMBER LOGIN
    // ------------------------
    public function showLogin()
    {
        return view('auth.login'); // <- resources/views/auth/login.blade.php
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('member')->attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return redirect()->route('member.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('member')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // ------------------------
    // ADMIN LOGIN
    // ------------------------
    public function showAdminLogin()
    {
        return view('auth.admin_login'); // <- resources/views/auth/admin_login.blade.php
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {

            //Check role 
            if (Auth::guard('admin')->user()->role != 'admin') {
                Auth::guard('admin')->logout();
                return back()->withErrors(['email' => 'Access denied.'])
                             ->withInput();
            }

            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid admin credentials'])
                     ->withInput();
    }

    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
