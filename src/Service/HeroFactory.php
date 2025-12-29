<?php

namespace App\Service;

use App\Entity\Hero;
use App\Entity\Spell;

class HeroFactory
{
    private const MIN_ATTRIBUTE = 1;
    private const MAX_ATTRIBUTE = 200;
    private const MIN_ATTACK_SPEED = 0.5;
    private const MAX_ATTACK_SPEED = 3.0;
    private const MIN_SPELL_DAMAGE = 20;
    private const MAX_SPELL_DAMAGE = 150;
    private const MIN_SPELL_COOLDOWN = 2.0;
    private const MAX_SPELL_COOLDOWN = 10.0;

    private const SPELL_NAMES = [
        'Fireball',
        'Ice Bolt',
        'Lightning Strike',
        'Poison Dart',
        'Heal',
        'Shield',
        'Teleport',
        'Berserker Rage',
        'Magic Missile',
        'Frost Nova',
        'Chain Lightning',
        'Shadow Strike',
    ];

    public function createRandomHero(string $name): Hero
    {
        $hero = new Hero();
        $hero->setName($name);
        $hero->setStrength($this->randomInt(self::MIN_ATTRIBUTE, self::MAX_ATTRIBUTE));
        $hero->setIntelligence($this->randomInt(self::MIN_ATTRIBUTE, self::MAX_ATTRIBUTE));
        $hero->setSpeed($this->randomInt(self::MIN_ATTRIBUTE, self::MAX_ATTRIBUTE));
        $hero->setAttackSpeed($this->randomFloat(self::MIN_ATTACK_SPEED, self::MAX_ATTACK_SPEED));
        $hero->setDamage($this->randomInt(self::MIN_ATTRIBUTE, self::MAX_ATTRIBUTE));

        // Health is calculated from strength (strength * 10)
        $maxHealth = $hero->getStrength() * 10;
        $hero->setMaxHealth($maxHealth);
        $hero->setHealth($maxHealth);

        // Add 4 random spells
        $this->addRandomSpells($hero);

        return $hero;
    }

    private function addRandomSpells(Hero $hero): void
    {
        $availableSpellNames = self::SPELL_NAMES;
        shuffle($availableSpellNames);

        for ($i = 0; $i < 4; $i++) {
            $spell = new Spell();
            $spell->setName($availableSpellNames[$i]);
            $spell->setDescription($this->generateSpellDescription($availableSpellNames[$i]));
            $spell->setDamage($this->randomInt(self::MIN_SPELL_DAMAGE, self::MAX_SPELL_DAMAGE));
            $spell->setCooldown($this->randomFloat(self::MIN_SPELL_COOLDOWN, self::MAX_SPELL_COOLDOWN));
            
            $hero->addSpell($spell);
        }
    }

    private function generateSpellDescription(string $spellName): string
    {
        return sprintf('Casts %s dealing damage to enemies', $spellName);
    }

    private function randomInt(int $min, int $max): int
    {
        return random_int($min, $max);
    }

    private function randomFloat(float $min, float $max): float
    {
        return round($min + mt_rand() / mt_getrandmax() * ($max - $min), 2);
    }
}

