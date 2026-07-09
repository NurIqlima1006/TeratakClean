<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TeratakClean | Housekeeping Management System</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#FAFAF7]">

<div class="flex h-screen">

    <!-- Sidebar -->
    <div class="w-64 flex flex-col shadow-md"
         style="background:#F8F6E4;">

        <!-- Logo -->
        <div class="flex justify-center items-center py-6 border-b"
             style="border-color:#DDD5BE;">

            <img src="{{ asset('images/logo.png') }}"
                 alt="Teratak Sofea"
                 class="h-16 w-auto">

        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-5 overflow-y-auto">

            {{-- ================= ADMIN ================= --}}

            @if(auth()->user()->role === 'admin')

                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-4 py-3 mb-2 rounded-lg font-medium transition-all duration-200
                   {{ request()->routeIs('admin.dashboard')
                        ? 'bg-[#B18457] text-white shadow'
                        : 'text-[#5A4632] hover:bg-[#ECE8D5]' }}">

                    <i class="fas fa-chart-column w-5 mr-3"></i>
                    Dashboard

                </a>

                <a href="{{ route('staff.index') }}"
                   class="flex items-center px-4 py-3 mb-2 rounded-lg font-medium transition-all duration-200
                   {{ request()->routeIs('staff.*')
                        ? 'bg-[#B18457] text-white shadow'
                        : 'text-[#5A4632] hover:bg-[#ECE8D5]' }}">

                    <i class="fas fa-users w-5 mr-3"></i>
                    Staff

                </a>

                <a href="{{ route('units.index') }}"
                   class="flex items-center px-4 py-3 mb-2 rounded-lg font-medium transition-all duration-200
                   {{ request()->routeIs('units.*')
                        ? 'bg-[#B18457] text-white shadow'
                        : 'text-[#5A4632] hover:bg-[#ECE8D5]' }}">

                    <i class="fas fa-house w-5 mr-3"></i>
                    Units

                </a>

                <a href="{{ route('housekeeping.checkouts') }}"
                   class="flex items-center px-4 py-3 mb-2 rounded-lg font-medium transition-all duration-200
                   {{ request()->routeIs('housekeeping.*')
                        ? 'bg-[#B18457] text-white shadow'
                        : 'text-[#5A4632] hover:bg-[#ECE8D5]' }}">

                    <i class="fas fa-calendar-check w-5 mr-3"></i>
                    Housekeeping

                </a>

                <a href="{{ route('tasks.assignment') }}"
                   class="flex items-center px-4 py-3 mb-2 rounded-lg font-medium transition-all duration-200
                   {{ request()->routeIs('tasks.*')
                        ? 'bg-[#B18457] text-white shadow'
                        : 'text-[#5A4632] hover:bg-[#ECE8D5]' }}">

                    <i class="fas fa-clipboard-list w-5 mr-3"></i>
                    Tasks

                </a>

            {{-- ================= OWNER ================= --}}
@elseif(auth()->user()->role === 'owner')

<a href="{{ route('owner.dashboard') }}"
   class="flex items-center px-4 py-3 mb-2 rounded-lg font-medium transition-all duration-200
   {{ request()->routeIs('owner.dashboard')
        ? 'bg-[#B18457] text-white shadow'
        : 'text-[#5A4632] hover:bg-[#ECE8D5]' }}">
    <i class="fas fa-chart-column w-5 mr-3"></i>
    Dashboard
</a>

<a href="{{ route('owner.units') }}"
   class="flex items-center px-4 py-3 mb-2 rounded-lg font-medium transition-all duration-200
   {{ request()->routeIs('owner.units')
        ? 'bg-[#B18457] text-white shadow'
        : 'text-[#5A4632] hover:bg-[#ECE8D5]' }}">
    <i class="fas fa-house w-5 mr-3"></i>
    Units
</a>

<a href="{{ route('owner.tasks') }}"
   class="flex items-center px-4 py-3 mb-2 rounded-lg font-medium transition-all duration-200
   {{ request()->routeIs('owner.tasks')
        ? 'bg-[#B18457] text-white shadow'
        : 'text-[#5A4632] hover:bg-[#ECE8D5]' }}">
    <i class="fas fa-clipboard-list w-5 mr-3"></i>
    Tasks
</a>

{{-- ================= STAFF / HANDYMAN / GARDENER ================= --}}
@elseif(in_array(auth()->user()->role, ['staff', 'handyman', 'gardener']))

<a href="{{ route('staff.dashboard') }}"
   class="flex items-center px-4 py-3 mb-2 rounded-lg font-medium transition-all duration-200
   {{ request()->routeIs('staff.dashboard')
        ? 'bg-[#B18457] text-white shadow'
        : 'text-[#5A4632] hover:bg-[#ECE8D5]' }}">
    <i class="fas fa-chart-column w-5 mr-3"></i>
    Dashboard
</a>

<a href="{{ route('staff.tasks') }}"
   class="flex items-center px-4 py-3 mb-2 rounded-lg font-medium transition-all duration-200
   {{ request()->routeIs('staff.tasks*')
        ? 'bg-[#B18457] text-white shadow'
        : 'text-[#5A4632] hover:bg-[#ECE8D5]' }}">

    @if(auth()->user()->role == 'staff')
        <i class="fas fa-broom w-5 mr-3"></i>
    @elseif(auth()->user()->role == 'handyman')
        <i class="fas fa-screwdriver-wrench w-5 mr-3"></i>
    @else
        <i class="fas fa-seedling w-5 mr-3"></i>
    @endif

    My Tasks
</a>

<a href="{{ route('staff.history') }}"
   class="flex items-center px-4 py-3 mb-2 rounded-lg font-medium transition-all duration-200
   {{ request()->routeIs('staff.history')
        ? 'bg-[#B18457] text-white shadow'
        : 'text-[#5A4632] hover:bg-[#ECE8D5]' }}">
    <i class="fas fa-clock-rotate-left w-5 mr-3"></i>
    Task History
</a>

@endif

</nav>

<!-- User -->
<div class="border-t p-4" style="border-color:#DDD5BE;">

    <div class="mb-5">
        <p class="font-bold text-[#5A4632]">
            {{ auth()->user()->name ?? auth()->user()->code }}
        </p>

        <p class="text-sm text-[#8A7356]">
            {{ ucfirst(auth()->user()->role) }}
        </p>
    </div>

    <a href="{{ route('profile.edit') }}"
       class="flex items-center px-4 py-2 rounded-lg text-[#5A4632] hover:bg-[#ECE8D5] transition mb-2">
        <i class="fas fa-user mr-3"></i>
        My Profile
    </a>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit"
            class="flex items-center w-full px-4 py-2 rounded-lg text-[#5A4632] hover:bg-[#ECE8D5] transition">

            <i class="fas fa-right-from-bracket mr-3"></i>
            Logout

        </button>
    </form>

</div>

    </div>

    <!-- Main -->
<div class="flex-1 flex flex-col overflow-hidden">

    <!-- Top Bar -->
    <div class="bg-white border-b border-[#E5E7EB] shadow-sm">

        <div class="px-8 py-5 flex items-center justify-between">

            <h1 class="text-2xl font-bold text-[#5A4632]">

                @if(request()->routeIs('admin.dashboard'))
                    Admin Dashboard

                @elseif(request()->routeIs('owner.dashboard'))
                    Owner Dashboard

                @elseif(request()->routeIs('staff.dashboard'))
                    Staff Dashboard

                @elseif(request()->routeIs('staff.tasks*'))
                    My Tasks

                @elseif(request()->routeIs('staff.history'))
                    Task History

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

            <div class="text-right">

                <p class="text-sm text-gray-500">
                    {{ now()->format('l') }}
                </p>

                <p class="font-semibold text-[#5A4632]">
                    {{ now()->format('d F Y') }}
                </p>

            </div>

        </div>

    </div>

    <!-- Page Content -->
    <div class="flex-1 overflow-y-auto bg-[#FAFAF7] p-6">

        {{ $slot }}

    </div>

</div>

</div>

</body>
</html>