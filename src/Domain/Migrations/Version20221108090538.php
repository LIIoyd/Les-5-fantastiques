<?php

declare(strict_types=1);

namespace App\Domain\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221108090538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user (
            id_user INT AUTO_INCREMENT NOT NULL, 
            name_user VARCHAR(255) NOT NULL, 
            password_user VARCHAR(255) NOT NULL,
            PRIMARY KEY(id_user))'
            );

        $this->addSql('CREATE TABLE gallery (
            id_gallery INT AUTO_INCREMENT NOT NULL, 
            name_gallery VARCHAR(255) NOT NULL, 
            acces_type VARCHAR(255) NOT NULL,
            description_gallery VARCHAR(255) DEFAULT NULL,
            PRIMARY KEY(id_gallery))'
            );

        $this->addSql('CREATE TABLE picture (
            id_picture INT AUTO_INCREMENT NOT NULL, 
            title VARCHAR(255) NOT NULL, 
            height INT DEFAULT NULL,
            width INT DEFAULT NULL,
            description_picture VARCHAR(255) DEFAULT NULL,
            PRIMARY KEY(id_picture))'
            );

        $this->addSql('CREATE TABLE tag (
            id_tag INT AUTO_INCREMENT NOT NULL, 
            name_tag VARCHAR(255) NOT NULL, 
            PRIMARY KEY(id_tag))'
            );
        
        $this->addSql('CREATE TABLE userGallery(
            id_gallery INT NOT NULL, 
            id_user INT NOT NULL,
            FOREIGN KEY (id_gallery) REFERENCES gallery(id_gallery),
            FOREIGN KEY (id_user) REFERENCES user(id_user))'
            );
   

        $this->addSql('CREATE TABLE galleryTag (
            id_gallery INT REFERENCES gallery(id_gallery),  
            id_tag INT REFERENCES tag(id_tag),
            FOREIGN KEY (id_gallery) REFERENCES gallery(id_gallery),
            FOREIGN KEY (id_tag) REFERENCES tag(id_tag))'
            );

        $this->addSql('CREATE TABLE pictureTag (
            id_picture INT REFERENCES picture(id_picture),  
            id_tag INT REFERENCES tag(id_tag),
            FOREIGN KEY (id_picture) REFERENCES picture(id_picture),
            FOREIGN KEY (id_tag) REFERENCES tag(id_tag))'
            );

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE userGallery');
        $this->addSql('DROP TABLE galleryTag');
        $this->addSql('DROP TABLE pictureTag');

        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE gallery');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE tag');
    }
}
