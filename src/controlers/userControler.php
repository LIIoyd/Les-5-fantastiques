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

    public function display(ResponseInterface $response){
        //$user = $this->userService->newUser('léa','test');

        return $this->view->render($response, 'app.twig');
    }

    public function start(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface{
        $this->display($response);
        return $response;
    }
}