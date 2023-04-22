<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230405095954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE under_product (id INT AUTO_INCREMENT NOT NULL, parent_product_id INT DEFAULT NULL, num VARCHAR(100) NOT NULL, INDEX IDX_A27ACD672C7E20A (parent_product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE under_product ADD CONSTRAINT FK_A27ACD672C7E20A FOREIGN KEY (parent_product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE image ADD under_product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FE55D2916 FOREIGN KEY (under_product_id) REFERENCES under_product (id)');
        $this->addSql('CREATE INDEX IDX_C53D045FE55D2916 ON image (under_product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FE55D2916');
        $this->addSql('ALTER TABLE under_product DROP FOREIGN KEY FK_A27ACD672C7E20A');
        $this->addSql('DROP TABLE under_product');
        $this->addSql('DROP INDEX IDX_C53D045FE55D2916 ON image');
        $this->addSql('ALTER TABLE image DROP under_product_id');
    }
}
