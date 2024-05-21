<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240521003912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payementhotel (id INT AUTO_INCREMENT NOT NULL, nom_prenom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, numtel VARCHAR(255) NOT NULL, methode_payement VARCHAR(255) NOT NULL, num_carte VARCHAR(255) NOT NULL, code_securite VARCHAR(255) NOT NULL, prix_total NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payement ADD hotel_id INT NOT NULL');
        $this->addSql('ALTER TABLE payement ADD CONSTRAINT FK_B20A78853243BB18 FOREIGN KEY (hotel_id) REFERENCES reservation_hotel (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B20A78853243BB18 ON payement (hotel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE payementhotel');
        $this->addSql('ALTER TABLE payement DROP FOREIGN KEY FK_B20A78853243BB18');
        $this->addSql('DROP INDEX UNIQ_B20A78853243BB18 ON payement');
        $this->addSql('ALTER TABLE payement DROP hotel_id');
    }
}
