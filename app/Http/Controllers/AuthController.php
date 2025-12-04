<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // ------------------------
    // MEMBER SIGNUP
    // ------------------------
    public function showSignup()
    {
        return view('auth.signup');
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string',
            'id_number' => 'required|string|unique:members,id_number',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
        ]);

        try {
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
        } catch (\Exception $e) {
            return back()->withErrors(['signup' => 'An error occurred during signup. Please try again.'])->withInput();
        }
    }

    // ------------------------
    // MEMBER LOGIN
    // ------------------------
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = Member::where('email', $request->email)->first();
        if (! $user) {
            Log::info('Member login failed: no member with email '.$request->email);
        } else {
            Log::info('Member login attempt for id '.$user->id.'; password_check='.(Hash::check($request->password, $user->password) ? 'pass' : 'fail'));
        }

        if (Auth::guard('member')->attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('member.dashboard'));
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    // ------------------------
    // MEMBER LOGOUT
    // ------------------------
    public function logout(Request $request)
    {
        Auth::guard('member')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to homepage after logout
        return redirect()->route('home');
    }

    // ------------------------
    // ADMIN LOGIN
    // ------------------------
    public function showAdminLogin()
    {
        return view('auth.admin_login');
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {

            if (Auth::guard('admin')->user()->role != 'admin') {
                Auth::guard('admin')->logout();
                return back()->withErrors(['email' => 'Access denied.'])->withInput();
            }

            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid admin credentials'])->withInput();
    }

    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
