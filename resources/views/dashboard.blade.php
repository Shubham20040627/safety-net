@extends('layouts.app')

@section('header_title', 'Dashboard Overview')

@section('content')
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=merriweather:300,400,700&display=swap" rel="stylesheet" />

<style>
    .font-serif-custom { font-family: 'Merriweather', serif; }
</style>

<!-- Custom Welcome & Status Banner -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-900 text-white p-8 rounded-2xl shadow-lg border border-slate-800 relative overflow-hidden mb-6">
    <div class="relative z-10">
        <span class="text-xs font-black uppercase tracking-widest text-indigo-400">Neighborhood Safety Center</span>
        <h2 class="text-3xl font-black font-serif-custom mt-1">Hello, {{ auth()->user()->name }} 👋</h2>
        <p class="text-sm text-slate-300 mt-1.5 leading-relaxed">Here is the latest security report and active overview for your community sector.</p>
    </div>
    <div class="flex items-center gap-2.5 bg-slate-800/60 backdrop-blur border border-slate-700/50 px-4 py-2.5 rounded-xl relative z-10 self-start md:self-auto">
        <span class="h-2.5 w-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
        <span class="text-xs font-black text-slate-200 uppercase tracking-widest">Sector Status: Secure</span>
    </div>
    <div class="absolute -right-12 -top-12 w-44 h-44 bg-indigo-600/10 rounded-full filter blur-xl"></div>
    <div class="absolute -left-12 -bottom-12 w-44 h-44 bg-slate-700/10 rounded-full filter blur-xl"></div>
</div>

<!-- Safety Announcements Section -->
@if(count($announcements) > 0)
<div class="space-y-3 mb-6">
    @foreach($announcements as $announcement)
        <div class="relative flex items-start gap-4 p-5 rounded-2xl border shadow-sm overflow-hidden animate-in fade-in slide-in-from-top-4 duration-500
            {{ $announcement->type == 'critical' ? 'bg-red-50 border-red-200 text-red-900' : '' }}
            {{ $announcement->type == 'warning' ? 'bg-amber-50 border-amber-200 text-amber-900' : '' }}
            {{ $announcement->type == 'info' ? 'bg-indigo-50 border-indigo-200 text-indigo-900' : '' }}">
            
            <div class="mt-0.5">
                @if($announcement->type == 'critical')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                @elseif($announcement->type == 'warning')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                @endif
            </div>
            
            <div class="flex-1">
                <div class="flex justify-between items-start">
                    <h4 class="font-black text-base font-serif-custom leading-tight">
                        {{ $announcement->title }}
                    </h4>
                    <span class="text-[10px] font-bold opacity-60 uppercase tracking-widest">{{ $announcement->created_at->diffForHumans() }}</span>
                </div>
                <p class="mt-1.5 text-sm opacity-90 leading-relaxed font-medium">{{ $announcement->content }}</p>
            </div>
            
            <!-- Tiny background accent shape -->
            <div class="absolute -right-4 -bottom-4 opacity-10 transform rotate-12">
                @if($announcement->type == 'critical')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-red-900" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2z"/></svg>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endif

<div class="space-y-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border-t-4 border-t-indigo-600 border-x border-b border-slate-200/60 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Incidents</p>
                    <h3 class="text-4xl font-black text-slate-900 mt-2 font-serif-custom counter" data-target="{{ $totalReports }}">0</h3>
                </div>
                <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600 border border-indigo-100/40">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <div class="mt-5 pt-4 border-t border-slate-100">
                <a href="{{ route('reports.index') }}" class="text-xs font-black text-slate-900 hover:text-indigo-600 transition-colors flex items-center gap-1">
                    View neighborhood map
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border-t-4 border-t-emerald-600 border-x border-b border-slate-200/60 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">My Contributions</p>
                    <h3 class="text-4xl font-black text-slate-900 mt-2 font-serif-custom counter" data-target="{{ $myReports }}">0</h3>
                </div>
                <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600 border border-emerald-100/40">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
            <div class="mt-5 pt-4 border-t border-slate-100">
                <a href="{{ route('reports.my-reports') }}" class="text-xs font-black text-slate-900 hover:text-emerald-600 transition-colors flex items-center gap-1">
                    View my contribution list
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border-t-4 border-t-amber-600 border-x border-b border-slate-200/60 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Verification Status</p>
                    <h3 class="text-3xl font-black text-slate-900 mt-2 font-serif-custom capitalize">{{ auth()->user()->status }}</h3>
                </div>
                <div class="p-3 bg-amber-50 rounded-xl text-amber-600 border border-amber-100/40">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <div class="mt-5 pt-4 border-t border-slate-100">
                <span class="text-xs text-slate-400 font-semibold flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M6.267 3.585a.75.75 0 00-.012 1.06L11.53 10l-5.275 5.354a.75.75 0 101.074 1.05l5.8-5.875a.75.75 0 000-1.06l-5.8-5.875a.75.75 0 00-1.062-.012z" clip-rule="evenodd" />
                    </svg>
                    Verified community resident
                </span>
            </div>
        </div>
    </div>

    <!-- Safety Intelligence & Predictive Insights -->
    <div class="bg-slate-900 text-white p-6 rounded-2xl shadow-xl border border-slate-800 mb-8 relative overflow-hidden">
        <div class="relative z-10 flex flex-wrap items-center justify-between gap-6">
            <div class="flex-1 min-w-[300px]">
                <h3 class="text-xl font-black font-serif-custom flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Safety Intelligence
                </h3>
                <p class="text-sm text-slate-400 mt-2 leading-relaxed">
                    @if($totalReports === 0)
                        No security incidents have been reported in your sector yet. Your neighborhood is currently fully secure, quiet, and peaceful! 🌟
                    @else
                        Our AI-driven analytics show that <span class="text-indigo-300 font-bold underline decoration-indigo-500/30">{{ $peakTime }}</span> is currently the peak hour for incidents in your sector. 
                        Neighborhood safety is <span class="font-bold {{ $trendDirection == 'down' ? 'text-emerald-400' : 'text-amber-400' }}">
                            {{ $trendDirection == 'down' ? 'Improving' : 'Declining' }} 
                        </span> 
                        with a <span class="underline">{{ $trendPercent }}%</span> change compared to last week.
                    @endif
                </p>
            </div>
            <div class="flex gap-4">
                <div class="bg-slate-800/50 p-4 rounded-xl border border-slate-700/50">
                    <span class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Peak Time</span>
                    <span class="text-xl font-black text-indigo-400">{{ $peakTime }}</span>
                </div>
                <div class="bg-slate-800/50 p-4 rounded-xl border border-slate-700/50">
                    <span class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Weekly Trend</span>
                    <span class="text-xl font-black {{ $trendDirection == 'stable' ? 'text-emerald-400' : ($trendDirection == 'down' ? 'text-emerald-400' : 'text-amber-400') }}">
                        @if($trendDirection === 'stable')
                            Stable 🟢
                        @else
                            {{ $trendDirection == 'down' ? '↓' : '↑' }} {{ $trendPercent }}%
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Weekly Trends Chart -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm hover:shadow-md transition duration-300">
            <h3 class="text-lg font-black text-slate-950 font-serif-custom mb-6 flex items-center gap-2">
                <span class="h-3.5 w-1.5 bg-emerald-500 rounded-full"></span>
                7-Day Activity Trend
            </h3>
            <div class="relative h-64 w-full">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Hourly Distribution Chart -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm hover:shadow-md transition duration-300">
            <h3 class="text-lg font-black text-slate-950 font-serif-custom mb-6 flex items-center gap-2">
                <span class="h-3.5 w-1.5 bg-indigo-600 rounded-full"></span>
                Hourly Distribution
            </h3>
            <div class="relative h-64 w-full">
                <canvas id="hourlyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Existing Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Recent Reports Preview -->
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden hover:shadow-md transition duration-300">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-lg font-black text-slate-950 font-serif-custom flex items-center gap-2">
                <span class="h-3.5 w-1.5 bg-slate-900 rounded-full"></span>
                Recent Incidents
            </h3>
            <a href="{{ route('reports.index') }}" class="text-xs font-black text-slate-900 hover:text-indigo-600 transition-colors flex items-center gap-0.5">
                See all reports
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($latestReports as $report)
                <div class="p-6 flex items-start gap-4 hover:bg-slate-50/40 transition duration-200 border-l-4 
                    {{ $report->type == 'crime' ? 'border-l-red-600' : '' }}
                    {{ $report->type == 'accident' ? 'border-l-amber-500' : '' }}
                    {{ $report->type == 'suspicious' ? 'border-l-indigo-600' : '' }}
                    {{ $report->type == 'other' ? 'border-l-slate-400' : '' }}">
                    <div class="h-10 w-10 rounded-xl bg-slate-50 text-slate-700 flex items-center justify-center flex-shrink-0 border border-slate-200/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-slate-900 text-base font-serif-custom hover:text-indigo-600 transition">
                                <a href="{{ route('reports.show', $report) }}">{{ $report->title }}</a>
                            </h4>
                            <span class="text-xs font-bold px-3 py-1 rounded-full border
                                {{ $report->type == 'crime' ? 'bg-red-50 text-red-700 border-red-100' : '' }}
                                {{ $report->type == 'accident' ? 'bg-amber-50 text-amber-700 border-amber-100' : '' }}
                                {{ $report->type == 'suspicious' ? 'bg-indigo-50 text-indigo-700 border-indigo-100' : '' }}
                                {{ $report->type == 'other' ? 'bg-slate-50 text-slate-700 border-slate-100' : '' }}">
                                {{ ucfirst($report->type) }}
                            </span>
                        </div>
                        <p class="text-sm text-slate-600 mt-2 line-clamp-1 leading-relaxed">{{ $report->description }}</p>
                        <div class="mt-4 flex items-center gap-4 text-xs text-slate-400 font-semibold">
                            <span class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $report->location }}
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $report->datetime }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-slate-500 font-medium">
                    No reports available yet.
                </div>
            @endforelse
        </div>
    </div>
    </div>
</div>

@if(false)
<!-- AI Safety Guardian Widget -->
<div id="ai-guardian-container" class="fixed bottom-6 right-6 z-[100] flex flex-col items-end">
    <!-- Chat Window -->
    <div id="ai-chat-window" class="hidden w-[350px] sm:w-[400px] h-[500px] bg-white/90 backdrop-blur-xl border border-slate-200 shadow-2xl rounded-3xl flex-col overflow-hidden mb-4 transition-all duration-300 transform scale-95 opacity-0 origin-bottom-right">
        <!-- Header -->
        <div class="bg-slate-900 p-5 text-white flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-slate-900 rounded-full"></span>
                </div>
                <div>
                    <h4 class="font-black text-sm font-serif-custom">Safety Guardian</h4>
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">AI Responder Online</span>
                </div>
            </div>
            <button onclick="toggleAIChat()" class="text-slate-400 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Chat Area -->
        <div id="ai-chat-messages" class="flex-1 overflow-y-auto p-5 space-y-4 bg-slate-50/50">
            <!-- Bot Message -->
            <div class="flex flex-col items-start max-w-[85%]">
                <div class="bg-white border border-slate-200 p-4 rounded-2xl rounded-tl-none shadow-sm">
                    <p class="text-sm text-slate-700 leading-relaxed font-medium">Hello, {{ auth()->user()->name }}. I am your AI Safety Guardian. How can I assist you with neighborhood safety or emergency guidance today?</p>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-4 bg-white border-t border-slate-100">
            <div class="flex gap-2 mb-3">
                <button onclick="quickAsk('What to do in a medical emergency?')" class="text-[10px] font-black bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-600 px-3 py-1.5 rounded-lg border border-slate-200/60 transition whitespace-nowrap">Medical Help</button>
                <button onclick="quickAsk('Tips for late night safety.')" class="text-[10px] font-black bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 text-slate-600 px-3 py-1.5 rounded-lg border border-slate-200/60 transition whitespace-nowrap">Safety Tips</button>
            </div>
            <form id="ai-chat-form" class="relative">
                <input type="text" id="ai-user-input" placeholder="Type your safety question..." class="w-full bg-slate-100 border-none rounded-2xl py-3 pl-4 pr-12 text-sm font-medium focus:ring-2 focus:ring-indigo-500 transition-all">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-900 text-white rounded-xl flex items-center justify-center hover:bg-indigo-600 transition shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Toggle Button -->
    <button onclick="toggleAIChat()" class="w-16 h-16 bg-slate-950 text-white rounded-full flex items-center justify-center shadow-2xl hover:scale-110 active:scale-95 transition-all duration-300 group border-4 border-white">
        <div class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            <span class="absolute -top-1 -right-1 w-4 h-4 bg-indigo-500 border-2 border-slate-950 rounded-full animate-ping"></span>
        </div>
    </button>
</div>

<script>
    function toggleAIChat() {
        const win = document.getElementById('ai-chat-window');
        if(win.classList.contains('hidden')) {
            win.classList.remove('hidden');
            setTimeout(() => {
                win.classList.remove('opacity-0', 'scale-95');
                win.classList.add('opacity-100', 'scale-100');
            }, 10);
        } else {
            win.classList.add('opacity-0', 'scale-95');
            win.classList.remove('opacity-100', 'scale-100');
            setTimeout(() => win.classList.add('hidden'), 300);
        }
    }

    function quickAsk(msg) {
        document.getElementById('ai-user-input').value = msg;
        document.getElementById('ai-chat-form').dispatchEvent(new Event('submit'));
    }

    document.getElementById('ai-chat-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const input = document.getElementById('ai-user-input');
        const msg = input.value.trim();
        if(!msg) return;

        // Add User Message to UI
        addMessage(msg, 'user');
        input.value = '';

        // Add Loading Message
        const loadingId = 'loading-' + Date.now();
        addMessage('<span class="animate-pulse">Analyzing safety protocols...</span>', 'bot', loadingId);

        try {
            const response = await fetch('{{ route("ai.chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: msg })
            });
            const data = await response.json();
            
            // Remove loading and add actual response
            document.getElementById(loadingId).remove();
            addMessage(data.response.replace(/\n/g, '<br>'), 'bot');
        } catch (err) {
            document.getElementById(loadingId).innerText = "Emergency protocol failed. Please check connection.";
        }
    });

    function addMessage(text, side, id = null) {
        const chat = document.getElementById('ai-chat-messages');
        const div = document.createElement('div');
        div.className = `flex flex-col ${side === 'user' ? 'items-end' : 'items-start'} max-w-[90%] animate-in fade-in slide-in-from-bottom-2 duration-300`;
        if(id) div.id = id;
        
        const contentClass = side === 'user' 
            ? 'bg-slate-900 text-white rounded-2xl rounded-tr-none' 
            : 'bg-white border border-slate-200 text-slate-700 rounded-2xl rounded-tl-none';
            
        div.innerHTML = `
            <div class="${contentClass} p-4 shadow-sm">
                <p class="text-sm leading-relaxed font-medium">${text}</p>
            </div>
            <span class="text-[8px] font-black uppercase text-slate-400 mt-1.5 px-1">${side === 'user' ? 'You' : 'Guardian'}</span>
        `;
        
        chat.appendChild(div);
        chat.scrollTop = chat.scrollHeight;
    }
</script>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeData = @json($typeData);
        const statusData = @json($statusData);
        const hourlyData = @json($fullHourlyData);
        const weeklyTrend = @json($weeklyTrend);

        // Render Trend Chart (Line)
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: Object.keys(weeklyTrend),
                datasets: [{
                    label: 'Incidents',
                    data: Object.values(weeklyTrend),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#10b981',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, color: '#64748b' }, grid: { color: '#f1f5f9' } },
                    x: { ticks: { color: '#64748b', font: { weight: '600' } }, grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });

        // Render Hourly Chart (Bar)
        const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
        new Chart(hourlyCtx, {
            type: 'bar',
            data: {
                labels: Array.from({length: 24}, (_, i) => i + ':00'),
                datasets: [{
                    label: 'Incidents',
                    data: Object.values(hourlyData),
                    backgroundColor: '#4f46e5',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, color: '#64748b' }, grid: { color: '#f1f5f9' } },
                    x: { ticks: { color: '#64748b', font: { size: 10 } }, grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });


        // Sophisticated Color Palette (Trustworthy & Mature)
        const typeColors = {
            'crime': '#b91c1c',        // Deep Crimson
            'accident': '#d97706',     // Mature Amber
            'suspicious': '#4f46e5',   // Indigo
            'other': '#64748b'         // Slate
        };

        const statusColors = {
            'pending': '#d97706',      // Mature Amber
            'investigating': '#4f46e5',// Indigo
            'resolved': '#059669'      // Sage Emerald Green
        };

        // Render Type Chart (Doughnut)
        const typeCtx = document.getElementById('typeChart').getContext('2d');
        new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(typeData).map(l => l.charAt(0).toUpperCase() + l.slice(1)),
                datasets: [{
                    data: Object.values(typeData),
                    backgroundColor: Object.keys(typeData).map(k => typeColors[k] || '#64748b'),
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom',
                        labels: {
                            font: {
                                family: 'Inter',
                                size: 12,
                                weight: '600'
                            },
                            color: '#334155'
                        }
                    }
                }
            }
        });

        // Render Status Chart (Bar)
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(statusData).map(l => l.charAt(0).toUpperCase() + l.slice(1)),
                datasets: [{
                    label: 'Number of Reports',
                    data: Object.values(statusData),
                    backgroundColor: Object.keys(statusData).map(k => statusColors[k] || '#64748b'),
                    borderRadius: 8,
                    maxBarThickness: 45
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { 
                        beginAtZero: true, 
                        ticks: { stepSize: 1, color: '#64748b' },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { color: '#64748b', font: { weight: '600' } },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });

    // Number Counter Animation
    const counters = document.querySelectorAll('.counter');
    const speed = 200;

    counters.forEach(counter => {
        const animate = () => {
            const value = +counter.getAttribute('data-target');
            const data = +counter.innerText;
            const time = value / speed;

            if (data < value) {
                counter.innerText = Math.ceil(data + time);
                setTimeout(animate, 1);
            } else {
                counter.innerText = value;
            }
        };
        
        // Trigger only when visible
        const observer = new IntersectionObserver((entries) => {
            if(entries[0].isIntersecting) animate();
        }, { threshold: 0.5 });
        
        observer.observe(counter);
    });
</script>
@endpush
