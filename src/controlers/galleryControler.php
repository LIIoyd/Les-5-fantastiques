<?php
namespace App\controlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use App\services\galleryService;
use App\controlers\userControler;


class galleryControler
{
    private $view;

    public function __construct(Twig $view, galleryService $galleryService, userControler $userControler)
    {
        $this->view = $view;
        $this->galleryService = $galleryService;
        $this->userControler = $userControler;
    }

    public function newGallery(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->galleryService->newGallery('test','test','test',1);
        return $this->view->render($response, 'app.twig');
    }

    public function getAllPublicGalleries(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        return $this->view->render($response, 'index.twig', [
          'private' => false,
          'galleriesToShow' => $this->galleryService->getGalleriesByPublicAccess(),
        ]);
    }

    public function getMyGalleries(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        return $this->view->render($response, 'index.twig', [
          'private' => true,
          'galleriesToShow' => $this->galleryService->getPrivatesGalleries($this->userControler->getGalleries()),
        ]);
    }

    public function getGallery(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        $gal = $this->galleryService->getGallery('photo mignon');
        echo $gal;
        var_dump($gal->getUsers());
        return $this->view->render($response, 'app.twig');
    }

    public function getUsers(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        $gal = $this->galleryService->getGallery('test');
        $galRes = $gal->getUsers();
        $text = "Nom de galerie: " . $gal->getNameGallery() . " <br> Users : ";
        foreach($galRes as $galleries){
            $text .= " " . $galleries;
        }
        echo $text;
        return $this->view->render($response, 'app.twig');
    }
}
