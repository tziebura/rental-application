<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221019200733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE apartment_booking_histories (apartment_id INT NOT NULL, PRIMARY KEY(apartment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE apartment_bookings (id INT AUTO_INCREMENT NOT NULL, apartment_booking_history_id INT DEFAULT NULL, step VARCHAR(255) NOT NULL, booking_date_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', owner_id VARCHAR(255) NOT NULL, tenant_id VARCHAR(255) NOT NULL, booking_period_start DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', booking_period_end DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_776460E562459E37 (apartment_booking_history_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE apartment_bookings ADD CONSTRAINT FK_776460E562459E37 FOREIGN KEY (apartment_booking_history_id) REFERENCES apartment_booking_histories (apartment_id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE apartment_bookings DROP FOREIGN KEY FK_776460E562459E37');
        $this->addSql('DROP TABLE apartment_booking_histories');
        $this->addSql('DROP TABLE apartment_bookings');
    }
}
