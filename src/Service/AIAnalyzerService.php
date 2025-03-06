<?php
// src/Service/AIAnalyzerService.php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AIAnalyzerService
{
    private HttpClientInterface $client;
    private string $openaiApiKeyWardrobe;
    
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
        // Récupération directe de la clé depuis l'environnement
        $this->openaiApiKeyWardrobe = $_ENV['openai_api_key_wardrobe'] ?? '';
        // Vous pouvez également utiliser getenv('OPENAI_API_KEY_WARDROBE') si vous préférez
    }
    
    public function analyzeImage(string $imagePath): array
    {
        $imageContent = file_get_contents($imagePath);
        $imageData = base64_encode($imageContent);
        $sampleImageData = substr($imageData, 0, 100) . '...';
        
        $prompt = "Analyse the following clothing item image data (base64 snippet): $sampleImageData\n\n"
            . "Determine the following attributes of the garment and return a valid JSON object with these keys:\n"
            . "- color (the main color of the garment),\n"
            . "- brand (the brand name, if available),\n"
            . "- name (the name of the garment),\n"
            . "- category (for example: shirt, pants, dress, etc.).\n"
            . "If any information is not available, return an empty string for that key. Only return a JSON object.";
        
        $response = $this->client->request('POST', 'https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->openaiApiKeyWardrobe,
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'model'       => 'gpt-4',
                'messages'    => [
                    [
                        'role'    => 'system',
                        'content' => 'You are a helpful assistant that extracts garment details from image data.',
                    ],
                    [
                        'role'    => 'user',
                        'content' => $prompt,
                    ],
                ],
                'temperature' => 0.7,
            ],
        ]);
        
        $result = $response->toArray();
        $aiOutput = $result['choices'][0]['message']['content'] ?? '';
        $parsedOutput = json_decode($aiOutput, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('JSON parsing error: ' . $aiOutput);
        }
        return $parsedOutput;
    }
}