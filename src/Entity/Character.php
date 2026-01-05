<?php

namespace App\Entity;

use App\ValueObject\CharacterStats;
use App\ValueObject\Race;
use App\ValueObject\RaceName;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

#[ORM\Entity(repositoryClass: 'App\Repository\CharacterRepository')]
#[ORM\Table(name: 'characters')]
class Character
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column]
    private int $strength = 0;

    #[ORM\Column]
    private int $intelligence = 0;

    #[ORM\Column]
    private int $health = 100;

    #[ORM\Column(length: 50, enumType: RaceName::class)]
    private RaceName $raceName;

    #[ORM\Column(type: 'decimal', precision: 3, scale: 2)]
    private float $criticalHitChance;

    #[ORM\Column(type: 'decimal', precision: 3, scale: 2)]
    private float $criticalHitMultiplier;

    public function __construct(
        string $name,
        CharacterStats $stats,
        Race $race,
        int $health = 100
    ) {
        if (empty(trim($name))) {
            throw new InvalidArgumentException('Character name cannot be empty');
        }
        
        $this->name = $name;
        $this->strength = $stats->getStrength();
        $this->intelligence = $stats->getIntelligence();
        $this->health = max(0, $health);
        $this->raceName = $race->getRaceName();
        $this->criticalHitChance = $race->getCriticalHitChance();
        $this->criticalHitMultiplier = $race->getCriticalHitMultiplier();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStats(): CharacterStats
    {
        return new CharacterStats($this->strength, $this->intelligence);
    }

    public function getStrength(): int
    {
        return $this->strength;
    }

    public function getIntelligence(): int
    {
        return $this->intelligence;
    }

    public function getHealth(): int
    {
        return $this->health;
    }

    public function takeDamage(int $damage): void
    {
        $this->health = max(0, $this->health - $damage);
    }

    public function isAlive(): bool
    {
        return $this->health > 0;
    }

    public function getRace(): Race
    {
        return new Race(
            $this->raceName,
            $this->criticalHitChance,
            $this->criticalHitMultiplier
        );
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
}

