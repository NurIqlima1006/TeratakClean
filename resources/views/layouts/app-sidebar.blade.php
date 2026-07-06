<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'TeratakClean') }}</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100">
        <div class="flex h-screen">
            <!-- Sidebar -->
            <div class="w-64 bg-gray-900 text-white flex flex-col">
                <!-- Logo -->
                <div class="p-6 border-b border-gray-700">
                    <h1 class="text-2xl font-bold">TeratakClean</h1>
                </div>

                <!-- Navigation Links -->
                <nav class="flex-1 px-4 py-6 overflow-y-auto">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 mb-2 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                            📊 Dashboard
                        </a>
                        <a href="{{ route('staff.index') }}" class="block px-4 py-3 mb-2 rounded {{ request()->routeIs('staff.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                            👥 Staff
                        </a>
                        <a href="{{ route('units.index') }}" class="block px-4 py-3 mb-2 rounded {{ request()->routeIs('units.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                            🏠 Units
                        </a>
                        <a href="{{ route('housekeeping.checkouts') }}" class="block px-4 py-3 mb-2 rounded {{ request()->routeIs('housekeeping.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                            📅 Housekeeping
                        </a>
                        <a href="{{ route('tasks.assignment') }}" class="block px-4 py-3 mb-2 rounded {{ request()->routeIs('tasks.*') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                            📋 Tasks
                        </a>
                    @elseif(auth()->user()->role === 'owner')
                        <a href="{{ route('owner.dashboard') }}" class="block px-4 py-3 mb-2 rounded {{ request()->routeIs('owner.dashboard') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                            📊 Dashboard
                        </a>
                    @elseif(auth()->user()->role === 'staff')
                        <a href="{{ route('staff.dashboard') }}" class="block px-4 py-3 mb-2 rounded {{ request()->routeIs('staff.dashboard') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                            🧹 My Tasks
                        </a>
                    @elseif(auth()->user()->role === 'handyman')
                        <a href="{{ route('staff.dashboard') }}" class="block px-4 py-3 mb-2 rounded {{ request()->routeIs('staff.dashboard') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                            🔧 My Tasks
                        </a>
                    @elseif(auth()->user()->role === 'gardener')
                        <a href="{{ route('staff.dashboard') }}" class="block px-4 py-3 mb-2 rounded {{ request()->routeIs('staff.dashboard') ? 'bg-blue-600' : 'hover:bg-gray-800' }}">
                            🌱 My Tasks
                        </a>
                    @endif
                </nav>

                <!-- User Section -->
                <div class="border-t border-gray-700 p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="font-semibold">{{ auth()->user()->name ?? auth()->user()->code }}</p>
                            <p class="text-xs text-gray-400">{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 rounded text-sm hover:bg-gray-800 mb-2">
                        👤 Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 rounded text-sm hover:bg-gray-800">
                            🚪 Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Bar -->
                <div class="bg-white shadow">
                    <div class="px-6 py-4">
                        <h1 class="text-2xl font-semibold text-gray-800">
                            @if(request()->routeIs('admin.dashboard'))
                                Admin Dashboard
                            @elseif(request()->routeIs('owner.dashboard'))
                                Owner Dashboard
                            @elseif(request()->routeIs('staff.dashboard'))
                                My Dashboard
                            @elseif(request()->routeIs('staff.*'))
                                Staff Management
                            @elseif(request()->routeIs('units.*'))
                                Units Management
                            @elseif(request()->routeIs('housekeeping.*'))
                                Housekeeping Schedule
                            @elseif(request()->routeIs('tasks.*'))
                                Task Assignment
                            @else
                                {{ config('app.name') }}
                            @endif
                        </h1>
                    </div>
                </div>

                <!-- Page Content -->
                <div class="flex-1 overflow-y-auto bg-gray-50">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>