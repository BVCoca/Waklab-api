<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231029005841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sublimation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, effect LONGTEXT NOT NULL, first_chasse VARCHAR(255) NOT NULL, second_chasse VARCHAR(255) NOT NULL, third_chasse VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE resource ADD sublimation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F4168C11E9EE FOREIGN KEY (sublimation_id) REFERENCES sublimation (id)');
        $this->addSql('CREATE INDEX IDX_BC91F4168C11E9EE ON resource (sublimation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resource DROP FOREIGN KEY FK_BC91F4168C11E9EE');
        $this->addSql('DROP TABLE sublimation');
        $this->addSql('DROP INDEX IDX_BC91F4168C11E9EE ON resource');
        $this->addSql('ALTER TABLE resource DROP sublimation_id');
    }
}
