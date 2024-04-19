<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240418162047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profile_image DROP FOREIGN KEY FK_32E99B8DA76ED395');
        $this->addSql('DROP INDEX UNIQ_32E99B8DA76ED395 ON profile_image');
        $this->addSql('ALTER TABLE profile_image DROP user_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649C4CF44DC');
        $this->addSql('DROP INDEX UNIQ_8D93D649C4CF44DC ON user');
        $this->addSql('ALTER TABLE user DROP profile_image_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` ADD profile_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649C4CF44DC FOREIGN KEY (profile_image_id) REFERENCES profile_image (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649C4CF44DC ON `user` (profile_image_id)');
        $this->addSql('ALTER TABLE profile_image ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE profile_image ADD CONSTRAINT FK_32E99B8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_32E99B8DA76ED395 ON profile_image (user_id)');
    }
}
