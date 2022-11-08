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

#[Entity, Table(name: 'user')]
final class user{

    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $idUser;

    #[Column(type: 'string', nullable: false)]
    private int $nameUser;

    #[Column(type: 'string', nullable: false)]
    private string $passwordUser;

    #[ManyToMany(targetEntity: gallery::class, inversedBy: 'users')]
    #[JoinTable(name: 'userGallery')]
    private Collection $gallery;

    public function __construct($nameUser,$passwordUser)
    {
        $this->gallery = new ArrayCollection();
        $this->nameUser = $nameUser;
        $this->passwordUser = $passwordUser; 
    }

    public function getIdUser(): int
    {
        return $this->idUser;
    }

    public function getNameUser(): string
    {
        return $this->nameUser;
    }

    public function getPasswordUser(): string
    {
        return $this->passwordUser;
    }
}
