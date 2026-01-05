<?php

namespace App\Tests\DTO;

use App\DTO\ExampleDTO;
use PHPUnit\Framework\TestCase;

class ExampleDTOTest extends TestCase
{
    public function testDTOCanBeCreated(): void
    {
        $dto = new ExampleDTO();

        $this->assertInstanceOf(ExampleDTO::class, $dto);
    }

    public function testDTOCanBeConvertedToArray(): void
    {
        $dto = new ExampleDTO();
        $dto->input = 'test';
        $dto->output = 'TEST (processed)';
        $dto->processedAt = new \DateTimeImmutable('2024-01-01 12:00:00');

        $array = $dto->toArray();

        $this->assertIsArray($array);
        $this->assertEquals('test', $array['input']);
        $this->assertEquals('TEST (processed)', $array['output']);
        $this->assertArrayHasKey('processedAt', $array);
    }
}

