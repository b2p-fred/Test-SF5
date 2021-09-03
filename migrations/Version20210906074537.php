<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210906074537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', type ENUM(\'main\', \'vehicle\') NOT NULL COMMENT \'(DC2Type:enum_address_type)\', address VARCHAR(255) DEFAULT NULL, address2 VARCHAR(255) DEFAULT NULL, zipcode VARCHAR(10) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, lat DOUBLE PRECISION DEFAULT NULL, lng DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', site_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', type ENUM(\'simple\', \'emergency\', \'responsible\', \'visitor\') NOT NULL COMMENT \'(DC2Type:enum_contact_type)\', first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, identifier VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, language VARCHAR(5) DEFAULT \'fr-FR\' NOT NULL, phone VARCHAR(16) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_4C62E638F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', site_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', type ENUM(\'main\', \'annex\') NOT NULL COMMENT \'(DC2Type:enum_document_type)\', name VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, language VARCHAR(5) DEFAULT \'fr-FR\' NOT NULL, filename VARCHAR(512) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_D8698A76F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', main_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', vehicle_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_694309E4CD4FDB16 (main_address_id), UNIQUE INDEX UNIQ_694309E4702520E8 (vehicle_address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E4CD4FDB16 FOREIGN KEY (main_address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E4702520E8 FOREIGN KEY (vehicle_address_id) REFERENCES address (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E4CD4FDB16');
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E4702520E8');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638F6BD1646');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76F6BD1646');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE site');
    }
}
