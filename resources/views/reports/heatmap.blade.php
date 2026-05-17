@extends('layouts.app')

@section('header_title', 'Security Heatmap')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-50 flex flex-wrap justify-between items-center gap-4">
            <div>
                <h3 class="text-xl font-black text-slate-800 tracking-tight">Neighborhood Danger Zones</h3>
                <p class="text-sm text-slate-500 font-medium">Visualizing incident density to identify unsafe areas.</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-4">
                <!-- Style Switcher Buttons -->
                <div class="flex gap-1 bg-slate-100 p-1 rounded-xl text-xs font-bold border border-slate-200/50 shadow-sm">
                    <button type="button" onclick="switchMapStyle('streets-v2', this)" class="map-style-btn px-3 py-1.5 rounded-lg transition bg-white text-slate-900 shadow-sm cursor-pointer">
                        🗺️ Streets
                    </button>
                    <button type="button" onclick="switchMapStyle('outdoor-v2', this)" class="map-style-btn px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-950 transition cursor-pointer">
                        🏔️ 3D Terrain
                    </button>
                    <button type="button" onclick="switchMapStyle('hybrid', this)" class="map-style-btn px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-950 transition cursor-pointer">
                        🛰️ Satellite
                    </button>
                </div>

                <div class="flex gap-2">
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-bold border border-red-100">
                        <span class="w-2.5 h-2.5 bg-red-500 rounded-full animate-pulse"></span>
                        High Risk
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg text-xs font-bold border border-amber-100">
                        <span class="w-2.5 h-2.5 bg-amber-500 rounded-full"></span>
                        Medium Risk
                    </div>
                </div>
            </div>
        </div>

        <div id="heatmap" class="h-[600px] w-full bg-slate-100"></div>
    </div>

    <!-- Legend & Analytics Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-slate-900 rounded-2xl p-6 text-white shadow-lg">
            <h4 class="font-black text-sm uppercase tracking-widest text-slate-400 mb-4">Map Controls</h4>
            <p class="text-xs text-slate-400 leading-relaxed">
                The heatmap intensity represents the frequency of incidents in a specific radius. Red zones indicate multiple reports in close proximity.
            </p>
            <div class="mt-4 space-y-2">
                <div class="flex justify-between text-[10px] font-bold">
                    <span>LOW DENSITY</span>
                    <span>HIGH DENSITY</span>
                </div>
                <div class="h-2 w-full bg-gradient-to-r from-blue-500 via-yellow-500 to-red-500 rounded-full"></div>
            </div>
        </div>
        
        <div class="md:col-span-2 bg-white rounded-2xl p-6 border border-slate-100 shadow-md">
            <h4 class="font-bold text-slate-800 mb-3">Safety Recommendation</h4>
            <p class="text-sm text-slate-600 leading-relaxed">
                Based on current data, avoid walking alone in the "Red Hot" areas during late hours. Security patrols have been prioritized for these high-density zones.
            </p>
            <div class="mt-4 flex gap-4">
                <div class="flex-1 bg-slate-50 p-3 rounded-xl">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-tighter">Total Data Points</span>
                    <span class="text-2xl font-black text-slate-800">{{ count($reports) }}</span>
                </div>
                <div class="flex-1 bg-slate-50 p-3 rounded-xl">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-tighter">Active Hotspots</span>
                    <span class="text-2xl font-black text-red-600">Calculated Live</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .leaflet-container {
        font-family: inherit;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
<script>
    // Make boundaryLayer accessible outside block scope so layers can be ordered correctly
    let boundaryLayer;

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize map centered on the user's neighborhood coordinates
        let center = [{{ $neighborhoodLat }}, {{ $neighborhoodLng }}];
        
        const map = L.map('heatmap').setView(center, 14);

        const maptilerKey = '{{ config("services.maptiler.key") }}';

        // Define beautiful MapTiler base layers
        const baseLayers = {
            'streets-v2': L.tileLayer('https://api.maptiler.com/maps/streets-v2/{z}/{x}/{y}.png?key=' + maptilerKey, {
                attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> &copy; OpenStreetMap contributors',
                maxZoom: 20
            }),
            'outdoor-v2': L.tileLayer('https://api.maptiler.com/maps/outdoor-v2/{z}/{x}/{y}.png?key=' + maptilerKey, {
                attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> &copy; OpenStreetMap contributors',
                maxZoom: 20
            }),
            'hybrid': L.tileLayer('https://api.maptiler.com/maps/hybrid/{z}/{x}/{y}.jpg?key=' + maptilerKey, {
                attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> &copy; OpenStreetMap contributors',
                maxZoom: 20
            })
        };

        // Add streets layer as default base map
        let currentLayer = baseLayers['streets-v2'].addTo(map);

        // Leaflet base switcher function
        window.switchMapStyle = function(styleName, btnElement) {
            if (!baseLayers[styleName]) return;
            
            // Swap base layers safely
            map.removeLayer(currentLayer);
            currentLayer = baseLayers[styleName].addTo(map);

            // Re-order vector layers (like GeoJSON geofence) if present
            if (boundaryLayer) {
                boundaryLayer.bringToFront();
            }

            // Update active button styles
            document.querySelectorAll('.map-style-btn').forEach(function(btn) {
                btn.className = 'map-style-btn px-3 py-1.5 rounded-lg text-slate-500 hover:text-slate-950 transition cursor-pointer';
            });
            btnElement.className = 'map-style-btn px-3 py-1.5 rounded-lg transition bg-white text-slate-900 shadow-sm cursor-pointer';
        };

        // Draw matching glowing neighborhood boundary polygon if defined
        @if($neighborhoodBoundary)
            try {
                var boundaryGeojson = {!! $neighborhoodBoundary !!};
                
                // Draw colored overlay & glowing outline (matching resident MapLibre styles)
                boundaryLayer = L.geoJSON(boundaryGeojson, {
                    style: {
                        color: '#6366f1',      // Glowing Neon Indigo Outline
                        weight: 4,             // Thick border
                        opacity: 1,
                        fillColor: '#4f46e5',  // Semi-transparent Royal Indigo Fill
                        fillOpacity: 0.22      // Opacity
                    }
                }).addTo(map);
                
                // Auto fit viewport bounds perfectly to contain the boundary shape
                map.fitBounds(boundaryLayer.getBounds(), { padding: [30, 30] });
            } catch (err) {
                console.error("Error loading Leaflet geofence boundary:", err);
            }
        @endif

        // Prepare Heat Data
        const heatData = [
            @foreach($reports as $report)
                [{{ $report->latitude }}, {{ $report->longitude }}, 0.8], // [lat, lng, intensity]
            @endforeach
        ];

        // Add Heat Layer
        const heat = L.heatLayer(heatData, {
            radius: 25,
            blur: 15,
            maxZoom: 17,
            gradient: {
                0.2: 'blue',
                0.4: 'cyan',
                0.6: 'lime',
                0.8: 'yellow',
                1.0: 'red'
            }
        }).addTo(map);

        // Add optional markers for individual incidents
        @foreach($reports as $report)
            L.circleMarker([{{ $report->latitude }}, {{ $report->longitude }}], {
                radius: 4,
                fillColor: "{{ $report->priority == 'critical' ? '#ef4444' : '#f59e0b' }}",
                color: "#fff",
                weight: 1,
                opacity: 1,
                fillOpacity: 0.8
            }).bindPopup("<b>{{ $report->title }}</b><br>Priority: {{ $report->priority }}")
            .addTo(map);
        @endforeach
    });
</script>
@endpush
@endsection
