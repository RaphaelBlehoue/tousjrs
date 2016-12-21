<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161221125128 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE format (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, online TINYINT(1) DEFAULT NULL, draft TINYINT(1) DEFAULT NULL, slug VARCHAR(128) NOT NULL, created DATETIME NOT NULL, UNIQUE INDEX UNIQ_DEBA72DF989D9B62 (slug), INDEX IDX_DEBA72DF126F525E (item_id), INDEX IDX_DEBA72DFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE format ADD CONSTRAINT FK_DEBA72DF126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE format ADD CONSTRAINT FK_DEBA72DFA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE media ADD format_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CD629F605 FOREIGN KEY (format_id) REFERENCES format (id)');
        $this->addSql('CREATE INDEX IDX_6A2CA10CD629F605 ON media (format_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CD629F605');
        $this->addSql('DROP TABLE format');
        $this->addSql('DROP INDEX IDX_6A2CA10CD629F605 ON media');
        $this->addSql('ALTER TABLE media DROP format_id');
    }
}
