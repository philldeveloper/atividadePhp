<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230111140941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE empresa (id INT AUTO_INCREMENT NOT NULL, funcionario_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B8D75A50642FEB76 (funcionario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE funcionario (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE empresa ADD CONSTRAINT FK_B8D75A50642FEB76 FOREIGN KEY (funcionario_id) REFERENCES funcionario (id)');
        $this->addSql('ALTER TABLE account ADD status INT NOT NULL');
        $this->addSql('ALTER TABLE manager DROP FOREIGN KEY FK_FA2425B9CDEADB2A');
        $this->addSql('DROP INDEX UNIQ_FA2425B9CDEADB2A ON manager');
        $this->addSql('ALTER TABLE manager DROP agency_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE empresa DROP FOREIGN KEY FK_B8D75A50642FEB76');
        $this->addSql('DROP TABLE empresa');
        $this->addSql('DROP TABLE funcionario');
        $this->addSql('ALTER TABLE account DROP status');
        $this->addSql('ALTER TABLE manager ADD agency_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE manager ADD CONSTRAINT FK_FA2425B9CDEADB2A FOREIGN KEY (agency_id) REFERENCES agency (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FA2425B9CDEADB2A ON manager (agency_id)');
    }
}
