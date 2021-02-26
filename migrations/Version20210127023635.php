<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210127023635 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contrat (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, sigle VARCHAR(25) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE domaine (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_78AF0ACCBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emploi (id INT AUTO_INCREMENT NOT NULL, contrat_id INT DEFAULT NULL, localite_id INT DEFAULT NULL, pays_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, mission VARCHAR(255) NOT NULL, formation VARCHAR(255) DEFAULT NULL, experience VARCHAR(255) DEFAULT NULL, competence VARCHAR(255) DEFAULT NULL, type VARCHAR(25) NOT NULL, datepost VARCHAR(255) NOT NULL, datelimit VARCHAR(255) NOT NULL, societyname VARCHAR(255) NOT NULL, img VARCHAR(255) NOT NULL, INDEX IDX_74A0B0FA1823061F (contrat_id), INDEX IDX_74A0B0FA924DD2B5 (localite_id), INDEX IDX_74A0B0FAA6E44244 (pays_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE domaine ADD CONSTRAINT FK_78AF0ACCBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE emploi ADD CONSTRAINT FK_74A0B0FA1823061F FOREIGN KEY (contrat_id) REFERENCES contrat (id)');
        $this->addSql('ALTER TABLE emploi ADD CONSTRAINT FK_74A0B0FA924DD2B5 FOREIGN KEY (localite_id) REFERENCES localite (id)');
        $this->addSql('ALTER TABLE emploi ADD CONSTRAINT FK_74A0B0FAA6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domaine DROP FOREIGN KEY FK_78AF0ACCBCF5E72D');
        $this->addSql('ALTER TABLE emploi DROP FOREIGN KEY FK_74A0B0FA1823061F');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE contrat');
        $this->addSql('DROP TABLE domaine');
        $this->addSql('DROP TABLE emploi');
    }
}
