<?php
namespace App\controlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use App\services\userService;


class userControler
{
    private $view;

    public function __construct(Twig $view, userService $userService)
    {
      $this->view = $view;
      $this->userService = $userService;
    }

    public function createUser(ServerRequestInterface $request,ResponseInterface $response, array $args){
        $user = $this->userService->newUser($_POST['Name'],$_POST['Password']);

        return $this->view->render($response, 'app.twig');
    }

    public function getGallery(ServerRequestInterface $request,ResponseInterface $response, array $args){
        $user = $this->userService->getGallery('Léa');
        echo $user;
        return $this->view->render($response, 'app.twig');
    }
}