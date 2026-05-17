@extends('layouts.app')

@section('header_title', auth()->user()->role === 'admin' ? 'Active Dispatches' : 'My Assignments')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800">{{ auth()->user()->role === 'admin' ? 'Active Responder Dispatches' : 'Incidents Assigned to You' }}</h3>
            <p class="text-sm text-gray-500">{{ auth()->user()->role === 'admin' ? 'Monitor current response operations in the neighborhood.' : 'Investigate and resolve these incidents.' }}</p>
        </div>
        
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Report</th>
                    @if(auth()->user()->role === 'admin')
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Assigned Responder</th>
                    @endif
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Priority</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reports as $report)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ $report->title }}</div>
                            <div class="text-[10px] text-gray-400 mt-0.5">{{ $report->location }} | {{ \Carbon\Carbon::parse($report->datetime)->format('M d, Y') }}</div>
                        </td>
                        @if(auth()->user()->role === 'admin')
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold text-gray-700">
                                    {{ $report->responder ? $report->responder->name : 'Unassigned' }}
                                </span>
                            </td>
                        @endif
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase
                                {{ $report->type == 'crime' ? 'bg-red-100 text-red-600' : '' }}
                                {{ $report->type == 'accident' ? 'bg-yellow-100 text-yellow-600' : '' }}
                                {{ $report->type == 'suspicious' ? 'bg-purple-100 text-purple-600' : '' }}
                                {{ $report->type == 'other' ? 'bg-gray-100 text-gray-600' : '' }}">
                                {{ $report->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-black px-2 py-0.5 rounded-full uppercase
                                {{ $report->priority == 'critical' ? 'bg-red-600 text-white shadow-sm' : '' }}
                                {{ $report->priority == 'high' ? 'bg-orange-100 text-orange-600 border border-orange-200' : '' }}
                                {{ $report->priority == 'medium' ? 'bg-blue-100 text-blue-600 border border-blue-200' : '' }}
                                {{ $report->priority == 'low' ? 'bg-slate-100 text-slate-600 border border-slate-200' : '' }}">
                                {{ $report->priority }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase 
                                {{ $report->status == 'resolved' ? 'bg-green-100 text-green-600' : 'bg-indigo-100 text-indigo-600' }}">
                                {{ $report->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('reports.show', $report) }}" class="bg-white hover:bg-gray-50 text-gray-700 px-3 py-1.5 rounded-lg text-xs font-bold border border-gray-200 transition shadow-sm">
                                    View Details
                                </a>

                                @if($report->status !== 'resolved')
                                    @if($report->latitude && $report->longitude)
                                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $report->latitude }},{{ $report->longitude }}" target="_blank" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-sm flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Navigate
                                        </a>
                                    @endif

                                    <form action="{{ route('reports.resolve-assigned', $report) }}" method="POST" onsubmit="return confirm('Are you sure you want to mark this incident as resolved?')">
                                        @csrf
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-sm flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Mark Resolved
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role === 'admin' ? 6 : 5 }}" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                <span class="font-medium text-sm">{{ auth()->user()->role === 'admin' ? 'No active dispatches' : 'No active assignments' }}</span>
                                <span class="text-xs text-gray-400 mt-1">{{ auth()->user()->role === 'admin' ? 'There are currently no active dispatches in the community.' : 'You currently have no incidents assigned to you.' }}</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $reports->links() }}
    </div>
</div>
@endsection
