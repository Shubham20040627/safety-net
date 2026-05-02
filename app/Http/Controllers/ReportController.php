<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with('user')->latest();

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('location', 'like', '%' . $request->search . '%');
        }

        $reports = $query->paginate(10);

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:crime,accident,suspicious,other',
            'location' => 'required|string|max:255',
            'datetime' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['status'] = 'pending';

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('reports', 'public');
        }

        Report::create($data);

        return redirect()->route('reports.index')->with('success', 'Incident reported successfully.');
    }

    public function myReports()
    {
        $reports = auth()->user()->reports()->latest()->paginate(10);
        return view('reports.my-reports', compact('reports'));
    }
}
