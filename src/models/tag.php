<?php
namespace App\models;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table(name: 'picture')]
final class tag{

    #[Id, Column(type: 'integer'), GeneratedValue(strategy: 'AUTO')]
    private int $idTag;

    #[Column(type: 'string', nullable: false)]
    private string $tag;

    public function __construct($tag)
    {
        $this->tag = $tag;
    }

    public function getIdTag(): int
    {
        return $this->idTag;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function setIdTag($idTag)
    {
        $this->idTag = $idTag;
    }

    public function setTag($tag)
    {
        $this->tag = $tag;
    }
}