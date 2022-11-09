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



}