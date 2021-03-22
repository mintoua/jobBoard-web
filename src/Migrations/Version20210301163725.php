<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210301163725 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_cart ADD idOrder INT NOT NULL, ADD idProduct INT NOT NULL, DROP id_order, DROP id_product');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA16E2EDD085 FOREIGN KEY (idOrder) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA16C3F36F5F FOREIGN KEY (idProduct) REFERENCES products (id)');
        $this->addSql('CREATE INDEX UNIQ_864BAA16E2EDD085 ON product_cart (idOrder)');
        $this->addSql('CREATE INDEX UNIQ_864BAA16C3F36F5F ON product_cart (idProduct)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA16E2EDD085');
        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA16C3F36F5F');
        $this->addSql('DROP INDEX UNIQ_864BAA16E2EDD085 ON product_cart');
        $this->addSql('DROP INDEX UNIQ_864BAA16C3F36F5F ON product_cart');
        $this->addSql('ALTER TABLE product_cart ADD id_order INT NOT NULL, ADD id_product INT NOT NULL, DROP idOrder, DROP idProduct');
    }
}
