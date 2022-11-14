<?php

namespace App\services;

use Doctrine\ORM\EntityManager;
use App\models\tag;
use Psr\Log\LoggerInterface;

final class tagService
{
    private EntityManager $em;

    public function __construct(EntityManager $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function getTag($name_tag){
        $this->logger->info("Recherche d'un tag");
        $repo = $this->em->getRepository(tag::class);
        $tag = $repo->findOneBy(
            array('name_tag' => $name_tag)
        );
        return $tag;
    }

    public function newtag($name_tag , $Pictures){
            $tag = $this->getTag($name_tag);
            if($tag !== null){
                $tag->addPictures($Pictures);
                $this->em->persist($tag);
                $this->em->flush();
                return $tag;
            }
            $newtag = new tag($name_tag);
            $newtag->addPictures($Pictures);
            $this->em->persist($newtag);
            $this->em->flush();
            $this->logger->info("Un tag a été créée");
    }


    public function newTagGallery($name_tag , $Gallery){
            $tag = $this->getTag($name_tag);
            if($tag !== null){
                $tag->addGallery($Gallery);
                $this->em->persist($tag);
                $this->em->flush();
                return $tag;
            }
            $newtag = new tag($name_tag);
            $newtag->addGallery($Gallery);
            $this->em->persist($newtag);
            $this->em->flush();
            $this->logger->info("Un tag a été créée");
    }

    public function getTagGalleries($name_tag)
    {
        $this->logger->info("Recherche d'un tag");
        $repo = $this->em->getRepository(tag::class);
        $tag = $repo->findOneBy(
            array('name_tag' => $name_tag)
        );
        if ($tag!=null) {
          return $tag->getGallerys();
        } else {
          return [];
        }
    }

    public function getIdGalleriesByTagId($id_tag) {
      $this->logger->info("Recherche d'une gallerie");
      $repo = $this->em->getRepository(galleryTag::class);
      $tag = $repo->findBy(
          array('id_tag' => $id_tag)
      );
      return $tag;
    }

}
