<?php
namespace App\models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'picture')]
final class user{

    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $idPicture;

    #[Column(type: 'string', nullable: false)]
    private int $title;

    #[Column(type: 'integer', nullable: false)]
    private string $height;

    #[Column(type: 'integer', nullable: false)]
    private string $width;

    #[Column(type: 'string', nullable: false)]
    private string $description;

    public function __construct($title,$height,$width,$description)
    {
        $this->title = $title;
        $this->height = $height; 
        $this->width = $width;
        $this->description = $description;
    }

    public function getIdPicture(): int
    {
        return $this->idPicture;
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
        return $this->description;
    }

    public function setIdPicture($idPicture)
    {
        $this->idPicture = $idPicture;
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
        $this->description = $description;
    }
}