<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240320000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create products and orders tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE product (
            id INT AUTO_INCREMENT NOT NULL,
            title VARCHAR(255) NOT NULL,
            description LONGTEXT NOT NULL,
            price DECIMAL NOT NULL,
            photo_id INT DEFAULT NULL,
            PRIMARY KEY(id),
            FOREIGN KEY (photo_id) REFERENCES easy_media__media (id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE `order` (
            id INT AUTO_INCREMENT NOT NULL,
            first_name VARCHAR(255) NOT NULL,
            last_name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            phone VARCHAR(255) NOT NULL,
            address VARCHAR(255) NOT NULL,
            order_items JSON NOT NULL,
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS `order`');
        $this->addSql('DROP TABLE IF EXISTS product');
    }
}
