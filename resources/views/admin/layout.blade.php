<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <nav class="bg-gray-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold">Admin Panel</a>
                        <a href="{{ route('admin.dashboard') }}" class="text-sm hover:text-gray-300 {{ request()->routeIs('admin.dashboard') ? 'text-white underline' : 'text-gray-400' }}">Dashboard</a>
                        <a href="{{ route('admin.users') }}" class="text-sm hover:text-gray-300 {{ request()->routeIs('admin.users*') ? 'text-white underline' : 'text-gray-400' }}">Users</a>
                        <a href="{{ route('admin.charts') }}" class="text-sm hover:text-gray-300 {{ request()->routeIs('admin.charts*') ? 'text-white underline' : 'text-gray-400' }}">Charts</a>
                        <a href="{{ route('admin.messages') }}" class="text-sm hover:text-gray-300 {{ request()->routeIs('admin.messages*') ? 'text-white underline' : 'text-gray-400' }}">Messages</a>
                        <a href="{{ route('admin.blog.index') }}" class="text-sm hover:text-gray-300 {{ request()->routeIs('admin.blog*') ? 'text-white underline' : 'text-gray-400' }}">Blog</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-400">{{ auth()->user()->email }}</span>
                        <a href="{{ route('welcome') }}" class="text-sm text-gray-400 hover:text-white">Back to site</a>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            @yield('content')
        </main>
    </div>
</body>
</html>
