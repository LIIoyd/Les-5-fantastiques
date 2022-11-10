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

    public function getTag($name_tag)
    {
        $this->logger->info("Recherche d'un tag");
        $repo = $this->em->getRepository(tag::class);
        $tag = $repo->findOneBy(
            array('name_tag' => $name_tag)
        );
        if ($tag !== null) {
            $this->logger->info("Tag trouvé :" . $tag->getIdTag());
        } else {
            $this->logger->info("Ce tag n'existe pas !");
        }
        return $tag;
    }

}