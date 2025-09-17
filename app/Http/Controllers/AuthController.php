<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegistrationForm() {
        $departments = Department::all();
        return view('auth.register-page', compact('departments'));
    }

    public function showLoginForm() {
        return view('auth.login-page');
    }
    public function register(Request $request) {
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'department_id' => 'nullable|exists:departments,id',
    ]);

    // default requester
    $role = 'requester';

    if (str_ends_with($data['email'], '@mis.com')) {
        $role = 'mis';
    } elseif (str_ends_with($data['email'], '@staff.com')) {
        $role = 'staff';
    }

    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'profile_pic' => $request->file('profile_pic')
            ? $request->file('profile_pic')->store('profile_pics', 'public')
            : null,
        'role' => $role,
    ]);

    if ($user->role === 'mis') {
        Auth::login($user);
        return redirect()->route('admin.dashboard')
                         ->with('success', 'MIS registered successfully.');
    }

    Auth::login($user);
    return redirect()->route('dashboard')
                     ->with('success', 'User registered successfully.');
}


    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'mis') {
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
        // Ticket statistics
        $stats = [
            'total' => \App\Models\Ticket::count(),
            'pending' => \App\Models\Ticket::where('status', 'pending')->count(),
            'ongoing' => \App\Models\Ticket::where('status', 'ongoing')->count(),
            'resolved' => \App\Models\Ticket::where('status', 'resolved')->count(),
            'cancelled' => \App\Models\Ticket::where('status', 'cancelled')->count(),
            'high_priority' => \App\Models\Ticket::where('priority', '>', 7)->count(),
        ];

        // Department-based statistics
        $departmentStats = \App\Models\Department::withCount(['tickets' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }])->get();

        // Recent activity (last 10 tickets)
        $recentTickets = \App\Models\Ticket::with(['requester', 'department'])
            ->latest()
            ->take(10)
            ->get();

        // Average resolution time (in days) - SQLite compatible
        $avgResolutionTime = \App\Models\Ticket::where('status', 'resolved')
            ->whereNotNull('updated_at')
            ->selectRaw('AVG((julianday(updated_at) - julianday(created_at))) as avg_days')
            ->first()
            ->avg_days ?? 0;

        // Monthly ticket trends - SQLite compatible
        $monthlyTrends = \App\Models\Ticket::selectRaw('strftime("%m", created_at) as month, COUNT(*) as count')
            ->whereRaw('strftime("%Y", created_at) = ?', [date('Y')])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('mis.dashboard', compact('stats', 'departmentStats', 'recentTickets', 'avgResolutionTime', 'monthlyTrends'));
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Logged out successfully.');
    }

}
