<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210330155044 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D75D262AF09');
        $this->addSql('ALTER TABLE education DROP FOREIGN KEY FK_DB0A5ED2D262AF09');
        $this->addSql('CREATE TABLE calendar (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, description LONGTEXT NOT NULL, all_day TINYINT(1) NOT NULL, background_color VARCHAR(7) NOT NULL, border_color VARCHAR(7) NOT NULL, text_color VARCHAR(7) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_recrutement (id INT AUTO_INCREMENT NOT NULL, offre_id INT NOT NULL, candidat_id INT DEFAULT NULL, date_debut DATE NOT NULL, dateexpiration DATE NOT NULL, status TINYINT(1) NOT NULL, INDEX IDX_84CC7C6D4CC8505A (offre_id), INDEX IDX_84CC7C6D8D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande_recrutement ADD CONSTRAINT FK_84CC7C6D4CC8505A FOREIGN KEY (offre_id) REFERENCES offre_emploi (id)');
        $this->addSql('ALTER TABLE demande_recrutement ADD CONSTRAINT FK_84CC7C6D8D0EB82 FOREIGN KEY (candidat_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE candidate_resume');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE certification');
        $this->addSql('DROP TABLE education');
        $this->addSql('DROP INDEX UNIQ_864BAA16C3F36F5F ON product_cart');
        $this->addSql('DROP INDEX UNIQ_864BAA16E2EDD085 ON product_cart');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_864BAA16C3F36F5F ON product_cart (idProduct)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_864BAA16E2EDD085 ON product_cart (idOrder)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE candidate_resume (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, resume_headline VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, skills TINYTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', experience VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_D4F28E7CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, couleur VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE certification (id INT AUTO_INCREMENT NOT NULL, resume_id INT DEFAULT NULL, label VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, image_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_6C3C6D75D262AF09 (resume_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE education (id INT AUTO_INCREMENT NOT NULL, resume_id INT NOT NULL, course VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, datefrom DATETIME NOT NULL, dateto DATETIME NOT NULL, institute VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_DB0A5ED2D262AF09 (resume_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE candidate_resume ADD CONSTRAINT FK_D4F28E7CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D75D262AF09 FOREIGN KEY (resume_id) REFERENCES candidate_resume (id)');
        $this->addSql('ALTER TABLE education ADD CONSTRAINT FK_DB0A5ED2D262AF09 FOREIGN KEY (resume_id) REFERENCES candidate_resume (id)');
        $this->addSql('DROP TABLE calendar');
        $this->addSql('DROP TABLE demande_recrutement');
        $this->addSql('DROP INDEX UNIQ_864BAA16E2EDD085 ON product_cart');
        $this->addSql('DROP INDEX UNIQ_864BAA16C3F36F5F ON product_cart');
        $this->addSql('CREATE INDEX UNIQ_864BAA16E2EDD085 ON product_cart (idOrder)');
        $this->addSql('CREATE INDEX UNIQ_864BAA16C3F36F5F ON product_cart (idProduct)');
    }
}
