<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210907104455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F4D2A7E12');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649979B1AD6');
        $this->addSql('CREATE TABLE address (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', type ENUM(\'main\', \'vehicle\') NOT NULL COMMENT \'(DC2Type:enum_address_type)\', address VARCHAR(255) DEFAULT NULL, address2 VARCHAR(255) DEFAULT NULL, zipcode VARCHAR(10) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, lat DOUBLE PRECISION DEFAULT NULL, lng DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', site_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', type ENUM(\'simple\', \'emergency\', \'responsible\', \'visitor\') NOT NULL COMMENT \'(DC2Type:enum_contact_type)\', first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, identifier VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, language VARCHAR(5) DEFAULT \'fr-FR\' NOT NULL, phone VARCHAR(16) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_4C62E638F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', site_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', type ENUM(\'protocol\', \'annex\') NOT NULL COMMENT \'(DC2Type:enum_document_type)\', name VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, language VARCHAR(5) DEFAULT \'fr-FR\' NOT NULL, filename VARCHAR(512) DEFAULT NULL, version INT NOT NULL, versioned_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_D8698A76F6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_version (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', document_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', initiator_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', version INT NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_1B73751FC33F7837 (document_id), INDEX IDX_1B73751F7DB3B714 (initiator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relation (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', sender_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', recipient_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', protocol_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', type ENUM(\'created\', \'sent\', \'approved\', \'refused\') NOT NULL COMMENT \'(DC2Type:enum_relation_type)\', comments LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_62894749F624B39D (sender_id), INDEX IDX_62894749E92F8F78 (recipient_id), INDEX IDX_62894749CCD59258 (protocol_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', main_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', vehicle_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_694309E4CD4FDB16 (main_address_id), UNIQUE INDEX UNIQ_694309E4702520E8 (vehicle_address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('ALTER TABLE document_version ADD CONSTRAINT FK_1B73751FC33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE document_version ADD CONSTRAINT FK_1B73751F7DB3B714 FOREIGN KEY (initiator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749E92F8F78 FOREIGN KEY (recipient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749CCD59258 FOREIGN KEY (protocol_id) REFERENCES document_version (id)');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E4CD4FDB16 FOREIGN KEY (main_address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE site ADD CONSTRAINT FK_694309E4702520E8 FOREIGN KEY (vehicle_address_id) REFERENCES address (id)');
        $this->addSql('DROP TABLE building');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP INDEX IDX_8D93D649979B1AD6 ON user');
        $this->addSql('ALTER TABLE user DROP company_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E4CD4FDB16');
        $this->addSql('ALTER TABLE site DROP FOREIGN KEY FK_694309E4702520E8');
        $this->addSql('ALTER TABLE document_version DROP FOREIGN KEY FK_1B73751FC33F7837');
        $this->addSql('ALTER TABLE relation DROP FOREIGN KEY FK_62894749CCD59258');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638F6BD1646');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76F6BD1646');
        $this->addSql('CREATE TABLE building (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, address VARCHAR(512) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, zipcode VARCHAR(10) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, city VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, lat DOUBLE PRECISION DEFAULT NULL, lng DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE company (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', building_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_4FBF094F4D2A7E12 (building_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F4D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id)');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE document_version');
        $this->addSql('DROP TABLE relation');
        $this->addSql('DROP TABLE site');
        $this->addSql('ALTER TABLE user ADD company_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649979B1AD6 ON user (company_id)');
    }
}
