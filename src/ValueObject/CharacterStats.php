<?php

namespace App\ValueObject;

use InvalidArgumentException;

/**
 * Value Object representing character statistics (strength and intelligence).
 * Immutable - once created, cannot be changed.
 */
readonly class CharacterStats
{
    public function __construct(
        private int $strength,
        private int $intelligence
    ) {
        if ($strength < 0) {
            throw new InvalidArgumentException('Strength cannot be negative');
        }
        
        if ($intelligence < 0) {
            throw new InvalidArgumentException('Intelligence cannot be negative');
        }
    }

    public function getStrength(): int
    {
        return $this->strength;
    }

    public function getIntelligence(): int
    {
        return $this->intelligence;
    }

    public function getTotalPower(): int
    {
        return $this->strength + $this->intelligence;
    }
}

