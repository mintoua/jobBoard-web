<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210227230405 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_cart (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, idOrder INT NOT NULL, idProduct INT NOT NULL, UNIQUE INDEX UNIQ_864BAA16E2EDD085 (idOrder), UNIQUE INDEX UNIQ_864BAA16C3F36F5F (idProduct), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA16E2EDD085 FOREIGN KEY (idOrder) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA16C3F36F5F FOREIGN KEY (idProduct) REFERENCES product (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE product_cart');
    }
}
