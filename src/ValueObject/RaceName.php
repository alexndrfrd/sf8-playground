<?php

namespace App\ValueObject;

/**
 * Enum for race names.
 */
enum RaceName: string
{
    case HUMAN = 'human';
    case ELF = 'elf';
    case ORC = 'orc';
    case DWARF = 'dwarf';

    public function getLabel(): string
    {
        return match ($this) {
            self::HUMAN => 'Human',
            self::ELF => 'Elf',
            self::ORC => 'Orc',
            self::DWARF => 'Dwarf',
        };
    }

    /**
     * Gets all available race names.
     *
     * @return array<self>
     */
    public static function all(): array
    {
        return self::cases();
    }
}

