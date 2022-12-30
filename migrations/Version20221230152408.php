<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221230152408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, agency_id INT NOT NULL, number VARCHAR(255) NOT NULL, balance VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_7D3656A4CDEADB2A (agency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agency (id INT AUTO_INCREMENT NOT NULL, bank_id INT DEFAULT NULL, manager_id INT DEFAULT NULL, number VARCHAR(255) NOT NULL, INDEX IDX_70C0C6E611C8FB41 (bank_id), UNIQUE INDEX UNIQ_70C0C6E6783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bank (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client_account (client_id INT NOT NULL, account_id INT NOT NULL, INDEX IDX_2F0B12D919EB6921 (client_id), INDEX IDX_2F0B12D99B6B5FBA (account_id), PRIMARY KEY(client_id, account_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manager (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A4CDEADB2A FOREIGN KEY (agency_id) REFERENCES agency (id)');
        $this->addSql('ALTER TABLE agency ADD CONSTRAINT FK_70C0C6E611C8FB41 FOREIGN KEY (bank_id) REFERENCES bank (id)');
        $this->addSql('ALTER TABLE agency ADD CONSTRAINT FK_70C0C6E6783E3463 FOREIGN KEY (manager_id) REFERENCES manager (id)');
        $this->addSql('ALTER TABLE client_account ADD CONSTRAINT FK_2F0B12D919EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_account ADD CONSTRAINT FK_2F0B12D99B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account DROP FOREIGN KEY FK_7D3656A4CDEADB2A');
        $this->addSql('ALTER TABLE agency DROP FOREIGN KEY FK_70C0C6E611C8FB41');
        $this->addSql('ALTER TABLE agency DROP FOREIGN KEY FK_70C0C6E6783E3463');
        $this->addSql('ALTER TABLE client_account DROP FOREIGN KEY FK_2F0B12D919EB6921');
        $this->addSql('ALTER TABLE client_account DROP FOREIGN KEY FK_2F0B12D99B6B5FBA');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE agency');
        $this->addSql('DROP TABLE bank');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE client_account');
        $this->addSql('DROP TABLE manager');
        $this->addSql('DROP TABLE messenger_messages');
    }
}