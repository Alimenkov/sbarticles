<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210802190351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_subscription DROP CONSTRAINT fk_230a18d1a486b18a');
        $this->addSql('DROP INDEX idx_230a18d1a486b18a');
        $this->addSql('ALTER TABLE user_subscription ALTER modified_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE user_subscription ALTER modified_at DROP DEFAULT');
        $this->addSql('ALTER TABLE user_subscription ALTER expired_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE user_subscription ALTER expired_at DROP DEFAULT');
        $this->addSql('ALTER TABLE user_subscription RENAME COLUMN subscribtion_id TO subscription_id');
        $this->addSql('COMMENT ON COLUMN user_subscription.modified_at IS NULL');
        $this->addSql('COMMENT ON COLUMN user_subscription.expired_at IS NULL');
        $this->addSql('ALTER TABLE user_subscription ADD CONSTRAINT FK_230A18D19A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_230A18D19A1887DC ON user_subscription (subscription_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_subscription DROP CONSTRAINT FK_230A18D19A1887DC');
        $this->addSql('DROP INDEX IDX_230A18D19A1887DC');
        $this->addSql('ALTER TABLE user_subscription ALTER modified_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE user_subscription ALTER modified_at DROP DEFAULT');
        $this->addSql('ALTER TABLE user_subscription ALTER expired_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE user_subscription ALTER expired_at DROP DEFAULT');
        $this->addSql('ALTER TABLE user_subscription RENAME COLUMN subscription_id TO subscribtion_id');
        $this->addSql('COMMENT ON COLUMN user_subscription.modified_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_subscription.expired_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user_subscription ADD CONSTRAINT fk_230a18d1a486b18a FOREIGN KEY (subscribtion_id) REFERENCES subscription (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_230a18d1a486b18a ON user_subscription (subscribtion_id)');
    }
}
