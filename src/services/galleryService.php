<?php

namespace App\services;

use Doctrine\ORM\EntityManager;
use App\models\gallery;
use Psr\Log\LoggerInterface;
use App\models\picture;

final class galleryService
{
    private EntityManager $em;

    public function __construct(EntityManager $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function getGallery(string $name)
    {
        $this->logger->info("Recherche d'une galerie");
        $repo = $this->em->getRepository(gallery::class);
        $gallery = $repo->findOneBy(
            array('name_gallery' => $name)
        );
        if ($gallery !== null) {
            $this->logger->info("Galerie trouvé :" . $gallery->getIdGallery());
        } else {
            $this->logger->info("Galerie inconnu");
        }
        return $gallery;
    }

    public function getGalleryId(int $id)
    {
        $this->logger->info("Recherche d'une galerie");
        $repo = $this->em->getRepository(gallery::class);
        $gallery = $repo->findOneBy(
            array('id_gallery' => $id)
        );
        if ($gallery !== null) {
            $this->logger->info("Galerie trouvé :" . $gallery->getIdGallery());
        } else {
            $this->logger->info("Galerie inconnu");
        }
        return $gallery;
    }

    public function getGalleriesByPublicAccess()
    {
        $this->logger->info("Recherche de toutes les galeries publiques");
        $repo = $this->em->getRepository(gallery::class);
        return $repo->findBy(array('acces_type' => 'public'));
    }

    public function getPrivatesGalleries($galleries)
    {
        $this->logger->info("Recherche de toutes les galeries d'un utilisateur");
        $repo = $this->em->getRepository(gallery::class);

        $idGalleries = [];
        foreach ($galleries as &$id) {
            array_push($idGalleries, $repo->findOneBy(array('id_gallery' => $id)));
        }
        return $idGalleries;
    }

    public function newGallery($name, $acces_type, $description_gallery, $id_creator)
    {
        $reserch = $this->getGallery($name);
        if ($reserch == null) {
            $newgallery = new gallery($name, $acces_type, $description_gallery, $id_creator);
            $this->em->persist($newgallery);
            $this->em->flush();
            $this->logger->info("Une galerie à été créer");
            return $newgallery;
        } else {
            $this->logger->info("La galerie existe déjà");
        }
        return  $reserch;
    }


    public function modifyGallery($id, $name, $acces_type, $description_gallery)
    {
        $reserch = $this->getGalleryId($id);

        $reserch->setNameGallery($name);
        $reserch->setAccesType($acces_type);
        $reserch->setDescriptionGallery($description_gallery);

        $this->em->persist($reserch);
        $this->em->flush();

        $this->logger->info("La galerie a été modifié");
        echo ("La galerie a été modifié");

        return  $reserch;
    }

    public function deleteGallery($gallery){
        $repo = $this->em->getRepository(gallery::class);
        $gal = $repo->findOneBy(
            array('id_gallery' => $gallery)
        );
        $repository = $this->em->getRepository(picture::class);

        $products = $repository->findBy(
          ['id_gallery' => $gallery],
        );
        
        if($gal !== null){
            foreach($products as $product){
                $this->em->remove($product);
            }
            $this->em->remove($gal);
            $this->em->flush();
            $this->logger->info("Une image a été supprimée");
            return "image supprimée";
        }
        return "erreur de suppression";
    }
}
