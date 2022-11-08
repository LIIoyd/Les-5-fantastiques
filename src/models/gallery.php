<?php
namespace App\models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\InverseJoinColumn;

#[Entity]
class gallery
{
    // ...
    /**
     * Many Groups have Many Users.
     * @var Collection<int, User>
     */
    #[ManyToMany(targetEntity: User::class, mappedBy: 'gallery')]
    private Collection $users;

    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id_gallery;

    #[Column(type: 'string', nullable: false)]
    private String $name_gallery;

    #[Column(type: 'string', nullable: false)]
    private String $acces_type;

    #[Column(type: 'string', nullable: false)]
    private String $description_gallery;

    public function __construct($name_gallery,$acces_type,$description_gallery) {
        $this->users = new ArrayCollection();
        $this->name_gallery = $name_gallery;
        $this->acces_type = $acces_type;
        $this->description_gallery = $description_gallery;
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

}