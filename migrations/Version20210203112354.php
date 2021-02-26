<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210203112354 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emploi ADD recruteur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE emploi ADD CONSTRAINT FK_74A0B0FABB0859F1 FOREIGN KEY (recruteur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_74A0B0FABB0859F1 ON emploi (recruteur_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE emploi DROP FOREIGN KEY FK_74A0B0FABB0859F1');
        $this->addSql('DROP INDEX IDX_74A0B0FABB0859F1 ON emploi');
        $this->addSql('ALTER TABLE emploi DROP recruteur_id');
    }
}
