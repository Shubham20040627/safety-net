@extends('layouts.app')

@section('header_title', 'All Incident Reports')

@section('content')
<div class="space-y-6">
    <!-- Filters and Search -->
    <div class="bg-white p-4 rounded-xl shadow-md border border-gray-100">
        <form action="{{ route('reports.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by location..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                </div>
            </div>

            <div class="w-full md:w-48">
                <select name="type" onchange="this.form.submit()" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    <option value="">All Types</option>
                    <option value="crime" {{ request('type') == 'crime' ? 'selected' : '' }}>Crime</option>
                    <option value="accident" {{ request('type') == 'accident' ? 'selected' : '' }}>Accident</option>
                    <option value="suspicious" {{ request('type') == 'suspicious' ? 'selected' : '' }}>Suspicious</option>
                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">
                Filter
            </button>
            
            @if(request()->has('search') || request()->has('type'))
                <a href="{{ route('reports.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 font-medium underline">Clear filters</a>
            @endif
        </form>
    </div>

    <!-- Reports Grid -->
    @if($reports->isEmpty())
        <div class="bg-white p-20 rounded-xl shadow-md text-center">
            <div class="mb-4 flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">No reports available</h3>
            <p class="text-gray-500 mt-2">No incidents have been reported in this category or location.</p>
            <a href="{{ route('reports.create') }}" class="mt-6 inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition shadow-md">
                Report an Incident
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($reports as $report)
                <div class="bg-white rounded-xl shadow-md hover:shadow-2xl transition duration-300 overflow-hidden border border-gray-100 flex flex-col">
                    @if($report->image)
                        <img src="{{ asset('storage/' . $report->image) }}" alt="{{ $report->title }}" class="h-48 w-full object-cover">
                    @else
                        <div class="h-48 bg-gray-100 flex items-center justify-center text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif

                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider
                                {{ $report->type == 'crime' ? 'bg-red-100 text-red-600' : '' }}
                                {{ $report->type == 'accident' ? 'bg-yellow-100 text-yellow-600' : '' }}
                                {{ $report->type == 'suspicious' ? 'bg-purple-100 text-purple-600' : '' }}
                                {{ $report->type == 'other' ? 'bg-gray-100 text-gray-600' : '' }}">
                                {{ $report->type }}
                            </span>
                            <span class="text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider 
                                {{ $report->status == 'resolved' ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-600' }}">
                                {{ $report->status }}
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-gray-800 line-clamp-1 mb-2">{{ $report->title }}</h3>
                        <p class="text-sm text-gray-600 line-clamp-3 mb-4">{{ $report->description }}</p>

                        <div class="mt-auto space-y-2 border-t pt-4">
                            <div class="flex items-center gap-2 text-xs text-gray-500 font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                {{ $report->location }}
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-500 font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ \Carbon\Carbon::parse($report->datetime)->format('M d, Y - h:i A') }}
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-400">
                                Reported by: <span class="font-bold text-gray-600">{{ $report->user->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $reports->links() }}
        </div>
    @endif
</div>
@endsection
