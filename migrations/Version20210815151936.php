<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210815151936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE article_params_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_article_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE article_params (id INT NOT NULL, user_article_id INT NOT NULL, params TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E5099973D5F34070 ON article_params (user_article_id)');
        $this->addSql('CREATE TABLE user_article (id INT NOT NULL, owner_id INT NOT NULL, article TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5A37106C7E3C61F9 ON user_article (owner_id)');
        $this->addSql('ALTER TABLE article_params ADD CONSTRAINT FK_E5099973D5F34070 FOREIGN KEY (user_article_id) REFERENCES user_article (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_article ADD CONSTRAINT FK_5A37106C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE article_params DROP CONSTRAINT FK_E5099973D5F34070');
        $this->addSql('DROP SEQUENCE article_params_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_article_id_seq CASCADE');
        $this->addSql('DROP TABLE article_params');
        $this->addSql('DROP TABLE user_article');
    }
}
