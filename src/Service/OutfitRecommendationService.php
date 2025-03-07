<?php

namespace App\Service;

use App\Entity\AiAnalysis;
use App\Entity\Outfit;
use App\Entity\OutfitItem;
use App\Entity\User;
use App\Entity\WardrobeItem;
use App\Enum\AnalysisType;
use App\Enum\WardrobeSeason;
use App\Enum\WardrobeStatus;
use App\Repository\OutfitRepository;
use App\Repository\WardrobeItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenAI;

class OutfitRecommendationService
{
    private OpenAI\Client $client;
    private EntityManagerInterface $entityManager;
    private WardrobeItemRepository $wardrobeItemRepository;
    private OutfitRepository $outfitRepository;
    private string $modelId;

    public function __construct(
        EntityManagerInterface $entityManager,
        WardrobeItemRepository $wardrobeItemRepository,
        OutfitRepository $outfitRepository,
        string $openaiApiKey,
        string $openaiModelId = 'gpt-4o-mini',
    ) {
        $this->client = \OpenAI::client($openaiApiKey);
        $this->entityManager = $entityManager;
        $this->wardrobeItemRepository = $wardrobeItemRepository;
        $this->outfitRepository = $outfitRepository;
        $this->modelId = $openaiModelId;
    }

    /**
     * Génère des suggestions de tenues pour un utilisateur.
     *
     * @param User                $user     L'utilisateur pour lequel générer des suggestions
     * @param string              $occasion L'occasion pour laquelle générer une tenue (casual, formel, sport, etc.)
     * @param WardrobeSeason|null $season   La saison pour laquelle générer une tenue
     * @param int                 $count    Le nombre de suggestions à générer
     *
     * @return array<Outfit> Les tenues suggérées
     */
    public function suggestOutfits(
        User $user,
        string $occasion,
        ?WardrobeSeason $season = null,
        int $count = 3,
    ): array {
        if (!$season) {
            $month = (int) date('n');
            $season = match (true) {
                $month >= 3 && $month <= 5 => WardrobeSeason::SPRING,
                $month >= 6 && $month <= 8 => WardrobeSeason::SUMMER,
                $month >= 9 && $month <= 11 => WardrobeSeason::AUTUMN,
                default => WardrobeSeason::WINTER,
            };
        }

        $userItems = $this->wardrobeItemRepository->findBy([
            'customer' => $user,
            'status' => WardrobeStatus::ACTIVE,
        ]);

        $seasonItems = array_filter($userItems, function (WardrobeItem $item) use ($season) {
            return $item->getSeason() === $season || WardrobeSeason::ALL === $item->getSeason();
        });

        if (empty($seasonItems)) {
            $seasonItems = $userItems;
        }

        $categorizedItems = [];
        foreach ($seasonItems as $item) {
            $category = $item->getCategory() ? $item->getCategory()->getName() : 'Autre';

            if (!isset($categorizedItems[$category])) {
                $categorizedItems[$category] = [];
            }

            $categorizedItems[$category][] = $item;
        }

        $itemsData = [];
        foreach ($categorizedItems as $category => $items) {
            foreach ($items as $item) {
                $itemsData[] = [
                    'id' => $item->getId(),
                    'name' => $item->getName(),
                    'color' => $item->getColor(),
                    'category' => $category,
                    'brand' => $item->getBrand(),
                ];
            }
        }

        $systemPrompt = 'Tu es un styliste expert qui crée des tenues harmonieuses et adaptées aux occasions. '.
            'Analyse les vêtements disponibles et propose des combinaisons optimales.';

        $userPrompt = json_encode([
            'occasion' => $occasion,
            'season' => $season->value,
            'count' => $count,
            'items' => $itemsData,
        ]);

        $response = $this->client->chat()->create([
            'model' => $this->modelId,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemPrompt,
                ],
                [
                    'role' => 'user',
                    'content' => "Crée $count tenues pour l'occasion \"$occasion\" pendant la saison ".
                        strtolower($season->value).' en utilisant les vêtements suivants: '.
                        $userPrompt,
                ],
            ],
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => [
                    'name' => 'outfit_suggestions',
                    'strict' => true,
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'outfits' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'name' => ['type' => 'string'],
                                        'description' => ['type' => 'string'],
                                        'items' => [
                                            'type' => 'array',
                                            'items' => [
                                                'type' => 'object',
                                                'properties' => [
                                                    'id' => ['type' => 'integer'],
                                                    'position' => ['type' => 'integer'],
                                                ],
                                                'required' => ['id', 'position'],
                                                'additionalProperties' => false,
                                            ],
                                        ],
                                    ],
                                    'required' => ['name', 'description', 'items'],
                                    'additionalProperties' => false,
                                ],
                            ],
                        ],
                        'required' => ['outfits'],
                        'additionalProperties' => false,
                    ],
                ],
            ],
            'max_tokens' => 2000,
        ]);

        $suggestionsData = json_decode($response->choices[0]->message->content, true);

        if (empty($suggestionsData) || !isset($suggestionsData['outfits']) || empty($suggestionsData['outfits'])) {
            return [];
        }

        $outfits = [];
        foreach ($suggestionsData['outfits'] as $outfitData) {
            $outfit = new Outfit();
            $outfit->setName($outfitData['name']);
            $outfit->setDescription($outfitData['description']);
            $outfit->setOccasion($occasion);
            $outfit->setCustomer($user);
            $outfit->setSeason($season);
            $outfit->setPublic(false);

            if (isset($outfitData['items']) && !empty($outfitData['items'])) {
                foreach ($outfitData['items'] as $itemData) {
                    $itemId = $itemData['id'];
                    $position = $itemData['position'];

                    $item = $this->findItemById($userItems, $itemId);

                    if ($item) {
                        $outfitItem = new OutfitItem();
                        $outfitItem->setWardrobeItem($item);
                        $outfitItem->setPosition($position);
                        $outfitItem->setOutfit($outfit);

                        $outfit->addOutfitItem($outfitItem);
                    }
                }
            }

            $this->entityManager->persist($outfit);

            $analysis = new AiAnalysis();
            $analysis->setAnalysisType(AnalysisType::OUTFIT);
            $analysis->setResults([
                'suggestion' => true,
                'occasion' => $occasion,
                'season' => $season->value,
                'items' => $outfitData['items'],
                'reasoning' => $outfitData['description'] ?? '',
            ]);
            $analysis->setConfidenceScore('85.00');
            $analysis->setCreatedAt(new \DateTimeImmutable());
            $analysis->setOutfitId($outfit);

            $this->entityManager->persist($analysis);

            $outfits[] = $outfit;
        }

        $this->entityManager->flush();

        return $outfits;
    }

    /**
     * Trouve le vêtement correspondant à l'ID spécifié.
     *
     * @param array<WardrobeItem> $items Tableau des vêtements à parcourir
     * @param int                 $id    ID du vêtement à rechercher
     */
    private function findItemById(array $items, int $id): ?WardrobeItem
    {
        foreach ($items as $item) {
            if ($item->getId() === $id) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Analyse le style personnel d'un utilisateur.
     *
     * @param User $user L'utilisateur dont on analyse le style
     *
     * @return array<string, mixed> Les résultats de l'analyse
     */
    public function analyzeUserStyle(User $user): array
    {
        $outfits = $this->outfitRepository->findBy(['customer' => $user]);
        $items = $this->wardrobeItemRepository->findBy(['customer' => $user]);

        if (empty($outfits) && empty($items)) {
            return [
                'status' => 'insufficient_data',
                'message' => 'Pas assez de données pour analyser le style.',
            ];
        }

        $itemDescriptions = [];
        foreach ($items as $item) {
            $category = $item->getCategory() ? $item->getCategory()->getName() : 'Autre';
            $itemDescriptions[] = [
                'type' => $item->getName(),
                'category' => $category,
                'color' => $item->getColor(),
                'brand' => $item->getBrand(),
            ];
        }

        $outfitDescriptions = [];
        foreach ($outfits as $outfit) {
            $outfitItems = [];
            foreach ($outfit->getOutfitItems() as $outfitItem) {
                $item = $outfitItem->getWardrobeItem();
                $outfitItems[] = [
                    'type' => $item->getName(),
                    'color' => $item->getColor(),
                ];
            }

            $outfitDescriptions[] = [
                'name' => $outfit->getName(),
                'occasion' => $outfit->getOccasion(),
                'season' => $outfit->getSeason()->value,
                'items' => $outfitItems,
            ];
        }

        $analysisData = [
            'wardrobe' => $itemDescriptions,
            'outfits' => $outfitDescriptions,
        ];

        $systemPrompt = 'Tu es un expert en analyse de style vestimentaire qui donne des conseils personnalisés. '.
            "Analyse l'ensemble des vêtements et des tenues pour déterminer le style dominant, les préférences ".
            'de couleurs, et fournir des recommandations pertinentes.';

        $response = $this->client->chat()->create([
            'model' => $this->modelId,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemPrompt,
                ],
                [
                    'role' => 'user',
                    'content' => 'Analyse le style vestimentaire de cette personne en fonction de sa garde-robe et de ses tenues: '.
                        json_encode($analysisData),
                ],
            ],
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => [
                    'name' => 'style_analysis',
                    'strict' => true,
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'styles' => [
                                'type' => 'array',
                                'items' => ['type' => 'string'],
                            ],
                            'colors' => [
                                'type' => 'array',
                                'items' => ['type' => 'string'],
                            ],
                            'combinations' => [
                                'type' => 'array',
                                'items' => ['type' => 'string'],
                            ],
                            'recommendations' => [
                                'type' => 'array',
                                'items' => ['type' => 'string'],
                            ],
                            'coherence_score' => ['type' => 'integer'],
                        ],
                        'required' => ['styles', 'colors', 'recommendations', 'coherence_score'],
                        'additionalProperties' => false,
                    ],
                ],
            ],
            'max_tokens' => 2000,
        ]);

        $analysisResult = json_decode($response->choices[0]->message->content, true);

        if (empty($analysisResult)) {
            return [
                'status' => 'analysis_failed',
                'message' => 'L\'analyse du style a échoué. Veuillez réessayer.',
            ];
        }

        $analysis = new AiAnalysis();
        $analysis->setAnalysisType(AnalysisType::OUTFIT);
        $analysis->setResults($analysisResult);
        $analysis->setConfidenceScore('90.00');
        $analysis->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($analysis);
        $this->entityManager->flush();

        return $analysisResult;
    }
}
