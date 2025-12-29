<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'heroes')]
class Hero
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private int $strength = 0;

    #[ORM\Column]
    private int $intelligence = 0;

    #[ORM\Column]
    private int $speed = 0;

    #[ORM\Column(type: 'decimal', precision: 5, scale: 2)]
    private float $attackSpeed = 0.0;

    #[ORM\Column]
    private int $damage = 0;

    #[ORM\Column]
    private int $health = 0;

    #[ORM\Column]
    private int $maxHealth = 0;

    #[ORM\OneToMany(targetEntity: Spell::class, mappedBy: 'hero', cascade: ['persist', 'remove'])]
    private Collection $spells;

    public function __construct()
    {
        $this->spells = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStrength(): int
    {
        return $this->strength;
    }

    public function setStrength(int $strength): static
    {
        $this->strength = $strength;

        return $this;
    }

    public function getIntelligence(): int
    {
        return $this->intelligence;
    }

    public function setIntelligence(int $intelligence): static
    {
        $this->intelligence = $intelligence;

        return $this;
    }

    public function getSpeed(): int
    {
        return $this->speed;
    }

    public function setSpeed(int $speed): static
    {
        $this->speed = $speed;

        return $this;
    }

    public function getAttackSpeed(): float
    {
        return $this->attackSpeed;
    }

    public function setAttackSpeed(float $attackSpeed): static
    {
        $this->attackSpeed = $attackSpeed;

        return $this;
    }

    public function getDamage(): int
    {
        return $this->damage;
    }

    public function setDamage(int $damage): static
    {
        $this->damage = $damage;

        return $this;
    }

    /**
     * @return Collection<int, Spell>
     */
    public function getSpells(): Collection
    {
        return $this->spells;
    }

    public function addSpell(Spell $spell): static
    {
        if (!$this->spells->contains($spell)) {
            $this->spells->add($spell);
            $spell->setHero($this);
        }

        return $this;
    }

    public function removeSpell(Spell $spell): static
    {
        if ($this->spells->removeElement($spell)) {
            if ($spell->getHero() === $this) {
                $spell->setHero(null);
            }
        }

        return $this;
    }

    public function getHealth(): int
    {
        return $this->health;
    }

    public function setHealth(int $health): static
    {
        $this->health = max(0, min($health, $this->maxHealth > 0 ? $this->maxHealth : $health));
        return $this;
    }

    public function getMaxHealth(): int
    {
        return $this->maxHealth;
    }

    public function setMaxHealth(int $maxHealth): static
    {
        $this->maxHealth = $maxHealth;
        if ($this->health === 0 || $this->health > $maxHealth) {
            $this->health = $maxHealth;
        }
        return $this;
    }

    public function takeDamage(int $damage): void
    {
        $this->health = max(0, $this->health - $damage);
    }

    public function isAlive(): bool
    {
        return $this->health > 0;
    }
}

