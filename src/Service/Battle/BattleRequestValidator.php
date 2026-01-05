<?php

namespace App\Service\Battle;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use InvalidArgumentException;

/**
 * Validates battle request input.
 * Responsible for ensuring battle requests are valid before processing.
 */
class BattleRequestValidator
{
    public function __construct(
        private readonly CharacterRepository $characterRepository
    ) {
    }

    /**
     * Validates battle request and returns validated characters.
     *
     * @param array<string, string|null> $requestData Request data containing character1_id and character2_id
     * @return array{character1: Character, character2: Character}
     * @throws InvalidArgumentException When validation fails
     */
    public function validate(array $requestData): array
    {
        $character1Id = $requestData['character1_id'] ?? null;
        $character2Id = $requestData['character2_id'] ?? null;

        $this->validateCharacterIdsPresent($character1Id, $character2Id);
        $this->validateCharacterIdsDifferent($character1Id, $character2Id);

        $character1 = $this->characterRepository->find($character1Id);
        $character2 = $this->characterRepository->find($character2Id);

        $this->validateCharactersExist($character1, $character2);

        return [
            'character1' => $character1,
            'character2' => $character2,
        ];
    }

    /**
     * Validates that both character IDs are present.
     */
    private function validateCharacterIdsPresent(?string $character1Id, ?string $character2Id): void
    {
        if (!$character1Id || !$character2Id) {
            throw new InvalidArgumentException('Please select two characters to battle.');
        }
    }

    /**
     * Validates that character IDs are different.
     */
    private function validateCharacterIdsDifferent(string $character1Id, string $character2Id): void
    {
        if ($character1Id === $character2Id) {
            throw new InvalidArgumentException('A character cannot battle itself.');
        }
    }

    /**
     * Validates that both characters exist in the database.
     */
    private function validateCharactersExist(?Character $character1, ?Character $character2): void
    {
        if (!$character1 || !$character2) {
            throw new InvalidArgumentException('One or both characters not found.');
        }
    }
}

