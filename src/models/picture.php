<?php
namespace App\models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Symfony\Component\DependencyInjection\Reference;

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

    #[ManyToMany(targetEntity: 'tag', inversedBy:'picture', cascade:["persist"])]
    #[JoinTable(name: 'pictureTag')]
    #[JoinColumn(name: 'id_picture', referencedColumnName: 'id_picture')]
    #[InverseJoinColumn(name: 'id_tag' , referencedColumnName: 'id_tag')]
    private Collection $tags;

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

    public function getLink(): string
    {
        return $this->link;
    }

    public function getTags() 
    {
        return $this->tags->getValues();
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
