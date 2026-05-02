<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Neighborhood Safety') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Tailwind CSS (via CDN) -->
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
        </style>
    </head>
    <body class="bg-gray-100 text-gray-900">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <div class="w-64 bg-indigo-700 text-white flex-shrink-0 shadow-xl">
                <div class="p-6">
                    <h1 class="text-2xl font-bold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        SafetyNet
                    </h1>
                </div>
                
                <nav class="mt-6 px-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-indigo-600 transition mb-2 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 shadow-md' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a11 11 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('reports.index') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-indigo-600 transition mb-2 {{ request()->routeIs('reports.index') ? 'bg-indigo-600 shadow-md' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        All Reports
                    </a>

                    <a href="{{ route('reports.create') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-indigo-600 transition mb-2 {{ request()->routeIs('reports.create') ? 'bg-indigo-600 shadow-md' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Report Incident
                    </a>

                    <a href="{{ route('reports.my-reports') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-indigo-600 transition mb-2 {{ request()->routeIs('reports.my-reports') ? 'bg-indigo-600 shadow-md' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        My Reports
                    </a>

                    @if(auth()->user()->role === 'admin')
                        <div class="mt-8 mb-2 px-3 text-xs font-semibold text-indigo-300 uppercase tracking-wider">
                            Admin Panel
                        </div>
                        <a href="{{ route('admin.users') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-indigo-600 transition mb-2 {{ request()->routeIs('admin.users') ? 'bg-indigo-600 shadow-md' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Manage Users
                        </a>
                        <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-indigo-600 transition mb-2 {{ request()->routeIs('admin.reports') ? 'bg-indigo-600 shadow-md' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Manage Reports
                        </a>
                    @endif
                </nav>
            </div>

            <!-- Content Area -->
            <div class="flex-1 flex flex-col">
                <!-- Top Navbar -->
                <header class="bg-white shadow-sm border-b h-16 flex items-center justify-between px-8">
                    <div class="text-xl font-medium text-gray-700">
                        @yield('header_title', 'Dashboard')
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600 font-medium">Hello, {{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-700 flex items-center gap-1 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </header>

                <!-- Flash Messages -->
                <div class="px-8 mt-6">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-200 text-green-700 p-4 rounded-xl shadow-sm flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-200 text-red-700 p-4 rounded-xl shadow-sm flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            {{ session('error') }}
                        </div>
                    @endif
                </div>

                <!-- Main Content -->
                <main class="p-8 flex-1">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
