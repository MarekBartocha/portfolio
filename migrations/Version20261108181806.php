<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261108181806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE topic CHANGE name_pl name_pl VARCHAR(255) DEFAULT NULL, CHANGE name_de name_de VARCHAR(255) DEFAULT NULL, CHANGE name_en name_en VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE roles roles JSON NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE topic CHANGE name_pl name_pl VARCHAR(255) NOT NULL, CHANGE name_en name_en VARCHAR(255) NOT NULL, CHANGE name_de name_de VARCHAR(255) NOT NULL
        SQL);
    }
}
