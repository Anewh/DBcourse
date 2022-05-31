<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220530215124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, genre VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(4048) DEFAULT NULL, poster_url VARCHAR(255) DEFAULT NULL, pdf_url VARCHAR(255) DEFAULT NULL, author VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE librarian (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, library_id INT DEFAULT NULL, post VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_3DB7FC74A76ED395 (user_id), INDEX IDX_3DB7FC74FE2541D7 (library_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE library (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE library_book (library_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_6D2A695CFE2541D7 (library_id), INDEX IDX_6D2A695C16A2B381 (book_id), PRIMARY KEY(library_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, book_id INT DEFAULT NULL, library_id INT NOT NULL, status VARCHAR(255) NOT NULL, book_format VARCHAR(255) NOT NULL, date_create DATE NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), INDEX IDX_F529939816A2B381 (book_id), INDEX IDX_F5299398FE2541D7 (library_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reader (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, birthday DATE DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_CC3F893CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, patronimic VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE librarian ADD CONSTRAINT FK_3DB7FC74A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE librarian ADD CONSTRAINT FK_3DB7FC74FE2541D7 FOREIGN KEY (library_id) REFERENCES library (id)');
        $this->addSql('ALTER TABLE library_book ADD CONSTRAINT FK_6D2A695CFE2541D7 FOREIGN KEY (library_id) REFERENCES library (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE library_book ADD CONSTRAINT FK_6D2A695C16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES reader (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F529939816A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398FE2541D7 FOREIGN KEY (library_id) REFERENCES library (id)');
        $this->addSql('ALTER TABLE reader ADD CONSTRAINT FK_CC3F893CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE library_book DROP FOREIGN KEY FK_6D2A695C16A2B381');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F529939816A2B381');
        $this->addSql('ALTER TABLE librarian DROP FOREIGN KEY FK_3DB7FC74FE2541D7');
        $this->addSql('ALTER TABLE library_book DROP FOREIGN KEY FK_6D2A695CFE2541D7');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398FE2541D7');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE librarian DROP FOREIGN KEY FK_3DB7FC74A76ED395');
        $this->addSql('ALTER TABLE reader DROP FOREIGN KEY FK_CC3F893CA76ED395');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE librarian');
        $this->addSql('DROP TABLE library');
        $this->addSql('DROP TABLE library_book');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE reader');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
