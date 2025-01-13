<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250113201516 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE joueur CHANGE classement_simple classement_simple VARCHAR(255) NOT NULL, CHANGE classement_double classement_double VARCHAR(255) NOT NULL, CHANGE classement_mixtes classement_mixtes VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE joueur CHANGE classement_simple classement_simple VARCHAR(10) DEFAULT NULL, CHANGE classement_double classement_double VARCHAR(10) DEFAULT NULL, CHANGE classement_mixtes classement_mixtes VARCHAR(10) DEFAULT NULL');
    }
}
