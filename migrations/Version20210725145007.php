<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210725145007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE module_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE module (id INT NOT NULL, name VARCHAR(255) NOT NULL, content TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE module_user (module_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(module_id, user_id))');
        $this->addSql('CREATE INDEX IDX_37AF9345AFC2B591 ON module_user (module_id)');
        $this->addSql('CREATE INDEX IDX_37AF9345A76ED395 ON module_user (user_id)');
        $this->addSql('ALTER TABLE module_user ADD CONSTRAINT FK_37AF9345AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE module_user ADD CONSTRAINT FK_37AF9345A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE module_user DROP CONSTRAINT FK_37AF9345AFC2B591');
        $this->addSql('DROP SEQUENCE module_id_seq CASCADE');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE module_user');
    }
}
