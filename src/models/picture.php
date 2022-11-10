<?php
namespace App\models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'picture')]
final class picture{

    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id_picture;

    #[Column(type: 'string', nullable: false)]
    private string $link;

    #[Column(type: 'string', nullable: false)]
    private string $title;

    #[Column(type: 'integer', nullable: false)]
    private int $height;

    #[Column(type: 'integer', nullable: false)]
    private int $width;

    #[Column(type: 'string', nullable: false)]
    private string $description_picture;

    #[Column(type: 'integer', nullable: false)]
    private int $id_gallery;

    public function __construct($link,$title,$height,$width,$description,$id_gallery)
    {
        $this->link = $link;
        $this->title = $title;
        $this->height = $height; 
        $this->width = $width;
        $this->description_picture = $description;
        $this->id_gallery = $id_gallery;
    }

    public function getIdPicture(): int
    {
        return $this->id_picture;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getDescription(): string
    {
        return $this->description_picture;
    }

    public function getIdGallery(): int
    {
        return $this->id_gallery;
    }

    public function setIdPicture($idPicture)
    {
        $this->id_picture = $idPicture;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function setDescription($description)
    {
        $this->description_picture = $description;
    }

    public function setIdGallery($id_gallery)
    {
        $this->id_gallery = $id_gallery;
    }
}