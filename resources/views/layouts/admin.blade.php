<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Velora Admin Panel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar-transition {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-green-800 text-white w-64 min-h-screen p-4 sidebar-transition">
            <div class="flex items-center mb-8">
                <i class="fas fa-map-marked-alt text-2xl mr-3"></i>
                <h1 class="text-xl font-bold">Velora Admin</h1>
            </div>
            
            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 rounded-lg hover:bg-green-700 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-green-700' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('admin.users') }}" class="flex items-center p-3 rounded-lg hover:bg-green-700 transition-colors {{ request()->routeIs('admin.users') ? 'bg-green-700' : '' }}">
                    <i class="fas fa-users mr-3"></i>
                    Users
                </a>
                <a href="{{ route('admin.sites') }}" class="flex items-center p-3 rounded-lg hover:bg-green-700 transition-colors {{ request()->routeIs('admin.sites') ? 'bg-green-700' : '' }}">
                    <i class="fas fa-map-marker-alt mr-3"></i>
                    Sites
                </a>
                <a href="{{ route('admin.guides') }}" class="flex items-center p-3 rounded-lg hover:bg-green-700 transition-colors {{ request()->routeIs('admin.guides') ? 'bg-green-700' : '' }}">
                    <i class="fas fa-user-tie mr-3"></i>
                    Guides
                </a>
                <a href="{{ route('admin.trips') }}" class="flex items-center p-3 rounded-lg hover:bg-green-700 transition-colors {{ request()->routeIs('admin.trips') ? 'bg-green-700' : '' }}">
                    <i class="fas fa-route mr-3"></i>
                    Trips
                </a>
                <a href="{{ route('admin.reviews') }}" class="flex items-center p-3 rounded-lg hover:bg-green-700 transition-colors {{ request()->routeIs('admin.reviews') ? 'bg-green-700' : '' }}">
                    <i class="fas fa-star mr-3"></i>
                    Reviews
                </a>
                <a href="{{ route('admin.bookings') }}" class="flex items-center p-3 rounded-lg hover:bg-green-700 transition-colors {{ request()->routeIs('admin.bookings') ? 'bg-green-700' : '' }}">
                    <i class="fas fa-calendar-check mr-3"></i>
                    Bookings
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">Welcome, Admin</span>
                        <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Add any JavaScript functionality here
    </script>
</body>
</html>

