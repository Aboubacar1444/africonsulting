<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210131034635 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidature (id INT AUTO_INCREMENT NOT NULL, emploi_id INT DEFAULT NULL, candidat_id INT DEFAULT NULL, istreated VARCHAR(10) NOT NULL, INDEX IDX_E33BD3B8EC013E12 (emploi_id), INDEX IDX_E33BD3B88D0EB82 (candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8EC013E12 FOREIGN KEY (emploi_id) REFERENCES emploi (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B88D0EB82 FOREIGN KEY (candidat_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE emploi CHANGE mission mission VARCHAR(2550) NOT NULL, CHANGE formation formation VARCHAR(2000) DEFAULT NULL, CHANGE experience experience VARCHAR(2000) DEFAULT NULL, CHANGE competence competence VARCHAR(2000) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE candidature');
        $this->addSql('ALTER TABLE emploi CHANGE mission mission VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE formation formation VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE experience experience VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE competence competence VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
