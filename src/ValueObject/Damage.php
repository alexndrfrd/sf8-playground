<?php

namespace App\ValueObject;

use InvalidArgumentException;

/**
 * Value Object representing damage in the game.
 * Immutable - once created, cannot be changed.
 */
readonly class Damage
{
    public function __construct(
        private int $value
    ) {
        if ($value < 0) {
            throw new InvalidArgumentException('Damage cannot be negative');
        }
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function add(Damage $other): self
    {
        return new self($this->value + $other->value);
    }

    public function subtract(Damage $other): self
    {
        $result = $this->value - $other->value;
        return new self(max(0, $result)); // Never negative
    }

    public function multiply(float $multiplier): self
    {
        return new self((int) round($this->value * $multiplier));
    }

    public function isGreaterThan(Damage $other): bool
    {
        return $this->value > $other->value;
    }

    public function equals(Damage $other): bool
    {
        return $this->value === $other->value;
    }

    public function toString(): string
    {
        return (string) $this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}

