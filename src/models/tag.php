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
    private int $id_tag;

    #[Column(type: 'string', nullable: false)]
    private string $name_tag;

    public function __construct($tag)
    {
        $this->name_tag = $tag;
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