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
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\OneToMany;

use function PHPSTORM_META\type;

#[Entity, Table(name: 'user')]
final class user{

    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $id_user;

    #[Column(type: 'string', nullable: false)]
    private string $name_user;

    #[Column(type: 'string', nullable: false)]
    private string $password_user;

    #[ManyToMany(targetEntity: 'gallery', inversedBy:'users', cascade:["persist"])]
    #[JoinTable(name: 'userGallery')]
    #[JoinColumn(name: 'id_user', referencedColumnName: 'id_user')]
    #[InverseJoinColumn(name: 'id_gallery' , referencedColumnName: 'id_gallery')]
    private Collection $galleries;

    public function __construct($nameUser,$passwordUser)
    {
        $this->galleries = new ArrayCollection();
        $this->name_user = $nameUser;
        $this->password_user = $passwordUser; 
    } 

    public function getGalleries(){
        return $this->galleries;
    }

    public function __toString()
    {
        return $this->name_user;
    }

    public function getIdUser(): int
    {
        return $this->id_user;
    }

    public function getNameUser(): string
    {
        return $this->name_user;
    }

    public function getPasswordUser(): string
    {
        return $this->password_user;
    }

    public function setIdUser($idUser)
    {
        $this->id_user = $idUser;
    }

    public function setNameUser($nameUser)
    {
        $this->name_user = $nameUser;
    }

    public function setPasswordUser($passwordUser)
    {
        $this->password_user = $passwordUser;
    }
}
