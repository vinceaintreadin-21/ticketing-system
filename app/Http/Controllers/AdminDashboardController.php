<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AdminDashboardController extends Controller
{
    use AuthorizesRequests;

    public function dashboard() {
        $this->authorize('viewAny', Ticket::class);

        $user = Auth::user();
        
        if ($user->role !== 'mis') {
            abort(403);
        }

        // Ticket statistics
        $stats = [
            'total' => Ticket::count(),
            'pending' => Ticket::where('status', 'pending')->count(),
            'ongoing' => Ticket::where('status', 'ongoing')->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
            'cancelled' => Ticket::where('status', 'cancelled')->count(),
            'high_priority' => Ticket::where('priority', '>', 7)->count(),
        ];

        // Department-based statistics
        $departmentStats = Department::withCount(['tickets' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }])->get();

        // Recent activity (last 10 tickets)
        $recentTickets = Ticket::with(['requester', 'department'])
            ->latest()
            ->take(10)
            ->get();

        // Average resolution time (in days) - SQLite compatible
        $avgResolutionTime = Ticket::where('status', 'resolved')
            ->whereNotNull('updated_at')
            ->selectRaw('AVG((julianday(updated_at) - julianday(created_at))) as avg_days')
            ->first()
            ->avg_days ?? 0;

        // Monthly ticket trends - SQLite compatible
        $monthlyTrends = Ticket::selectRaw('strftime("%m", created_at) as month, COUNT(*) as count')
            ->whereRaw('strftime("%Y", created_at) = ?', [date('Y')])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('mis.dashboard', compact('stats', 'departmentStats', 'recentTickets', 'avgResolutionTime', 'monthlyTrends'));
    }
}
