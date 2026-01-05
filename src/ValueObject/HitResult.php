<?php

namespace App\ValueObject;

/**
 * Value Object representing the result of a hit/attack.
 * Immutable - once created, cannot be changed.
 */
readonly class HitResult
{
    public function __construct(
        private Damage $damage,
        private bool $isCritical
    ) {
    }

    public function getDamage(): Damage
    {
        return $this->damage;
    }

    public function isCritical(): bool
    {
        return $this->isCritical;
    }
}

