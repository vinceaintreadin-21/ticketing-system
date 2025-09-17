<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
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

    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Dashboard</h1>
        <p class="text-gray-700 mb-2">Welcome, {{ Auth::user()->name }}!</p>
        
        @if (Auth::user()->tickets->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach (Auth::user()->tickets as $ticket)
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-xl font-semibold mb-2">{{ $ticket->title }}</h2>
                    </div>
                @endforeach
            </div>
        @else
            <form action="{{ route('create-ticket') }}" method="GET">
                @csrf
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Ticket</button>
            </form>
        @endif
    </main>
</body>
</html>
