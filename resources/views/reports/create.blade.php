@extends('layouts.app')

@section('header_title', 'Report an Incident')

@section('content')
@push('styles')
    <link href="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.css" rel="stylesheet" />
    <style>
        #map { height: 350px; z-index: 10; border-radius: 0.5rem; }
    </style>
@endpush

<div class="max-w-3xl mx-auto">
    <div class="bg-white p-8 rounded-xl shadow-md border border-gray-100">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Submit a New Report</h2>
            <p class="text-gray-500 mt-1">Provide accurate details to help community safety officers.</p>
        </div>

        <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Incident Title</label>
                    <input type="text" name="title" id="title" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="e.g., Suspicious activity near Park Avenue" required>
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">Incident Type</label>
                    <select name="type" id="type" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                        <option value="">Select Type</option>
                        <option value="crime">Crime</option>
                        <option value="accident">Accident</option>
                        <option value="suspicious">Suspicious Activity</option>
                        <option value="other">Other</option>
                    </select>
                    @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Date & Time -->
                <div>
                    <label for="datetime" class="block text-sm font-semibold text-gray-700 mb-2">Date & Time</label>
                    <input type="datetime-local" name="datetime" id="datetime" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" required>
                    @error('datetime') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Location -->
                <div class="md:col-span-2">
                    <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                    <input type="text" name="location" id="location" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Enter street name or landmark" required>
                    @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    
                    <p class="text-xs text-gray-500 mt-3 mb-1">Click on the map to drop a pin, OR type the coordinates below.</p>
                    <div id="map" class="w-full border border-gray-300 shadow-sm mb-4"></div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="latitude" class="block text-sm font-semibold text-gray-700 mb-2">Latitude</label>
                            <input type="text" name="latitude" id="latitude" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="e.g. 40.7128">
                        </div>
                        <div>
                            <label for="longitude" class="block text-sm font-semibold text-gray-700 mb-2">Longitude</label>
                            <input type="text" name="longitude" id="longitude" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="e.g. -74.0060">
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Describe the incident in detail..." required></textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Image Upload -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Image (Optional)</label>
                    <div onclick="document.getElementById('image').click()" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-500 hover:bg-indigo-50/30 transition-all cursor-pointer group">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-indigo-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <span class="relative rounded-md font-medium text-indigo-600 group-hover:text-indigo-500">
                                    <span>Upload an image</span>
                                    <input id="image" name="image" type="file" class="hidden" onchange="updateFileName(this, 'image-name')">
                                </span>
                                <p class="pl-1 text-gray-400">or drag and drop</p>
                            </div>
                            <p id="image-name" class="text-xs text-indigo-500 font-bold mt-2"></p>
                        </div>
                    </div>
                    @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-4">
                <a href="{{ route('dashboard') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 shadow-md hover:shadow-lg transition transform hover:-translate-y-1 active:translate-y-0">
                    Submit Report
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <!-- Import MapLibre GL JS -->
    <script src="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Geofence Configuration
            var neighborhoodLat = {{ $neighborhoodLat }};
            var neighborhoodLng = {{ $neighborhoodLng }};
            var neighborhoodName = "{{ $neighborhoodName }}";
            var neighborhoodBoundaryJson = {!! $neighborhoodBoundary ? $neighborhoodBoundary : 'null' !!};

            // Initialize MapLibre Map centered on neighborhood center
            var map = new maplibregl.Map({
                container: 'map',
                style: 'https://api.maptiler.com/maps/streets-v2/style.json?key={{ config('services.maptiler.key') }}', 
                center: [neighborhoodLng, neighborhoodLat], // [Lng, Lat]
                zoom: 14,
                pitch: 45, // Tilted 3D perspective by default
                bearing: -17.6
            });

            // Add smooth navigation controls
            map.addControl(new maplibregl.NavigationControl());

            // Add geolocate control (User Location blue dot + target button)
            var geolocate = new maplibregl.GeolocateControl({
                positionOptions: {
                    enableHighAccuracy: true
                },
                trackUserLocation: true,
                showUserLocation: true
            });
            map.addControl(geolocate);

            // Enable 3D Buildings & Paint Admin Neighborhood Boundary Layer
            map.on('load', function () {
                var layers = map.getStyle().layers;
                var labelLayerId;
                for (var i = 0; i < layers.length; i++) {
                    if (layers[i].type === 'symbol' && layers[i].layout['text-field']) {
                        labelLayerId = layers[i].id;
                        break;
                    }
                }

                // 3D Buildings Layer
                map.addLayer({
                    'id': '3d-buildings',
                    'source': 'openmaptiles',
                    'source-layer': 'building',
                    'type': 'fill-extrusion',
                    'minzoom': 14,
                    'paint': {
                        'fill-extrusion-color': '#e2e8f0',
                        'fill-extrusion-height': [
                            'interpolate', ['linear'], ['zoom'],
                            14, 0,
                            14.5, ['get', 'render_height']
                        ],
                        'fill-extrusion-base': [
                            'interpolate', ['linear'], ['zoom'],
                            14, 0,
                            14.5, ['get', 'render_min_height']
                        ],
                        'fill-extrusion-opacity': 0.8
                    }
                }, labelLayerId);

                // Add Highlighted Admin Neighborhood Polygon Layer if defined
                if (neighborhoodBoundaryJson) {
                    map.addSource('neighborhood-boundary', {
                        type: 'geojson',
                        data: neighborhoodBoundaryJson
                    });

                    // Semi-transparent indigo overlay
                    map.addLayer({
                        id: 'boundary-fill',
                        type: 'fill',
                        source: 'neighborhood-boundary',
                        paint: {
                            'fill-color': '#4f46e5',
                            'fill-opacity': 0.22
                        }
                    });

                    // Thicker vibrant neon border line
                    map.addLayer({
                        id: 'boundary-line',
                        type: 'line',
                        source: 'neighborhood-boundary',
                        paint: {
                            'line-color': '#6366f1',
                            'line-width': 4
                        }
                    });

                    // Fit Map view perfectly to contain the boundary coordinates
                    try {
                        var coordinates = neighborhoodBoundaryJson.features[0].geometry.coordinates[0];
                        var bounds = coordinates.reduce(function (bounds, coord) {
                            return bounds.extend(coord);
                        }, new maplibregl.LngLatBounds(coordinates[0], coordinates[0]));

                        map.fitBounds(bounds, {
                            padding: 40,
                            maxZoom: 16
                        });
                    } catch (err) {
                        console.error('Error fitting bounds:', err);
                    }
                }
            });

            // Point in Polygon Check (Ray-Casting Algorithm)
            function isPointInPolygon(point, polygonCoords) {
                var x = point[0], y = point[1];
                var inside = false;
                for (var i = 0, j = polygonCoords.length - 1; i < polygonCoords.length; j = i++) {
                    var xi = polygonCoords[i][0], yi = polygonCoords[i][1];
                    var xj = polygonCoords[j][0], yj = polygonCoords[j][1];
                    var intersect = ((yi > y) != (yj > y))
                        && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
                    if (intersect) inside = !inside;
                }
                return inside;
            }

            function isPointInGeofence(lng, lat) {
                if (!neighborhoodBoundaryJson || !neighborhoodBoundaryJson.features || neighborhoodBoundaryJson.features.length === 0) {
                    return true; // No boundary configured, permit freely
                }
                var point = [lng, lat];
                var feature = neighborhoodBoundaryJson.features[0];
                if (feature.geometry.type === 'Polygon') {
                    return isPointInPolygon(point, feature.geometry.coordinates[0]);
                } else if (feature.geometry.type === 'MultiPolygon') {
                    for (var i = 0; i < feature.geometry.coordinates.length; i++) {
                        if (isPointInPolygon(point, feature.geometry.coordinates[i][0])) {
                            return true;
                        }
                    }
                }
                return false;
            }

            var marker;
            var latInput = document.getElementById('latitude');
            var lngInput = document.getElementById('longitude');

            // Function to get address from coordinates (Reverse Geocoding)
            function getAddressFromCoords(lat, lng) {
                var locationInput = document.getElementById('location');
                locationInput.placeholder = "Finding address...";
                
                fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                    .then(response => response.json())
                    .then(data => {
                        if(data.display_name) {
                            var address = data.address;
                            var shortAddress = "";
                            if(address.road) shortAddress += address.road;
                            if(address.suburb || address.city || address.town || address.village) {
                                shortAddress += (shortAddress ? ", " : "") + (address.suburb || address.city || address.town || address.village);
                            }
                            locationInput.value = shortAddress || data.display_name;
                        }
                    })
                    .catch(error => {
                        console.error("Geocoding failed:", error);
                        locationInput.placeholder = "Could not find address automatically";
                    });
            }

            // When clicking the map
            map.on('click', function(e) {
                var lat = parseFloat(e.lngLat.lat.toFixed(6));
                var lng = parseFloat(e.lngLat.lng.toFixed(6));

                // Geofence Validation
                if (!isPointInGeofence(lng, lat)) {
                    alert(`🚫 Geofence Restriction!\n\nYou must only report incidents inside your neighborhood's registered boundary (${neighborhoodName || 'Assigned Zone'}).\n\nPlease click inside the highlighted region.`);
                    return;
                }

                if (marker) {
                    marker.setLngLat([lng, lat]);
                } else {
                    marker = new maplibregl.Marker({ color: "#ef4444" })
                        .setLngLat([lng, lat])
                        .addTo(map);
                }

                latInput.value = lat;
                lngInput.value = lng;
                
                getAddressFromCoords(lat, lng);
            });

            // When typing in the latitude/longitude boxes manually
            let timeoutId;
            function updateMapFromInputs() {
                var lat = parseFloat(latInput.value);
                var lng = parseFloat(lngInput.value);

                if (!isNaN(lat) && !isNaN(lng)) {
                    // Geofence Validation for Manual inputs
                    if (!isPointInGeofence(lng, lat)) {
                        alert(`🚫 Outside Boundary Alert!\n\nThe coordinates entered are outside your neighborhood (${neighborhoodName || 'Assigned Zone'}). Please select a location within the boundary.`);
                        latInput.value = '';
                        lngInput.value = '';
                        if (marker) marker.remove();
                        return;
                    }

                    if (marker) {
                        marker.setLngLat([lng, lat]);
                    } else {
                        marker = new maplibregl.Marker({ color: "#ef4444" })
                            .setLngLat([lng, lat])
                            .addTo(map);
                    }
                    map.setCenter([lng, lat]);
                    map.setZoom(16);
                    
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => {
                        getAddressFromCoords(lat, lng);
                    }, 800);
                }
            }

            latInput.addEventListener('input', updateMapFromInputs);
            lngInput.addEventListener('input', updateMapFromInputs);
        });

        // Function to show file name after selection
        function updateFileName(input, targetId) {
            const fileName = input.files[0] ? input.files[0].name : '';
            document.getElementById(targetId).textContent = fileName ? '✓ Selected: ' + fileName : '';
        }
    </script>
@endpush

@endsection
