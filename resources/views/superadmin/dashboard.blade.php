@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <div class="inline-flex items-center gap-2 bg-indigo-50 border border-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-2 shadow-sm">
                🛡️ Master Control Center
            </div>
            <h1 class="text-3xl font-bold font-serif-custom text-slate-900 leading-tight">Super Admin Dashboard</h1>
            <p class="text-slate-500 mt-1">Oversee, authorize, and moderate neighborhood administrative nodes.</p>
        </div>
        <div class="flex flex-wrap items-center gap-4">
            <!-- Master SOS Reset -->
            <form action="{{ route('superadmin.reset-sos') }}" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Are you sure you want to force resolve and clear all active neighborhood SOS emergency alerts?');">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-black uppercase tracking-widest text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition shadow-md hover:shadow-rose-500/25 border border-rose-500 cursor-pointer">
                    🚨 Reset Active SOS Signals
                </button>
            </form>
            <div class="flex items-center gap-2.5">
                <span class="inline-flex h-3.5 w-3.5 rounded-full bg-emerald-500 animate-ping"></span>
                <span class="text-sm font-semibold text-slate-600">System Secure & Online</span>
            </div>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl shadow-sm text-emerald-800 flex items-center gap-3 animate-slide-up">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-xl shadow-sm text-rose-800 flex items-center gap-3 animate-slide-up">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span class="font-bold text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Statistics Overview Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Stat Card 1: Total Admins -->
        <div class="bg-white/70 backdrop-blur-md p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 bg-slate-900 text-white rounded-xl flex items-center justify-center shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Admin Nodes</span>
                <span class="text-2xl font-black text-slate-800">{{ $totalAdmins }}</span>
            </div>
        </div>

        <!-- Stat Card 2: Approved Admins -->
        <div class="bg-white/70 backdrop-blur-md p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 bg-emerald-500 text-white rounded-xl flex items-center justify-center shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Active Admins</span>
                <span class="text-2xl font-black text-slate-800">{{ $approvedAdmins }}</span>
            </div>
        </div>

        <!-- Stat Card 3: Pending Requests -->
        <div class="bg-white/70 backdrop-blur-md p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 @if($pendingRequests > 0) bg-amber-500 animate-pulse @else bg-slate-200 @endif text-white rounded-xl flex items-center justify-center shadow-md transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Pending Actions</span>
                <span class="text-2xl font-black text-slate-800">{{ $pendingRequests }}</span>
            </div>
        </div>

        <!-- Stat Card 4: Rejected Admins -->
        <div class="bg-white/70 backdrop-blur-md p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
            <div class="w-12 h-12 bg-rose-500 text-white rounded-xl flex items-center justify-center shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Suspended Nodes</span>
                <span class="text-2xl font-black text-slate-800">{{ $rejectedRequests }}</span>
            </div>
        </div>
    </div>

    <!-- Admins Management Table -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl border border-slate-100 shadow-xl overflow-hidden animate-slide-up stagger-1">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Neighborhood Administrators</h3>
                <p class="text-xs text-slate-500">View, moderate, and adjust credentials of active or pending moderators.</p>
            </div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-600 font-semibold shadow-sm">
                <span>Total Registered: <strong>{{ count($admins) }}</strong></span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Admin Name</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Contact Details</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Claimed Neighborhood</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Registered Date</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500">Access Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">Moderator Controls</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($admins as $admin)
                        <tr class="hover:bg-slate-50/50 transition">
                            <!-- Admin Name -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm shadow-inner uppercase">
                                        {{ substr($admin->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-slate-800 block">{{ $admin->name }}</span>
                                        <span class="text-xs text-slate-400">ID: #{{ $admin->id }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Email Contact -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-slate-600 block">{{ $admin->email }}</span>
                                <span class="text-xs text-slate-400 uppercase tracking-widest font-black">Neighborhood Authority</span>
                            </td>

                            <!-- Claimed Neighborhood -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($admin->neighborhood_name)
                                    <span class="text-sm font-bold text-slate-800 block">🏡 {{ $admin->neighborhood_name }}</span>
                                    <span class="text-xs text-slate-500 font-semibold font-mono block">📍 {{ $admin->neighborhood_lat }}, {{ $admin->neighborhood_lng }}</span>
                                    @if($admin->neighborhood_boundary)
                                        <button type="button" 
                                                onclick='showBoundaryModal("{{ addslashes($admin->neighborhood_name) }}", {{ $admin->neighborhood_boundary }})'
                                                class="mt-1.5 px-2.5 py-0.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg text-[10px] font-black transition-all flex items-center gap-0.5 border border-indigo-100 shadow-sm w-fit uppercase tracking-wider">
                                            🗺️ View Region
                                        </button>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400 italic">None Claimed</span>
                                @endif
                            </td>

                            <!-- Registered Date -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-semibold">
                                {{ $admin->created_at->format('M d, Y') }}
                                <span class="text-xs text-slate-400 block font-normal">{{ $admin->created_at->format('h:i A') }}</span>
                            </td>

                            <!-- Status Badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($admin->status === 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 shadow-sm">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        Active Approved
                                    </span>
                                @elseif($admin->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-100 shadow-sm animate-pulse">
                                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                        Awaiting Review
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100 shadow-sm">
                                        <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                                        Suspended Nodes
                                    </span>
                                @endif
                            </td>

                            <!-- Moderator Action Controls -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Approve Button (Visible if Pending or Rejected) -->
                                    @if($admin->status !== 'approved')
                                        <form action="{{ route('superadmin.approve', $admin->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="p-2 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white border border-emerald-100 transition-all shadow-sm hover:shadow-md"
                                                    title="Approve Moderator Access">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Reject/Suspend Button (Visible if Pending or Approved) -->
                                    @if($admin->status !== 'rejected')
                                        <form action="{{ route('superadmin.reject', $admin->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="p-2 rounded-xl bg-amber-50 text-amber-700 hover:bg-amber-500 hover:text-white border border-amber-100 transition-all shadow-sm hover:shadow-md"
                                                    title="Suspend/Reject Access">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Delete Button (Visible for all, permanently erases account) -->
                                    <form action="{{ route('superadmin.delete', $admin->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you absolutely sure you want to permanently erase the Moderator account for \'{{ $admin->name }}\'? This action is irreversible.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white border border-rose-100 transition-all shadow-sm hover:shadow-md"
                                                title="Permanently Delete Account">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span class="text-sm font-semibold text-slate-500 block">No Registered Admins Found</span>
                                    <span class="text-xs text-slate-400 mt-1">When new local authorities register at `/admin/register`, they will show up here.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Map Boundary Viewer Modal -->
<div id="boundaryModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300 opacity-0">
    <div class="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl border border-slate-100 transform scale-95 transition-all duration-300" id="boundaryModalContainer">
        <!-- Header -->
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-800" id="modalTitle">Neighborhood Boundary</h3>
                <p class="text-xs text-slate-400">Exact geo-spatial territory claim of this moderator.</p>
            </div>
            <button onclick="closeBoundaryModal()" class="p-2 rounded-xl hover:bg-slate-200 text-slate-400 hover:text-slate-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <!-- Map Canvas -->
        <div class="relative">
            <div id="modal-map" class="w-full h-96"></div>
        </div>
        <!-- Footer -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end">
            <button onclick="closeBoundaryModal()" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-xs transition shadow-md">
                Done Viewing
            </button>
        </div>
    </div>
</div>

<link href="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.css" rel="stylesheet" />
<script src="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.js"></script>
<script>
    var modalMap;
    var currentSourceId = 'boundary-source';
    var currentLayerId = 'boundary-layer';
    var currentOutlineId = 'boundary-outline';

    function showBoundaryModal(name, geojson) {
        document.getElementById('modalTitle').textContent = `🏡 ${name} Boundary`;
        
        var modal = document.getElementById('boundaryModal');
        var container = document.getElementById('boundaryModalContainer');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            container.classList.remove('scale-95');
        }, 10);

        // Initialize or resize the MapLibre Map inside the modal
        setTimeout(() => {
            if (!modalMap) {
                modalMap = new maplibregl.Map({
                    container: 'modal-map',
                    style: 'https://api.maptiler.com/maps/streets-v2/style.json?key={{ config('services.maptiler.key') }}',
                    zoom: 12
                });
                modalMap.addControl(new maplibregl.NavigationControl());
            }

            // Wait for map style to load to add layers
            function drawPolygon() {
                modalMap.resize();

                // Clean up existing layer/source if any
                if (modalMap.getLayer(currentLayerId)) modalMap.removeLayer(currentLayerId);
                if (modalMap.getLayer(currentOutlineId)) modalMap.removeLayer(currentOutlineId);
                if (modalMap.getSource(currentSourceId)) modalMap.removeSource(currentSourceId);

                // Add source
                modalMap.addSource(currentSourceId, {
                    type: 'geojson',
                    data: geojson
                });

                // Fill Layer (Semi-transparent Royal Indigo - matching resident map)
                modalMap.addLayer({
                    id: currentLayerId,
                    type: 'fill',
                    source: currentSourceId,
                    paint: {
                        'fill-color': '#4f46e5',
                        'fill-opacity': 0.22
                    }
                });

                // Thicker neon indigo border line
                modalMap.addLayer({
                    id: currentOutlineId,
                    type: 'line',
                    source: currentSourceId,
                    paint: {
                        'line-color': '#6366f1',
                        'line-width': 4
                    }
                });

                // Fit Map Viewport to coordinates
                var coordinates = geojson.features[0].geometry.coordinates[0];
                var bounds = coordinates.reduce(function (bounds, coord) {
                    return bounds.extend(coord);
                }, new maplibregl.LngLatBounds(coordinates[0], coordinates[0]));

                modalMap.fitBounds(bounds, {
                    padding: 40,
                    maxZoom: 16
                });

                setTimeout(() => {
                    modalMap.resize();
                }, 100);
            }

            if (modalMap.loaded()) {
                drawPolygon();
            } else {
                modalMap.once('load', drawPolygon);
            }
            
            modalMap.resize();
        }, 250);
    }

    function closeBoundaryModal() {
        var modal = document.getElementById('boundaryModal');
        var container = document.getElementById('boundaryModalContainer');
        modal.classList.add('opacity-0');
        container.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection
