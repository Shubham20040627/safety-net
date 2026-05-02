<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users()
    {
        $users = User::where('id', '!=', auth()->id())->latest()->paginate(10);
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
        $reports = Report::with('user')->latest()->paginate(10);
        return view('admin.reports', compact('reports'));
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
}
