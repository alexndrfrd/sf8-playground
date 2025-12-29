<?php

namespace App\Service;

use App\Entity\Hero;

class BattleService
{
    private const MAX_ROUNDS = 100; // Safety limit to prevent infinite loops

    public function __construct(
        private readonly DamageCalculatorService $damageCalculator
    ) {
    }

    public function battle(Hero $hero1, Hero $hero2): array
    {
        // Reset health to max for both heroes
        $hero1->setHealth($hero1->getMaxHealth());
        $hero2->setHealth($hero2->getMaxHealth());

        $rounds = [];
        $roundNumber = 0;

        // Determine who attacks first (higher speed attacks first)
        $attacker = $hero1->getSpeed() >= $hero2->getSpeed() ? $hero1 : $hero2;
        $defender = $attacker === $hero1 ? $hero2 : $hero1;

        while ($hero1->isAlive() && $hero2->isAlive() && $roundNumber < self::MAX_ROUNDS) {
            $roundNumber++;

            // Calculate damage using injected service
            $damage = $this->damageCalculator->calculateDamage($attacker->getDamage());

            // Apply damage
            $defender->takeDamage($damage);

            // Record round
            $rounds[] = [
                'round' => $roundNumber,
                'attacker' => $attacker->getName(),
                'defender' => $defender->getName(),
                'damage' => $damage,
                'defenderHealth' => $defender->getHealth(),
            ];

            // Swap attacker and defender for next round
            $temp = $attacker;
            $attacker = $defender;
            $defender = $temp;
        }

        // Determine winner and loser
        $winner = $hero1->isAlive() ? $hero1 : $hero2;
        $loser = $hero1->isAlive() ? $hero2 : $hero1;

        return [
            'winner' => $winner,
            'loser' => $loser,
            'rounds' => $rounds,
            'totalRounds' => $roundNumber,
        ];
    }
}

