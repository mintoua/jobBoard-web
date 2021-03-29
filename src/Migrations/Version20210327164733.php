<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210327164733 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE candidate_resume (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, resume_headline VARCHAR(255) DEFAULT NULL, skills TINYTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', experience VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D4F28E7CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, couleur VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, descriptionc VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE certification (id INT AUTO_INCREMENT NOT NULL, resume_id INT DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, image_name VARCHAR(255) NOT NULL, INDEX IDX_6C3C6D75D262AF09 (resume_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_recrutement (id INT AUTO_INCREMENT NOT NULL, offre_id INT NOT NULL, candidat_id INT DEFAULT NULL, date_debut DATE NOT NULL, dateexpiration DATE NOT NULL, status TINYINT(1) NOT NULL, INDEX IDX_84CC7C6D4CC8505A (offre_id), INDEX IDX_84CC7C6D8D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE education (id INT AUTO_INCREMENT NOT NULL, resume_id INT NOT NULL, course VARCHAR(255) NOT NULL, datefrom DATETIME NOT NULL, dateto DATETIME NOT NULL, institute VARCHAR(255) NOT NULL, INDEX IDX_DB0A5ED2D262AF09 (resume_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, formateur VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, adresse VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, tel DOUBLE PRECISION NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_404021BF12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre_emploi (id INT AUTO_INCREMENT NOT NULL, id_candidat_id INT DEFAULT NULL, id_recruteur_id INT DEFAULT NULL, categorie_id INT NOT NULL, titre VARCHAR(20) NOT NULL, poste VARCHAR(30) NOT NULL, description VARCHAR(255) DEFAULT NULL, date_debut DATE NOT NULL, date_expiration DATE NOT NULL, max_salary INT DEFAULT NULL, min_salary INT DEFAULT NULL, location VARCHAR(50) DEFAULT NULL, file VARCHAR(255) DEFAULT NULL, email VARCHAR(50) NOT NULL, INDEX IDX_132AD0D110C22675 (id_candidat_id), INDEX IDX_132AD0D198C92C83 (id_recruteur_id), INDEX IDX_132AD0D1BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, total_payment DOUBLE PRECISION NOT NULL, state TINYINT(1) NOT NULL, date VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_cart (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, idOrder INT NOT NULL, idProduct INT NOT NULL, UNIQUE INDEX UNIQ_864BAA16E2EDD085 (idOrder), UNIQUE INDEX UNIQ_864BAA16C3F36F5F (idProduct), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, ref VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(60) NOT NULL, password VARCHAR(64) NOT NULL, is_active TINYINT(1) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', professionaltitle VARCHAR(20) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, token VARCHAR(255) DEFAULT NULL, date_of_birth DATETIME NOT NULL, lng DOUBLE PRECISION DEFAULT NULL, lat DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, activated_at DATETIME DEFAULT NULL, phone INT DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, companyname VARCHAR(255) DEFAULT NULL, contactemail VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, foundeddate DATE DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, contactphone INT DEFAULT NULL, companyadress VARCHAR(255) DEFAULT NULL, facebooklink VARCHAR(255) DEFAULT NULL, twitterlink VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidate_resume ADD CONSTRAINT FK_D4F28E7CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D75D262AF09 FOREIGN KEY (resume_id) REFERENCES candidate_resume (id)');
        $this->addSql('ALTER TABLE demande_recrutement ADD CONSTRAINT FK_84CC7C6D4CC8505A FOREIGN KEY (offre_id) REFERENCES offre_emploi (id)');
        $this->addSql('ALTER TABLE demande_recrutement ADD CONSTRAINT FK_84CC7C6D8D0EB82 FOREIGN KEY (candidat_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE education ADD CONSTRAINT FK_DB0A5ED2D262AF09 FOREIGN KEY (resume_id) REFERENCES candidate_resume (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE offre_emploi ADD CONSTRAINT FK_132AD0D110C22675 FOREIGN KEY (id_candidat_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offre_emploi ADD CONSTRAINT FK_132AD0D198C92C83 FOREIGN KEY (id_recruteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE offre_emploi ADD CONSTRAINT FK_132AD0D1BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA16E2EDD085 FOREIGN KEY (idOrder) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA16C3F36F5F FOREIGN KEY (idProduct) REFERENCES products (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D75D262AF09');
        $this->addSql('ALTER TABLE education DROP FOREIGN KEY FK_DB0A5ED2D262AF09');
        $this->addSql('ALTER TABLE offre_emploi DROP FOREIGN KEY FK_132AD0D1BCF5E72D');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF12469DE2');
        $this->addSql('ALTER TABLE demande_recrutement DROP FOREIGN KEY FK_84CC7C6D4CC8505A');
        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA16E2EDD085');
        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA16C3F36F5F');
        $this->addSql('ALTER TABLE candidate_resume DROP FOREIGN KEY FK_D4F28E7CA76ED395');
        $this->addSql('ALTER TABLE demande_recrutement DROP FOREIGN KEY FK_84CC7C6D8D0EB82');
        $this->addSql('ALTER TABLE offre_emploi DROP FOREIGN KEY FK_132AD0D110C22675');
        $this->addSql('ALTER TABLE offre_emploi DROP FOREIGN KEY FK_132AD0D198C92C83');
        $this->addSql('DROP TABLE candidate_resume');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE certification');
        $this->addSql('DROP TABLE demande_recrutement');
        $this->addSql('DROP TABLE education');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE offre_emploi');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE product_cart');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE user');
    }
}
