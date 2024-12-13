<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241204205444 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_media (product_id INT NOT NULL, media_id INT NOT NULL, INDEX IDX_CB70DA504584665A (product_id), INDEX IDX_CB70DA50EA9FDD75 (media_id), PRIMARY KEY(product_id, media_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_media ADD CONSTRAINT FK_CB70DA504584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_media ADD CONSTRAINT FK_CB70DA50EA9FDD75 FOREIGN KEY (media_id) REFERENCES easy_media__media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY product_ibfk_1');
        $this->addSql('DROP INDEX photo_id ON product');
        $this->addSql('ALTER TABLE product DROP photo_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_media DROP FOREIGN KEY FK_CB70DA504584665A');
        $this->addSql('ALTER TABLE product_media DROP FOREIGN KEY FK_CB70DA50EA9FDD75');
        $this->addSql('DROP TABLE product_media');
        $this->addSql('ALTER TABLE product ADD photo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT product_ibfk_1 FOREIGN KEY (photo_id) REFERENCES easy_media__media (id)');
        $this->addSql('CREATE INDEX photo_id ON product (photo_id)');
    }
}
