<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210801131138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE subscription_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_subscription_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE subscription (id INT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, much BOOLEAN NOT NULL, basic BOOLEAN NOT NULL, pro BOOLEAN NOT NULL, modules BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_subscription (id INT NOT NULL, owner_id INT NOT NULL, subscribtion_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, modified_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expired_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_230A18D17E3C61F9 ON user_subscription (owner_id)');
        $this->addSql('CREATE INDEX IDX_230A18D1A486B18A ON user_subscription (subscribtion_id)');
        $this->addSql('COMMENT ON COLUMN user_subscription.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_subscription.modified_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_subscription.expired_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user_subscription ADD CONSTRAINT FK_230A18D17E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_subscription ADD CONSTRAINT FK_230A18D1A486B18A FOREIGN KEY (subscribtion_id) REFERENCES subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_subscription DROP CONSTRAINT FK_230A18D1A486B18A');
        $this->addSql('DROP SEQUENCE subscription_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_subscription_id_seq CASCADE');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE user_subscription');
    }
}
