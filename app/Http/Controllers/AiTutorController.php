<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiTutorController extends Controller
{
    /**
     * Handle the student's question using Groq API.
     */
    public function askQuestion(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'question'  => 'required|string|max:1000',
        ]);

        $lesson     = Lesson::findOrFail($request->lesson_id);
        $transcript = $lesson->transcript ?? '';
        $question   = $request->question;

        // System prompt
        $systemPrompt = "You are an AI tutor for EduBridge, an online learning platform. 
Your job is to help students understand their lesson content.
First, answer using ONLY the following transcript provided by the instructor.
If the transcript is empty or does not contain the answer, use your general knowledge to help, but you MUST politely state: \"The instructor didn't cover this specific detail in the lesson notes, but generally speaking...\"
Keep your answers clear, helpful, and student-friendly.";

        // User message combining transcript + question
        $userMessage = "--- LESSON TRANSCRIPT ---
{$transcript}
--- END TRANSCRIPT ---

STUDENT QUESTION: {$question}";

        $apiKey   = env('GROQ_API_KEY');
        $endpoint = "https://api.groq.com/openai/v1/chat/completions";

        try {
            $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type'  => 'application/json',
                ])
                ->post($endpoint, [
                    'model'    => 'llama-3.3-70b-versatile',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user',   'content' => $userMessage],
                    ],
                    'temperature' => 0.7,
                    'max_tokens'  => 1024,
                ]);

            $data   = $response->json();
            $aiText = $data['choices'][0]['message']['content'] ?? null;

            if (!$aiText) {
                Log::error('Groq API Error: ' . json_encode($data));
                return response()->json(['error' => 'The AI Assistant is temporarily unavailable. Please try again.'], 500);
            }

            return response()->json(['answer' => $aiText]);

        } catch (\Exception $e) {
            Log::error('AI Tutor Exception: ' . $e->getMessage());
            return response()->json(['error' => 'Connection error. Please try again.'], 500);
        }
    }
}
