<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    protected $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $userMessage = $request->input('message');
        \Log::info('AI Chat Request: ' . $userMessage);

        $neighborhoodName = auth()->user()->neighborhood_name;

        // Fetch Real-Time Context from User's Neighborhood
        $totalIncidents = \App\Models\Report::whereHas('user', function($q) use ($neighborhoodName) {
            $q->where('neighborhood_name', $neighborhoodName);
        })->count();

        $recentIncidents = \App\Models\Report::whereHas('user', function($q) use ($neighborhoodName) {
            $q->where('neighborhood_name', $neighborhoodName);
        })->latest()->take(3)->get()->map(function($r) {
            return "Type: {$r->type}, Title: {$r->title}, Location: {$r->location}";
        })->implode('; ');

        // Specialized System Prompt with Real Data
        $systemPrompt = "You are the 'SafetyNet AI Guardian', a specialized educational safety assistant for neighborhood residents. 
        Your goal is to provide helpful, general first-aid steps and neighborhood safety advice based on current data.
        
        CURRENT NEIGHBORHOOD CONTEXT:
        - System-wide Incidents: {$totalIncidents}
        - Recent Activities: {$recentIncidents}

        OPERATING PROTOCOLS:
        1. For medical situations: Provide standard FIRST-AID GUIDANCE (e.g., Heimlich, CPR, basic wound care). Do NOT say 'I cannot help with medical'. Instead, say 'While you wait for professionals, here is standard first-aid...'.
        2. For safety: Use the context above to warn the user or provide peace of mind.
        3. Tone: Calm, fast-acting, and helpful.
        4. Disclaimer: Remind them once per chat that you are an AI and they should call emergency services (Police/Ambulance) for serious threats.";

        try {
            $response = $this->gemini->generateResponse($userMessage, $systemPrompt);
            return response()->json(['response' => $response]);
        } catch (\Exception $e) {
            Log::error('AI Chat Error: ' . $e->getMessage());
            return response()->json(['response' => "I'm sorry, I'm having trouble connecting to my safety protocols right now."], 500);
        }
    }
}
