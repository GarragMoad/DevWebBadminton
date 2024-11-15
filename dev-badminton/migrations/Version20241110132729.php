<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241110132729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE capitaine (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(15) NOT NULL, prenom VARCHAR(15) NOT NULL, mail VARCHAR(25) NOT NULL, telephone VARCHAR(25) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE club (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(15) NOT NULL, sigle VARCHAR(10) DEFAULT NULL, gymnase VARCHAR(50) NOT NULL, adresse VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, club_id INT NOT NULL, capitaine_id INT NOT NULL, nom_equipe VARCHAR(15) NOT NULL, numero_equipe VARCHAR(10) NOT NULL, INDEX IDX_2449BA1561190A32 (club_id), INDEX IDX_2449BA152A10D79E (capitaine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe_joueur (equipe_id INT NOT NULL, joueur_id INT NOT NULL, INDEX IDX_F046CF6D6D861B89 (equipe_id), INDEX IDX_F046CF6DA9E2D76C (joueur_id), PRIMARY KEY(equipe_id, joueur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE joueur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(15) NOT NULL, prenom VARCHAR(15) NOT NULL, numreo_licence VARCHAR(25) NOT NULL, classement_simple VARCHAR(10) DEFAULT NULL, cpph_simple DOUBLE PRECISION DEFAULT NULL, classement_double VARCHAR(10) DEFAULT NULL, cpph_double DOUBLE PRECISION DEFAULT NULL, classement_mixtes VARCHAR(10) DEFAULT NULL, cpph_mixtes DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jours (id INT AUTO_INCREMENT NOT NULL, jour VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reception (id INT AUTO_INCREMENT NOT NULL, club_id INT NOT NULL, type_reception_id INT NOT NULL, jour_id INT NOT NULL, horaire_debut DATE NOT NULL, horaire_fin DATE NOT NULL, INDEX IDX_50D6852F61190A32 (club_id), INDEX IDX_50D6852FFB576557 (type_reception_id), INDEX IDX_50D6852F220C6AD0 (jour_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_reception (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA1561190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA152A10D79E FOREIGN KEY (capitaine_id) REFERENCES capitaine (id)');
        $this->addSql('ALTER TABLE equipe_joueur ADD CONSTRAINT FK_F046CF6D6D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipe_joueur ADD CONSTRAINT FK_F046CF6DA9E2D76C FOREIGN KEY (joueur_id) REFERENCES joueur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reception ADD CONSTRAINT FK_50D6852F61190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE reception ADD CONSTRAINT FK_50D6852FFB576557 FOREIGN KEY (type_reception_id) REFERENCES type_reception (id)');
        $this->addSql('ALTER TABLE reception ADD CONSTRAINT FK_50D6852F220C6AD0 FOREIGN KEY (jour_id) REFERENCES jours (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA1561190A32');
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA152A10D79E');
        $this->addSql('ALTER TABLE equipe_joueur DROP FOREIGN KEY FK_F046CF6D6D861B89');
        $this->addSql('ALTER TABLE equipe_joueur DROP FOREIGN KEY FK_F046CF6DA9E2D76C');
        $this->addSql('ALTER TABLE reception DROP FOREIGN KEY FK_50D6852F61190A32');
        $this->addSql('ALTER TABLE reception DROP FOREIGN KEY FK_50D6852FFB576557');
        $this->addSql('ALTER TABLE reception DROP FOREIGN KEY FK_50D6852F220C6AD0');
        $this->addSql('DROP TABLE capitaine');
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE equipe_joueur');
        $this->addSql('DROP TABLE joueur');
        $this->addSql('DROP TABLE jours');
        $this->addSql('DROP TABLE reception');
        $this->addSql('DROP TABLE type_reception');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
