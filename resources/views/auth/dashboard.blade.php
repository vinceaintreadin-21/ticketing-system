<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center font-sans">

    <div class="w-full max-w-md bg-white shadow-lg rounded-2xl p-6">
        <h1 class="text-2xl font-bold text-gray-800 text-center mb-4">
            Welcome to your Dashboard
        </h1>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-4">
                <p class="bg-green-100 text-green-800 px-4 py-2 rounded-md text-sm font-medium">
                    {{ session('success') }}
                </p>
            </div>
        @endif

        <p class="text-gray-700 mb-2">
            You are logged in as:
            <span class="font-semibold text-blue-600">{{ Auth::user()->name }}</span>
        </p>

        @if (Auth::user()->role === 'admin')
            <p class="text-sm text-purple-600 font-medium mb-4">You're an admin.</p>
        @else
            <p class="text-sm text-gray-600 font-medium mb-4">You're a regular user.</p>
        @endif

        <form action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button
                type="submit"
                class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
            >
                Logout
            </button>
        </form>
    </div>

</body>
</html>
