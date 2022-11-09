<?php
namespace App\controlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use App\services\pictureService;


class pictureControler
{
    private $view;

    public function __construct(Twig $view, pictureService $pictureService)
    {
      $this->view = $view;
      $this->pictureService = $pictureService;
    }


    public function start(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        echo " image";
        return $this->view->render($response, 'app.twig');
    }
}