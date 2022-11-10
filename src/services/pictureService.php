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



}