<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161213180620 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE content content LONGTEXT DEFAULT NULL, CHANGE online online TINYINT(1) DEFAULT NULL, CHANGE draft draft TINYINT(1) DEFAULT NULL, CHANGE has_video has_video TINYINT(1) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post CHANGE name name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE content content LONGTEXT NOT NULL COLLATE utf8_unicode_ci, CHANGE online online TINYINT(1) NOT NULL, CHANGE draft draft TINYINT(1) NOT NULL, CHANGE has_video has_video TINYINT(1) NOT NULL');
    }
}
