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

    public function getUser(string $name)
    {
        $this->logger->info("Recherche d'un utilisateur");
        $repo = $this->em->getRepository(user::class);
        $user = $repo->findOneBy(
            array('name_user' => $name)
        );
        if ($user !== null) {
            $this->logger->info("Utilisateur trouvé :" . $user->getIdUser());
        } else {
            $this->logger->info("Utilisateur inconnu");
        }
        return $user;
    }

    public function getUserId(int $id)
    {
        $this->logger->info("Recherche d'un utilisateur");
        $repo = $this->em->getRepository(user::class);
        $user = $repo->findOneBy(
            array('id_user' => $id)
        );
        if ($user !== null) {
            $this->logger->info("Utilisateur trouvé :" . $user->getIdUser());
        } else {
            $this->logger->info("Utilisateur inconnu");
        }
        return $user;
    }


    public function getPassword(string $name)
    {   
        $user = $this->getUser($name);
        $password = $user->getPasswordUser();
        return $password;
    }
    

    public function newUser($name, $password, $sexeUser)
    {
        $newuser = new user($name, $password, $sexeUser);
        $this->em->persist($newuser);
        $this->em->flush();
    }

    public function addGalleries($user, $gallery)
    {
        $user = $this->getUser($user);
        if($user !== null){
            $user->addGallery($gallery);
            $this->em->persist($user);
            $this->em->flush();
            return "galerie ajouté a l utilisateur";
        }
        return "utilisateur inconnu";
    }
}
