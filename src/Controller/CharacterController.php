<?php

namespace App\Controller;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use App\ValueObject\CharacterStats;
use App\ValueObject\Race;
use App\ValueObject\RaceName;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/character', name: 'character_')]
class CharacterController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CharacterRepository    $characterRepository
    )
    {
    }

    #[Route('/create', name: 'create', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('character/create.html.twig', [
            'races' => Race::all(),
        ]);
    }

    #[Route('/store', name: 'store', methods: ['POST'])]
    public function store(Request $request): Response
    {
        $name = trim($request->request->get('character')['name'] ?? '');
        $strength = (int)($request->request->get('character')['strength'] ?? 10);
        $intelligence = (int)($request->request->get('character')['intelligence'] ?? 10);
        $health = (int)($request->request->get('character')['health'] ?? 100);

        // Validation
        if (empty($name)) {
            $this->addFlash('error', 'Character name cannot be empty.');
            return $this->redirectToRoute('character_create');
        }

        if ($strength < 0) {
            $this->addFlash('error', 'Strength cannot be negative.');
            return $this->redirectToRoute('character_create');
        }

        if ($intelligence < 0) {
            $this->addFlash('error', 'Intelligence cannot be negative.');
            return $this->redirectToRoute('character_create');
        }

        if ($health < 1) {
            $this->addFlash('error', 'Health must be at least 1.');
            return $this->redirectToRoute('character_create');
        }

        // Check if character with same name already exists
        $existingCharacter = $this->characterRepository->findByName($name);
        if ($existingCharacter) {
            $this->addFlash('error', 'A character with this name already exists.');
            return $this->redirectToRoute('character_create');
        }

        // Create character
        try {
            $raceNameString = $request->request->get('character')['race'] ?? 'human';
            $race = Race::fromString($raceNameString);

            $stats = new CharacterStats($strength, $intelligence);
            $character = new Character($name, $stats, $race, $health);

            $this->entityManager->persist($character);
            $this->entityManager->flush();

            $this->addFlash('success', sprintf('Character "%s" created successfully!', $name));
            return $this->redirectToRoute('battle_index');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Failed to create character: ' . $e->getMessage());
            return $this->redirectToRoute('character_create');
        }
    }
}
