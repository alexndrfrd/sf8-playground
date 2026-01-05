<?php

namespace App\Tests\Repository;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use App\ValueObject\CharacterStats;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CharacterRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private CharacterRepository $repository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->repository = $this->entityManager->getRepository(Character::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }

    public function testCanFindCharacterByName(): void
    {
        $stats = new CharacterStats(10, 15);
        $character = new Character('TestWarrior', $stats);
        
        $this->entityManager->persist($character);
        $this->entityManager->flush();
        
        $found = $this->repository->findByName('TestWarrior');
        
        $this->assertInstanceOf(Character::class, $found);
        $this->assertEquals('TestWarrior', $found->getName());
        
        // Cleanup
        $this->entityManager->remove($found);
        $this->entityManager->flush();
    }

    public function testFindByNameReturnsNullWhenNotFound(): void
    {
        $found = $this->repository->findByName('NonExistentCharacter');
        
        $this->assertNull($found);
    }

    public function testCanFindAllCharacters(): void
    {
        $stats1 = new CharacterStats(10, 15);
        $stats2 = new CharacterStats(20, 25);
        $character1 = new Character('Warrior1', $stats1);
        $character2 = new Character('Warrior2', $stats2);
        
        $this->entityManager->persist($character1);
        $this->entityManager->persist($character2);
        $this->entityManager->flush();
        
        $all = $this->repository->findAll();
        
        $this->assertIsArray($all);
        $this->assertGreaterThanOrEqual(2, count($all));
        
        // Cleanup
        $this->entityManager->remove($character1);
        $this->entityManager->remove($character2);
        $this->entityManager->flush();
    }
}

