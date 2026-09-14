<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260914145913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE buyer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE card_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE order_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE order_item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payment_method_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE buyer (identity_guid VARCHAR(200) NOT NULL, name VARCHAR(255) DEFAULT NULL, id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_84905FB3A5620636 ON buyer (identity_guid)');
        $this->addSql('CREATE TABLE card_type (name VARCHAR(200) NOT NULL, id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE "order" (order_status INT NOT NULL, description VARCHAR(255) DEFAULT NULL, order_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, id INT NOT NULL, address_street VARCHAR(255) DEFAULT NULL, address_city VARCHAR(255) DEFAULT NULL, address_state VARCHAR(255) DEFAULT NULL, address_country VARCHAR(255) DEFAULT NULL, address_zipcode VARCHAR(255) DEFAULT NULL, buyer_id INT DEFAULT NULL, payment_method_id INT DEFAULT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_F52993986C755722 ON "order" (buyer_id)');
        $this->addSql('CREATE INDEX IDX_F52993985AA1164F ON "order" (payment_method_id)');
        $this->addSql('CREATE TABLE order_item (product_id INT NOT NULL, discount NUMERIC(10, 2) NOT NULL, picture_url VARCHAR(255) DEFAULT NULL, product_name VARCHAR(255) NOT NULL, unit_price NUMERIC(10, 2) NOT NULL, units INT NOT NULL, id INT NOT NULL, order_id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_52EA1F098D9F6D38 ON order_item (order_id)');
        $this->addSql('CREATE TABLE payment_method (alias VARCHAR(200) NOT NULL, card_holder_name VARCHAR(200) NOT NULL, card_number VARCHAR(25) NOT NULL, expiration TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, id INT NOT NULL, buyer_id INT NOT NULL, card_type_id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_7B61A1F66C755722 ON payment_method (buyer_id)');
        $this->addSql('CREATE INDEX IDX_7B61A1F6925606E5 ON payment_method (card_type_id)');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F52993986C755722 FOREIGN KEY (buyer_id) REFERENCES buyer (id)');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F52993985AA1164F FOREIGN KEY (payment_method_id) REFERENCES payment_method (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id)');
        $this->addSql('ALTER TABLE payment_method ADD CONSTRAINT FK_7B61A1F66C755722 FOREIGN KEY (buyer_id) REFERENCES buyer (id)');
        $this->addSql('ALTER TABLE payment_method ADD CONSTRAINT FK_7B61A1F6925606E5 FOREIGN KEY (card_type_id) REFERENCES card_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE buyer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE card_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE order_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE order_item_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payment_method_id_seq CASCADE');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F52993986C755722');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F52993985AA1164F');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F098D9F6D38');
        $this->addSql('ALTER TABLE payment_method DROP CONSTRAINT FK_7B61A1F66C755722');
        $this->addSql('ALTER TABLE payment_method DROP CONSTRAINT FK_7B61A1F6925606E5');
        $this->addSql('DROP TABLE buyer');
        $this->addSql('DROP TABLE card_type');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE payment_method');
    }
}
