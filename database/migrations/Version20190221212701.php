<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190221212701 extends AbstractMigration
{
    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     *
     * @return void
     */
    public function up(Schema $schema) : void
    {
        /*
        $this->addSql('
            CREATE TABLE `user` (
                `user_id` bigint(20) NOT NULL,
                `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
                `mail` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
                `password` varchar(70) COLLATE utf8_unicode_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
            
            ALTER TABLE `user` ADD PRIMARY KEY (`user_id`);
            ALTER TABLE `user` MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT;
        ');
        */
    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     *
     * @return void
     */
    public function down(Schema $schema) : void
    {
        /*
        $this->addSql('DROP TABLE user');
        */
    }
}
