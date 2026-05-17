@extends('layouts.app')

@section('header_title', 'Security Analytics Panel')

@section('content')
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=merriweather:300,400,700&display=swap" rel="stylesheet" />

<style>
    .font-serif-custom { font-family: 'Merriweather', serif; }
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(226, 232, 240, 0.8);
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
</style>

<div class="space-y-6">
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-900 text-white p-8 rounded-2xl shadow-lg border border-slate-800 relative overflow-hidden">
        <div class="relative z-10">
            <span class="text-xs font-black uppercase tracking-widest text-indigo-400">Security Operations Command</span>
            <h2 class="text-3xl font-black font-serif-custom mt-1">Intelligence Dashboard</h2>
            <p class="text-sm text-slate-300 mt-1.5 leading-relaxed">AI-enhanced neighborhood security analytics, resolution times, and safety trend projections.</p>
        </div>
        <div class="flex items-center gap-2.5 bg-indigo-500/10 backdrop-blur border border-indigo-500/30 px-4 py-2.5 rounded-xl relative z-10 self-start md:self-auto">
            <span class="h-2.5 w-2.5 bg-indigo-400 rounded-full animate-ping"></span>
            <span class="text-xs font-black text-indigo-200 uppercase tracking-widest">AI Engine Active</span>
        </div>
        <div class="absolute -right-12 -top-12 w-44 h-44 bg-indigo-600/10 rounded-full filter blur-xl"></div>
        <div class="absolute -left-12 -bottom-12 w-44 h-44 bg-slate-700/10 rounded-full filter blur-xl"></div>
    </div>

    <!-- Core Metrics Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Metric 1: Total Incidents -->
        <div class="glass-card p-6 rounded-2xl border-t-4 border-t-indigo-600 shadow-sm hover:shadow-md transition duration-300 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Reports</p>
                <h3 class="text-3xl font-black text-slate-900 mt-2 font-serif-custom counter" data-target="{{ $totalIncidents }}">0</h3>
                <span class="text-[10px] text-slate-400 font-semibold mt-1 block">Lifetime incident count</span>
            </div>
            <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600 border border-indigo-100/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>

        <!-- Metric 2: Unresolved Incidents -->
        <div class="glass-card p-6 rounded-2xl border-t-4 border-t-red-500 shadow-sm hover:shadow-md transition duration-300 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Active Threats</p>
                <h3 class="text-3xl font-black text-slate-900 mt-2 font-serif-custom counter" data-target="{{ $unresolvedIncidents }}">0</h3>
                <span class="text-[10px] text-red-500 font-semibold mt-1 block flex items-center gap-0.5">
                    <span class="h-1.5 w-1.5 bg-red-500 rounded-full animate-pulse"></span> Requires response
                </span>
            </div>
            <div class="p-3 bg-red-50 rounded-xl text-red-500 border border-red-100/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>

        <!-- Metric 3: Resolved Cases -->
        <div class="glass-card p-6 rounded-2xl border-t-4 border-t-emerald-500 shadow-sm hover:shadow-md transition duration-300 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Resolved Cases</p>
                <h3 class="text-3xl font-black text-slate-900 mt-2 font-serif-custom counter" data-target="{{ $resolvedIncidents }}">0</h3>
                <span class="text-[10px] text-emerald-600 font-semibold mt-1 block">Successfully resolved</span>
            </div>
            <div class="p-3 bg-emerald-50 rounded-xl text-emerald-500 border border-emerald-100/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                </svg>
            </div>
        </div>

        <!-- Metric 4: Avg Resolution Time -->
        <div class="glass-card p-6 rounded-2xl border-t-4 border-t-amber-500 shadow-sm hover:shadow-md transition duration-300 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Avg. Response</p>
                <h3 class="text-3xl font-black text-slate-900 mt-2 font-serif-custom"><span class="counter" data-target="{{ floor($avgResolutionTime) }}">0</span>.{{ round(($avgResolutionTime - floor($avgResolutionTime)) * 10) }} hrs</h3>
                <span class="text-[10px] text-slate-400 font-semibold mt-1 block">Creation-to-resolution avg</span>
            </div>
            <div class="p-3 bg-amber-50 rounded-xl text-amber-500 border border-amber-100/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- AI Command Advice & Insights Feed -->
    <div class="bg-slate-900 text-white p-6 rounded-2xl shadow-xl border border-slate-800 relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex-1">
                <h3 class="text-lg font-black font-serif-custom flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    AI Advisory Feed
                </h3>
                <div class="mt-3 space-y-2.5 text-sm text-slate-400 leading-relaxed">
                    @php
                        // Calculate peak hour
                        $peakHour = array_search(max($fullHourlyData), $fullHourlyData);
                        $peakTime = date('h A', strtotime("$peakHour:00"));
                        
                        // Calculate advice text
                        if ($unresolvedIncidents > 5) {
                            $advice = "CRITICAL ADVISORY: Active response dispatch queue is overloaded (+{$unresolvedIncidents} unresolved reports). Prioritize dispatching active responders immediately to incident zones.";
                            $adviceTheme = 'text-red-400';
                        } elseif ($peakHour >= 20 || $peakHour <= 4) {
                            $advice = "NIGHT-OPS SECURITY PROTOCOL: Calculated threat density peaks during the late-night hours around {$peakTime}. It is highly recommended to schedule responder foot patrols and focus safety lighting during this window.";
                            $adviceTheme = 'text-indigo-300';
                        } else {
                            $advice = "STANDARD OPERATIONS: Community threat levels are stable. Dispatch efficiency is currently averaging {$avgResolutionTime} hours. Maintain active vigilance programs.";
                            $adviceTheme = 'text-emerald-400';
                        }
                    @endphp
                    <p class="font-medium">
                        🤖 <span class="{{ $adviceTheme }} font-bold">{{ $advice }}</span>
                    </p>
                </div>
            </div>
            <div class="bg-slate-800/60 p-4 rounded-xl border border-slate-700/50 self-stretch flex flex-col justify-center text-center">
                <span class="block text-[9px] font-black text-slate-500 uppercase tracking-widest">Calculated Peak</span>
                <span class="text-xl font-black text-indigo-400 mt-1">{{ $peakTime }}</span>
            </div>
        </div>
    </div>

    <!-- Analytics Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- 7-Day Safety Trend & AI Forecast -->
        <div class="lg:col-span-8 glass-card p-6 rounded-2xl shadow-sm">
            <h3 class="text-lg font-black text-slate-950 font-serif-custom mb-6 flex items-center gap-2">
                <span class="h-3.5 w-1.5 bg-emerald-500 rounded-full"></span>
                Safety Trend & AI Threat Forecast
            </h3>
            <div class="relative h-80 w-full">
                <canvas id="trendForecastChart"></canvas>
            </div>
        </div>

        <!-- Incident Category (Doughnut) -->
        <div class="lg:col-span-4 glass-card p-6 rounded-2xl shadow-sm flex flex-col justify-between">
            <h3 class="text-lg font-black text-slate-950 font-serif-custom mb-6 flex items-center gap-2">
                <span class="h-3.5 w-1.5 bg-indigo-600 rounded-full"></span>
                Incident Categories
            </h3>
            <div class="relative h-60 w-full flex items-center justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
            <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-2 gap-2 text-center text-xs">
                <div>
                    <span class="block font-bold text-red-600">{{ $typeData['crime'] }}</span>
                    <span class="text-[9px] font-black uppercase text-slate-400">Crimes</span>
                </div>
                <div>
                    <span class="block font-bold text-indigo-600">{{ $typeData['suspicious'] }}</span>
                    <span class="text-[9px] font-black uppercase text-slate-400">Suspicious</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Clock Hour Grid and Incident Priority Chart Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Polar Clock Danger Distribution -->
        <div class="glass-card p-6 rounded-2xl shadow-sm">
            <h3 class="text-lg font-black text-slate-950 font-serif-custom mb-6 flex items-center gap-2">
                <span class="h-3.5 w-1.5 bg-amber-500 rounded-full"></span>
                Danger Density by Hourly Blocks
            </h3>
            <div class="relative h-80 w-full flex items-center justify-center">
                <canvas id="hourlyPolarChart"></canvas>
            </div>
        </div>

        <!-- Priority Stacked Bars -->
        <div class="glass-card p-6 rounded-2xl shadow-sm">
            <h3 class="text-lg font-black text-slate-950 font-serif-custom mb-6 flex items-center gap-2">
                <span class="h-3.5 w-1.5 bg-red-600 rounded-full"></span>
                Threat Level Priorities
            </h3>
            <div class="relative h-80 w-full flex items-center justify-center">
                <canvas id="priorityChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- 1. Weekly Safety Trend & AI Forecast Chart ---
        const weeklyData = @json($weeklyData);
        const forecastData = @json($forecastData);

        const labels = Object.keys(weeklyData);
        const values = Object.values(weeklyData);

        // Append 3 future forecasted dates
        const lastDate = labels.length > 0 ? new Date(labels[labels.length - 1]) : new Date();
        const forecastLabels = [];
        for (let i = 1; i <= 3; i++) {
            const nextDate = new Date(lastDate);
            nextDate.setDate(lastDate.getDate() + i);
            forecastLabels.push(nextDate.toISOString().split('T')[0]);
        }

        const allLabels = [...labels, ...forecastLabels];
        
        // Pad original values with nulls for the forecast section
        const paddedValues = [...values, ...arrayFill(null, 3)];
        
        // The forecast line will start at the last actual data point
        const forecastValues = [...arrayFill(null, values.length - 1), values[values.length - 1] || 0, ...forecastData];

        function arrayFill(val, len) {
            return Array.from({length: len}, () => val);
        }

        const trendCtx = document.getElementById('trendForecastChart').getContext('2d');
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: allLabels,
                datasets: [
                    {
                        label: 'Actual Reports',
                        data: paddedValues,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.05)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#10b981',
                        pointRadius: 5
                    },
                    {
                        label: 'AI Forecasted Trend',
                        data: forecastValues,
                        borderColor: '#4f46e5',
                        borderDash: [6, 6],
                        backgroundColor: 'rgba(79, 70, 229, 0.03)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4f46e5',
                        pointRadius: 5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { 
                        beginAtZero: true, 
                        ticks: { stepSize: 1, color: '#94a3b8', font: { weight: '600' } }, 
                        grid: { color: '#f1f5f9' } 
                    },
                    x: { 
                        ticks: { color: '#94a3b8', font: { weight: '600', size: 10 } }, 
                        grid: { display: false } 
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { family: 'Inter', size: 11, weight: '600' },
                            color: '#475569'
                        }
                    }
                }
            }
        });

        // --- 2. Category Doughnut Chart ---
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const typeData = @json($typeData);
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Crime', 'Accident', 'Suspicious', 'Other'],
                datasets: [{
                    data: [
                        typeData.crime || 0,
                        typeData.accident || 0,
                        typeData.suspicious || 0,
                        typeData.other || 0
                    ],
                    backgroundColor: ['#ef4444', '#f59e0b', '#4f46e5', '#64748b'],
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
                            font: { family: 'Inter', size: 10, weight: '600' },
                            color: '#475569'
                        }
                    }
                }
            }
        });

        // --- 3. Hourly Danger Blocks Polar Area Chart ---
        const hourlyCtx = document.getElementById('hourlyPolarChart').getContext('2d');
        const hourlyData = @json($fullHourlyData);

        // Consolidate 24 hours into 6-hour blocks (Night, Morning, Afternoon, Evening)
        const blockLabels = ['Night (12am-6am)', 'Morning (6am-12pm)', 'Afternoon (12pm-6pm)', 'Evening (6pm-12am)'];
        const blockValues = [0, 0, 0, 0];

        for (let i = 0; i < 24; i++) {
            const count = hourlyData[i] || 0;
            if (i >= 0 && i < 6) blockValues[0] += count;
            else if (i >= 6 && i < 12) blockValues[1] += count;
            else if (i >= 12 && i < 18) blockValues[2] += count;
            else blockValues[3] += count;
        }

        new Chart(hourlyCtx, {
            type: 'polarArea',
            data: {
                labels: blockLabels,
                datasets: [{
                    data: blockValues,
                    backgroundColor: [
                        'rgba(79, 70, 229, 0.75)',  // Indigo (Night)
                        'rgba(16, 185, 129, 0.75)', // Emerald (Morning)
                        'rgba(245, 158, 11, 0.75)',  // Amber (Afternoon)
                        'rgba(239, 68, 68, 0.75)'   // Red (Evening)
                    ],
                    borderWidth: 1,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        grid: { color: '#e2e8f0' },
                        ticks: { color: '#94a3b8', font: { weight: '600' } }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { family: 'Inter', size: 10, weight: '600' },
                            color: '#475569'
                        }
                    }
                }
            }
        });

        // --- 4. Incident Priority Horizontal Bar Chart ---
        const priorityCtx = document.getElementById('priorityChart').getContext('2d');
        const priorityData = @json($priorityData);
        new Chart(priorityCtx, {
            type: 'bar',
            data: {
                labels: ['Low', 'Medium', 'High', 'Critical'],
                datasets: [{
                    label: 'Reports',
                    data: [
                        priorityData.low || 0,
                        priorityData.medium || 0,
                        priorityData.high || 0,
                        priorityData.critical || 0
                    ],
                    backgroundColor: ['#64748b', '#3b82f6', '#f59e0b', '#ef4444'],
                    borderRadius: 8,
                    maxBarThickness: 35
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { 
                        beginAtZero: true, 
                        ticks: { stepSize: 1, color: '#94a3b8', font: { weight: '600' } },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { color: '#94a3b8', font: { weight: '600' } },
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
        
        const observer = new IntersectionObserver((entries) => {
            if(entries[0].isIntersecting) animate();
        }, { threshold: 0.5 });
        
        observer.observe(counter);
    });
</script>
@endpush
