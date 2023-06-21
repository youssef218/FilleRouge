<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230531224713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evenemant (id INT AUTO_INCREMENT NOT NULL, admin_id INT DEFAULT NULL, titre VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, debut_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', fin_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', creat_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_public TINYINT(1) NOT NULL, INDEX IDX_C2FC0C2642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evenemant ADD CONSTRAINT FK_C2FC0C2642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenemant DROP FOREIGN KEY FK_C2FC0C2642B8210');
        $this->addSql('DROP TABLE evenemant');
    }
}
