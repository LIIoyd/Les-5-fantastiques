<?php
namespace App\controlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use App\services\galleryService;


class galleryControler
{
    private $view;

    public function __construct(Twig $view, galleryService $galleryService)
    {
        $this->view = $view;
        $this->galleryService = $galleryService;
    }

    public function newGallery(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->galleryService->newGallery('test','test','test',1);
        return $this->view->render($response, 'app.twig');
    }

    public function getGallery(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        $gal = $this->galleryService->getGallery('test');
        echo $gal;
        return $this->view->render($response, 'app.twig');
    }
}
