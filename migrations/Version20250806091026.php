<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250806091026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Drop quantity column from ps_product_attribute table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ps_product_attribute DROP COLUMN quantity');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ps_product_attribute ADD quantity INT NOT NULL');
    }
}
