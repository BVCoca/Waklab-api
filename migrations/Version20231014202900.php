<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231014202900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE caracteristic (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, icon VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stuff (id INT AUTO_INCREMENT NOT NULL, rarity_id INT NOT NULL, type_id INT NOT NULL, name VARCHAR(255) NOT NULL, level INT NOT NULL, effect LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, image_url VARCHAR(255) NOT NULL, zone_type VARCHAR(255) DEFAULT NULL, cost_pa INT DEFAULT NULL, required_po INT DEFAULT NULL, effect_type VARCHAR(255) DEFAULT NULL, effect_value INT DEFAULT NULL, critical_effect_value INT DEFAULT NULL, INDEX IDX_5941F83EF3747573 (rarity_id), INDEX IDX_5941F83EC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stuff_caracteristic (id INT AUTO_INCREMENT NOT NULL, stuff_id INT NOT NULL, caracteristic_id INT NOT NULL, value INT NOT NULL, INDEX IDX_BC20409E950A1740 (stuff_id), INDEX IDX_BC20409E81194CF4 (caracteristic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_stuff (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stuff ADD CONSTRAINT FK_5941F83EF3747573 FOREIGN KEY (rarity_id) REFERENCES rarity (id)');
        $this->addSql('ALTER TABLE stuff ADD CONSTRAINT FK_5941F83EC54C8C93 FOREIGN KEY (type_id) REFERENCES type_stuff (id)');
        $this->addSql('ALTER TABLE stuff_caracteristic ADD CONSTRAINT FK_BC20409E950A1740 FOREIGN KEY (stuff_id) REFERENCES stuff (id)');
        $this->addSql('ALTER TABLE stuff_caracteristic ADD CONSTRAINT FK_BC20409E81194CF4 FOREIGN KEY (caracteristic_id) REFERENCES caracteristic (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stuff DROP FOREIGN KEY FK_5941F83EF3747573');
        $this->addSql('ALTER TABLE stuff DROP FOREIGN KEY FK_5941F83EC54C8C93');
        $this->addSql('ALTER TABLE stuff_caracteristic DROP FOREIGN KEY FK_BC20409E950A1740');
        $this->addSql('ALTER TABLE stuff_caracteristic DROP FOREIGN KEY FK_BC20409E81194CF4');
        $this->addSql('DROP TABLE caracteristic');
        $this->addSql('DROP TABLE stuff');
        $this->addSql('DROP TABLE stuff_caracteristic');
        $this->addSql('DROP TABLE type_stuff');
    }
}
