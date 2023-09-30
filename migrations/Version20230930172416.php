<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230930172416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hotel_booking_histories (hotel_id INT NOT NULL, PRIMARY KEY(hotel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel_room_booking_histories (hotel_room_id INT NOT NULL, hotel_booking_history_id INT DEFAULT NULL, INDEX IDX_454CC29C80071F4C (hotel_booking_history_id), PRIMARY KEY(hotel_room_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotel_room_bookings (id INT AUTO_INCREMENT NOT NULL, booking_history_id INT DEFAULT NULL, step VARCHAR(255) NOT NULL, booking_date_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', tenant_id VARCHAR(255) NOT NULL, days LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_5E76B2DC879FE723 (booking_history_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hotel_room_booking_histories ADD CONSTRAINT FK_454CC29C80071F4C FOREIGN KEY (hotel_booking_history_id) REFERENCES hotel_booking_histories (hotel_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel_room_bookings ADD CONSTRAINT FK_5E76B2DC879FE723 FOREIGN KEY (booking_history_id) REFERENCES hotel_room_booking_histories (hotel_room_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel_room CHANGE hotel_id hotel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel_room ADD CONSTRAINT FK_C55A87133243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_C55A87133243BB18 ON hotel_room (hotel_id)');
        $this->addSql('ALTER TABLE hotel_room_offers ADD hotel_room_number INT NOT NULL, CHANGE hotel_room_id hotel_id VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hotel_room_booking_histories DROP FOREIGN KEY FK_454CC29C80071F4C');
        $this->addSql('ALTER TABLE hotel_room_bookings DROP FOREIGN KEY FK_5E76B2DC879FE723');
        $this->addSql('DROP TABLE hotel_booking_histories');
        $this->addSql('DROP TABLE hotel_room_booking_histories');
        $this->addSql('DROP TABLE hotel_room_bookings');
        $this->addSql('ALTER TABLE hotel_room DROP FOREIGN KEY FK_C55A87133243BB18');
        $this->addSql('DROP INDEX IDX_C55A87133243BB18 ON hotel_room');
        $this->addSql('ALTER TABLE hotel_room CHANGE hotel_id hotel_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE hotel_room_offers DROP hotel_room_number, CHANGE hotel_id hotel_room_id VARCHAR(255) NOT NULL');
    }
}
