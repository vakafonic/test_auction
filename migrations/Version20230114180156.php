<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230114180156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE auction_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE auction_position_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE bid_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE buyer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE auction (id INT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE auction_position (id INT NOT NULL, auction_id INT NOT NULL, winner_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, reserve_price NUMERIC(12, 2) NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, completed TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F3A43A1B57B8F0DE ON auction_position (auction_id)');
        $this->addSql('CREATE INDEX IDX_F3A43A1B5DFCD4B8 ON auction_position (winner_id)');
        $this->addSql('CREATE TABLE bid (id INT NOT NULL, buyer_id INT NOT NULL, value NUMERIC(12, 2) NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4AF2B3F36C755722 ON bid (buyer_id)');
        $this->addSql('CREATE TABLE buyer (id INT NOT NULL, full_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE auction_position ADD CONSTRAINT FK_F3A43A1B57B8F0DE FOREIGN KEY (auction_id) REFERENCES auction (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE auction_position ADD CONSTRAINT FK_F3A43A1B5DFCD4B8 FOREIGN KEY (winner_id) REFERENCES buyer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F36C755722 FOREIGN KEY (buyer_id) REFERENCES buyer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE auction_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE auction_position_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE bid_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE buyer_id_seq CASCADE');
        $this->addSql('ALTER TABLE auction_position DROP CONSTRAINT FK_F3A43A1B57B8F0DE');
        $this->addSql('ALTER TABLE auction_position DROP CONSTRAINT FK_F3A43A1B5DFCD4B8');
        $this->addSql('ALTER TABLE bid DROP CONSTRAINT FK_4AF2B3F36C755722');
        $this->addSql('DROP TABLE auction');
        $this->addSql('DROP TABLE auction_position');
        $this->addSql('DROP TABLE bid');
        $this->addSql('DROP TABLE buyer');
    }
}
