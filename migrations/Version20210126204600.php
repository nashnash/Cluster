<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210126204600 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_restriction (event_id INT NOT NULL, restriction_id INT NOT NULL, INDEX IDX_D110484E71F7E88B (event_id), INDEX IDX_D110484EE6160631 (restriction_id), PRIMARY KEY(event_id, restriction_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restriction (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_restriction ADD CONSTRAINT FK_D110484E71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_restriction ADD CONSTRAINT FK_D110484EE6160631 FOREIGN KEY (restriction_id) REFERENCES restriction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event DROP INDEX UNIQ_3BAE0AA7A76ED395, ADD INDEX IDX_3BAE0AA7A76ED395 (user_id)');
        $this->addSql('ALTER TABLE event ADD updated_at DATETIME NOT NULL, ADD price DOUBLE PRECISION NOT NULL, ADD updated_status DATETIME DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3BAE0AA7989D9B62 ON event (slug)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_restriction DROP FOREIGN KEY FK_D110484EE6160631');
        $this->addSql('DROP TABLE event_restriction');
        $this->addSql('DROP TABLE restriction');
        $this->addSql('ALTER TABLE event DROP INDEX IDX_3BAE0AA7A76ED395, ADD UNIQUE INDEX UNIQ_3BAE0AA7A76ED395 (user_id)');
        $this->addSql('DROP INDEX UNIQ_3BAE0AA7989D9B62 ON event');
        $this->addSql('ALTER TABLE event DROP updated_at, DROP price, DROP updated_status');
    }
}
