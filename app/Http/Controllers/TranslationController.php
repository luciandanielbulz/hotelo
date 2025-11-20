<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationController extends Controller
{
    /**
     * Übersetzt einen Text mit GPT
     */
    public function translate(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:2000',
            'target_language' => 'nullable|string|max:50',
        ]);

        $apiKey = env('OPENAI_API_KEY');
        
        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'OpenAI API Key ist nicht konfiguriert.'
            ], 500);
        }

        $text = $request->input('text');
        $targetLanguage = $request->input('target_language', 'Deutsch');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Du bist ein professioneller Übersetzer. Übersetze den gegebenen Text präzise und natürlich ins {$targetLanguage}. Behalte die Formatierung bei und verwende eine professionelle, geschäftliche Sprache."
                    ],
                    [
                        'role' => 'user',
                        'content' => $text
                    ]
                ],
                'temperature' => 0.3,
                'max_tokens' => 1000,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $translatedText = $data['choices'][0]['message']['content'] ?? null;

                if ($translatedText) {
                    return response()->json([
                        'success' => true,
                        'translated_text' => trim($translatedText),
                    ]);
                } else {
                    Log::error('OpenAI API Response missing content', ['response' => $data]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Keine Übersetzung in der API-Antwort gefunden.'
                    ], 500);
                }
            } else {
                $error = $response->json();
                Log::error('OpenAI API Error', [
                    'status' => $response->status(),
                    'error' => $error
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Fehler bei der Übersetzung: ' . ($error['error']['message'] ?? 'Unbekannter Fehler')
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Translation Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Fehler bei der Verbindung zur OpenAI API: ' . $e->getMessage()
            ], 500);
        }
    }
}

