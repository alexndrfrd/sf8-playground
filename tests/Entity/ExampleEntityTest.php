<?php

namespace App\Tests\Entity;

use App\Entity\ExampleEntity;
use PHPUnit\Framework\TestCase;

class ExampleEntityTest extends TestCase
{
    public function testEntityCanBeCreated(): void
    {
        $entity = new ExampleEntity();

        $this->assertInstanceOf(ExampleEntity::class, $entity);
    }

    public function testEntityHasName(): void
    {
        $entity = new ExampleEntity();
        $entity->setName('Test');

        $this->assertEquals('Test', $entity->getName());
    }

    public function testEntityHasValue(): void
    {
        $entity = new ExampleEntity();
        $entity->setValue(100);

        $this->assertEquals(100, $entity->getValue());
    }
}

