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

    public function getGalleries(ServerRequestInterface $request,ResponseInterface $response, array $args){
        $us = $this->userService->getUser('Léa');
        $userRes = $us->getGalleries();
        $text = "User: " . $us->getNameUser() . " <br> All galleries : ";
        foreach($userRes as $user){
            $text .= " " . $user;
        }
        echo  $text;
        return $this->view->render($response, 'app.twig');
    }
}