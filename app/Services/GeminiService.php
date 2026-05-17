<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected $apiKey;
    protected $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY') ?: env('GOOGLE_API_KEY');
    }

    public function analyzeIncident($title, $description, $type)
    {
        if (!$this->apiKey) {
            return [
                'summary' => 'AI Analysis is pending (API Key not set).',
                'advice' => 'Please contact the administrator to enable real-time AI safety guidance.'
            ];
        }

        $prompt = "You are a neighborhood safety AI expert. Analyze this incident:
        Title: {$title}
        Description: {$description}
        Type: {$type}

        Please provide your response in JSON format with exactly two keys:
        1. 'summary': A professional 1-sentence executive summary of the incident.
        2. 'advice': 3 concise, bullet-pointed safety action steps for residents.
        
        Do not include any other text in your response, only the raw JSON.";

        return $this->callGemini($prompt, true);
    }

    public function generateResponse($message, $systemPrompt)
    {
        if (!$this->apiKey) return "AI services are currently offline.";

        $fullPrompt = "{$systemPrompt}\n\nUser Question: {$message}";

        $result = $this->callGemini($fullPrompt, false);
        return $result['text'] ?? "I'm sorry, I couldn't process that request.";
    }

    protected function callGemini($prompt, $isJson = false)
    {
        try {
            $response = Http::timeout(10)->post("{$this->endpoint}?key={$this->apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                if (empty($result['candidates'])) {
                    \Log::warning('Gemini Safety Block: ' . json_encode($result));
                    return $isJson ? [
                        'summary' => 'Incident reported. AI analysis withheld for safety.',
                        'advice' => 'Contact emergency services immediately.'
                    ] : ['text' => 'My safety protocols are preventing a direct answer to this specific query.'];
                }

                $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
                
                if ($isJson) {
                    $text = str_replace(['```json', '```'], '', $text);
                    $data = json_decode(trim($text), true);
                    return [
                        'summary' => $data['summary'] ?? 'Analysis complete.',
                        'advice' => $data['advice'] ?? 'Stay vigilant.'
                    ];
                }

                return ['text' => $text ?: "I am here to help. Please rephrase your question."];
            } else {
                \Log::error('Gemini API Failure: ' . $response->status() . ' - ' . $response->body());
            }
        } catch (\Exception $e) {
            \Log::error('Gemini Service Exception: ' . $e->getMessage());
        }

        return $isJson ? ['summary' => 'Error', 'advice' => 'Error'] : ['text' => null];
    }
}
