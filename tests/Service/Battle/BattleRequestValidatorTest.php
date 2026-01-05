<?php

namespace App\Tests\Service\Battle;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use App\Service\Battle\BattleRequestValidator;
use App\ValueObject\CharacterStats;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class BattleRequestValidatorTest extends TestCase
{
    private BattleRequestValidator $validator;
    private CharacterRepository|MockObject $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(CharacterRepository::class);
        $this->validator = new BattleRequestValidator($this->repository);
    }

    public function testCanCreateValidator(): void
    {
        $this->assertInstanceOf(BattleRequestValidator::class, $this->validator);
    }

    public function testValidatesBothCharacterIdsPresent(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Please select two characters to battle.');

        $this->validator->validate(['character1_id' => '1', 'character2_id' => null]);
    }

    public function testValidatesCharacterIdsAreNotSame(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A character cannot battle itself.');

        $this->validator->validate(['character1_id' => '1', 'character2_id' => '1']);
    }

    public function testValidatesCharactersExist(): void
    {
        $this->repository->expects($this->exactly(2))
            ->method('find')
            ->willReturnCallback(function ($id) {
                return match ($id) {
                    '1' => null,
                    '2' => new Character('Mage', new CharacterStats(10, 20), 100),
                    default => null,
                };
            });

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('One or both characters not found.');

        $this->validator->validate(['character1_id' => '1', 'character2_id' => '2']);
    }

    public function testReturnsValidatedCharacters(): void
    {
        $character1 = new Character('Warrior', new CharacterStats(20, 10), 100);
        $character2 = new Character('Mage', new CharacterStats(10, 20), 100);

        $this->repository->expects($this->exactly(2))
            ->method('find')
            ->willReturnCallback(function ($id) use ($character1, $character2) {
                return match ($id) {
                    '1' => $character1,
                    '2' => $character2,
                    default => null,
                };
            });

        $result = $this->validator->validate(['character1_id' => '1', 'character2_id' => '2']);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('character1', $result);
        $this->assertArrayHasKey('character2', $result);
        $this->assertInstanceOf(Character::class, $result['character1']);
        $this->assertInstanceOf(Character::class, $result['character2']);
        $this->assertEquals('Warrior', $result['character1']->getName());
        $this->assertEquals('Mage', $result['character2']->getName());
    }
}

