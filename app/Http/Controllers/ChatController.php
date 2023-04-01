<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ChatController extends Controller
{
    private const OPEN_AI_URL = 'https://api.openai.com/v1/chat/completions';
    private const OPEN_AI_MODEL = "gpt-3.5-turbo";
    private const OPEN_AI_TEMPERATURE = 0.7;

    public function chat(Request $request)    
    {
        $message = $request->input('message');

        $requestHeaders = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
        ];

        $requestJson = [
            "model" => self::OPEN_AI_MODEL,
            "temperature" => self::OPEN_AI_TEMPERATURE,
            "messages" => json_decode('[{"role": "user", "content": "' . $message . '"}]', true)
        ];

        $client = new Client();
        $response = $client->post(self::OPEN_AI_URL, [
            'headers' => $requestHeaders,
            'json' => $requestJson,
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        return response()->json($result['choices'][0]['message']['content']);
    }
}
