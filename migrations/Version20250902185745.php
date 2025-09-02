<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250902185745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create ps_lpcrm_coupon table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE ps_lpcrm_coupon (id_lpcrm_coupon INT AUTO_INCREMENT NOT NULL, id_cart_rule INT NOT NULL, not_combinable TINYINT(1) NOT NULL, online_only TINYINT(1) NOT NULL, date_add DATETIME NOT NULL, date_upd DATETIME NOT NULL, PRIMARY KEY(id_lpcrm_coupon)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE ps_lpcrm_coupon');
    }
}
