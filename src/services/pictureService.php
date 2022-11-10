<?php

namespace App\services;

use Doctrine\ORM\EntityManager;
use App\models\picture;
use Psr\Log\LoggerInterface;

final class pictureService
{
    private EntityManager $em;

    public function __construct(EntityManager $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function newPicture($link,$title,$height,$width,$description,$id_gallery){
            $newpicture = new picture($link,$title,$height,$width,$description,$id_gallery);
            $this->em->persist($newpicture);
            $this->em->flush();
            $this->logger->info("Une image a été créée");
            return $newpicture;
    }

    public function getAllPicturesGallery($idgallery){
        $this->logger->info("Recherche des images d'un gallery");
        $repo = $this->em->getRepository(picture::class);
        $pictures = $repo->findBy(
            array('id_gallery' => $idgallery)
        );
        if ($pictures !== null) {
            $this->logger->info("Images trouvé");
        } else {
            $this->logger->info("Images non trouvé");
        }
        return $pictures;
    }

    public function DeletePicture($Picture){
        $pic = $this->em->find(picture::class, $Picture);
        if($pic !== null){
            $this->em->remove($pic);
            $this->em->flush();
            $this->logger->info("Une image a été supprimée");
            return "image supprimée";
        }
        return "erreur de suppression";
    }

}