<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalReports = Report::count();
        $myReports = auth()->user()->reports()->count();
        
        // Latest reports for the dashboard preview
        $latestReports = Report::with('user')->latest()->take(5)->get();

        return view('dashboard', compact('totalReports', 'myReports', 'latestReports'));
    }
}
