<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatGPTController extends Controller
{
    public function index()
    {
        try {
            $response = Http::timeout(120)->retry(3, 100)
                ->withHeaders([
                    'Authorization' => 'Bearer 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',
                    'Content-Type' => 'application/json'
                ])
                ->post("https://api.openai.com/v1/chat/completions", [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                        ['role' => 'user', 'content' => 'What is the weather like?'],
                    ],
                ]);

            if ($response->successful() && $response->status() == "200") {
                $response = json_decode($response->json());

                return response()->json([
                    'message' => 'Successfully fetched response from GPT-3.5 Turbo.',
                    'response' => $response,
                ]);

                // ************* Evaluate the quality of the AI response by comparing it against pre-defined criteria (e.g., relevance, clarity, and tone) ************* //

                // For Relevance Analysis:
                    // 1. Use cosine similarity to compare the AI response with the user query.
                        // (It converts texts to vectors and calculates cosine similarity)

                // For Tone Analysis:
                    // 1. Google Cloud NLP API can be used for sentiment and syntax analysis.
                    // 2. IBM Watson Tone Analyzer API

                // For Clarity Analysis:
                    // 3. "Textstat" Python library can be used to determine readability, complexity, and grade level.

            }
        } catch (\Exception $ex) {
            return response()->json([
                'message' => 'Error finding location address => ' . $ex->getMessage(),
            ]);

            Log::error('Error finding location address => ' . $ex->getMessage());
        }
    }
}
