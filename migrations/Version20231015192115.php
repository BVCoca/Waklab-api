<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231015192115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE caracteristic (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, icon VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE family (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mobs (id INT AUTO_INCREMENT NOT NULL, family_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, action_points INT NOT NULL, movement_points INT NOT NULL, initiative INT NOT NULL, tackle INT NOT NULL, dodge INT NOT NULL, parry INT NOT NULL, critical_hit INT NOT NULL, attack_water INT NOT NULL, attack_earth INT NOT NULL, attack_wind INT NOT NULL, attack_fire INT NOT NULL, res_water INT NOT NULL, res_earth INT NOT NULL, res_wind INT NOT NULL, res_fire INT NOT NULL, level_min INT NOT NULL, level_max INT NOT NULL, is_capturable TINYINT(1) NOT NULL, image_url VARCHAR(255) NOT NULL, hp INT NOT NULL, INDEX IDX_3544557CC35E566A (family_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rarity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, value INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, job_id INT DEFAULT NULL, resource_id INT DEFAULT NULL, stuff_id INT DEFAULT NULL, job_level INT DEFAULT NULL, INDEX IDX_DA88B137BE04EA9 (job_id), INDEX IDX_DA88B13789329D25 (resource_id), INDEX IDX_DA88B137950A1740 (stuff_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_ingredient (id INT AUTO_INCREMENT NOT NULL, recipe_id INT NOT NULL, stuff_id INT DEFAULT NULL, resource_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_22D1FE1359D8A214 (recipe_id), INDEX IDX_22D1FE13950A1740 (stuff_id), INDEX IDX_22D1FE1389329D25 (resource_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resource (id INT AUTO_INCREMENT NOT NULL, rarity_id INT NOT NULL, name VARCHAR(255) NOT NULL, level INT NOT NULL, description LONGTEXT DEFAULT NULL, image_url VARCHAR(255) NOT NULL, INDEX IDX_BC91F416F3747573 (rarity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resource_drop (id INT AUTO_INCREMENT NOT NULL, resource_id INT NOT NULL, mob_id INT NOT NULL, value INT NOT NULL, INDEX IDX_7F35A59889329D25 (resource_id), INDEX IDX_7F35A59816E57E11 (mob_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stuff (id INT AUTO_INCREMENT NOT NULL, rarity_id INT NOT NULL, type_id INT NOT NULL, name VARCHAR(255) NOT NULL, level INT NOT NULL, description LONGTEXT DEFAULT NULL, image_url VARCHAR(255) NOT NULL, zone_type VARCHAR(255) DEFAULT NULL, cost_pa INT DEFAULT NULL, required_po INT DEFAULT NULL, effect_type VARCHAR(255) DEFAULT NULL, effect_value INT DEFAULT NULL, critical_effect_value INT DEFAULT NULL, INDEX IDX_5941F83EF3747573 (rarity_id), INDEX IDX_5941F83EC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stuff_caracteristic (id INT AUTO_INCREMENT NOT NULL, stuff_id INT NOT NULL, caracteristic_id INT NOT NULL, value INT NOT NULL, INDEX IDX_BC20409E950A1740 (stuff_id), INDEX IDX_BC20409E81194CF4 (caracteristic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stuff_drop (id INT AUTO_INCREMENT NOT NULL, stuff_id INT NOT NULL, mob_id INT NOT NULL, value DOUBLE PRECISION NOT NULL, INDEX IDX_6AD24C7C950A1740 (stuff_id), INDEX IDX_6AD24C7C16E57E11 (mob_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_stuff (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mobs ADD CONSTRAINT FK_3544557CC35E566A FOREIGN KEY (family_id) REFERENCES family (id)');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B13789329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137950A1740 FOREIGN KEY (stuff_id) REFERENCES stuff (id)');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE1359D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE13950A1740 FOREIGN KEY (stuff_id) REFERENCES stuff (id)');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE1389329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F416F3747573 FOREIGN KEY (rarity_id) REFERENCES rarity (id)');
        $this->addSql('ALTER TABLE resource_drop ADD CONSTRAINT FK_7F35A59889329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('ALTER TABLE resource_drop ADD CONSTRAINT FK_7F35A59816E57E11 FOREIGN KEY (mob_id) REFERENCES mobs (id)');
        $this->addSql('ALTER TABLE stuff ADD CONSTRAINT FK_5941F83EF3747573 FOREIGN KEY (rarity_id) REFERENCES rarity (id)');
        $this->addSql('ALTER TABLE stuff ADD CONSTRAINT FK_5941F83EC54C8C93 FOREIGN KEY (type_id) REFERENCES type_stuff (id)');
        $this->addSql('ALTER TABLE stuff_caracteristic ADD CONSTRAINT FK_BC20409E950A1740 FOREIGN KEY (stuff_id) REFERENCES stuff (id)');
        $this->addSql('ALTER TABLE stuff_caracteristic ADD CONSTRAINT FK_BC20409E81194CF4 FOREIGN KEY (caracteristic_id) REFERENCES caracteristic (id)');
        $this->addSql('ALTER TABLE stuff_drop ADD CONSTRAINT FK_6AD24C7C950A1740 FOREIGN KEY (stuff_id) REFERENCES stuff (id)');
        $this->addSql('ALTER TABLE stuff_drop ADD CONSTRAINT FK_6AD24C7C16E57E11 FOREIGN KEY (mob_id) REFERENCES mobs (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mobs DROP FOREIGN KEY FK_3544557CC35E566A');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137BE04EA9');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B13789329D25');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137950A1740');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE1359D8A214');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE13950A1740');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE1389329D25');
        $this->addSql('ALTER TABLE resource DROP FOREIGN KEY FK_BC91F416F3747573');
        $this->addSql('ALTER TABLE resource_drop DROP FOREIGN KEY FK_7F35A59889329D25');
        $this->addSql('ALTER TABLE resource_drop DROP FOREIGN KEY FK_7F35A59816E57E11');
        $this->addSql('ALTER TABLE stuff DROP FOREIGN KEY FK_5941F83EF3747573');
        $this->addSql('ALTER TABLE stuff DROP FOREIGN KEY FK_5941F83EC54C8C93');
        $this->addSql('ALTER TABLE stuff_caracteristic DROP FOREIGN KEY FK_BC20409E950A1740');
        $this->addSql('ALTER TABLE stuff_caracteristic DROP FOREIGN KEY FK_BC20409E81194CF4');
        $this->addSql('ALTER TABLE stuff_drop DROP FOREIGN KEY FK_6AD24C7C950A1740');
        $this->addSql('ALTER TABLE stuff_drop DROP FOREIGN KEY FK_6AD24C7C16E57E11');
        $this->addSql('DROP TABLE caracteristic');
        $this->addSql('DROP TABLE family');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE mobs');
        $this->addSql('DROP TABLE rarity');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_ingredient');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE resource_drop');
        $this->addSql('DROP TABLE stuff');
        $this->addSql('DROP TABLE stuff_caracteristic');
        $this->addSql('DROP TABLE stuff_drop');
        $this->addSql('DROP TABLE type_stuff');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
