@extends('layouts.app')

@section('header_title', 'Dashboard Overview')

@section('content')
<div class="space-y-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 border-l-4 border-indigo-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Incidents</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalReports }}</h3>
                </div>
                <div class="p-3 bg-indigo-50 rounded-lg text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('reports.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">View all reports →</a>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">My Reports</p>
                    <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $myReports }}</h3>
                </div>
                <div class="p-3 bg-green-50 rounded-lg text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('reports.my-reports') }}" class="text-sm font-semibold text-green-600 hover:text-green-700">View my activity →</a>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 border-l-4 border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Account Status</p>
                    <h3 class="text-xl font-bold text-gray-800 mt-1 capitalize">{{ auth()->user()->status }}</h3>
                </div>
                <div class="p-3 bg-amber-50 rounded-lg text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-xs text-gray-400">Verified community member</span>
            </div>
        </div>
    </div>

    <!-- Recent Reports Preview -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">Recent Incidents</h3>
            <a href="{{ route('reports.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">See all</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($latestReports as $report)
                <div class="p-6 flex items-start gap-4 hover:bg-gray-50 transition">
                    <div class="h-12 w-12 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-gray-800">{{ $report->title }}</h4>
                            <span class="text-xs font-semibold px-2 py-1 rounded-full 
                                {{ $report->type == 'crime' ? 'bg-red-100 text-red-600' : '' }}
                                {{ $report->type == 'accident' ? 'bg-yellow-100 text-yellow-600' : '' }}
                                {{ $report->type == 'suspicious' ? 'bg-purple-100 text-purple-600' : '' }}
                                {{ $report->type == 'other' ? 'bg-gray-100 text-gray-600' : '' }}">
                                {{ ucfirst($report->type) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1 line-clamp-1">{{ $report->description }}</p>
                        <div class="mt-3 flex items-center gap-4 text-xs text-gray-400 font-medium">
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $report->location }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $report->datetime }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <p class="text-gray-500 text-lg">No reports available yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
