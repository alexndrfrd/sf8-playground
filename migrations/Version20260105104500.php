<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Fix existing characters with null/empty race_name values
 */
final class Version20260105104500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fix existing characters with null/empty race_name values';
    }

    public function up(Schema $schema): void
    {
        // Update any characters with null or empty race_name to 'human' (default)
        $this->addSql("UPDATE characters SET race_name = 'human', critical_hit_chance = 0.10, critical_hit_multiplier = 1.50 WHERE race_name IS NULL OR race_name = '' OR race_name = '0'");
    }

    public function down(Schema $schema): void
    {
        // This migration is data-only, no schema changes to revert
    }
}

