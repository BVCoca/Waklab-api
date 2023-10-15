<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231015010724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stuff_drop (id INT AUTO_INCREMENT NOT NULL, stuff_id INT NOT NULL, mob_id INT NOT NULL, INDEX IDX_6AD24C7C950A1740 (stuff_id), INDEX IDX_6AD24C7C16E57E11 (mob_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stuff_drop ADD CONSTRAINT FK_6AD24C7C950A1740 FOREIGN KEY (stuff_id) REFERENCES stuff (id)');
        $this->addSql('ALTER TABLE stuff_drop ADD CONSTRAINT FK_6AD24C7C16E57E11 FOREIGN KEY (mob_id) REFERENCES mobs (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stuff_drop DROP FOREIGN KEY FK_6AD24C7C950A1740');
        $this->addSql('ALTER TABLE stuff_drop DROP FOREIGN KEY FK_6AD24C7C16E57E11');
        $this->addSql('DROP TABLE stuff_drop');
    }
}
