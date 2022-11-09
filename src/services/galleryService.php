<?php

namespace App\services;

use Doctrine\ORM\EntityManager;
use App\models\gallery;
use Psr\Log\LoggerInterface;

final class galleryService
{
    private EntityManager $em;

    public function __construct(EntityManager $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function getGallery(string $name){
        $this->logger->info("Recherche d'une galerie");
        $repo = $this->em->getRepository(gallery::class);
        $gallery = $repo->findOneBy(
            array('name_gallery'=>$name)
        );
        if($gallery !== null){
            $this->logger->info("Galerie trouvé :".$gallery->getIdGallery());
        }else{
            $this->logger->info("Galerie inconnu");
        }
        return $gallery;
    }

    public function newGallery($name,$acces_type,$description_gallery,$id_creator){
        $reserch = $this->getGallery($name);
        if($reserch == null){
            $newgallery = new gallery($name,$acces_type,$description_gallery,$id_creator);
            $this->em->persist($newgallery);
            $this->em->flush();
            $this->logger->info("Une galerie à été créer");
            echo "Une galerie à été créer";
        }else{
            $this->logger->info("La galerie existe déjà");
            echo "La galerie existe déjà";
        }
    }

}
