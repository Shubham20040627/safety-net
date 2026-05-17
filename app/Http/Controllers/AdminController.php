<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users()
    {
        $neighborhoodName = auth()->user()->neighborhood_name;
        $users = User::where('id', '!=', auth()->id())
            ->where('neighborhood_name', $neighborhoodName)
            ->latest()
            ->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function approveUser(User $user)
    {
        $user->update(['status' => 'approved']);
        return back()->with('success', 'User approved successfully.');
    }

    public function rejectUser(User $user)
    {
        $user->update(['status' => 'rejected']);
        return back()->with('success', 'User rejected successfully.');
    }

    public function reports()
    {
        $neighborhoodName = auth()->user()->neighborhood_name;

        $reports = Report::whereHas('user', function($query) use ($neighborhoodName) {
            $query->where('neighborhood_name', $neighborhoodName);
        })->with('user', 'responder')->latest()->paginate(10);

        $responders = User::where('role', 'responder')
            ->where('neighborhood_name', $neighborhoodName)
            ->get();

        return view('admin.reports', compact('reports', 'responders'));
    }

    public function resolveReport(Report $report)
    {
        $report->update(['status' => 'resolved']);
        return back()->with('success', 'Report marked as resolved.');
    }

    public function deleteReport(Report $report)
    {
        $report->delete();
        return back()->with('success', 'Report deleted successfully.');
    }

    public function makeResponder(User $user)
    {
        $user->update(['role' => 'responder']);
        return back()->with('success', 'User promoted to Responder.');
    }

    public function removeResponder(User $user)
    {
        $user->update(['role' => 'user']);
        return back()->with('success', 'User demoted to regular User.');
    }

    public function assignResponder(Request $request, Report $report)
    {
        $request->validate([
            'responder_id' => 'required|exists:users,id',
        ]);

        $report->update([
            'responder_id' => $request->responder_id,
            'status' => 'investigating'
        ]);

        return back()->with('success', 'Responder assigned and report is now being investigated.');
    }

    public function analytics()
    {
        $neighborhoodName = auth()->user()->neighborhood_name;

        $baseQuery = Report::whereHas('user', function($query) use ($neighborhoodName) {
            $query->where('neighborhood_name', $neighborhoodName);
        });

        // 1. Core Metrics
        $totalIncidents = (clone $baseQuery)->count();
        $unresolvedIncidents = (clone $baseQuery)->where('status', '!=', 'resolved')->count();
        $resolvedIncidents = (clone $baseQuery)->where('status', 'resolved')->count();
        
        // Calculate average resolution time (in hours)
        $resolvedReports = (clone $baseQuery)->where('status', 'resolved')
            ->whereNotNull('created_at')
            ->whereNotNull('updated_at')
            ->get();
            
        $totalHours = 0;
        $count = $resolvedReports->count();
        foreach ($resolvedReports as $report) {
            $created = \Carbon\Carbon::parse($report->created_at);
            $updated = \Carbon\Carbon::parse($report->updated_at);
            $totalHours += $created->diffInHours($updated);
        }
        $avgResolutionTime = $count > 0 ? round($totalHours / $count, 1) : 0;

        // 2. Incident Category Distribution
        $typeData = (clone $baseQuery)->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        // Ensure all types exist in types list
        $typesList = ['crime', 'accident', 'suspicious', 'other'];
        foreach ($typesList as $t) {
            if (!isset($typeData[$t])) {
                $typeData[$t] = 0;
            }
        }

        // 3. Priority Distribution
        $priorityData = (clone $baseQuery)->selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();
        $priorityList = ['low', 'medium', 'high', 'critical'];
        foreach ($priorityList as $p) {
            if (!isset($priorityData[$p])) {
                $priorityData[$p] = 0;
            }
        }

        // 4. Hourly Peak Distribution
        $hourlyData = (clone $baseQuery)->selectRaw('HOUR(datetime) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();
        $fullHourlyData = array_fill(0, 24, 0);
        foreach ($hourlyData as $hour => $c) {
            $fullHourlyData[$hour] = $c;
        }

        // 5. Weekly Safety Trend & AI Forecast
        $weeklyData = (clone $baseQuery)->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Simple linear progression trend forecast for next 3 days
        $forecastData = [];
        $dates = array_keys($weeklyData);
        $counts = array_values($weeklyData);
        $n = count($counts);
        
        if ($n > 1) {
            // Calculate simple slope (m)
            $sumX = 0; $sumY = 0; $sumXY = 0; $sumXX = 0;
            for ($i = 0; $i < $n; $i++) {
                $sumX += $i;
                $sumY += $counts[$i];
                $sumXY += $i * $counts[$i];
                $sumXX += $i * $i;
            }
            $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumXX - $sumX * $sumX);
            $intercept = ($sumY - $slope * $sumX) / $n;
            
            // Forecast 3 days ahead
            for ($i = $n; $i < $n + 3; $i++) {
                $forecastVal = max(0, round($slope * $i + $intercept, 1));
                $forecastData[] = $forecastVal;
            }
        } else {
            $forecastData = [0, 0, 0];
        }

        return view('admin.analytics', compact(
            'totalIncidents', 'unresolvedIncidents', 'resolvedIncidents', 'avgResolutionTime',
            'typeData', 'priorityData', 'fullHourlyData', 'weeklyData', 'forecastData'
        ));
    }
}
