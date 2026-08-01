<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-900">
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="w-full max-w-md rounded border border-gray-200 bg-white p-6 shadow-sm">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold">Admin Login</h1>
                <p class="mt-1 text-sm text-gray-500">Sign in to manage the store.</p>
            </div>

            @if (session('success'))
                <div class="mb-5 rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.login.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900" autofocus>
                </div>

                <div>
                    <label for="password" class="mb-1 block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:outline-none focus:ring-1 focus:ring-gray-900">
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" id="remember" name="remember" value="1" class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                    <label for="remember" class="text-sm font-medium text-gray-700">Remember me</label>
                </div>

                <button type="submit" class="w-full rounded bg-gray-900 px-5 py-2 text-sm font-medium text-white hover:bg-gray-800">
                    Login
                </button>
            </form>
        </div>
    </main>
</body>
</html>
