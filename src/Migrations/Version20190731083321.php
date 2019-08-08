<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190731083321 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE item_list_priority (item_list_id INT NOT NULL, priority_id INT NOT NULL, INDEX IDX_F067479C36F330DF (item_list_id), INDEX IDX_F067479C497B19F9 (priority_id), PRIMARY KEY(item_list_id, priority_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_list_priority ADD CONSTRAINT FK_F067479C36F330DF FOREIGN KEY (item_list_id) REFERENCES item_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_list_priority ADD CONSTRAINT FK_F067479C497B19F9 FOREIGN KEY (priority_id) REFERENCES priority (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE item CHANGE color_code color_code VARCHAR(255) DEFAULT NULL, CHANGE placement placement VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE item_list_priority');
        $this->addSql('ALTER TABLE item CHANGE color_code color_code VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE placement placement VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
