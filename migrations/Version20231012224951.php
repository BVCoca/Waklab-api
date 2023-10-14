<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231012224951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE resource_drop (id INT AUTO_INCREMENT NOT NULL, resource_id INT NOT NULL, mob_id INT NOT NULL, value INT NOT NULL, INDEX IDX_7F35A59889329D25 (resource_id), INDEX IDX_7F35A59816E57E11 (mob_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE resource_drop ADD CONSTRAINT FK_7F35A59889329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('ALTER TABLE resource_drop ADD CONSTRAINT FK_7F35A59816E57E11 FOREIGN KEY (mob_id) REFERENCES mobs (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resource_drop DROP FOREIGN KEY FK_7F35A59889329D25');
        $this->addSql('ALTER TABLE resource_drop DROP FOREIGN KEY FK_7F35A59816E57E11');
        $this->addSql('DROP TABLE resource_drop');
    }
}
