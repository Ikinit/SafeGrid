<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeGrid</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-blue-600">SafeGrid</h1>
        <div class="flex gap-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">Login</a>
            <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-gray-900">Register</a>
        </div>
    </nav>

    <div class="flex flex-col items-center justify-center min-h-screen text-center px-4">

        <h2 class="text-4xl font-bold text-gray-800 mb-4">Welcome to SafeGrid</h2>
        <p class="text-gray-500 text-lg mb-8">
            A disaster preparedness planner for Filipino families.
        </p>

    </div>

</body>
</html>