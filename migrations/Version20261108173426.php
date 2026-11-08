<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261108173426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE blog CHANGE content_en content_en LONGTEXT NOT NULL, CHANGE content_de content_de LONGTEXT NOT NULL, CHANGE title_en title_en VARCHAR(255) NOT NULL, CHANGE title_de title_de VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE topic CHANGE name_pl name_pl VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE roles roles JSON NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE blog CHANGE title_en title_en VARCHAR(255) DEFAULT NULL, CHANGE content_en content_en LONGTEXT DEFAULT NULL, CHANGE title_de title_de VARCHAR(255) DEFAULT NULL, CHANGE content_de content_de LONGTEXT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE topic CHANGE name_pl name_pl VARCHAR(255) NOT NULL
        SQL);
    }
}
