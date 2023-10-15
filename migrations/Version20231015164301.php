<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231015164301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe DROP INDEX UNIQ_DA88B137950A1740, ADD INDEX IDX_DA88B137950A1740 (stuff_id)');
        $this->addSql('ALTER TABLE recipe DROP INDEX UNIQ_DA88B13789329D25, ADD INDEX IDX_DA88B13789329D25 (resource_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe DROP INDEX IDX_DA88B13789329D25, ADD UNIQUE INDEX UNIQ_DA88B13789329D25 (resource_id)');
        $this->addSql('ALTER TABLE recipe DROP INDEX IDX_DA88B137950A1740, ADD UNIQUE INDEX UNIQ_DA88B137950A1740 (stuff_id)');
    }
}
