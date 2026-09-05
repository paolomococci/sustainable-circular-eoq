<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260805080057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE items (
              id INT AUTO_INCREMENT NOT NULL,
              name VARCHAR(255) NOT NULL,
              price NUMERIC(10, 2) DEFAULT NULL,
              total_annual_purchase_cost NUMERIC(10, 2) DEFAULT NULL,
              total_annual_cost_of_issuing_orders NUMERIC(10, 2) DEFAULT NULL,
              total_annual_cost_of_maintenance_in_stock NUMERIC(10, 2) DEFAULT NULL,
              annual_demand NUMERIC(10, 2) DEFAULT NULL,
              order_issue_cost NUMERIC(10, 2) DEFAULT NULL,
              purchase_price NUMERIC(10, 2) DEFAULT NULL,
              annual_interest_rate NUMERIC(10, 2) DEFAULT NULL,
              supply_lead_time SMALLINT DEFAULT NULL,
              economic_order_quantity NUMERIC(5, 2) DEFAULT NULL,
              description LONGTEXT DEFAULT NULL,
              in_assortment TINYINT DEFAULT NULL,
              in_stock_out TINYINT DEFAULT NULL,
              notes LONGTEXT DEFAULT NULL,
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (
              id BIGINT AUTO_INCREMENT NOT NULL,
              body LONGTEXT NOT NULL,
              headers LONGTEXT NOT NULL,
              queue_name VARCHAR(190) NOT NULL,
              created_at DATETIME NOT NULL,
              available_at DATETIME NOT NULL,
              delivered_at DATETIME DEFAULT NULL,
              INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (
                queue_name, available_at, delivered_at,
                id
              ),
              PRIMARY KEY (id)
            ) DEFAULT CHARACTER SET utf8mb4
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE items');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
