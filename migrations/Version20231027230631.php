<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231027230631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dungeon (id INT AUTO_INCREMENT NOT NULL, boss_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, max_player INT DEFAULT NULL, room_count INT DEFAULT NULL, level INT NOT NULL, INDEX IDX_3FFA1F90261FB672 (boss_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dungeon_mobs (dungeon_id INT NOT NULL, mobs_id INT NOT NULL, INDEX IDX_51909984B606863 (dungeon_id), INDEX IDX_519099848DD778A9 (mobs_id), PRIMARY KEY(dungeon_id, mobs_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dungeon ADD CONSTRAINT FK_3FFA1F90261FB672 FOREIGN KEY (boss_id) REFERENCES mobs (id)');
        $this->addSql('ALTER TABLE dungeon_mobs ADD CONSTRAINT FK_51909984B606863 FOREIGN KEY (dungeon_id) REFERENCES dungeon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dungeon_mobs ADD CONSTRAINT FK_519099848DD778A9 FOREIGN KEY (mobs_id) REFERENCES mobs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mobs ADD dungeon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mobs ADD CONSTRAINT FK_3544557CB606863 FOREIGN KEY (dungeon_id) REFERENCES dungeon (id)');
        $this->addSql('CREATE INDEX IDX_3544557CB606863 ON mobs (dungeon_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mobs DROP FOREIGN KEY FK_3544557CB606863');
        $this->addSql('ALTER TABLE dungeon DROP FOREIGN KEY FK_3FFA1F90261FB672');
        $this->addSql('ALTER TABLE dungeon_mobs DROP FOREIGN KEY FK_51909984B606863');
        $this->addSql('ALTER TABLE dungeon_mobs DROP FOREIGN KEY FK_519099848DD778A9');
        $this->addSql('DROP TABLE dungeon');
        $this->addSql('DROP TABLE dungeon_mobs');
        $this->addSql('DROP INDEX IDX_3544557CB606863 ON mobs');
        $this->addSql('ALTER TABLE mobs DROP dungeon_id');
    }
}
