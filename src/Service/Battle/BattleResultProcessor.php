<?php

namespace App\Service\Battle;

use App\Entity\Character;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Processes battle results for storage and retrieval.
 * Handles session management for battle results.
 */
class BattleResultProcessor
{
    private const SESSION_KEY = 'battle_result';

    /**
     * Stores battle result in session for later retrieval.
     *
     * @param SessionInterface $session
     * @param Character $character1
     * @param Character $character2
     * @param array{winner: Character, turns: int, history: array} $battleResult
     */
    public function storeResult(
        SessionInterface $session,
        Character $character1,
        Character $character2,
        array $battleResult
    ): void {
        $session->set(self::SESSION_KEY, [
            'character1_id' => $character1->getId(),
            'character2_id' => $character2->getId(),
            'winner_name' => $battleResult['winner']->getName(),
            'turns' => $battleResult['turns'],
            'history' => $battleResult['history'],
        ]);
    }

    /**
     * Retrieves battle result from session if it matches the requested characters.
     *
     * @param SessionInterface $session
     * @param Character $character1
     * @param Character $character2
     * @return array{winner: Character, turns: int, history: array}|null
     */
    public function retrieveResult(
        SessionInterface $session,
        Character $character1,
        Character $character2
    ): ?array {
        $battleData = $session->get(self::SESSION_KEY);

        if (!$battleData) {
            return null;
        }

        // Verify the battle data matches the requested characters
        if ($battleData['character1_id'] != $character1->getId() ||
            $battleData['character2_id'] != $character2->getId()) {
            return null;
        }

        // Reconstruct battle result for display
        return [
            'winner' => $battleData['winner_name'] === $character1->getName()
                ? $character1
                : $character2,
            'turns' => $battleData['turns'],
            'history' => $battleData['history'],
        ];
    }

    /**
     * Clears battle result from session.
     */
    public function clearResult(SessionInterface $session): void
    {
        $session->remove(self::SESSION_KEY);
    }
}

