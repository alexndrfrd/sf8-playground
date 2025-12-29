<?php

namespace App\Controller;

use App\Service\HeroFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/api/hero', name: 'api_hero_')]
class HeroController extends AbstractController
{
    public function __construct(
        private readonly HeroFactory $heroFactory
    ) {
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name']) || empty(trim($data['name']))) {
            return new JsonResponse(
                ['error' => 'Name is required and cannot be empty'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $hero = $this->heroFactory->createRandomHero(trim($data['name']));

        $heroData = [
            'id' => $hero->getId(),
            'name' => $hero->getName(),
            'strength' => $hero->getStrength(),
            'intelligence' => $hero->getIntelligence(),
            'speed' => $hero->getSpeed(),
            'attackSpeed' => $hero->getAttackSpeed(),
            'damage' => $hero->getDamage(),
            'spells' => array_map(function ($spell) {
                return [
                    'id' => $spell->getId(),
                    'name' => $spell->getName(),
                    'description' => $spell->getDescription(),
                    'damage' => $spell->getDamage(),
                    'cooldown' => $spell->getCooldown(),
                ];
            }, $hero->getSpells()->toArray()),
        ];

        return new JsonResponse($heroData, Response::HTTP_CREATED);
    }
}

