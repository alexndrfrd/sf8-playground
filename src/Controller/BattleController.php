<?php

namespace App\Controller;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use App\Service\Battle\BattleRequestValidator;
use App\Service\Battle\BattleResultProcessor;
use App\Service\BattleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/battle', name: 'battle_')]
class BattleController extends AbstractController
{
    public function __construct(
        private readonly BattleService $battleService,
        private readonly CharacterRepository $characterRepository,
        private readonly BattleRequestValidator $requestValidator,
        private readonly BattleResultProcessor $resultProcessor
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $characters = $this->characterRepository->findAll();

        return $this->render('battle/index.html.twig', [
            'characters' => $characters,
        ]);
    }

    #[Route('/fight', name: 'fight', methods: ['POST'])]
    public function fight(Request $request): Response
    {
        try {
            $validated = $this->requestValidator->validate($request->request->all());
            $character1 = $validated['character1'];
            $character2 = $validated['character2'];

            $battleChar1 = $this->cloneCharacter($character1);
            $battleChar2 = $this->cloneCharacter($character2);

            $battleResult = $this->battleService->battle($battleChar1, $battleChar2);

            $this->resultProcessor->storeResult(
                $request->getSession(),
                $character1,
                $character2,
                $battleResult
            );

            return $this->redirectToRoute('battle_result', [
                'char1' => $character1->getId(),
                'char2' => $character2->getId(),
            ]);
        } catch (\InvalidArgumentException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('battle_index');
        }
    }

    #[Route('/result/{char1}/{char2}', name: 'result', methods: ['GET'])]
    public function result(int $char1, int $char2, Request $request): Response
    {
        $character1 = $this->characterRepository->find($char1);
        $character2 = $this->characterRepository->find($char2);

        if (!$character1 || !$character2) {
            $this->addFlash('error', 'Characters not found.');
            return $this->redirectToRoute('battle_index');
        }

        $battleResult = $this->resultProcessor->retrieveResult(
            $request->getSession(),
            $character1,
            $character2
        );

        if (!$battleResult) {
            $this->addFlash('error', 'Battle result not found. Please start a new battle.');
            return $this->redirectToRoute('battle_index');
        }

        $this->resultProcessor->clearResult($request->getSession());

        return $this->render('battle/result.html.twig', [
            'character1' => $character1,
            'character2' => $character2,
            'battleResult' => $battleResult,
        ]);
    }

    /**
     * Creates a clone of a character for battle purposes.
     * This preserves the original character's health in the database.
     */
    private function cloneCharacter(Character $character): Character
    {
        return new Character(
            $character->getName(),
            $character->getStats(),
            $character->getRace(),
            $character->getHealth()
        );
    }
}

