<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210724160011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE paragraph_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE theme_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE title_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE paragraph (id INT NOT NULL, theme_id INT NOT NULL, text TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7DD3986259027487 ON paragraph (theme_id)');
        $this->addSql('CREATE TABLE theme (id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE title (id INT NOT NULL, theme_id INT NOT NULL, text VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2B36786B59027487 ON title (theme_id)');
        $this->addSql('ALTER TABLE paragraph ADD CONSTRAINT FK_7DD3986259027487 FOREIGN KEY (theme_id) REFERENCES theme (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE title ADD CONSTRAINT FK_2B36786B59027487 FOREIGN KEY (theme_id) REFERENCES theme (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE paragraph DROP CONSTRAINT FK_7DD3986259027487');
        $this->addSql('ALTER TABLE title DROP CONSTRAINT FK_2B36786B59027487');
        $this->addSql('DROP SEQUENCE paragraph_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE theme_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE title_id_seq CASCADE');
        $this->addSql('DROP TABLE paragraph');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE title');
    }
}
