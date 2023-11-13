<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231113233721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subzone (id INT AUTO_INCREMENT NOT NULL, zone_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, level_min INT NOT NULL, level_max INT NOT NULL, image_url VARCHAR(255) NOT NULL, INDEX IDX_795EB28C9F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subzone_resource (subzone_id INT NOT NULL, resource_id INT NOT NULL, INDEX IDX_D9245D14D44E9764 (subzone_id), INDEX IDX_D9245D1489329D25 (resource_id), PRIMARY KEY(subzone_id, resource_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subzone_family (subzone_id INT NOT NULL, family_id INT NOT NULL, INDEX IDX_D5756A9CD44E9764 (subzone_id), INDEX IDX_D5756A9CC35E566A (family_id), PRIMARY KEY(subzone_id, family_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zone (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, level_min INT NOT NULL, level_max INT NOT NULL, image_url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subzone ADD CONSTRAINT FK_795EB28C9F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE subzone_resource ADD CONSTRAINT FK_D9245D14D44E9764 FOREIGN KEY (subzone_id) REFERENCES subzone (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subzone_resource ADD CONSTRAINT FK_D9245D1489329D25 FOREIGN KEY (resource_id) REFERENCES resource (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subzone_family ADD CONSTRAINT FK_D5756A9CD44E9764 FOREIGN KEY (subzone_id) REFERENCES subzone (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subzone_family ADD CONSTRAINT FK_D5756A9CC35E566A FOREIGN KEY (family_id) REFERENCES family (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dungeon ADD subzone_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dungeon ADD CONSTRAINT FK_3FFA1F90D44E9764 FOREIGN KEY (subzone_id) REFERENCES subzone (id)');
        $this->addSql('CREATE INDEX IDX_3FFA1F90D44E9764 ON dungeon (subzone_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dungeon DROP FOREIGN KEY FK_3FFA1F90D44E9764');
        $this->addSql('ALTER TABLE subzone DROP FOREIGN KEY FK_795EB28C9F2C3FAB');
        $this->addSql('ALTER TABLE subzone_resource DROP FOREIGN KEY FK_D9245D14D44E9764');
        $this->addSql('ALTER TABLE subzone_resource DROP FOREIGN KEY FK_D9245D1489329D25');
        $this->addSql('ALTER TABLE subzone_family DROP FOREIGN KEY FK_D5756A9CD44E9764');
        $this->addSql('ALTER TABLE subzone_family DROP FOREIGN KEY FK_D5756A9CC35E566A');
        $this->addSql('DROP TABLE subzone');
        $this->addSql('DROP TABLE subzone_resource');
        $this->addSql('DROP TABLE subzone_family');
        $this->addSql('DROP TABLE zone');
        $this->addSql('DROP INDEX IDX_3FFA1F90D44E9764 ON dungeon');
        $this->addSql('ALTER TABLE dungeon DROP subzone_id');
    }
}
