<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180113161608 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE destination (id INT AUTO_INCREMENT NOT NULL, airport_id INT DEFAULT NULL, airline_id INT DEFAULT NULL, xpath VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_3EC63EAA289F53C8 (airport_id), INDEX IDX_3EC63EAA130D0C16 (airline_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE destination ADD CONSTRAINT FK_3EC63EAA289F53C8 FOREIGN KEY (airport_id) REFERENCES airport (id)');
        $this->addSql('ALTER TABLE destination ADD CONSTRAINT FK_3EC63EAA130D0C16 FOREIGN KEY (airline_id) REFERENCES airline (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE destination');
    }
}
