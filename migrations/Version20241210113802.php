<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241210113802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product CHANGE folder_id folder_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD162CB942 FOREIGN KEY (folder_id) REFERENCES easy_media__folder (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD162CB942 ON product (folder_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD162CB942');
        $this->addSql('DROP INDEX UNIQ_D34A04AD162CB942 ON product');
        $this->addSql('ALTER TABLE product CHANGE folder_id folder_id INT NOT NULL');
    }
}
