<?php

namespace App\Tests\Entity;

use App\Entity\Hero;
use App\Entity\Spell;
use PHPUnit\Framework\TestCase;

class SpellTest extends TestCase
{
    public function testSpellCanBeCreated(): void
    {
        $spell = new Spell();
        
        $this->assertInstanceOf(Spell::class, $spell);
    }

    public function testSpellHasName(): void
    {
        $spell = new Spell();
        $spell->setName('Fireball');
        
        $this->assertEquals('Fireball', $spell->getName());
    }

    public function testSpellHasDescription(): void
    {
        $spell = new Spell();
        $spell->setDescription('Shoots a fireball');
        
        $this->assertEquals('Shoots a fireball', $spell->getDescription());
    }

    public function testSpellHasDamage(): void
    {
        $spell = new Spell();
        $spell->setDamage(100);
        
        $this->assertEquals(100, $spell->getDamage());
    }

    public function testSpellHasCooldown(): void
    {
        $spell = new Spell();
        $spell->setCooldown(5.0);
        
        $this->assertEquals(5.0, $spell->getCooldown());
    }

    public function testSpellBelongsToHero(): void
    {
        $hero = new Hero();
        $hero->setName('Test Hero');
        
        $spell = new Spell();
        $spell->setName('Fireball');
        $spell->setHero($hero);
        
        $this->assertEquals($hero, $spell->getHero());
    }

    public function testHeroCanHaveMultipleSpells(): void
    {
        $hero = new Hero();
        $hero->setName('Test Hero');
        
        $spell1 = new Spell();
        $spell1->setName('Fireball');
        $spell1->setHero($hero);
        
        $spell2 = new Spell();
        $spell2->setName('Ice Bolt');
        $spell2->setHero($hero);
        
        $this->assertEquals($hero, $spell1->getHero());
        $this->assertEquals($hero, $spell2->getHero());
    }
}

