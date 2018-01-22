<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180113150612 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE airport CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL, CHANGE country country VARCHAR(255) DEFAULT NULL, CHANGE iata iata VARCHAR(3) DEFAULT NULL, CHANGE icao icao VARCHAR(4) DEFAULT NULL, CHANGE latitude latitude NUMERIC(11, 8) DEFAULT NULL, CHANGE longitude longitude NUMERIC(10, 8) DEFAULT NULL, CHANGE altitude altitude INT DEFAULT NULL, CHANGE timezone timezone DOUBLE PRECISION DEFAULT NULL, CHANGE dst dst VARCHAR(1) DEFAULT NULL, CHANGE tz tz VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE airport CHANGE name name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE city city VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE country country VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE iata iata VARCHAR(3) NOT NULL COLLATE utf8_unicode_ci, CHANGE icao icao VARCHAR(4) NOT NULL COLLATE utf8_unicode_ci, CHANGE latitude latitude NUMERIC(11, 8) NOT NULL, CHANGE longitude longitude NUMERIC(10, 8) NOT NULL, CHANGE altitude altitude INT NOT NULL, CHANGE timezone timezone DOUBLE PRECISION NOT NULL, CHANGE dst dst VARCHAR(1) NOT NULL COLLATE utf8_unicode_ci, CHANGE tz tz VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
