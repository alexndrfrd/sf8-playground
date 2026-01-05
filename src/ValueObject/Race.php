<?php

namespace App\ValueObject;

use InvalidArgumentException;

/**
 * Value Object representing a character race.
 * Immutable - once created, cannot be changed.
 * Each race has specific critical hit properties.
 */
readonly class Race
{
    private const RACE_PROPERTIES = [
        RaceName::HUMAN->value => ['name' => 'Human', 'criticalChance' => 0.1, 'criticalMultiplier' => 1.5],
        RaceName::ELF->value => ['name' => 'Elf', 'criticalChance' => 0.15, 'criticalMultiplier' => 1.6],
        RaceName::ORC->value => ['name' => 'Orc', 'criticalChance' => 0.08, 'criticalMultiplier' => 2.0],
        RaceName::DWARF->value => ['name' => 'Dwarf', 'criticalChance' => 0.12, 'criticalMultiplier' => 1.7],
    ];

    public function __construct(
        private RaceName $raceName,
        private float $criticalHitChance,
        private float $criticalHitMultiplier
    ) {
        if ($criticalHitChance < 0) {
            throw new InvalidArgumentException('Critical hit chance cannot be negative');
        }
        
        if ($criticalHitChance > 1.0) {
            throw new InvalidArgumentException('Critical hit chance cannot exceed 1.0');
        }
        
        if ($criticalHitMultiplier < 1.0) {
            throw new InvalidArgumentException('Critical hit multiplier must be at least 1.0');
        }
    }

    public function getName(): string
    {
        return self::RACE_PROPERTIES[$this->raceName->value]['name'];
    }

    public function getRaceName(): RaceName
    {
        return $this->raceName;
    }

    public function getCriticalHitChance(): float
    {
        return $this->criticalHitChance;
    }

    public function getCriticalHitMultiplier(): float
    {
        return $this->criticalHitMultiplier;
    }

    /**
     * Creates a Race from a RaceName enum.
     */
    public static function from(RaceName $raceName): self
    {
        $properties = self::RACE_PROPERTIES[$raceName->value];
        
        return new self(
            $raceName,
            $properties['criticalChance'],
            $properties['criticalMultiplier']
        );
    }

    /**
     * Creates a Race from a string race name.
     */
    public static function fromString(string $raceName): self
    {
        try {
            $raceNameEnum = RaceName::from($raceName);
            return self::from($raceNameEnum);
        } catch (\ValueError $e) {
            throw new InvalidArgumentException("Invalid race name: {$raceName}");
        }
    }

    /**
     * Gets all available races.
     *
     * @return array<string, self>
     */
    public static function all(): array
    {
        $races = [];
        foreach (RaceName::all() as $raceName) {
            $races[$raceName->value] = self::from($raceName);
        }
        return $races;
    }
}

