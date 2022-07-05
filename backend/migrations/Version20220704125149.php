<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220704125149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE facility (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facility_mosque (facility_id INT NOT NULL, mosque_id INT NOT NULL, INDEX IDX_F0D75FB0A7014910 (facility_id), INDEX IDX_F0D75FB0FBDAA034 (mosque_id), PRIMARY KEY(facility_id, mosque_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE khateeb (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE khateeb_mosque (khateeb_id INT NOT NULL, mosque_id INT NOT NULL, INDEX IDX_31EA19082894A52E (khateeb_id), INDEX IDX_31EA1908FBDAA034 (mosque_id), PRIMARY KEY(khateeb_id, mosque_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mosque (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, address VARCHAR(255) NOT NULL, phone_number INT NOT NULL, email VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, mosque_id INT DEFAULT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_14B78418FBDAA034 (mosque_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE facility_mosque ADD CONSTRAINT FK_F0D75FB0A7014910 FOREIGN KEY (facility_id) REFERENCES facility (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE facility_mosque ADD CONSTRAINT FK_F0D75FB0FBDAA034 FOREIGN KEY (mosque_id) REFERENCES mosque (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE khateeb_mosque ADD CONSTRAINT FK_31EA19082894A52E FOREIGN KEY (khateeb_id) REFERENCES khateeb (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE khateeb_mosque ADD CONSTRAINT FK_31EA1908FBDAA034 FOREIGN KEY (mosque_id) REFERENCES mosque (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418FBDAA034 FOREIGN KEY (mosque_id) REFERENCES mosque (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facility_mosque DROP FOREIGN KEY FK_F0D75FB0A7014910');
        $this->addSql('ALTER TABLE khateeb_mosque DROP FOREIGN KEY FK_31EA19082894A52E');
        $this->addSql('ALTER TABLE facility_mosque DROP FOREIGN KEY FK_F0D75FB0FBDAA034');
        $this->addSql('ALTER TABLE khateeb_mosque DROP FOREIGN KEY FK_31EA1908FBDAA034');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B78418FBDAA034');
        $this->addSql('DROP TABLE facility');
        $this->addSql('DROP TABLE facility_mosque');
        $this->addSql('DROP TABLE khateeb');
        $this->addSql('DROP TABLE khateeb_mosque');
        $this->addSql('DROP TABLE mosque');
        $this->addSql('DROP TABLE photo');
    }
}
