<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180113150320 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE airport ADD name VARCHAR(255) NOT NULL, ADD city VARCHAR(255) NOT NULL, ADD country VARCHAR(255) NOT NULL, ADD iata VARCHAR(3) NOT NULL, ADD icao VARCHAR(4) NOT NULL, ADD latitude NUMERIC(11, 8) NOT NULL, ADD longitude NUMERIC(10, 8) NOT NULL, ADD altitude INT NOT NULL, ADD timezone DOUBLE PRECISION NOT NULL, ADD dst VARCHAR(1) NOT NULL, ADD tz VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE airport DROP name, DROP city, DROP country, DROP iata, DROP icao, DROP latitude, DROP longitude, DROP altitude, DROP timezone, DROP dst, DROP tz');
    }
}
