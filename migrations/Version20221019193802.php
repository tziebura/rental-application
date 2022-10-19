<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221019193802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE apartment_rooms (id INT AUTO_INCREMENT NOT NULL, apartment_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, square_meter_size DOUBLE PRECISION NOT NULL, INDEX IDX_3B66495B176DFE85 (apartment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE apartments (id INT AUTO_INCREMENT NOT NULL, owner_id VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, address_street VARCHAR(255) NOT NULL, address_postal_code VARCHAR(255) NOT NULL, address_house_number VARCHAR(255) NOT NULL, address_apartment_number VARCHAR(255) NOT NULL, address_city VARCHAR(255) NOT NULL, address_country VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, rental_place_id INT NOT NULL, tenant_id VARCHAR(255) NOT NULL, rental_type VARCHAR(255) NOT NULL, dates LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address_street VARCHAR(255) NOT NULL, address_building_number VARCHAR(255) NOT NULL, address_postal_code VARCHAR(255) NOT NULL, address_city VARCHAR(255) NOT NULL, address_country VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel_room (id INT AUTO_INCREMENT NOT NULL, hotel_id VARCHAR(255) NOT NULL, number INT NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, hotel_room_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, size_size DOUBLE PRECISION NOT NULL, INDEX IDX_729F519B875D6EBA (hotel_room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE apartment_rooms ADD CONSTRAINT FK_3B66495B176DFE85 FOREIGN KEY (apartment_id) REFERENCES apartments (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B875D6EBA FOREIGN KEY (hotel_room_id) REFERENCES hotel_room (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE apartment_rooms DROP FOREIGN KEY FK_3B66495B176DFE85');
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519B875D6EBA');
        $this->addSql('DROP TABLE apartment_rooms');
        $this->addSql('DROP TABLE apartments');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE hotel');
        $this->addSql('DROP TABLE hotel_room');
        $this->addSql('DROP TABLE room');
    }
}
