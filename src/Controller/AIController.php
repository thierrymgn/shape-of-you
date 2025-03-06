<?php
// src/Controller/AIController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;

class AIController extends AbstractController
{
    #[Route('/ai/analyze', name: 'app_ai_analyze', methods: ['POST'])]
    public function analyze(Request $request): JsonResponse
    {
        // Récupérer le fichier image depuis le formulaire
        $uploadedFile = $request->files->get('image');
        if (!$uploadedFile) {
            return new JsonResponse(['error' => 'Aucun fichier image fourni.'], 400);
        }

        // Lire le contenu du fichier
        $imageContent = file_get_contents($uploadedFile->getPathname());
        $imageData = base64_encode($imageContent);
        $sampleImageData = substr($imageData, 0, 100) . '...';

        // Construire le prompt pour l'API OpenAI
        $prompt = "Analyse the following clothing item image data (base64 snippet): $sampleImageData\n\n"
                . "Determine the following attributes of the garment and return a valid JSON object with these keys:\n"
                . "- name (the name of the garment),\n"
                . "- brand (the brand of the garment),\n"
                . "- color (the main color),\n"
                . "- category (e.g., shirt, pants, dress, etc.),\n"
                . "- description (a brief description of the garment).\n"
                . "If any information is missing, return an empty string for that key.";

        // Créer un client HTTP
        $client = HttpClient::create();

        // Récupérer la clé API depuis l'environnement
        $apiKey = trim($_ENV['OPENAI_API_KEY_WARDROBE']);

        if (!$apiKey) {
            return new JsonResponse(['error' => 'Clé API non configurée.'], 500);
        }

        try {
            // Appeler l'API OpenAI
            $response = $client->request('POST', 'https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo-0125', // ou "gpt-4" si vous y avez accès
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an expert in clothing analysis. Extract garment details from image data.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ]
                    ],
                    'temperature' => 0.7,
                ]
            ]);

            $result = $response->toArray();
            $contentString = $result['choices'][0]['message']['content'] ?? '';
            // Nettoyer le contenu si nécessaire (par exemple, retirer les délimiteurs Markdown)
            $cleanContent = preg_replace('/```(json)?/', '', $contentString);
            $cleanContent = trim($cleanContent);

            $parsedOutput = json_decode($cleanContent, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return new JsonResponse([
                    'error' => 'Erreur lors du parsing JSON: ' . json_last_error_msg(),
                    'raw_output' => $cleanContent
                ], 500);
            }
            return new JsonResponse($parsedOutput);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}