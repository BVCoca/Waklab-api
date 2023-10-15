<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231015114438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, stuff_id INT DEFAULT NULL, resource_id INT DEFAULT NULL, job_id INT DEFAULT NULL, job_level INT DEFAULT NULL, UNIQUE INDEX UNIQ_DA88B137950A1740 (stuff_id), UNIQUE INDEX UNIQ_DA88B13789329D25 (resource_id), INDEX IDX_DA88B137BE04EA9 (job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_ingredient (id INT AUTO_INCREMENT NOT NULL, recipe_id INT NOT NULL, stuff_id INT DEFAULT NULL, resource_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_22D1FE1359D8A214 (recipe_id), INDEX IDX_22D1FE13950A1740 (stuff_id), INDEX IDX_22D1FE1389329D25 (resource_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137950A1740 FOREIGN KEY (stuff_id) REFERENCES stuff (id)');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B13789329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE1359D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE13950A1740 FOREIGN KEY (stuff_id) REFERENCES stuff (id)');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE1389329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137950A1740');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B13789329D25');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137BE04EA9');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE1359D8A214');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE13950A1740');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE1389329D25');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_ingredient');
    }
}
