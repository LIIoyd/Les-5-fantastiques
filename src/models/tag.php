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

#[Entity, Table(name: 'tag')]
final class tag{    

    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id_tag;

    #[Column(type: 'string', nullable: false)]
    private string $name_tag;
 
    #[ManyToMany(targetEntity: 'gallery', inversedBy:'tags', cascade:["persist"])]
    #[JoinTable(name: 'galleryTag')]
    #[JoinColumn(name: 'id_tag', referencedColumnName: 'id_tag')]
    #[InverseJoinColumn(name: 'id_gallery' , referencedColumnName: 'id_gallery')]
    private Collection $gallerys; 

    #[ManyToMany(targetEntity: 'picture', inversedBy:'tags', cascade:["persist"])]
    #[JoinTable(name: 'pictureTag')]
    #[JoinColumn(name: 'id_tag', referencedColumnName: 'id_tag')]
    #[InverseJoinColumn(name: 'id_picture' , referencedColumnName: 'id_picture')]
    private Collection $pictures;

    public function __construct($tag)
    {
        $this->name_tag = $tag;
        $this->pictures = new ArrayCollection();
        $this->gallerys = new ArrayCollection();

    }

    public function addPictures($picture){
        $this->pictures->add($picture);
    }

    public function addGallery($gallery){
        $this->gallerys->add($gallery);
    }


    public function getPictures(){
        return $this->pictures;
    }

    public function getIdTag(): int
    {
        return $this->id_tag;
    }

    public function getTag(): string
    {
        return $this->name_tag;
    }

    public function setIdTag($idTag)
    {
        $this->id_tag = $idTag;
    }

    public function setTag($tag)
    {
        $this->name_tag = $tag;
    }
}