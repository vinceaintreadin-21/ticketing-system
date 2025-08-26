<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center font-sans">

    <div class="w-full max-w-md bg-white shadow-lg rounded-2xl p-6">
        <h1 class="text-2xl font-bold text-gray-800 text-center mb-4">
            Admin Dashboard
        </h1>

        <p class="text-gray-700 mb-2">
            Welcome, <span class="font-semibold text-blue-600">{{ Auth::user()->name }}</span>!
        </p>

        <p class="text-sm text-purple-600 font-medium mb-4">
            Your role is: <span class="font-semibold">{{ Auth::user()->role }}</span>
        </p>

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
