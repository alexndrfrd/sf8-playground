<?php

namespace App\Service;

class DamageCalculatorService
{
    public function calculateDamage(int $baseDamage, float $variationPercent = 0.2): int
    {
        $variation = (int) ($baseDamage * $variationPercent);
        $damage = $baseDamage + random_int(-$variation, $variation);
        
        return max(1, $damage); // Minimum 1 damage
    }
}

