<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegistrationForm() {
        return view('auth.register-page');
    }

    public function showLoginForm() {
        return view('auth.login-page');
    }
    public function register(Request $request) {
        $user = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => 'in:user,admin',
        ]);

        $role = 'user';

        if (str_ends_with($user['email'], '@admin.com')) {
            $role = 'admin';
        }

        $user = User::create([
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => Hash::make($user['password']),
            'profile_pic' => $request->file('profile_pic') ? $request->file('profile_pic')->store('profile_pics', 'public') : null,
            'role' => $role,
        ]);

        if (!$user) {
            return back()->withErrors(['registration' => 'Registration failed. Please try again.'])->withInput();
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin-dashboard')->with('success', 'Admin registered successfully.');
        }

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'User registered successfully.');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Logged in successfully as admin.');
            }

            return redirect()->route('dashboard')->with('success', 'Logged in successfully.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function dashboard() {
        return view('auth.dashboard');
    }

    public function adminDashboard() {
        return view('admin.dashboard');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Logged out successfully.');
    }

}
