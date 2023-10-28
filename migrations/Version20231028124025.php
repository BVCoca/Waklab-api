<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231028124025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dungeon DROP INDEX IDX_3FFA1F90261FB672, ADD UNIQUE INDEX UNIQ_3FFA1F90261FB672 (boss_id)');
        $this->addSql('ALTER TABLE mobs DROP FOREIGN KEY FK_3544557CB606863');
        $this->addSql('DROP INDEX IDX_3544557CB606863 ON mobs');
        $this->addSql('ALTER TABLE mobs DROP dungeon_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dungeon DROP INDEX UNIQ_3FFA1F90261FB672, ADD INDEX IDX_3FFA1F90261FB672 (boss_id)');
        $this->addSql('ALTER TABLE mobs ADD dungeon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mobs ADD CONSTRAINT FK_3544557CB606863 FOREIGN KEY (dungeon_id) REFERENCES dungeon (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3544557CB606863 ON mobs (dungeon_id)');
    }
}
