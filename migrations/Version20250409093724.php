<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250409093724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice ADD created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD paid_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517447F9BC654 FOREIGN KEY (paid_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_90651744B03A8386 ON invoice (created_by_id)');
        $this->addSql('CREATE INDEX IDX_906517447F9BC654 ON invoice (paid_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_90651744B03A8386');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_906517447F9BC654');
        $this->addSql('DROP INDEX IDX_90651744B03A8386');
        $this->addSql('DROP INDEX IDX_906517447F9BC654');
        $this->addSql('ALTER TABLE invoice DROP created_by_id');
        $this->addSql('ALTER TABLE invoice DROP paid_by_id');
    }
}
