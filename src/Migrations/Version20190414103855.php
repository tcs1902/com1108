<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190414103855 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE book ADD seller_id INT NOT NULL, ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A3318DE820D9 FOREIGN KEY (seller_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A3317E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CBE5A3318DE820D9 ON book (seller_id)');
        $this->addSql('CREATE INDEX IDX_CBE5A3317E3C61F9 ON book (owner_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A3318DE820D9');
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A3317E3C61F9');
        $this->addSql('DROP INDEX IDX_CBE5A3318DE820D9 ON book');
        $this->addSql('DROP INDEX IDX_CBE5A3317E3C61F9 ON book');
        $this->addSql('ALTER TABLE book DROP seller_id, DROP owner_id');
    }
}
