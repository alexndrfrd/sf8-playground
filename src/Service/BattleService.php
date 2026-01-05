<?php

namespace App\Service;

use App\Entity\Character;
use App\ValueObject\Damage;
use App\ValueObject\HitResult;

/**
 * Service responsible for battle logic.
 * Handles attacks, damage calculation, and battle simulation.
 */
class BattleService
{
    private const DAMAGE_VARIANCE_MIN = 0.8;
    private const DAMAGE_VARIANCE_MAX = 1.2;

    /**
     * Performs an attack from attacker to defender.
     * Damage is calculated based on attacker's strength with random variance.
     * Has a chance to be a critical hit.
     */
    public function attack(Character $attacker, Character $defender): HitResult
    {
        $baseDamage = $attacker->getStrength();

        $variance = $this->calculateDamageVariance($baseDamage);

        $isCritical = $this->isCriticalHit($attacker);

        if ($isCritical) {
            $variance = $this->applyCriticalHitMultiplier($variance, $attacker);
        }

        $damage = new Damage($variance);
        $defender->takeDamage($damage->getValue());

        return new HitResult($damage, $isCritical);
    }

    /**
     * Simulates a complete battle between two characters.
     * Returns battle result with winner, turns, and history.
     */
    public function battle(Character $character1, Character $character2): array
    {
        $history = [];
        $turn = 0;

        // Reset health for battle (create fresh instances would be better, but for simplicity...)
        // In a real scenario, we'd clone or use a battle-specific health tracking

        while ($character1->isAlive() && $character2->isAlive()) {
            $turn++;

            // Character 1 attacks Character 2
            $hitResult1 = $this->attack($character1, $character2);
            $history[] = [
                'turn' => $turn,
                'attacker' => $character1->getName(),
                'defender' => $character2->getName(),
                'damage' => $hitResult1->getDamage()->getValue(),
                'isCritical' => $hitResult1->isCritical(),
                'defenderHealth' => $character2->getHealth(),
            ];

            if (!$character2->isAlive()) {
                break;
            }

            // Character 2 attacks Character 1
            $hitResult2 = $this->attack($character2, $character1);
            $history[] = [
                'turn' => $turn,
                'attacker' => $character2->getName(),
                'defender' => $character1->getName(),
                'damage' => $hitResult2->getDamage()->getValue(),
                'isCritical' => $hitResult2->isCritical(),
                'defenderHealth' => $character1->getHealth(),
            ];
        }

        $winner = $character1->isAlive() ? $character1 : $character2;

        return [
            'winner' => $winner,
            'turns' => $turn,
            'history' => $history,
        ];
    }

    /**
     * Calculates damage variance based on base damage.
     * Applies random variance between DAMAGE_VARIANCE_MIN and DAMAGE_VARIANCE_MAX.
     */
    private function calculateDamageVariance(int $baseDamage): int
    {
        return mt_rand(
            (int) ($baseDamage * self::DAMAGE_VARIANCE_MIN),
            (int) ($baseDamage * self::DAMAGE_VARIANCE_MAX)
        );
    }

    /**
     * Applies critical hit multiplier to damage variance.
     * Uses the attacker's race-specific multiplier.
     */
    private function applyCriticalHitMultiplier(int $variance, Character $attacker): int
    {
        $multiplier = $attacker->getCriticalHitMultiplier();
        return (int) ($variance * $multiplier);
    }

    /**
     * Determines if an attack is a critical hit based on chance.
     * Uses the attacker's race-specific critical hit chance.
     */
    private function isCriticalHit(Character $attacker): bool
    {
        $chance = $attacker->getCriticalHitChance();
        return mt_rand(1, 100) <= ($chance * 100);
    }
}

