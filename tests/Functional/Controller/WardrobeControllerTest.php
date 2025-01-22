<?php

namespace App\Tests\Functional\Controller;

use App\DataFixtures\CategoryFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Category;
use App\Entity\User;
use App\Enum\WardrobeSeason;
use App\Enum\WardrobeStatus;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class WardrobeControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private AbstractDatabaseTool $databaseTool;
    private User $user;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();

        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();

        $fixtures = $this->databaseTool->loadFixtures([
            UserFixtures::class,
            CategoryFixtures::class,
        ]);

        $userRepository = static::getContainer()->get(UserRepository::class);
        $categoryRepository = static::getContainer()->get(CategoryRepository::class);

        $this->user = $userRepository->findOneBy([]);
        $this->category = $categoryRepository->findOneBy([]);

        if (!$this->user || !$this->category) {
            throw new \RuntimeException('Test user or category not found');
        }

        $this->client->loginUser($this->user);
    }

    public function testCreateWardrobeItemWithInvalidData(): void
    {
        $crawler = $this->client->request('GET', '/wardrobe/test');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Enregistrer')->form();
        $csrfToken = $form->get('wardrobe_item[_token]')->getValue();

        $formData = [
            'wardrobe_item' => [
                'name' => '',
                'size' => '',
                'color' => '',
                'status' => WardrobeStatus::ACTIVE->value,
                'season' => WardrobeSeason::ALL->value,
                '_token' => $csrfToken,
            ],
        ];

        $this->client->request(
            method: 'POST',
            uri: '/wardrobe/test',
            parameters: $formData,
            server: ['HTTP_X-Requested-With' => 'XMLHttpRequest']
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertSelectorTextContains('.invalid-feedback', 'Le nom est obligatoire');
    }

    public function testCreateWardrobeItemWithValidData(): void
    {
        $crawler = $this->client->request('GET', '/wardrobe/test');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Enregistrer')->form();
        $csrfToken = $form->get('wardrobe_item[_token]')->getValue();

        $formData = [
            'wardrobe_item' => [
                'name' => 'T-shirt test',
                'description' => 'Description test',
                'brand' => 'Marque test',
                'size' => 'M',
                'color' => 'Bleu',
                'status' => WardrobeStatus::ACTIVE->value,
                'season' => WardrobeSeason::ALL->value,
                'category' => $this->category->getId(), // Ajout de la catégorie
                '_token' => $csrfToken,
            ],
        ];

        $this->client->request('POST', '/wardrobe/test', $formData);

        $this->assertResponseRedirects('/wardrobe/test');
    }

    public function testCreateWardrobeItemWithTooLongName(): void
    {
        $crawler = $this->client->request('GET', '/wardrobe/test');

        $form = $crawler->selectButton('Enregistrer')->form();
        $csrfToken = $form->get('wardrobe_item[_token]')->getValue();

        $formData = [
            'wardrobe_item' => [
                'name' => str_repeat('a', 256),
                'size' => 'M',
                'color' => 'Bleu',
                'status' => WardrobeStatus::ACTIVE->value,
                'season' => WardrobeSeason::ALL->value,
                'category' => $this->category->getId(),
                '_token' => $csrfToken,
            ],
        ];

        $this->client->request('POST', '/wardrobe/test', $formData);
        $this->assertResponseStatusCodeSame(422);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->client);
        unset($this->databaseTool);
    }
}
