<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221018125509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create document tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, current_file_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, file_extension VARCHAR(10) NOT NULL, author VARCHAR(255) NOT NULL, creation_date VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D8698A7639C6FCE9 (current_file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document_file (id INT AUTO_INCREMENT NOT NULL, document_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, filename VARCHAR(255) NOT NULL, mimetype VARCHAR(255) NOT NULL, original_filename VARCHAR(255) NOT NULL, checksum VARCHAR(255) NOT NULL, size INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, version_nr SMALLINT NOT NULL, INDEX IDX_2B2BBA83C33F7837 (document_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A7639C6FCE9 FOREIGN KEY (current_file_id) REFERENCES document_file (id)');
        $this->addSql('ALTER TABLE document_file ADD CONSTRAINT FK_2B2BBA83C33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A7639C6FCE9');
        $this->addSql('ALTER TABLE document_file DROP FOREIGN KEY FK_2B2BBA83C33F7837');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE document_file');
    }
}
