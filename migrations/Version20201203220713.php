<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201203220713 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription() : string
    {
        return '';
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users CHANGE rating rating INT DEFAULT 0 NOT NULL, CHANGE exp exp INT DEFAULT 0 NOT NULL, CHANGE last_activity last_activity DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE premium premium TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users CHANGE rating rating INT NOT NULL, CHANGE exp exp INT NOT NULL, CHANGE last_activity last_activity DATETIME NOT NULL, CHANGE premium premium TINYINT(1) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL');
    }
}
