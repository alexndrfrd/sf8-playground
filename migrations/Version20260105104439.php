<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260105104439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Add columns as nullable first
        $this->addSql('ALTER TABLE characters ADD race_name VARCHAR(50) DEFAULT NULL, ADD critical_hit_chance NUMERIC(3, 2) DEFAULT NULL, ADD critical_hit_multiplier NUMERIC(3, 2) DEFAULT NULL');
        
        // Set default values for existing rows (Human race defaults)
        $this->addSql("UPDATE characters SET race_name = 'human', critical_hit_chance = 0.10, critical_hit_multiplier = 1.50 WHERE race_name IS NULL");
        
        // Make columns NOT NULL now that all rows have values
        $this->addSql('ALTER TABLE characters MODIFY race_name VARCHAR(50) NOT NULL, MODIFY critical_hit_chance NUMERIC(3, 2) NOT NULL, MODIFY critical_hit_multiplier NUMERIC(3, 2) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters DROP race_name, DROP critical_hit_chance, DROP critical_hit_multiplier');
    }
}
