<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261020100058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE buyer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE card_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE order_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE order_item_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payment_method_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE buyers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE card_types_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE order_items_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE orders_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payment_methods_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE buyers (identity_guid VARCHAR(200) NOT NULL, name VARCHAR(255) DEFAULT NULL, id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_49E2BD62A5620636 ON buyers (identity_guid)');
        $this->addSql('CREATE TABLE card_types (name VARCHAR(200) NOT NULL, id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE order_items (product_id INT NOT NULL, discount NUMERIC(10, 2) NOT NULL, picture_url VARCHAR(255) DEFAULT NULL, product_name VARCHAR(255) NOT NULL, unit_price NUMERIC(10, 2) NOT NULL, units INT NOT NULL, id INT NOT NULL, order_id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_62809DB08D9F6D38 ON order_items (order_id)');
        $this->addSql('CREATE TABLE orders (order_status INT NOT NULL, description VARCHAR(255) DEFAULT NULL, order_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, id INT NOT NULL, address_street VARCHAR(255) DEFAULT NULL, address_city VARCHAR(255) DEFAULT NULL, address_state VARCHAR(255) DEFAULT NULL, address_country VARCHAR(255) DEFAULT NULL, address_zipcode VARCHAR(255) DEFAULT NULL, buyer_id INT DEFAULT NULL, payment_method_id INT DEFAULT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_E52FFDEE6C755722 ON orders (buyer_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE5AA1164F ON orders (payment_method_id)');
        $this->addSql('CREATE TABLE payment_methods (alias VARCHAR(200) NOT NULL, card_holder_name VARCHAR(200) NOT NULL, card_number VARCHAR(25) NOT NULL, expiration TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, id INT NOT NULL, buyer_id INT NOT NULL, card_type_id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_4FABF9836C755722 ON payment_methods (buyer_id)');
        $this->addSql('CREATE INDEX IDX_4FABF983925606E5 ON payment_methods (card_type_id)');
        $this->addSql('ALTER TABLE order_items ADD CONSTRAINT FK_62809DB08D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE6C755722 FOREIGN KEY (buyer_id) REFERENCES buyers (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE5AA1164F FOREIGN KEY (payment_method_id) REFERENCES payment_methods (id)');
        $this->addSql('ALTER TABLE payment_methods ADD CONSTRAINT FK_4FABF9836C755722 FOREIGN KEY (buyer_id) REFERENCES buyers (id)');
        $this->addSql('ALTER TABLE payment_methods ADD CONSTRAINT FK_4FABF983925606E5 FOREIGN KEY (card_type_id) REFERENCES card_types (id)');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT fk_f52993986c755722');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT fk_f52993985aa1164f');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT fk_52ea1f098d9f6d38');
        $this->addSql('ALTER TABLE payment_method DROP CONSTRAINT fk_7b61a1f66c755722');
        $this->addSql('ALTER TABLE payment_method DROP CONSTRAINT fk_7b61a1f6925606e5');
        $this->addSql('DROP TABLE buyer');
        $this->addSql('DROP TABLE card_type');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE payment_method');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE buyers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE card_types_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE order_items_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE orders_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payment_methods_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE buyer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE card_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE order_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE order_item_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payment_method_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE buyer (identity_guid VARCHAR(200) NOT NULL, name VARCHAR(255) DEFAULT NULL, id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_84905fb3a5620636 ON buyer (identity_guid)');
        $this->addSql('CREATE TABLE card_type (name VARCHAR(200) NOT NULL, id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE "order" (order_status INT NOT NULL, description VARCHAR(255) DEFAULT NULL, order_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, id INT NOT NULL, address_street VARCHAR(255) DEFAULT NULL, address_city VARCHAR(255) DEFAULT NULL, address_state VARCHAR(255) DEFAULT NULL, address_country VARCHAR(255) DEFAULT NULL, address_zipcode VARCHAR(255) DEFAULT NULL, buyer_id INT DEFAULT NULL, payment_method_id INT DEFAULT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX idx_f52993985aa1164f ON "order" (payment_method_id)');
        $this->addSql('CREATE INDEX idx_f52993986c755722 ON "order" (buyer_id)');
        $this->addSql('CREATE TABLE order_item (product_id INT NOT NULL, discount NUMERIC(10, 2) NOT NULL, picture_url VARCHAR(255) DEFAULT NULL, product_name VARCHAR(255) NOT NULL, unit_price NUMERIC(10, 2) NOT NULL, units INT NOT NULL, id INT NOT NULL, order_id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX idx_52ea1f098d9f6d38 ON order_item (order_id)');
        $this->addSql('CREATE TABLE payment_method (alias VARCHAR(200) NOT NULL, card_holder_name VARCHAR(200) NOT NULL, card_number VARCHAR(25) NOT NULL, expiration TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, id INT NOT NULL, buyer_id INT NOT NULL, card_type_id INT NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX idx_7b61a1f66c755722 ON payment_method (buyer_id)');
        $this->addSql('CREATE INDEX idx_7b61a1f6925606e5 ON payment_method (card_type_id)');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT fk_f52993986c755722 FOREIGN KEY (buyer_id) REFERENCES buyer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT fk_f52993985aa1164f FOREIGN KEY (payment_method_id) REFERENCES payment_method (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT fk_52ea1f098d9f6d38 FOREIGN KEY (order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_method ADD CONSTRAINT fk_7b61a1f66c755722 FOREIGN KEY (buyer_id) REFERENCES buyer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payment_method ADD CONSTRAINT fk_7b61a1f6925606e5 FOREIGN KEY (card_type_id) REFERENCES card_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_items DROP CONSTRAINT FK_62809DB08D9F6D38');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE6C755722');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE5AA1164F');
        $this->addSql('ALTER TABLE payment_methods DROP CONSTRAINT FK_4FABF9836C755722');
        $this->addSql('ALTER TABLE payment_methods DROP CONSTRAINT FK_4FABF983925606E5');
        $this->addSql('DROP TABLE buyers');
        $this->addSql('DROP TABLE card_types');
        $this->addSql('DROP TABLE order_items');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE payment_methods');
    }
}
