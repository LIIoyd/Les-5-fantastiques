<?php
namespace App\Domain;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'user')]
final class user{

    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $idUser;

    #[Column(type: 'string', nullable: false)]
    private int $nameUser;

    #[Column(type: 'string', nullable: false)]
    private string $passwordUser;

    public function __construct($nameUser,$passwordUser)
    {
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