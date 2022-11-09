<?php

namespace App\services;

use Doctrine\ORM\EntityManager;
use App\models\user;
use Psr\Log\LoggerInterface;

final class userService
{
    private EntityManager $em;

    public function __construct(EntityManager $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function getUser(string $name){
        $this->logger->info("Recherche d'un utilisateur");
        $repo = $this->em->getRepository(user::class);
        $user = $repo->findOneBy(
            array('name_user'=>$name)
        );
        if($user !== null){
            $this->logger->info("Utilisateur trouvé :".$user->getIdUser());
        }else{
            $this->logger->info("Utilisateur inconnu");
        }
        return $user;
    }

    public function newUser($name,$password){
        $reserch = $this->getUser($name);
        if($reserch == null){
            $newuser = new user($name,$password);
            $this->em->persist($newuser);
            $this->em->flush();
            $this->logger->info("Un utilisateur à été créer");
            echo "Un utilisateur à été créer";
        }else{
            $this->logger->info("L'utilisateur existe déjà");
            echo "Un utilisateur existe déjà";
        }
    }

    public function getGallery($name){
        $reserch = $this->getUser($name);
        $reserch->getGallery();
        return $reserch;
    }
}