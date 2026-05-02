@extends('layouts.app')

@section('header_title', 'Manage Reports')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-800">All Reported Incidents</h3>
                <p class="text-sm text-gray-500">Monitor and resolve neighborhood incidents.</p>
            </div>
        </div>
        
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Report</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Reporter</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($reports as $report)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ $report->title }}</div>
                            <div class="text-[10px] text-gray-400 mt-0.5">{{ $report->location }} | {{ \Carbon\Carbon::parse($report->datetime)->format('M d, Y') }}</div>
                        </td>
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
                            <div class="text-sm font-medium text-gray-700">{{ $report->user->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase 
                                {{ $report->status == 'resolved' ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-600' }}">
                                {{ $report->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                @if($report->status !== 'resolved')
                                    <form action="{{ route('admin.reports.resolve', $report) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-sm">
                                            Resolve
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('admin.reports.delete', $report) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this report?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition shadow-sm">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $reports->links() }}
    </div>
</div>
@endsection
