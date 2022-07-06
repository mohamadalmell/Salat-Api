<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220705132803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE khateeb_mosque (khateeb_id INT NOT NULL, mosque_id INT NOT NULL, INDEX IDX_31EA19082894A52E (khateeb_id), INDEX IDX_31EA1908FBDAA034 (mosque_id), PRIMARY KEY(khateeb_id, mosque_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE khateeb_mosque ADD CONSTRAINT FK_31EA19082894A52E FOREIGN KEY (khateeb_id) REFERENCES khateeb (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE khateeb_mosque ADD CONSTRAINT FK_31EA1908FBDAA034 FOREIGN KEY (mosque_id) REFERENCES mosque (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE khateeb_mosque');
    }
}
