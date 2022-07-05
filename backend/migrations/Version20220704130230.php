<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220704130230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE iqama_time (id INT AUTO_INCREMENT NOT NULL, mosque_id INT DEFAULT NULL, fajr VARCHAR(255) NOT NULL, dhuhur VARCHAR(255) NOT NULL, asr VARCHAR(255) NOT NULL, maghrib VARCHAR(255) NOT NULL, ishaa VARCHAR(255) NOT NULL, day VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E8BBDEE6FBDAA034 (mosque_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE iqama_time ADD CONSTRAINT FK_E8BBDEE6FBDAA034 FOREIGN KEY (mosque_id) REFERENCES mosque (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE iqama_time');
    }
}
