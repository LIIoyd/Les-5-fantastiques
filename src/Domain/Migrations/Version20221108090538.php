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
            date_creat TIMESTAMP DEFAULT(CURRENT_TIMESTAMP),
            acces_type VARCHAR(255) NOT NULL,
            description_gallery VARCHAR(255) DEFAULT NULL,
            id_creator INT NOT NULL,
            PRIMARY KEY(id_gallery),
            FOREIGN KEY (id_creator) REFERENCES user(id_user))'
            );

        $this->addSql('CREATE TABLE picture (
            id_picture INT AUTO_INCREMENT NOT NULL,
            link VARCHAR(255) NOT NULL,
            title VARCHAR(255) NOT NULL,
            height INT DEFAULT NULL,
            width INT DEFAULT NULL,
            description_picture VARCHAR(255) DEFAULT NULL,
            id_gallery INT NOT NULL,
            PRIMARY KEY(id_picture),
            FOREIGN KEY (id_gallery) REFERENCES gallery(id_gallery))'
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

        $usersArray = array(
          array('name' => 'admin', 'password' => password_hash('admin', PASSWORD_DEFAULT)),
          array('name' => 'florianFerbach', 'password' => password_hash('coucou', PASSWORD_DEFAULT)),
          array('name' => 'geromeCanals', 'password' => password_hash('test', PASSWORD_DEFAULT))
        );
        foreach ($usersArray as $userObject) {
            $this->addSql("INSERT INTO user (name_user, password_user) VALUES (:name, :password)", $userObject);
        }
        $galleriesArray = array(
          array('name_gallery' => 'Galerie chiens', 'acces_type' => 'public', 'description_gallery' => 'Photos de chiens cute', 'id_creator' => '1'),
          array('name_gallery' => 'Lilian dans un parc', 'acces_type' => 'private', 'description_gallery' => 'Photos de lilian dans un parc hehe', 'id_creator' => '2'),
          array('name_gallery' => 'Loic le bg', 'acces_type' => 'private', 'description_gallery' => 'Photos de loic ce vrai beau-gosse de la night', 'id_creator' => '3')
        );
        foreach ($galleriesArray as $gallery) {
          $this->addSql("INSERT INTO gallery (name_gallery, acces_type, description_gallery, id_creator) VALUES (:name_gallery, :acces_type, :description_gallery, :id_creator)", $gallery);
        }
        $usersGalleriesArray = array(
          array('id_gallery' => '1', 'id_user' => '1'),
          array('id_gallery' => '1', 'id_user' => '2'),
          array('id_gallery' => '2', 'id_user' => '1'),
          array('id_gallery' => '2', 'id_user' => '3'),
          array('id_gallery' => '3', 'id_user' => '1'),
          array('id_gallery' => '3', 'id_user' => '2'),
          array('id_gallery' => '3', 'id_user' => '3')
        );
        foreach ($usersGalleriesArray as $userGallery) {
          $this->addSql("INSERT INTO userGallery (id_gallery, id_user) VALUES (:id_gallery, :id_user)", $userGallery);
        }
        $picturesArray = array(
          array('link' => 'uploads/IMG_20210906_171049.jpg', 'title' => 'chien 1', 'height' => 200, 'width' => 200, 'description_picture' => 'il est mignon lui', 'id_gallery' => 1),
          array('link' => 'uploads/pp.PNG', 'title' => 'chien 2', 'height' => 250, 'width' => 120, 'description_picture' => 'il est mignon lui aussi', 'id_gallery' => 1),
          array('link' => 'uploads/IMG_20210906_171049.jpg', 'title' => 'lilian 1', 'height' => 500, 'width' => 800, 'description_picture' => 'wow il est devant un conifere', 'id_gallery' => 2),
          array('link' => 'uploads/IMG_20210906_171049.jpg', 'title' => 'lilian 2', 'height' => 300, 'width' => 100, 'description_picture' => 'wow il est devant un sapin', 'id_gallery' => 2),
          array('link' => 'uploads/IMG_20210906_171049.jpg', 'title' => 'Loic 1', 'height' => 600, 'width' => 300, 'description_picture' => 'tah le bg', 'id_gallery' => 3)
        );
        foreach ($picturesArray as $picture) {
          $this->addSql("INSERT INTO picture (link, title, height, width, description_picture, id_gallery) VALUES (:link, :title, :height, :width, :description_picture, :id_gallery)", $picture);
        }
        $tagsArray = array(
          array('name_tag' => 'cute'),
          array('name_tag' => 'bg'),
          array('name_tag' => 'wtf'),
          array('name_tag' => 'picture')
        );
        foreach ($tagsArray as $tag) {
          $this->addSql("INSERT INTO tag (name_tag) VALUES (:name_tag)", $tag);
        }
        $galleryTagsArray = array(
          array('id_gallery' => '1', 'id_tag' => '1'),
          array('id_gallery' => '1', 'id_tag' => '4'),
          array('id_gallery' => '2', 'id_tag' => '3'),
          array('id_gallery' => '2', 'id_tag' => '4'),
          array('id_gallery' => '3', 'id_tag' => '2'),
          array('id_gallery' => '3', 'id_tag' => '4')
        );
        foreach ($galleryTagsArray as $galleryTag) {
          $this->addSql("INSERT INTO galleryTag (id_gallery, id_tag) VALUES (:id_gallery, :id_tag)", $galleryTag);
        }
        $pictureTagsArray = array(
          array('id_picture' => '1', 'id_tag' => '1'),
          array('id_picture' => '2', 'id_tag' => '2'),
          array('id_picture' => '3', 'id_tag' => '3'),
          array('id_picture' => '4', 'id_tag' => '4'),
          array('id_picture' => '5', 'id_tag' => '1'),
          array('id_picture' => '5', 'id_tag' => '2')
        );
        foreach ($pictureTagsArray as $pictureTag) {
          $this->addSql("INSERT INTO pictureTag (id_picture, id_tag) VALUES (:id_picture, :id_tag)", $pictureTag);
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE userGallery');
        $this->addSql('DROP TABLE galleryTag');
        $this->addSql('DROP TABLE pictureTag');

        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE gallery');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE tag');
    }
}
