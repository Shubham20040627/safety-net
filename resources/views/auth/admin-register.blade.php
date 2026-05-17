<link href="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.css" rel="stylesheet" />
<link href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.4.3/mapbox-gl-draw.css" rel="stylesheet" />
<style>
    #map { height: 350px; z-index: 10; border-radius: 1.25rem; }
    /* Customize the drawn polygon styling slightly to match royal indigo */
    .mapboxgl-ctrl-group button {
        width: 30px;
        height: 30px;
    }
</style>

<x-guest-layout>
    <form method="POST" action="{{ route('admin.register') }}" class="space-y-4" id="admin-register-form">
        @csrf

        <!-- STEP 1: Credentials Panel -->
        <div id="step-1-panel" class="space-y-4 transition-all duration-300">
            <div class="mb-6">
                <div class="inline-flex items-center gap-2 bg-indigo-50 border border-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-3 shadow-sm">
                    🛡️ Administrative Authority
                </div>
                <h2 class="text-3xl font-bold text-slate-900 font-serif-custom mb-2">Register Admin Node</h2>
                <p class="text-slate-500 text-sm">Request moderator privileges to secure local corridors.</p>
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-bold text-slate-700 mb-1.5">Admin Full Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none transition-all bg-white text-sm" 
                    placeholder="Admin Officer Name">
                <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-red-600" />
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-bold text-slate-700 mb-1.5">Official Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none transition-all bg-white text-sm" 
                    placeholder="officer@safetynet.com">
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-600" />
            </div>

            <!-- Neighborhood Name -->
            <div>
                <label for="neighborhood_name" class="block text-sm font-bold text-slate-700 mb-1.5">Neighborhood Name</label>
                <input id="neighborhood_name" type="text" name="neighborhood_name" value="{{ old('neighborhood_name') }}" required 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none transition-all bg-white text-sm" 
                    placeholder="e.g. Greenwood Valley (or draw boundary to auto-fill)">
                <x-input-error :messages="$errors->get('neighborhood_name')" class="mt-1 text-xs text-red-600" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-bold text-slate-700 mb-1.5">Secret Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password" 
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none transition-all bg-white text-sm" 
                    placeholder="Create a strong password">
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-600" />
            </div>

            <div class="pt-4 border-t border-slate-100">
                <button type="button" id="btn-next-step" class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-xl transition-all shadow-lg hover:shadow-indigo-500/30 flex items-center justify-center gap-2 active:scale-[0.98] text-sm uppercase tracking-wider">
                    Select Map Region on Map ➡️
                </button>
            </div>

            <div class="mt-6 text-center text-xs text-slate-600">
                Registering as a regular resident? 
                <a href="{{ route('register') }}" class="font-bold text-slate-900 hover:text-slate-800 transition-colors">Resident Signup</a>
            </div>
        </div>

        <!-- STEP 2: Map Selection Panel -->
        <div id="step-2-panel" class="hidden space-y-4 transition-all duration-300">
            <div class="flex items-center justify-between mb-2">
                <button type="button" id="btn-prev-step" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-800 rounded-xl text-xs font-bold transition shadow-sm border border-slate-200/50">
                    ⬅️ Back to Details
                </button>
                <div class="text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md">Step 2 of 2</div>
            </div>

            <div class="mb-4">
                <h3 class="text-2xl font-bold text-slate-900 font-serif-custom">🏡 Outline Neighborhood Region</h3>
                <p class="text-xs text-slate-500">Define the geographical boundary territory that you will moderate.</p>
            </div>

            <div class="border border-indigo-50 bg-indigo-50/20 p-4 rounded-2xl space-y-3">
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-sm font-bold text-slate-700">Region Boundary Selector</label>
                    <div class="flex items-center gap-1.5">
                        <button type="button" id="btn-draw" class="px-2.5 py-1 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-lg text-[10px] font-black transition-all flex items-center gap-1 border border-indigo-200 shadow-sm uppercase tracking-wider">
                            ✏️ Draw
                        </button>
                        <button type="button" id="btn-clear" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-[10px] font-black transition-all flex items-center gap-1 border border-rose-100 shadow-sm uppercase tracking-wider">
                            🗑️ Reset
                        </button>
                    </div>
                </div>
                
                <p class="text-[11px] text-slate-400">Click <strong>Draw</strong>, then click corners on the map to outline your region boundary. Double-click the last point to close and lock the shape.</p>
                
                <div id="map" class="w-full border border-slate-200 shadow-sm mb-3"></div>
                
                <input type="hidden" name="neighborhood_boundary" id="neighborhood_boundary" value="{{ old('neighborhood_boundary') }}">
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="neighborhood_lat" class="block text-[10px] font-bold text-slate-500 mb-1">Calculated Center Lat</label>
                        <input id="neighborhood_lat" type="text" name="neighborhood_lat" readonly required
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-slate-500 outline-none text-xs font-semibold" 
                            placeholder="Draw Area On Map">
                    </div>
                    <div>
                        <label for="neighborhood_lng" class="block text-[10px] font-bold text-slate-500 mb-1">Calculated Center Lng</label>
                        <input id="neighborhood_lng" type="text" name="neighborhood_lng" readonly required
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-slate-500 outline-none text-xs font-semibold" 
                            placeholder="Draw Area On Map">
                    </div>
                </div>
                <x-input-error :messages="$errors->get('neighborhood_lat')" class="mt-1 text-xs text-red-600" />
            </div>

            <div class="pt-4 border-t border-slate-100">
                <button type="submit" class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-xl transition-all shadow-lg hover:shadow-indigo-500/30 flex items-center justify-center gap-2 active:scale-[0.98] group text-sm uppercase tracking-wider">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/80 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Submit Admin Request
                </button>
            </div>
        </div>
    </form>
</x-guest-layout>

<script src="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.js"></script>
<script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.4.3/mapbox-gl-draw.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var defaultCenter = [77.2090, 28.6139]; // Default: New Delhi [Lng, Lat]

        var map = new maplibregl.Map({
            container: 'map',
            style: 'https://api.maptiler.com/maps/streets-v2/style.json?key={{ config('services.maptiler.key') }}', 
            center: defaultCenter, 
            zoom: 11,
            pitch: 10
        });

        map.addControl(new maplibregl.NavigationControl());

        var geolocate = new maplibregl.GeolocateControl({
            positionOptions: {
                enableHighAccuracy: true
            },
            trackUserLocation: true,
            showUserLocation: true
        });
        map.addControl(geolocate);

        // Automatically geolocate user on load
        map.on('load', function() {
            geolocate.trigger();
        });

        // Initialize Mapbox Draw
        var draw = new MapboxDraw({
            displayControlsDefault: false,
            controls: {
                polygon: true,
                trash: true
            },
            defaultMode: 'draw_polygon' // Start in polygon drawing mode immediately!
        });
        map.addControl(draw);

        var latInput = document.getElementById('neighborhood_lat');
        var lngInput = document.getElementById('neighborhood_lng');
        var nameInput = document.getElementById('neighborhood_name');
        var boundaryInput = document.getElementById('neighborhood_boundary');

        function getNeighborhoodName(lat, lng) {
            if (nameInput.value.trim() === "" || nameInput.value.includes("Safety Corridor")) {
                fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                    .then(response => response.json())
                    .then(data => {
                        if(data.address) {
                            var place = data.address.suburb || data.address.neighbourhood || data.address.village || data.address.city_district || data.address.city;
                            if (place) {
                                nameInput.value = place + " Safety Corridor";
                            }
                        }
                    })
                    .catch(err => console.error(err));
            }
        }

        function updateBoundaryInputs() {
            var data = draw.getAll();
            if (data.features.length > 0) {
                boundaryInput.value = JSON.stringify(data);
                
                // Get all coordinates of the drawn polygon to calculate simple centroid
                var coordinates = data.features[0].geometry.coordinates[0];
                var latSum = 0;
                var lngSum = 0;
                // Exclude last coordinate if it matches first (closed loop)
                var count = coordinates.length - 1;
                if (count < 1) count = coordinates.length;

                for (var i = 0; i < count; i++) {
                    lngSum += coordinates[i][0];
                    latSum += coordinates[i][1];
                }

                var centerLat = (latSum / count).toFixed(6);
                var centerLng = (lngSum / count).toFixed(6);

                latInput.value = centerLat;
                lngInput.value = centerLng;

                getNeighborhoodName(centerLat, centerLng);
            } else {
                boundaryInput.value = "";
                latInput.value = "";
                lngInput.value = "";
            }
        }

        // Mapbox Draw Event Listeners
        map.on('draw.create', updateBoundaryInputs);
        map.on('draw.update', updateBoundaryInputs);
        map.on('draw.delete', updateBoundaryInputs);

        // Custom Buttons Actions
        document.getElementById('btn-draw').addEventListener('click', function() {
            draw.changeMode('draw_polygon');
        });

        document.getElementById('btn-clear').addEventListener('click', function() {
            draw.deleteAll();
            updateBoundaryInputs();
        });

        // Wizard Panels toggle handlers
        var nextBtn = document.getElementById('btn-next-step');
        var prevBtn = document.getElementById('btn-prev-step');
        var step1 = document.getElementById('step-1-panel');
        var step2 = document.getElementById('step-2-panel');

        nextBtn.addEventListener('click', function() {
            // Basic Step 1 field check
            var name = document.getElementById('name').value.trim();
            var email = document.getElementById('email').value.trim();
            var nName = document.getElementById('neighborhood_name').value.trim();
            var pwd = document.getElementById('password').value;

            if (!name || !email || !nName || !pwd) {
                alert("Please fill out all credentials to proceed to the map region selection.");
                return;
            }

            // Slide out step 1, show map step 2
            step1.classList.add('hidden');
            step2.classList.remove('hidden');

            // Force MapLibre GL to recalculate dimensions since it was in hidden block
            setTimeout(function() {
                map.resize();
            }, 200);
        });

        prevBtn.addEventListener('click', function() {
            // Slide back to step 1
            step2.classList.add('hidden');
            step1.classList.remove('hidden');
        });
    });
</script>
