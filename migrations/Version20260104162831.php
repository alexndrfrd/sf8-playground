<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260104162831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE heroes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, strength INT NOT NULL, intelligence INT NOT NULL, speed INT NOT NULL, attack_speed NUMERIC(5, 2) NOT NULL, damage INT NOT NULL, health INT NOT NULL, max_health INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE spells (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, damage INT NOT NULL, cooldown NUMERIC(5, 2) NOT NULL, hero_id INT NOT NULL, INDEX IDX_88D70F5B45B0BCD (hero_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE spells ADD CONSTRAINT FK_88D70F5B45B0BCD FOREIGN KEY (hero_id) REFERENCES heroes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE spells DROP FOREIGN KEY FK_88D70F5B45B0BCD');
        $this->addSql('DROP TABLE heroes');
        $this->addSql('DROP TABLE spells');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
