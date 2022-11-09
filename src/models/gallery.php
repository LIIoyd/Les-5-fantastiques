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

#[Entity, Table(name: 'gallery')]
class gallery
{
    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id_gallery;

    #[Column(type: 'string', nullable: false)]
    private String $name_gallery;

    #[Column(type: 'string', nullable: false)]
    private String $acces_type;

    #[Column(type: 'string', nullable: true)]
    private String $description_gallery;

    #[Column(type: 'integer', nullable: false)]
    private int $id_creator;

    #[ManyToMany(targetEntity: 'user', inversedBy:'gallerys', cascade:["persist"])]
    #[JoinTable(name: 'userGallery')]
    #[JoinColumn(name: 'id_gallery', referencedColumnName: 'id_gallery')]
    #[InverseJoinColumn(name: 'id_user' , referencedColumnName: 'id_user')]
    private Collection $users;

    public function __construct($name_gallery,$acces_type,$description_gallery,$id_creator) {
        $this->users = new ArrayCollection();
        $this->name_gallery = $name_gallery;
        $this->acces_type = $acces_type;
        $this->description_gallery = $description_gallery;
        $this->id_creator = $id_creator;
    }

    public function getUsers(){
        return $this->users;
    }

    public function __toString()
    {
        return $this->name_gallery;
    }

    public function getIdGallery(): int
    {
        return $this->id_gallery;
    }

    public function getNameGallery(): string
    {
        return $this->name_gallery;
    }

    public function getAccesType(): string
    {
        return $this->acces_type;
    }

    public function getDescriptionGallery(): string
    {
        return $this->description_gallery;
    }

    public function getIdCreator(): int
    {
        return $this->id_creator;
    }

    public function setIdGallery($id_gallery)
    {
        $this->id_gallery = $id_gallery;
    }

    public function setNameGallery($name_gallery)
    {
        $this->name_gallery = $name_gallery;
    }

    public function setAccesType($acces_type)
    {
        $this->acces_type = $acces_type;
    }

    public function setDescriptionGallery($description_gallery)
    {
        $this->description_gallery = $description_gallery;
    }

    public function setIdCreator($id_creator) 
    {
        $this->id_creator = $id_creator;
    }

}