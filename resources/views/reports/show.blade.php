@extends('layouts.app')

@section('header_title', 'Incident Details')

@section('content')
@push('styles')
    <link href="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.css" rel="stylesheet" />
    <style>
        #incident-map { height: 350px; border-radius: 0.75rem; }
    </style>
@endpush

<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Back Navigation & Status Bar -->
    <div class="flex flex-wrap items-center justify-between gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to Incident Reports
        </a>
        
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status:</span>
            <span class="text-xs font-extrabold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm
                {{ $report->status == 'resolved' ? 'bg-green-100 text-green-700 border border-green-200' : '' }}
                {{ $report->status == 'investigating' ? 'bg-indigo-100 text-indigo-700 border border-indigo-200' : '' }}
                {{ $report->status == 'pending' ? 'bg-amber-100 text-amber-700 border border-amber-200' : '' }}">
                {{ $report->status }}
            </span>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Incident Details & Description (2 columns on large screens) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <!-- Cover Image -->
                @if($report->image)
                    <div class="relative h-96 w-full bg-slate-900">
                        <img src="{{ asset('storage/' . $report->image) }}" alt="{{ $report->title }}" class="h-full w-full object-contain">
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-6 pt-20">
                            <span class="text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-md
                                {{ $report->type == 'crime' ? 'bg-red-500 text-white' : '' }}
                                {{ $report->type == 'accident' ? 'bg-amber-500 text-white' : '' }}
                                {{ $report->type == 'suspicious' ? 'bg-purple-500 text-white' : '' }}
                                {{ $report->type == 'other' ? 'bg-gray-500 text-white' : '' }}">
                                {{ $report->type }}
                            </span>
                        </div>
                    </div>
                @else
                    <div class="h-64 bg-slate-50 flex flex-col items-center justify-center text-gray-300 border-b border-gray-100 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-200 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm text-gray-400 font-semibold">No Image Uploaded</span>
                        <div class="absolute bottom-4 left-6">
                            <span class="text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm
                                {{ $report->type == 'crime' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $report->type == 'accident' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $report->type == 'suspicious' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $report->type == 'other' ? 'bg-gray-100 text-gray-700' : '' }}">
                                {{ $report->type }}
                            </span>
                        </div>
                    </div>
                @endif

                <!-- Content Body -->
                <div class="p-8">
                    <h1 class="text-3xl font-extrabold text-gray-900 leading-tight mb-4">
                        {{ $report->title }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-6 text-sm text-gray-500 pb-6 border-b border-gray-100 mb-6">
                        <div class="flex items-center gap-2">
                            <div class="h-8 w-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-xs uppercase">
                                {{ substr($report->user->name, 0, 2) }}
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Reported By</p>
                                <p class="font-bold text-gray-700">{{ $report->user->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="h-8 w-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Date & Time</p>
                                <p class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($report->datetime)->format('M d, Y - h:i A') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="h-8 w-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Location Area</p>
                                <p class="font-bold text-gray-700">{{ $report->location }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="prose max-w-none">
                        <h3 class="text-lg font-bold text-gray-800 mb-3">Incident Description</h3>
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line text-base">
                            {{ $report->description }}
                        </p>
                    </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Interactive Pin Map & Safety Advisories -->
        <div class="space-y-6">
            <!-- Dispatch / Volunteer Status Card -->
            <div class="bg-white p-5 rounded-2xl shadow-md border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Dispatch Details
                </h3>

                @if($report->responder)
                    <div class="flex items-center gap-3 bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <div class="h-10 w-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">
                            {{ substr($report->responder->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Assigned Responder</p>
                            <p class="font-bold text-gray-800">{{ $report->responder->name }}</p>
                        </div>
                    </div>

                    @if($report->status !== 'resolved' && (auth()->user()->role === 'admin' || auth()->id() === $report->responder_id))
                        <form action="{{ route('reports.resolve-assigned', $report) }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-bold transition shadow-lg shadow-indigo-500/20 active:scale-[0.98]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Resolve Incident
                            </button>
                        </form>
                    @endif
                @else
                    <div class="bg-amber-50 p-4 rounded-xl border border-amber-100 text-center">
                        <p class="text-sm text-amber-800 font-semibold">No Responder Dispatched Yet</p>
                        <p class="text-xs text-amber-600 mt-1">This report is pending response.</p>
                    </div>

                    @if(auth()->user()->role === 'responder')
                        <form action="{{ route('reports.volunteer', $report) }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-bold transition shadow-lg shadow-indigo-500/20 active:scale-[0.98]">
                                🙋 Volunteer to Respond
                            </button>
                        </form>
                    @endif
                @endif
            </div>

            <!-- Location Map Card -->
            <div class="bg-white p-5 rounded-2xl shadow-md border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    Geographic Location
                </h3>
                
                @if($report->latitude && $report->longitude)
                    <div id="incident-map" class="w-full border border-gray-200 mb-4"></div>
                    <div class="flex flex-col gap-3">
                        <div class="text-xs text-gray-400 font-semibold space-y-1">
                            <p>Latitude: <span class="text-gray-600 font-mono">{{ $report->latitude }}</span></p>
                            <p>Longitude: <span class="text-gray-600 font-mono">{{ $report->longitude }}</span></p>
                        </div>
                        
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $report->latitude }},{{ $report->longitude }}" target="_blank" class="w-full flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl font-bold transition shadow-lg shadow-emerald-500/20 active:scale-[0.98]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Navigate to Scene
                        </a>
                    </div>
                @else
                    <div class="h-48 bg-slate-50 flex flex-col items-center justify-center text-gray-400 rounded-lg border border-dashed border-gray-200 text-center p-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        </svg>
                        <p class="font-bold text-sm">No exact coordinates</p>
                        <p class="text-xs text-gray-400 mt-1">This report was logged using written location details only.</p>
                    </div>
                @endif
            </div>

            <!-- Community Advisory Info -->
            <div class="bg-indigo-50 p-6 rounded-2xl border border-indigo-100 shadow-sm">
                <h4 class="text-indigo-900 font-bold text-base mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    Safety Advisory
                </h4>
                <p class="text-xs text-indigo-700 leading-relaxed mb-4">
                    If you reside near <strong>{{ $report->location }}</strong>, please remain vigilant. Stay updated on resolution progress, and contact neighborhood authorities if you observe further suspicious activities.
                </p>
                <div class="text-xs font-semibold text-indigo-500">
                    Neighborhood Security Dispatch
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    @if($report->latitude && $report->longitude)
        <!-- Import MapLibre GL JS -->
        <script src="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var lat = parseFloat("{{ $report->latitude }}");
                var lng = parseFloat("{{ $report->longitude }}");

                var map = new maplibregl.Map({
                    container: 'incident-map',
                    style: 'https://api.maptiler.com/maps/streets-v2/style.json?key={{ config('services.maptiler.key') }}', 
                    center: [lng, lat],
                    zoom: 15,
                    pitch: 45,
                    bearing: -10
                });

                map.addControl(new maplibregl.NavigationControl());

                var typeColors = {
                    'crime': '#ef4444',
                    'accident': '#f59e0b',
                    'suspicious': '#8b5cf6',
                    'other': '#6b7280'
                };
                var markerColor = typeColors["{{ $report->type }}"] || '#6b7280';

                new maplibregl.Marker({ color: markerColor })
                    .setLngLat([lng, lat])
                    .addTo(map);
            });
        </script>
    @endif
@endpush
@endsection
