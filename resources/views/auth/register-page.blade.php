<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen font-sans">
    <nav class="flex justify-between items-center p-4 bg-white shadow-sm">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('/storage/logo/lvcclogo.png') }}" alt="Logo" class="w-10 h-10">
            <h1 class="text-xl font-semibold">iServe LVCC</h1>
        </div>
        <a href="{{route('login-form')}}" class="text-blue-500 hover:text-blue-700">Login</a>
    </nav>
    
    <main class="container mx-auto px-4 py-8 flex items-center justify-center min-h-screen bg-gray-100">
        <form method="POST" action="{{ route('register') }}" class="bg-white p-6 rounded-lg shadow-md w-80">
            @csrf
            <img src="{{ asset('/storage/logo/lvcclogo.png') }}" alt="Logo" class="w-[200px] h-[200px] mb-4 mx-auto block">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Login</h1>
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                <input type="name" id="name" name="name" required 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
            </div>
            <div class="mb-4" class="block text-sm font-medium text-gray-700">
                <label for="email" id="email" name="email">Email: </label>
                <input type="email" id="email" name="email" required 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
                <input type="password" id="password" name="password" required 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:outline-none">
            </div>
            <button type="submit" 
                    class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Login
            </button>
        </form>
    </main>
</body>
</html>
