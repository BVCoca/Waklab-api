<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231011121619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mobs (id INT AUTO_INCREMENT NOT NULL, family_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, action_points INT NOT NULL, movement_points INT NOT NULL, initiative INT NOT NULL, tackle INT NOT NULL, dodge INT NOT NULL, parry INT NOT NULL, critical_hit INT NOT NULL, attack_water INT NOT NULL, attack_earth INT NOT NULL, attack_wind INT NOT NULL, attack_fire INT NOT NULL, res_water INT NOT NULL, res_earth INT NOT NULL, res_wind INT NOT NULL, res_fire INT NOT NULL, level_min INT NOT NULL, level_max INT NOT NULL, is_capturable TINYINT(1) NOT NULL, image_url VARCHAR(255) NOT NULL, hp INT NOT NULL, INDEX IDX_3544557CC35E566A (family_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mobs ADD CONSTRAINT FK_3544557CC35E566A FOREIGN KEY (family_id) REFERENCES family (id)');
        $this->addSql('ALTER TABLE mobs DROP FOREIGN KEY FK_FE97F67DC35E566A');
        $this->addSql('DROP TABLE mobs');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mob (id INT AUTO_INCREMENT NOT NULL, family_id INT DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, action_points INT NOT NULL, movement_points INT NOT NULL, initiative INT NOT NULL, tackle INT NOT NULL, dodge INT NOT NULL, parry INT NOT NULL, critical_hit INT NOT NULL, attack_water INT NOT NULL, attack_earth INT NOT NULL, attack_wind INT NOT NULL, attack_fire INT NOT NULL, res_water INT NOT NULL, res_earth INT NOT NULL, res_wind INT NOT NULL, res_fire INT NOT NULL, level_min INT NOT NULL, level_max INT NOT NULL, is_capturable TINYINT(1) NOT NULL, image_url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_FE97F67DC35E566A (family_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE mob ADD CONSTRAINT FK_FE97F67DC35E566A FOREIGN KEY (family_id) REFERENCES family (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE mobs DROP FOREIGN KEY FK_3544557CC35E566A');
        $this->addSql('DROP TABLE mobs');
    }
}
