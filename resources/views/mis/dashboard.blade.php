<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen font-sans">
    <nav class="flex justify-between items-center p-4 bg-white shadow-sm">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('/storage/logo/lvcclogo.png') }}" alt="Logo" class="w-10 h-10">
            <h1 class="text-xl font-semibold">iServe LVCC</h1>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="text-blue-500 hover:text-blue-700 bg-transparent border-none cursor-pointer">Logout</button>
        </form>
    </nav>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Admin Dashboard</h1>
        
        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2 text-gray-700">Total Tickets</h2>
                <p class="text-3xl font-bold text-blue-600">{{ $stats['total'] ?? 0 }}</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2 text-gray-700">Pending Tickets</h2>
                <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] ?? 0 }}</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2 text-gray-700">Ongoing Tickets</h2>
                <p class="text-3xl font-bold text-orange-600">{{ $stats['ongoing'] ?? 0 }}</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2 text-gray-700">Resolved Tickets</h2>
                <p class="text-3xl font-bold text-green-600">{{ $stats['resolved'] ?? 0 }}</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2 text-gray-700">Cancelled Tickets</h2>
                <p class="text-3xl font-bold text-red-600">{{ $stats['cancelled'] ?? 0 }}</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2 text-gray-700">High Priority</h2>
                <p class="text-3xl font-bold text-purple-600">{{ $stats['high_priority'] ?? 0 }}</p>
            </div>
        </div>
        
        @if($stats['total'] == 0)
            <div class="bg-white p-8 rounded-lg shadow-md text-center">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No Tickets Found</h3>
                <p class="text-gray-500">There are currently no tickets in the system. Tickets will appear here once they are created.</p>
            </div>
        @else
            <!-- Recent Tickets Section -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Recent Tickets</h2>
                @if($recentTickets->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requester</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentTickets as $ticket)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $ticket->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ticket->title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ticket->requester->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ticket->department->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($ticket->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($ticket->status === 'ongoing') bg-blue-100 text-blue-800
                                                @elseif($ticket->status === 'resolved') bg-green-100 text-green-800
                                                @elseif($ticket->status === 'cancelled') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($ticket->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ticket->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No recent tickets to display.</p>
                @endif
            </div>
        @endif
    </div>
</body>
</html>
