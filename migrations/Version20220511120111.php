<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220511120111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, hotel_id INT NOT NULL, user_id INT NOT NULL, rate INT NOT NULL, INDEX IDX_8F91ABF03243BB18 (hotel_id), INDEX IDX_8F91ABF0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chambres (id INT AUTO_INCREMENT NOT NULL, hotels_id INT NOT NULL, type_chambre VARCHAR(255) NOT NULL, nbr_lit INT NOT NULL, description LONGTEXT NOT NULL, prix DOUBLE PRECISION NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_36C92962F42F66C8 (hotels_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, hotel_id INT NOT NULL, user_id INT NOT NULL, text VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, is_published TINYINT(1) NOT NULL, INDEX IDX_67F068BC3243BB18 (hotel_id), INDEX IDX_67F068BCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id_event INT AUTO_INCREMENT NOT NULL, nom VARCHAR(30) NOT NULL, date_event DATE NOT NULL, type_event VARCHAR(30) NOT NULL, capacite INT NOT NULL, prix DOUBLE PRECISION NOT NULL, image VARCHAR(255) NOT NULL, updatedAt DATETIME NOT NULL, PRIMARY KEY(id_event)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hotels (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, nbetoiles INT NOT NULL, adresse VARCHAR(255) NOT NULL, pointfort TINYTEXT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE login_time (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, firstlogin DATETIME NOT NULL, lastlogin DATETIME DEFAULT NULL, tokenverif VARCHAR(255) NOT NULL, INDEX IDX_A9E61300A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, idevenement INT NOT NULL, iduser INT NOT NULL, INDEX fk_idev (idevenement), INDEX iduser (iduser), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, date_d_n DATE DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, sexe VARCHAR(255) DEFAULT NULL, tel_num INT DEFAULT NULL, img_name VARCHAR(255) DEFAULT NULL, updatedAt DATETIME NOT NULL, adresse VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, disable TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voiture (id INT AUTO_INCREMENT NOT NULL, matricule VARCHAR(1000) NOT NULL, marque VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, nbsieges INT NOT NULL, prix DOUBLE PRECISION NOT NULL, image VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF03243BB18 FOREIGN KEY (hotel_id) REFERENCES hotels (id)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE chambres ADD CONSTRAINT FK_36C92962F42F66C8 FOREIGN KEY (hotels_id) REFERENCES hotels (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC3243BB18 FOREIGN KEY (hotel_id) REFERENCES hotels (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE login_time ADD CONSTRAINT FK_A9E61300A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF03243BB18');
        $this->addSql('ALTER TABLE chambres DROP FOREIGN KEY FK_36C92962F42F66C8');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC3243BB18');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0A76ED395');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('ALTER TABLE login_time DROP FOREIGN KEY FK_A9E61300A76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE chambres');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE hotels');
        $this->addSql('DROP TABLE login_time');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE voiture');
    }
}
