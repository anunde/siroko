<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241217232351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (cart_id CHAR(36) NOT NULL, cart_customer_id CHAR(36) NOT NULL, cart_created_at DATETIME NOT NULL, PRIMARY KEY(cart_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart_item (cart_item_id CHAR(36) NOT NULL, cart_item_cart_id CHAR(36) NOT NULL, cart_item_sku VARCHAR(255) NOT NULL COMMENT \'(DC2Type:sku)\', cart_item_price VARCHAR(255) NOT NULL COMMENT \'(DC2Type:money)\', cart_item_quantity INT NOT NULL COMMENT \'(DC2Type:qty)\', INDEX IDX_F0FE252767077982 (cart_item_cart_id), PRIMARY KEY(cart_item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE252767077982 FOREIGN KEY (cart_item_cart_id) REFERENCES cart (cart_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE252767077982');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_item');
    }
}
