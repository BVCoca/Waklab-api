<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231017110539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE family ADD slug VARCHAR(128) DEFAULT NULL');
        $this->addSql('ALTER TABLE job ADD slug VARCHAR(128) DEFAULT NULL');
        $this->addSql('ALTER TABLE mobs ADD slug VARCHAR(128) DEFAULT NULL');
        $this->addSql('ALTER TABLE resource ADD slug VARCHAR(128) DEFAULT NULL');
        $this->addSql('ALTER TABLE stuff ADD slug VARCHAR(128) DEFAULT NULL');
        $this->addSql('ALTER TABLE type_stuff ADD slug VARCHAR(128) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job DROP slug');
        $this->addSql('ALTER TABLE resource DROP slug');
        $this->addSql('ALTER TABLE stuff DROP slug');
        $this->addSql('ALTER TABLE mobs DROP slug');
        $this->addSql('ALTER TABLE family DROP slug');
        $this->addSql('ALTER TABLE type_stuff DROP slug');
    }
}
